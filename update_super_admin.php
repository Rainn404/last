<?php
require __DIR__ . '/vendor/autoload.php';

$app = require __DIR__ . '/bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

use App\Models\User;

echo "\n╔════════════════════════════════════════════════════════════════╗\n";
echo "║       UPDATE USER ROLE TO SUPER_ADMIN                          ║\n";
echo "╚════════════════════════════════════════════════════════════════╝\n\n";

// Find user by email
$email = 'gelang307@gmail.com';
$user = User::where('email', $email)->first();

if (!$user) {
    echo "❌ User tidak ditemukan dengan email: $email\n\n";
    exit(1);
}

echo "📋 USER DITEMUKAN:\n";
echo "   Nama: {$user->name}\n";
echo "   Email: {$user->email}\n";
echo "   Role Saat Ini: {$user->role}\n\n";

// Update role
$oldRole = $user->role;
$user->role = 'super_admin';
$user->save();

echo "✅ ROLE BERHASIL DIUPDATE:\n";
echo "   Email: {$user->email}\n";
echo "   Role Lama: $oldRole\n";
echo "   Role Baru: {$user->role}\n\n";

// Verify
$updated = User::find($user->id);
if ($updated->role === 'super_admin') {
    echo "✨ VERIFIKASI: ✅ Role sudah benar di database\n\n";
} else {
    echo "⚠️  VERIFIKASI: ❌ Role tidak terupdate dengan benar\n\n";
}

echo "╔════════════════════════════════════════════════════════════════╗\n";
echo "║ SUPER_ADMIN ACCOUNTS SEKARANG:                               ║\n";
echo "╚════════════════════════════════════════════════════════════════╝\n\n";

$superAdmins = User::where('role', 'super_admin')->get(['id', 'name', 'email', 'role']);
foreach ($superAdmins as $admin) {
    echo "   • {$admin->name}\n";
    echo "     Email: {$admin->email}\n\n";
}

?>
