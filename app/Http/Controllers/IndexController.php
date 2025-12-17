<?php

namespace App\Http\Controllers;

use App\Models\Berita;
use App\Models\Prestasi;
use App\Models\Divisi;
use App\Models\AnggotaHima;
use Illuminate\Http\Request;

class IndexController extends Controller
{
    public function index()
    {
        // Ambil 3 berita terbaru berdasarkan tanggal, kemudian id
        $beritas = Berita::orderByDesc('tanggal')
            ->orderByDesc('id_berita')
            ->take(3)
            ->get();
        
        // Ambil 3 prestasi terbaru
        $prestasis = Prestasi::latest()->take(3)->get();
        
        // Ambil semua divisi
        $divisis = Divisi::all();
        
        // Hitung jumlah anggota
        $jumlahAnggota = AnggotaHima::count();
        
        return view('index', compact('beritas', 'prestasis', 'divisis', 'jumlahAnggota'));
    }
}
