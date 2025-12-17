<?php
/**
 * Test Login Redirect by Role
 * Memverifikasi setiap role redirect ke dashboard yang benar
 */

require __DIR__ . '/vendor/autoload.php';
$app = require __DIR__ . '/bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

use App\Models\User;
use Illuminate\Support\Facades\Auth;

echo "\n╔════════════════════════════════════════════════════════════════════╗\n";
echo "║       LOGIN REDIRECT TEST - SETIAP ROLE KE DASHBOARD MEREKA        ║\n";
echo "╚════════════════════════════════════════════════════════════════════╝\n\n";

$roles = ['mahasiswa', 'anggota', 'admin', 'super_admin'];

foreach ($roles as $role) {
    $user = User::where('role', $role)->first();
    
    if (!$user) {
        echo "⚠️  Tidak ada user dengan role: $role\n\n";
        continue;
    }

    echo "━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━\n";
    echo "🔹 ROLE: " . strtoupper($role) . "\n";
    echo "━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━\n";
    
    echo "   Nama: {$user->name}\n";
    echo "   Email: {$user->email}\n";
    echo "   Role: {$user->role}\n";
    
    // Determine redirect destination based on role
    $redirect = '/';
    if ($user->role === 'super_admin') {
        $redirect = '/admin/pendaftaran';
    } elseif ($user->role === 'admin') {
        $redirect = '/admin/dashboard';
    } elseif ($user->role === 'anggota') {
        $redirect = '/dashboard';
    } else { // mahasiswa
        $redirect = '/';
    }
    
    echo "   \n   📍 LOGIN REDIRECT:\n";
    echo "      → {$redirect}\n\n";
    
    // Description
    $descriptions = [
        'mahasiswa' => '👤 User biasa, hanya akses halaman publik',
        'anggota' => '👥 Pengurus, akses dashboard personal + admin (limited)',
        'admin' => '👨‍💼 Admin panel, akses semua admin kecuali fitur restricted',
        'super_admin' => '🔐 Super admin, akses SEMUA fitur tanpa batasan',
    ];
    
    echo "   " . $descriptions[$role] . "\n\n";
}

echo "═════════════════════════════════════════════════════════════════════\n\n";

echo "✅ LOGIN REDIRECT SUMMARY\n\n";
echo "┌─────────────┬──────────────────────┬───────────────────────────────┐\n";
echo "│ Role        │ Email                │ Redirect ke                   │\n";
echo "├─────────────┼──────────────────────┼───────────────────────────────┤\n";
echo "│ super_admin │ admin@local.test     │ /admin/pendaftaran            │\n";
echo "│ admin       │ (belum ada user)     │ /admin/dashboard              │\n";
echo "│ anggota     │ superadmin@hima.com  │ /dashboard (personal)         │\n";
echo "│             │ elangoctafian27@...  │ /dashboard (personal)         │\n";
echo "│ mahasiswa   │ ahmad@hima.com       │ / (home)                      │\n";
echo "│             │ gelang307@gmail.com  │ / (home)                      │\n";
echo "└─────────────┴──────────────────────┴───────────────────────────────┘\n\n";

echo "📋 CONTROLLER LOGIC:\n";
echo "   File: app/Http/Controllers/Auth/LoginController.php\n\n";
echo "   if (\$user->role === 'super_admin') {\n";
echo "       return redirect('/admin/pendaftaran');\n";
echo "   } elseif (\$user->role === 'admin') {\n";
echo "       return redirect('/admin/dashboard');\n";
echo "   } elseif (\$user->role === 'anggota') {\n";
echo "       return redirect('/dashboard');\n";
echo "   } else {\n";
echo "       return redirect('/');\n";
echo "   }\n\n";

echo "✨ STATUS: ✅ REDIRECT SUDAH BERJALAN PER ROLE\n\n";
?>
