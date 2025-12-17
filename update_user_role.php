<?php
// Script untuk update role user

require 'vendor/autoload.php';
$app = require_once 'bootstrap/app.php';

$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use App\Models\User;

// Update gelang307@gmail.com menjadi admin
$user1 = User::where('email', 'gelang307@gmail.com')->first();
if ($user1) {
    $user1->update(['role' => 'admin']);
    echo "✓ gelang307@gmail.com berhasil diubah menjadi admin\n";
} else {
    echo "✗ User gelang307@gmail.com tidak ditemukan\n";
}

// Update tipolitalahima@gmail.com menjadi superadmin
$user2 = User::where('email', 'tipolitalahima@gmail.com')->first();
if ($user2) {
    $user2->update(['role' => 'super_admin']);
    echo "✓ tipolitalahima@gmail.com berhasil diubah menjadi super_admin\n";
} else {
    echo "✗ User tipolitalahima@gmail.com tidak ditemukan\n";
}

echo "\nSelesai!\n";
?>
