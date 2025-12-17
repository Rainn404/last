<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use App\Models\Pendaftaran;

class WhatsAppService
{
    private $token;
    private $apiUrl;

    public function __construct()
    {
        $this->token = config('services.fonnte.token');
        $this->apiUrl = config('services.fonnte.api_url');

        if (!$this->token) {
            throw new \Exception('FONNTE_TOKEN tidak dikonfigurasi di .env');
        }
    }

    /**
     * Menormalkan nomor WhatsApp ke format internasional
     * @param string $phone
     * @return string
     */
    private function normalizePhoneNumber($phone)
    {
        // Remove non-digits
        $digits = preg_replace('/\D+/', '', $phone);

        if (empty($digits)) {
            throw new \Exception("Nomor WhatsApp tidak valid: {$phone}");
        }

        // Convert leading 0 to 62
        if (strpos($digits, '0') === 0) {
            $digits = '62' . ltrim($digits, '0');
        }

        // If starts with 8, assume it's Indonesia (add 62)
        if (strpos($digits, '8') === 0) {
            $digits = '62' . $digits;
        }

        // Ensure starts with 62
        if (strpos($digits, '62') !== 0) {
            $digits = '62' . ltrim($digits, '0');
        }

        return $digits;
    }

    /**
     * Build pesan berdasarkan status
     * @param Pendaftaran $pendaftaran
     * @param string $status
     * @return string
     */
    private function buildMessage($pendaftaran, $status)
    {
        $name = $pendaftaran->nama ?? 'Calon Anggota';

        if ($status === 'interview') {
            $interviewDate = $pendaftaran->interview_date 
                ? $pendaftaran->interview_date->format('d M Y') 
                : 'akan ditentukan kemudian';

            return "Selamat {$name}! 🎉\n\nPendaftaran Anda telah lolos tahap seleksi berkas.\n\n"
                . "📅 Jadwal Interview: {$interviewDate}\n\n"
                . "Silakan persiapkan diri Anda dengan baik.\n"
                . "Hubungi pengurus jika ada pertanyaan.\n\n"
                . "Terima kasih!";
        }

        if ($status === 'diterima') {
            $divisiName = $pendaftaran->divisi ? $pendaftaran->divisi->nama_divisi : '';

            $msg = "Selamat {$name}! 🎊\n\n"
                . "Pendaftaran Anda telah DITERIMA sebagai anggota HIMA.\n\n";
            
            // Only show divisi if available
            if ($divisiName) {
                $msg .= "📌 Divisi: {$divisiName}\n\n";
            }
            
            $msg .= "Silakan cek dashboard atau hubungi pengurus untuk instruksi selanjutnya.\n\n"
                . "Selamat bergabung dengan HIMA!";

            return $msg;
        }

        if ($status === 'ditolak') {
            $notes = $pendaftaran->notes;
            
            // Build message
            $msg = "Halo {$name},\n\n"
                . "Mohon maaf, pendaftaran Anda tidak dapat kami terima kali ini.\n\n";
            
            // Add reason if provided
            if ($notes) {
                $msg .= "Alasan: {$notes}\n\n";
            }
            
            $msg .= "Jangan berkecil hati! Anda dapat mendaftar kembali di kesempatan berikutnya.\n"
                . "Terima kasih telah mendaftar di HIMA.";
            
            return $msg;
        }

        return "Update status pendaftaran Anda: {$status}";
    }

    /**
     * Kirim notifikasi WhatsApp
     * @param Pendaftaran $pendaftaran
     * @param string $status
     * @return array
     */
    public function send($pendaftaran, $status)
    {
        try {
            // Validasi nomor WA
            if (empty($pendaftaran->no_hp)) {
                Log::warning('No WhatsApp number', ['pendaftaran_id' => $pendaftaran->id_pendaftaran]);
                return [
                    'success' => false,
                    'message' => 'Nomor WhatsApp tidak tersedia',
                    'pendaftaran_id' => $pendaftaran->id_pendaftaran
                ];
            }

            // Normalize nomor
            $phoneNumber = $this->normalizePhoneNumber($pendaftaran->no_hp);

            // Build message
            $message = $this->buildMessage($pendaftaran, $status);

            // Prepare payload untuk Fonnte
            $payload = [
                'target' => $phoneNumber,
                'message' => $message,
            ];

            Log::info('Sending WhatsApp via Fonnte', [
                'pendaftaran_id' => $pendaftaran->id_pendaftaran,
                'to' => $phoneNumber,
                'status' => $status
            ]);

            // Send via HTTP
            $response = Http::withHeaders([
                'Authorization' => $this->token,
            ])
            ->timeout(15)
            ->post($this->apiUrl, $payload);

            if ($response->failed()) {
                Log::error('Fonnte API error', [
                    'pendaftaran_id' => $pendaftaran->id_pendaftaran,
                    'status_code' => $response->status(),
                    'response' => $response->body()
                ]);

                return [
                    'success' => false,
                    'message' => 'Gagal mengirim WhatsApp',
                    'error' => $response->body(),
                    'pendaftaran_id' => $pendaftaran->id_pendaftaran
                ];
            }

            // Mark as sent
            $pendaftaran->update(['wa_sent' => true]);

            Log::info('WhatsApp sent successfully', [
                'pendaftaran_id' => $pendaftaran->id_pendaftaran,
                'to' => $phoneNumber,
                'status' => $status,
                'response' => $response->json()
            ]);

            return [
                'success' => true,
                'message' => 'WhatsApp berhasil dikirim',
                'pendaftaran_id' => $pendaftaran->id_pendaftaran,
                'phone' => $phoneNumber
            ];

        } catch (\Exception $e) {
            Log::error('WhatsApp send exception', [
                'pendaftaran_id' => $pendaftaran->id_pendaftaran,
                'error' => $e->getMessage()
            ]);

            return [
                'success' => false,
                'message' => 'Error: ' . $e->getMessage(),
                'pendaftaran_id' => $pendaftaran->id_pendaftaran
            ];
        }
    }

    /**
     * Bulk send untuk multiple pendaftar (admin feature)
     * @param array $pendaftaranIds
     * @param string $status
     * @return array
     */
    public function bulkSend($pendaftaranIds, $status)
    {
        $results = [
            'success' => 0,
            'failed' => 0,
            'details' => []
        ];

        foreach ($pendaftaranIds as $id) {
            $pendaftaran = Pendaftaran::find($id);

            if (!$pendaftaran) {
                $results['failed']++;
                $results['details'][] = "Pendaftaran {$id} tidak ditemukan";
                continue;
            }

            $result = $this->send($pendaftaran, $status);

            if ($result['success']) {
                $results['success']++;
            } else {
                $results['failed']++;
                $results['details'][] = "ID {$id}: " . $result['message'];
            }
        }

        return $results;
    }
}
