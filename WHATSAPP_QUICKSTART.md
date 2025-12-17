# WhatsApp Fonnte - Quick Start Guide

## ✅ Installation Complete

WhatsApp notification system sudah fully integrated ke sistem pendaftaran.

---

## 🚀 Getting Started

### 1. Verify Installation (Optional)
```bash
php test_whatsapp_fonnte.php
```

### 2. Send First WhatsApp

**Via Admin Dashboard:**

1. Login → Dashboard Admin
2. Navigate → Pendaftaran
3. Click Edit pada salah satu pendaftaran
4. Ubah "Status Pendaftaran" ke salah satu:
   - Interview
   - Diterima
   - Ditolak
5. Click "Simpan"
6. ✅ WhatsApp akan otomatis terkirim

**Via Command Line (Testing):**
```php
$pendaftaran = \App\Models\Pendaftaran::find(1);
$service = new \App\Services\WhatsAppService();
$result = $service->send($pendaftaran, 'diterima');
dd($result);
```

---

## 📊 Status Configuration

### Message Auto-Sent For:

| Status | Sent Ke | Pesan Berisi |
|--------|---------|--------------|
| interview | Nomor Pendaftar | Jadwal interview |
| diterima | Nomor Pendaftar | Info divisi & jabatan |
| ditolak | Nomor Pendaftar | Alasan penolakan |

---

## 🔍 Check Status

### In Database:
```sql
SELECT id_pendaftaran, nama, status_pendaftaran, wa_sent, validated_at
FROM pendaftaran
WHERE status_pendaftaran IN ('interview', 'diterima', 'ditolak')
ORDER BY validated_at DESC LIMIT 10;
```

### In Logs:
```bash
# Linux/Mac
tail -f storage/logs/laravel.log | grep WhatsApp

# Windows PowerShell
Get-Content storage/logs/laravel.log -Tail 50 | Select-String "WhatsApp"
```

---

## 🛠️ Configuration

### Token Management:

**Current Token:** `yTos57zCYVUM1kivj2pX`

**Location:** `.env` (line 56)

**To Change:**
```env
FONNTE_TOKEN=your_new_token_here
```
Then restart Laravel.

---

## 📝 Customize Messages

Edit: `app/Services/WhatsAppService.php`

Method: `buildMessage()`

Example:
```php
if ($status === 'diterima') {
    return "Selamat! Anda diterima. Custom pesan Anda di sini.";
}
```

---

## 🚨 Troubleshooting

### WhatsApp tidak terkirim?

1. **Check No HP:**
   ```sql
   SELECT id_pendaftaran, nama, no_hp FROM pendaftaran WHERE no_hp IS NULL;
   ```
   Pastikan ada nomor HP.

2. **Check Logs:**
   ```bash
   tail -100 storage/logs/laravel.log | grep -i whatsapp
   ```

3. **Check Fonnte Balance:**
   - Visit: https://fonnte.com/dashboard
   - Ensure balance > 0

4. **Verify Token:**
   Pastikan `FONNTE_TOKEN` di `.env` correct.

### "Status tidak berubah" = WhatsApp tidak terkirim?

**Ini normal!** WhatsApp hanya dikirim jika status BERUBAH.

---

## 📊 Bulk Operations

### Send WhatsApp ke Multiple Pendaftaran:

```php
$service = new \App\Services\WhatsAppService();
$result = $service->bulkSend([1, 2, 3, 4, 5], 'diterima');

// Result:
// [
//   'success' => 4,
//   'failed' => 1,
//   'details' => [...]
// ]
```

---

## 🎯 Best Practices

✅ Always verify nomor HP sebelum send
✅ Check logs untuk troubleshooting
✅ Monitor Fonnte balance
✅ Customize messages sesuai kebutuhan
✅ Test dengan staging database dulu
✅ Backup token di safe place

---

## 📚 Documentation

Full documentation: `WHATSAPP_FONNTE_SETUP.md`
Implementation details: `WHATSAPP_IMPLEMENTATION_SUMMARY.txt`

---

## ⚡ Quick Reference

| Task | Command/Where |
|------|---|
| Test | `php test_whatsapp_fonnte.php` |
| View Logs | `tail -f storage/logs/laravel.log` |
| Check DB | `SELECT * FROM pendaftaran WHERE wa_sent = true;` |
| Send Test | Dashboard → Pendaftaran → Edit Status |
| Config | `.env` line 56 |
| Service | `app/Services/WhatsAppService.php` |

---

## 🟢 Status: PRODUCTION READY

All systems go! WhatsApp integration is fully functional and production-tested.

---

**Last Updated:** December 16, 2025
**Version:** 1.0
**Status:** ✅ Active
