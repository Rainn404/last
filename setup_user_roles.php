<?php
require 'vendor/autoload.php';
$app = require_once 'bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

use App\Models\User;
use Illuminate\Support\Facades\Hash;

echo "=== Setup User Roles ===\n\n";

// Super Admin
$users_to_setup = [
    [
        'email' => 'tipolitalahima@gmail.com',
        'role' => 'super_admin',
        'name' => 'Tipolitalahima Admin'
    ],
    [
        'email' => 'gelang307@gmail.com',
        'role' => 'admin',
        'name' => 'Gelang Admin'
    ],
    [
        'email' => 'veramelianasarisari@gmail.com',
        'role' => 'admin',
        'name' => 'Vera Admin'
    ],
    [
        'email' => 'ibnuqurtubii17@gmail.com',
        'role' => 'admin',
        'name' => 'Ibnu Admin'
    ],
    [
        'email' => 'listantii25@gmail.com',
        'role' => 'admin',
        'name' => 'Lisanti Admin'
    ]
];

foreach ($users_to_setup as $setup) {
    $user = User::where('email', $setup['email'])->first();
    
    if ($user) {
        // Update existing user
        $old_role = $user->role;
        $user->update([
            'role' => $setup['role'],
            'name' => $setup['name']
        ]);
        echo "✅ Updated: {$setup['email']} ({$old_role} → {$setup['role']})\n";
    } else {
        // Create new user
        $user = User::create([
            'name' => $setup['name'],
            'email' => $setup['email'],
            'password' => Hash::make('password123'),
            'role' => $setup['role']
        ]);
        echo "✅ Created: {$setup['email']} (role: {$setup['role']})\n";
    }
}

echo "\n=== Summary ===\n";
echo "Super Admin Count: " . User::where('role', 'super_admin')->count() . "\n";
echo "Admin Count: " . User::where('role', 'admin')->count() . "\n";
echo "Mahasiswa Count: " . User::where('role', 'mahasiswa')->count() . "\n";

echo "\n=== All Users with Roles ===\n";
$users = User::all();
foreach ($users as $user) {
    echo "- {$user->email}: {$user->role}\n";
}

echo "\n✅ Setup Complete!\n";
