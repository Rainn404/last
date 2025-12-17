<?php
require __DIR__ . '/vendor/autoload.php';

$app = require __DIR__ . '/bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

use App\Models\User;

$users = User::all(['id', 'name', 'email', 'role'])->sortBy('role');

$roleGroups = $users->groupBy('role');

echo "\n========== USERS BY ROLE ==========\n\n";

foreach ($roleGroups as $role => $group) {
    echo "📌 ROLE: " . strtoupper($role) . " (" . count($group) . " users)\n";
    echo str_repeat("-", 50) . "\n";
    
    foreach ($group as $user) {
        echo "   • {$user->name}\n     Email: {$user->email}\n\n";
    }
}

echo "========== SUMMARY ==========\n";
echo "Total Users: " . $users->count() . "\n";
foreach ($roleGroups as $role => $group) {
    echo "• " . ucfirst($role) . ": " . count($group) . "\n";
}
echo "\n";
?>
