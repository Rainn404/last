<?php
// Simulate Google Login Redirect Flow

require_once __DIR__ . '/vendor/autoload.php';

$app = require_once __DIR__ . '/bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

use App\Models\GoogleRoleMapping;

echo "=== Simulating Google Login Redirect Flow ===\n\n";

// Test cases with different emails
$testCases = [
    'himapolitala.ti@gmail.com' => 'admin.dashboard',
    'admin@hima-ti.politala.ac.id' => 'admin-panel.dashboard',
    'user@politala.ac.id' => 'dashboard',
    'random@example.com' => 'dashboard',
];

foreach ($testCases as $email => $expectedRoute) {
    $role = GoogleRoleMapping::findRoleForEmail($email);
    
    // Determine redirect route based on role
    if ($role === 'super_admin') {
        $redirectRoute = 'admin.dashboard';
    } elseif ($role === 'admin') {
        $redirectRoute = 'admin-panel.dashboard';
    } else {
        $redirectRoute = 'dashboard';
    }
    
    $status = ($redirectRoute === $expectedRoute) ? '✓ PASS' : '✗ FAIL';
    echo "{$status} | Email: {$email}\n";
    echo "         Role: {$role} | Redirect: {$redirectRoute}\n\n";
}

echo "Google Login Integration Ready!\n";
