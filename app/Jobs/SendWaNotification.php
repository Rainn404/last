<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use App\Models\Pendaftaran;
use App\Models\Divisi;
use App\Models\Jabatan;

class SendWaNotification implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $pendaftaranId;
    protected $status;

    /**
     * Create a new job instance.
     *
     * @param int $pendaftaranId ID of the registration record
     * @param string $status Registration status ('diterima' or 'ditolak')
     */
    public function __construct($pendaftaranId, $status)
    {
        $this->pendaftaranId = $pendaftaranId;
        $this->status = $status;
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        Log::info('SendWaNotification job started', ['pendaftaran_id' => $this->pendaftaranId, 'status' => $this->status]);

        // Load registration with related data
        $pendaftaran = Pendaftaran::with('user')
            ->find($this->pendaftaranId);

        if (!$pendaftaran) {
            Log::warning('Pendaftaran not found for job', ['id' => $this->pendaftaranId]);
            return;
        }

        // Extract phone number
        $numberRaw = $pendaftaran->no_hp ?? '';
        if (empty($numberRaw)) {
            Log::warning('No phone number for pendaftaran ' . $this->pendaftaranId);
            return;
        }

        // Normalize phone number: remove non-digits
        $digits = preg_replace('/\D+/', '', $numberRaw);
        if (empty($digits)) {
            Log::warning('Invalid phone number for pendaftaran ' . $this->pendaftaranId, ['original' => $numberRaw]);
            return;
        }

        // Convert leading 0 or 8 to 62 (Indonesia assumption)
        if (strpos($digits, '0') === 0) {
            $digits = '62' . ltrim($digits, '0');
        }

        if (strpos($digits, '8') === 0) {
            $digits = '62' . $digits;
        }

        $numberForApi = $digits;

        // Build message based on status
        $name = $pendaftaran->nama ?? 'Calon Anggota';
        $divisiName = null;
        $jabatanName = null;

        try {
            if ($pendaftaran->id_divisi) {
                $divisi = Divisi::find($pendaftaran->id_divisi);
                $divisiName = $divisi->nama ?? null;
            }
            if ($pendaftaran->id_jabatan) {
                $jabatan = Jabatan::find($pendaftaran->id_jabatan);
                $jabatanName = $jabatan->nama ?? null;
            }
        } catch (\Exception $e) {
            // Ignore lookup errors
        }

        if ($this->status === 'diterima') {
            $positionText = '';
            if ($divisiName) $positionText .= "Divisi: {$divisiName}";
            if ($jabatanName) $positionText .= ($positionText ? ' - ' : '') . "Jabatan: {$jabatanName}";

            $message = "Selamat {$name}, pendaftaran Anda telah DITERIMA.";
            if ($positionText) $message .= " Anda ditempatkan pada {$positionText}.";
            $message .= "\nSilakan cek instruksi selanjutnya di dashboard atau hubungi pengurus untuk informasi lebih lanjut.";
        } else {
            $reason = $pendaftaran->alasan_penolakan ?? '';
            $message = "Halo {$name}, mohon maaf pendaftaran Anda TIDAK LULUS.";
            if ($reason) $message .= " Alasan: {$reason}.";
            $message .= "\nTerima kasih sudah mendaftar.";
        }

        // Call WA API
        try {
            $apiUrl = config('services.wa_server.url') ?? 'http://127.0.0.1:5000/send';
            $payload = [
                'number' => $numberForApi,
                'message' => $message
            ];

            Log::info('Calling WA API', ['url' => $apiUrl, 'payload' => $payload]);

            $response = Http::timeout(10)->post($apiUrl, $payload);

            $status = $response->status();
            $body = null;
            try {
                $body = $response->body();
            } catch (\Exception $ex) {
                $body = 'unable to read body: ' . $ex->getMessage();
            }

            if ($response->failed()) {
                Log::error('WA API returned error', ['status' => $status, 'response' => $body, 'pendaftaran_id' => $this->pendaftaranId]);
                // Throw to allow retry
                throw new \Exception('WA API error: ' . $status . ' ' . substr($body, 0, 200));
            } else {
                Log::info('WA notification sent', ['pendaftaran_id' => $this->pendaftaranId, 'to' => $numberForApi, 'response_status' => $status, 'response_body' => substr($body, 0, 200)]);
            }
        } catch (\Exception $e) {
            Log::error('Failed to call WA API for pendaftaran ' . $this->pendaftaranId, ['error' => $e->getMessage()]);
            // Optionally re-throw to mark job as failed for retry
            // throw $e;
        }
    }
}
