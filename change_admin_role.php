<?php
// Change gelang307@gmail.com from super_admin to admin

require 'vendor/autoload.php';
$app = require_once 'bootstrap/app.php';
$app->make(\Illuminate\Contracts\Console\Kernel::class)->bootstrap();

use Illuminate\Support\Facades\DB;

echo "=== CHANGING ROLE gelang307@gmail.com ===\n\n";

// Before
echo "BEFORE:\n";
$before = DB::table('users')->where('email', 'gelang307@gmail.com')->first();
if ($before) {
    echo "Email: {$before->email}\n";
    echo "Role: {$before->role}\n";
} else {
    echo "User not found!\n";
    exit(1);
}

// Update
echo "\n--- UPDATING ---\n";
$updated = DB::table('users')
    ->where('email', 'gelang307@gmail.com')
    ->update(['role' => 'admin']);

if ($updated > 0) {
    echo "✅ Successfully updated!\n";
} else {
    echo "❌ Update failed!\n";
    exit(1);
}

// After
echo "\nAFTER:\n";
$after = DB::table('users')->where('email', 'gelang307@gmail.com')->first();
if ($after) {
    echo "Email: {$after->email}\n";
    echo "Role: {$after->role}\n";
} else {
    echo "User not found!\n";
}

echo "\n✅ Done\n";
