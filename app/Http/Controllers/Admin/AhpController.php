<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Criterion;
use App\Models\Mahasiswa;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class AhpController extends Controller
{
    /**
     * Form Perbandingan Berpasangan
     */
    public function perbandingan()
    {
        // Ambil kriteria aktif
        $kriteria = Criterion::where('is_active', 1)->orderBy('order')->orderBy('id')->get();
        
        // Ambil data perbandingan yang sudah ada (jika ada tabel)
        $perbandingan = collect();
        
        if (Schema::hasTable('pairwise_comparisons')) {
            $perbandingan = DB::table('pairwise_comparisons')
                ->get()
                ->keyBy(function($item) {
                    return $item->criterion1_id . '_' . $item->criterion2_id;
                });
        }
        
        // Hitung statistik
        $totalPasangan = ($kriteria->count() * ($kriteria->count() - 1)) / 2;
        $terisi = $perbandingan->count() > 0 ? $perbandingan->count() / 2 : 0;
        $progress = $totalPasangan > 0 ? ($terisi / $totalPasangan) * 100 : 0;
        
        return view('admin.ahp.perbandingan', compact(
            'kriteria', 'perbandingan', 'totalPasangan', 'terisi', 'progress'
        ));
    }
    
    /**
     * Simpan Perbandingan
     */
    public function storePerbandingan(Request $request)
    {
        $request->validate([
            'pairs' => 'required|array',
            'pairs.*.krit1' => 'required|exists:criteria,id',
            'pairs.*.krit2' => 'required|exists:criteria,id',
            'pairs.*.nilai' => 'required|numeric|min:0.1|max:9',
        ]);
        
        try {
            // Pastikan tabel ada
            if (!Schema::hasTable('pairwise_comparisons')) {
                Schema::create('pairwise_comparisons', function ($table) {
                    $table->id();
                    $table->unsignedBigInteger('criterion1_id');
                    $table->unsignedBigInteger('criterion2_id');
                    $table->decimal('value', 10, 6);
                    $table->timestamps();
                    
                    $table->foreign('criterion1_id')->references('id')->on('criteria')->onDelete('cascade');
                    $table->foreign('criterion2_id')->references('id')->on('criteria')->onDelete('cascade');
                    $table->unique(['criterion1_id', 'criterion2_id']);
                });
            }
            
            // Hapus data lama (gunakan delete bukan truncate untuk menghindari masalah transaction)
            DB::table('pairwise_comparisons')->delete();
            
            // Simpan data baru
            foreach ($request->pairs as $pair) {
                $krit1 = $pair['krit1'];
                $krit2 = $pair['krit2'];
                $nilai = floatval($pair['nilai']);
                
                // Simpan pasangan (krit1, krit2) dengan nilai
                DB::table('pairwise_comparisons')->insert([
                    'criterion1_id' => $krit1,
                    'criterion2_id' => $krit2,
                    'value' => $nilai,
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);
                
                // Simpan pasangan kebalikan (krit2, krit1) dengan nilai 1/nilai
                DB::table('pairwise_comparisons')->insert([
                    'criterion1_id' => $krit2,
                    'criterion2_id' => $krit1,
                    'value' => 1 / $nilai,
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);
            }
            
            return redirect()->route('admin.ahp.perbandingan')
                ->with('success', 'Perbandingan berhasil disimpan! Silakan lanjut ke Hitung AHP.');
                
        } catch (\Exception $e) {
            return redirect()->back()
                ->with('error', 'Gagal menyimpan perbandingan: ' . $e->getMessage());
        }
    }
    
    /**
     * Dashboard AHP
     */
    public function index()
    {
        $kriteria = Criterion::where('is_active', 1)->get();
        $hasComparisons = Schema::hasTable('pairwise_comparisons') 
            ? DB::table('pairwise_comparisons')->count() > 0 
            : false;
            
        return view('admin.ahp.index', compact('kriteria', 'hasComparisons'));
    }
    
    /**
     * Form Hitung AHP
     */
    public function hitung()
    {
        $kriteria = Criterion::where('is_active', 1)->orderBy('order')->orderBy('id')->get();
        
        // Cek apakah ada data perbandingan
        $hasComparisons = Schema::hasTable('pairwise_comparisons') 
            ? DB::table('pairwise_comparisons')->count() > 0 
            : false;
        
        if (!$hasComparisons) {
            return redirect()->route('admin.ahp.perbandingan')
                ->with('error', 'Silakan isi perbandingan terlebih dahulu!');
        }
        
        // Hitung AHP
        $result = $this->calculateAHP($kriteria);
        
        return view('admin.ahp.hitung', compact('kriteria', 'result'));
    }
    
    /**
     * Proses Hitung AHP
     */
    public function prosesHitung(Request $request)
    {
        $kriteria = Criterion::where('is_active', 1)->orderBy('order')->orderBy('id')->get();
        
        // Hitung dan simpan bobot ke database
        $result = $this->calculateAHP($kriteria);
        
        if ($result['is_consistent']) {
            // Update bobot di tabel criteria
            foreach ($result['weights'] as $id => $weight) {
                Criterion::where('id', $id)->update(['weight' => $weight]);
            }
            
            return redirect()->route('admin.ahp.hasil')
                ->with('success', 'Perhitungan AHP berhasil! Bobot kriteria telah diperbarui.');
        } else {
            return redirect()->route('admin.ahp.hitung')
                ->with('error', 'Rasio Konsistensi (CR) melebihi 0.1. Silakan perbaiki perbandingan Anda.');
        }
    }
    
    /**
     * Calculate AHP
     */
    private function calculateAHP($kriteria)
    {
        $n = $kriteria->count();
        $ids = $kriteria->pluck('id')->toArray();
        
        // Buat matriks perbandingan
        $matrix = [];
        foreach ($ids as $i => $id1) {
            foreach ($ids as $j => $id2) {
                if ($id1 == $id2) {
                    $matrix[$i][$j] = 1;
                } else {
                    $value = DB::table('pairwise_comparisons')
                        ->where('criterion1_id', $id1)
                        ->where('criterion2_id', $id2)
                        ->value('value');
                    $matrix[$i][$j] = $value ?? 1;
                }
            }
        }
        
        // Hitung jumlah kolom
        $columnSums = [];
        for ($j = 0; $j < $n; $j++) {
            $sum = 0;
            for ($i = 0; $i < $n; $i++) {
                $sum += $matrix[$i][$j];
            }
            $columnSums[$j] = $sum;
        }
        
        // Normalisasi matriks
        $normalizedMatrix = [];
        for ($i = 0; $i < $n; $i++) {
            for ($j = 0; $j < $n; $j++) {
                $normalizedMatrix[$i][$j] = $matrix[$i][$j] / $columnSums[$j];
            }
        }
        
        // Hitung bobot (rata-rata baris)
        $weights = [];
        $weightsByName = [];
        foreach ($ids as $i => $id) {
            $sum = 0;
            for ($j = 0; $j < $n; $j++) {
                $sum += $normalizedMatrix[$i][$j];
            }
            $weights[$id] = $sum / $n;
            $weightsByName[$kriteria[$i]->name] = $sum / $n;
        }
        
        // Hitung Lambda Max (sesuai rumus Excel)
        // Step 1: Untuk setiap baris, hitung weighted sum = sum(matrix_row * weights)
        // Step 2: Bagi weighted sum dengan bobot baris tersebut
        // Step 3: Lambda Max = rata-rata dari semua nilai tersebut
        $lambdaMax = 0;
        $weightArray = []; // Array bobot dengan index numerik
        foreach ($ids as $i => $id) {
            $weightArray[$i] = $weights[$id];
        }
        
        for ($i = 0; $i < $n; $i++) {
            $weightedSum = 0;
            for ($j = 0; $j < $n; $j++) {
                $weightedSum += $matrix[$i][$j] * $weightArray[$j];
            }
            // Bagi dengan bobot baris ini
            $lambdaMax += $weightedSum / $weightArray[$i];
        }
        $lambdaMax = $lambdaMax / $n;
        
        // Hitung Consistency Index (CI)
        $ci = ($lambdaMax - $n) / ($n - 1);
        
        // Random Index (RI) untuk n = 1-10
        $ri = [0, 0, 0.58, 0.90, 1.12, 1.24, 1.32, 1.41, 1.45, 1.49];
        $riValue = $ri[$n - 1] ?? 1.49;
        
        // Hitung Consistency Ratio (CR)
        $cr = $riValue > 0 ? $ci / $riValue : 0;
        
        return [
            'matrix' => $matrix,
            'column_sums' => $columnSums,
            'normalized_matrix' => $normalizedMatrix,
            'weights' => $weights,
            'weights_by_name' => $weightsByName,
            'lambda_max' => $lambdaMax,
            'ci' => $ci,
            'ri' => $riValue,
            'cr' => $cr,
            'is_consistent' => $cr < 0.1,
            'criteria_names' => $kriteria->pluck('name')->toArray(),
            'criteria_ids' => $ids,
        ];
    }
    
    /**
     * Hasil Perhitungan
     */
    public function hasil()
    {
        $kriteria = Criterion::where('is_active', 1)->orderBy('order')->orderBy('id')->get();
        
        // Cek apakah ada bobot yang tersimpan
        $hasWeights = $kriteria->whereNotNull('weight')->count() > 0;
        
        if (!$hasWeights) {
            return redirect()->route('admin.ahp.hitung')
                ->with('error', 'Belum ada hasil perhitungan. Silakan hitung AHP terlebih dahulu.');
        }
        
        // Ambil hasil perhitungan
        $result = $this->calculateAHP($kriteria);
        
        return view('admin.ahp.hasil', compact('kriteria', 'result'));
    }
    
    /**
     * Ranking dengan metode SAW
     */
    public function ranking()
    {
        $kriteria = Criterion::where('is_active', 1)->orderBy('order')->orderBy('id')->get();
        
        // Cek apakah ada bobot yang tersimpan
        $hasWeights = $kriteria->whereNotNull('weight')->count() > 0;
        
        if (!$hasWeights) {
            return redirect()->route('admin.ahp.hitung')
                ->with('error', 'Belum ada bobot kriteria. Silakan hitung AHP terlebih dahulu.');
        }
        
        // Buat mapping kriteria berdasarkan nama (lowercase)
        $kriteriaMap = [];
        foreach ($kriteria as $krit) {
            $kriteriaMap[strtolower($krit->name)] = $krit;
        }
        
        // Ambil data mahasiswa dari database
        $mahasiswaData = Mahasiswa::whereNotNull('ipk')
            ->whereNotNull('juara')
            ->whereNotNull('tingkatan')
            ->whereNotNull('keterangan')
            ->get();
        
        // Jika tidak ada data di database, gunakan sample data
        if ($mahasiswaData->count() == 0) {
            // Sample data mahasiswa sesuai Excel sebagai fallback
            $sampleData = [
                ['nama' => 'MARYANA', 'nim' => '2021001', 'ipk' => 3.49, 'juara' => 5, 'tingkatan' => 3, 'keterangan' => 1],
                ['nama' => 'MUHAMMAD ZAKI', 'nim' => '2021002', 'ipk' => 2.66, 'juara' => 7, 'tingkatan' => 7, 'keterangan' => 1],
                ['nama' => 'TITIN SADIYAH', 'nim' => '2021003', 'ipk' => 3.52, 'juara' => 1, 'tingkatan' => 7, 'keterangan' => 3],
                ['nama' => 'SITI NURHALIZA', 'nim' => '2021004', 'ipk' => 2.59, 'juara' => 3, 'tingkatan' => 5, 'keterangan' => 1],
                ['nama' => 'M. REZA FAHLEVI', 'nim' => '2021005', 'ipk' => 2.67, 'juara' => 5, 'tingkatan' => 5, 'keterangan' => 1],
                ['nama' => 'Reihan Fariza', 'nim' => '2021006', 'ipk' => 3.03, 'juara' => 7, 'tingkatan' => 5, 'keterangan' => 1],
                ['nama' => 'Annisa', 'nim' => '2021007', 'ipk' => 3.62, 'juara' => 7, 'tingkatan' => 5, 'keterangan' => 1],
                ['nama' => 'Noor Ridwansyah', 'nim' => '2021008', 'ipk' => 3.17, 'juara' => 3, 'tingkatan' => 7, 'keterangan' => 1],
                ['nama' => 'Nova Dwi Kesumawati', 'nim' => '2021009', 'ipk' => 2.92, 'juara' => 7, 'tingkatan' => 7, 'keterangan' => 1],
                ['nama' => 'Ahmad Nasrullah Yusuf', 'nim' => '2021010', 'ipk' => 3.42, 'juara' => 5, 'tingkatan' => 7, 'keterangan' => 1],
            ];
            $mahasiswaData = collect($sampleData)->map(function($item, $index) {
                return (object) array_merge($item, ['id' => $index + 1]);
            });
        }
        
        // Konversi ke format alternatif
        $alternatif = [];
        foreach ($mahasiswaData as $mhs) {
            $nilai = [];
            foreach ($kriteria as $krit) {
                $kritLower = strtolower($krit->name);
                $nilai[$krit->id] = $mhs->$kritLower ?? 0;
            }
            
            $alternatif[] = [
                'id' => $mhs->id,
                'nama' => $mhs->nama,
                'nim' => $mhs->nim ?? '',
                'nilai' => $nilai,
            ];
        }
        
        // Hitung MAX dan MIN untuk setiap kriteria
        $maxValues = [];
        $minValues = [];
        foreach ($kriteria as $krit) {
            $values = [];
            foreach ($alternatif as $alt) {
                if (isset($alt['nilai'][$krit->id])) {
                    $values[] = $alt['nilai'][$krit->id];
                }
            }
            $maxValues[$krit->id] = count($values) > 0 ? max($values) : 1;
            $minValues[$krit->id] = count($values) > 0 ? min($values) : 1;
        }
        
        // Normalisasi SAW (sesuai rumus Excel)
        // Benefit: r = nilai / max
        // Cost: r = min / nilai
        $normalisasi = [];
        foreach ($alternatif as $i => $alt) {
            foreach ($kriteria as $krit) {
                $nilai = $alt['nilai'][$krit->id] ?? 0;
                $max = $maxValues[$krit->id];
                $min = $minValues[$krit->id];
                
                if ($krit->type == 'benefit') {
                    // Benefit: r = nilai / max
                    $normalisasi[$i][$krit->id] = $max > 0 ? $nilai / $max : 0;
                } else {
                    // Cost: r = min / nilai
                    $normalisasi[$i][$krit->id] = $nilai > 0 ? $min / $nilai : 0;
                }
            }
        }
        
        // Hitung nilai SAW dan ranking
        // V = Σ (normalisasi × bobot)
        $ranking = [];
        foreach ($alternatif as $i => $alt) {
            $nilaiSaw = 0;
            $detail = [];
            
            foreach ($kriteria as $krit) {
                $norm = $normalisasi[$i][$krit->id] ?? 0;
                $bobot = $krit->weight ?? 0;
                $nilaiSaw += $norm * $bobot;
                
                $detail[$krit->id] = [
                    'nilai_asli' => $alt['nilai'][$krit->id] ?? 0,
                    'norm' => $norm,
                    'bobot' => $bobot,
                    'hasil' => $norm * $bobot,
                ];
            }
            
            $ranking[] = [
                'id' => $alt['id'],
                'nama' => $alt['nama'],
                'nim' => $alt['nim'],
                'nilai' => $nilaiSaw,
                'detail' => $detail,
                'nilai_asli' => $alt['nilai'],
            ];
        }
        
        // Sort by nilai descending
        usort($ranking, function($a, $b) {
            return $b['nilai'] <=> $a['nilai'];
        });
        
        return view('admin.ahp.ranking', compact(
            'kriteria', 'alternatif', 'normalisasi', 'ranking', 'maxValues', 'minValues'
        ));
    }
    
    /**
     * Subkriteria
     */
    public function subkriteria()
    {
        $kriteria = Criterion::where('is_active', 1)->get();
        return view('admin.ahp.subkriteria', compact('kriteria'));
    }
}