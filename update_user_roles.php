<?php

require_once 'vendor/autoload.php';

use Illuminate\Foundation\Application;
use Illuminate\Contracts\Console\Kernel;
use App\Models\User;

$app = require_once 'bootstrap/app.php';

$kernel = $app->make(Kernel::class);
$kernel->bootstrap();

try {
    // Update tipolitalahima@gmail.com to super_admin
    $user1 = User::where('email', 'tipolitalahima@gmail.com')->first();
    if ($user1) {
        $user1->role = 'super_admin';
        $user1->save();
        echo "Updated tipolitalahima@gmail.com to super_admin\n";
    } else {
        echo "User tipolitalahima@gmail.com not found\n";
    }

    // Update admin emails
    $adminEmails = [
        'gelang307@gmail.com',
        'veramelianasarisari@gmail.com',
        'ibnuqurtubii17@gmail.com',
        'listantii25@gmail.com'
    ];

    foreach ($adminEmails as $email) {
        $user = User::where('email', $email)->first();
        if ($user) {
            $user->role = 'admin';
            $user->save();
            echo "Updated {$email} to admin\n";
        } else {
            echo "User {$email} not found\n";
        }
    }

    echo "Role updates completed.\n";

} catch (Exception $e) {
    echo "Error: " . $e->getMessage() . "\n";
}
