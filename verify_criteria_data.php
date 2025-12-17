<?php
require 'vendor/autoload.php';
$app = require_once 'bootstrap/app.php';
$app->make(\Illuminate\Contracts\Console\Kernel::class)->bootstrap();

$criteria = \App\Models\Criterion::all();
echo "Data Kriteria yang ter-insert:\n\n";
foreach($criteria as $item) {
    echo "ID: {$item->id_criterion} | Code: {$item->code} | Name: {$item->name} | Type: {$item->type} | Weight: {$item->weight}% | Priority: {$item->priority} | Status: " . ($item->status ? 'Aktif' : 'Nonaktif') . "\n";
}
echo "\nTotal: " . count($criteria) . " kriteria\n";
