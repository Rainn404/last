<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\AnggotaHima;
use App\Models\Divisi;
use App\Models\Prestasi;
use App\Models\Berita;
use App\Models\Pendaftaran;
use App\Models\Mahasiswa;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    public function __construct()
    {
        // Apply middleware di constructor untuk semua method
        $this->middleware(['auth']);
    }

    /**
     * Display dashboard page - role-based
     * 
     * Akses:
     * - mahasiswa: hanya dashboard pribadi (jika ada)
     * - anggota: dashboard anggota
     * - super_admin: admin dashboard
     * - Mahasiswa TIDAK bisa akses admin/anggota
     * - Anggota TIDAK bisa akses super_admin
     */
    public function index()
    {
        $user = Auth::user();

        // Mahasiswa hanya bisa akses dashboard pribadi
        if ($user->role === 'mahasiswa') {
            // Redirect mahasiswa ke home atau user dashboard
            return redirect('/home')->with('warning', 'Akses dashboard admin hanya untuk admin dan anggota');
        }

        // Anggota bisa akses anggota dashboard
        if ($user->role === 'anggota') {
            return $this->anggotaDashboard();
        }

        // Super admin dan admin bisa akses admin dashboard
        if ($user->role === 'super_admin' || $user->role === 'admin') {
            return $this->adminDashboard();
        }

        // Jika role tidak dikenali, redirect ke home
        return redirect('/home');
    }



    /**
     * Display admin dashboard page with real database statistics
     */
    public function adminDashboard()
    {
        // Real time statistics from database
        $totalAnggota = AnggotaHima::count();
        $totalDivisi = Divisi::where('status', 1)->count();
        $totalPrestasi = Prestasi::count();
        $prestasiValid = Prestasi::where('status_validasi', 'disetujui')->count();
        $totalBerita = Berita::count();
        
        // Anggota aktif (yang memiliki data di tabel AnggotaHima)
        $anggotaAktif = AnggotaHima::count();
        
        // Pendaftaran baru bulan ini
        $pendaftaranBaru = Pendaftaran::whereMonth('created_at', Carbon::now()->month)
            ->whereYear('created_at', Carbon::now()->year)
            ->count();
        
        // Recent activities dari Prestasi (terbaru dulu)
        $recentActivities = Prestasi::with('user')
            ->orderBy('created_at', 'desc')
            ->limit(4)
            ->get()
            ->map(function($prestasi) {
                return [
                    'text' => 'Prestasi ' . $prestasi->nama_prestasi . ' ditambahkan oleh ' . ($prestasi->user->name ?? 'Unknown'),
                    'time' => $prestasi->created_at->diffForHumans(),
                    'type' => 'Prestasi',
                    'color' => $prestasi->status_validasi === 'disetujui' ? 'success' : 'warning'
                ];
            })
            ->toArray();
        
        // Jika kurang dari 4 activities, tambahkan dari Berita
        if (count($recentActivities) < 4) {
            $beritaActivities = Berita::orderBy('tanggal', 'desc')
                ->limit(4 - count($recentActivities))
                ->get()
                ->map(function($berita) {
                    return [
                        'text' => 'Berita ' . $berita->judul . ' dipublikasikan oleh ' . ($berita->penulis ?? 'Unknown'),
                        'time' => isset($berita->tanggal) ? Carbon::parse($berita->tanggal)->diffForHumans() : 'Waktu tidak diketahui',
                        'type' => 'Berita',
                        'color' => 'info'
                    ];
                })
                ->toArray();
            
            $recentActivities = array_merge($recentActivities, $beritaActivities);
        }
        
        // Recent members dari AnggotaHima
        $recentMembers = AnggotaHima::with('divisi')
            ->orderBy('created_at', 'desc')
            ->limit(4)
            ->get();
    
        // Map dengan fallback jika relasi tidak ada
        $recentMembers = $recentMembers->map(function($member) {
            return [
                'name' => $member->nama,
                'divisi' => $member->divisi ? $member->divisi->nama_divisi : 'N/A',
                'avatar' => asset('images/default-avatar.png'),
                'status' => rand(0, 1) ? 'online' : 'offline'
            ];
        })->toArray();
        
        // Statistik tambahan
        try {
            $prestasiPending = Prestasi::where('status_validasi', 'pending')->count();
        } catch (\Exception $e) {
            $prestasiPending = 0;
        }
    
        try {
            $pendaftaranPending = Pendaftaran::where('status_pendaftaran', 'pending')->count();
        } catch (\Exception $e) {
            $pendaftaranPending = 0;
        }
        
            // Total mahasiswa aktif (status = 'Aktif')
            $mahasiswaAktif = Mahasiswa::where('status', 'Aktif')->count();
        
        $data = [
            'totalAnggota' => $totalAnggota,
            'totalDivisi' => $totalDivisi,
            'totalPrestasi' => $totalPrestasi,
            'totalBerita' => $totalBerita,
            'anggotaAktif' => $anggotaAktif,
            'prestasiValid' => $prestasiValid,
            'prestasiPending' => $prestasiPending,
            'pendaftaranBaru' => $pendaftaranBaru,
            'pendaftaranPending' => $pendaftaranPending,
                'mahasiswaAktif' => $mahasiswaAktif,
            'recentActivities' => $recentActivities,
            'recentMembers' => $recentMembers
        ];

        return view('admin.dashboard', $data);
    }
}
