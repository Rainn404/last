<?php
require __DIR__ . '/vendor/autoload.php';

$app = require_once __DIR__ . '/bootstrap/app.php';
$app->make(Illuminate\Contracts\Console\Kernel::class)->bootstrap();

use App\Models\User;
use Illuminate\Support\Facades\Auth;

echo "\n=== CEK APAKAH ADA ISSUE DI ROLE CONDITION ===\n\n";

// Cari user gelang307
$user = User::where('email', 'gelang307@gmail.com')->first();

echo "👤 User Data:\n";
echo "   Email: " . $user->email . "\n";
echo "   Role: " . $user->role . "\n";
echo "   Role Type: " . gettype($user->role) . "\n";
echo "   Role === 'admin': " . ($user->role === 'admin' ? 'TRUE' : 'FALSE') . "\n";
echo "   Role == 'admin': " . ($user->role == 'admin' ? 'TRUE' : 'FALSE') . "\n";
echo "   strcmp: " . strcmp($user->role, 'admin') . "\n";

echo "\n🔍 Debug Sidebar Condition:\n";

// Test sidebar condition
if ($user && $user->role === 'super_admin') {
    echo "   ❌ SHOW restricted menus (WRONG)\n";
} else {
    echo "   ✅ HIDE restricted menus (CORRECT)\n";
}

echo "\n🔍 Test all roles:\n";
$allUsers = User::select('email', 'role')->get();
foreach ($allUsers as $u) {
    $isSuper = $u->role === 'super_admin';
    echo "   " . $u->email . " => " . $u->role . " (super_admin: " . ($isSuper ? 'YES' : 'NO') . ")\n";
}

echo "\n";
