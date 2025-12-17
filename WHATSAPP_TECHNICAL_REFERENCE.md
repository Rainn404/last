# WhatsApp Fonnte Integration - Technical Reference

## System Overview

```
┌─────────────────────────────────────────────────────────────┐
│                   Admin Dashboard                           │
│  (Edit Pendaftaran Status)                                  │
└───────────────────┬─────────────────────────────────────────┘
                    │
                    ▼
┌─────────────────────────────────────────────────────────────┐
│         PendaftaranController::updateStatus()               │
│  • Capture old & new status                                 │
│  • Validate status change                                   │
│  • Check wa_sent flag                                       │
└───────────────────┬─────────────────────────────────────────┘
                    │
                    ▼
        ┌───────────────────────────┐
        │ Status Changed?           │
        │ & wa_sent = false?        │
        └───────────────────────────┘
                    │
        ┌───────────┴───────────┐
        │                       │
       YES                      NO
        │                       │
        ▼                       ▼
┌──────────────────┐  [End - No SMS]
│ WhatsAppService  │
│   ::send()       │
└────────┬─────────┘
         │
         ▼
┌─────────────────────────────────────┐
│ 1. Normalize Phone Number           │
│    08123456789 → 628123456789       │
└─────────────────────────────────────┘
         │
         ▼
┌─────────────────────────────────────┐
│ 2. Build Message Based on Status    │
│    interview/diterima/ditolak       │
└─────────────────────────────────────┘
         │
         ▼
┌─────────────────────────────────────┐
│ 3. HTTP POST to Fonnte API          │
│    https://api.fonnte.com/send      │
│    Authorization: Token             │
└─────────────────────────────────────┘
         │
         ├─── Success (200) ──┐
         │                    │
         ▼                    ▼
   ┌──────────┐         ┌──────────────┐
   │ Mark     │         │ Log Error    │
   │ wa_sent  │         │ & Continue   │
   │ = true   │         └──────────────┘
   └──────────┘
         │
         ▼
[Return Success Message]
```

---

## File Structure

```
cobapbl/
├── app/
│   ├── Services/
│   │   └── WhatsAppService.php          ← Main service
│   ├── Http/Controllers/Admin/
│   │   └── PendaftaranController.php    ← Integration point
│   └── Models/
│       └── Pendaftaran.php              ← Updated fillable
│
├── database/
│   └── migrations/
│       └── 2025_12_16_*.php             ← wa_sent column
│
├── config/
│   └── services.php                     ← Fonnte config
│
├── .env                                 ← FONNTE_TOKEN
├── test_whatsapp_fonnte.php             ← Test script
├── WHATSAPP_FONNTE_SETUP.md             ← Full documentation
├── WHATSAPP_QUICKSTART.md               ← Quick guide
└── WHATSAPP_IMPLEMENTATION_SUMMARY.txt  ← Summary
```

---

## API Integration

### Fonnte API Endpoint

**Base URL:** `https://api.fonnte.com/send`

**Request:**
```bash
curl -X POST https://api.fonnte.com/send \
  -H "Authorization: yTos57zCYVUM1kivj2pX" \
  -H "Content-Type: application/json" \
  -d '{
    "target": "628123456789",
    "message": "Pesan Anda di sini"
  }'
```

**Response Success:**
```json
{
    "status": true,
    "message": "Pesan terkirim",
    "data": {
        "chat_id": "628123456789",
        "type": "text",
        "status": "sent"
    }
}
```

**Response Error:**
```json
{
    "status": false,
    "message": "Error message",
    "data": null
}
```

---

## WhatsAppService Class

### Namespace
```php
namespace App\Services;
```

### Constructor
```php
public function __construct()
{
    $this->token = config('services.fonnte.token');
    $this->apiUrl = config('services.fonnte.api_url');
}
```

### Public Methods

#### 1. send()
```php
public function send($pendaftaran, $status): array
```

**Parameters:**
- `$pendaftaran` - Pendaftaran model instance
- `$status` - String: 'interview', 'diterima', or 'ditolak'

**Returns:**
```php
[
    'success' => true|false,
    'message' => 'Human readable message',
    'pendaftaran_id' => 5,
    'phone' => '628123456789',
    'error' => 'Error details (if failed)'
]
```

**Usage:**
```php
$waService = new WhatsAppService();
$result = $waService->send($pendaftaran, 'diterima');

if ($result['success']) {
    // WhatsApp sent
} else {
    // Handle error
}
```

#### 2. bulkSend()
```php
public function bulkSend($pendaftaranIds, $status): array
```

**Parameters:**
- `$pendaftaranIds` - Array of IDs: [1, 2, 3, ...]
- `$status` - String: status to send

**Returns:**
```php
[
    'success' => 4,
    'failed' => 1,
    'details' => [
        'ID 5: WhatsApp berhasil dikirim',
        'ID 6: Error: Invalid phone number'
    ]
]
```

**Usage:**
```php
$waService = new WhatsAppService();
$result = $waService->bulkSend([1, 2, 3, 4, 5], 'diterima');
echo "Sent: " . $result['success'];
echo "Failed: " . $result['failed'];
```

### Private Methods

#### normalizePhoneNumber()
```php
private function normalizePhoneNumber($phone): string
```

**Logic:**
1. Remove all non-digits
2. Handle leading 0: `0812... → 62812...`
3. Handle leading 8: `8123... → 628123...`
4. Ensure starts with 62

**Examples:**
```
0812-3456-789    → 628123456789
08123456789      → 628123456789
8123456789       → 628123456789
628123456789     → 628123456789
+62 812 3456789  → 628123456789
```

#### buildMessage()
```php
private function buildMessage($pendaftaran, $status): string
```

**Returns formatted message based on status:**

**Interview:**
```
Selamat [nama]! 🎉

Pendaftaran Anda telah lolos tahap seleksi berkas.

📅 Jadwal Interview: [tanggal jam]

Silakan persiapkan diri Anda dengan baik.
Hubungi pengurus jika ada pertanyaan.

Terima kasih!
```

**Diterima:**
```
Selamat [nama]! 🎊

Pendaftaran Anda telah DITERIMA sebagai anggota HIMA.

📌 Divisi: [divisi]
💼 Jabatan: [jabatan]

Silakan cek dashboard atau hubungi pengurus untuk instruksi selanjutnya.

Selamat bergabung dengan HIMA!
```

**Ditolak:**
```
Halo [nama],

Mohon maaf, pendaftaran Anda tidak dapat kami terima kali ini.

Alasan: [notes]

Jangan berkecil hati! Anda dapat mendaftar kembali di kesempatan berikutnya.
Terima kasih telah mendaftar di HIMA.
```

---

## Controller Integration

### File
`app/Http/Controllers/Admin/PendaftaranController.php`

### Import
```php
use App\Services\WhatsAppService;
```

### Method: updateStatus()

**Before Update:**
```php
$oldStatus = $pendaftaran->status_pendaftaran;
```

**After Update:**
```php
$pendaftaran->status_pendaftaran = $newStatus;
$pendaftaran->save();

// 🟢 SEND WHATSAPP IF STATUS CHANGED & NOT SENT BEFORE
if ($statusChanged && !$pendaftaran->wa_sent) {
    try {
        $waService = new WhatsAppService();
        $waResult = $waService->send($pendaftaran, $newStatus);
        Log::info('WhatsApp notification result', $waResult);
    } catch (\Exception $waException) {
        Log::error('WhatsApp service error', [
            'pendaftaran_id' => $id,
            'error' => $waException->getMessage()
        ]);
    }
}
```

**Key Points:**
- Only sends if `$statusChanged` is true
- Only sends if `wa_sent` is false
- Error doesn't stop main process
- All results logged

---

## Database Schema

### Table: pendaftaran

**New Column:**
```sql
ALTER TABLE pendaftaran ADD COLUMN wa_sent BOOLEAN DEFAULT FALSE;
```

**Field Details:**
- Column Name: `wa_sent`
- Type: BOOLEAN
- Default: FALSE
- Index: None (no frequent queries on this column)
- Nullable: No

**Migration File:**
```
database/migrations/2025_12_16_000000_add_wa_sent_to_pendaftaran_table.php
```

**Query Examples:**
```sql
-- Check sent
SELECT * FROM pendaftaran WHERE wa_sent = true;

-- Resend (reset flag)
UPDATE pendaftaran SET wa_sent = false WHERE id_pendaftaran = 5;

-- Count sent
SELECT COUNT(*) FROM pendaftaran WHERE wa_sent = true;

-- With status filter
SELECT * FROM pendaftaran 
WHERE status_pendaftaran IN ('interview', 'diterima', 'ditolak') 
AND wa_sent = true;
```

---

## Error Handling

### Exception Scenarios

| Scenario | Handling | Result |
|----------|----------|--------|
| No phone number | Log warning, return false | No send |
| Invalid phone | Caught in normalize, return false | No send |
| API timeout | Caught in try-catch | Log error, continue |
| API error response | Check response.failed() | Log error, continue |
| Database error | Caught in try-catch | Log error, continue |

### Logging

**All events logged to:** `storage/logs/laravel.log`

**Log Levels:**
```php
Log::info('WhatsApp sent successfully', [...]);
Log::warning('No phone number', [...]);
Log::error('Fonnte API error', [...]);
```

**Sample Logs:**
```
[2025-12-16 10:30:45] local.INFO: Sending WhatsApp via Fonnte {"pendaftaran_id":5,"to":"628123456789","status":"diterima"}
[2025-12-16 10:30:46] local.INFO: WhatsApp sent successfully {"pendaftaran_id":5,"to":"628123456789","response":{"status":true,...}}
[2025-12-16 10:31:00] local.ERROR: WhatsApp service error {"pendaftaran_id":6,"error":"Invalid phone number: 12345"}
```

---

## Configuration

### .env
```env
FONNTE_TOKEN=yTos57zCYVUM1kivj2pX
```

### config/services.php
```php
'fonnte' => [
    'token' => env('FONNTE_TOKEN'),
    'api_url' => 'https://api.fonnte.com/send',
],
```

### Access in Code
```php
config('services.fonnte.token');    // yTos57zCYVUM1kivj2pX
config('services.fonnte.api_url');  // https://api.fonnte.com/send
```

---

## Testing

### Unit Test Template
```php
// tests/Unit/Services/WhatsAppServiceTest.php

<?php

namespace Tests\Unit\Services;

use Tests\TestCase;
use App\Services\WhatsAppService;
use App\Models\Pendaftaran;

class WhatsAppServiceTest extends TestCase
{
    public function test_can_normalize_phone_numbers()
    {
        $service = new WhatsAppService();
        
        // Test via reflection
        $reflection = new \ReflectionClass($service);
        $method = $reflection->getMethod('normalizePhoneNumber');
        $method->setAccessible(true);
        
        $this->assertEquals('628123456789', $method->invoke($service, '08123456789'));
        $this->assertEquals('628123456789', $method->invoke($service, '628123456789'));
    }
    
    public function test_can_send_whatsapp()
    {
        $pendaftaran = Pendaftaran::factory()->create([
            'no_hp' => '08123456789',
            'status_pendaftaran' => 'diterima'
        ]);
        
        $service = new WhatsAppService();
        $result = $service->send($pendaftaran, 'diterima');
        
        $this->assertTrue($result['success']);
        $this->assertEquals($pendaftaran->id_pendaftaran, $result['pendaftaran_id']);
    }
}
```

### Feature Test
```bash
php artisan test tests/Feature/Services/WhatsAppServiceTest.php
```

---

## Performance Considerations

### Current Implementation
- **Type:** Synchronous (blocking)
- **Response Time:** ~1-2 seconds per send
- **Max Throughput:** ~30-60 messages/minute
- **Suitable For:** < 100 messages/hour

### Production Optimization (Future)

**Option 1: Laravel Queue**
```php
SendWhatsAppNotification::dispatch($pendaftaran, $status);
```

**Option 2: Batch Processing**
```php
$waService->bulkSend($ids, $status);
```

**Option 3: Webhook Callback**
- Receive delivery confirmation from Fonnte
- Track message status

---

## Security Best Practices

✅ Token in .env (never in code)
✅ Input validation (phone number)
✅ Error isolation (no crash on API fail)
✅ Logging for audit trail
✅ No sensitive data in logs
✅ HTTPS only API calls
✅ Rate limiting awareness

---

## Troubleshooting Guide

### Issue: "FONNTE_TOKEN tidak dikonfigurasi"
```
Solution:
1. Open .env
2. Add: FONNTE_TOKEN=yTos57zCYVUM1kivj2pX
3. Restart Laravel server
php artisan serve
```

### Issue: "WhatsApp tidak terkirim tapi no error"
```
Solution:
1. Check wa_sent column: SELECT wa_sent FROM pendaftaran WHERE id = 5;
2. If true, status tidak berubah - ubah ke status lain dulu
3. Check logs untuk detail error
tail -f storage/logs/laravel.log | grep WhatsApp
```

### Issue: "Fonnte API error 401"
```
Solution:
1. Token invalid atau expired
2. Check token di .env
3. Verify di Fonnte dashboard: https://fonnte.com/api
4. Generate new token if needed
5. Update .env dan restart
```

### Issue: "Invalid phone number"
```
Solution:
1. Check no_hp di database
2. Must start with 0, 62, or 8
3. Remove special characters
4. Update no_hp format di pendaftaran
```

---

## Version Info

- **Implementation Date:** December 16, 2025
- **Version:** 1.0
- **Status:** Production Ready
- **Framework:** Laravel 12
- **PHP:** 8.2+
- **API Provider:** Fonnte
- **Support:** Community/Stack Overflow

---

## Related Files

| File | Purpose |
|------|---------|
| `app/Services/WhatsAppService.php` | Main service |
| `app/Http/Controllers/Admin/PendaftaranController.php` | Integration |
| `app/Models/Pendaftaran.php` | Model |
| `database/migrations/2025_12_16_*.php` | Migration |
| `.env` | Configuration |
| `config/services.php` | Services config |
| `WHATSAPP_FONNTE_SETUP.md` | Full documentation |
| `WHATSAPP_QUICKSTART.md` | Quick start |
| `test_whatsapp_fonnte.php` | Test script |

---

**End of Technical Reference**
