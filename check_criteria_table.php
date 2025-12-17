<?php
require 'vendor/autoload.php';
$app = require_once 'bootstrap/app.php';
$app->make(\Illuminate\Contracts\Console\Kernel::class)->bootstrap();

$connection = \Illuminate\Support\Facades\DB::connection();
$columns = $connection->getSchemaBuilder()->getColumnListing('criteria');
echo "Columns in criteria table:\n";
print_r($columns);

// Try to get table info
$table_info = $connection->select("DESCRIBE criteria");
echo "\n\nTable structure:\n";
foreach($table_info as $col) {
    echo $col->Field . " - " . $col->Type . "\n";
}
