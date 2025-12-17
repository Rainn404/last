<?php
require __DIR__ . '/vendor/autoload.php';

$app = require __DIR__ . '/bootstrap/app.php';
$kernel = $app->make(\Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

$db = app('db');
$updated = $db->table('divisis')->update(['status' => 'active']);

echo "Updated $updated divisi records to status='active'\n";

// Check result
$divisi = $db->table('divisis')->get();
foreach ($divisi as $d) {
    echo "- ID: {$d->id_divisi}, Nama: {$d->nama_divisi}, Status: {$d->status}\n";
}
?>
