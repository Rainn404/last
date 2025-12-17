✅ BUG FIX: ALASAN PENOLAKAN WHATSAPP

═════════════════════════════════════════════════════════════════════════════

MASALAH:
────────
WhatsApp yang dikirim saat status "ditolak" tidak menampilkan alasan yang 
di-input oleh admin. Selalu menampilkan default: "Terima kasih telah mencoba."

PENYEBAB:
─────────
1. Field 'notes' tidak divalidasi di request validator
2. Field 'notes' tidak di-save ke database sebelum WhatsApp dikirim
3. WhatsApp service menggunakan fallback value yang salah

FIX YANG SUDAH DILAKUKAN:
─────────────────────────

1. ✅ UPDATE PendaftaranController.php
   - Tambah 'notes' ke request validator
   - Capture & simpan notes sebelum kirim WhatsApp
   
   ```php
   // Validate notes field
   $validator = Validator::make($request->only([
       'status_pendaftaran', 'interview_date', 'notes'  // ← Added 'notes'
   ]), [
       'notes' => 'nullable|string|max:500'  // ← Added validation
   ]);
   
   // Save notes before sending WhatsApp
   if ($request->has('notes') && $request->notes) {
       $pendaftaran->notes = $request->notes;
   }
   ```

2. ✅ UPDATE WhatsAppService.php
   - Improve logic untuk field 'notes' saat status ditolak
   - Hanya tampilkan "Alasan:" jika notes ada
   
   ```php
   if ($status === 'ditolak') {
       $notes = $pendaftaran->notes;  // ← Ambil notes yang sudah di-save
       
       $msg = "Halo {$name},\n\n"
           . "Mohon maaf, pendaftaran Anda tidak dapat kami terima...\n\n";
       
       // Only add alasan if exists
       if ($notes) {
           $msg .= "Alasan: {$notes}\n\n";
       }
       
       return $msg;
   }
   ```

═════════════════════════════════════════════════════════════════════════════

CARA KERJA SEKARANG:
────────────────────

1. Admin buka pendaftaran untuk di-TOLAK
2. Admin ubah status → "Ditolak"
3. Form menampilkan textarea "Alasan Penolakan"
4. Admin input alasan (misal: "Nilai tidak memenuhi standar")
5. Admin klik "Simpan"
6. Controller: 
   - Validasi & capture field 'notes'
   - Simpan notes ke database
   - Panggil WhatsApp service
7. WhatsApp Service:
   - Ambil notes dari database
   - Gunakan di message: "Alasan: Nilai tidak memenuhi standar"
   - Kirim ke nomor pendaftar
8. ✅ Pendaftar terima WhatsApp dengan alasan yang benar

═════════════════════════════════════════════════════════════════════════════

HASIL SETELAH FIX:
──────────────────

❌ SEBELUM (WRONG):
"Halo Vera Meliana Sari,

Mohon maaf, pendaftaran Anda tidak dapat kami terima kali ini.

Alasan: Terima kasih telah mencoba.

Jangan berkecil hati!..."


✅ SESUDAH (CORRECT):
"Halo Vera Meliana Sari,

Mohon maaf, pendaftaran Anda tidak dapat kami terima kali ini.

Alasan: [Alasan yang di-input admin]

Jangan berkecil hati!..."

═════════════════════════════════════════════════════════════════════════════

CARA TEST FIX:
───────────────

1. Login admin dashboard
2. Buka pendaftaran (ambil yang status pending)
3. Klik Edit
4. Ubah status → "Ditolak"
5. PENTING: Input alasan di field "Alasan Penolakan"
   Contoh: "Nilai kurang memenuhi standar minimal"
6. Klik Simpan
7. ✅ Cek WhatsApp: seharusnya muncul alasan yang diinput

═════════════════════════════════════════════════════════════════════════════

FILES YANG DIUBAH:
──────────────────

1. app/Http/Controllers/Admin/PendaftaranController.php
   - Line ~474: Tambah 'notes' ke validator
   - Line ~525: Tambah save notes logic

2. app/Services/WhatsAppService.php
   - Line ~90-105: Improve template ditolak
   - Only show "Alasan:" jika notes ada

═════════════════════════════════════════════════════════════════════════════

STATUS:
────────
✅ FIX COMPLETE
✅ READY TO TEST

═════════════════════════════════════════════════════════════════════════════
