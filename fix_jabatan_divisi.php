<?php
require 'vendor/autoload.php';
$app = require_once 'bootstrap/app.php';
$kernel = $app->make(\Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

// Update Wakil Ketua to have id_divisi = 1
DB::table('jabatans')->where('id_jabatan', 2)->update(['id_divisi' => 1]);

// Verify
$jabatans = DB::table('jabatans')->select('id_jabatan', 'nama_jabatan', 'id_divisi', 'status')->get();
echo "=== JABATAN DATA AFTER UPDATE ===\n";
foreach ($jabatans as $jab) {
    $divisi = $jab->id_divisi ?? 'NULL';
    echo "ID: {$jab->id_jabatan}, Nama: {$jab->nama_jabatan}, Divisi ID: {$divisi}, Status: {$jab->status}\n";
}
echo "\n✅ UPDATE BERHASIL!\n";
?>
