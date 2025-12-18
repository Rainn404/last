<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - SIMAWA</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        html, body {
            width: 100%;
            height: 100%;
            font-family: 'Poppins', sans-serif;
            background: url('{{ asset("logo_bg/Gedung 2 Politala-thumbnail.jpg") }}') fixed center/cover no-repeat;
            min-height: 100vh;
        }

        html {
            background-attachment: fixed;
        }

        body::before {
            content: '';
            position: fixed;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: linear-gradient(rgba(15, 23, 42, 0.55), rgba(49, 46, 129, 0.25));
            z-index: 0;
            pointer-events: none;
        }

        .login-container {
            display: flex;
            align-items: center;
            justify-content: center;
            min-height: 100vh;
            width: 100%;
            padding: 20px;
            position: relative;
            z-index: 1;
        }

        .login-wrapper {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 30px;
            max-width: 1200px;
            width: 100%;
            align-items: center;
        }

        /* ===== FORM CARD ===== */
        .form-card {
            background: rgba(255, 255, 255, 0.12);
            backdrop-filter: blur(15px);
            border: 1px solid rgba(224, 231, 255, 0.25);
            border-radius: 24px;
            padding: 50px 40px;
            box-shadow: 0 8px 32px rgba(2, 6, 23, 0.2);
            animation: slideInLeft 0.6s ease-out;
        }

        @keyframes slideInLeft {
            from {
                opacity: 0;
                transform: translateX(-30px);
            }
            to {
                opacity: 1;
                transform: translateX(0);
            }
        }

        /* ===== HEADER ===== */
        .logo {
            display: flex;
            align-items: center;
            gap: 12px;
            margin-bottom: 40px;
        }

        .logo-icon {
            width: 180px;
            height: 60px;
            border-radius: 12px;
            display: flex;
            align-items: center;
            justify-content: center;
            overflow: hidden;
            box-shadow: 0 4px 12px rgba(99, 102, 241, 0.3);
            background: transparent;
        }

        .logo-icon img {
            width: 100%;
            height: 100%;
            object-fit: contain;
        }

        .logo-text {
            font-size: 28px;
            font-weight: 800;
            color: #F8FAFC;
            text-shadow: 0 2px 4px rgba(2, 6, 23, 0.2);
        }

        .form-card h2 {
            color: #F8FAFC;
            font-size: 28px;
            font-weight: 800;
            margin-bottom: 8px;
            text-shadow: 0 2px 4px rgba(2, 6, 23, 0.1);
        }

        .form-card > p {
            color: #CBD5E1;
            font-size: 14px;
            margin-bottom: 30px;
            line-height: 1.6;
        }

        /* ===== FORM ELEMENTS ===== */
        .form-group {
            margin-bottom: 20px;
        }

        .form-group label {
            display: block;
            color: #FFFFFF;
            font-weight: 700;
            font-size: 13px;
            margin-bottom: 8px;
            letter-spacing: 0.5px;
            text-transform: uppercase;
        }

        .form-group input {
            width: 100%;
            padding: 14px 16px;
            background: rgba(15, 23, 42, 0.6);
            border: 1px solid rgba(224, 231, 255, 0.20);
            border-radius: 12px;
            color: #F8FAFC;
            font-size: 14px;
            font-family: 'Poppins', sans-serif;
            backdrop-filter: blur(6px);
            transition: all 0.3s ease;
        }

        .form-group input::placeholder {
            color: #64748B;
        }

        .form-group input:focus {
            outline: none;
            background: rgba(15, 23, 42, 0.8);
            border-color: #6366F1;
            box-shadow: 0 0 0 3px rgba(99, 102, 241, 0.15);
        }

        /* ===== FORM OPTIONS ===== */
        .form-options {
            display: flex;
            align-items: center;
            justify-content: space-between;
            margin-bottom: 24px;
            font-size: 14px;
        }

        .checkbox-label {
            display: flex;
            align-items: center;
            gap: 6px;
            cursor: pointer;
            color: #CBD5E1;
            font-weight: 500;
        }

        .checkbox-label input {
            width: auto;
            cursor: pointer;
        }

        .forgot-password {
            color: #A5B4FC;
            text-decoration: none;
            font-weight: 600;
            transition: color 0.3s ease;
        }

        .forgot-password:hover {
            color: #6366F1;
        }

        /* ===== BUTTONS ===== */
        .btn {
            width: 100%;
            padding: 14px 24px;
            border: none;
            border-radius: 12px;
            font-size: 15px;
            font-weight: 700;
            cursor: pointer;
            transition: all 0.3s ease;
            font-family: 'Poppins', sans-serif;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 10px;
        }

        .btn-primary {
            background: linear-gradient(135deg, #6366F1, #4F46E5);
            color: #FFFFFF;
            box-shadow: 0 4px 12px rgba(99, 102, 241, 0.3);
            margin-bottom: 16px;
        }

        .btn-primary:hover {
            background: linear-gradient(135deg, #4F46E5, #4338CA);
            box-shadow: 0 6px 20px rgba(99, 102, 241, 0.4);
            transform: translateY(-2px);
        }

        .btn-google {
            background: rgba(255, 255, 255, 0.12);
            border: 1px solid rgba(224, 231, 255, 0.30);
            color: #F8FAFC;
            backdrop-filter: blur(8px);
            margin-bottom: 16px;
        }

        .btn-google:hover {
            background: rgba(255, 255, 255, 0.18);
            border-color: rgba(224, 231, 255, 0.40);
        }

        .btn-google img {
            width: 18px;
            height: 18px;
        }

        /* ===== DIVIDER ===== */
        .divider {
            display: flex;
            align-items: center;
            gap: 12px;
            margin: 24px 0;
            color: #64748B;
            font-size: 12px;
            font-weight: 600;
        }

        .divider::before,
        .divider::after {
            content: '';
            flex: 1;
            height: 1px;
            background: rgba(224, 231, 255, 0.20);
        }

        /* ===== FOOTER ===== */
        .form-footer {
            text-align: center;
            font-size: 14px;
            color: #CBD5E1;
        }

        .form-footer a {
            color: #A5B4FC;
            font-weight: 700;
            text-decoration: none;
            transition: color 0.3s ease;
        }

        .form-footer a:hover {
            color: #6366F1;
        }

        /* ===== ALERTS ===== */
        .alert {
            padding: 16px 20px;
            border-radius: 12px;
            margin-bottom: 20px;
            display: flex;
            align-items: flex-start;
            gap: 12px;
            font-size: 14px;
            animation: slideDown 0.3s ease-out;
        }

        @keyframes slideDown {
            from {
                opacity: 0;
                transform: translateY(-10px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        .alert-danger {
            background: rgba(239, 68, 68, 0.15);
            border: 1px solid rgba(239, 68, 68, 0.3);
            color: #FCA5A5;
        }

        .alert-success {
            background: rgba(34, 197, 94, 0.15);
            border: 1px solid rgba(34, 197, 94, 0.3);
            color: #86EFAC;
        }

        .alert i {
            flex-shrink: 0;
            font-size: 16px;
            margin-top: 2px;
        }

        /* ===== INFO CARDS ===== */
        .info-section {
            animation: slideInRight 0.6s ease-out;
        }

        @keyframes slideInRight {
            from {
                opacity: 0;
                transform: translateX(30px);
            }
            to {
                opacity: 1;
                transform: translateX(0);
            }
        }

        .info-card {
            background: rgba(255, 255, 255, 0.12);
            backdrop-filter: blur(12px);
            border: 1px solid rgba(224, 231, 255, 0.25);
            border-radius: 16px;
            padding: 28px;
            margin-bottom: 20px;
            transition: all 0.3s ease;
        }

        .info-card:hover {
            background: rgba(255, 255, 255, 0.18);
            border-color: rgba(224, 231, 255, 0.35);
            transform: translateY(-4px);
        }

        .info-card-header {
            display: flex;
            align-items: center;
            gap: 12px;
            margin-bottom: 16px;
        }

        .info-icon {
            width: 40px;
            height: 40px;
            background: linear-gradient(135deg, rgba(99, 102, 241, 0.3), rgba(79, 70, 229, 0.2));
            border-radius: 10px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 18px;
            color: #A5B4FC;
        }

        .info-card h4 {
            color: #F8FAFC;
            font-size: 16px;
            font-weight: 700;
            margin: 0;
        }

        .info-card p {
            color: #CBD5E1;
            font-size: 13px;
            line-height: 1.6;
            margin: 12px 0 0 0;
        }

        .features-list {
            background: rgba(15, 23, 42, 0.3);
            border: 1px solid rgba(224, 231, 255, 0.15);
            border-radius: 12px;
            padding: 20px;
            margin-top: 20px;
        }

        .features-list h5 {
            color: #F8FAFC;
            font-size: 14px;
            font-weight: 700;
            margin-bottom: 16px;
            letter-spacing: 0.5px;
        }

        .features-list ul {
            list-style: none;
        }

        .features-list li {
            display: flex;
            align-items: center;
            gap: 10px;
            color: #CBD5E1;
            font-size: 13px;
            margin-bottom: 10px;
            font-weight: 500;
        }

        .features-list li:last-child {
            margin-bottom: 0;
        }

        .features-list i {
            color: #22C55E;
            font-size: 14px;
        }

        /* ===== RESPONSIVE ===== */
        @media (max-width: 1024px) {
            .form-card {
                padding: 40px 30px;
            }

            .info-card {
                padding: 24px;
            }
        }

        @media (max-width: 768px) {
            .login-wrapper {
                grid-template-columns: 1fr;
            }

            .form-card {
                padding: 30px 24px;
            }

            .form-card h2 {
                font-size: 24px;
            }

            .info-section {
                order: -1;
            }

            .info-card {
                padding: 20px;
                margin-bottom: 16px;
            }

            .features-list {
                padding: 16px;
            }

            .logo-icon {
                width: 50px;
                height: 50px;
            }

            .logo-text {
                font-size: 24px;
            }
        }

        @media (max-width: 480px) {
            .login-container {
                padding: 16px;
            }

            .form-card {
                padding: 24px 16px;
            }

            .logo-text {
                font-size: 22px;
            }

            .form-card h2 {
                font-size: 20px;
            }

            .form-card > p {
                font-size: 13px;
            }

            .logo-icon {
                width: 45px;
                height: 45px;
            }
        }
    </style>
</head>
<body>
    <div class="login-container">
        <div class="login-wrapper">
            <!-- ===== LOGIN FORM ===== -->
            <div class="form-card">
                <div class="logo">
                    <div class="logo-icon">
                        <img src="{{ asset('logo_bg/logo_putih.png') }}" alt="Logo HIMA-TI">
                    </div>
        
                </div>

                <h2>Masuk ke Akun Anda</h2>
                <p>Akses dashboard dan fitur eksklusif dengan sistem autentikasi tunggal</p>

                <!-- Alerts -->
                @if($errors->any())
                    <div class="alert alert-danger">
                        <i class="fas fa-exclamation-circle"></i>
                        <div>{{ $errors->first() }}</div>
                    </div>
                @endif

                @if(session('success'))
                    <div class="alert alert-success">
                        <i class="fas fa-check-circle"></i>
                        <div>{{ session('success') }}</div>
                    </div>
                @endif

                <!-- Google Login -->
                <a href="{{ route('google.login') }}" class="btn btn-google">
                    <img src="{{ asset('logo_bg/google.png') }}" alt="Google">
                    <span>Masuk dengan Google</span>
                </a>

                <div class="divider">atau gunakan email</div>

                <!-- Email & Password Form -->
                <form action="{{ route('login.post') }}" method="POST">
                    @csrf

                    <div class="form-group">
                        <label for="email">Email Address</label>
                        <input type="email" id="email" name="email" placeholder="masukkan email anda" value="{{ old('email') }}" required>
                    </div>

                    <div class="form-group">
                        <label for="password">Password</label>
                        <input type="password" id="password" name="password" placeholder="masukkan password" required>
                    </div>

                    <div class="form-options">
                        <label class="checkbox-label">
                            <input type="checkbox" name="remember" {{ old('remember') ? 'checked' : '' }}>
                            <span>Ingat saya</span>
                        </label>
                        <a href="#" class="forgot-password">Lupa password?</a>
                    </div>

                    <button type="submit" class="btn btn-primary">
                        <i class="fas fa-sign-in-alt"></i>
                        <span>Masuk</span>
                    </button>
                </form>

                <p class="form-footer">
                    Belum punya akun? <a href="/register">Daftar di sini</a>
                </p>
            </div>

            <!-- ===== INFO SECTION ===== -->
            <div class="info-section">
                <div class="info-card">
                    <div class="info-card-header">
                        <div class="info-icon">
                            <i class="fas fa-users"></i>
                        </div>
                        <h4>Anggota HIMA-TI</h4>
                    </div>
                    <p>Login untuk mengakses dashboard anggota, mengajukan prestasi, dan berpartisipasi dalam kegiatan organisasi.</p>
                </div>

                <div class="info-card">
                    <div class="info-card-header">
                        <div class="info-icon">
                            <i class="fas fa-cogs"></i>
                        </div>
                        <h4>Admin & Pengurus</h4>
                    </div>
                    <p>Akses panel admin untuk mengelola data anggota, berita, prestasi, dan kegiatan organisasi.</p>
                </div>

                <div class="features-list">
                    <h5>⭐ Fitur yang Dapat Diakses:</h5>
                    <ul>
                        <li><i class="fas fa-check-circle"></i> Kelola profil anggota</li>
                        <li><i class="fas fa-check-circle"></i> Ajukan dan kelola prestasi</li>
                        <li><i class="fas fa-check-circle"></i> Akses materi eksklusif</li>
                        <li><i class="fas fa-check-circle"></i> Partisipasi dalam kegiatan</li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
</body>
</html>