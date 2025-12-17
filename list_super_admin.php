<?php
require __DIR__ . '/vendor/autoload.php';

$app = require_once __DIR__ . '/bootstrap/app.php';
$app->make(Illuminate\Contracts\Console\Kernel::class)->bootstrap();

use Illuminate\Support\Facades\DB;

echo "\n╔═══════════════════════════════════════════════════════════════╗\n";
echo "║              DAFTAR AKUN SUPER ADMIN                        ║\n";
echo "╚═══════════════════════════════════════════════════════════════╝\n\n";

$super_admins = DB::table('users')
    ->where('role', 'super_admin')
    ->select('id', 'name', 'email', 'role')
    ->get();

echo "📊 SUPER ADMIN ACCOUNTS:\n";
echo "───────────────────────────────────────────────────────────────\n";

if ($super_admins->count() > 0) {
    foreach ($super_admins as $idx => $user) {
        echo sprintf(
            "\n%d. 👤 %s\n   📧 %s\n   🔑 Role: %s\n",
            $idx + 1,
            $user->name,
            $user->email,
            strtoupper($user->role)
        );
    }
} else {
    echo "❌ Tidak ada super admin account\n";
}

echo "\n───────────────────────────────────────────────────────────────\n";
echo "Total Super Admin: " . $super_admins->count() . "\n";
echo "\n═══════════════════════════════════════════════════════════════\n\n";

// Info tambahan
echo "💡 TESTING:\n";
echo "───────────────────────────────────────────────────────────────\n";
echo "Gunakan salah satu akun di atas untuk:\n";
echo "1. Login dengan email + password\n";
echo "2. Atau Login dengan Google (jika sudah tersetup)\n";
echo "3. Akan lihat SEMUA menu (Prestasi, Pelanggaran, Sanksi, Mahasiswa Bermasalah)\n\n";
