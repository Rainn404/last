<?php
// Promote users to super_admin

require 'vendor/autoload.php';
$app = require_once 'bootstrap/app.php';
$app->make(\Illuminate\Contracts\Console\Kernel::class)->bootstrap();

use Illuminate\Support\Facades\DB;

echo "=== PROMOTING USERS TO SUPER_ADMIN ===\n\n";

// Emails to promote
$emails_to_promote = [
    'elang141026@gmail.com',
    'gelang307@gmail.com',
    'superadmin@hima.com'
];

foreach ($emails_to_promote as $email) {
    $result = DB::table('users')
        ->where('email', $email)
        ->update(['role' => 'super_admin']);
    
    if ($result > 0) {
        echo "✅ {$email} => super_admin\n";
    } else {
        echo "❌ {$email} not found\n";
    }
}

echo "\n=== VERIFICATION ===\n";
$users = DB::table('users')->where('role', 'super_admin')->select('id', 'email', 'role')->get();

foreach ($users as $user) {
    echo "✓ {$user->email} | {$user->role}\n";
}

echo "\n✅ Done\n";
