<?php

namespace App\Http\Controllers;

use App\Models\Pendaftaran;
use App\Models\PendaftaranSetting;
use App\Models\User;
use App\Models\Divisi;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Hash;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class PendaftaranController extends Controller
{
    /**
     * Halaman utama pendaftaran - redirect ke form
     */
    public function index()
    {
        return $this->checkPendaftaranStatus();
    }

    /**
     * Cek status pendaftaran dan redirect ke halaman yang sesuai
     */
    public function checkPendaftaranStatus()
    {
        try {
            $settings = PendaftaranSetting::first();

            // Jika pengaturan belum ada, buat default
            if (!$settings) {
                return view('users.pendaftaran.closed')->with([
                    'message' => 'Sistem pendaftaran sedang dalam maintenance'
                ]);
            }

            // Cek apakah pendaftaran aktif
            if (!$settings->pendaftaran_aktif) {
                return view('users.pendaftaran.closed', compact('settings'));
            }

            // Cek apakah dalam periode pendaftaran
            $now = Carbon::now();
            $tanggalMulai = Carbon::parse($settings->tanggal_mulai);
            $tanggalSelesai = Carbon::parse($settings->tanggal_selesai);

            if ($now->lt($tanggalMulai)) {
                return view('users.pendaftaran.coming-soon', compact('settings'));
            }

            if ($now->gt($tanggalSelesai)) {
                // Jika auto_close aktif, update status
                if ($settings->auto_close) {
                    $settings->pendaftaran_aktif = false;
                    $settings->save();
                }
                return view('users.pendaftaran.ended', compact('settings'));
            }

            // Cek kuota
            $totalDiterima = Pendaftaran::where('status_pendaftaran', 'diterima')->count();
            if ($totalDiterima >= $settings->kuota) {
                return view('users.pendaftaran.quota-full', compact('settings'));
            }

            // Hitung kuota tersisa
            $kuotaTersisa = $settings->kuota - $totalDiterima;

            // Load divisi yang aktif
            $divisi = Divisi::where('status', 1)->get();

            // Jika semua kondisi terpenuhi, tampilkan form
            return view('users.pendaftaran.create', compact('settings', 'kuotaTersisa', 'totalDiterima', 'divisi'));

        } catch (\Exception $e) {
            return view('users.pendaftaran.closed')->with([
                'error' => 'Terjadi kesalahan sistem: ' . $e->getMessage()
            ]);
        }
    }

    /**
     * Menampilkan form pendaftaran
     */
    public function create()
    {
        return $this->checkPendaftaranStatus();
    }

    /**
     * Proses pendaftaran - PERBAIKAN FINAL
     */
    public function store(Request $request)
    {
        \Log::info('STORE METHOD CALLED', ['url' => $request->url(), 'method' => $request->method()]);
        
        // Validasi status pendaftaran sebelum memproses
        $settings = PendaftaranSetting::first();
        
        if (!$settings || !$settings->pendaftaran_aktif) {
            return redirect()->route('pendaftaran.closed')
                ->with('error', 'Pendaftaran sedang ditutup');
        }

        // Cek periode
        $now = Carbon::now();
        $tanggalMulai = Carbon::parse($settings->tanggal_mulai);
        $tanggalSelesai = Carbon::parse($settings->tanggal_selesai);

        if ($now->lt($tanggalMulai)) {
            return redirect()->route('pendaftaran.coming-soon')
                ->with('error', 'Pendaftaran belum dibuka');
        }

        if ($now->gt($tanggalSelesai)) {
            return redirect()->route('pendaftaran.ended')
                ->with('error', 'Periode pendaftaran sudah berakhir');
        }

        // Cek kuota
        $totalDiterima = Pendaftaran::where('status_pendaftaran', 'diterima')->count();
        if ($totalDiterima >= $settings->kuota) {
            return redirect()->route('pendaftaran.quota-full')
                ->with('error', 'Kuota pendaftaran sudah penuh');
        }

        // Validasi input
        $validator = Validator::make($request->all(), [
            'nama' => 'required|string|max:150',
            'nim' => 'required|string|max:30|unique:pendaftaran,nim',
            'semester' => 'required|integer|between:1,8',
            'no_hp' => 'required|string|max:20',
            'alasan_mendaftar' => 'required|string|min:50|max:1000',
            'id_divisi' => 'required|exists:divisis,id_divisi',
            'alasan_divisi' => 'required|string|min:20|max:1000',
            'pengalaman' => 'nullable|string|max:1000',
            'skill' => 'nullable|string|max:1000',
            'dokumen' => 'nullable|file|mimes:pdf,doc,docx,jpg,png|max:2048',
            'agree' => 'required|accepted'
        ], [
            'nim.unique' => 'NIM ini sudah terdaftar',
            'agree.accepted' => 'Anda harus menyetujui syarat dan ketentuan',
            'alasan_mendaftar.min' => 'Alasan mendaftar minimal 50 karakter'
        ]);

        if ($validator->fails()) {
            \Log::error('Pendaftaran validation failed', [
                'errors' => $validator->errors()->toArray(),
                'input' => $request->except('_token'),
            ]);
            
            return redirect()->back()
                ->withErrors($validator)
                ->withInput()
                ->with('error', 'Terjadi kesalahan dalam pengisian form');
        }

        \Log::info('Pendaftaran validation passed', ['input' => $request->except('_token')]);

        try {
            // Mulai transaction
            DB::beginTransaction();

            // CARI USER YANG SUDAH ADA BERDASARKAN AUTH, ATAU BUAT BARU JIKA PERLU
            $user = auth()->user();
            
            if (!$user) {
                // Jika belum login, buat user baru dengan email default
                $user = User::create([
                    'name' => $request->nama,
                    'email' => $request->nim . '@student.example.com',
                    'password' => Hash::make($request->nim),
                    'role' => 'user',
                    'email_verified_at' => now()
                ]);
            }

            // Handle file upload
            $dokumenPath = null;
            if ($request->hasFile('dokumen')) {
                $file = $request->file('dokumen');
                $filename = 'doc_' . time() . '_' . uniqid() . '.' . $file->getClientOriginalExtension();
                $dokumenPath = $file->storeAs('dokumen_pendaftaran', $filename, 'public');
            }

            // Simpan data pendaftaran
            $pendaftaran = Pendaftaran::create([
                'id_user' => $user->id,
                'nim' => $request->nim,
                'nama' => $request->nama,
                'semester' => $request->semester,
                'no_hp' => $request->no_hp,
                'alasan_mendaftar' => $request->alasan_mendaftar,
                'id_divisi' => $request->id_divisi,
                'alasan_divisi' => $request->alasan_divisi,
                'pengalaman' => $request->pengalaman,
                'skill' => $request->skill,
                'dokumen' => $dokumenPath,
                'status_pendaftaran' => 'pending',
                'submitted_at' => now()
            ]);

            DB::commit();

            // Log activity (non-fatal) - protect if activity_log table missing
            try {
                if (function_exists('activity')) {
                    activity()
                        ->causedBy($user)
                        ->performedOn($pendaftaran)
                        ->log('Mendaftar sebagai anggota HIMA TI');
                }
            } catch (\Exception $e) {
                \Log::warning('Activity log failed (non-fatal)', ['message' => $e->getMessage()]);
            }

            // REDIRECT KE SUCCESS - PASTIKAN INI DIEKSEKUSI
            \Log::info('Pendaftaran saved', ['id' => $pendaftaran->id_pendaftaran]);
            \Log::info('Redirecting to success route with flash');

            return redirect()->route('pendaftaran.success')
                ->with([
                    'success' => 'Pendaftaran berhasil dikirim!',
                    'pendaftaran_id' => $pendaftaran->id_pendaftaran,
                    'nama' => $pendaftaran->nama,
                    'nim' => $pendaftaran->nim,
                    'semester' => $pendaftaran->semester,
                    'submitted_at' => $pendaftaran->submitted_at
                ]);

        } catch (\Exception $e) {
            DB::rollBack();
            
            // Untuk debugging, tampilkan error
            \Log::error('Exception in PendaftaranController@store', [
                'message' => $e->getMessage(),
                'line' => $e->getLine(),
                'trace' => $e->getTraceAsString(),
            ]);

            if (config('app.debug')) {
                return redirect()->back()
                    ->with('error', 'Terjadi kesalahan: ' . $e->getMessage() . ' di line: ' . $e->getLine())
                    ->withInput();
            }
            
            return redirect()->back()
                ->with('error', 'Terjadi kesalahan sistem. Silakan coba lagi.')
                ->withInput();
        }
    }

    /**
     * Halaman sukses pendaftaran - PERBAIKAN
     */
    public function success()
    {
        // Cek apakah ada data success dari store method
        if (!session('success')) {
            return redirect()->route('pendaftaran.create');
        }

        // Ambil data dari session
        $pendaftaranData = [
            'id' => session('pendaftaran_id'),
            'nama' => session('nama'),
            'nim' => session('nim'),
            'semester' => session('semester'),
            'submitted_at' => session('submitted_at')
        ];

        return view('users.pendaftaran.success', compact('pendaftaranData'));
    }

    /**
     * Halaman pendaftaran ditutup
     */
    public function closed()
    {
        $settings = PendaftaranSetting::first();
        return view('users.pendaftaran.closed', compact('settings'));
    }

    /**
     * Halaman kuota penuh
     */
    public function quotaFull()
    {
        $settings = PendaftaranSetting::first();
        return view('users.pendaftaran.quota-full', compact('settings'));
    }

    /**
     * Halaman periode akan datang
     */
    public function comingSoon()
    {
        $settings = PendaftaranSetting::first();
        return view('users.pendaftaran.coming-soon', compact('settings'));
    }

    /**
     * Halaman periode berakhir
     */
    public function ended()
    {
        $settings = PendaftaranSetting::first();
        return view('users.pendaftaran.ended', compact('settings'));
    }

    /**
     * Tampilkan status pendaftaran - PERBAIKAN
     */
    public function status($id)
    {
        try {
            // Gunakan find dengan primary key id_pendaftaran
            $pendaftaran = Pendaftaran::with(['user', 'divisi', 'jabatan', 'validator'])
                ->find($id);
                
            if (!$pendaftaran) {
                return redirect()->route('pendaftaran.check-status.form')
                    ->with('error', 'Data pendaftaran tidak ditemukan');
            }

            return view('users.pendaftaran.status', compact('pendaftaran'));

        } catch (\Exception $e) {
            return redirect()->route('pendaftaran.check-status.form')
                ->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }

    /**
     * Form cek status pendaftaran
     */
    public function showCheckStatus()
    {
        return view('users.pendaftaran.check-status');
    }

    /**
     * Proses cek status pendaftaran - PERBAIKAN
     */
    public function checkStatus(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'nim' => 'required|string|max:30'
        ]);

        if ($validator->fails()) {
            return back()->withErrors($validator)
                ->with('error', 'NIM harus diisi');
        }

        $pendaftaran = Pendaftaran::where('nim', $request->nim)
            ->with(['user', 'divisi', 'jabatan'])
            ->first();

        if (!$pendaftaran) {
            return back()->with('error', 'Data pendaftaran tidak ditemukan untuk NIM: ' . $request->nim);
        }

        return redirect()->route('pendaftaran.status', $pendaftaran->id_pendaftaran);
    }

    /**
     * API untuk mendapatkan status pendaftaran
     */
    public function getStatusApi()
    {
        try {
            $settings = PendaftaranSetting::first();
            
            if (!$settings) {
                return response()->json([
                    'success' => false,
                    'pendaftaran_aktif' => false,
                    'message' => 'Pengaturan pendaftaran belum diatur'
                ]);
            }

            $totalDiterima = Pendaftaran::where('status_pendaftaran', 'diterima')->count();
            $kuotaTerpenuhi = $totalDiterima >= $settings->kuota;

            $now = Carbon::now();
            $tanggalMulai = Carbon::parse($settings->tanggal_mulai);
            $tanggalSelesai = Carbon::parse($settings->tanggal_selesai);

            $statusDetail = 'available';
            $message = 'Pendaftaran sedang dibuka';

            if (!$settings->pendaftaran_aktif) {
                $statusDetail = 'closed';
                $message = 'Pendaftaran sedang ditutup';
            } else if ($now->lt($tanggalMulai)) {
                $statusDetail = 'coming_soon';
                $message = 'Pendaftaran akan dibuka pada ' . $tanggalMulai->format('d M Y');
            } else if ($now->gt($tanggalSelesai)) {
                $statusDetail = 'ended';
                $message = 'Periode pendaftaran telah berakhir';
            } else if ($kuotaTerpenuhi) {
                $statusDetail = 'quota_full';
                $message = 'Kuota pendaftaran telah penuh';
            }

            return response()->json([
                'success' => true,
                'pendaftaran_aktif' => $settings->pendaftaran_aktif,
                'status_detail' => $statusDetail,
                'is_active' => $settings->pendaftaran_aktif && !$kuotaTerpenuhi && $now->between($tanggalMulai, $tanggalSelesai),
                'is_quota_full' => $kuotaTerpenuhi,
                'tanggal_mulai' => $settings->tanggal_mulai,
                'tanggal_selesai' => $settings->tanggal_selesai,
                'kuota' => $settings->kuota,
                'total_diterima' => $totalDiterima,
                'total_pending' => Pendaftaran::where('status_pendaftaran', 'pending')->count(),
                'sisa_kuota' => max(0, $settings->kuota - $totalDiterima),
                'message' => $message,
                'server_time' => $now->toDateTimeString()
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Download dokumen pendaftaran - PERBAIKAN
     */
    public function downloadDokumen($id)
    {
        try {
            $pendaftaran = Pendaftaran::find($id);
            
            if (!$pendaftaran) {
                return redirect()->back()
                    ->with('error', 'Data pendaftaran tidak ditemukan');
            }

            // Cek apakah user berhak mengakses dokumen
            if (auth()->check() && (auth()->id() === $pendaftaran->id_user || auth()->user()->role === 'super_admin')) {
                if ($pendaftaran->dokumen && Storage::disk('public')->exists($pendaftaran->dokumen)) {
                    return Storage::disk('public')->download($pendaftaran->dokumen);
                }
            }

            return redirect()->back()
                ->with('error', 'Dokumen tidak ditemukan atau Anda tidak memiliki akses');

        } catch (\Exception $e) {
            return redirect()->back()
                ->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }

    /**
     * Lihat dokumen pendaftaran - PERBAIKAN
     */
    public function viewDokumen($id)
    {
        try {
            $pendaftaran = Pendaftaran::find($id);
            
            if (!$pendaftaran) {
                return redirect()->back()
                    ->with('error', 'Data pendaftaran tidak ditemukan');
            }

            // Cek apakah user berhak mengakses dokumen
            if (auth()->check() && (auth()->id() === $pendaftaran->id_user || auth()->user()->role === 'super_admin')) {
                if ($pendaftaran->dokumen && Storage::disk('public')->exists($pendaftaran->dokumen)) {
                    $path = Storage::disk('public')->path($pendaftaran->dokumen);
                    $mime = Storage::disk('public')->mimeType($pendaftaran->dokumen);
                    
                    return response()->file($path, [
                        'Content-Type' => $mime,
                    ]);
                }
            }

            return redirect()->back()
                ->with('error', 'Dokumen tidak ditemukan atau Anda tidak memiliki akses');

        } catch (\Exception $e) {
            return redirect()->back()
                ->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }
}