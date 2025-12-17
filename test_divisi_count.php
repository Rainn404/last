<?php
require 'vendor/autoload.php';
$app = require_once 'bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use App\Models\Divisi;

try {
    $divisis = Divisi::withCount('anggotaHima')
        ->orderBy('nama_divisi')
        ->get();
    
    echo "✅ Query berhasil!\n";
    echo "Jumlah divisi: " . count($divisis) . "\n";
    
    foreach ($divisis as $d) {
        echo "- ID: {$d->id_divisi} | Nama: {$d->nama_divisi} | Anggota: {$d->anggota_hima_count}\n";
    }
} catch (\Exception $e) {
    echo "❌ Error: " . $e->getMessage() . "\n";
    echo "SQL: " . $e->getSql() . "\n";
}
