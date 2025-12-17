<?php
/**
 * Quick Test: WhatsApp Fonnte Integration
 */

require_once __DIR__ . '/bootstrap/app.php';

use App\Models\Pendaftaran;
use App\Services\WhatsAppService;
use Illuminate\Support\Facades\Schema;

$app = require_once __DIR__ . '/bootstrap/app.php';
$app->make(\Illuminate\Contracts\Console\Kernel::class)->bootstrap();

echo "\n";
echo "╔════════════════════════════════════════════════════════════╗\n";
echo "║     WhatsApp Fonnte Integration - Quick Test             ║\n";
echo "╚════════════════════════════════════════════════════════════╝\n\n";

// Test 1: Configuration
echo "1️⃣  CONFIGURATION CHECK\n";
echo "─────────────────────────────────────────────────────────────\n";

$token = config('services.fonnte.token');
if ($token) {
    echo "✅ FONNTE_TOKEN: " . substr($token, 0, 10) . "...\n";
} else {
    echo "❌ FONNTE_TOKEN: NOT FOUND\n";
}

$apiUrl = config('services.fonnte.api_url');
echo "✅ API URL: " . $apiUrl . "\n\n";

// Test 2: Database
echo "2️⃣  DATABASE CHECK\n";
echo "─────────────────────────────────────────────────────────────\n";

$hasColumn = Schema::hasColumn('pendaftaran', 'wa_sent');
if ($hasColumn) {
    echo "✅ Column 'wa_sent' EXISTS in pendaftaran table\n";
} else {
    echo "❌ Column 'wa_sent' NOT FOUND\n";
}

// Test 3: Service
echo "\n3️⃣  SERVICE CHECK\n";
echo "─────────────────────────────────────────────────────────────\n";

try {
    $service = new WhatsAppService();
    echo "✅ WhatsAppService: Can be instantiated\n";
    
    // Check methods exist
    $methods = ['send', 'bulkSend'];
    foreach ($methods as $method) {
        if (method_exists($service, $method)) {
            echo "✅ Method: $method() exists\n";
        } else {
            echo "❌ Method: $method() NOT FOUND\n";
        }
    }
} catch (\Exception $e) {
    echo "❌ Error: " . $e->getMessage() . "\n";
}

// Test 4: Sample Data
echo "\n4️⃣  SAMPLE DATA CHECK\n";
echo "─────────────────────────────────────────────────────────────\n";

$pendaftaran = Pendaftaran::where('no_hp', '!=', null)
    ->whereNotNull('status_pendaftaran')
    ->first();

if ($pendaftaran) {
    echo "✅ Found sample pendaftaran:\n";
    echo "   ID: " . $pendaftaran->id_pendaftaran . "\n";
    echo "   Nama: " . $pendaftaran->nama . "\n";
    echo "   No HP: " . $pendaftaran->no_hp . "\n";
    echo "   Status: " . $pendaftaran->status_pendaftaran . "\n";
    echo "   wa_sent: " . ($pendaftaran->wa_sent ? "TRUE ✅" : "FALSE") . "\n";
} else {
    echo "⚠️  No pendaftaran with phone number found\n";
}

// Test 5: Test Send (optional)
echo "\n5️⃣  MESSAGE TEMPLATE TEST\n";
echo "─────────────────────────────────────────────────────────────\n";

try {
    $service = new WhatsAppService();
    
    // Create mock pendaftaran for testing messages
    $testData = [
        'interview' => new Pendaftaran([
            'nama' => 'Test User',
            'no_hp' => '08123456789',
            'interview_date' => now()->addDays(3),
        ]),
        'diterima' => new Pendaftaran([
            'nama' => 'Test User',
            'no_hp' => '08123456789',
        ]),
        'ditolak' => new Pendaftaran([
            'nama' => 'Test User',
            'no_hp' => '08123456789',
            'notes' => 'Tidak memenuhi kriteria'
        ]),
    ];
    
    // Use reflection to call private buildMessage method
    $reflection = new ReflectionClass($service);
    $method = $reflection->getMethod('buildMessage');
    $method->setAccessible(true);
    
    foreach ($testData as $status => $obj) {
        $msg = $method->invoke($service, $obj, $status);
        echo "\n📱 Status: $status\n";
        echo "   Length: " . strlen($msg) . " chars\n";
        echo "   Preview: " . substr($msg, 0, 50) . "...\n";
    }
    
} catch (\Exception $e) {
    echo "⚠️  Error testing messages: " . $e->getMessage() . "\n";
}

// Test 6: Summary
echo "\n\n6️⃣  SUMMARY\n";
echo "═════════════════════════════════════════════════════════════\n";

$allOk = $token && $hasColumn && $pendaftaran;

if ($allOk) {
    echo "🟢 INTEGRATION READY\n";
    echo "\nREADY TO USE:\n";
    echo "1. Login to admin dashboard\n";
    echo "2. Go to Pendaftaran\n";
    echo "3. Edit a pendaftaran\n";
    echo "4. Change status → interview/diterima/ditolak\n";
    echo "5. Click Simpan\n";
    echo "6. ✅ WhatsApp will be sent automatically\n";
    echo "\nCHECK LOGS:\n";
    echo "tail -f storage/logs/laravel.log | grep WhatsApp\n";
} else {
    echo "⚠️  Some components missing - check details above\n";
}

echo "\n\n";
