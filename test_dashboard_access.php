<?php
// Quick test to check dashboard access and user roles

require 'vendor/autoload.php';
$app = require_once 'bootstrap/app.php';
$app->make(\Illuminate\Contracts\Console\Kernel::class)->bootstrap();

use Illuminate\Support\Facades\DB;

echo "=== CHECKING USER ROLES ===\n\n";

$users = DB::table('users')->select('id', 'name', 'email', 'role')->orderBy('role')->get();

foreach ($users as $user) {
    echo "Email: {$user->email} | Role: {$user->role}\n";
}

echo "\n=== SUMMARY ===\n";

$super_admins = DB::table('users')->where('role', 'super_admin')->count();
$admins = DB::table('users')->where('role', 'admin')->count();
$anggota = DB::table('users')->where('role', 'anggota')->count();
$mahasiswa = DB::table('users')->where('role', 'mahasiswa')->count();

echo "Super Admins: {$super_admins}\n";
echo "Admins: {$admins}\n";
echo "Anggota: {$anggota}\n";
echo "Mahasiswa: {$mahasiswa}\n";

echo "\n✅ Test complete\n";
