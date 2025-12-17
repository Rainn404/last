<?php
require 'vendor/autoload.php';
$app = require_once 'bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

$divisis = DB::table('divisis')->get();
echo "=== Data Divisi ===\n";
foreach ($divisis as $divisi) {
    echo "ID: {$divisi->id_divisi} | Nama: {$divisi->nama_divisi} | Status: {$divisi->status}\n";
}
echo "\n=== Divisi dengan Status = 1 ===\n";
$active = DB::table('divisis')->where('status', 1)->get();
echo "Jumlah: " . count($active) . "\n";
