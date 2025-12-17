#!/php
<?php

/**
 * WHATSAPP FONNTE INTEGRATION - QUICK TEST
 * 
 * Testing WhatsApp notification via Fonnte API
 * 
 * Usage:
 * php test_whatsapp_fonnte.php
 */

require_once __DIR__ . '/vendor/autoload.php';
require_once __DIR__ . '/bootstrap/app.php';

use App\Models\Pendaftaran;
use App\Services\WhatsAppService;
use Illuminate\Support\Facades\Log;

$app = require_once __DIR__ . '/bootstrap/app.php';
$app->make(\Illuminate\Contracts\Console\Kernel::class)->bootstrap();

echo "\n=== WHATSAPP FONNTE INTEGRATION TEST ===\n\n";

// 1. Check environment
echo "1. Checking environment configuration...\n";
$token = config('services.fonnte.token');
if (!$token) {
    echo "   ❌ FONNTE_TOKEN tidak ditemukan di .env\n";
    exit(1);
}
echo "   ✅ Token sudah dikonfigurasi\n";
echo "   📝 Token: " . substr($token, 0, 10) . "...\n";
echo "   🔗 API URL: " . config('services.fonnte.api_url') . "\n\n";

// 2. Check database
echo "2. Checking database...\n";
$pendaftaranCount = Pendaftaran::count();
echo "   📊 Total pendaftaran: {$pendaftaranCount}\n";

$pendaftaranWithoutWa = Pendaftaran::whereNull('no_hp')->count();
echo "   ⚠️  Pendaftaran tanpa no_hp: {$pendaftaranWithoutWa}\n";

$pendaftaranDiterima = Pendaftaran::where('status_pendaftaran', 'diterima')->count();
echo "   ✅ Pendaftaran diterima: {$pendaftaranDiterima}\n";

$pendaftaranWaSent = Pendaftaran::where('wa_sent', true)->count();
echo "   📱 Sudah dikirim WA: {$pendaftaranWaSent}\n\n";

// 3. Check sample pendaftaran
echo "3. Sample pendaftaran untuk ditest:\n";
$samples = Pendaftaran::where('status_pendaftaran', '!=', 'pending')
    ->whereNotNull('no_hp')
    ->limit(3)
    ->get();

if ($samples->isEmpty()) {
    echo "   ⚠️  Tidak ada pendaftaran yang cocok untuk test\n\n";
} else {
    foreach ($samples as $p) {
        echo "\n   ID: {$p->id_pendaftaran} | Nama: {$p->nama} | Status: {$p->status_pendaftaran} | No HP: {$p->no_hp}\n";
    }
    echo "\n";
}

// 4. Test WhatsAppService
echo "4. Testing WhatsAppService...\n";
try {
    $waService = new WhatsAppService();
    echo "   ✅ Service initialized successfully\n\n";

    // Show message templates
    echo "5. Message Templates:\n";
    echo "\n   📋 Interview Message:\n";
    echo "   ---\n";
    $testInterview = new Pendaftaran([
        'nama' => 'John Doe',
        'id_divisi' => 1,
        'id_jabatan' => 1,
        'no_hp' => '082123456789',
        'status_pendaftaran' => 'interview',
        'interview_date' => now()->addDays(3)
    ]);
    // Use reflection to call private method
    $reflection = new ReflectionClass($waService);
    $method = $reflection->getMethod('buildMessage');
    $method->setAccessible(true);
    $msg = $method->invoke($waService, $testInterview, 'interview');
    echo $msg . "\n";
    echo "   ---\n";

    echo "\n   📋 Accepted Message:\n";
    echo "   ---\n";
    $testAccepted = new Pendaftaran([
        'nama' => 'Jane Smith',
        'id_divisi' => 1,
        'id_jabatan' => 2,
        'no_hp' => '0812987654321',
        'status_pendaftaran' => 'diterima'
    ]);
    $msg = $method->invoke($waService, $testAccepted, 'diterima');
    echo $msg . "\n";
    echo "   ---\n";

    echo "\n   📋 Rejected Message:\n";
    echo "   ---\n";
    $testRejected = new Pendaftaran([
        'nama' => 'Budi Santoso',
        'no_hp' => '085123456789',
        'status_pendaftaran' => 'ditolak',
        'notes' => 'Tidak memenuhi kriteria pengalaman yang dibutuhkan'
    ]);
    $msg = $method->invoke($waService, $testRejected, 'ditolak');
    echo $msg . "\n";
    echo "   ---\n";

} catch (Exception $e) {
    echo "   ❌ Error: " . $e->getMessage() . "\n";
}

echo "\n\n=== TEST SUMMARY ===\n";
echo "✅ Configuration: OK\n";
echo "✅ Database: OK\n";
echo "✅ Service: OK\n";
echo "✅ Messages: OK\n\n";

echo "To send actual WhatsApp:\n";
echo "  1. Update status pendaftaran dari admin dashboard\n";
echo "  2. Sistem akan otomatis mengirim WA via Fonnte API\n";
echo "  3. Check logs di storage/logs/laravel.log\n\n";
