<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Admin - HIMA Dashboard')</title>
    
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">

    <style>
        :root {
            --primary-color: #0d6efd;
            --secondary-color: #030304;
            --success-color: #1cc88a;
            --info-color: #36b9cc;
            --warning-color: #f6c23e;
            --danger-color: #e74a3b;
            --light-color: #f8f9fc;
            --black-color: #020203;
            --dropdown-animation-speed: 0.3s;
        }
        
        body {
            font-family: 'Inter', sans-serif;
            background-color: #f8f9fc;
        }
        
        #wrapper {
            display: flex;
            width: 100%;
            height: 100vh;
            flex-wrap: nowrap;
        }
        
        /* Sidebar Styles - TIDAK BISA DITUTUP */
        #sidebar {
            flex: 0 0 280px;
            min-width: 280px;
            max-width: 280px;
            width: 280px;
            background: #fff;
            color: #000000;
            transition: none;
            height: 100vh;
            box-shadow: 0 0.15rem 1.75rem 0 rgba(58, 59, 69, 0.15);
            overflow-y: auto;
            position: fixed;
            left: 0;
            top: 0;
            z-index: 999;
        }
        
        #sidebar .sidebar-header {
            padding: 25px 20px;
            background: linear-gradient(180deg, var(--primary-color) 0%, #224abe 100%);
            color: #fff;
            text-align: center;
        }
        
        #sidebar .sidebar-header h4 {
            font-weight: 700;
            margin-bottom: 5px;
            font-size: 1.5rem;
        }
        
        #sidebar .sidebar-header small {
            font-size: 0.85rem;
            opacity: 0.9;
        }
        
        .sidebar-nav {
            padding: 20px 0;
        }
        
        .sidebar-nav .nav-section {
            padding: 0 20px;
            margin-bottom: 25px;
        }
        
        .sidebar-nav .nav-section-title {
            font-size: 0.75rem;
            font-weight: 700;
            text-transform: uppercase;
            color: var(--secondary-color);
            margin-bottom: 15px;
            letter-spacing: 0.5px;
        }
        
        .sidebar-nav .nav-item {
            margin-bottom: 8px;
        }
        
        .sidebar-nav .nav-link {
            color: #010202;
            padding: 12px 15px;
            border-radius: 8px;
            transition: all 0.3s;
            display: flex;
            align-items: center;
            text-decoration: none;
            font-weight: 500;
        }
        
        .sidebar-nav .nav-link:hover {
            color: var(--primary-color);
            background-color: #f8f9fa;
        }
        
        .sidebar-nav .nav-link.active {
            color: var(--primary-color);
            background-color: #eef2ff;
            font-weight: 600;
        }
        
        .sidebar-nav .nav-link i {
            width: 20px;
            text-align: center;
            margin-right: 12px;
            font-size: 1rem;
        }

        /* Dropdown Styling - Custom dengan Animasi */
        .sidebar-nav .dropdown-toggle {
            display: flex;
            align-items: center;
            justify-content: space-between;
            cursor: pointer;
            position: relative;
        }

        .sidebar-nav .dropdown-toggle::after {
            content: '\f078';
            font-family: 'Font Awesome 6 Free';
            font-weight: 900;
            border: none;
            font-size: 0.8rem;
            transition: transform var(--dropdown-animation-speed) ease;
            margin-left: auto;
        }

        .sidebar-nav .dropdown.show .dropdown-toggle::after {
            transform: rotate(180deg);
        }

        .sidebar-nav .dropdown-menu {
            border: none;
            background-color: #f9fafc;
            box-shadow: none;
            padding: 0;
            min-width: auto;
            margin: 0 15px;
            border-radius: 8px;
            overflow: hidden;
            position: relative;
            float: none;
            
            /* Animasi Properties */
            max-height: 0;
            opacity: 0;
            visibility: hidden;
            transition: all var(--dropdown-animation-speed) cubic-bezier(0.4, 0, 0.2, 1);
            transform-origin: top;
            transform: scaleY(0.95) translateY(-10px);
        }

        .sidebar-nav .dropdown.show .dropdown-menu {
            /* Animasi ketika dibuka */
            max-height: 500px; /* Nilai cukup besar untuk menampung semua item */
            opacity: 1;
            visibility: visible;
            margin-top: 5px;
            margin-bottom: 5px;
            transform: scaleY(1) translateY(0);
            animation: dropdownSlideIn var(--dropdown-animation-speed) ease forwards;
        }

        /* Keyframes untuk animasi yang lebih smooth */
        @keyframes dropdownSlideIn {
            0% {
                opacity: 0;
                transform: scaleY(0.95) translateY(-10px);
                max-height: 0;
            }
            100% {
                opacity: 1;
                transform: scaleY(1) translateY(0);
                max-height: 500px;
            }
        }

        @keyframes dropdownSlideOut {
            0% {
                opacity: 1;
                transform: scaleY(1) translateY(0);
                max-height: 500px;
            }
            100% {
                opacity: 0;
                transform: scaleY(0.95) translateY(-10px);
                max-height: 0;
            }
        }

        /* Untuk dropdown yang sedang ditutup */
        .sidebar-nav .dropdown.closing .dropdown-menu {
            animation: dropdownSlideOut var(--dropdown-animation-speed) ease forwards;
        }

        .sidebar-nav .dropdown-item {
            color: #010202;
            padding: 10px 15px 10px 45px;
            font-weight: 500;
            border-radius: 6px;
            margin: 2px 0;
            transition: all 0.2s;
            display: flex;
            align-items: center;
            gap: 8px;
            position: relative;
            text-decoration: none;
            
            /* Animasi untuk item dropdown */
            opacity: 0;
            transform: translateX(-10px);
            transition: opacity 0.2s ease, transform 0.2s ease;
        }

        .sidebar-nav .dropdown.show .dropdown-item {
            opacity: 1;
            transform: translateX(0);
        }

        /* Stagger animation untuk item dropdown */
        .sidebar-nav .dropdown.show .dropdown-item:nth-child(1) { transition-delay: 0.05s; }
        .sidebar-nav .dropdown.show .dropdown-item:nth-child(2) { transition-delay: 0.1s; }
        .sidebar-nav .dropdown.show .dropdown-item:nth-child(3) { transition-delay: 0.15s; }
        .sidebar-nav .dropdown.show .dropdown-item:nth-child(4) { transition-delay: 0.2s; }
        .sidebar-nav .dropdown.show .dropdown-item:nth-child(5) { transition-delay: 0.25s; }

        .sidebar-nav .dropdown-item:hover {
            color: var(--primary-color);
            background-color: #f0f4ff;
        }

        .sidebar-nav .dropdown-item.active {
            color: var(--primary-color);
            background-color: #eef2ff;
            font-weight: 600;
        }

        .sidebar-nav .dropdown-item i {
            width: 16px;
            text-align: center;
        }

        .sidebar-nav .dropdown-item::before {
            content: '';
            position: absolute;
            left: 25px;
            top: 50%;
            width: 5px;
            height: 5px;
            background-color: #adb5bd;
            border-radius: 50%;
            transform: translateY(-50%);
            transition: background-color 0.2s ease;
        }

        .sidebar-nav .dropdown-item.active::before {
            background-color: var(--primary-color);
        }

        /* Indikator untuk menu yang memiliki submenu aktif */
        .sidebar-nav .dropdown.active > .nav-link {
            color: var(--primary-color);
            background-color: #eef2ff;
        }
        
        /* Content Styles */
        #content {
            flex: 1 1 auto;
            width: calc(100% - 280px);
            margin-left: 280px;
            padding: 20px;
            height: 100vh;
            transition: none;
            overflow-y: auto;
            overflow-x: hidden;
        }
        
        /* Tombol sidebar collapse di HIDE */
        #sidebarCollapse {
            display: none !important;
        }
        
        .navbar {
            background: #fff;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
            margin-bottom: 20px;
            border-radius: 8px;
            padding-left: 20px;
        }
        
        .card {
            border: none;
            box-shadow: 0 0.15rem 1.75rem 0 rgba(0, 0, 0, 0.15);
            margin-bottom: 20px;
            border-radius: 12px;
        }
        
        .card-header {
            background: #fff;
            border-bottom: 1px solid #e3e6f0;
            font-weight: 600;
            padding: 20px;
            border-radius: 12px 12px 0 0 !important;
        }
        
        .btn-primary {
            background-color: var(--primary-color);
            border-color: var(--primary-color);
            border-radius: 8px;
            font-weight: 500;
        }
        
        .btn-primary:hover {
            background-color: #2e59d9;
            border-color: #2e59d9;
        }
        
        /* Responsive */
        @media (max-width: 768px) {
            #sidebar {
                flex: 0 0 280px;
                min-width: 280px;
                max-width: 280px;
                width: 280px;
                position: fixed;
                left: 0;
                top: 0;
                z-index: 1050;
                height: 100vh;
                overflow-y: auto;
                box-shadow: 0 0 20px rgba(0, 0, 0, 0.1);
            }
            
            #content {
                width: 100%;
                margin-left: 280px;
                padding: 15px;
            }
            
            #sidebar .sidebar-header h4,
            #sidebar .sidebar-header small,
            #sidebar .nav-link span,
            #sidebar .nav-section-title,
            #sidebar .checkbox-item span,
            #sidebar .dropdown-toggle::after {
                display: block !important;
            }
            
            #sidebar .nav-link {
                padding: 12px 15px;
                justify-content: flex-start;
            }
            
            #sidebar .nav-link i {
                margin-right: 12px;
                font-size: 1rem;
            }
            
            /* Nonaktifkan animasi di mobile untuk performa */
            .sidebar-nav .dropdown-menu {
                transition: none !important;
                animation: none !important;
            }
            
            .sidebar-nav .dropdown-item {
                transition: none !important;
                opacity: 1 !important;
                transform: none !important;
            }
            
            #sidebar .checkbox-item {
                padding: 12px 15px;
                justify-content: flex-start;
            }
            
            #sidebar .checkbox-icon {
                margin-right: 12px;
            }
        }
        
        @media (max-width: 480px) {
            #sidebar {
                width: 100%;
                max-width: 100%;
                min-width: 100%;
            }
            
            #content {
                width: 100%;
                margin-left: 0;
                padding: 10px;
            }
        }
    </style>
    
    @stack('styles')
</head>
<body>
    <div id="wrapper">
        <!-- Sidebar -->
        <nav id="sidebar" class="sidebar">
            <div class="sidebar-header bg-primary text-white text-center py-4">
                <h4 class="mb-0 fw-bold">HIMA Dashboard</h4>
                <small class="opacity-75">Sistem Manajemen</small>
            </div>
            
            <ul class="sidebar-nav">
                {{-- DASHBOARD --}}
                <li class="nav-item">
                    <a href="{{ route('admin.dashboard') }}" class="nav-link {{ Request::routeIs('admin.dashboard') ? 'active' : '' }}">
                        <i class="fas fa-home me-3"></i>
                        <span>Dashboard</span>
                    </a>
                </li>
                
                {{-- MASTER DATA DROPDOWN --}}
                @php
                    $isMasterDataActive = Request::routeIs('admin.anggota.*') || 
                                         Request::routeIs('admin.jabatan.*') || 
                                         Request::routeIs('admin.divisi.*') || 
                                         Request::routeIs('admin.mahasiswa.*');
                @endphp
                <li class="nav-item dropdown {{ $isMasterDataActive ? 'active show' : '' }}">
                    <a href="#" class="nav-link dropdown-toggle {{ $isMasterDataActive ? 'active' : '' }}" 
                       onclick="toggleDropdown(this)">
                        <i class="fas fa-database me-3"></i>
                        <span>Master Data</span>
                    </a>
                    <ul class="dropdown-menu {{ $isMasterDataActive ? 'show' : '' }}">
                        <li>
                            <a href="{{ route('admin.anggota.index') }}" class="dropdown-item {{ Request::routeIs('admin.anggota.*') ? 'active' : '' }}">
                                <i class="fas fa-users me-2"></i>
                                <span>Kelola Anggota</span>
                            </a>
                        </li>
                        <li>
                            <a href="{{ route('admin.jabatan.index') }}" class="dropdown-item {{ Request::routeIs('admin.jabatan.*') ? 'active' : '' }}">
                                <i class="fas fa-briefcase me-2"></i>
                                <span>Kelola Jabatan</span>
                            </a>
                        </li>
                        <li>
                            <a href="{{ route('admin.divisi.index') }}" class="dropdown-item {{ Request::routeIs('admin.divisi.*') ? 'active' : '' }}">
                                <i class="fas fa-building me-2"></i>
                                <span>Kelola Divisi</span>
                            </a>
                        </li>
                        <li>
                            <a href="{{ route('admin.mahasiswa.index') }}" class="dropdown-item {{ Request::routeIs('admin.mahasiswa.*') ? 'active' : '' }}">
                                <i class="fas fa-user-graduate me-2"></i>
                                <span>Data Mahasiswa</span>
                            </a>
                        </li>
                    </ul>
                </li>

                {{-- AHP & CRITERIA DROPDOWN --}}
                @php
                    $isAhpActive = Request::routeIs('admin.ahp.*') || Request::routeIs('admin.criteria.*');
                @endphp
                <li class="nav-item dropdown {{ $isAhpActive ? 'active show' : '' }}">
                    <a href="#" class="nav-link dropdown-toggle {{ $isAhpActive ? 'active' : '' }}" 
                       onclick="toggleDropdown(this)">
                        <i class="fas fa-balance-scale me-3"></i>
                        <span>SPK</span>
                    </a>
                    <ul class="dropdown-menu {{ $isAhpActive ? 'show' : '' }}">
                        <li>
                            <a href="{{ route('admin.criteria.index') }}" class="dropdown-item {{ Request::routeIs('admin.criteria.*') ? 'active' : '' }}">
                                <i class="fas fa-list me-2"></i>
                                <span>Kelola Kriteria</span>
                            </a>
                        </li>
                        <li>
                            <a href="{{ route('admin.ahp.index') }}" class="dropdown-item {{ Request::routeIs('admin.ahp.index') ? 'active' : '' }}">
                                <i class="fas fa-home me-2"></i>
                                <span>Dashboard AHP</span>
                            </a>
                        </li>
                        <li>
                            <a href="{{ route('admin.ahp.perbandingan') }}" class="dropdown-item {{ Request::routeIs('admin.ahp.perbandingan') ? 'active' : '' }}">
                                <i class="fas fa-exchange-alt me-2"></i>
                                <span>Perbandingan Berpasangan</span>
                            </a>
                        </li>
                        <li>
                            <a href="{{ route('admin.ahp.hitung') }}" class="dropdown-item {{ Request::routeIs('admin.ahp.hitung') ? 'active' : '' }}">
                                <i class="fas fa-calculator me-2"></i>
                                <span>Hitung AHP</span>
                            </a>
                        </li>
                        <li>
                            <a href="{{ route('admin.ahp.hasil') }}" class="dropdown-item {{ Request::routeIs('admin.ahp.hasil') ? 'active' : '' }}">
                                <i class="fas fa-chart-bar me-2"></i>
                                <span>Lihat Hasil</span>
                            </a>
                        </li>
                        <li>
                            <a href="{{ route('admin.ahp.ranking') }}" class="dropdown-item {{ Request::routeIs('admin.ahp.ranking') ? 'active' : '' }}">
                                <i class="fas fa-trophy me-2"></i>
                                <span>Ranking SAW</span>
                            </a>
                        </li>
                    </ul>
                </li>

                {{-- AKADEMIK DROPDOWN (Super Admin Only) --}}
                @if(auth()->check() && auth()->user()->role === 'super_admin')
                @php
                    $isAkademikActive = Request::routeIs('admin.prestasi.*') || 
                                        Request::routeIs('admin.mahasiswa-bermasalah.*');
                @endphp
                <li class="nav-item dropdown {{ $isAkademikActive ? 'active show' : '' }}">
                    <a href="#" class="nav-link dropdown-toggle {{ $isAkademikActive ? 'active' : '' }}" 
                       onclick="toggleDropdown(this)">
                        <i class="fas fa-book me-3"></i>
                        <span>Akademik</span>
                    </a>
                    <ul class="dropdown-menu {{ $isAkademikActive ? 'show' : '' }}">
                        <li>
                            <a href="{{ route('admin.prestasi.index') }}" class="dropdown-item {{ Request::routeIs('admin.prestasi.*') ? 'active' : '' }}">
                                <i class="fas fa-trophy me-2"></i>
                                <span>Kelola Prestasi</span>
                            </a>
                        </li>
                        <li>
                            <a href="{{ route('admin.mahasiswa-bermasalah.index') }}" class="dropdown-item {{ Request::routeIs('admin.mahasiswa-bermasalah.*') ? 'active' : '' }}">
                                <i class="fas fa-exclamation-triangle me-2"></i>
                                <span>Mahasiswa Bermasalah</span>
                            </a>
                        </li>
                    </ul>
                </li>
                @endif

                {{-- DISIPLIN DROPDOWN (Super Admin Only) --}}
                @if(auth()->check() && auth()->user()->role === 'super_admin')
                @php
                    $isDisiplinActive = Request::routeIs('admin.pelanggaran.*') || 
                                        Request::routeIs('admin.sanksi.*');
                @endphp
                <li class="nav-item dropdown {{ $isDisiplinActive ? 'active show' : '' }}">
                    <a href="#" class="nav-link dropdown-toggle {{ $isDisiplinActive ? 'active' : '' }}" 
                       onclick="toggleDropdown(this)">
                        <i class="fas fa-gavel me-3"></i>
                        <span>Disiplin</span>
                    </a>
                    <ul class="dropdown-menu {{ $isDisiplinActive ? 'show' : '' }}">
                        <li>
                            <a href="{{ route('admin.pelanggaran.index') }}" class="dropdown-item {{ Request::routeIs('admin.pelanggaran.*') ? 'active' : '' }}">
                                <i class="fas fa-exclamation-circle me-2"></i>
                                <span>Data Pelanggaran</span>
                            </a>
                        </li>
                        <li>
                            <a href="{{ route('admin.sanksi.index') }}" class="dropdown-item {{ Request::routeIs('admin.sanksi.*') ? 'active' : '' }}">
                                <i class="fas fa-balance-scale me-2"></i>
                                <span>Data Sanksi</span>
                            </a>
                        </li>
                    </ul>
                </li>
                @endif

                {{-- KONTEN DROPDOWN --}}
                @php
                    $isKontenActive = Request::routeIs('admin.berita.*') || 
                                      Request::routeIs('admin.komentar.*');
                @endphp
                <li class="nav-item dropdown {{ $isKontenActive ? 'active show' : '' }}">
                    <a href="#" class="nav-link dropdown-toggle {{ $isKontenActive ? 'active' : '' }}" 
                       onclick="toggleDropdown(this)">
                        <i class="fas fa-newspaper me-3"></i>
                        <span>Konten</span>
                    </a>
                    <ul class="dropdown-menu {{ $isKontenActive ? 'show' : '' }}">
                        <li>
                            <a href="{{ route('admin.berita.index') }}" class="dropdown-item {{ Request::routeIs('admin.berita.*') ? 'active' : '' }}">
                                <i class="fas fa-newspaper me-2"></i>
                                <span>Berita</span>
                            </a>
                        </li>
                        <li>
                            <a href="{{ route('admin.komentar.index') }}" class="dropdown-item {{ Request::routeIs('admin.komentar.*') ? 'active' : '' }}">
                                <i class="fas fa-comments me-2"></i>
                                <span>Kelola Komentar</span>
                            </a>
                        </li>
                    </ul>
                </li>
                
                {{-- PENDAFTARAN --}}
                <li class="nav-item">
                    <a href="{{ route('admin.pendaftaran.index') }}" class="nav-link {{ Request::routeIs('admin.pendaftaran.*') ? 'active' : '' }}">
                        <i class="fas fa-user-check me-3"></i>
                        <span>Pendaftaran</span>
                    </a>
                </li>
                
                <hr class="nav-divider">
                
                <li class="nav-item">
                    <a href="{{ url('/') }}" class="nav-link">
                        <i class="fas fa-house me-3"></i>
                        <span>Pergi ke Beranda</span>
                    </a>
                </li>
                
                <li class="nav-item">
                    <a href="#" class="nav-link">
                        <i class="fas fa-cog me-3"></i>
                        <span>Pengaturan</span>
                    </a>
                </li>
                
                <li class="nav-item">
                    <a href="{{ route('logout') }}" 
                       class="nav-link text-danger"
                       onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                        <i class="fas fa-sign-out-alt me-3"></i>
                        <span>Logout</span>
                    </a>
                    <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
                        @csrf
                    </form>
                </li>
            </ul>
        </nav>

        <!-- Content -->
        <div id="content">
            <!-- Top Navigation -->
            <nav class="navbar navbar-expand-lg navbar-light bg-white rounded shadow-sm">
                <div class="container-fluid">
                    <div class="navbar-brand ms-2">
                        <h5 class="mb-0">@yield('page-title', 'Dashboard Admin')</h5>
                    </div>
                    
                    <div class="navbar-nav ms-auto">
                        <li class="nav-item dropdown">
                            <a class="nav-link dropdown-toggle" href="#" id="navbarDropdown" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                                <i class="fas fa-user-circle me-2"></i>
                                {{ auth()->user()->name ?? 'Admin User' }}
                            </a>
                            <ul class="dropdown-menu" aria-labelledby="navbarDropdown">
                                <li><a class="dropdown-item" href="#"><i class="fas fa-user me-2"></i>Profile</a></li>
                                <li><a class="dropdown-item" href="#"><i class="fas fa-cog me-2"></i>Settings</a></li>
                                <li><hr class="dropdown-divider"></li>
                                <li>
                                    <a class="dropdown-item text-danger" href="{{ route('logout') }}" onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                                        <i class="fas fa-sign-out-alt me-2"></i>Logout
                                    </a>
                                </li>
                            </ul>
                        </li>
                    </div>
                </div>
            </nav>

            <!-- Main Content -->
            <main>
                <!-- Notifications -->
                @if(session('success'))
                    <div class="alert alert-success alert-dismissible fade show" role="alert">
                        <i class="fas fa-check-circle me-2"></i>
                        {{ session('success') }}
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>
                @endif

                @if(session('error'))
                    <div class="alert alert-danger alert-dismissible fade show" role="alert">
                        <i class="fas fa-exclamation-circle me-2"></i>
                        {{ session('error') }}
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>
                @endif

                @if($errors->any())
                    <div class="alert alert-danger alert-dismissible fade show" role="alert">
                        <i class="fas fa-exclamation-triangle me-2"></i>
                        <ul class="mb-0">
                            @foreach($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>
                @endif

                <!-- Page Content -->
                @yield('content')
            </main>
        </div>
    </div>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <!-- jQuery -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    
    <script>
        // Custom dropdown toggle untuk sidebar dengan animasi
        function toggleDropdown(element) {
            event.preventDefault();
            event.stopPropagation();
            
            const dropdownItem = element.closest('.dropdown');
            const dropdownMenu = dropdownItem.querySelector('.dropdown-menu');
            const isCurrentlyOpen = dropdownItem.classList.contains('show');
            const isClosing = dropdownItem.classList.contains('closing');
            
            // Jika sedang dalam proses closing, jangan lakukan apa-apa
            if (isClosing) return;
            
            // Tutup semua dropdown terlebih dahulu dengan animasi
            closeAllDropdownsWithAnimation(() => {
                // Jika dropdown sedang tidak terbuka, buka dropdown ini dengan animasi
                if (!isCurrentlyOpen) {
                    openDropdownWithAnimation(dropdownItem);
                }
            });
        }
        
        function openDropdownWithAnimation(dropdownItem) {
            const dropdownMenu = dropdownItem.querySelector('.dropdown-menu');
            const toggle = dropdownItem.querySelector('.dropdown-toggle');
            
            // Reset state sebelum animasi
            dropdownItem.classList.remove('closing');
            
            // Tambahkan class show untuk memulai animasi
            dropdownItem.classList.add('show');
            dropdownMenu.classList.add('show');
            
            if (toggle) {
                toggle.setAttribute('aria-expanded', 'true');
            }
            
            // Trigger reflow untuk memastikan animasi berjalan
            void dropdownMenu.offsetWidth;
        }
        
        function closeDropdownWithAnimation(dropdownItem, callback) {
            const dropdownMenu = dropdownItem.querySelector('.dropdown-menu');
            const toggle = dropdownItem.querySelector('.dropdown-toggle');
            
            if (!dropdownItem.classList.contains('show')) {
                if (callback) callback();
                return;
            }
            
            // Tambahkan class closing untuk animasi keluar
            dropdownItem.classList.add('closing');
            
            if (toggle) {
                toggle.setAttribute('aria-expanded', 'false');
            }
            
            // Tunggu animasi selesai sebelum menghapus class show
            setTimeout(() => {
                dropdownItem.classList.remove('show', 'closing');
                dropdownMenu.classList.remove('show');
                if (callback) callback();
            }, 300); // Sesuaikan dengan durasi animasi (var(--dropdown-animation-speed))
        }
        
        function closeAllDropdownsWithAnimation(callback) {
            const allDropdowns = document.querySelectorAll('.sidebar-nav .dropdown.show');
            let closedCount = 0;
            
            if (allDropdowns.length === 0) {
                if (callback) callback();
                return;
            }
            
            allDropdowns.forEach(dropdown => {
                closeDropdownWithAnimation(dropdown, () => {
                    closedCount++;
                    if (closedCount === allDropdowns.length && callback) {
                        callback();
                    }
                });
            });
        }
        
        $(document).ready(function () {
            // Auto-hide alerts after 5 seconds
            setTimeout(function() {
                $('.alert').fadeOut(300);
            }, 5000);
            
            // Pastikan dropdown dengan submenu aktif tetap terbuka saat halaman dimuat
            $('.dropdown-item.active').each(function() {
                const parentDropdown = $(this).closest('.dropdown');
                parentDropdown.addClass('show');
                parentDropdown.find('.dropdown-menu').addClass('show');
                parentDropdown.find('.dropdown-toggle').attr('aria-expanded', 'true');
                
                // Trigger animasi untuk item dropdown
                setTimeout(() => {
                    parentDropdown.find('.dropdown-item').css({
                        opacity: '1',
                        transform: 'translateX(0)'
                    });
                }, 50);
            });
            
            // Tutup dropdown ketika klik di luar sidebar
            $(document).on('click', function(event) {
                if (!$(event.target).closest('#sidebar').length) {
                    closeAllDropdownsWithAnimation();
                }
            });
            
            // Mencegah event bubbling untuk dropdown items
            $('.sidebar-nav .dropdown-item').on('click', function(event) {
                event.stopPropagation();
            });
            
            // Handle ESC key untuk menutup dropdown
            $(document).on('keydown', function(event) {
                if (event.key === 'Escape') {
                    closeAllDropdownsWithAnimation();
                }
            });
        });

        // CSRF Token setup for AJAX
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });

        // Global alert function
        function showAlert(type, message) {
            const alertClass = type === 'success' ? 'alert-success' : 'alert-danger';
            const alertHtml = `
                <div class="alert ${alertClass} alert-dismissible fade show" role="alert">
                    <i class="fas ${type === 'success' ? 'fa-check-circle' : 'fa-exclamation-circle'} me-2"></i>
                    ${message}
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            `;
            $('main').prepend(alertHtml);
            
            setTimeout(() => {
                $('.alert').alert('close');
            }, 5000);
        }
    </script>
    z
    @stack('scripts')
</body>
</html>