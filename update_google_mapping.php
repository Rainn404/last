<?php
// Update Google role mapping for gelang307@gmail.com

require 'vendor/autoload.php';
$app = require_once 'bootstrap/app.php';
$app->make(\Illuminate\Contracts\Console\Kernel::class)->bootstrap();

use Illuminate\Support\Facades\DB;

echo "=== UPDATING GOOGLE MAPPING ===\n\n";

// Update google_role_mappings
$updated = DB::table('google_role_mappings')
    ->where('email_pattern', 'gelang307@gmail.com')
    ->update(['role' => 'admin']);

if ($updated > 0) {
    echo "✅ Google mapping updated: admin\n";
} else {
    echo "⚠️  No mapping found or already correct\n";
}

// Verify
$mapping = DB::table('google_role_mappings')
    ->where('email_pattern', 'gelang307@gmail.com')
    ->first();

if ($mapping) {
    echo "   Email: {$mapping->email_pattern}\n";
    echo "   Role: {$mapping->role}\n";
    echo "   Active: " . ($mapping->is_active ? 'Yes' : 'No') . "\n";
}

echo "\n✅ Done\n";
