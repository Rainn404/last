<?php
// Add Google role mappings for super admin and admin accounts

require 'vendor/autoload.php';
$app = require_once 'bootstrap/app.php';
$app->make(\Illuminate\Contracts\Console\Kernel::class)->bootstrap();

use Illuminate\Support\Facades\DB;

echo "=== ADDING GOOGLE ROLE MAPPINGS ===\n\n";

// Get the super admin emails from users table
$super_admins = DB::table('users')
    ->where('role', 'super_admin')
    ->select('email')
    ->get();

echo "Super Admin Users:\n";
foreach ($super_admins as $user) {
    echo "  - {$user->email}\n";
}

// Check existing mappings
echo "\n=== EXISTING MAPPINGS ===\n";
$existing = DB::table('google_role_mappings')->select('email_pattern', 'role', 'is_active')->get();

if ($existing->isEmpty()) {
    echo "No mappings yet\n";
} else {
    foreach ($existing as $m) {
        $active = $m->is_active ? "✓ ACTIVE" : "✗ INACTIVE";
        echo "  {$m->email_pattern} => {$m->role} [{$active}]\n";
    }
}

// Add mappings for our super admins
echo "\n=== ADDING NEW MAPPINGS ===\n";

$mappings_to_add = [
    ['email_pattern' => 'elang141026@gmail.com', 'role' => 'super_admin'],
    ['email_pattern' => 'gelang307@gmail.com', 'role' => 'super_admin'],
    ['email_pattern' => 'superadmin@hima.com', 'role' => 'super_admin'],
    ['email_pattern' => 'admin@local.test', 'role' => 'super_admin'],
];

foreach ($mappings_to_add as $mapping) {
    // Check if already exists
    $exists = DB::table('google_role_mappings')
        ->where('email_pattern', $mapping['email_pattern'])
        ->first();
    
    if ($exists) {
        echo "⚠️  {$mapping['email_pattern']} already exists\n";
        // Update to make sure it's active and correct role
        DB::table('google_role_mappings')
            ->where('email_pattern', $mapping['email_pattern'])
            ->update(['role' => $mapping['role'], 'is_active' => true, 'priority' => 100]);
        echo "✅ Updated to role={$mapping['role']}, active=true\n";
    } else {
        DB::table('google_role_mappings')->insert([
            'email_pattern' => $mapping['email_pattern'],
            'role' => $mapping['role'],
            'priority' => 100,
            'is_active' => true,
            'description' => 'Super Admin',
            'created_at' => now(),
            'updated_at' => now(),
        ]);
        echo "✅ Added: {$mapping['email_pattern']} => {$mapping['role']}\n";
    }
}

echo "\n=== FINAL MAPPINGS ===\n";
$final = DB::table('google_role_mappings')->where('is_active', true)->select('email_pattern', 'role')->get();

foreach ($final as $m) {
    echo "  ✓ {$m->email_pattern} => {$m->role}\n";
}

echo "\n✅ Done\n";
