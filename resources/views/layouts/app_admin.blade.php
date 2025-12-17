<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Dashboard HIMA - Sistem Manajemen')</title>
    
    <!-- Bootstrap 5.3 -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">

    <!-- Custom Layout Fix -->
    <style>
        body {
            display: flex;
            min-height: 100vh;
            background-color: #f8fafc;
            overflow-x: hidden;
        }

        .sidebar {
            width: 250px;
            position: fixed;
            top: 0;
            left: 0;
            height: 100%;
            background-color: #ffffff;
            border-right: 1px solid #e2e8f0;
            box-shadow: 0 0 20px rgba(0,0,0,0.05);
            z-index: 1000;
            transition: all 0.3s ease;
        }

        .main-content {
            margin-left: 250px;
            flex: 1;
            display: flex;
            flex-direction: column;
            min-height: 100vh;
            transition: margin-left 0.3s ease;
        }

        /* Sidebar toggle on small screen */
        @media (max-width: 992px) {
            .sidebar {
                left: -250px;
            }
            .sidebar.active {
                left: 0;
            }
            .main-content {
                margin-left: 0;
            }
        }

        .navbar-toggler {
            border: none;
            background: none;
            font-size: 1.4rem;
        }

        .content {
            flex: 1;
            background: #f9fafb;
        }

        /* Dropdown user */
        .dropdown-menu {
            font-size: 0.95rem;
        }
    </style>

    @stack('styles')
</head>
<body>
    <!-- Sidebar -->
    @include('layouts.sidebar-admin')

    <!-- Main Content -->
    <div class="main-content">
        <!-- Top Navbar -->
        <header class="navbar navbar-expand-lg navbar-light bg-white border-bottom shadow-sm">
            <div class="container-fluid">
                <!-- Sidebar Toggle Button -->
                <button class="navbar-toggler d-lg-none" type="button" id="sidebarToggle">
                    <i class="fas fa-bars"></i>
                </button>

                <div class="d-flex align-items-center ms-auto">
                    <div class="search-container me-3 d-none d-md-block">
                        <div class="input-group">
                            <span class="input-group-text bg-transparent border-end-0">
                                <i class="fas fa-search text-muted"></i>
                            </span>
                            <input type="text" class="form-control border-start-0" placeholder="Cari...">
                        </div>
                    </div>

                    <div class="dropdown d-flex align-items-center">
                        {{-- Clicking avatar/name goes directly to profile.show --}}
                        <a href="{{ route('profile.show') }}" class="d-flex align-items-center text-dark text-decoration-none me-2">
                            @if(Auth::user()->avatar)
                                <img src="{{ asset('storage/avatars/' . Auth::user()->avatar) }}" 
                                     alt="{{ Auth::user()->name }}" class="rounded-circle me-2" width="32" height="32">
                            @else
                                <img src="https://ui-avatars.com/api/?name={{ urlencode(Auth::user()->name) }}&background=3B82F6&color=fff" 
                                     alt="{{ Auth::user()->name }}" class="rounded-circle me-2" width="32" height="32">
                            @endif
                            <span class="d-none d-md-inline">{{ Auth::user()->name }}</span>
                        </a>

                        {{-- Small caret button opens dropdown for other actions --}}
                        <button class="btn btn-link text-dark p-0" type="button" data-bs-toggle="dropdown" aria-expanded="false">
                            <i class="fas fa-caret-down"></i>
                        </button>

                        <ul class="dropdown-menu dropdown-menu-end">
                            <li><a class="dropdown-item" href="{{ route('profile.show') }}"><i class="fas fa-user me-2"></i>Profil</a></li>
                            <li><a class="dropdown-item" href="{{ route('profile.edit') }}"><i class="fas fa-edit me-2"></i>Edit Profil</a></li>
                            <li><a class="dropdown-item" href="{{ route('profile.password') }}"><i class="fas fa-lock me-2"></i>Ubah Password</a></li>
                            <li><hr class="dropdown-divider"></li>
                            <li>
                                <form action="{{ route('logout') }}" method="POST" class="m-0 p-0">
                                    @csrf
                                    <button type="submit" class="dropdown-item text-danger">
                                        <i class="fas fa-sign-out-alt me-2"></i>Logout
                                    </button>
                                </form>
                            </li>
                        </ul>
                    </div>
                </div>
            </div>
        </header>

        <!-- Page Content -->
        <main class="content p-3 p-lg-4">
            @yield('content')
        </main>
    </div>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

    <!-- Sidebar Toggle Script -->
    <script>
        const sidebar = document.querySelector('.sidebar');
        const sidebarToggle = document.getElementById('sidebarToggle');
        if (sidebarToggle) {
            sidebarToggle.addEventListener('click', () => {
                sidebar.classList.toggle('active');
            });
        }
    </script>

    @stack('scripts')
</body>
</html>
