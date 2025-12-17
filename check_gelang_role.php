<?php
require __DIR__ . '/vendor/autoload.php';

$app = require_once __DIR__ . '/bootstrap/app.php';
$app->make(Illuminate\Contracts\Console\Kernel::class)->bootstrap();

use Illuminate\Support\Facades\DB;

echo "\n=== CEK ROLE GELANG307 ===\n\n";

$user = DB::table('users')->where('email', 'gelang307@gmail.com')->first();
echo "👤 USER DATABASE:\n";
echo "Email: " . ($user?->email ?? 'NOT FOUND') . "\n";
echo "Role: " . ($user?->role ?? 'NOT FOUND') . "\n";
echo "ID: " . ($user?->id ?? 'NOT FOUND') . "\n";

echo "\n📍 GOOGLE ROLE MAPPING:\n";
$mapping = DB::table('google_role_mappings')
    ->where('email_pattern', 'LIKE', '%gelang307%')
    ->orWhere('email_pattern', 'gelang307@gmail.com')
    ->first();
    
if ($mapping) {
    echo "Email Pattern: " . $mapping->email_pattern . "\n";
    echo "Role: " . $mapping->role . "\n";
    echo "Is Active: " . $mapping->is_active . "\n";
} else {
    echo "NO MAPPING FOUND\n";
}

echo "\n=== ALL GOOGLE MAPPINGS ===\n";
$allMappings = DB::table('google_role_mappings')->get();
foreach ($allMappings as $m) {
    echo "- " . $m->email_pattern . " => " . $m->role . " (Active: " . ($m->is_active ? 'Yes' : 'No') . ")\n";
}

echo "\n";
