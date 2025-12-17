<?php
// Test Google Role Mapping

require_once __DIR__ . '/vendor/autoload.php';

// Load Laravel bootstraps
$app = require_once __DIR__ . '/bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

use App\Models\GoogleRoleMapping;

echo "=== Google Role Mappings ===\n\n";

$mappings = GoogleRoleMapping::all();
echo "Total records in database: " . count($mappings) . "\n";

if (count($mappings) > 0) {
    foreach ($mappings as $m) {
        echo "\n- Pattern: {$m->email_pattern}\n";
        echo "  Role: {$m->role}\n";
        echo "  Priority: {$m->priority}\n";
        echo "  Active: " . ($m->is_active ? 'Yes' : 'No') . "\n";
    }
} else {
    echo "No mappings found. Running seeder...\n\n";
    
    // Try seeding
    $seeder = new Database\Seeders\GoogleRoleMappingSeeder();
    $seeder->run();
    
    echo "Seeding completed. Records after seeding:\n";
    $mappings = GoogleRoleMapping::all();
    echo "Total: " . count($mappings) . "\n";
    
    foreach ($mappings as $m) {
        echo "\n- Pattern: {$m->email_pattern}\n";
        echo "  Role: {$m->role}\n";
        echo "  Priority: {$m->priority}\n";
    }
}

// Test email matching
echo "\n\n=== Testing Email Matching ===\n";
$testEmails = [
    'himapolitala.ti@gmail.com',
    'admin@hima-ti.politala.ac.id',
    'student@politala.ac.id',
    'test@gmail.com'
];

foreach ($testEmails as $email) {
    $role = GoogleRoleMapping::findRoleForEmail($email);
    echo "\n{$email} => {$role}";
}

echo "\n\nDone!\n";
