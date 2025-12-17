<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Admin Panel HIMA')</title>
    
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    
    <style>
        /* Use consistent sidebar style with super-admin layout */
        :root {
            --primary-color: #0d6efd;
            --secondary-color: #030304;
            --success-color: #1cc88a;
            --info-color: #36b9cc;
            --warning-color: #f6c23e;
            --danger-color: #e74a3b;
            --light-color: #f8f9fc;
            --black-color: #020203;
        }

        body {
            font-family: 'Inter', sans-serif;
            background-color: #f8f9fc;
        }

        #wrapper {
            display: flex;
            width: 100%;
            min-height: 100vh;
            flex-wrap: nowrap;
        }

        /* Sidebar Styles (match super admin) */
        .sidebar {
            flex: 0 0 280px;
            min-width: 280px;
            max-width: 280px;
            width: 280px;
            background: #fff;
            color: #000000;
            transition: all 0.3s;
            min-height: 100vh;
            box-shadow: 0 0.15rem 1.75rem 0 rgba(58, 59, 69, 0.15);
            overflow-y: auto;
            position: fixed;
            left: 0;
            top: 0;
            z-index: 1000;
        }

        .sidebar-header {
            padding: 25px 20px;
            background: linear-gradient(180deg, var(--primary-color) 0%, #224abe 100%);
            color: #fff;
            text-align: center;
        }

        .sidebar-header h5, .sidebar-header h4 {
            font-weight: 700;
            margin-bottom: 5px;
            font-size: 1.2rem;
        }

        .sidebar-nav { padding: 20px 0; }

        .nav-section-title {
            font-size: 0.75rem;
            font-weight: 700;
            text-transform: uppercase;
            color: var(--secondary-color);
            margin-bottom: 15px;
            letter-spacing: 0.5px;
            padding: 0 20px;
        }

        .nav-link {
            color: #010202;
            padding: 12px 15px;
            border-radius: 8px;
            transition: all 0.3s;
            display: flex;
            align-items: center;
            text-decoration: none;
            font-weight: 500;
            margin: 0 20px 8px 20px;
        }

        .nav-link:hover { color: var(--primary-color); background-color: #f8f9fa; }
        .nav-link.active { color: var(--primary-color); background-color: #eef2ff; font-weight: 600; }
        .nav-link i { width: 20px; text-align: center; margin-right: 12px; }

        .main-content { flex: 1; margin-left: 280px; display: flex; flex-direction: column; }
        .topbar { background: #fff; padding: 1rem; box-shadow: 0 2px 4px rgba(0,0,0,0.05); border-bottom: 1px solid #E5E7EB; }
        .content { flex: 1; padding: 1.5rem; overflow-y: auto; }

        @media (max-width: 768px) {
            .sidebar { transform: translateX(-100%); }
            .sidebar.active { transform: translateX(0); }
            .main-content { margin-left: 0; }
        }
    </style>

    @stack('styles')
</head>
<body>
    <div class="admin-wrapper">
        <!-- Sidebar -->
        <aside class="sidebar" id="sidebar">
            <div class="sidebar-header">
                <h5><i class="fas fa-tachometer-alt"></i> Admin Panel</h5>
                <small>Pengurus HIMA</small>
            </div>

            <nav class="sidebar-nav">
                <!-- Dashboard -->
                <div class="nav-section">
                    <a href="{{ route('admin-panel.dashboard') }}" class="nav-link {{ request()->routeIs('admin-panel.dashboard') ? 'active' : '' }}">
                        <i class="fas fa-home"></i>
                        <span>Dashboard</span>
                    </a>
                </div>

                <!-- Manajemen Anggota -->
                <div class="nav-section">
                    <div class="nav-section-title">Manajemen Anggota</div>
                    <a href="{{ route('admin.anggota.index') }}" class="nav-link {{ request()->routeIs('admin.anggota.*') ? 'active' : '' }}">
                        <i class="fas fa-users"></i>
                        <span>Anggota HIMA</span>
                    </a>
                    <a href="{{ route('admin.divisi.index') }}" class="nav-link {{ request()->routeIs('admin.divisi.*') ? 'active' : '' }}">
                        <i class="fas fa-building"></i>
                        <span>Divisi</span>
                    </a>
                    <a href="{{ route('admin.jabatan.index') }}" class="nav-link {{ request()->routeIs('admin.jabatan.*') ? 'active' : '' }}">
                        <i class="fas fa-briefcase"></i>
                        <span>Jabatan</span>
                    </a>
                </div>

                <!-- Informasi -->
                <div class="nav-section">
                    <div class="nav-section-title">Informasi</div>
                    <a href="{{ route('admin.berita.index') }}" class="nav-link {{ request()->routeIs('admin.berita.*') ? 'active' : '' }}">
                        <i class="fas fa-newspaper"></i>
                        <span>Berita</span>
                    </a>
                    <a href="{{ route('admin.komentar.index') }}" class="nav-link {{ request()->routeIs('admin.komentar.*') ? 'active' : '' }}">
                        <i class="fas fa-comments"></i>
                        <span>Komentar</span>
                    </a>
                </div>

                <!-- Pendaftaran -->
                <div class="nav-section">
                    <div class="nav-section-title">Pendaftaran</div>
                    <a href="{{ route('admin.pendaftaran.index') }}" class="nav-link {{ request()->routeIs('admin.pendaftaran.*') ? 'active' : '' }}">
                        <i class="fas fa-clipboard-list"></i>
                        <span>Data Pendaftaran</span>
                    </a>
                </div>

                <!-- Account -->
                <div class="nav-section" style="margin-top: auto; padding-bottom: 1rem;">
                    <div class="nav-section-title">Akun</div>
                    <a href="{{ route('profile.show') }}" class="nav-link">
                        <i class="fas fa-user"></i>
                        <span>Profil Saya</span>
                    </a>
                    <a href="{{ route('logout') }}" class="nav-link" onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                        <i class="fas fa-sign-out-alt"></i>
                        <span>Logout</span>
                    </a>
                    <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
                        @csrf
                    </form>
                </div>
            </nav>
        </aside>

        <!-- Main Content -->
        <div class="main-content">
            <!-- Top Bar -->
            <div class="topbar">
                <div class="topbar-left">
                    <button class="btn btn-sm btn-outline-secondary toggle-sidebar" id="toggleSidebar">
                        <i class="fas fa-bars"></i>
                    </button>
                </div>
                <div class="topbar-right">
                    <div class="user-info">
                        <div class="user-avatar">{{ strtoupper(substr(auth()->user()->name, 0, 1)) }}</div>
                        <div>
                            <div style="font-weight: 600; color: #1F2937;">{{ auth()->user()->name }}</div>
                            <small style="color: #6B7280;">Pengurus HIMA</small>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Content -->
            <div class="content">
                @yield('content')
            </div>
        </div>
    </div>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    
    <script>
        // Toggle sidebar on mobile
        document.getElementById('toggleSidebar')?.addEventListener('click', function() {
            document.getElementById('sidebar').classList.toggle('active');
        });

        // Close sidebar when clicking a link on mobile
        document.querySelectorAll('.sidebar .nav-link').forEach(link => {
            link.addEventListener('click', function() {
                if (window.innerWidth <= 768) {
                    document.getElementById('sidebar').classList.remove('active');
                }
            });
        });
    </script>

    @stack('scripts')
</body>
</html>
