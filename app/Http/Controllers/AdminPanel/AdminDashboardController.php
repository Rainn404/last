<?php

namespace App\Http\Controllers\AdminPanel;

use App\Http\Controllers\Controller;
use App\Models\AnggotaHima;
use App\Models\Divisi;
use App\Models\Berita;
use App\Models\Pendaftaran;
use App\Models\Jabatan;
use Carbon\Carbon;

class AdminDashboardController extends Controller
{
    /**
     * Display admin panel dashboard
     * Hanya untuk role 'admin' (Pengurus HIMA)
     */
    public function index()
    {
        // Verifikasi user adalah admin atau super_admin
        $user = auth()->user();
        if (!$user || !in_array(strtolower((string)$user->role), ['admin', 'super_admin'])) {
            abort(403, 'Anda tidak memiliki akses ke halaman ini');
        }

        // Statistik untuk Admin Panel
        $totalAnggota = AnggotaHima::count();
        $anggotaAktif = AnggotaHima::count(); // Semua anggota dianggap aktif
        
        $totalDivisi = Divisi::where('status', 1)->count();
        
        $totalBerita = Berita::count();
        
        $totalJabatan = Jabatan::count();
        
        // Pendaftaran bulan ini
        $pendaftaranBaru = Pendaftaran::whereMonth('created_at', Carbon::now()->month)
            ->whereYear('created_at', Carbon::now()->year)
            ->count();
        
        $pendaftaranPending = Pendaftaran::where('status_pendaftaran', 'pending')->count();
        
        // Recent activities dari Berita (yang bisa dikelola admin)
        $recentActivities = Berita::with('user')
            ->orderBy('tanggal', 'desc')
            ->limit(5)
            ->get()
            ->map(function($berita) {
                return [
                    'text' => 'Berita "' . $berita->judul . '" ditambahkan oleh ' . ($berita->user->name ?? 'Unknown'),
                    'time' => isset($berita->tanggal) ? Carbon::parse($berita->tanggal)->diffForHumans() : 'Waktu tidak diketahui',
                    'type' => 'Berita',
                    'color' => 'info',
                    'icon' => 'newspaper'
                ];
            })
            ->toArray();
        
        // Jika kurang dari 5, tambahkan dari Pendaftaran
        if (count($recentActivities) < 5) {
            $pendaftaranActivities = Pendaftaran::orderBy('created_at', 'desc')
                ->limit(5 - count($recentActivities))
                ->get()
                ->map(function($pendaftaran) {
                    return [
                        'text' => 'Pendaftaran baru dari ' . ($pendaftaran->nama_lengkap ?? 'Unknown'),
                        'time' => $pendaftaran->created_at->diffForHumans(),
                        'type' => 'Pendaftaran',
                        'color' => 'success',
                        'icon' => 'user-plus'
                    ];
                })
                ->toArray();
            
            $recentActivities = array_merge($recentActivities, $pendaftaranActivities);
        }
        
        // Recent members dari AnggotaHima
        $recentMembers = AnggotaHima::with('divisi')
            ->orderBy('created_at', 'desc')
            ->limit(5)
            ->get()
            ->map(function($member) {
                return [
                    'name' => $member->nama,
                    'divisi' => $member->divisi ? $member->divisi->nama_divisi : 'N/A',
                    'avatar' => asset('images/default-avatar.png'),
                    'status' => rand(0, 1) ? 'online' : 'offline'
                ];
            })
            ->toArray();
        
        $data = [
            'totalAnggota' => $totalAnggota,
            'anggotaAktif' => $anggotaAktif,
            'totalDivisi' => $totalDivisi,
            'totalBerita' => $totalBerita,
            'totalJabatan' => $totalJabatan,
            'pendaftaranBaru' => $pendaftaranBaru,
            'pendaftaranPending' => $pendaftaranPending,
            'recentActivities' => $recentActivities,
            'recentMembers' => $recentMembers
        ];
        
        return view('admin-panel.dashboard', $data);
    }
}
