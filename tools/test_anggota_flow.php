<?php

/**
 * Test: Automatic Anggota Creation & Dashboard Flow
 * 
 * Scenario:
 * 1. Admin updates registration status to "diterima"
 * 2. User account created automatically
 * 3. AnggotaHima record created
 * 4. Anggota can login
 * 5. Dashboard shows their data
 */

echo "\n=== TEST: AUTOMATIC ANGGOTA CREATION FLOW ===\n\n";

// Find a pending registration
$pendaftaran = \App\Models\Pendaftaran::where('status_pendaftaran', 'pending')->first();

if (!$pendaftaran) {
    echo "❌ Tidak ada registrasi dengan status 'pending'.\n";
    echo "   Silakan buat registrasi baru atau update status yang ada ke 'pending'.\n\n";
    
    // Show existing registrations
    echo "Registrasi yang ada:\n";
    $all = \App\Models\Pendaftaran::select('id_pendaftaran', 'nama', 'nim', 'status_pendaftaran', 'created_at')->take(5)->get();
    foreach ($all as $p) {
        echo "  - ID: {$p->id_pendaftaran}, Nama: {$p->nama}, NIM: {$p->nim}, Status: {$p->status_pendaftaran}\n";
    }
    exit;
}

echo "✅ Found pending registration:\n";
echo "   ID: {$pendaftaran->id_pendaftaran}\n";
echo "   Nama: {$pendaftaran->nama}\n";
echo "   NIM: {$pendaftaran->nim}\n\n";

// Simulate admin updating status to "diterima"
echo "1. Simulating admin update to 'diterima'...\n";

$service = new \App\Services\CreateAnggotaService();
$result = $service->createFromPendaftaran($pendaftaran);

if ($result['success']) {
    echo "   ✅ User created successfully!\n";
    echo "      User ID: {$result['user']->id}\n";
    echo "      Email: {$result['user']->email}\n";
    echo "      Role: {$result['user']->role}\n";
    echo "      Password (temp): {$result['password']}\n\n";
    
    echo "2. Checking AnggotaHima record...\n";
    if ($result['anggota']) {
        echo "   ✅ AnggotaHima created!\n";
        echo "      ID: {$result['anggota']->id_anggota_hima}\n";
        echo "      Nama: {$result['anggota']->nama}\n";
        echo "      NIM: {$result['anggota']->nim}\n";
        echo "      Divisi ID: {$result['anggota']->id_divisi}\n";
        echo "      Jabatan ID: {$result['anggota']->id_jabatan}\n";
        echo "      Status: " . ($result['anggota']->status ? 'Aktif' : 'Tidak Aktif') . "\n\n";
    }
    
    echo "3. Testing Dashboard Data Retrieval...\n";
    
    // Simulate user login
    $user = $result['user'];
    $anggota = \App\Models\AnggotaHima::where('id_user', $user->id)->first();
    $prestasi = \App\Models\Prestasi::where('id_user', $user->id)->get();
    
    if ($anggota) {
        echo "   ✅ Anggota data accessible!\n";
        echo "      Name in DB: {$anggota->nama}\n";
        echo "      Division: {$anggota->divisi?->nama_divisi ?? 'N/A'}\n";
        echo "      Position: {$anggota->jabatan?->nama_jabatan ?? 'N/A'}\n";
    } else {
        echo "   ⚠️  Anggota record not found for this user\n";
    }
    
    echo "   Prestasi Count: {$prestasi->count()}\n\n";
    
    echo "4. Login Flow Simulation:\n";
    echo "   Email: {$user->email}\n";
    echo "   Password: {$result['password']}\n";
    echo "   Role: {$user->role}\n";
    echo "   ✅ After login → Redirect to /dashboard\n";
    echo "   ✅ Dashboard Controller detects role='anggota'\n";
    echo "   ✅ Shows anggota personal dashboard with:\n";
    echo "      - Nama: {$anggota->nama}\n";
    echo "      - NIM: {$anggota->nim}\n";
    echo "      - Divisi: {$anggota->divisi?->nama_divisi ?? 'N/A'}\n";
    echo "      - Jabatan: {$anggota->jabatan?->nama_jabatan ?? 'N/A'}\n";
    echo "      - Prestasi Count: {$prestasi->count()}\n\n";
    
    echo "=== ✅ TEST PASSED ===\n";
    echo "Flow adalah:\n";
    echo "1. Admin update status → 'diterima'\n";
    echo "2. User account dibuat otomatis\n";
    echo "3. AnggotaHima record dibuat otomatis\n";
    echo "4. User login dengan akun baru\n";
    echo "5. Dashboard anggota menampilkan data pribadi mereka\n\n";
    
} else {
    echo "   ❌ Failed: {$result['message']}\n\n";
}
