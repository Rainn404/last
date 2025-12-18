@extends('layouts.app')

@section('title', 'Kuota Penuh - HIMA-TI')

@section('content')
<link rel="stylesheet" href="{{ asset('css/glassmorphism.css') }}">

<style>
    body {
        background-image: url('/logo_bg/gedung politala');
        background-attachment: fixed;
        background-position: center;
        background-repeat: no-repeat;
        background-size: cover;
    }

    body::before {
        content: '';
        position: fixed;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        background: linear-gradient(rgba(15, 23, 42, 0.55), rgba(49, 46, 129, 0.25));
        pointer-events: none;
        z-index: 0;
    }

    main, section {
        position: relative;
        z-index: 2;
    }

    .page-header {
        background: rgba(255, 255, 255, 0.12);
        backdrop-filter: blur(15px);
        border-bottom: 1px solid rgba(224, 231, 255, 0.25);
        padding: 80px 20px;
        margin-top: 70px;
        text-align: center;
        color: #F8FAFC;
    }

    .page-header h1 {
        color: #F8FAFC;
        font-weight: 800;
        font-size: 2.5rem;
        text-shadow: 0 2px 4px rgba(2, 6, 23, 0.2);
    }

    .page-header p {
        color: #E0E7FF;
        font-size: 1.1rem;
    }

    .quota-section {
        padding: 60px 20px;
        display: flex;
        align-items: center;
        justify-content: center;
        min-height: 400px;
    }

    .container {
        max-width: 1200px;
        margin: 0 auto;
        position: relative;
        z-index: 2;
    }

    .quota-card {
        background: rgba(255, 255, 255, 0.18);
        backdrop-filter: blur(8px);
        border: 1px solid rgba(224, 231, 255, 0.30);
        border-radius: 16px;
        padding: 60px 40px;
        text-align: center;
        box-shadow: 0 8px 32px rgba(2, 6, 23, 0.15);
    }

    .quota-icon {
        font-size: 4rem;
        color: #FB923C;
        margin-bottom: 24px;
    }

    .quota-card h2 {
        color: #F8FAFC;
        font-weight: 700;
        font-size: 2rem;
        margin-bottom: 16px;
    }

    .quota-message {
        color: #CBD5E1;
        font-size: 1.1rem;
        margin-bottom: 32px;
        line-height: 1.7;
        max-width: 600px;
        margin-left: auto;
        margin-right: auto;
    }

    .stats-card {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
        gap: 16px;
        margin: 32px 0;
        background: rgba(99, 102, 241, 0.15);
        border: 1px solid rgba(99, 102, 241, 0.3);
        border-radius: 12px;
        padding: 24px;
    }

    .stat-item {
        display: flex;
        align-items: center;
        gap: 16px;
        color: #CBD5E1;
    }

    .stat-item i {
        font-size: 2rem;
        color: #6366F1;
    }

    .stat-item strong {
        display: block;
        font-size: 1.5rem;
        color: #F8FAFC;
        line-height: 1.2;
    }

    .stat-item span {
        display: block;
        font-size: 0.875rem;
        color: #94A3B8;
    }

    .next-period {
        background: rgba(34, 197, 94, 0.15);
        border: 1px solid rgba(34, 197, 94, 0.3);
        border-radius: 12px;
        padding: 24px;
        margin: 24px 0;
        color: #86EFAC;
    }

    .next-period h4 {
        color: #F8FAFC;
        margin-bottom: 12px;
    }

    .next-period p {
        color: #CBD5E1;
        margin-bottom: 16px;
    }

    .info-channels {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(150px, 1fr));
        gap: 12px;
    }

    .channel {
        background: rgba(255, 255, 255, 0.1);
        border: 1px solid rgba(34, 197, 94, 0.3);
        border-radius: 8px;
        padding: 12px;
        display: flex;
        flex-direction: column;
        align-items: center;
        gap: 8px;
        cursor: pointer;
        transition: all 0.3s ease;
        color: #86EFAC;
    }

    .channel:hover {
        background: rgba(34, 197, 94, 0.2);
        transform: translateY(-2px);
    }

    .channel i {
        font-size: 1.5rem;
    }

    .channel span {
        font-size: 0.875rem;
    }

    .action-buttons {
        display: flex;
        gap: 16px;
        justify-content: center;
        flex-wrap: wrap;
        margin-top: 32px;
    }

    .btn-primary {
        background: #6366F1 !important;
        border-color: #6366F1 !important;
        color: #FFFFFF !important;
        padding: 12px 24px;
        border-radius: 8px;
        font-weight: 600;
        text-decoration: none;
        display: inline-flex;
        align-items: center;
        gap: 8px;
        transition: all 0.3s ease;
    }

    .btn-primary:hover {
        background: #4F46E5 !important;
        border-color: #4F46E5 !important;
        transform: translateY(-2px);
        box-shadow: 0 6px 20px rgba(99, 102, 241, 0.35);
    }

    .btn-outline {
        background: rgba(255, 255, 255, 0.15) !important;
        border: 1px solid rgba(224, 231, 255, 0.30) !important;
        color: #F8FAFC !important;
        padding: 12px 24px;
        border-radius: 8px;
        font-weight: 600;
        text-decoration: none;
        display: inline-flex;
        align-items: center;
        gap: 8px;
        transition: all 0.3s ease;
    }

    .btn-outline:hover {
        background: rgba(255, 255, 255, 0.25) !important;
        border-color: #6366F1 !important;
        color: #E0E7FF !important;
    }

    @media (max-width: 768px) {
        .page-header h1 {
            font-size: 1.8rem;
        }

        .quota-card {
            padding: 40px 20px;
        }

        .stats-card {
            grid-template-columns: 1fr;
        }

        .action-buttons {
            flex-direction: column;
        }

        .btn-primary, .btn-outline {
            width: 100%;
            justify-content: center;
        }
    }
</style>

<!-- Page Header -->
<section class="page-header">
    <div class="container">
        <h1>Kuota Pendaftaran Penuh</h1>
        <p>Maaf, kuota pendaftaran anggota HIMA-TI telah terpenuhi</p>
    </div>
</section>

<!-- Quota Full Message -->
<section class="quota-section">
    <div class="container">
        <div class="quota-card">
            <div class="quota-icon">
                <i class="fas fa-users-slash"></i>
            </div>
            <h2>Kuota Telah Terpenuhi</h2>
            <p class="quota-message">
                Maaf, kuota pendaftaran anggota HIMA-TI untuk periode ini telah terpenuhi. 
                Terima kasih atas minat dan antusiasme Anda.
            </p>

            <div class="stats-card">
                <div class="stat-item">
                    <i class="fas fa-user-check"></i>
                    <div>
                        <strong>{{ \App\Models\Pendaftaran::where('status_pendaftaran', 'diterima')->count() }}</strong>
                        <span>Anggota Diterima</span>
                    </div>
                </div>
                <div class="stat-item">
                    <i class="fas fa-clock"></i>
                    <div>
                        <strong>{{ \App\Models\Pendaftaran::where('status_pendaftaran', 'pending')->count() }}</strong>
                        <span>Menunggu Review</span>
                    </div>
                </div>
                <div class="stat-item">
                    <i class="fas fa-trophy"></i>
                    <div>
                        <strong>{{ \App\Models\SystemSetting::getSettings()->kuota }}</strong>
                        <span>Kuota Maksimal</span>
                    </div>
                </div>
            </div>

            <div class="next-period">
                <h4>Periode Berikutnya</h4>
                <p>Silakan pantau informasi untuk periode pendaftaran berikutnya melalui:</p>
                <div class="info-channels">
                    <div class="channel">
                        <i class="fas fa-globe"></i>
                        <span>Website Resmi</span>
                    </div>
                    <div class="channel">
                        <i class="fab fa-instagram"></i>
                        <span>Instagram</span>
                    </div>
                    <div class="channel">
                        <i class="fab fa-line"></i>
                        <span>LINE Official</span>
                    </div>
                </div>
            </div>

            <div class="action-buttons">
                <a href="{{ url('/') }}" class="btn-primary">
                    <i class="fas fa-home"></i> Kembali ke Beranda
                </a>
                <a href="{{ route('pendaftaran.check-status') }}" class="btn-outline">
                    <i class="fas fa-search"></i> Cek Status Pendaftaran
                </a>
            </div>
        </div>
    </div>
</section>

<style>
.quota-section {
    padding: 80px 0;
    background-color: var(--gray-light);
    min-height: 70vh;
}

.quota-card {
    background: var(--white);
    padding: 50px;
    border-radius: 16px;
    box-shadow: var(--shadow);
    text-align: center;
    max-width: 600px;
    margin: 0 auto;
}

.quota-icon {
    font-size: 5rem;
    color: #ffc107;
    margin-bottom: 25px;
}

.quota-card h2 {
    color: var(--primary-color);
    margin-bottom: 20px;
    font-size: 2rem;
}

.quota-message {
    color: var(--text-light);
    font-size: 1.1rem;
    line-height: 1.6;
    margin-bottom: 30px;
}

.stats-card {
    display: grid;
    grid-template-columns: repeat(3, 1fr);
    gap: 15px;
    margin: 30px 0;
}

.stat-item {
    background: var(--gray-light);
    padding: 20px;
    border-radius: 8px;
    text-align: center;
}

.stat-item i {
    font-size: 2rem;
    color: var(--primary-color);
    margin-bottom: 10px;
}

.stat-item strong {
    display: block;
    font-size: 1.5rem;
    color: var(--text-dark);
    margin-bottom: 5px;
}

.stat-item span {
    color: var(--text-light);
    font-size: 0.9rem;
}

.next-period {
    background: var(--gray-light);
    padding: 25px;
    border-radius: 12px;
    margin: 25px 0;
}

.next-period h4 {
    color: var(--primary-color);
    margin-bottom: 15px;
}

.info-channels {
    display: flex;
    justify-content: center;
    gap: 20px;
    margin-top: 20px;
}

.channel {
    display: flex;
    flex-direction: column;
    align-items: center;
    gap: 8px;
}

.channel i {
    font-size: 1.5rem;
    color: var(--primary-color);
}

.action-buttons {
    display: flex;
    gap: 15px;
    justify-content: center;
    margin-top: 30px;
}

.btn {
    padding: 12px 24px;
    border-radius: 6px;
    text-decoration: none;
    display: inline-flex;
    align-items: center;
    gap: 8px;
    transition: var(--transition);
    font-weight: 600;
}

.btn-primary {
    background: var(--primary-color);
    color: var(--white);
}

.btn-primary:hover {
    background: var(--primary-dark);
    transform: translateY(-2px);
}

.btn-outline {
    background: transparent;
    color: var(--primary-color);
    border: 2px solid var(--primary-color);
}

.btn-outline:hover {
    background: var(--primary-color);
    color: var(--white);
    transform: translateY(-2px);
}

@media (max-width: 768px) {
    .quota-card {
        padding: 30px 20px;
    }
    
    .stats-card {
        grid-template-columns: 1fr;
    }
    
    .info-channels {
        flex-direction: column;
        gap: 15px;
    }
    
    .action-buttons {
        flex-direction: column;
    }
}
</style>
@endsection