## 🔐 Role-Based Access Control Flow

### Access Control Diagram

```
┌─────────────────────────────────────────────────────────────────┐
│                    USER LOGIN                                   │
└────────────────────┬────────────────────────────────────────────┘
                     │
                     ▼
           ┌─────────────────────┐
           │  Auth::attempt()    │
           └────────┬────────────┘
                    │
        ┌───────────┼───────────┬───────────┬──────────────┐
        │           │           │           │              │
        ▼           ▼           ▼           ▼              ▼
    MAHASISWA  ANGGOTA      ADMIN    SUPER_ADMIN       (OTHER)
        │           │           │           │              │
        │           │           │           │              │
        └────────┬──┴──┬────────┴──┬────────┴──┬────────────┘
                 │     │           │           │
            Redirect  Auto        Auto         Auto
            to /      to /        to /         to /
            home    dashboard   admin/       admin/
                                dashboard   pendaftaran
                      │           │           │
                      └───────────┼───────────┘
                                  │
                      ┌───────────▼────────────┐
                      │   /admin/* Routes      │
                      │   (admin_access MW)    │
                      └───────────┬────────────┘
                                  │
                    ┌─────────────┴─────────────┐
                    │                           │
            MAHASISWA BLOCKED?           Allow: ANGGOTA/
            YES ─────────────────→         ADMIN/SUPER_ADMIN
            └─ Redirect /home

    ┌─────────────────────────────────────────────────────────┐
    │         INSIDE ADMIN DASHBOARD                          │
    │         DashboardController::index()                    │
    └─────────────────────────────────────────────────────────┘
            │
            ├─ If MAHASISWA → redirect /home (security backup)
            ├─ If ANGGOTA → render anggota.blade.php
            └─ If ADMIN/SUPER_ADMIN → render admin.blade.php

    ┌─────────────────────────────────────────────────────────┐
    │         SIDEBAR FILTERING                               │
    │         sidebar-admin.blade.php                         │
    └─────────────────────────────────────────────────────────┘
            │
            ├─ @if(role === 'super_admin')
            │  ├─ Show: Prestasi & Akademik
            │  ├─ Show: Mahasiswa Bermasalah
            │  ├─ Show: Disiplin & Sanksi
            │  └─ Show: Laporan & Analytics
            │
            └─ @else (ANGGOTA/ADMIN)
               ├─ Show: Dashboard, Anggota, Divisi, Jabatan
               ├─ Show: Berita, Pendaftaran, Mahasiswa
               └─ Hide: Advanced features
```

---

### Access Matrix

| Feature | Mahasiswa | Anggota | Admin | Super_Admin |
|---------|-----------|---------|-------|------------|
| /home | ✅ | ✅ | ✅ | ✅ |
| /dashboard | ❌ Blocked | ✅ Personal | ❌ Blocked | ❌ Blocked |
| /admin/* | ❌ Blocked | ✅ | ✅ | ✅ |
| Admin Dashboard | ❌ | ✅ | ✅ | ✅ |
| Anggota Dashboard | ❌ | ✅ | ❌ | ❌ |
| Kelola Anggota | ❌ | ✅ | ✅ | ✅ |
| Prestasi | ❌ | ❌ Sidebar | ❌ Sidebar | ✅ |
| Pelanggaran | ❌ | ❌ Sidebar | ❌ Sidebar | ✅ |
| Sanksi | ❌ | ❌ Sidebar | ❌ Sidebar | ✅ |
| Analytics | ❌ | ❌ Sidebar | ❌ Sidebar | ✅ |

---

### Middleware Flow

```
REQUEST → admin_access MW
  │
  ├─ Is user logged in?
  │  └─ NO: redirect /login
  │
  └─ Is user mahasiswa?
     └─ YES: redirect /home (error msg)
     └─ NO: allow next

REQUEST → DashboardController::index()
  │
  ├─ Is user mahasiswa?
  │  └─ YES: redirect /home
  │
  ├─ Is user anggota?
  │  └─ YES: return anggotaDashboard()
  │
  └─ Is user admin/super_admin?
     └─ YES: return adminDashboard()
```

---

### Security Layers

1. **Layer 1 - Route Middleware (admin_access)**
   - Blocks mahasiswa from entering /admin routes
   - First defense barrier

2. **Layer 2 - Controller Logic (DashboardController)**
   - Role-based dashboard routing
   - Backup security for /dashboard endpoint
   - Ensures correct view is rendered

3. **Layer 3 - Sidebar Filtering (Blade)**
   - Hides menu items based on role
   - UX-level access control
   - Doesn't expose restricted menu items

4. **Layer 4 - Backend Route Protection**
   - Each admin route still has its own middleware
   - Even if sidebar is hacked, routes are protected

---

### Configuration Files Updated

✅ `app/Http/Controllers/DashboardController.php`
- Added role checking in index() method
- Routes mahasiswa to /home
- Routes anggota to anggota dashboard
- Routes admin/super_admin to admin dashboard

✅ `app/Http/Middleware/AdminAccess.php` (NEW)
- Blocks mahasiswa from /admin/*
- Allows anggota, admin, super_admin

✅ `app/Http/Middleware/CheckRoleAccess.php` (NEW)
- Specific role checking
- Used for protected features

✅ `app/Http/Kernel.php`
- Registered 'admin_access' middleware
- Registered 'check_role' middleware

✅ `routes/web.php`
- Added 'admin_access' middleware to /admin routes

✅ `resources/views/layouts/sidebar-admin.blade.php`
- Wrapped restricted menus in @if(role === 'super_admin')
- Cleaned up duplicate menu items

---

### Testing Instructions

#### Test 1: Mahasiswa Access to Admin
```
1. Login as mahasiswa (ahmad@hima.com)
2. Try access /admin/dashboard
3. Expected: Redirect to /home with error message
```

#### Test 2: Anggota Access to Personal Dashboard
```
1. Login as anggota (superadmin@hima.com)
2. Access /dashboard
3. Expected: Show anggota personal dashboard
4. Verify: Sidebar doesn't show Prestasi, Pelanggaran, Sanksi
```

#### Test 3: Anggota Access to Admin Dashboard
```
1. Login as anggota (superadmin@hima.com)
2. Access /admin/dashboard
3. Expected: Show admin dashboard
4. Verify: Can see core menus (Anggota, Divisi, Berita, etc.)
```

#### Test 4: Super Admin Full Access
```
1. Login as super_admin (admin@local.test)
2. Access /admin/dashboard
3. Verify: All menus visible including Prestasi, Pelanggaran, Sanksi
4. Verify: Can access all restricted features
```

---

**Implementation Status:** ✅ Complete
**Last Updated:** 2025-12-14
**Version:** 1.0
