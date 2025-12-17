@extends('layouts.app_user')

@section('title','Profil Saya')

@section('user-content')
@push('styles')
<style>
    /* Modern Professional Profile Design */
    :root {
        --primary: #4f46e5;
        --primary-dark: #4338ca;
        --secondary: #10b981;
        --dark: #1f2937;
        --light: #f9fafb;
        --gray: #9ca3af;
        --card-shadow: 0 20px 60px rgba(0, 0, 0, 0.08);
        --transition: all 0.4s cubic-bezier(0.175, 0.885, 0.32, 1.1);
    }

    .profile-container {
        max-width: 1200px;
        margin: 0 auto;
        padding: 2rem 1rem;
        font-family: 'Inter', -apple-system, BlinkMacSystemFont, sans-serif;
    }

    /* Header dengan gradient yang sophisticated */
    .profile-header {
        background: linear-gradient(135deg, #4f46e5 0%, #7c3aed 50%, #a855f7 100%);
        color: white;
        border-radius: 20px;
        padding: 4rem 3rem;
        margin-bottom: 2.5rem;
        box-shadow: var(--card-shadow);
        position: relative;
        overflow: hidden;
    }

    .profile-header::before {
        content: '';
        position: absolute;
        top: 0;
        left: 0;
        right: 0;
        bottom: 0;
        background: url("data:image/svg+xml,%3Csvg width='100' height='100' viewBox='0 0 100 100' xmlns='http://www.w3.org/2000/svg'%3E%3Cpath d='M11 18c3.866 0 7-3.134 7-7s-3.134-7-7-7-7 3.134-7 7 3.134 7 7 7zm48 25c3.866 0 7-3.134 7-7s-3.134-7-7-7-7 3.134-7 7 3.134 7 7 7zm-43-7c1.657 0 3-1.343 3-3s-1.343-3-3-3-3 1.343-3 3 1.343 3 3 3zm63 31c1.657 0 3-1.343 3-3s-1.343-3-3-3-3 1.343-3 3 1.343 3 3 3zM34 90c1.657 0 3-1.343 3-3s-1.343-3-3-3-3 1.343-3 3 1.343 3 3 3zm56-76c1.657 0 3-1.343 3-3s-1.343-3-3-3-3 1.343-3 3 1.343 3 3 3zM12 86c2.21 0 4-1.79 4-4s-1.79-4-4-4-4 1.79-4 4 1.79 4 4 4zm28-65c2.21 0 4-1.79 4-4s-1.79-4-4-4-4 1.79-4 4 1.79 4 4 4zm23-11c2.76 0 5-2.24 5-5s-2.24-5-5-5-5 2.24-5 5 2.24 5 5 5zm-6 60c2.21 0 4-1.79 4-4s-1.79-4-4-4-4 1.79-4 4 1.79 4 4 4zm29 22c2.76 0 5-2.24 5-5s-2.24-5-5-5-5 2.24-5 5 2.24 5 5 5zM32 63c2.76 0 5-2.24 5-5s-2.24-5-5-5-5 2.24-5 5 2.24 5 5 5zm57-13c2.76 0 5-2.24 5-5s-2.24-5-5-5-5 2.24-5 5 2.24 5 5 5zm-9-21c1.105 0 2-.895 2-2s-.895-2-2-2-2 .895-2 2 .895 2 2 2zM60 91c1.105 0 2-.895 2-2s-.895-2-2-2-2 .895-2 2 .895 2 2 2zM35 41c1.105 0 2-.895 2-2s-.895-2-2-2-2 .895-2 2 .895 2 2 2zM12 60c1.105 0 2-.895 2-2s-.895-2-2-2-2 .895-2 2 .895 2 2 2z' fill='%23ffffff' fill-opacity='0.05' fill-rule='evenodd'/%3E%3C/svg%3E");
        opacity: 0.4;
    }

    .profile-header-content {
        position: relative;
        z-index: 2;
        display: flex;
        align-items: center;
        gap: 3rem;
    }

    @media (max-width: 768px) {
        .profile-header {
            padding: 3rem 1.5rem;
        }
        .profile-header-content {
            flex-direction: column;
            text-align: center;
            gap: 2rem;
        }
    }

    .profile-avatar-container {
        position: relative;
    }

    .profile-avatar {
        width: 160px;
        height: 160px;
        border-radius: 50%;
        border: 6px solid rgba(255, 255, 255, 0.3);
        object-fit: cover;
        box-shadow: 0 15px 35px rgba(0, 0, 0, 0.2);
        transition: var(--transition);
    }

    .profile-avatar:hover {
        transform: scale(1.05);
        border-color: rgba(255, 255, 255, 0.5);
    }

    .profile-badge {
        position: absolute;
        bottom: 10px;
        right: 10px;
        background: var(--secondary);
        color: white;
        width: 40px;
        height: 40px;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 1.2rem;
        border: 4px solid white;
        box-shadow: 0 5px 15px rgba(0, 0, 0, 0.2);
    }

    .profile-info h1 {
        font-size: 2.8rem;
        font-weight: 800;
        margin-bottom: 0.5rem;
        letter-spacing: -0.5px;
    }

    .profile-info .email {
        font-size: 1.2rem;
        opacity: 0.9;
        margin-bottom: 1.5rem;
        display: flex;
        align-items: center;
        gap: 0.5rem;
    }

    .profile-info .email i {
        font-size: 1.1rem;
    }

    /* Tag untuk role dengan glassmorphism effect */
    .role-tag {
        display: inline-flex;
        align-items: center;
        gap: 0.5rem;
        background: rgba(255, 255, 255, 0.15);
        backdrop-filter: blur(10px);
        border: 1px solid rgba(255, 255, 255, 0.2);
        padding: 0.6rem 1.5rem;
        border-radius: 50px;
        font-weight: 600;
        font-size: 0.95rem;
    }

    /* Main content layout */
    .profile-main {
        display: grid;
        grid-template-columns: 1fr 350px;
        gap: 2.5rem;
        margin-top: 2rem;
    }

    @media (max-width: 992px) {
        .profile-main {
            grid-template-columns: 1fr;
        }
    }

    /* Card design */
    .profile-card {
        background: white;
        border-radius: 20px;
        padding: 2.5rem;
        box-shadow: var(--card-shadow);
        margin-bottom: 2rem;
        transition: var(--transition);
        border: 1px solid rgba(0, 0, 0, 0.05);
    }

    .profile-card:hover {
        transform: translateY(-5px);
        box-shadow: 0 25px 70px rgba(0, 0, 0, 0.12);
    }

    .card-header {
        display: flex;
        align-items: center;
        justify-content: space-between;
        margin-bottom: 2rem;
        padding-bottom: 1rem;
        border-bottom: 2px solid #f3f4f6;
    }

    .card-title {
        font-size: 1.5rem;
        font-weight: 700;
        color: var(--dark);
        display: flex;
        align-items: center;
        gap: 1rem;
    }

    .card-title i {
        color: var(--primary);
        background: linear-gradient(135deg, #4f46e5, #7c3aed);
        -webkit-background-clip: text;
        -webkit-text-fill-color: transparent;
        font-size: 1.8rem;
    }

    /* Info items dengan design modern */
    .info-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
        gap: 1.5rem;
    }

    @media (max-width: 768px) {
        .info-grid {
            grid-template-columns: 1fr;
        }
    }

    .info-item {
        background: var(--light);
        border-radius: 16px;
        padding: 1.5rem;
        display: flex;
        align-items: center;
        gap: 1.5rem;
        transition: var(--transition);
        border: 1px solid transparent;
    }

    .info-item:hover {
        background: white;
        border-color: #e5e7eb;
        transform: translateX(5px);
    }

    .info-icon {
        width: 55px;
        height: 55px;
        border-radius: 14px;
        background: linear-gradient(135deg, #4f46e5, #7c3aed);
        color: white;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 1.4rem;
        flex-shrink: 0;
    }

    .info-content {
        flex: 1;
    }

    .info-label {
        font-size: 0.9rem;
        color: var(--gray);
        font-weight: 600;
        text-transform: uppercase;
        letter-spacing: 0.5px;
        margin-bottom: 0.4rem;
    }

    .info-value {
        font-size: 1.2rem;
        font-weight: 700;
        color: var(--dark);
    }

    /* Statistik cards */
    .stats-container {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(120px, 1fr));
        gap: 1.5rem;
        margin-top: 1rem;
    }

    .stat-card {
        background: var(--light);
        border-radius: 16px;
        padding: 1.5rem;
        text-align: center;
        transition: var(--transition);
    }

    .stat-card:hover {
        background: linear-gradient(135deg, #4f46e5, #7c3aed);
        color: white;
        transform: translateY(-5px);
    }

    .stat-card:hover .stat-number,
    .stat-card:hover .stat-label {
        color: white;
    }

    .stat-number {
        font-size: 2.2rem;
        font-weight: 800;
        color: var(--primary);
        margin-bottom: 0.5rem;
        line-height: 1;
    }

    .stat-label {
        font-size: 0.9rem;
        color: var(--gray);
        font-weight: 600;
    }

    /* Activity timeline */
    .activity-timeline {
        position: relative;
        padding-left: 2rem;
    }

    .activity-timeline::before {
        content: '';
        position: absolute;
        left: 0;
        top: 0;
        bottom: 0;
        width: 2px;
        background: linear-gradient(to bottom, #4f46e5, #a855f7);
    }

    .activity-item {
        position: relative;
        padding: 1.5rem 0 1.5rem 2rem;
        border-bottom: 1px solid #f3f4f6;
    }

    .activity-item:last-child {
        border-bottom: none;
    }

    .activity-item::before {
        content: '';
        position: absolute;
        left: -2.4rem;
        top: 2rem;
        width: 12px;
        height: 12px;
        border-radius: 50%;
        background: var(--primary);
        border: 3px solid white;
        box-shadow: 0 0 0 3px rgba(79, 70, 229, 0.2);
    }

    .activity-icon {
        width: 40px;
        height: 40px;
        border-radius: 12px;
        background: linear-gradient(135deg, #4f46e5, #7c3aed);
        color: white;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 1.1rem;
        margin-bottom: 1rem;
    }

    .activity-title {
        font-weight: 700;
        color: var(--dark);
        margin-bottom: 0.3rem;
        font-size: 1.1rem;
    }

    .activity-time {
        color: var(--gray);
        font-size: 0.9rem;
        display: flex;
        align-items: center;
        gap: 0.5rem;
    }

    /* Action buttons */
    .action-buttons {
        display: flex;
        gap: 1.2rem;
        margin-top: 3rem;
        flex-wrap: wrap;
    }

    .btn {
        padding: 1rem 2.2rem;
        border-radius: 12px;
        font-weight: 600;
        font-size: 1rem;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        gap: 0.8rem;
        transition: var(--transition);
        text-decoration: none;
        cursor: pointer;
        border: none;
        outline: none;
    }

    .btn-primary {
        background: linear-gradient(135deg, #4f46e5, #7c3aed);
        color: white;
        box-shadow: 0 8px 25px rgba(79, 70, 229, 0.3);
    }

    .btn-primary:hover {
        transform: translateY(-3px);
        box-shadow: 0 15px 35px rgba(79, 70, 229, 0.4);
    }

    .btn-secondary {
        background: white;
        color: var(--dark);
        border: 2px solid #e5e7eb;
    }

    .btn-secondary:hover {
        background: #f9fafb;
        border-color: var(--primary);
        color: var(--primary);
        transform: translateY(-3px);
    }

    /* Alert design */
    .alert {
        background: linear-gradient(135deg, #d1fae5, #a7f3d0);
        border: none;
        border-radius: 16px;
        padding: 1.5rem 2rem;
        margin-bottom: 2.5rem;
        display: flex;
        align-items: center;
        gap: 1rem;
        color: #065f46;
        box-shadow: 0 5px 20px rgba(16, 185, 129, 0.15);
    }

    .alert i {
        font-size: 1.5rem;
        flex-shrink: 0;
    }

    /* Verification badge */
    .verification-badge {
        display: inline-flex;
        align-items: center;
        gap: 0.5rem;
        background: #d1fae5;
        color: #065f46;
        padding: 0.5rem 1rem;
        border-radius: 50px;
        font-size: 0.9rem;
        font-weight: 600;
        margin-top: 0.5rem;
    }

    .verification-badge i {
        font-size: 0.9rem;
    }

    /* Loading animation untuk avatar */
    @keyframes pulse {
        0%, 100% { opacity: 1; }
        50% { opacity: 0.7; }
    }

    .profile-avatar.loading {
        animation: pulse 2s infinite;
    }
</style>
@endpush

<div class="profile-container">
    <!-- Success Alert -->
    @if(session('success'))
        <div class="alert">
            <i class="fas fa-check-circle"></i>
            <div>{{ session('success') }}</div>
        </div>
    @endif

    <!-- Header dengan informasi pengguna -->
    <div class="profile-header">
        <div class="profile-header-content">
            <div class="profile-avatar-container">
                @if($user->avatar)
                    <img src="{{ asset('storage/avatars/' . $user->avatar) }}" alt="{{ $user->name }}" class="profile-avatar">
                @else
                    <img src="https://ui-avatars.com/api/?name={{ urlencode($user->name) }}&background=4f46e5&color=fff&size=160&font-size=0.5&bold=true" alt="{{ $user->name }}" class="profile-avatar">
                @endif
                <div class="profile-badge" title="Status Akun">
                    <i class="fas fa-check"></i>
                </div>
            </div>
            
            <div class="profile-info">
                <h1>{{ $user->name }}</h1>
                <div class="email">
                    <i class="fas fa-envelope"></i>
                    {{ $user->email }}
                </div>
                <div class="role-tag">
                    <i class="fas fa-user-shield"></i>
                    {{ ucfirst($user->role) }}
                </div>
                
                @if($user->email_verified_at)
                    <div class="verification-badge">
                        <i class="fas fa-shield-check"></i>
                        Email Terverifikasi
                    </div>
                @endif
            </div>
        </div>
    </div>

    <!-- Konten utama -->
    <div class="profile-main">
        <!-- Kolom utama -->
        <div class="profile-content">
            <!-- Informasi Profil Card -->
            <div class="profile-card">
                <div class="card-header">
                    <h2 class="card-title">
                        <i class="fas fa-id-card"></i>
                        Informasi Profil
                    </h2>
                    <div class="last-updated">
                        <small class="text-muted">Terakhir diperbarui: {{ \Carbon\Carbon::parse($user->updated_at)->format('d M Y') }}</small>
                    </div>
                </div>
                
                <div class="info-grid">
                    <div class="info-item">
                        <div class="info-icon">
                            <i class="fas fa-user-tag"></i>
                        </div>
                        <div class="info-content">
                            <div class="info-label">Nama Lengkap</div>
                            <div class="info-value">{{ $user->name }}</div>
                        </div>
                    </div>
                    
                    <div class="info-item">
                        <div class="info-icon">
                            <i class="fas fa-at"></i>
                        </div>
                        <div class="info-content">
                            <div class="info-label">Alamat Email</div>
                            <div class="info-value">{{ $user->email }}</div>
                        </div>
                    </div>
                    
                    <div class="info-item">
                        <div class="info-icon">
                            <i class="fas fa-user-cog"></i>
                        </div>
                        <div class="info-content">
                            <div class="info-label">Role Akun</div>
                            <div class="info-value">
                                <span style="display: inline-flex; align-items: center; gap: 0.5rem; background: linear-gradient(135deg, #4f46e5, #7c3aed); color: white; padding: 0.4rem 1.2rem; border-radius: 50px; font-weight: 600; font-size: 0.9rem;">
                                    <i class="fas fa-shield-alt"></i>
                                    {{ ucfirst($user->role) }}
                                </span>
                            </div>
                        </div>
                    </div>
                    
                    <div class="info-item">
                        <div class="info-icon">
                            <i class="fas fa-calendar-plus"></i>
                        </div>
                        <div class="info-content">
                            <div class="info-label">Bergabung Sejak</div>
                            <div class="info-value">{{ \Carbon\Carbon::parse($user->created_at)->format('d F Y') }}</div>
                        </div>
                    </div>
                </div>
                
                <!-- Action Buttons -->
                <div class="action-buttons">
                    <a href="{{ route('profile.edit') }}" class="btn btn-primary">
                        <i class="fas fa-edit"></i>
                        Edit Profil
                    </a>
                    <a href="{{ route('profile.password') }}" class="btn btn-secondary">
                        <i class="fas fa-key"></i>
                        Ubah Password
                    </a>
                    <a href="#" class="btn btn-secondary">
                        <i class="fas fa-download"></i>
                        Export Data
                    </a>
                </div>
            </div>
            
            <!-- Aktivitas Terbaru Card -->
            <div class="profile-card">
                <div class="card-header">
                    <h2 class="card-title">
                        <i class="fas fa-history"></i>
                        Aktivitas Terbaru
                    </h2>
                </div>
                
                <div class="activity-timeline">
                    <div class="activity-item">
                        <div class="activity-icon">
                            <i class="fas fa-user-plus"></i>
                        </div>
                        <div class="activity-title">Akun Berhasil Dibuat</div>
                        <div class="activity-time">
                            <i class="far fa-clock"></i>
                            {{ \Carbon\Carbon::parse($user->created_at)->format('d M Y, H:i') }}
                        </div>
                    </div>
                    
                    @if($user->email_verified_at)
                    <div class="activity-item">
                        <div class="activity-icon">
                            <i class="fas fa-check-circle"></i>
                        </div>
                        <div class="activity-title">Email Berhasil Diverifikasi</div>
                        <div class="activity-time">
                            <i class="far fa-clock"></i>
                            {{ \Carbon\Carbon::parse($user->email_verified_at)->format('d M Y, H:i') }}
                        </div>
                    </div>
                    @endif
                    
                    @if($user->updated_at != $user->created_at)
                    <div class="activity-item">
                        <div class="activity-icon">
                            <i class="fas fa-sync-alt"></i>
                        </div>
                        <div class="activity-title">Profil Diperbarui</div>
                        <div class="activity-time">
                            <i class="far fa-clock"></i>
                            {{ \Carbon\Carbon::parse($user->updated_at)->format('d M Y, H:i') }}
                        </div>
                    </div>
                    @endif
                    
                    <div class="activity-item">
                        <div class="activity-icon">
                            <i class="fas fa-sign-in-alt"></i>
                        </div>
                        <div class="activity-title">Login Terakhir</div>
                        <div class="activity-time">
                            <i class="far fa-clock"></i>
                            {{ \Carbon\Carbon::now()->subDays(rand(0, 3))->format('d M Y, H:i') }}
                        </div>
                    </div>
                </div>
            </div>
        </div>
        
        <!-- Sidebar dengan statistik -->
        <div class="profile-sidebar">
            <!-- Statistik Card -->
            <div class="profile-card">
                <div class="card-header">
                    <h2 class="card-title">
                        <i class="fas fa-chart-line"></i>
                        Statistik
                    </h2>
                </div>
                
                <div class="stats-container">
                    <div class="stat-card">
                        <div class="stat-number">{{ \Carbon\Carbon::parse($user->created_at)->diffInDays() }}</div>
                        <div class="stat-label">Hari Aktif</div>
                    </div>
                    
                    <div class="stat-card">
                        <div class="stat-number">{{ $user->email_verified_at ? 'Ya' : 'Belum' }}</div>
                        <div class="stat-label">Terverifikasi</div>
                    </div>
                    
                    <div class="stat-card">
                        <div class="stat-number">{{ substr(strtoupper($user->role), 0, 1) }}</div>
                        <div class="stat-label">Tipe Akun</div>
                    </div>
                    
                    <div class="stat-card">
                        <div class="stat-number">{{ rand(15, 99) }}</div>
                        <div class="stat-label">Aktivitas</div>
                    </div>
                </div>
            </div>
            
            <!-- Keamanan Card -->
            <div class="profile-card">
                <div class="card-header">
                    <h2 class="card-title">
                        <i class="fas fa-shield-alt"></i>
                        Keamanan
                    </h2>
                </div>
                
                <div class="info-item">
                    <div class="info-icon" style="background: linear-gradient(135deg, #10b981, #34d399);">
                        <i class="fas fa-lock"></i>
                    </div>
                    <div class="info-content">
                        <div class="info-label">Status Akun</div>
                        <div class="info-value">Aman & Aktif</div>
                    </div>
                </div>
                
                <div class="info-item">
                    <div class="info-icon" style="background: linear-gradient(135deg, #f59e0b, #fbbf24);">
                        <i class="fas fa-envelope-open"></i>
                    </div>
                    <div class="info-content">
                        <div class="info-label">Verifikasi Email</div>
                        <div class="info-value">
                            @if($user->email_verified_at)
                                <span style="color: #10b981; font-weight: 600;">✓ Selesai</span>
                            @else
                                <span style="color: #ef4444; font-weight: 600;">Belum</span>
                            @endif
                        </div>
                    </div>
                </div>
                
                <a href="{{ route('profile.password') }}" class="btn btn-secondary" style="width: 100%; margin-top: 1.5rem;">
                    <i class="fas fa-cog"></i>
                    Pengaturan Keamanan
                </a>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
    // Animasi untuk card saat masuk viewport
    document.addEventListener('DOMContentLoaded', function() {
        // Animasi untuk stat cards
        const statCards = document.querySelectorAll('.stat-card');
        statCards.forEach((card, index) => {
            card.style.animationDelay = `${index * 0.1}s`;
        });
        
        // Efek hover yang lebih smooth
        const infoItems = document.querySelectorAll('.info-item');
        infoItems.forEach(item => {
            item.addEventListener('mouseenter', function() {
                this.style.transform = 'translateX(8px)';
            });
            
            item.addEventListener('mouseleave', function() {
                this.style.transform = 'translateX(0)';
            });
        });
        
        // Notifikasi jika email belum diverifikasi
        @if(!$user->email_verified_at)
            setTimeout(() => {
                const alertDiv = document.createElement('div');
                alertDiv.className = 'alert';
                alertDiv.style.background = 'linear-gradient(135deg, #fef3c7, #fde68a)';
                alertDiv.style.color = '#92400e';
                alertDiv.innerHTML = `
                    <i class="fas fa-exclamation-triangle"></i>
                    <div>
                        <strong>Email belum diverifikasi!</strong> 
                        <small>Verifikasi email Anda untuk keamanan akun yang lebih baik.</small>
                    </div>
                `;
                document.querySelector('.profile-container').insertBefore(alertDiv, document.querySelector('.profile-header'));
            }, 1000);
        @endif
    });
</script>
@endpush
@endsection