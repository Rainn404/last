<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Criterion;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class CriterionController extends Controller
{
    public function index(Request $request)
    {
        // Pencarian dan filter
        $search = $request->input('search');
        $type = $request->input('type');

        $query = Criterion::query();

        if ($search) {
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('code', 'like', "%{$search}%")
                  ->orWhere('description', 'like', "%{$search}%");
            });
        }

        if ($type && in_array($type, ['benefit', 'cost'])) {
            $query->where('type', $type);
        }

        // Gunakan pagination
        $criteria = $query->orderBy('order')->orderBy('name')->paginate(10);

        // statistik yang diperlukan view
        $totalCriteria   = Criterion::count();
        $activeCriteria  = Criterion::where('is_active', 1)->count();
        $benefitCriteria = Criterion::where('type', 'benefit')->count();
        $costCriteria    = Criterion::where('type', 'cost')->count();
        
        // Hitung persentase dengan pengecekan division by zero
        $activePercentage = $totalCriteria > 0 
            ? number_format(($activeCriteria / $totalCriteria) * 100, 1)
            : 0;
        $benefitPercentage = $totalCriteria > 0 
            ? number_format(($benefitCriteria / $totalCriteria) * 100, 1)
            : 0;
        $costPercentage = $totalCriteria > 0 
            ? number_format(($costCriteria / $totalCriteria) * 100, 1)
            : 0;

        return view('admin.criteria.index', compact(
            'criteria',
            'totalCriteria',
            'activeCriteria',
            'benefitCriteria',
            'costCriteria',
            'activePercentage',
            'benefitPercentage',
            'costPercentage'
        ));
    }

    public function create()
    {
        return view('admin.criteria.create');
    }

    public function store(Request $request)
    {
        // HAPUS validasi untuk code, description, order, is_active
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'type' => 'required|in:benefit,cost',
        ], [
            'name.required' => 'Nama kriteria wajib diisi',
            'type.required' => 'Tipe kriteria wajib dipilih',
            'type.in'       => 'Tipe kriteria harus benefit atau cost'
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput()
                ->with('error', 'Validasi gagal. Silakan periksa data yang dimasukkan.');
        }

        try {
            // **METODE YANG FIX: Cari angka terbesar dari kode C**
            // Ambil semua kode yang ada
            $existingCodes = Criterion::pluck('code')->toArray();
            
            $maxNumber = 0;
            foreach ($existingCodes as $existingCode) {
                // Cek pattern C1, C2, C3, dst
                if (preg_match('/^C(\d+)$/', $existingCode, $matches)) {
                    $num = (int)$matches[1];
                    if ($num > $maxNumber) {
                        $maxNumber = $num;
                    }
                }
            }
            
            $nextNumber = $maxNumber + 1;
            $code = 'C' . $nextNumber;
            
            // Double check untuk memastikan kode unik
            $counter = 1;
            $originalCode = $code;
            
            while (Criterion::where('code', $code)->exists()) {
                $code = $originalCode . '_' . $counter;
                $counter++;
                
                // Safety net jika terlalu banyak percobaan
                if ($counter > 100) {
                    $code = 'C' . time(); // Gunakan timestamp sebagai fallback
                    break;
                }
            }

            Criterion::create([
                'name'        => $request->name,
                'code'        => $code,
                'type'        => $request->type,
                'description' => null,
                'order'       => 0,
                'is_active'   => true
            ]);

            return redirect()->route('admin.criteria.index')
                ->with('success', 'Kriteria berhasil ditambahkan!');

        } catch (\Exception $e) {
            return redirect()->back()
                ->withInput()
                ->with('error', 'Gagal menambahkan kriteria. Error: ' . $e->getMessage());
        }
    }

    public function show($id)
    {
        $criterion = Criterion::findOrFail($id);
        return view('admin.criteria.show', compact('criterion'));
    }

    public function edit($id)
    {
        $criterion = Criterion::findOrFail($id);
        return view('admin.criteria.edit', compact('criterion'));
    }

    public function update(Request $request, $id)
    {
        // HAPUS validasi untuk code, description, order, is_active
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'type' => 'required|in:benefit,cost',
        ], [
            'name.required' => 'Nama kriteria wajib diisi',
            'type.required' => 'Tipe kriteria wajib dipilih',
            'type.in'       => 'Tipe kriteria harus benefit atau cost'
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput()
                ->with('error', 'Validasi gagal. Silakan periksa data yang dimasukkan.');
        }

        try {
            $criterion = Criterion::findOrFail($id);
            
            // Update hanya nama dan tipe, kode tetap sama
            $criterion->update([
                'name' => $request->name,
                'type' => $request->type,
                // Kode TIDAK diupdate (tetap kode lama yang sudah di-generate)
                // Description tetap null
                // Order tetap 0 atau tidak diubah
                // Status tetap true atau tidak diubah
            ]);

            return redirect()->route('admin.criteria.index')
                ->with('success', 'Kriteria berhasil diperbarui!');

        } catch (\Exception $e) {
            return redirect()->back()
                ->withInput()
                ->with('error', 'Gagal memperbarui kriteria. Error: ' . $e->getMessage());
        }
    }

    public function destroy($id)
{
    try {
        $criterion = Criterion::findOrFail($id);
        $criterionName = $criterion->name;

        // Hard delete (hapus permanen dari database)
        $criterion->forceDelete();

        return redirect()->route('admin.criteria.index')
            ->with('success', "Kriteria '{$criterionName}' berhasil dihapus!");
    } catch (\Exception $e) {
        return redirect()->back()
            ->with('error', 'Gagal menghapus kriteria. Error: ' . $e->getMessage());
    }
}
    // OPSIONAL: Method tambahan jika diperlukan nanti

    public function toggleStatus($id)
    {
        try {
            $criterion = Criterion::findOrFail($id);
            $criterion->is_active = !$criterion->is_active;
            $criterion->save();

            $status = $criterion->is_active ? 'diaktifkan' : 'dinonaktifkan';
            
            return redirect()->back()
                ->with('success', "Kriteria '{$criterion->name}' berhasil {$status}!");

        } catch (\Exception $e) {
            return redirect()->back()
                ->with('error', 'Gagal mengubah status kriteria. Error: ' . $e->getMessage());
        }
    }

    public function updateOrder(Request $request)
    {
        $request->validate([
            'order' => 'required|array',
            'order.*' => 'integer|exists:criteria,id'
        ]);

        try {
            foreach ($request->order as $index => $criterionId) {
                Criterion::where('id', $criterionId)->update(['order' => $index + 1]);
            }

            return response()->json([
                'success' => true,
                'message' => 'Urutan kriteria berhasil diperbarui!'
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Gagal memperbarui urutan. Error: ' . $e->getMessage()
            ], 500);
        }
    }

    public function export()
    {
        $criteria = Criterion::orderBy('order')->orderBy('name')->get();
        
        $headers = [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => 'attachment; filename="criteria_' . date('Ymd_His') . '.csv"',
        ];

        $callback = function() use ($criteria) {
            $file = fopen('php://output', 'w');
            
            // Header CSV
            fputcsv($file, ['No', 'Kode', 'Nama', 'Tipe', 'Dibuat', 'Diperbarui']);
            
            // Data (hanya kolom yang relevan)
            foreach ($criteria as $index => $criterion) {
                fputcsv($file, [
                    $index + 1,
                    $criterion->code,
                    $criterion->name,
                    $criterion->type == 'benefit' ? 'Benefit' : 'Cost',
                    $criterion->created_at->format('d/m/Y'),
                    $criterion->updated_at->format('d/m/Y')
                ]);
            }
            
            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }
    
    // Method untuk generate kode alternatif (tidak digunakan di store(), hanya referensi)
    private function generateCodeFromName($name)
    {
        $name = strtoupper(trim($name));
        $words = preg_split('/\s+/', $name);
        
        // Ambil inisial dari setiap kata
        $initials = '';
        foreach ($words as $word) {
            if (!empty($word) && preg_match('/^[A-Z]/', $word)) {
                $initials .= $word[0];
                if (strlen($initials) >= 3) break;
            }
        }
        
        // Minimal 2 karakter
        if (strlen($initials) < 2) {
            $initials = 'KT'; // Default: Kriteria
        }
        
        // Ambil 2-3 karakter
        $baseCode = substr($initials, 0, 3);
        
        // Cek jika kode sudah ada, tambah angka
        $code = $baseCode;
        $counter = 1;
        
        while (Criterion::where('code', $code)->exists()) {
            $code = $baseCode . $counter;
            $counter++;
            
            if ($counter > 100) {
                $code = $baseCode . '_' . time();
                break;
            }
        }
        
        return $code;
    }
}