<?php
require 'vendor/autoload.php';
$app = require_once 'bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

use App\Models\User;

$email = 'tipolitalahima@gmail.com';

$user = User::where('email', $email)->first();
if ($user) {
    $user->update(['role' => 'super_admin']);
    echo "✓ User '{$email}' berhasil diupdate menjadi super_admin\n";
    echo "Name: {$user->name}\n";
    echo "Role: {$user->role}\n";
} else {
    echo "✗ User dengan email '{$email}' tidak ditemukan\n";
}
