<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - HIMA-TI</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
body {
  background: linear-gradient(135deg, #eef1f8 0%, #dee6ff 100%);
  min-height: 100vh;
  display: flex;
  align-items: center;
  justify-content: center;
  padding: 0;
  margin: 0;
  overflow-y: auto;       /* ✅ aktifkan scroll ke bawah */
}

.login-card {
    text-align: center;
    background: #fff;
    padding: 25px 20px;
    border-radius: 10px;
    box-shadow: 0 3px 10px rgba(0, 0, 0, 0.06);
    width: 100%;
    margin: 30px auto;
    font-family: 'Poppins', sans-serif;
    position: relative;
    z-index: 5;
}

.login-card h5 {
    font-weight: 700;
    margin-bottom: 8px;
}

.login-card p {
    font-size: 14px;
    color: #555;
    margin-bottom: 25px;
    line-height: 1.5;
}

.google-btn {
    display: flex;
    align-items: center;
    justify-content: center;
    width: 100%;
    border: 2px solid #4285F4; /* 🔹 Garis tepi biru */
    border-radius: 8px;
    background-color: #fff; /* Tetap putih */
    color: #3c4043;
    text-decoration: none;
    font-weight: 600;
    font-size: 15px;
    padding: 12px 0;
    margin-bottom: 20px;
    transition: all 0.25s ease;
    box-shadow: 0 3px 6px rgba(66, 133, 244, 0.1); /* 🔹 Bayangan lembut */
    cursor: pointer;
    pointer-events: auto;
    position: relative;
    z-index: 10;
}

.google-btn img {
    width: 22px;
    height: 22px;
    margin-right: 10px;
    pointer-events: auto;
}

.google-btn:hover {
    background-color: #f1f4ff; /* 🔹 Efek hover biru lembut */
    box-shadow: 0 4px 8px rgba(66, 133, 244, 0.2);
    transform: translateY(-1px);
    border-color: #3367D6; /* sedikit lebih gelap saat hover */
}

.google-btn:active {
    transform: translateY(0);
    box-shadow: 0 2px 4px rgba(66, 133, 244, 0.15);
}


.divider {
    display: flex;
    align-items: center;
    justify-content: center;
    margin-top: 25px;
    color: #9aa0a6;
    font-size: 14px;
    pointer-events: none;
}

.divider::before,
.divider::after {
    content: "";
    flex: 1;
    height: 1px;
    background: #e0e0e0;
    margin: 0 12px;
}

        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        }

        :root {
            --primary: #4361ee;
            --secondary: #3a0ca3;
            --accent: #7209b7;
            --light: #f8f9fa;
            --dark: #212529;
            --success: #4cc9f0;
            --gray: #6c757d;
            --light-gray: #e9ecef;
        }

/* ====== Fullscreen, no outer gaps ====== */
html, body {
  width: 100%;
  height: 100%;
  margin: 0;
  padding: 0;
  overflow-x: hidden;
  background: linear-gradient(135deg, #eef1f8 0%, #dee6ff 100%);
}

/* Container memenuhi layar penuh */
.login-container {
  display: flex;
  align-items: stretch;
  justify-content: space-between;
  width: 100vw;
  min-height: 100vh;       /* ❗ ubah dari height ke min-height */
  margin: 0;
  border-radius: 0;
  box-shadow: none;
  overflow-x: hidden;      /* biar bisa scroll ke bawah, tapi nggak ke samping */
  background: #fff;
}


/* Kolom kiri */
.login-form-container {
  flex: 1;
  background: #fff;
  padding: 60px 80px;
  display: flex;
  flex-direction: column;
  justify-content: center;
  border-right: 1px solid #eee;
  min-width: 0;          /* cegah overflow konten panjang */
}

/* Kolom kanan */
.login-info {
  flex: 1;
  background: linear-gradient(135deg, #3a0ca3 0%, #4361ee 100%);
  color: #fff;
  padding: 60px 80px;
  display: flex;
  flex-direction: column;
  justify-content: center;
  min-width: 0;
}

/* Kartu info */
.info-card {
  background: rgba(255, 255, 255, 0.1);
  padding: 25px;
  border-radius: 15px;
  margin-bottom: 25px;
  border: 1px solid rgba(255, 255, 255, 0.2);
  transition: all 0.3s ease;
}
.info-card:hover {
  background: rgba(255, 255, 255, 0.15);
  transform: translateY(-2px);
}

/* ====== Responsif ====== */
@media (max-width: 992px) {
  .login-container {
    flex-direction: column;
    width: 100%;
    height: auto;         /* biar konten panjang bisa scroll */
  }
  .login-form-container {
    border-right: none;
    border-bottom: 1px solid #eee;
    padding: 40px 30px;
  }
  .login-info {
    padding: 40px 30px;
    text-align: center;
    align-items: center;
  }
}

        .logo {
            display: flex;
            align-items: center;
            margin-bottom: 30px;
        }

        .logo-icon {
            width: 50px;
            height: 50px;
            background: var(--primary);
            border-radius: 10px;
            display: flex;
            align-items: center;
            justify-content: center;
            margin-right: 15px;
        }

        .logo-icon i {
            font-size: 24px;
            color: white;
        }

        .logo-text {
            font-size: 24px;
            font-weight: 700;
            color: var(--dark);
        }

        h1 {
            font-size: 32px;
            margin-bottom: 10px;
            color: var(--dark);
        }

        .subtitle {
            color: var(--gray);
            margin-bottom: 30px;
            font-size: 16px;
        }

        .form-group {
            position: relative;
            margin-bottom: 25px;
        }

        label {
            display: block;
            margin-bottom: 8px;
            font-weight: 600;
            color: var(--dark);
        }

        input {
            width: 100%;
            padding: 15px 45px 15px 15px;
            border: 2px solid var(--light-gray);
            border-radius: 10px;
            font-size: 16px;
            transition: all 0.3s;
        }

        input:focus {
            outline: none;
            border-color: var(--primary);
            box-shadow: 0 0 0 3px rgba(67, 97, 238, 0.2);
        }

        .input-icon {
            position: absolute;
            right: 15px;
            top: 50%;
            transform: translateY(-50%);
            color: var(--gray);
        }

        .toggle-password {
            position: absolute;
            right: 15px;
            top: 50%;
            transform: translateY(-50%);
            background: none;
            border: none;
            color: var(--gray);
            cursor: pointer;
        }

        .form-options {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 25px;
        }

        .checkbox-label {
            display: flex;
            align-items: center;
            cursor: pointer;
            font-size: 14px;
        }

        .checkbox-label input {
            width: auto;
            margin-right: 8px;
        }

        .forgot-password {
            color: var(--primary);
            text-decoration: none;
            font-size: 14px;
            font-weight: 600;
        }

        .forgot-password:hover {
            text-decoration: underline;
        }

        .btn {
            display: block;
            width: 100%;
            padding: 15px;
            border: none;
            border-radius: 10px;
            font-size: 16px;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.3s;
            text-align: center;
        }

        .btn-primary {
            background: var(--primary);
            color: white;
        }

        .btn-primary:hover {
            background: var(--secondary);
            transform: translateY(-2px);
            box-shadow: 0 5px 15px rgba(67, 97, 238, 0.3);
        }

        .login-divider {
            display: flex;
            align-items: center;
            margin: 30px 0;
            color: var(--gray);
        }

        .login-divider::before,
        .login-divider::after {
            content: "";
            flex: 1;
            height: 1px;
            background: var(--light-gray);
        }

        .login-divider span {
            padding: 0 15px;
            font-size: 14px;
        }

        .social-login {
            display: flex;
            gap: 15px;
        }

        .btn-google {
            flex: 1;
            background: white;
            color: var(--dark);
            border: 2px solid var(--light-gray);
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 10px;
        }

        .btn-google:hover {
            border-color: #db4437;
            color: #db4437;
            transform: translateY(-2px);
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.1);
        }

        .btn-microsoft {
            flex: 1;
            background: white;
            color: var(--dark);
            border: 2px solid var(--light-gray);
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 10px;
        }

        .btn-microsoft:hover {
            border-color: #0078d4;
            color: #0078d4;
            transform: translateY(-2px);
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.1);
        }

        .info-card {
            background: rgba(255, 255, 255, 0.1);
            padding: 25px;
            border-radius: 15px;
            margin-bottom: 25px;
            backdrop-filter: blur(10px);
            border: 1px solid rgba(255, 255, 255, 0.2);
        }

        .info-icon {
            width: 60px;
            height: 60px;
            background: rgba(255, 255, 255, 0.2);
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            margin-bottom: 15px;
        }

        .info-icon i {
            font-size: 24px;
        }

        .info-card h3 {
            font-size: 20px;
            margin-bottom: 10px;
        }

        .info-card p {
            font-size: 14px;
            opacity: 0.9;
            margin-bottom: 15px;
        }

        .btn-outline {
            background: transparent;
            color: white;
            border: 2px solid white;
            padding: 10px 20px;
            border-radius: 8px;
            text-decoration: none;
            font-size: 14px;
            display: inline-block;
            transition: all 0.3s;
        }

        .btn-outline:hover {
            background: white;
            color: var(--primary);
            transform: translateY(-2px);
        }

        .login-features h4 {
            margin-bottom: 15px;
            font-size: 18px;
        }

        .login-features ul {
            list-style: none;
        }

        .login-features li {
            margin-bottom: 10px;
            display: flex;
            align-items: center;
        }

        .login-features i {
            margin-right: 10px;
            color: var(--success);
        }

        .alert {
            padding: 15px;
            border-radius: 10px;
            margin-bottom: 20px;
            border: 1px solid transparent;
        }

        .alert-danger {
            color: #721c24;
            background-color: #f8d7da;
            border-color: #f5c6cb;
        }

        .alert-success {
            color: #155724;
            background-color: #d4edda;
            border-color: #c3e6cb;
        }

        .demo-accounts {
            background: var(--light);
            padding: 20px;
            border-radius: 10px;
            margin-top: 20px;
        }

        .demo-accounts h4 {
            margin-bottom: 10px;
            color: var(--dark);
        }

        .demo-account {
            display: flex;
            justify-content: space-between;
            margin-bottom: 5px;
            font-size: 14px;
        }

        .demo-account .role {
            font-weight: 600;
            color: var(--primary);
        }

        /* Responsive Design */
        @media (max-width: 992px) {
            .login-container {
                flex-direction: column;
            }
            
            .login-info {
                order: -1;
            }
        }

        @media (max-width: 576px) {
            .login-form-container, .login-info {
                padding: 30px;
            }
            
            .social-login {
                flex-direction: column;
            }
        }

        /* Animation */
        @keyframes fadeIn {
            from { opacity: 0; transform: translateY(20px); }
            to { opacity: 1; transform: translateY(0); }
        }

        .login-form-container, .login-info {
            animation: fadeIn 0.8s ease-out;
        }

        /* Loading state for buttons */
        .btn-loading {
            position: relative;
            color: transparent;
        }

        .btn-loading::after {
            content: "";
            position: absolute;
            width: 20px;
            height: 20px;
            top: 50%;
            left: 50%;
            margin-left: -10px;
            margin-top: -10px;
            border: 2px solid #ffffff;
            border-radius: 50%;
            border-top-color: transparent;
            animation: spin 1s ease-in-out infinite;
        }

        @keyframes spin {
            to { transform: rotate(360deg); }
        }

        /* Form styling untuk login */
        .login-form-container form {
            margin-top: 30px;
            position: relative;
            z-index: 5;
            width: 100%;
        }

        .login-form-container form + p {
            margin-top: 15px;
            z-index: 5;
            position: relative;
        }
    </style>
</head>
<body>
  <div class="login-container">
    <!-- Kolom kiri -->
    <div class="login-form-container">
      <div class="logo">
        <div class="logo-icon"><i class="fas fa-id-card"></i></div>
        <span class="logo-text">HIMA-TI</span>
      </div>

      <section style="background: rgba(255, 255, 255, 0.7); padding: 24px; border-radius: 12px; box-shadow: 0 1px 3px rgba(0,0,0,0.1); margin-bottom: 30px;">
        <h1 style="font-size: 24px; font-weight: 700; color: #111; margin-bottom: 10px;">Masuk ke Akun Anda</h1>
        <p style="color: #666; font-size: 14px;">Akses dashboard dan fitur eksklusif untuk anggota HIMA-TI</p>
      </section>

      <button type="button" id="google-btn" onclick="window.location.href='{{ route('google.login') }}';" onmouseover="this.style.backgroundColor='#f1f4ff'; this.style.boxShadow='0 4px 8px rgba(66, 133, 244, 0.2)'; this.style.borderColor='#3367D6'; this.style.transform='translateY(-1px)';" onmouseout="this.style.backgroundColor='#fff'; this.style.boxShadow='0 3px 6px rgba(66, 133, 244, 0.1)'; this.style.borderColor='#4285F4'; this.style.transform='translateY(0)';" style="display: flex; align-items: center; justify-content: center; width: 100%; border: 2px solid #4285F4; border-radius: 8px; background-color: #fff; color: #3c4043; font-weight: 600; font-size: 15px; padding: 12px 0; margin-bottom: 30px; transition: all 0.25s ease; box-shadow: 0 3px 6px rgba(66, 133, 244, 0.1); cursor: pointer; pointer-events: auto; position: relative; z-index: 20;">
        <img src="https://developers.google.com/identity/images/g-logo.png" alt="Google" style="width: 22px; height: 22px; margin-right: 10px;">
        Login dengan Google
      </button>

      <div style="text-align: center; margin-bottom: 30px;">
        <h5 style="font-weight: 700; margin-bottom: 8px;">Masuk dan Verifikasi</h5>
        <p style="font-size: 14px; color: #555; margin-bottom: 15px;"><b>Baru!</b> Nikmati kemudahan sistem autentikasi tunggal untuk mengakses semua layanan dengan satu akun.</p>
      </div>

      <form action="{{ route('login.post') }}" method="POST">
          @csrf

          <div class="form-group">
              <label for="email">Email</label>
              <input id="email" type="email" name="email" placeholder="Masukkan email" autocomplete="email" required>
          </div>

          <div class="form-group">
              <label for="password">Password</label>
              <input id="password" type="password" name="password" placeholder="Masukkan password" autocomplete="current-password" required>
          </div>

          <div class="form-options">
              <label class="checkbox-label">
                  <input type="checkbox" name="remember" autocomplete="off"> Ingat saya
              </label>
              <!-- Jika belum ada fitur lupa password -->
              <a href="#" class="forgot-password">Lupa password?</a>
          </div>

          <button type="submit" class="btn btn-primary">Masuk</button>
      </form>

      <p style="text-align:center;margin-top:10px;font-size:14px">
          Belum punya akun? <a href="/register" style="color:#4361ee;font-weight:600;">Daftar di sini</a>
      </p>

      <!-- Error Messages -->
      @if($errors->any())
        <div class="alert alert-danger">
          <i class="fas fa-exclamation-triangle me-2"></i>
          {{ $errors->first() }}
        </div>
      @endif

      @if(session('success'))
        <div class="alert alert-success">
          <i class="fas fa-check-circle me-2"></i>
          {{ session('success') }}
        </div>
      @endif
    </div>

    <!-- Kolom kanan -->
    <div class="login-info">
      <div class="info-card">
        <div class="info-icon"><i class="fas fa-users"></i></div>
        <h3>Anggota HIMA-TI</h3>
        <p>Login untuk mengakses dashboard anggota, mengajukan prestasi, dan berpartisipasi dalam kegiatan.</p>
        <a href="#" class="btn-outline">Daftar Menjadi Anggota</a>
      </div>

      <div class="info-card">
        <div class="info-icon"><i class="fas fa-user-cog"></i></div>
        <h3>Admin & Pengurus</h3>
        <p>Akses panel admin untuk mengelola data anggota, berita, dan kegiatan organisasi.</p>
      </div>

      <div class="login-features">
        <h4>Fitur yang Dapat Diakses:</h4>
        <ul>
          <li><i class="fas fa-check"></i> Kelola profil anggota</li>
          <li><i class="fas fa-check"></i> Ajukan prestasi dan kegiatan</li>
          <li><i class="fas fa-check"></i> Akses materi eksklusif</li>
          <li><i class="fas fa-check"></i> Partisipasi dalam forum diskusi</li>
        </ul>
      </div>
    </div>
  </div>

  <script>
    // Debug: Ensure Google button is clickable
    document.addEventListener('DOMContentLoaded', function() {
      const googleBtn = document.getElementById('google-btn');
      if (googleBtn) {
        console.log('✅ Google button found:', googleBtn);
        console.log('onclick:', googleBtn.onclick);
        console.log('pointer-events:', window.getComputedStyle(googleBtn).pointerEvents);
        console.log('z-index:', window.getComputedStyle(googleBtn).zIndex);
        console.log('display:', window.getComputedStyle(googleBtn).display);
        console.log('visibility:', window.getComputedStyle(googleBtn).visibility);
        console.log('opacity:', window.getComputedStyle(googleBtn).opacity);
        console.log('cursor:', window.getComputedStyle(googleBtn).cursor);
        
        // Add click event listener for debugging
        googleBtn.addEventListener('click', function(e) {
          console.log('✅ Google button clicked!');
          console.log('Event:', e);
        });
        
        // Also check for overlays
        const rect = googleBtn.getBoundingClientRect();
        const elementsAtPoint = document.elementsFromPoint(rect.left + rect.width/2, rect.top + rect.height/2);
        console.log('Elements at button position:', elementsAtPoint);
        console.log('Button position (rect):', {
          top: rect.top,
          left: rect.left,
          width: rect.width,
          height: rect.height,
          bottom: rect.bottom,
          right: rect.right
        });
      } else {
        console.error('❌ Google button NOT found!');
        console.log('Available elements with id or class:', {
          byId: document.getElementById('google-btn'),
          byClass: document.querySelector('.google-btn'),
          all: document.querySelectorAll('button')
        });
      }
    });
  </script>
</body>
</html>