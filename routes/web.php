<?php

use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;
use Laravel\Socialite\Facades\Socialite;
use App\Http\Controllers\IndexController;
use App\Http\Controllers\BeritaController;
use App\Http\Controllers\DivisiController;
use App\Http\Controllers\GoogleController;
use App\Http\Controllers\SanksiController;
use App\Http\Controllers\JabatanController;
use App\Http\Controllers\KomentarController;
use App\Http\Controllers\PrestasiController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\AnggotaHimaController;
use App\Http\Controllers\PelanggaranController;
use App\Http\Controllers\PendaftaranController;
use App\Http\Controllers\Auth\RegisterController;
use App\Http\Controllers\UserDashboardController;
use App\Http\Controllers\Admin\MahasiswaController;
use App\Http\Controllers\Admin\AdminKomentarController;
use App\Http\Controllers\MahasiswaBermasalahController;
use App\Http\Controllers\Admin\CriteriaController;
use App\Http\Controllers\Admin\BeritaController as AdminBeritaController;
use App\Http\Controllers\Admin\DivisiController as AdminDivisiController;
use App\Http\Controllers\Admin\AnggotaController as AdminAnggotaController;
use App\Http\Controllers\Admin\PrestasiController as AdminPrestasiController;
use App\Http\Controllers\Admin\PendaftaranController as AdminPendaftaranController;



/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
*/

// ========================
// Authentication Routes
// ========================
Route::get('/login', [LoginController::class, 'showLoginForm'])->name('login');
Route::post('/login', [LoginController::class, 'login'])->name('login.post'); // ← ini WAJIB ada
Route::post('/logout', function () {
    Auth::logout();
    session()->flush();
    request()->session()->invalidate();
    request()->session()->regenerateToken();
    return redirect('/login')->with('success', 'Anda telah logout. Silakan login kembali.');
})->name('logout');

// Public Routes (Frontend)
// ========================
Route::get('/', [IndexController::class, 'index'])->name('home');

// Redirect /home ke /
Route::get('/home', function () {
    return redirect()->route('home');
});

// Berita Routes (Public)
Route::prefix('berita')->name('berita.')->group(function () {
    Route::get('/', [BeritaController::class, 'publicIndex'])->name('index');
    Route::get('/lainnya', [BeritaController::class, 'lainnya'])->name('lainnya');
    Route::get('/{id}', [BeritaController::class, 'publicShow'])->name('show');

    // Komentar Routes
    Route::post('/{id}/komentar', [BeritaController::class, 'publicCommentStore'])->name('komentar.store');
    Route::put('/{id}/komentar/{komentarId}', [BeritaController::class, 'publicCommentUpdate'])->name('komentar.update');
    Route::delete('/{id}/komentar/{komentarId}', [BeritaController::class, 'publicCommentDestroy'])->name('komentar.destroy');
});

Route::get('/prestasi', [PrestasiController::class, 'index'])->name('prestasi.index');
Route::resource('prestasi', PrestasiController::class);

// Divisi Routes (Public)
Route::prefix('divisi')->name('divisi.')->group(function () {
    Route::get('/', [DivisiController::class, 'publicIndex'])->name('index');
    Route::get('/{id}', [DivisiController::class, 'publicShow'])->name('show');
});

// Anggota HIMA Routes (Public)
Route::prefix('anggota')->name('anggota.')->group(function () {
    Route::get('/', [AnggotaHimaController::class, 'index'])->name('index');
    Route::get('/{id}', [AnggotaHimaController::class, 'show'])->name('show');
});

// Pendaftaran Routes (Public)
Route::prefix('pendaftaran')->name('pendaftaran.')->group(function () {
    // Halaman utama & form pendaftaran
    Route::get('/', [PendaftaranController::class, 'index'])->name('index');
    Route::get('/create', [PendaftaranController::class, 'create'])->name('create');
    Route::post('/', [PendaftaranController::class, 'store'])->name('store');

    // Halaman status pendaftaran
    Route::get('/status/{id}', [PendaftaranController::class, 'status'])->name('status');
    Route::get('/success', [PendaftaranController::class, 'success'])->name('success');

    // Cek status pendaftaran
    Route::get('/check-status', [PendaftaranController::class, 'showCheckStatus'])->name('check-status.form');
    Route::post('/check-status', [PendaftaranController::class, 'checkStatus'])->name('check-status');

    // Halaman kondisi khusus
    Route::get('/closed', [PendaftaranController::class, 'closed'])->name('closed');
    Route::get('/quota-full', [PendaftaranController::class, 'quotaFull'])->name('quota-full');
    Route::get('/coming-soon', [PendaftaranController::class, 'comingSoon'])->name('coming-soon');
    Route::get('/ended', [PendaftaranController::class, 'ended'])->name('ended');

    // Dokumen pendaftaran
    Route::get('/{id}/download-dokumen', [PendaftaranController::class, 'downloadDokumen'])->name('download-dokumen');
    Route::get('/{id}/view-dokumen', [PendaftaranController::class, 'viewDokumen'])->name('view-dokumen');

    // API
    Route::get('/api/status', [PendaftaranController::class, 'getStatusApi'])->name('api.status');

    // Admin routes (jika diperlukan)
    Route::get('/{id}/edit', [PendaftaranController::class, 'edit'])->name('edit');
    Route::put('/{id}', [PendaftaranController::class, 'update'])->name('update');
    Route::delete('/{id}', [PendaftaranController::class, 'destroy'])->name('destroy');
});

// API Routes
Route::prefix('api')->group(function () {
    Route::get('/pendaftaran/{id}', [PendaftaranController::class, 'showApi'])->name('api.pendaftaran.show');
    Route::get('/pendaftaran-status', [PendaftaranController::class, 'getStatusApi'])->name('api.pendaftaran-status');
});

Route::middleware(['auth'])->prefix('admin')->name('admin.')->group(function () {

    // Dashboard
    Route::get('dashboard', [DashboardController::class, 'index'])->name('dashboard');

    // Komentar Management (ADMIN)
    Route::get('komentar', [AdminKomentarController::class, 'index'])
        ->name('komentar.index')
        ->middleware(\App\Http\Middleware\RoleMiddleware::class . ':admin');

    Route::delete('komentar/{id}', [AdminKomentarController::class, 'destroy'])
        ->name('komentar.destroy')
        ->middleware(\App\Http\Middleware\RoleMiddleware::class . ':admin');

    // Anggota Management
    Route::prefix('anggota')->name('anggota.')->group(function () {
        Route::get('/', [AdminAnggotaController::class, 'index'])->name('index');
        Route::get('/create', [AdminAnggotaController::class, 'create'])->name('create');
        Route::post('/', [AdminAnggotaController::class, 'store'])->name('store');
        Route::get('/{id}', [AdminAnggotaController::class, 'show'])->name('show');
        Route::get('/{id}/edit', [AdminAnggotaController::class, 'edit'])->name('edit');
        Route::put('/{id}', [AdminAnggotaController::class, 'update'])->name('update');
        Route::delete('/{id}', [AdminAnggotaController::class, 'destroy'])->name('destroy');
    });

    // Divisi Management
    Route::prefix('divisi')->name('divisi.')->group(function () {
        Route::get('/', [AdminDivisiController::class, 'index'])->name('index');
        Route::get('/create', [AdminDivisiController::class, 'create'])->name('create');
        Route::post('/', [AdminDivisiController::class, 'store'])->name('store');
        Route::get('/{id}', [AdminDivisiController::class, 'show'])->name('show');
        Route::get('/{id}/edit', [AdminDivisiController::class, 'edit'])->name('edit');
        Route::put('/{id}', [AdminDivisiController::class, 'update'])->name('update');
        Route::delete('/{id}', [AdminDivisiController::class, 'destroy'])->name('destroy');
    });

    // Jabatan Management
    Route::prefix('jabatan')->name('jabatan.')->group(function () {
        Route::get('/', [JabatanController::class, 'index'])->name('index');
        Route::get('/create', [JabatanController::class, 'create'])->name('create');
        Route::post('/', [JabatanController::class, 'store'])->name('store');
        Route::get('/{id}', [JabatanController::class, 'show'])->name('show');
        Route::get('/{id}/edit', [JabatanController::class, 'edit'])->name('edit');
        Route::put('/{id}', [JabatanController::class, 'update'])->name('update');
        Route::delete('/{id}', [JabatanController::class, 'destroy'])->name('destroy');
        Route::post('/{id}/toggle-status', [JabatanController::class, 'toggleStatus'])->name('toggle-status');
    });

    // Prestasi Management - ADMIN AND SUPER_ADMIN
    Route::middleware(\App\Http\Middleware\RoleMiddleware::class . ':admin')->prefix('prestasi')->name('prestasi.')->group(function () {
        Route::get('/', [AdminPrestasiController::class, 'index'])->name('index');
        Route::get('/create', [AdminPrestasiController::class, 'create'])->name('create');
        Route::post('/', [AdminPrestasiController::class, 'store'])->name('store');
        Route::get('/{id}', [AdminPrestasiController::class, 'show'])->name('show');
        Route::get('/{id}/edit', [AdminPrestasiController::class, 'edit'])->name('edit');
        Route::put('/{id}', [AdminPrestasiController::class, 'update'])->name('update');
        Route::delete('/{id}', [AdminPrestasiController::class, 'destroy'])->name('destroy');
        Route::match(['put', 'patch'], '/{id}/validasi', [AdminPrestasiController::class, 'validasi'])->name('validasi');
        Route::post('/bulk-action', [AdminPrestasiController::class, 'bulkAction'])->name('bulk-action');
    });

    // Mahasiswa Management
    Route::prefix('mahasiswa')->name('mahasiswa.')->group(function () {
        Route::get('/', [MahasiswaController::class, 'index'])->name('index');
        Route::get('/create', [MahasiswaController::class, 'create'])->name('create');
        Route::post('/', [MahasiswaController::class, 'store'])->name('store');

        // Specific routes must come before parameterized routes
        Route::get('/import', [MahasiswaController::class, 'importView'])->name('import.view');
        Route::post('/import', [MahasiswaController::class, 'import'])->name('import');
        Route::get('/export', [MahasiswaController::class, 'exportView'])->name('export.view');
        Route::get('/export/data', [MahasiswaController::class, 'export'])->name('export');
        Route::get('/template', [MahasiswaController::class, 'template'])->name('template');

        // Parameterized routes
        Route::get('/{id}', [MahasiswaController::class, 'show'])->name('show');
        Route::get('/{id}/edit', [MahasiswaController::class, 'edit'])->name('edit');
        Route::put('/{id}', [MahasiswaController::class, 'update'])->name('update');
        Route::delete('/{id}', [MahasiswaController::class, 'destroy'])->name('destroy');
    });

    // Mahasiswa Bermasalah Management - ADMIN AND SUPER_ADMIN
    Route::middleware(\App\Http\Middleware\RoleMiddleware::class . ':admin')->group(function () {
        Route::resource('mahasiswa-bermasalah', MahasiswaBermasalahController::class);
        Route::post('/mahasiswa-bermasalah/store-multiple', [MahasiswaBermasalahController::class, 'storeMultiple'])->name('mahasiswa-bermasalah.store-multiple');
        Route::get('/mahasiswa-bermasalah/get-mahasiswa/{nim}', [MahasiswaBermasalahController::class, 'getMahasiswaByNim'])->name('mahasiswa-bermasalah.get-mahasiswa');
    });

    // Pendaftaran Management
    Route::prefix('pendaftaran')->name('pendaftaran.')->group(function () {
        Route::get('/', [AdminPendaftaranController::class, 'index'])->name('index');
        Route::post('/buka-sesi', [AdminPendaftaranController::class, 'bukaSesi'])->name('buka-sesi');
        Route::post('/tutup-sesi', [AdminPendaftaranController::class, 'tutupSesi'])->name('tutup-sesi');
        Route::post('/update-settings', [AdminPendaftaranController::class, 'updateSettings'])->name('update-settings');
        Route::post('/bulk-interview', [AdminPendaftaranController::class, 'bulkInterview'])->name('bulk-interview');
        Route::post('/bulk-accept', [AdminPendaftaranController::class, 'bulkAccept'])->name('bulk-accept');
        Route::get('/status', [AdminPendaftaranController::class, 'getStatus'])->name('get-status');
        Route::get('/jabatan-by-divisi/{idDivisi}', [AdminPendaftaranController::class, 'getJabatanByDivisi'])->name('jabatan-by-divisi');
        Route::get('/{pendaftaran}', [AdminPendaftaranController::class, 'show'])->name('show');
        Route::get('/{pendaftaran}/edit', [AdminPendaftaranController::class, 'edit'])->name('edit');
        Route::put('/{pendaftaran}', [AdminPendaftaranController::class, 'update'])->name('update');
        Route::put('/{pendaftaran}/update-status', [AdminPendaftaranController::class, 'updateStatus'])->name('update-status');
        Route::post('/{pendaftaran}/status', [AdminPendaftaranController::class, 'changeStatus'])->name('status');
        Route::delete('/{pendaftaran}', [AdminPendaftaranController::class, 'destroy'])->name('destroy');
    });

    // Berita Management
    Route::resource('berita', AdminBeritaController::class);

    // Criteria Management
    Route::prefix('criteria')->name('criteria.')->group(function () {
        Route::get('/', [CriteriaController::class, 'index'])->name('index');
        Route::get('/create', [CriteriaController::class, 'create'])->name('create');
        Route::post('/', [CriteriaController::class, 'store'])->name('store');
        Route::get('/{id}', [CriteriaController::class, 'show'])->name('show');
        Route::get('/{id}/edit', [CriteriaController::class, 'edit'])->name('edit');
        Route::put('/{id}', [CriteriaController::class, 'update'])->name('update');
        Route::delete('/{id}', [CriteriaController::class, 'destroy'])->name('destroy');
    });

    // Pelanggaran Management - SUPER_ADMIN ONLY
    Route::middleware(\App\Http\Middleware\RoleMiddleware::class . ':super_admin')->prefix('pelanggaran')->name('pelanggaran.')->group(function () {
        Route::get('/', [PelanggaranController::class, 'index'])->name('index');
        Route::get('/create', [PelanggaranController::class, 'create'])->name('create');
        Route::post('/', [PelanggaranController::class, 'store'])->name('store');
        Route::get('/{id}', [PelanggaranController::class, 'show'])->name('show');
        Route::get('/{id}/edit', [PelanggaranController::class, 'edit'])->name('edit');
        Route::put('/{id}', [PelanggaranController::class, 'update'])->name('update');
        Route::delete('/{id}', [PelanggaranController::class, 'destroy'])->name('destroy');
    });

    // Sanksi Management - ADMIN AND SUPER_ADMIN
    Route::middleware(\App\Http\Middleware\RoleMiddleware::class . ':admin')->prefix('sanksi')->name('sanksi.')->group(function () {
        Route::get('/', [SanksiController::class, 'index'])->name('index');
        Route::get('/create', [SanksiController::class, 'create'])->name('create');
        Route::post('/', [SanksiController::class, 'store'])->name('store');
        Route::get('/{id}', [SanksiController::class, 'show'])->name('show');
        Route::get('/{id}/edit', [SanksiController::class, 'edit'])->name('edit');
        Route::put('/{id}', [SanksiController::class, 'update'])->name('update');
        Route::delete('/{id}', [SanksiController::class, 'destroy'])->name('destroy');
    });

    // Data & Reports
    Route::prefix('data')->name('data.')->group(function () {
        Route::get('/users', function () { return view('admin.data.users'); })->name('users');
        Route::get('/auggets', function () { return view('admin.data.auggets'); })->name('auggets');
        Route::get('/applets', function () { return view('admin.data.applets'); })->name('applets');
        Route::get('/process-files', function () { return view('admin.data.process-files'); })->name('process-files');
        Route::get('/scripts', function () { return view('admin.data.scripts'); })->name('scripts');
        Route::get('/chat', function () { return view('admin.data.chat'); })->name('chat');
    });
});

// ========================
// User Dashboard (After Login)
// ========================
Route::middleware(['auth'])->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
});

// Profile & account management
use App\Http\Controllers\ProfileController;

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'show'])->name('profile.show');
    Route::get('/profile/edit', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::post('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::post('/profile/avatar', [ProfileController::class, 'uploadAvatar'])->name('profile.avatar.upload');
    Route::delete('/profile/avatar', [ProfileController::class, 'removeAvatar'])->name('profile.avatar.remove');
    Route::get('/profile/password', [ProfileController::class, 'showChangePassword'])->name('profile.password');
    Route::post('/profile/password', [ProfileController::class, 'changePassword'])->name('profile.password.change');
});

// Password reset (public)
Route::get('/password/forgot', [ProfileController::class, 'showResetRequestForm'])->name('password.request');
Route::post('/password/forgot', [ProfileController::class, 'sendResetLink'])->name('password.email');
Route::get('/password/reset/{token}', [ProfileController::class, 'showResetForm'])->name('password.reset');
Route::post('/password/reset', [ProfileController::class, 'resetPassword'])->name('password.update');

Route::get('/auth/google', [GoogleController::class, 'redirectToGoogle'])->name('google.login');
Route::get('/auth/google/callback', [GoogleController::class, 'handleGoogleCallback']);

// ========================
// Register
// ========================
Route::get('/register', [RegisterController::class, 'showRegistrationForm'])->name('register');
Route::post('/register', [RegisterController::class, 'register']);

// ========================
// Admin Panel Routes (Pengurus HIMA)
// ========================
Route::middleware(['auth'])->prefix('admin-panel')->name('admin-panel.')->group(function () {
    // Dashboard
    Route::get('dashboard', [App\Http\Controllers\AdminPanel\AdminDashboardController::class, 'index'])->name('dashboard');
});

// ========================
// User Dashboard Routes
// ========================
Route::middleware('auth')->group(function () {
    Route::get('/profil', [UserDashboardController::class, 'profil'])->name('user.profil');
    Route::get('/pesan', [UserDashboardController::class, 'pesan'])->name('user.pesan');
    Route::get('/statistik', [UserDashboardController::class, 'statistik'])->name('user.statistik');
});

// ========================
// Redirects & Fallback
// ========================
Route::redirect('/admin', '/admin/dashboard');
Route::fallback(function () {
    return view('errors.404');
});

