<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Pendaftaran;
use App\Models\Divisi;
use App\Models\Jabatan;
use App\Models\PendaftaranSetting;
use App\Models\User;
use App\Enums\PendaftaranStatus;
use App\Services\CreateAnggotaService;
use App\Services\WhatsAppService;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use App\Jobs\SendWaNotification;

class PendaftaranController extends Controller
{
    /**
     * Menampilkan halaman kelola pendaftaran
     */
    public function index(Request $request)
    {
        // Validasi akses admin (super admin atau admin diperbolehkan)
        if (!Auth::check()) {
            if ($request->ajax() || $request->wantsJson()) {
                return response()->json(['success' => false, 'message' => 'Anda tidak memiliki akses.'], 403);
            }
            return redirect()->route('admin.dashboard')->with('error', 'Anda tidak memiliki akses.');
        }

        try {
            // Ambil settings pendaftaran
            $settings = PendaftaranSetting::first();
            if (!$settings) {
                $settings = $this->createDefaultSettings();
            }

            // Statistik lengkap
            $stats = $this->getPendaftaranStats();

            // Query dengan filter dan search
            $pendaftaran = $this->getFilteredPendaftaran($request);

            // Ambil data divisi dan jabatan untuk form validasi
            $divisi = Divisi::where('status', 1)->get();
            $jabatan = Jabatan::where('status', 1)->get();

            return view('admin.pendaftaran.index', compact(
                'settings', 
                'stats', 
                'pendaftaran', 
                'divisi', 
                'jabatan'
            ));

        } catch (\Exception $e) {
            return redirect()->back()
                ->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }

    /**
     * Menampilkan detail pendaftaran
     */
    public function show($id)
    {
        // Validasi akses admin
        if (!Auth::check()) {
            return response()->json([
                'success' => false,
                'message' => 'Anda tidak memiliki akses.'
            ], 403);
        }

        try {
            $pendaftaran = Pendaftaran::with([
                'user', 
                'validator', 
                'divisi', 
                'jabatan'
            ])->findOrFail($id);

            // Convert to array untuk ensure semua field termasuk
            $data = $pendaftaran->toArray();
            
            return response()->json([
                'success' => true,
                'data' => $data
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Data tidak ditemukan'
            ], 404);
        }
    }

    /**
     * Menampilkan form edit pendaftaran
     */
    public function edit($id)
    {
        // Validasi admin access
        if (!Auth::check()) {
            return response()->json([
                'success' => false,
                'message' => 'Anda tidak memiliki akses.'
            ], 403);
        }

        try {
            $pendaftaran = Pendaftaran::with(['user', 'divisi', 'jabatan'])
                ->findOrFail($id);
                
            $divisi = Divisi::where('status', 1)->get();
            $jabatan = Jabatan::where('status', 'active')->get();
            
            return view('admin.pendaftaran.partials.edit_form', compact(
                'pendaftaran', 
                'divisi', 
                'jabatan'
            ));

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Data tidak ditemukan'
            ], 404);
        }
    }

    /**
     * Update data pendaftaran
     */
    public function update(Request $request, $id)
    {
        // Validasi admin access
        if (!Auth::check()) {
            return redirect()->route('admin.dashboard')->with('error', 'Anda tidak memiliki akses.');
        }

        $pendaftaran = Pendaftaran::findOrFail($id);

        $rules = [
            'nama' => 'required|string|max:255',
            'nim' => 'required|string|max:50|unique:pendaftaran,nim,' . $pendaftaran->id . ',id_pendaftaran',
            'semester' => 'required|integer|min:1|max:14',
            'no_hp' => 'nullable|string|max:20',
            'alasan_mendaftar' => 'required|string|max:1000',
            'pengalaman' => 'nullable|string|max:1000',
            'skill' => 'nullable|string|max:1000',
            'status_pendaftaran' => 'required|in:pending,diterima,ditolak',
            'id_divisi' => 'nullable|required_if:status_pendaftaran,diterima|exists:divisis,id_divisi',
            'id_jabatan' => 'nullable|required_if:status_pendaftaran,diterima|exists:jabatans,id_jabatan',
            'notes' => 'nullable|string|max:1000',
            'dokumen' => 'nullable|file|mimes:pdf,doc,docx|max:2048'
        ];

        $validator = Validator::make($request->all(), $rules);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput()
                ->with('error', 'Validasi gagal: ' . $validator->errors()->first());
        }

        try {
            DB::beginTransaction();

            // Update data dasar
            $pendaftaran->nama = $request->nama;
            $pendaftaran->nim = $request->nim;
            $pendaftaran->semester = $request->semester;
            $pendaftaran->no_hp = $request->no_hp;
            $pendaftaran->alasan_mendaftar = $request->alasan_mendaftar;
            $pendaftaran->pengalaman = $request->pengalaman;
            $pendaftaran->skill = $request->skill;
            
            // Handle status perubahan
            $statusChanged = $pendaftaran->status_pendaftaran !== $request->status_pendaftaran;
            $pendaftaran->status_pendaftaran = $request->status_pendaftaran;

            if ($request->status_pendaftaran == 'diterima') {
                $pendaftaran->id_divisi = $request->id_divisi;
                $pendaftaran->id_jabatan = $request->id_jabatan;
                $pendaftaran->notes = null;
                $pendaftaran->divalidasi_oleh = auth()->id();
                $pendaftaran->validated_at = now();
                
                // Update user role jika status berubah menjadi diterima
                if ($statusChanged) {
                    $user = User::find($pendaftaran->user_id);
                    if ($user && $user->role !== 'anggota') {
                        $user->role = 'anggota';
                        $user->save();
                    }
                }
            } else if ($request->status_pendaftaran == 'ditolak') {
                $pendaftaran->notes = $request->notes;
                $pendaftaran->id_divisi = null;
                $pendaftaran->id_jabatan = null;
                $pendaftaran->divalidasi_oleh = auth()->id();
                $pendaftaran->validated_at = now();
            } else {
                $pendaftaran->notes = null;
                $pendaftaran->validator_id = null;
                $pendaftaran->validated_at = null;
            }

            // Handle file upload
            if ($request->hasFile('dokumen')) {
                // Hapus file lama jika ada
                if ($pendaftaran->dokumen && Storage::disk('public')->exists($pendaftaran->dokumen)) {
                    Storage::disk('public')->delete($pendaftaran->dokumen);
                }
                
                $file = $request->file('dokumen');
                $filename = 'doc_' . time() . '_' . uniqid() . '.' . $file->getClientOriginalExtension();
                $path = $file->storeAs('dokumen_pendaftaran', $filename, 'public');
                $pendaftaran->dokumen = $path;
            }

            $pendaftaran->save();

            DB::commit();
            DB::commit();

            $message = $statusChanged ?
                "Status pendaftaran berhasil diubah menjadi " . $request->status_pendaftaran :
                "Data pendaftaran berhasil diperbarui";

            // Jika status berubah, dispatch notifikasi WA ke job queue
            if ($statusChanged) {
                SendWaNotification::dispatch($pendaftaran->id, $request->status_pendaftaran);
            }

            return redirect()->route('admin.pendaftaran.index')
                ->with('success', $message);

        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()
                ->with('error', 'Terjadi kesalahan: ' . $e->getMessage())
                ->withInput();
        }
    }

    /**
     * Hapus data pendaftaran
     */
    public function destroy($id)
    {
        // Validasi admin access
        if (!Auth::check()) {
            return redirect()->route('admin.dashboard')->with('error', 'Anda tidak memiliki akses.');
        }

        try {
            DB::beginTransaction();

            $pendaftaran = Pendaftaran::findOrFail($id);
            
            // Hapus file dokumen jika ada
            if ($pendaftaran->dokumen && Storage::disk('public')->exists($pendaftaran->dokumen)) {
                Storage::disk('public')->delete($pendaftaran->dokumen);
            }
            
            $pendaftaran->delete();

            DB::commit();

            return redirect()->back()
                ->with('success', 'Data pendaftaran berhasil dihapus.');

        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()
                ->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }

    /**
     * Buka sesi pendaftaran
     */
    public function bukaSesi(Request $request)
    {
        // Validasi admin access
        if (!Auth::check()) {
            return response()->json([
                'success' => false,
                'message' => 'Anda tidak memiliki akses.'
            ], 403);
        }

        try {
            DB::beginTransaction();

            $settings = PendaftaranSetting::first();
            
            if (!$settings) {
                $settings = $this->createDefaultSettings();
            }

            // Validasi pengaturan
            $validation = $this->validateSettings($settings);
            if (!$validation['valid']) {
                return response()->json([
                    'success' => false,
                    'message' => $validation['message']
                ], 400);
            }

            $settings->pendaftaran_aktif = true;
            $settings->save();

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => 'Sesi pendaftaran berhasil dibuka',
                'settings' => $settings
            ]);

        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Tutup sesi pendaftaran
     */
    public function tutupSesi(Request $request)
    {
        // Validasi admin access
        if (!Auth::check()) {
            return response()->json([
                'success' => false,
                'message' => 'Anda tidak memiliki akses.'
            ], 403);
        }

        try {
            DB::beginTransaction();

            $settings = PendaftaranSetting::first();
            if ($settings) {
                $settings->pendaftaran_aktif = false;
                $settings->save();
            }

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => 'Sesi pendaftaran berhasil ditutup',
                'settings' => $settings
            ]);

        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Update pengaturan periode pendaftaran
     */
    public function updateSettings(Request $request)
    {
        // Validasi admin access
        if (!Auth::check() || !Auth::user()->isAdministrator()) {
            if ($request->ajax()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Anda tidak memiliki akses.'
                ], 403);
            }
            return redirect()->route('admin.dashboard')->with('error', 'Anda tidak memiliki akses sebagai super admin.');
        }

        $validator = Validator::make($request->all(), [
            'tanggal_mulai' => 'required|date',
            'tanggal_selesai' => 'required|date|after_or_equal:tanggal_mulai',
            'kuota' => 'required|integer|min:1',
            'auto_close' => 'sometimes|boolean'
        ], [
            'tanggal_selesai.after_or_equal' => 'Tanggal selesai harus setelah atau sama dengan tanggal mulai'
        ]);

        if ($validator->fails()) {
            if ($request->ajax()) {
                return response()->json([
                    'success' => false,
                    'message' => $validator->errors()->first()
                ], 422);
            }
            return redirect()->back()
                ->withErrors($validator)
                ->withInput()
                ->with('error', $validator->errors()->first());
        }

        try {
            DB::beginTransaction();

            $settings = PendaftaranSetting::firstOrNew();
            $settings->tanggal_mulai = $request->tanggal_mulai;
            $settings->tanggal_selesai = $request->tanggal_selesai;
            $settings->kuota = $request->kuota;
            $settings->auto_close = $request->boolean('auto_close', false);
            
            // Jika auto_close aktif dan tanggal selesai sudah lewat, tutup otomatis
            if ($settings->auto_close && Carbon::now()->gt($settings->tanggal_selesai)) {
                $settings->pendaftaran_aktif = false;
            }
            
            $settings->save();

            DB::commit();

            if ($request->ajax()) {
                return response()->json([
                    'success' => true,
                    'message' => 'Pengaturan periode berhasil disimpan',
                    'settings' => $settings
                ]);
            }

            return redirect()->back()->with('success', 'Pengaturan periode berhasil disimpan');

        } catch (\Exception $e) {
            DB::rollBack();
            if ($request->ajax()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Terjadi kesalahan: ' . $e->getMessage()
                ], 500);
            }
            return redirect()->back()
                ->with('error', 'Terjadi kesalahan: ' . $e->getMessage())
                ->withInput();
        }
    }

    /**
     * Bulk update interview untuk semua pending
     */
    public function bulkInterview(Request $request)
    {
        // Validasi admin access
        if (!Auth::check()) {
            return response()->json(['success' => false, 'message' => 'Anda tidak memiliki akses.'], 401);
        }

        // Validasi input
        $request->validate([
            'interview_date' => 'required|date|after_or_equal:today'
        ]);

        try {
            DB::beginTransaction();

            // Get all pending pendaftaran
            $pendaftaranList = Pendaftaran::where('status_pendaftaran', 'pending')->get();

            if ($pendaftaranList->isEmpty()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Tidak ada pendaftaran dengan status Pending'
                ], 422);
            }

            // Update semua ke interview status dengan interview_date
            $updatedCount = 0;
            foreach ($pendaftaranList as $pendaftaran) {
                $pendaftaran->status_pendaftaran = 'interview';
                $pendaftaran->interview_date = $request->interview_date;
                $pendaftaran->divalidasi_oleh = auth()->id();
                $pendaftaran->validated_at = now();
                $pendaftaran->wa_sent = false; // Reset untuk allow wa send
                $pendaftaran->save();

                // Send WhatsApp notification
                try {
                    $waService = new WhatsAppService();
                    $waService->send($pendaftaran, 'interview');
                    Log::info('Bulk interview WA sent', ['pendaftaran_id' => $pendaftaran->id_pendaftaran]);
                } catch (\Exception $waException) {
                    Log::warning('Bulk interview WA failed', [
                        'pendaftaran_id' => $pendaftaran->id_pendaftaran,
                        'error' => $waException->getMessage()
                    ]);
                }

                $updatedCount++;
            }

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => "Berhasil menjadwalkan interview untuk {$updatedCount} pendaftar pada " . \Carbon\Carbon::parse($request->interview_date)->format('d M Y'),
                'updated_count' => $updatedCount
            ]);

        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Bulk interview error', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);

            return response()->json([
                'success' => false,
                'message' => 'Gagal menjadwalkan interview: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Bulk accept untuk semua interview
     */
    public function bulkAccept(Request $request)
    {
        // Validasi admin access
        if (!Auth::check()) {
            return response()->json(['success' => false, 'message' => 'Anda tidak memiliki akses.'], 401);
        }

        // Validasi input
        $request->validate([
            'id_jabatan' => 'required|exists:jabatans,id_jabatan'
        ]);

        try {
            DB::beginTransaction();

            // Get all interview pendaftaran
            $pendaftaranList = Pendaftaran::where('status_pendaftaran', 'interview')->get();

            if ($pendaftaranList->isEmpty()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Tidak ada pendaftaran dengan status Interview'
                ], 422);
            }

            // Get jabatan info untuk pesan
            $jabatan = \App\Models\Jabatan::find($request->id_jabatan);

            // Update semua ke accepted status dengan divisi masing-masing dan jabatan yang sama
            $updatedCount = 0;
            foreach ($pendaftaranList as $pendaftaran) {
                $pendaftaran->status_pendaftaran = 'diterima';
                // Divisi tetap dari divisi yang dipilih saat pendaftar mendaftar
                $pendaftaran->id_jabatan = $request->id_jabatan;
                $pendaftaran->divalidasi_oleh = auth()->id();
                $pendaftaran->validated_at = now();
                $pendaftaran->wa_sent = false; // Reset untuk allow wa send
                $pendaftaran->notes = null; // Clear notes
                $pendaftaran->save();

                // Create user dan anggota otomatis
                try {
                    $anggotaService = new CreateAnggotaService();
                    $result = $anggotaService->createFromPendaftaran($pendaftaran);
                    
                    if ($result['success']) {
                        Log::info("Bulk accept: Anggota created", [
                            'pendaftaran_id' => $pendaftaran->id_pendaftaran,
                            'user_id' => $result['user']->id
                        ]);
                    }
                } catch (\Exception $anggotaException) {
                    Log::warning('Bulk accept: Failed to create anggota', [
                        'pendaftaran_id' => $pendaftaran->id_pendaftaran,
                        'error' => $anggotaException->getMessage()
                    ]);
                }

                // Send WhatsApp notification
                try {
                    $waService = new WhatsAppService();
                    $waService->send($pendaftaran, 'diterima');
                    Log::info('Bulk accept WA sent', ['pendaftaran_id' => $pendaftaran->id_pendaftaran]);
                } catch (\Exception $waException) {
                    Log::warning('Bulk accept WA failed', [
                        'pendaftaran_id' => $pendaftaran->id_pendaftaran,
                        'error' => $waException->getMessage()
                    ]);
                }

                $updatedCount++;
            }

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => "Berhasil menerima {$updatedCount} pendaftar sebagai " . ($jabatan ? $jabatan->nama_jabatan : 'Anggota') . " di divisi masing-masing",
                'updated_count' => $updatedCount
            ]);

        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Bulk accept error', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);

            return response()->json([
                'success' => false,
                'message' => 'Gagal menerima pendaftar: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Update status pendaftaran (terima/tolak)
     */
    public function updateStatus(Request $request, $id)
    {
        // Validasi admin access
        if (!Auth::check()) {
            return response()->json(['success' => false, 'message' => 'Anda tidak memiliki akses.'], 401);
        }

        // Validate only required fields for status update
        $validator = Validator::make($request->only(['status_pendaftaran', 'interview_date', 'notes']), [
            'status_pendaftaran' => 'required|in:' . PendaftaranStatus::validationString(),
            'interview_date' => 'nullable|required_if:status_pendaftaran,interview|date|after_or_equal:today',
            'notes' => 'nullable|string|max:500'
        ]);

        if ($validator->fails()) {
            $message = $validator->errors()->first();
            if ($request->expectsJson()) {
                return response()->json([
                    'success' => false, 
                    'message' => 'Validasi gagal: ' . $message,
                    'errors' => $validator->errors()
                ], 422);
            }
            return redirect()->back()
                ->withErrors($validator)
                ->with('error', 'Validasi gagal: ' . $message);
        }

        try {
            DB::beginTransaction();

            $pendaftaran = Pendaftaran::findOrFail($id);
            $oldStatus = $pendaftaran->status_pendaftaran;
            
            // Use the status value directly (frontend now sends correct enum values)
            $newStatus = $request->status_pendaftaran;
            
            // Check if status actually changed
            $statusChanged = $oldStatus !== $newStatus;
            
            // Validasi kuota jika status diterima (hanya untuk penerimaan baru, bukan update)
            if ($newStatus == PendaftaranStatus::ACCEPTED->value && $statusChanged) {
                $kuotaCheck = $this->checkKuotaPenerimaan();
                if (!$kuotaCheck['available']) {
                    if ($request->expectsJson()) {
                        return response()->json(['success' => false, 'message' => $kuotaCheck['message']], 422);
                    }
                    return redirect()->back()->with('error', $kuotaCheck['message']);
                }
            }

            // Only update specific fields
            $pendaftaran->status_pendaftaran = $newStatus;
            $pendaftaran->divalidasi_oleh = auth()->id();
            $pendaftaran->validated_at = now();

            // Set interview_date if status is interview
            if ($newStatus == PendaftaranStatus::INTERVIEW->value) {
                $pendaftaran->interview_date = $request->interview_date;
            }

            // Set notes if provided (untuk alasan ditolak atau info lainnya)
            if ($request->has('notes') && $request->notes) {
                $pendaftaran->notes = $request->notes;
            } elseif ($newStatus !== PendaftaranStatus::REJECTED->value) {
                // Clear notes jika bukan ditolak
                $pendaftaran->notes = null;
            }

            // Reset wa_sent flag jika status berubah (untuk memungkinkan pengiriman ulang)
            if ($statusChanged) {
                $pendaftaran->wa_sent = false;
            }

            $pendaftaran->save();

            // 🔹 If status is "diterima", automatically create user and anggota
            if ($newStatus == PendaftaranStatus::ACCEPTED->value) {
                $anggotaService = new CreateAnggotaService();
                $result = $anggotaService->createFromPendaftaran($pendaftaran);
                
                if ($result['success']) {
                    Log::info("Anggota created successfully", [
                        'pendaftaran_id' => $pendaftaran->id_pendaftaran,
                        'user_id' => $result['user']->id,
                        'anggota_id' => $result['anggota']->id_anggota_hima
                    ]);
                } else {
                    Log::warning("Failed to create anggota", [
                        'pendaftaran_id' => $pendaftaran->id_pendaftaran,
                        'error' => $result['message']
                    ]);
                }
            }

            DB::commit();

            // 🟢 KIRIM WHATSAPP HANYA JIKA STATUS BERUBAH & BELUM PERNAH DIKIRIM
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
                    // Jangan stop proses jika WA gagal
                }
            }

            $message = match($newStatus) {
                PendaftaranStatus::ACCEPTED->value => 'Pendaftaran berhasil diterima dan akun anggota telah dibuat',
                PendaftaranStatus::REJECTED->value => 'Pendaftaran berhasil ditolak',
                PendaftaranStatus::INTERVIEW->value => 'Jadwal interview berhasil disimpan',
                default => 'Status berhasil diubah'
            };

            if ($request->expectsJson()) {
                return response()->json(['success' => true, 'message' => $message]);
            }

            return redirect()->back()->with('success', $message);

        } catch (\Exception $e) {
            DB::rollBack();
            $message = 'Terjadi kesalahan: ' . $e->getMessage();
            
            if ($request->expectsJson()) {
                return response()->json(['success' => false, 'message' => $message], 500);
            }
            return redirect()->back()->with('error', $message);
        }
    }

    /**
     * Change registration stage/status (new workflow)
     * Accepts: status, interview_date (nullable), notes (nullable)
     */
    public function changeStatus(Request $request, $id)
    {
        // Validasi akses admin (super admin atau admin diperbolehkan)
        if (!Auth::check()) {
            if ($request->ajax() || $request->wantsJson()) {
                return response()->json(['success' => false, 'message' => 'Anda tidak memiliki akses.'], 403);
            }
            return redirect()->route('admin.dashboard')->with('error', 'Anda tidak memiliki akses.');
        }

        $allowed = ['submitted','verifying','interview','accepted','rejected'];

        $validator = Validator::make($request->all(), [
            'status' => ['required', 'in:' . implode(',', $allowed)],
            // accept nullable raw input; we'll parse with Carbon below if provided
            'interview_date' => 'nullable',
            'notes' => 'nullable|string|max:2000'
        ]);

        if ($validator->fails()) {
            if ($request->ajax() || $request->wantsJson()) {
                return response()->json([
                    'success' => false,
                    'message' => $validator->errors()->first()
                ], 422);
            }
            return redirect()->back()->with('error', $validator->errors()->first());
        }

        try {
            DB::beginTransaction();

            $pendaftaran = Pendaftaran::findOrFail($id);

            // Map new workflow statuses to existing DB enum values (backward-compatible)
            $statusMap = [
                'submitted' => 'pending',
                'verifying'  => 'pending',
                'interview'  => 'pending',
                'accepted'   => 'diterima',
                'rejected'   => 'ditolak',
            ];

            $dbStatus = $statusMap[$request->status] ?? $request->status;

            // Update only allowed fields
            $pendaftaran->status_pendaftaran = $dbStatus;
            $pendaftaran->interview_date = $request->interview_date ? Carbon::parse($request->interview_date) : null;
            $pendaftaran->notes = $request->notes;

            // If accepted, set validator and validated_at and promote user
            if ($request->status === 'accepted') {
                $pendaftaran->divalidasi_oleh = auth()->id();
                $pendaftaran->validated_at = now();

                $user = User::find($pendaftaran->id_user);
                if ($user && $user->role !== 'anggota') {
                    $user->role = 'anggota';
                    $user->save();
                }
            }

            $pendaftaran->save();

            DB::commit();

            if ($request->ajax() || $request->wantsJson()) {
                return response()->json([
                    'success' => true,
                    'message' => 'Status pendaftaran berhasil diperbarui.',
                    'data' => $pendaftaran
                ]);
            }

            return redirect()->back()->with('success', 'Status pendaftaran berhasil diperbarui.');

        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }

    /**
     * Get status pendaftaran (API)
     */
    public function getStatus()
    {
        // Validasi akses admin
        if (!Auth::check() || !Auth::user()->isAdministrator()) {
            return response()->json([
                'success' => false,
                'message' => 'Anda tidak memiliki akses.'
            ], 403);
        }

        try {
            $settings = PendaftaranSetting::first();
            
            if (!$settings) {
                return response()->json([
                    'success' => false,
                    'pendaftaran_aktif' => false,
                    'message' => 'Pengaturan pendaftaran belum diatur'
                ]);
            }

            $stats = $this->getPendaftaranStats();

            return response()->json([
                'success' => true,
                'pendaftaran_aktif' => $settings->pendaftaran_aktif,
                'settings' => $settings,
                'stats' => $stats,
                'server_time' => now()->toDateTimeString()
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Export data pendaftaran
     */
    public function export(Request $request)
    {
        // Validasi admin access
        if (!Auth::check()) {
            return response()->json([
                'success' => false,
                'message' => 'Anda tidak memiliki akses.'
            ], 403);
        }

        try {
            $pendaftaran = $this->getFilteredPendaftaran($request, false);
            
            $filename = 'pendaftaran_' . date('Y-m-d_H-i-s') . '.xlsx';
            
            return response()->json([
                'success' => true,
                'message' => 'Fitur export akan segera tersedia',
                'data_count' => $pendaftaran->count()
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * =========================================================================
     * PRIVATE METHODS
     * =========================================================================
     */

    /**
     * Buat pengaturan default
     */
    private function createDefaultSettings()
    {
        return PendaftaranSetting::create([
            'pendaftaran_aktif' => false,
            'tanggal_mulai' => now()->format('Y-m-d'),
            'tanggal_selesai' => now()->addMonth()->format('Y-m-d'),
            'kuota' => 50,
            'auto_close' => true
        ]);
    }

    /**
     * Get statistik pendaftaran
     */
    private function getPendaftaranStats()
    {
        return [
            'totalPendaftaran' => Pendaftaran::count(),
            'pendingCount' => Pendaftaran::where('status_pendaftaran', 'pending')->count(),
            'interviewCount' => Pendaftaran::where('status_pendaftaran', 'interview')->count(),
            'diterimaCount' => Pendaftaran::where('status_pendaftaran', 'diterima')->count(),
            'ditolakCount' => Pendaftaran::where('status_pendaftaran', 'ditolak')->count(),
            'todayCount' => Pendaftaran::whereDate('created_at', today())->count(),
            'weekCount' => Pendaftaran::whereBetween('created_at', [now()->startOfWeek(), now()->endOfWeek()])->count(),
            'monthCount' => Pendaftaran::whereMonth('created_at', now()->month)->count(),
        ];
    }

    /**
     * Get data pendaftaran dengan filter
     */
    private function getFilteredPendaftaran(Request $request, $paginate = true)
    {
        $query = Pendaftaran::with(['user', 'validator', 'divisi', 'jabatan']);

        // Filter status
        if ($request->has('status') && $request->status != 'all') {
            $query->where('status_pendaftaran', $request->status);
        }

        // Search
        if ($request->has('search') && $request->search != '') {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('nama', 'like', '%' . $search . '%')
                  ->orWhere('nim', 'like', '%' . $search . '%')
                  ->orWhere('no_hp', 'like', '%' . $search . '%')
                  ->orWhereHas('user', function($q) use ($search) {
                      $q->where('email', 'like', '%' . $search . '%');
                  });
            });
        }

        // Filter tanggal
        if ($request->has('start_date') && $request->start_date != '') {
            $query->whereDate('created_at', '>=', $request->start_date);
        }

        if ($request->has('end_date') && $request->end_date != '') {
            $query->whereDate('created_at', '<=', $request->end_date);
        }

        // Sort by nama, then by created_at
        $query->orderBy('nama', 'asc')
              ->orderBy('created_at', 'desc');

        return $paginate ? $query->paginate(10) : $query->get();
    }

    /**
     * Validasi pengaturan
     */
    private function validateSettings($settings)
    {
        if (!$settings->tanggal_mulai || !$settings->tanggal_selesai) {
            return [
                'valid' => false,
                'message' => 'Harap setting tanggal mulai dan selesai terlebih dahulu'
            ];
        }

        if (!$settings->kuota || $settings->kuota <= 0) {
            return [
                'valid' => false,
                'message' => 'Harap setting kuota penerimaan terlebih dahulu'
            ];
        }

        if (Carbon::now()->gt($settings->tanggal_selesai)) {
            return [
                'valid' => false,
                'message' => 'Tanggal selesai pendaftaran sudah lewat. Perbarui tanggal terlebih dahulu.'
            ];
        }

        return ['valid' => true, 'message' => 'OK'];
    }

    /**
     * Cek ketersediaan kuota
     */
    private function checkKuotaPenerimaan()
    {
        $settings = PendaftaranSetting::first();
        $totalDiterima = Pendaftaran::where('status_pendaftaran', 'diterima')->count();

        if ($settings && $totalDiterima >= $settings->kuota) {
            return [
                'available' => false,
                'message' => 'Kuota penerimaan sudah penuh. Tidak dapat menerima pendaftar lagi.'
            ];
        }

        return [
            'available' => true,
            'message' => 'Kuota tersedia',
            'terpakai' => $totalDiterima,
            'sisa' => $settings ? $settings->kuota - $totalDiterima : 0
        ];
    }

    /**
     * Get jabatan berdasarkan divisi
     */
    public function getJabatanByDivisi($idDivisi)
    {
        try {
            $jabatans = Jabatan::where('id_divisi', $idDivisi)
                ->where('status', 1)
                ->get(['id_jabatan', 'nama_jabatan']);
            
            return response()->json([
                'success' => true,
                'data' => $jabatans
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Gagal mengambil data jabatan: ' . $e->getMessage()
            ], 500);
        }
    }
}