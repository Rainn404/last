<?php
require 'vendor/autoload.php';
$app = require_once 'bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

$columns = DB::select('DESC pendaftaran');
echo "=== Struktur Tabel Pendaftaran ===\n";
foreach ($columns as $col) {
    echo "{$col->Field} | {$col->Type} | {$col->Null} | {$col->Key}\n";
}
