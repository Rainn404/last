<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'HIMA-TI - Himpunan Mahasiswa Teknik Informatika')</title>

    <!-- FontAwesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

    <!-- Custom CSS -->
    <link rel="stylesheet" href="{{ asset('css/style.css') }}">

    <style>
        /* === Fixed Background & Overlay === */
        html, body {
            margin: 0;
            padding: 0;
            font-family: 'Poppins', sans-serif;
        }

        body {
            background: url('{{ asset("logo_bg/Gedung 2 Politala-thumbnail.jpg") }}') fixed center/cover no-repeat;
            position: relative;
            padding-top: 80px;
        }

        body::before {
            content: '';
            position: fixed;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: rgba(15, 23, 42, 0.55);
            z-index: -1;
        }

        body::after {
            content: '';
            position: fixed;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: rgba(49, 46, 129, 0.25);
            z-index: -1;
            pointer-events: none;
        }

        /* === Navbar Fix === */
        .navbar {
            position: fixed;
            top: 0;
            left: 0;
            right: 0;
            z-index: 1000;
            background: rgba(255, 255, 255, 0.13);
            backdrop-filter: blur(12px);
            -webkit-backdrop-filter: blur(12px);
            border-bottom: 1px solid rgba(224, 231, 255, 0.3);
            box-shadow: 0 8px 32px 0 rgba(2, 6, 23, 0.2);
        }

        .user-menu {
            background: rgba(255, 255, 255, 0.18);
            backdrop-filter: blur(12px);
            -webkit-backdrop-filter: blur(12px);
            border-radius: 12px;
            padding: 8px 0;
            min-width: 160px;
            border: 1px solid rgba(224, 231, 255, 0.3);
            z-index: 9999 !important;
            box-shadow: 0 8px 32px 0 rgba(2, 6, 23, 0.3);
        }

.user-menu li {
    list-style: none;
}

.user-menu .dropdown-item {
    display: block;
    padding: 10px 16px;
    color: #F8FAFC;
    text-decoration: none;
    font-weight: 500;
    transition: all 0.2s ease;
}

.user-menu .dropdown-item:hover {
    background: rgba(99, 102, 241, 0.2);
    color: #E0E7FF;
}

        .nav-container {
            max-width: 1200px;
            margin: 0 auto;
            padding: 12px 20px;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .nav-logo a {
            text-decoration: none;
            color: transparent;
            font-weight: 700;
            font-size: 1.3rem;
            display: flex;
            align-items: center;
            gap: 8px;
        }

        .nav-logo img {
            height: 40px;
            width: auto;
        }

        .nav-logo i {
            display: none;
        }

        .nav-menu {
            display: flex;
            gap: 24px;
            align-items: center;
        }

        .nav-link {
            text-decoration: none;
            color: #CBD5E1;
            font-weight: 500;
            transition: all 0.3s ease;
            padding: 8px 16px;
            border-radius: 8px;
            position: relative;
            background: transparent;
        }

        .nav-link:hover {
            color: #E0E7FF;
            background: rgba(99, 102, 241, 0.15);
        }

        /* Active indicator */
        .nav-link.active {
            color: #E0E7FF;
            font-weight: 600;
            background: rgba(99, 102, 241, 0.15);
            border: 1px solid #6366F1;
        }

        .nav-link.active::after {
            content: '';
            position: absolute;
            bottom: 0;
            left: 16px;
            right: 16px;
            height: 2px;
            background: #4F46E5;
            border-radius: 1px;
        }

        .login-btn {
            background: #6366F1;
            color: #FFFFFF !important;
            padding: 10px 20px;
            border-radius: 8px;
            font-weight: 600;
            transition: all 0.3s ease;
            border: none;
            box-shadow: 0 4px 15px rgba(99, 102, 241, 0.3);
        }

        .login-btn:hover {
            background: #4F46E5;
            box-shadow: 0 8px 25px rgba(99, 102, 241, 0.4);
            transform: translateY(-2px);
        }

        /* === Responsive Navbar === */
        .nav-toggle {
            display: none;
            flex-direction: column;
            cursor: pointer;
        }

        .nav-toggle .bar {
            width: 25px;
            height: 3px;
            background: #F8FAFC;
            margin: 4px 0;
            border-radius: 2px;
        }

        @media (max-width: 768px) {
            .nav-menu {
                position: absolute;
                top: 70px;
                right: 0;
                background: rgba(255, 255, 255, 0.15);
                flex-direction: column;
                width: 200px;
                padding: 16px;
                box-shadow: 0 4px 12px rgba(2, 6, 23, 0.3);
                display: none;
                border: 1px solid rgba(224, 231, 255, 0.3);
                border-radius: 8px;
                backdrop-filter: blur(12px);
            }

            .nav-menu.show {
                display: flex;
            }

            .nav-toggle {
                display: flex;
            }
        }

        /* === Footer === */
        .footer {
            background: rgba(255, 255, 255, 0.08);
            color: #CBD5E1;
            margin-top: 60px;
            padding-top: 40px;
            border-top: 1px solid rgba(224, 231, 255, 0.2);
            backdrop-filter: blur(12px);
        }

        .footer-container {
            max-width: 1200px;
            margin: 0 auto;
            display: flex;
            flex-wrap: wrap;
            justify-content: space-between;
            gap: 20px;
            padding: 0 20px;
        }

        .footer-section {
            flex: 1 1 300px;
        }

        .footer-section h3 {
            margin-bottom: 15px;
            font-weight: 700;
            color: #F8FAFC;
        }

        .footer-section p, .footer-section ul {
            font-size: 0.95rem;
            line-height: 1.6;
            color: #CBD5E1;
        }

        .footer-section ul {
            list-style: none;
            padding: 0;
        }

        .footer-section ul li a {
            color: #6366F1;
            text-decoration: none;
            transition: color 0.2s ease;
        }

        .footer-section ul li a:hover {
            color: #818CF8;
            text-decoration: underline;
        }

        .social-links a {
            color: #6366F1;
            margin-right: 10px;
            font-size: 1.3rem;
            transition: color 0.2s ease;
        }

        .social-links a:hover {
            color: #818CF8;
        }

        .footer-bottom {
            text-align: center;
            padding: 16px;
            background: rgba(2, 6, 23, 0.5);
            color: #94A3B8;
            font-size: 0.9rem;
            margin-top: 20px;
            border-top: 1px solid rgba(224, 231, 255, 0.2);
        }
    </style>

    <script src="https://cdn.tailwindcss.com"></script>

</head>

<body>
<nav class="navbar">
    <div class="nav-container">
        <div class="nav-logo">
            <a href="{{ url('/') }}">
                <img src="{{ asset('logo_bg/logo_putih.png') }}" alt="HIMA-TI Logo">
            </a>
        </div>

        <div class="nav-menu" id="navMenu">
            <a href="{{ url('/home') }}" class="nav-link {{ Request::is('/') || Request::is('home') ? 'active' : '' }}">Home</a>
            <a href="{{ url('/divisi') }}" class="nav-link {{ Request::is('divisi') || Request::is('divisi/*') ? 'active' : '' }}">Divisi</a>
            <a href="{{ url('/anggota') }}" class="nav-link {{ Request::is('anggota') || Request::is('anggota/*') ? 'active' : '' }}">Profil Anggota</a>
            <a href="{{ url('/berita') }}" class="nav-link {{ Request::is('berita') || Request::is('berita/*') ? 'active' : '' }}">Berita</a>
            <a href="{{ url('/pendaftaran') }}" class="nav-link {{ Request::is('pendaftaran') || Request::is('pendaftaran/*') ? 'active' : '' }}">Pendaftaran</a>
            <a href="{{ url('/prestasi') }}" class="nav-link {{ Request::is('prestasi') || Request::is('prestasi/*') ? 'active' : '' }}">Prestasi</a>

            @guest
                <!-- Jika belum login -->
                <a href="{{ route('login') }}" class="nav-link login-btn">Masuk/Login</a>
            @endguest

@auth
    {{-- Jika ADMIN → pakai dropdown --}}
    @if(Auth::user()->role === 'admin')

        <div class="relative">

            <!-- Tombol menu admin -->
            <button id="userMenuToggle"
                class="p-2 rounded-md border bg-white shadow-sm hover:bg-gray-100"
                style="font-size: 22px;">
                <i class="fa-solid fa-bars"></i>
            </button>

            <!-- Dropdown admin -->
            <ul id="userMenuPanel"
                class="user-menu shadow"
                style="
                    display:none;
                    position:fixed;
                    right:20px;
                    top:70px;
                    z-index:99999;
                ">

                <li>
                    <a href="{{ route('admin.dashboard') }}" class="dropdown-item">
                        <i class="fa-solid fa-gauge"></i>
                        Dashboard Admin
                    </a>
                </li>

                <li><hr class="dropdown-divider my-1"></li>

                <li>
                    <form action="{{ route('logout') }}" method="POST" class="m-0">
                        @csrf
                        <button class="dropdown-item text-danger">
                            Logout
                            <i class="fa-solid fa-right-from-bracket"></i>
                        </button>
                    </form>
                </li>
            </ul>
        </div>

        {{-- Script toggle --}}
        <script>
            document.addEventListener("DOMContentLoaded", function () {
                const toggle = document.getElementById("userMenuToggle");
                const panel = document.getElementById("userMenuPanel");

                toggle.addEventListener("click", () => {
                    panel.style.display = panel.style.display === "block" ? "none" : "block";
                });

                document.addEventListener("click", function (e) {
                    if (!toggle.contains(e.target) && !panel.contains(e.target)) {
                        panel.style.display = "none";
                    }
                });
            });
        </script>

    {{-- Jika USER BIASA → tampilkan profile icon --}}
    @else

        <div class="relative">
            <!-- Profile Icon untuk user biasa -->
            <a href="{{ route('profile.show') }}"
               class="flex items-center justify-center w-10 h-10 rounded-full bg-gray-200 hover:bg-gray-300 transition-colors"
               title="Profil Saya">
                @if(Auth::user()->avatar)
                    <img src="{{ asset('storage/avatars/' . Auth::user()->avatar) }}"
                         alt="Profile"
                         class="w-10 h-10 rounded-full object-cover">
                @else
                    <i class="fas fa-user text-gray-600"></i>
                @endif
            </a>
        </div>

    @endif
@endauth

        </div>

        <div class="nav-toggle" id="navToggle">
            <span class="bar"></span>
            <span class="bar"></span>
            <span class="bar"></span>
        </div>
    </div>
</nav>

    <!-- Main Content -->
    <main>
        @yield('content')
    </main>

    <!-- Footer -->
    <footer class="footer">
        <div class="footer-container">
            <div class="footer-section">
                <h3>HIMA-TI</h3>
                <p>Himpunan Mahasiswa Teknik Informatika - Wadah bagi mahasiswa untuk mengembangkan potensi dan berkontribusi dalam dunia teknologi informasi.</p>
                <div class="social-links">
                    <a href="#"><i class="fab fa-instagram"></i></a>
                    <a href="#"><i class="fab fa-facebook"></i></a>
                    <a href="#"><i class="fab fa-twitter"></i></a>
                    <a href="#"><i class="fab fa-youtube"></i></a>
                </div>
            </div>
            <div class="footer-section">
                <h3>Menu Cepat</h3>
                <ul>
                    <li><a href="{{ url('/home') }}">Home</a></li>
                    <li><a href="{{ url('/divisi') }}">Divisi</a></li>
                    <li><a href="{{ url('/anggota') }}">Profil Anggota</a></li>
                    <li><a href="{{ url('/mahasiswa') }}">Data Mahasiswa</a></li>
                    <li><a href="{{ url('/berita') }}">Berita</a></li>
                </ul>
            </div>
            <div class="footer-section">
                <h3>Kontak</h3>
                <p><i class="fas fa-map-marker-alt"></i> Gedung Teknik Informatika, Kampus Universitas</p>
                <p><i class="fas fa-envelope"></i> himati@universitas.ac.id</p>
                <p><i class="fas fa-phone"></i> +62 812 3456 7890</p>
            </div>
        </div>
        <div class="footer-bottom">
            <p>&copy; {{ date('Y') }} HIMA-TI. All rights reserved.</p>
        </div>
    </footer>

    <script>
        const toggle = document.getElementById('navToggle');
        const menu = document.getElementById('navMenu');
        toggle.addEventListener('click', () => menu.classList.toggle('show'));
    </script>
</body>
</html>


