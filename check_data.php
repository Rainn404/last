<?php
require 'vendor/autoload.php';
$app = require_once 'bootstrap/app.php';

use Illuminate\Database\Capsule\Manager as DB;

$app['db']->setAsGlobal();

$connection = app('db')->connection();
$results = $connection->select('SELECT * FROM criteria');

echo "Data Kriteria di Database:\n\n";
foreach($results as $row) {
    echo "ID: {$row->id_criterion} | Code: {$row->code} | Name: {$row->name} | Weight: {$row->weight}% | Status: " . ($row->status ? 'Aktif' : 'Nonaktif') . "\n";
}
echo "\nTotal: " . count($results) . " kriteria telah di-insert\n";
