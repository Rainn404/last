<?php
require 'vendor/autoload.php';
$app = require_once 'bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

use App\Models\GoogleRoleMapping;

echo "=== Setup Google Role Mappings ===\n\n";

$mappings = [
    [
        'email_pattern' => 'tipolitalahima@gmail.com',
        'role' => 'super_admin',
        'priority' => 200,
        'description' => 'Super Admin Tipolitalahima'
    ],
    [
        'email_pattern' => 'gelang307@gmail.com',
        'role' => 'admin',
        'priority' => 190,
        'description' => 'Admin Gelang307'
    ],
    [
        'email_pattern' => 'veramelianasarisari@gmail.com',
        'role' => 'admin',
        'priority' => 185,
        'description' => 'Admin Vera'
    ],
    [
        'email_pattern' => 'ibnuqurtubii17@gmail.com',
        'role' => 'admin',
        'priority' => 180,
        'description' => 'Admin Ibnu'
    ],
    [
        'email_pattern' => 'listantii25@gmail.com',
        'role' => 'admin',
        'priority' => 175,
        'description' => 'Admin Lisanti'
    ],
];

foreach ($mappings as $mapping) {
    $existing = GoogleRoleMapping::where('email_pattern', $mapping['email_pattern'])->first();
    
    if ($existing) {
        $existing->update($mapping);
        echo "✅ Updated mapping: {$mapping['email_pattern']} → {$mapping['role']}\n";
    } else {
        GoogleRoleMapping::create($mapping);
        echo "✅ Created mapping: {$mapping['email_pattern']} → {$mapping['role']}\n";
    }
}

echo "\n=== Current Google Role Mappings ===\n";
$all = GoogleRoleMapping::orderBy('priority', 'desc')->get();
foreach ($all as $map) {
    $status = $map->is_active ? '✓' : '✗';
    echo "{$status} {$map->email_pattern} → {$map->role} (priority: {$map->priority})\n";
}

echo "\n✅ Google Role Mapping Setup Complete!\n";
