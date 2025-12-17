<?php
require __DIR__ . '/bootstrap/app.php';

use App\Models\GoogleRoleMapping;

$mappings = GoogleRoleMapping::all();
echo "Total mappings: " . $mappings->count() . "\n\n";

foreach ($mappings as $mapping) {
    echo "Email Pattern: {$mapping->email_pattern}\n";
    echo "Role: {$mapping->role}\n";
    echo "Priority: {$mapping->priority}\n";
    echo "Is Active: " . ($mapping->is_active ? 'Yes' : 'No') . "\n";
    echo "Description: {$mapping->description}\n";
    echo "---\n";
}
