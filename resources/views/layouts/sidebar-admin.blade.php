<nav id="sidebar" class="sidebar bg-white shadow-sm">
    <div class="sidebar-header bg-primary text-white text-center py-4">
        <h4 class="mb-0 fw-bold">HIMA Dashboard</h4>
        <small class="opacity-75">Sistem Manajemen</small>
    </div>

    <ul class="sidebar-nav list-unstyled px-3 py-3">
        {{-- Dashboard --}}
        <li class="nav-item mb-1">
            <a href="{{ route('admin.dashboard') }}" class="nav-link d-flex align-items-center {{ Request::routeIs('admin.dashboard') ? 'active' : '' }}">
                <i class="fas fa-home me-3"></i>
                <span>Dashboard</span>
            </a>
        </li>

        {{-- Master Data (Dropdown) --}}
        <li class="nav-item mb-1">
            <a href="javascript:void(0);" class="nav-link dropdown-toggle d-flex align-items-center justify-content-between" onclick="toggleDropdown(this)">
                <span class="d-flex align-items-center">
                    <i class="fas fa-database me-3"></i>
                    <span>Master Data</span>
                </span>
                <i class="fas fa-chevron-right toggle-icon"></i>
            </a>
            <div class="dropdown-submenu {{ Request::routeIs('admin.anggota.*', 'admin.divisi.*', 'admin.jabatan.*') ? 'show' : '' }}">
                <a href="{{ route('admin.anggota.index') }}" class="submenu-link {{ Request::routeIs('admin.anggota.*') ? 'active' : '' }}">
                    <i class="fas fa-users me-2"></i>Kelola Anggota
                </a>
                <a href="{{ route('admin.divisi.index') }}" class="submenu-link {{ Request::routeIs('admin.divisi.*') ? 'active' : '' }}">
                    <i class="fas fa-building me-2"></i>Kelola Divisi
                </a>
                <a href="{{ route('admin.jabatan.index') }}" class="submenu-link {{ Request::routeIs('admin.jabatan.*') ? 'active' : '' }}">
                    <i class="fas fa-user-tie me-2"></i>Kelola Jabatan
                </a>
            </div>
        </li>

        {{-- Akademik (Dropdown) --}}
        <li class="nav-item mb-1">
            <a href="javascript:void(0);" class="nav-link dropdown-toggle d-flex align-items-center justify-content-between" onclick="toggleDropdown(this)">
                <span class="d-flex align-items-center">
                    <i class="fas fa-book me-3"></i>
                    <span>Akademik</span>
                </span>
                <i class="fas fa-chevron-right toggle-icon"></i>
            </a>
            <div class="dropdown-submenu {{ Request::routeIs('admin.berita.*', 'admin.mahasiswa.*') ? 'show' : '' }}">
                <a href="{{ route('admin.berita.index') }}" class="submenu-link {{ Request::routeIs('admin.berita.*') ? 'active' : '' }}">
                    <i class="fas fa-newspaper me-2"></i>Kelola Berita
                </a>
                <a href="{{ route('admin.mahasiswa.index') }}" class="submenu-link {{ Request::routeIs('admin.mahasiswa.*') ? 'active' : '' }}">
                    <i class="fas fa-user-graduate me-2"></i>Data Mahasiswa
                </a>
            </div>
        </li>

        {{-- Pendaftaran (Dropdown) --}}
        <li class="nav-item mb-1">
            <a href="javascript:void(0);" class="nav-link dropdown-toggle d-flex align-items-center justify-content-between" onclick="toggleDropdown(this)">
                <span class="d-flex align-items-center">
                    <i class="fas fa-user-check me-3"></i>
                    <span>Pendaftaran</span>
                </span>
                <i class="fas fa-chevron-right toggle-icon"></i>
            </a>
            <div class="dropdown-submenu {{ Request::routeIs('admin.pendaftaran.*') ? 'show' : '' }}">
                <a href="{{ route('admin.pendaftaran.index') }}" class="submenu-link {{ Request::routeIs('admin.pendaftaran.*') ? 'active' : '' }}">
                    <i class="fas fa-list me-2"></i>Kelola Pendaftaran
                </a>
            </div>
        </li>

        <li class="nav-divider my-3 border-top"></li>

        <!-- Prestasi & Akademik - For Admin and Super Admin -->
        @if(Auth::user() && (Auth::user()->role === 'super_admin' || Auth::user()->role === 'admin'))
            <li class="nav-header">PRESTASI & AKADEMIK</li>

            <li class="nav-item mb-1">
                <a href="{{ route('admin.prestasi.index') }}" class="nav-link d-flex align-items-center {{ Request::routeIs('admin.prestasi.*') ? 'active' : '' }}">
                    <i class="fas fa-trophy me-3"></i>
                    <span>Kelola Prestasi</span>
                </a>
            </li>

            <li class="nav-item mb-1">
                <a href="{{ route('admin.mahasiswa-bermasalah.index') }}" class="nav-link d-flex align-items-center {{ Request::routeIs('admin.mahasiswa-bermasalah.*') ? 'active' : '' }}">
                    <i class="fas fa-exclamation-triangle me-3"></i>
                    <span>Mahasiswa Bermasalah</span>
                </a>
            </li>

            <li class="nav-divider my-3 border-top"></li>

            <li class="nav-header">DISIPLIN & SANKSI</li>

            {{-- Disiplin (Dropdown) --}}
            <li class="nav-item mb-1">
                <a href="javascript:void(0);" class="nav-link dropdown-toggle d-flex align-items-center justify-content-between" onclick="toggleDropdown(this)">
                    <span class="d-flex align-items-center">
                        <i class="fas fa-gavel me-3"></i>
                        <span>Disiplin</span>
                    </span>
                    <i class="fas fa-chevron-right toggle-icon"></i>
                </a>
                <div class="dropdown-submenu {{ Request::routeIs('admin.pelanggaran.*', 'admin.sanksi.*') ? 'show' : '' }}">
                    <a href="{{ route('admin.pelanggaran.index') }}" class="submenu-link {{ Request::routeIs('admin.pelanggaran.*') ? 'active' : '' }}">
                        <i class="fas fa-exclamation-circle me-2"></i>Data Pelanggaran
                    </a>
                    <a href="{{ route('admin.sanksi.index') }}" class="submenu-link {{ Request::routeIs('admin.sanksi.*') ? 'active' : '' }}">
                        <i class="fas fa-balance-scale me-2"></i>Data Sanksi
                    </a>
                </div>
            </li>

            <li class="nav-divider my-3 border-top"></li>

            <li class="nav-header">LAPORAN & DATA</li>

            <li class="nav-item dropdown">
                <a href="#" class="nav-link dropdown-toggle d-flex align-items-center" data-bs-toggle="dropdown">
                    <i class="fas fa-chart-bar me-3"></i>
                    <span>Laporan & Analytics</span>
                </a>
                <ul class="dropdown-menu">
                    <li>
                        <a class="dropdown-item" href="{{ route('admin.data.users') }}">
                            <i class="fas fa-users me-2"></i>Data Users
                        </a>
                    </li>
                    <li>
                        <a class="dropdown-item" href="{{ route('admin.data.auggets') }}">
                            <i class="fas fa-chart-pie me-2"></i>Statistik
                        </a>
                    </li>
                    <li>
                        <a class="dropdown-item" href="{{ route('admin.data.applets') }}">
                            <i class="fas fa-cube me-2"></i>Aplikasi
                        </a>
                    </li>
                    <li>
                        <a class="dropdown-item" href="{{ route('admin.data.scripts') }}">
                            <i class="fas fa-code me-2"></i>Scripts
                        </a>
                    </li>
                </ul>
            </li>
        @endif

        <li class="nav-divider my-3 border-top"></li>

        <!-- System -->
        <li class="nav-header">SYSTEM</li>

        <li class="nav-item mb-1">
            <a href="#" class="nav-link d-flex align-items-center">
                <i class="fas fa-cog me-3"></i>
                <span>Pengaturan Sistem</span>
            </a>
        </li>

        {{-- Logout --}}
        <li class="nav-item">
            <form action="{{ route('logout') }}" method="POST" class="m-0">
                @csrf
                <button type="submit" class="nav-link d-flex align-items-center text-danger border-0 bg-transparent w-100 text-start">
                    <i class="fas fa-sign-out-alt me-3"></i>
                    <span>Logout</span>
                </button>
            </form>
        </li>
    </ul>
</nav>

{{-- === Styling Sidebar === --}}
<style>
.sidebar {
    width: 250px;
    min-height: 100vh;
    position: fixed;
    top: 80px; left: 0;
    background: rgba(255, 255, 255, 0.08);
    border-right: 1px solid rgba(224, 231, 255, 0.2);
    transition: all 0.3s ease;
    backdrop-filter: blur(12px);
}

.sidebar-header {
    border-bottom: 1px solid rgba(224, 231, 255, 0.2);
    background: rgba(99, 102, 241, 0.1);
    color: #F8FAFC;
}

.sidebar-nav .nav-link {
    display: flex;
    align-items: center;
    padding: 10px 14px;
    border-radius: 8px;
    color: #CBD5E1;
    font-weight: 500;
    transition: all .2s ease;
}

.sidebar-nav .nav-link:hover {
    background: rgba(99, 102, 241, 0.15);
    color: #E0E7FF;
}

.sidebar-nav .nav-link.active {
    background: #6366F1;
    color: #FFFFFF;
    font-weight: 600;
    box-shadow: 0 4px 12px rgba(99, 102, 241, 0.3);
}

.sidebar-nav i {
    width: 22px;
    text-align: center;
}

.nav-divider {
    border-top: 1px solid rgba(224, 231, 255, 0.2);
    margin: 10px 0;
}

/* ===== DROPDOWN MENU STYLES ===== */

/* Parent dropdown toggle */
.dropdown-toggle {
    cursor: pointer;
    position: relative;
    user-select: none;
}

.dropdown-toggle:hover {
    background: rgba(99, 102, 241, 0.15) !important;
    color: #E0E7FF;
}

/* Toggle icon animation */
.toggle-icon {
    transition: transform 0.3s ease;
    font-size: 12px;
    color: #94A3B8;
}

.dropdown-toggle.active .toggle-icon {
    transform: rotate(90deg);
    color: #6366F1;
}

/* Submenu container */
.dropdown-submenu {
    max-height: 0;
    overflow: hidden;
    transition: max-height 0.3s ease;
    background: rgba(99, 102, 241, 0.1);
    border-left: 3px solid transparent;
    margin-top: 3px;
    margin-bottom: 3px;
    border-radius: 6px;
}

.dropdown-submenu.show {
    max-height: 500px;
    border-left-color: #6366F1;
}

/* Submenu link */
.submenu-link {
    display: flex;
    align-items: center;
    padding: 8px 14px 8px 38px;
    color: #CBD5E1;
    text-decoration: none;
    transition: all 0.2s ease;
    font-size: 14px;
    font-weight: 500;
}

.submenu-link:hover {
    background: rgba(99, 102, 241, 0.2);
    color: #E0E7FF;
    padding-left: 42px;
}

.submenu-link.active {
    background: rgba(99, 102, 241, 0.25);
    color: #E0E7FF;
    font-weight: 600;
    border-left: 3px solid #6366F1;
    padding-left: 35px;
}

.submenu-link i {
    width: 18px;
    text-align: center;
}

.nav-header {
    padding: 12px 14px 8px 14px;
    font-size: 11px;
    font-weight: 700;
    text-transform: uppercase;
    color: #94A3B8;
    letter-spacing: 0.5px;
    margin-top: 8px;
}
</style>

{{-- === JavaScript untuk Dropdown === --}}
<script>
function toggleDropdown(element) {
    // Toggle active class pada button
    element.classList.toggle('active');
    
    // Cari submenu yang berdekatan
    const submenu = element.nextElementSibling;
    if (submenu && submenu.classList.contains('dropdown-submenu')) {
        submenu.classList.toggle('show');
    }
}
</script>
</style>

