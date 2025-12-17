<?php
require 'vendor/autoload.php';
$app = require_once 'bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use App\Models\Pendaftaran;
use Illuminate\Support\Facades\Storage;

// Test data
$testData = [
    'nama' => 'Test Pendaftar',
    'nim' => '2401301099',
    'semester' => 3,
    'no_hp' => '08123456789',
    'alasan_mendaftar' => 'Saya ingin menjadi bagian dari organisasi HIMA untuk mengembangkan kemampuan kepemimpinan dan networking dengan sesama mahasiswa.',
    'id_divisi' => 1, // Divisi "kader" 
    'alasan_divisi' => 'Saya tertarik dengan divisi kader karena ingin mengembangkan kepemimpinan dan menjadi mentor bagi junior di masa depan.',
    'pengalaman' => 'Sudah aktif di organisasi kampus sebelumnya',
    'skill' => 'Komunikasi, Leadership, Public Speaking',
    'dokumen' => null,
    'status_pendaftaran' => 'pending',
    'submitted_at' => now(),
];

try {
    $pendaftaran = Pendaftaran::create($testData);
    echo "✅ Test pendaftaran berhasil dibuat!\n";
    echo "ID: {$pendaftaran->id_pendaftaran}\n";
    echo "Nama: {$pendaftaran->nama}\n";
    echo "NIM: {$pendaftaran->nim}\n";
    echo "Divisi ID: {$pendaftaran->id_divisi}\n";
    echo "Alasan Divisi: {$pendaftaran->alasan_divisi}\n";
} catch (\Exception $e) {
    echo "❌ Error: " . $e->getMessage() . "\n";
}
