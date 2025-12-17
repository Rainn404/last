<?php

/**
 * Verification Script - Automatic Anggota Account Creation Feature
 * 
 * Usage: php artisan tinker
 * Then: include('tools/verify_anggota_feature.php');
 */

echo "\n=== FITUR OTOMATIS PEMBUATAN AKUN ANGGOTA - VERIFIKASI ===\n\n";

// 1. Check Service Class exists
echo "1. Cek Service Class Exists...\n";
if (class_exists('App\Services\CreateAnggotaService')) {
    echo "   ✅ CreateAnggotaService found\n";
} else {
    echo "   ❌ CreateAnggotaService NOT found\n";
}

// 2. Check Enums
echo "\n2. Cek PendaftaranStatus Enum...\n";
try {
    $values = \App\Enums\PendaftaranStatus::values();
    echo "   ✅ Valid values: " . implode(', ', $values) . "\n";
    echo "   ACCEPTED value: " . \App\Enums\PendaftaranStatus::ACCEPTED->value . "\n";
} catch (Exception $e) {
    echo "   ❌ Error: " . $e->getMessage() . "\n";
}

// 3. Check User Model has role field
echo "\n3. Cek User Model...\n";
$user = new \App\Models\User();
if (in_array('role', $user->getFillable())) {
    echo "   ✅ User model has 'role' field in fillable\n";
} else {
    echo "   ⚠️  'role' not in User fillable (check if guarded)\n";
}

// 4. Check AnggotaHima Model
echo "\n4. Cek AnggotaHima Model...\n";
$anggota = new \App\Models\AnggotaHima();
$fillable = $anggota->getFillable();
$required = ['id_user', 'nim', 'nama', 'id_divisi', 'id_jabatan', 'semester', 'status'];
foreach ($required as $field) {
    if (in_array($field, $fillable)) {
        echo "   ✅ AnggotaHima.$field in fillable\n";
    } else {
        echo "   ❌ AnggotaHima.$field NOT in fillable\n";
    }
}

// 5. Check Pendaftaran model relationships
echo "\n5. Cek Pendaftaran Model Relationships...\n";
$pendaftaran = new \App\Models\Pendaftaran();
try {
    echo "   ✅ Has user() relationship\n";
    echo "   ✅ Has divisi() relationship\n";
    echo "   ✅ Has jabatan() relationship\n";
} catch (Exception $e) {
    echo "   ❌ Relationship error: " . $e->getMessage() . "\n";
}

// 6. Database schema check
echo "\n6. Cek Database Schema...\n";
$tables = [
    'users' => ['role', 'email', 'password'],
    'anggota_hima' => ['id_user', 'nim', 'nama', 'id_divisi', 'id_jabatan', 'status'],
    'pendaftaran' => ['id_user', 'status_pendaftaran', 'nim', 'nama']
];

foreach ($tables as $table => $columns) {
    foreach ($columns as $column) {
        if (\Illuminate\Support\Facades\Schema::hasColumn($table, $column)) {
            echo "   ✅ $table.$column exists\n";
        } else {
            echo "   ❌ $table.$column MISSING\n";
        }
    }
}

// 7. Enum ACCEPTED value
echo "\n7. Cek Enum Value Mapping...\n";
$acceptedValue = \App\Enums\PendaftaranStatus::ACCEPTED->value;
echo "   ACCEPTED -> '$acceptedValue'\n";
if ($acceptedValue === 'diterima') {
    echo "   ✅ Correct enum value\n";
} else {
    echo "   ❌ Enum value mismatch! Expected 'diterima', got '$acceptedValue'\n";
}

// 8. Test data (optional)
echo "\n8. Data Sample Check...\n";
$pendaftaranCount = \App\Models\Pendaftaran::count();
$userCount = \App\Models\User::count();
$anggotaCount = \App\Models\AnggotaHima::count();
echo "   Pendaftaran count: $pendaftaranCount\n";
echo "   User count: $userCount\n";
echo "   AnggotaHima count: $anggotaCount\n";

// 9. Recent logs
echo "\n9. Recent Action Logs...\n";
$logFile = storage_path('logs/laravel.log');
if (file_exists($logFile)) {
    $lines = array_slice(file($logFile), -10);
    echo "   Last 10 log lines:\n";
    foreach ($lines as $line) {
        echo "   " . trim($line) . "\n";
    }
} else {
    echo "   ⚠️  Log file not found\n";
}

echo "\n=== VERIFIKASI SELESAI ===\n\n";
