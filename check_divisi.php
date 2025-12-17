<?php
require __DIR__ . '/vendor/autoload.php';

$app = require __DIR__ . '/bootstrap/app.php';
$kernel = $app->make(\Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

$db = app('db');
$divisi = $db->table('divisis')->get();

echo "Total divisi: " . count($divisi) . "\n";
foreach ($divisi as $d) {
    echo "- ID: {$d->id_divisi}, Nama: {$d->nama_divisi}, Status: {$d->status}\n";
}
?>
