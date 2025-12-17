<?php
require __DIR__ . '/vendor/autoload.php';

$app = require __DIR__ . '/bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

use App\Models\User;
use Illuminate\Support\Facades\Hash;

echo "\n╔════════════════════════════════════════════════════════════════╗\n";
echo "║       CREATE NEW USER AS SUPER_ADMIN                           ║\n";
echo "╚════════════════════════════════════════════════════════════════╝\n\n";

$email = 'elang141026@gmail.com';
$name = 'Elang Octafian (Super Admin)';
$password = 'password123'; // Default password

// Check if user already exists
$existing = User::where('email', $email)->first();
if ($existing) {
    echo "⚠️  User sudah ada dengan email ini!\n";
    echo "   Email: {$existing->email}\n";
    echo "   Role: {$existing->role}\n\n";
    exit(1);
}

// Create new user
$user = User::create([
    'name' => $name,
    'email' => $email,
    'password' => Hash::make($password),
    'role' => 'super_admin',
    'email_verified_at' => now(),
]);

echo "✅ USER BERHASIL DIBUAT:\n";
echo "   Nama: {$user->name}\n";
echo "   Email: {$user->email}\n";
echo "   Role: {$user->role}\n";
echo "   Password: $password\n\n";

// Verify
$verified = User::find($user->id);
if ($verified && $verified->role === 'super_admin') {
    echo "✨ VERIFIKASI: ✅ User berhasil dibuat dengan role super_admin\n\n";
}

echo "╔════════════════════════════════════════════════════════════════╗\n";
echo "║ SUPER_ADMIN ACCOUNTS SEKARANG:                               ║\n";
echo "╚════════════════════════════════════════════════════════════════╝\n\n";

$superAdmins = User::where('role', 'super_admin')
    ->get(['id', 'name', 'email', 'role'])
    ->sortBy('email');

foreach ($superAdmins as $admin) {
    echo "   • {$admin->name}\n";
    echo "     📧 {$admin->email}\n\n";
}

echo "╔════════════════════════════════════════════════════════════════╗\n";
echo "║ INFORMASI LOGIN                                              ║\n";
echo "╚════════════════════════════════════════════════════════════════╝\n\n";
echo "📧 Email: $email\n";
echo "🔑 Password: $password\n";
echo "🔗 Login di: http://localhost:8000/login\n";
echo "📍 Akan redirect ke: /admin/pendaftaran\n\n";

?>
