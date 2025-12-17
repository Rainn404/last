<?php
require_once __DIR__.'/vendor/autoload.php';
$app = require_once __DIR__.'/bootstrap/app.php';
$kernel = $app->make(\Illuminate\Contracts\Http\Kernel::class);
$response = $kernel->handle($request = \Illuminate\Http\Request::capture());

// Query database
$jabatans = \App\Models\Jabatan::all();
echo "Total Jabatan: " . count($jabatans) . "\n";
echo "================================\n";

foreach ($jabatans as $jab) {
    echo "ID: {$jab->id_jabatan}\n";
    echo "  Nama: {$jab->nama_jabatan}\n";
    echo "  Status: {$jab->status}\n";
    echo "  ID Divisi: " . ($jab->id_divisi ?? 'NULL') . "\n";
    echo "---\n";
}

// Check divisi
echo "\nDivisi Available:\n";
$divisis = \App\Models\Divisi::all();
foreach ($divisis as $div) {
    echo "ID: {$div->id_divisi}, Nama: {$div->nama_divisi}\n";
}

// Test getJabatanByDivisi for divisi 'kader'
echo "\nTest Query for Divisi 'kader' (id_divisi should be found):\n";
$kaderr = \App\Models\Divisi::where('nama_divisi', 'kader')->first();
if ($kaderr) {
    echo "Divisi 'kader' ID: {$kaderr->id_divisi}\n";
    $jabatanKader = \App\Models\Jabatan::where('id_divisi', $kaderr->id_divisi)
        ->where('status', 'active')
        ->get();
    echo "Jabatan untuk Divisi 'kader': " . count($jabatanKader) . "\n";
    foreach ($jabatanKader as $jab) {
        echo "  - {$jab->nama_jabatan}\n";
    }
}
