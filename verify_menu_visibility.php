<?php
require __DIR__ . '/vendor/autoload.php';

$app = require_once __DIR__ . '/bootstrap/app.php';
$app->make(Illuminate\Contracts\Console\Kernel::class)->bootstrap();

use Illuminate\Support\Facades\DB;

echo "\n╔═══════════════════════════════════════════════════════════════╗\n";
echo "║   VERIFIKASI MENU VISIBILITY - SIDEBAR CONDITIONAL         ║\n";
echo "╚═══════════════════════════════════════════════════════════════╝\n\n";

// Baca file sidebar
$sidebar_content = file_get_contents(__DIR__ . '/resources/views/layouts/admin/app.blade.php');

echo "📋 CEK CONDITIONAL DI SIDEBAR:\n";
echo "─────────────────────────────────────────────────────────────\n\n";

// Check untuk setiap menu yang harus hidden
$hidden_menus = [
    'Kelola Prestasi' => 'Prestasi',
    'Mahasiswa Bermasalah' => 'Mahasiswa Bermasalah',
    'Data Pelanggaran' => 'Pelanggaran',
    'Data Sanksi' => 'Sanksi',
];

$all_wrapped = true;

foreach ($hidden_menus as $label => $name) {
    // Cek apakah menu dibungkus dengan @if conditional
    $pattern = "@if.*?role.*?super_admin.*?$label";
    if (preg_match("/{$pattern}/is", $sidebar_content)) {
        echo "✅ $name           → WRAPPED dengan conditional\n";
    } else {
        echo "❌ $name           → NOT wrapped\n";
        $all_wrapped = false;
    }
}

echo "\n📊 HASIL VERIFIKASI:\n";
echo "─────────────────────────────────────────────────────────────\n";

if (strpos($sidebar_content, "@if(auth()->check() && auth()->user()->role === 'super_admin')") !== false) {
    echo "✅ Pattern conditional     → FOUND\n";
} else {
    echo "❌ Pattern conditional     → NOT FOUND\n";
    $all_wrapped = false;
}

if (strpos($sidebar_content, "Kelola Prestasi") !== false && 
    strpos($sidebar_content, "@if(auth()->check() && auth()->user()->role === 'super_admin')") !== false) {
    echo "✅ Menu items wrapped      → YES\n";
} else {
    echo "❌ Menu items wrapped      → NO\n";
    $all_wrapped = false;
}

// Database check
$users = DB::table('users')->select('email', 'role')->get();
$super_admin_count = 0;
$admin_count = 0;

foreach ($users as $user) {
    if ($user->role === 'super_admin') $super_admin_count++;
    if ($user->role === 'admin') $admin_count++;
}

echo "\n👥 DATABASE CHECK:\n";
echo "─────────────────────────────────────────────────────────────\n";
echo "Super Admin users: $super_admin_count\n";
echo "Admin users:       $admin_count\n";

echo "\n" . ($all_wrapped ? "✅ SEMUA MENU SUDAH TERSEMBUNYI DENGAN CONDITIONAL!" : "❌ ADA YANG BELUM TERCUKUPI") . "\n";
echo "\n═══════════════════════════════════════════════════════════════\n";
echo "\nTesting Instructions:\n";
echo "1. Login as super_admin    → Lihat SEMUA menu (Prestasi, Pelanggaran, Sanksi, Mahasiswa Bermasalah)\n";
echo "2. Login as admin (gelang307@gmail.com) → Lihat CORE menu SAJA (hidden: Prestasi, Pelanggaran, Sanksi, Mahasiswa Bermasalah)\n";
echo "\n═══════════════════════════════════════════════════════════════\n\n";
