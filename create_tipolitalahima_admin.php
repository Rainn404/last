<?php
require 'vendor/autoload.php';
$app = require_once 'bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

use App\Models\User;

$email = 'tipolitalahima@gmail.com';
$name = 'Tipologi Tala';
$password = 'password123';

// Cek apakah user sudah ada
$user = User::where('email', $email)->first();

if ($user) {
    // User sudah ada, update menjadi super_admin
    $user->update(['role' => 'super_admin']);
    echo "✓ User '{$email}' berhasil diupdate menjadi super_admin\n";
    echo "Name: {$user->name}\n";
    echo "Email: {$user->email}\n";
    echo "Role: {$user->role}\n";
} else {
    // Buat user baru dengan role super_admin
    $user = User::create([
        'name' => $name,
        'email' => $email,
        'password' => bcrypt($password),
        'role' => 'super_admin'
    ]);
    echo "✓ User baru berhasil dibuat sebagai super_admin\n";
    echo "Name: {$user->name}\n";
    echo "Email: {$user->email}\n";
    echo "Password: {$password}\n";
    echo "Role: {$user->role}\n";
}
