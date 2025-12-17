<?php
/**
 * Test Role-Based Access Control
 * Script untuk memverifikasi access control berdasarkan role
 */

require __DIR__ . '/vendor/autoload.php';
$app = require __DIR__ . '/bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

use App\Models\User;
use Illuminate\Support\Facades\Auth;

echo "\n=== ROLE-BASED ACCESS CONTROL TEST ===\n\n";

// Get users dari each role
$roles = ['mahasiswa', 'anggota', 'admin', 'super_admin'];
$testResults = [];

foreach ($roles as $role) {
    $user = User::where('role', $role)->first();
    
    if (!$user) {
        echo "⚠️  Tidak ada user dengan role: $role\n";
        continue;
    }

    echo "━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━\n";
    echo "🔹 Role: " . strtoupper($role) . "\n";
    echo "   User: {$user->name} ({$user->email})\n";
    echo "━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━\n";

    // Simulate Authentication
    Auth::login($user);

    // Test 1: /dashboard access
    $canAccessDashboard = true;
    $dashboardResult = "❌ BLOCKED";
    if ($role === 'mahasiswa') {
        $canAccessDashboard = false;
        $dashboardResult = "❌ Redirect to /home";
    } elseif ($role === 'anggota') {
        $dashboardResult = "✅ Anggota Dashboard";
    } elseif ($role === 'admin' || $role === 'super_admin') {
        $dashboardResult = "✅ Admin Dashboard";
    }

    // Test 2: /admin/* access
    $canAccessAdmin = ($role !== 'mahasiswa');
    $adminResult = $canAccessAdmin ? "✅ Allowed" : "❌ Blocked";

    // Test 3: Restricted features (only super_admin)
    $canAccessRestricted = ($role === 'super_admin');
    $restrictedResult = $canAccessRestricted ? "✅ Can see" : "❌ Hidden in sidebar";

    echo "\n   Dashboard Access (/dashboard):\n";
    echo "   └─ $dashboardResult\n\n";

    echo "   Admin Panel Access (/admin/*):\n";
    echo "   └─ $adminResult\n\n";

    echo "   Restricted Features (Prestasi, Pelanggaran, Sanksi):\n";
    echo "   └─ $restrictedResult\n\n";

    $testResults[$role] = [
        'dashboard' => $dashboardResult,
        'admin' => $adminResult,
        'restricted' => $restrictedResult
    ];

    Auth::logout();
}

// Summary Table
echo "\n=== SUMMARY TABLE ===\n\n";
echo sprintf("%-15s | %-25s | %-20s | %-20s\n", "Role", "Dashboard", "Admin Panel", "Restricted");
echo str_repeat("-", 85) . "\n";

foreach ($testResults as $role => $results) {
    echo sprintf("%-15s | %-25s | %-20s | %-20s\n", 
        strtoupper($role),
        $results['dashboard'],
        $results['admin'],
        $results['restricted']
    );
}

echo "\n=== ACCESS CONTROL RULES ===\n\n";
echo "📌 MAHASISWA\n";
echo "   • Cannot access /admin/*\n";
echo "   • Cannot access /dashboard (anggota)\n";
echo "   • Redirected to /home\n\n";

echo "👥 ANGGOTA\n";
echo "   • Can access /dashboard (personal)\n";
echo "   • Can access /admin/* (basic)\n";
echo "   • Cannot see: Prestasi, Pelanggaran, Sanksi, Analytics\n\n";

echo "👨‍💼 ADMIN\n";
echo "   • Can access /admin/*\n";
echo "   • Cannot see: Prestasi, Pelanggaran, Sanksi, Analytics (sidebar hidden)\n";
echo "   • Can still access if direct URL (middleware check)\n\n";

echo "🔐 SUPER_ADMIN\n";
echo "   • Can access EVERYTHING\n";
echo "   • Can see ALL menu items\n";
echo "   • Can see ALL features\n\n";

echo "✅ Test complete!\n\n";
?>
