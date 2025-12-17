## Sistem Kontrol Akses Dashboard (Role-Based Access Control)

### Aturan Akses Dashboard

#### 1. **MAHASISWA** ❌
- **TIDAK BISA** akses `/admin/*`
- **TIDAK BISA** akses `/dashboard` (anggota dashboard)
- ✅ Bisa akses `/home` (user dashboard)
- ✅ Bisa akses `/profile` (profil pribadi)
- **Redirect otomatis** ke `/home` jika coba akses admin

#### 2. **ANGGOTA** ✅
- ✅ Akses `/dashboard` (dashboard pribadi anggota)
- ✅ Akses `/admin/*` (menu kelola dasar)
- **TIDAK BISA** akses fitur admin advanced:
  - Prestasi & Akademik
  - Mahasiswa Bermasalah
  - Disiplin & Sanksi (Pelanggaran, Sanksi)
  - Laporan & Analytics
- ✅ Bisa akses: Dashboard, Anggota, Divisi, Jabatan, Berita, Pendaftaran, Data Mahasiswa

#### 3. **ADMIN** ✅
- ✅ Akses semua menu di `/admin/*` kecuali menu super_admin-only
- Sama dengan anggota untuk menu visibility

#### 4. **SUPER_ADMIN** 🔐
- ✅ Akses **SEMUA** fitur
- ✅ Akses semua menu di sidebar
- ✅ Akses `/admin/*`
- ✅ Akses fitur restricted (Prestasi, Mahasiswa Bermasalah, Pelanggaran, Sanksi)
- ✅ Akses Laporan & Analytics

---

### Implementasi Teknis

#### Middleware
- **admin_access**: Blok mahasiswa dari `/admin/*`
- **check_role**: Cek role spesifik untuk rute tertentu

#### Routes
```
/dashboard → DashboardController@index
  - Mahasiswa → redirect /home
  - Anggota → anggota dashboard
  - Admin/Super_admin → admin dashboard

/admin/* → Protected by 'admin_access' middleware
  - Mahasiswa → blocked
  - Anggota, Admin, Super_admin → allowed (dengan sidebar filtering)
```

#### Controller Logic (DashboardController)
```php
if ($user->role === 'mahasiswa') {
    return redirect('/home'); // Mahasiswa tidak bisa akses admin
}
if ($user->role === 'anggota') {
    return $this->anggotaDashboard(); // Anggota dashboard
}
if ($user->role === 'super_admin' || $user->role === 'admin') {
    return $this->adminDashboard(); // Admin dashboard
}
```

#### Sidebar Filtering (sidebar-admin.blade.php)
```blade
@if(Auth::user()->role === 'super_admin')
    <!-- Tampilkan menu advanced: Prestasi, Pelanggaran, Sanksi, Analytics -->
@endif
```

#### Login Redirects (LoginController)
```
super_admin → /admin/pendaftaran
admin → /admin/dashboard
anggota → /dashboard
mahasiswa/user → /home
```

---

### Testing Checklist

- [ ] Login sebagai **mahasiswa** → redirect dari admin → `/home`
- [ ] Login sebagai **anggota** → bisa akses `/dashboard` (anggota dashboard)
- [ ] Login sebagai **anggota** → akses `/admin/dashboard` → dashboard admin
- [ ] Login sebagai **anggota** → sidebar tidak menampilkan Prestasi, Pelanggaran, Sanksi
- [ ] Login sebagai **admin** → akses semua menu dasar, filter menu advanced
- [ ] Login sebagai **super_admin** → akses semua, semua menu visible
- [ ] Direct URL `/admin/prestasi` sebagai **mahasiswa** → error/blocked
- [ ] Direct URL `/admin/prestasi` sebagai **anggota** → blocked (route tidak visible)
- [ ] Direct URL `/admin/prestasi` sebagai **super_admin** → allowed

---

### File yang Diubah

1. `app/Http/Controllers/DashboardController.php` - Tambah role checking
2. `app/Http/Middleware/AdminAccess.php` - Middleware baru untuk blok mahasiswa
3. `app/Http/Middleware/CheckRoleAccess.php` - Middleware baru untuk role specific
4. `app/Http/Kernel.php` - Register middleware
5. `routes/web.php` - Tambah middleware ke /admin routes
6. `resources/views/layouts/sidebar-admin.blade.php` - Sidebar role-based filtering

---

**Created:** 2025-12-14
**Status:** Implementation Complete ✅
