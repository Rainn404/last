<?php
require 'vendor/autoload.php';
$app = require_once 'bootstrap/app.php';
$kernel = $app->make(\Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

$result = \Illuminate\Support\Facades\DB::select('DESCRIBE pendaftaran');
foreach ($result as $col) {
    if ($col->Field == 'status_pendaftaran') {
        echo "Field: " . $col->Field . "\n";
        echo "Type: " . $col->Type . "\n";
        break;
    }
}
?>
