<?php
require 'vendor/autoload.php';
$app = require_once 'bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

use App\Models\User;
use App\Models\AnggotaHima;
use App\Models\Prestasi;
use App\Models\Pendaftaran;
use App\Models\Berita;
use App\Models\Divisi;

echo "=== USER DATA ===\n";
$users = User::where('role', 'super_admin')->get();
echo "Super Admin Users:\n";
foreach ($users as $user) {
    echo "- {$user->name} ({$user->email}) - Role: {$user->role}\n";
}

echo "\n=== DASHBOARD STATS ===\n";
echo "Total AnggotaHima: " . AnggotaHima::count() . "\n";
echo "Total Divisi (active): " . Divisi::where('status', 1)->count() . "\n";
echo "Total Prestasi: " . Prestasi::count() . "\n";
echo "  - Disetujui: " . Prestasi::where('status_validasi', 'disetujui')->count() . "\n";
echo "  - Pending: " . Prestasi::where('status_validasi', 'pending')->count() . "\n";
// Berita table doesn't have `status` column; count all berita
echo "Total Berita: " . Berita::count() . "\n";
echo "Pendaftaran Baru (bulan ini): " . Pendaftaran::whereMonth('created_at', now()->month)->whereYear('created_at', now()->year)->count() . "\n";
echo "Pendaftaran Pending: " . Pendaftaran::where('status_pendaftaran', 'pending')->count() . "\n";

echo "\n=== RECENT PRESTASI ===\n";
$prestasi = Prestasi::with('user')->orderBy('created_at', 'desc')->limit(3)->get();
foreach ($prestasi as $p) {
    $userName = $p->user ? $p->user->name : 'Unknown';
    echo "- {$p->nama_prestasi} (by $userName) - {$p->created_at}\n";
}

echo "\n=== RECENT BERITA ===\n";
// Use `tanggal` column for ordering; `created_at` may not exist
$berita = Berita::with('user')->orderBy('tanggal', 'desc')->limit(3)->get();
foreach ($berita as $b) {
    $userName = $b->user ? $b->user->name : 'Unknown';
    echo "- {$b->judul} (by $userName) - {$b->created_at}\n";
}

echo "\n=== RECENT ANGGOTA ===\n";
$anggota = AnggotaHima::with('divisi')->orderBy('created_at', 'desc')->limit(3)->get();
foreach ($anggota as $a) {
    $divisi = $a->divisi ? $a->divisi->nama_divisi : 'N/A';
    echo "- {$a->nama} ($divisi)\n";
}
