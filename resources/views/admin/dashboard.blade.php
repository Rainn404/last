@php $layout = request()->is('admin-panel/*') ? 'layouts.admin-panel.app' : 'layouts.admin.app'; @endphp
@extends($layout)

@section('title', 'Dashboard - HIMA Sistem Manajemen')

@section('content')
<div class="dashboard-container">
    <!-- Header Section -->
    <div class="dashboard-header">
        <div class="header-content">
            <div class="header-text">
                <h1 class="header-title">Dashboard Kemahasiswaan</h1>
                <p class="header-description">Selamat datang di Sistem Manajemen HIMA</p>
            </div>
            <div class="header-actions">
                <button class="action-button outline">
                    <i class="fas fa-download button-icon"></i>
                    <span class="button-text">Export</span>
                </button>
                <button class="action-button primary">
                    <i class="fas fa-plus button-icon"></i>
                    <span class="button-text">Tambah Data</span>
                </button>
            </div>
        </div>
    </div>

    <!-- Stats Cards -->
    <div class="stats-grid">
        <div class="stats-card">
            <div class="stats-content">
                <div class="stats-icon primary">
                    <i class="fas fa-users"></i>
                </div>
                <div class="stats-info">
                    <h3 class="stats-number">{{ $totalAnggota ?? 0 }}</h3>
                    <p class="stats-label">Total Anggota</p>
                    <div class="stats-trend positive">
                        <i class="fas fa-arrow-up trend-icon"></i>
                        <span class="trend-text">12% dari bulan lalu</span>
                    </div>
                </div>
            </div>
        </div>

        <div class="stats-card">
            <div class="stats-content">
                <div class="stats-icon success">
                    <i class="fas fa-building"></i>
                </div>
                <div class="stats-info">
                    <h3 class="stats-number">{{ $totalDivisi ?? 0 }}</h3>
                    <p class="stats-label">Total Divisi</p>
                    <div class="stats-trend positive">
                        <i class="fas fa-plus trend-icon"></i>
                        <span class="trend-text">1 divisi baru</span>
                    </div>
                </div>
            </div>
        </div>

        <div class="stats-card">
            <div class="stats-content">
                <div class="stats-icon warning">
                    <i class="fas fa-trophy"></i>
                </div>
                <div class="stats-info">
                    <h3 class="stats-number">{{ $totalPrestasi ?? 0 }}</h3>
                    <p class="stats-label">Prestasi Bulan Ini</p>
                    <div class="stats-trend positive">
                        <i class="fas fa-arrow-up trend-icon"></i>
                        <span class="trend-text">25% dari bulan lalu</span>
                    </div>
                </div>
            </div>
        </div>

        <div class="stats-card">
            <div class="stats-content">
                <div class="stats-icon info">
                    <i class="fas fa-newspaper"></i>
                </div>
                <div class="stats-info">
                    <h3 class="stats-number">{{ $totalBerita ?? 0 }}</h3>
                    <p class="stats-label">Berita Aktif</p>
                    <div class="stats-trend neutral">
                        <i class="fas fa-bell trend-icon"></i>
                        <span class="trend-text">2 berita terbaru</span>
                    </div>
                </div>
            </div>
        </div>
        
        <!-- Mahasiswa Aktif (new) -->
        <div class="stats-card">
            <div class="stats-content">
                <div class="stats-icon info">
                    <i class="fas fa-graduation-cap"></i>
                </div>
                <div class="stats-info">
                    <h3 class="stats-number">{{ $mahasiswaAktif ?? 0 }}</h3>
                    <p class="stats-label">Mahasiswa Aktif</p>
                    <div class="stats-trend positive">
                        <i class="fas fa-arrow-up trend-icon"></i>
                        <span class="trend-text">Kalkulasi real-time</span>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Main Content Grid -->
    <div class="dashboard-grid">
        <!-- Recent Activities -->
        <div class="dashboard-card large">
            <div class="card-header">
                <h3 class="card-title">Aktivitas Terbaru</h3>
                <button class="card-action">
                    <i class="fas fa-ellipsis-h"></i>
                </button>
            </div>
            <div class="card-content">
                <div class="activity-list">
                    @foreach($recentActivities as $activity)
                    <div class="activity-item">
                        <div class="activity-icon {{ $activity['color'] }}">
                            <i class="fas fa-{{ $activity['icon'] ?? 'circle' }}"></i>
                        </div>
                        <div class="activity-content">
                            <p class="activity-text">{{ $activity['text'] }}</p>
                            <span class="activity-time">{{ $activity['time'] }}</span>
                        </div>
                        <span class="activity-badge badge-{{ $activity['color'] }}">{{ $activity['type'] }}</span>
                    </div>
                    @endforeach
                </div>
            </div>
        </div>

        <!-- Quick Statistics -->
        <div class="dashboard-card">
            <div class="card-header">
                <h3 class="card-title">Statistik Cepat</h3>
                <button class="card-action">
                    <i class="fas fa-sync-alt"></i>
                </button>
            </div>
            <div class="card-content">
                <div class="stats-list">
                    <div class="stat-item">
                        <div class="stat-info">
                            <i class="fas fa-user-check stat-icon primary"></i>
                            <div>
                                <p class="stat-label">Anggota Aktif</p>
                                <span class="stat-value">{{ $anggotaAktif ?? 0 }}</span>
                            </div>
                        </div>
                        <div class="stat-trend positive">
                            <i class="fas fa-arrow-up"></i>
                        </div>
                    </div>
                    
                    <div class="stat-item">
                        <div class="stat-info">
                            <i class="fas fa-graduation-cap stat-icon info"></i>
                            <div>
                                <p class="stat-label">Mahasiswa Aktif</p>
                                <span class="stat-value">{{ $mahasiswaAktif ?? 0 }}</span>
                            </div>
                        </div>
                        <div class="stat-trend positive">
                            <i class="fas fa-arrow-up"></i>
                        </div>
                    </div>
                    
                    <div class="stat-item">
                        <div class="stat-info">
                            <i class="fas fa-award stat-icon success"></i>
                            <div>
                                <p class="stat-label">Prestasi Tervalidasi</p>
                                <span class="stat-value">{{ $prestasiValid ?? 0 }}</span>
                            </div>
                        </div>
                        <div class="stat-trend positive">
                            <i class="fas fa-arrow-up"></i>
                        </div>
                    </div>
                    
                    <div class="stat-item">
                        <div class="stat-info">
                            <i class="fas fa-user-plus stat-icon warning"></i>
                            <div>
                                <p class="stat-label">Pendaftaran Baru</p>
                                <span class="stat-value">{{ $pendaftaranBaru ?? 0 }}</span>
                            </div>
                        </div>
                        <div class="stat-trend warning">
                            <i class="fas fa-exclamation"></i>
                        </div>
                    </div>
                    
                    <div class="stat-item">
                        <div class="stat-info">
                            <i class="fas fa-clock stat-icon danger"></i>
                            <div>
                                <p class="stat-label">Prestasi Pending</p>
                                <span class="stat-value">{{ $prestasiPending ?? 0 }}</span>
                            </div>
                        </div>
                        <div class="stat-trend {{ $prestasiPending > 0 ? 'danger' : 'success' }}">
                            <i class="fas fa-{{ $prestasiPending > 0 ? 'exclamation' : 'check' }}"></i>
                        </div>
                    </div>
                    
                    <div class="stat-item">
                        <div class="stat-info">
                            <i class="fas fa-hourglass stat-icon danger"></i>
                            <div>
                                <p class="stat-label">Pendaftaran Pending</p>
                                <span class="stat-value">{{ $pendaftaranPending ?? 0 }}</span>
                            </div>
                        </div>
                        <div class="stat-trend {{ $pendaftaranPending > 0 ? 'danger' : 'success' }}">
                            <i class="fas fa-{{ $pendaftaranPending > 0 ? 'exclamation' : 'check' }}"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Additional Cards -->
        <div class="dashboard-card">
            <div class="card-header">
                <h3 class="card-title">Status Sistem</h3>
            </div>
            <div class="card-content">
                <div class="system-status">
                    <div class="status-item online">
                        <i class="fas fa-server status-icon"></i>
                        <div class="status-info">
                            <span class="status-label">Server</span>
                            <span class="status-value">Online</span>
                        </div>
                    </div>
                    <div class="status-item online">
                        <i class="fas fa-database status-icon"></i>
                        <div class="status-info">
                            <span class="status-label">Database</span>
                            <span class="status-value">Connected</span>
                        </div>
                    </div>
                    <div class="status-item warning">
                        <i class="fas fa-shield-alt status-icon"></i>
                        <div class="status-info">
                            <span class="status-label">Security</span>
                            <span class="status-value">Update Required</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Recent Members -->
        <div class="dashboard-card">
            <div class="card-header">
                <h3 class="card-title">Anggota Terbaru</h3>
                <a href="#" class="card-link">Lihat Semua</a>
            </div>
            <div class="card-content">
                <div class="members-list">
                    @foreach($recentMembers as $member)
                    <div class="member-item">
                        <img src="{{ $member['avatar'] }}" alt="{{ $member['name'] }}" class="member-avatar">
                        <div class="member-info">
                            <p class="member-name">{{ $member['name'] }}</p>
                            <span class="member-divisi">{{ $member['divisi'] }}</span>
                        </div>
                        <span class="member-status {{ $member['status'] }}"></span>
                    </div>
                    @endforeach
                </div>
            </div>
        </div>
    </div>
</div>

<style>
/* Variables */
:root {
    --primary-color: #3B82F6;
    --primary-dark: #1D4ED8;
    --primary-light: #EFF6FF;
    --secondary-color: #10B981;
    --accent-color: #F59E0B;
    --warning-color: #F59E0B;
    --error-color: #EF4444;
    --text-dark: #1F2937;
    --text-light: #6B7280;
    --text-lighter: #9CA3AF;
    --white: #FFFFFF;
    --gray-light: #F3F4F6;
    --gray-medium: #E5E7EB;
    --border-color: #D1D5DB;
    --shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1), 0 2px 4px -1px rgba(0, 0, 0, 0.06);
    --shadow-lg: 0 10px 15px -3px rgba(0, 0, 0, 0.1), 0 4px 6px -2px rgba(0, 0, 0, 0.05);
    --shadow-xl: 0 20px 25px -5px rgba(0, 0, 0, 0.1), 0 10px 10px -5px rgba(0, 0, 0, 0.04);
    --border-radius: 12px;
    --border-radius-lg: 16px;
    --transition: all 0.3s ease;
}

/* Base Container */
.dashboard-container {
    padding: 2rem;
    max-width: 1400px;
    margin: 0 auto;
    animation: fadeInUp 0.6s ease-out;
}

/* Header Section */
.dashboard-header {
    margin-bottom: 2rem;
}

.header-content {
    display: flex;
    justify-content: space-between;
    align-items: center;
    gap: 2rem;
}

.header-title {
    font-size: 2.25rem;
    font-weight: 700;
    background: linear-gradient(135deg, var(--primary-color), var(--primary-dark));
    -webkit-background-clip: text;
    -webkit-text-fill-color: transparent;
    background-clip: text;
    margin-bottom: 0.5rem;
    line-height: 1.2;
}

.header-description {
    color: var(--text-light);
    font-size: 1.1rem;
    margin: 0;
}

.header-actions {
    display: flex;
    gap: 1rem;
    align-items: center;
}

/* Action Buttons */
.action-button {
    padding: 0.75rem 1.5rem;
    border-radius: var(--border-radius);
    font-weight: 600;
    text-decoration: none;
    display: inline-flex;
    align-items: center;
    gap: 0.5rem;
    transition: var(--transition);
    border: none;
    cursor: pointer;
    font-size: 0.95rem;
}

.action-button.primary {
    background: linear-gradient(135deg, var(--primary-color), var(--primary-dark));
    color: var(--white);
}

.action-button.primary:hover {
    transform: translateY(-2px);
    box-shadow: var(--shadow-lg);
}

.action-button.outline {
    background: transparent;
    color: var(--primary-color);
    border: 2px solid var(--primary-color);
}

.action-button.outline:hover {
    background: var(--primary-color);
    color: var(--white);
    transform: translateY(-2px);
}

.button-icon {
    font-size: 0.9rem;
}

/* Stats Grid */
.stats-grid {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(280px, 1fr));
    gap: 1.5rem;
    margin-bottom: 2rem;
}

.stats-card {
    background: var(--white);
    padding: 1.5rem;
    border-radius: var(--border-radius-lg);
    box-shadow: var(--shadow);
    transition: var(--transition);
    border: 1px solid var(--border-color);
}

.stats-card:hover {
    transform: translateY(-5px);
    box-shadow: var(--shadow-xl);
}

.stats-content {
    display: flex;
    align-items: center;
    gap: 1rem;
}

.stats-icon {
    width: 60px;
    height: 60px;
    border-radius: 12px;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 1.5rem;
    flex-shrink: 0;
}

.stats-icon.primary {
    background: var(--primary-light);
    color: var(--primary-color);
}

.stats-icon.success {
    background: #ECFDF5;
    color: var(--secondary-color);
}

.stats-icon.warning {
    background: #FFFBEB;
    color: var(--warning-color);
}

.stats-icon.info {
    background: #EFF6FF;
    color: var(--primary-color);
}

.stats-number {
    font-size: 2rem;
    font-weight: 700;
    color: var(--text-dark);
    margin-bottom: 0.25rem;
    line-height: 1;
}

.stats-label {
    color: var(--text-light);
    margin-bottom: 0.5rem;
    font-size: 0.95rem;
}

.stats-trend {
    display: flex;
    align-items: center;
    gap: 0.25rem;
    font-size: 0.8rem;
    font-weight: 600;
}

.stats-trend.positive {
    color: var(--secondary-color);
}

.stats-trend.neutral {
    color: var(--text-light);
}

.stats-trend.negative {
    color: var(--error-color);
}

.trend-icon {
    font-size: 0.7rem;
}

/* Dashboard Grid */
.dashboard-grid {
    display: grid;
    grid-template-columns: 2fr 1fr;
    gap: 1.5rem;
}

.dashboard-card {
    background: var(--white);
    border-radius: var(--border-radius-lg);
    box-shadow: var(--shadow);
    overflow: hidden;
}

.dashboard-card.large {
    grid-column: 1 / -1;
}

.card-header {
    display: flex;
    justify-content: space-between;
    align-items: center;
    padding: 1.5rem 1.5rem 0;
    margin-bottom: 1rem;
}

.card-title {
    font-size: 1.25rem;
    font-weight: 600;
    color: var(--text-dark);
    margin: 0;
}

.card-action {
    background: none;
    border: none;
    color: var(--text-light);
    cursor: pointer;
    padding: 0.5rem;
    border-radius: 6px;
    transition: var(--transition);
}

.card-action:hover {
    background: var(--gray-light);
    color: var(--text-dark);
}

.card-link {
    color: var(--primary-color);
    text-decoration: none;
    font-size: 0.9rem;
    font-weight: 500;
    transition: var(--transition);
}

.card-link:hover {
    color: var(--primary-dark);
}

.card-content {
    padding: 0 1.5rem 1.5rem;
}

/* Activity List */
.activity-list {
    display: flex;
    flex-direction: column;
    gap: 1rem;
}

.activity-item {
    display: flex;
    align-items: flex-start;
    gap: 1rem;
    padding: 1rem;
    background: var(--gray-light);
    border-radius: var(--border-radius);
    transition: var(--transition);
}

.activity-item:hover {
    background: var(--primary-light);
    transform: translateX(5px);
}

.activity-icon {
    width: 40px;
    height: 40px;
    border-radius: 8px;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 1rem;
    flex-shrink: 0;
}

.activity-icon.primary { background: var(--primary-light); color: var(--primary-color); }
.activity-icon.success { background: #ECFDF5; color: var(--secondary-color); }
.activity-icon.warning { background: #FFFBEB; color: var(--warning-color); }
.activity-icon.error { background: #FEF2F2; color: var(--error-color); }

.activity-content {
    flex: 1;
}

.activity-text {
    color: var(--text-dark);
    margin-bottom: 0.25rem;
    font-weight: 500;
    line-height: 1.4;
}

.activity-time {
    color: var(--text-light);
    font-size: 0.8rem;
}

.activity-badge {
    padding: 0.25rem 0.75rem;
    border-radius: 20px;
    font-size: 0.75rem;
    font-weight: 600;
    text-transform: uppercase;
}

.badge-primary { background: var(--primary-light); color: var(--primary-color); }
.badge-success { background: #ECFDF5; color: var(--secondary-color); }
.badge-warning { background: #FFFBEB; color: var(--warning-color); }
.badge-error { background: #FEF2F2; color: var(--error-color); }

/* Stats List */
.stats-list {
    display: flex;
    flex-direction: column;
    gap: 1rem;
}

.stat-item {
    display: flex;
    justify-content: space-between;
    align-items: center;
    padding: 1rem;
    background: var(--gray-light);
    border-radius: var(--border-radius);
    transition: var(--transition);
}

.stat-item:hover {
    background: var(--primary-light);
    transform: translateX(5px);
}

.stat-info {
    display: flex;
    align-items: center;
    gap: 1rem;
}

.stat-icon {
    width: 40px;
    height: 40px;
    border-radius: 8px;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 1rem;
}

.stat-icon.primary { background: var(--primary-light); color: var(--primary-color); }
.stat-icon.success { background: #ECFDF5; color: var(--secondary-color); }
.stat-icon.warning { background: #FFFBEB; color: var(--warning-color); }

.stat-label {
    color: var(--text-light);
    font-size: 0.9rem;
    margin-bottom: 0.25rem;
}

.stat-value {
    color: var(--text-dark);
    font-weight: 600;
    font-size: 1.1rem;
}

.stat-trend {
    padding: 0.5rem;
    border-radius: 6px;
    font-size: 0.8rem;
}

.stat-trend.positive { background: #ECFDF5; color: var(--secondary-color); }
.stat-trend.warning { background: #FFFBEB; color: var(--warning-color); }

/* System Status */
.system-status {
    display: flex;
    flex-direction: column;
    gap: 1rem;
}

.status-item {
    display: flex;
    align-items: center;
    gap: 1rem;
    padding: 1rem;
    background: var(--gray-light);
    border-radius: var(--border-radius);
}

.status-icon {
    width: 40px;
    height: 40px;
    border-radius: 8px;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 1.25rem;
}

.status-item.online .status-icon { background: #ECFDF5; color: var(--secondary-color); }
.status-item.warning .status-icon { background: #FFFBEB; color: var(--warning-color); }

.status-label {
    color: var(--text-light);
    font-size: 0.9rem;
    display: block;
    margin-bottom: 0.25rem;
}

.status-value {
    color: var(--text-dark);
    font-weight: 600;
    font-size: 1rem;
}

/* Members List */
.members-list {
    display: flex;
    flex-direction: column;
    gap: 1rem;
}

.member-item {
    display: flex;
    align-items: center;
    gap: 1rem;
    padding: 0.75rem;
    background: var(--gray-light);
    border-radius: var(--border-radius);
    transition: var(--transition);
}

.member-item:hover {
    background: var(--primary-light);
    transform: translateX(5px);
}

.member-avatar {
    width: 40px;
    height: 40px;
    border-radius: 50%;
    object-fit: cover;
}

.member-name {
    color: var(--text-dark);
    font-weight: 500;
    margin-bottom: 0.25rem;
}

.member-divisi {
    color: var(--text-light);
    font-size: 0.8rem;
}

.member-status {
    width: 8px;
    height: 8px;
    border-radius: 50%;
    margin-left: auto;
}

.member-status.active { background: var(--secondary-color); }
.member-status.inactive { background: var(--text-light); }
.member-status.pending { background: var(--warning-color); }

/* Animations */
@keyframes fadeInUp {
    from {
        opacity: 0;
        transform: translateY(30px);
    }
    to {
        opacity: 1;
        transform: translateY(0);
    }
}

/* Responsive Design */
@media (max-width: 1024px) {
    .dashboard-grid {
        grid-template-columns: 1fr;
    }
    
    .dashboard-card.large {
        grid-column: 1;
    }
}

@media (max-width: 768px) {
    .dashboard-container {
        padding: 1rem;
    }
    
    .header-content {
        flex-direction: column;
        align-items: flex-start;
        gap: 1rem;
    }
    
    .header-actions {
        width: 100%;
        justify-content: flex-start;
    }
    
    .stats-grid {
        grid-template-columns: 1fr;
    }
    
    .stats-content {
        flex-direction: column;
        text-align: center;
        gap: 0.75rem;
    }
    
    .action-button {
        width: 100%;
        justify-content: center;
    }
}

@media (max-width: 480px) {
    .header-title {
        font-size: 1.75rem;
    }
    
    .stats-number {
        font-size: 1.75rem;
    }
    
    .card-header {
        padding: 1rem 1rem 0;
    }
    
    .card-content {
        padding: 0 1rem 1rem;
    }
}
</style>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Add hover effects to cards
    const cards = document.querySelectorAll('.stats-card, .dashboard-card');
    cards.forEach(card => {
        card.addEventListener('mouseenter', function() {
            this.style.transform = 'translateY(-5px)';
        });
        
        card.addEventListener('mouseleave', function() {
            this.style.transform = 'translateY(0)';
        });
    });

    // Refresh stats functionality
    const refreshBtn = document.querySelector('.card-action .fa-sync-alt');
    if (refreshBtn) {
        refreshBtn.closest('.card-action').addEventListener('click', function() {
            const icon = this.querySelector('i');
            icon.style.animation = 'spin 1s linear';
            
            setTimeout(() => {
                icon.style.animation = '';
            }, 1000);
        });
    }

    // Add spin animation
    const style = document.createElement('style');
    style.textContent = `
        @keyframes spin {
            0% { transform: rotate(0deg); }
            100% { transform: rotate(360deg); }
        }
    `;
    document.head.appendChild(style);
});
</script>
@endsection