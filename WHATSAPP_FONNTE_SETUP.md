# WhatsApp Fonnte Integration - Documentation

## 🟢 STATUS: PRODUCTION READY

WhatsApp notifikasi otomatis telah terintegrasi dengan Fonnte API untuk sistem pendaftaran.

---

## 📋 FITUR

✅ Mengirim WA otomatis saat admin mengubah status pendaftaran  
✅ Support 3 status: interview, diterima, ditolak  
✅ Otomatis konversi nomor ke format internasional (62xxx)  
✅ Tracking: hanya kirim sekali per pendaftaran (wa_sent flag)  
✅ Graceful error handling - tidak block proses jika WA gagal  
✅ Comprehensive logging  
✅ Production-safe dengan proper configuration

---

## 🔧 IMPLEMENTASI

### 1. Files Created/Updated

#### New Files:
```
app/Services/WhatsAppService.php          ✅ Service untuk kirim WA
database/migrations/2025_12_16_000000_... ✅ Migration untuk kolom wa_sent
test_whatsapp_fonnte.php                  ✅ Test script
WHATSAPP_FONNTE_SETUP.md                  ✅ Documentation (ini)
```

#### Updated Files:
```
.env                                      ✅ Tambah FONNTE_TOKEN
config/services.php                       ✅ Tambah Fonnte config
app/Models/Pendaftaran.php               ✅ Tambah wa_sent ke fillable
app/Http/Controllers/Admin/PendaftaranController.php  ✅ Integrate WhatsAppService
```

### 2. Environment Configuration

Di `.env`:
```env
FONNTE_TOKEN=yTos57zCYVUM1kivj2pX
```

Di `config/services.php`:
```php
'fonnte' => [
    'token' => env('FONNTE_TOKEN'),
    'api_url' => 'https://api.fonnte.com/send',
],
```

### 3. Database Migration

Kolom `wa_sent` ditambahkan ke tabel `pendaftaran`:
```sql
ALTER TABLE pendaftaran ADD COLUMN wa_sent BOOLEAN DEFAULT FALSE;
```

Status migration: ✅ Sudah running

---

## 🚀 CARA KERJA

### Alur Pengiriman WA:

```
Admin Ubah Status
        ↓
PendaftaranController::updateStatus()
        ↓
Check: Status Berubah? + Belum Dikirim WA?
        ↓
WhatsAppService::send()
        ↓
Normalize Phone (628xx → 62xxxxx)
        ↓
Build Message (sesuai status)
        ↓
HTTP POST ke Fonnte API
        ↓
Tandai wa_sent = true
        ↓
Log hasil
```

### Status Message Templates:

#### 📅 Interview Status:
```
Selamat [Nama]! 🎉

Pendaftaran Anda telah lolos tahap seleksi berkas.

📅 Jadwal Interview: [tanggal jam]

Silakan persiapkan diri Anda dengan baik.
Hubungi pengurus jika ada pertanyaan.

Terima kasih!
```

#### ✅ Accepted Status:
```
Selamat [Nama]! 🎊

Pendaftaran Anda telah DITERIMA sebagai anggota HIMA.

📌 Divisi: [nama divisi]
💼 Jabatan: [nama jabatan]

Silakan cek dashboard atau hubungi pengurus untuk instruksi selanjutnya.

Selamat bergabung dengan HIMA!
```

#### ❌ Rejected Status:
```
Halo [Nama],

Mohon maaf, pendaftaran Anda tidak dapat kami terima kali ini.

Alasan: [alasan/notes]

Jangan berkecil hati! Anda dapat mendaftar kembali di kesempatan berikutnya.
Terima kasih telah mendaftar di HIMA.
```

---

## 🧪 TESTING

### Quick Test:
```bash
php test_whatsapp_fonnte.php
```

Output akan menunjukkan:
- ✅ Configuration status
- ✅ Database connection
- ✅ Message templates
- ✅ Ready to send

### Manual Test via Dashboard:

1. Login sebagai super_admin/admin
2. Pergi ke Admin → Pendaftaran
3. Klik tombol edit status pada pendaftaran
4. Ubah status ke: interview, diterima, atau ditolak
5. Klik Save
6. WhatsApp akan otomatis terkirim ke nomor pendaftar

### Check Logs:

```bash
# Real-time log
tail -f storage/logs/laravel.log

# Or view dalam file
storage/logs/laravel.log
```

Expected log entries:
```
[2025-12-16 10:30:45] local.INFO: Sending WhatsApp via Fonnte {"pendaftaran_id":5,"to":"62812345678","status":"diterima"}
[2025-12-16 10:30:46] local.INFO: WhatsApp sent successfully {"pendaftaran_id":5,"to":"62812345678",...}
```

---

## ⚙️ KONFIGURASI LANJUTAN

### Customizing Messages:

Edit `app/Services/WhatsAppService.php` method `buildMessage()`:

```php
private function buildMessage($pendaftaran, $status)
{
    $name = $pendaftaran->nama ?? 'Calon Anggota';

    if ($status === 'interview') {
        // Customize interview message here
        return "Custom message...";
    }
    // ... etc
}
```

### Bulk Send Feature:

```php
$waService = new WhatsAppService();
$result = $waService->bulkSend(
    [1, 2, 3, 4, 5],  // Pendaftaran IDs
    'diterima'          // Status
);

// Result: ['success' => 4, 'failed' => 1, 'details' => [...]]
```

---

## 🛡️ SECURITY & BEST PRACTICES

✅ Token disimpan di `.env` (tidak hardcode)  
✅ Phone number validation sebelum kirim  
✅ Status change verification - hanya kirim jika berbeda  
✅ Duplicate prevention - check wa_sent flag  
✅ Error handling - jangan block proses jika WA gagal  
✅ Comprehensive logging untuk debugging  
✅ Graceful fallback - aplikasi tetap jalan meski WA gagal  

---

## 🐛 TROUBLESHOOTING

### ❌ "FONNTE_TOKEN tidak dikonfigurasi"

**Solusi:**
1. Buka `.env`
2. Pastikan ada: `FONNTE_TOKEN=yTos57zCYVUM1kivj2pX`
3. Jika pakai server, restart aplikasi

### ❌ "Invalid phone number"

**Solusi:**
- Pastikan nomor WA pendaftar valid (dimulai 08 atau sudah 62xxx)
- Cek di database: `SELECT id_pendaftaran, nama, no_hp FROM pendaftaran WHERE no_hp IS NULL;`

### ❌ "Fonnte API error"

**Solusi:**
1. Check Fonnte credit/balance
2. Verify token valid: https://fonnte.com/api
3. Check logs untuk error message detail
4. Ensure internet connection

### ❌ "WhatsApp tidak terkirim tapi status berhasil"

**Solusi:**
1. Check `wa_sent` column: `SELECT id_pendaftaran, wa_sent FROM pendaftaran WHERE id_pendaftaran = 5;`
2. Review logs di `storage/logs/laravel.log`
3. Verify nomor WhatsApp valid di Fonnte dashboard

---

## 📊 DATABASE QUERIES

### Check WA Status:
```sql
SELECT id_pendaftaran, nama, no_hp, status_pendaftaran, wa_sent, validated_at
FROM pendaftaran
WHERE status_pendaftaran IN ('interview', 'diterima', 'ditolak')
ORDER BY validated_at DESC;
```

### Resend WA (Jika perlu):
```sql
UPDATE pendaftaran 
SET wa_sent = FALSE 
WHERE id_pendaftaran = 5;
```

Kemudian ubah status lagi dari dashboard untuk trigger pengiriman.

---

## 📱 FONNTE API INTEGRATION

**Endpoint:** `https://api.fonnte.com/send`

**Method:** POST

**Headers:**
```
Authorization: [FONNTE_TOKEN]
```

**Body:**
```json
{
    "target": "628123456789",
    "message": "Pesan WhatsApp Anda di sini"
}
```

**Response Success (200):**
```json
{
    "status": true,
    "message": "Pesan terkirim"
}
```

---

## 🚦 STATUS PENGIRIMAN

| Status | Terkirim | Keterangan |
|--------|----------|-----------|
| pending | ❌ | Belum ada perubahan status |
| interview | ✅ | WA dengan jadwal interview |
| diterima | ✅ | WA dengan info divisi & jabatan |
| ditolak | ✅ | WA dengan alasan penolakan |

---

## 📝 MAINTENANCE

### Regular Checks:
```bash
# Monitor WhatsApp sending
grep "WhatsApp" storage/logs/laravel.log

# Check failed sends
grep "failed\|error\|Error" storage/logs/laravel.log | grep WhatsApp

# Count total sent
grep "WhatsApp sent successfully" storage/logs/laravel.log | wc -l
```

### Backup Configuration:
```bash
# Backup token (JANGAN push ke git!)
echo "FONNTE_TOKEN=yTos57zCYVUM1kivj2pX" >> .env.backup
```

---

## 🎯 NEXT STEPS (Optional)

1. **Queue Integration** - Gunakan queue untuk async sending
   ```php
   SendWhatsAppNotification::dispatch($pendaftaran, $status);
   ```

2. **Webhook Callback** - Terima status delivery dari Fonnte
   
3. **Message Analytics** - Track open/read rates
   
4. **Scheduled Reminders** - Reminder interview sebelumnya

---

## 📞 SUPPORT

**WhatsApp Integration Version:** 1.0  
**Last Updated:** December 16, 2025  
**Status:** ✅ Production Ready

Untuk bantuan teknis, check logs atau dokumentasi Fonnte API: https://fonnte.com/api
