<?php
require 'vendor/autoload.php';
$app = require_once 'bootstrap/app.php';
$kernel = $app->make(\Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

$jabatans = DB::table('jabatans')->select('id_jabatan', 'nama_jabatan', 'id_divisi', 'status')->get();
echo "=== JABATAN DATA ===\n";
foreach ($jabatans as $jab) {
    $divisi = $jab->id_divisi ?? 'NULL';
    echo "ID: {$jab->id_jabatan}, Nama: {$jab->nama_jabatan}, Divisi ID: {$divisi}, Status: {$jab->status}\n";
}
?>
