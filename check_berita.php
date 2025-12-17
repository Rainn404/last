<?php
require 'vendor/autoload.php';
$app = require_once 'bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

use App\Models\Berita;

echo "=== CHECKING BERITA DATA ===\n";
$berita = Berita::all();
echo "Total Berita: " . $berita->count() . "\n\n";

if ($berita->count() > 0) {
    echo "Daftar Berita:\n";
    foreach ($berita as $b) {
        echo "- ID: {$b->id_berita}\n";
        echo "  Judul: {$b->judul}\n";
        echo "  Isi: " . substr($b->isi, 0, 50) . "...\n";
        echo "  Foto: {$b->foto}\n";
        echo "  Tanggal: {$b->tanggal}\n";
        echo "  Penulis: {$b->penulis}\n\n";
    }
} else {
    echo "⚠️ Tidak ada data berita di database!\n";
}

echo "\n=== CHECKING QUERY RESULT ===\n";
$query = Berita::orderByDesc('tanggal')->orderByDesc('id_berita')->take(3)->get();
echo "Query hasil (3 terbaru):\n";
echo "Total: " . $query->count() . "\n";
foreach ($query as $b) {
    echo "- {$b->judul} (Tanggal: {$b->tanggal})\n";
}
