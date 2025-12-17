<?php
require __DIR__ . '/../vendor/autoload.php';
$app = require_once __DIR__ . '/../bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use App\Models\User;

$users = User::select('email','role')->get();
foreach ($users as $u) {
    echo ($u->email ?? '(no-email)') . ' => ' . ($u->role ?? 'NULL') . PHP_EOL;
}
