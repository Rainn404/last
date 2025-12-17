<?php
require 'vendor/autoload.php';
$app = require_once 'bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

use App\Models\Berita;

echo "=== DATABASE BERITA DEBUG ===\n\n";

// Check total
echo "Total records in berita table:\n";
$total = Berita::count();
echo "Count: {$total}\n\n";

if ($total > 0) {
    // Get all
    $all = Berita::all();
    echo "All berita:\n";
    foreach ($all as $b) {
        echo "ID: {$b->id_berita}\n";
        echo "Judul: {$b->judul}\n";
        echo "Tanggal: {$b->tanggal}\n";
        echo "Penulis: {$b->penulis}\n\n";
    }

    // Check query OrderByDesc
    echo "\n=== QUERY RESULT ===\n";
    $query = Berita::orderByDesc('tanggal')->orderByDesc('id_berita')->take(3)->get();
    echo "Query Result Count: " . $query->count() . "\n";
    foreach ($query as $b) {
        echo "- {$b->judul} ({$b->id_berita})\n";
    }
} else {
    echo "❌ Database is EMPTY!\n";
    echo "Run: php create_berita.php\n";
}
