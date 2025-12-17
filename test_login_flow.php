<?php
require __DIR__ . '/vendor/autoload.php';

$app = require_once __DIR__ . '/bootstrap/app.php';
$app->make(Illuminate\Contracts\Console\Kernel::class)->bootstrap();

use App\Models\User;
use App\Models\GoogleRoleMapping;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

echo "\n=== TEST LOGIN FLOW UNTUK gelang307@gmail.com ===\n\n";

// 1. Simulasi Google callback
$email = 'gelang307@gmail.com';
echo "1️⃣  SIMULASI GOOGLE CALLBACK\n";
echo "   Email: $email\n";

// 2. Cari role dari database
$role = GoogleRoleMapping::findRoleForEmail($email);
echo "\n2️⃣  ROLE DARI GOOGLE MAPPING:\n";
echo "   Role: $role\n";

// 3. Cek user di database
$user = User::where('email', $email)->first();
echo "\n3️⃣  USER DARI DATABASE:\n";
if ($user) {
    echo "   ID: " . $user->id . "\n";
    echo "   Name: " . $user->name . "\n";
    echo "   Email: " . $user->email . "\n";
    echo "   Role: " . $user->role . "\n";
    echo "   Status: ✅ Found\n";
} else {
    echo "   Status: ❌ Not Found\n";
}

// 4. Test redirect logic
echo "\n4️⃣  TEST REDIRECT LOGIC:\n";
if ($role === 'super_admin') {
    echo "   Redirect: /admin/pendaftaran\n";
    echo "   Status: Super Admin\n";
} elseif ($role === 'admin') {
    echo "   Redirect: /admin/dashboard\n";
    echo "   Status: ✅ Admin Biasa\n";
} elseif ($role === 'anggota') {
    echo "   Redirect: /dashboard\n";
    echo "   Status: Anggota\n";
} else {
    echo "   Redirect: /home\n";
    echo "   Status: User Biasa\n";
}

// 5. Cek apakah di DashboardController, akan masuk ke adminDashboard
echo "\n5️⃣  CEK DASHBOARD CONTROLLER LOGIC:\n";
if ($user && ($user->role === 'super_admin' || $user->role === 'admin')) {
    echo "   Method: adminDashboard()\n";
    echo "   Status: ✅ Will use admin dashboard\n";
} else {
    echo "   Status: ❌ Will NOT use admin dashboard\n";
}

// 6. Cek SQL untuk sidebar condition
echo "\n6️⃣  CEK SIDEBAR CONDITION:\n";
echo "   Sidebar check: Auth::user()->role === 'super_admin'\n";
if ($user && $user->role === 'super_admin') {
    echo "   Result: ✅ SHOW restricted menus (Prestasi, Pelanggaran, dll)\n";
} else {
    echo "   Result: ✅ HIDE restricted menus (Prestasi, Pelanggaran, dll)\n";
}

// 7. Debug session
echo "\n7️⃣  FINAL SUMMARY:\n";
echo "   ✅ Database role: " . ($user->role ?? 'N/A') . "\n";
echo "   ✅ Google mapping role: $role\n";
echo "   ✅ Expected dashboard: admin\n";
echo "   ✅ Expected redirect: /admin/dashboard\n";
echo "   ✅ Expected sidebar: Hide restricted menus\n";

echo "\n";
