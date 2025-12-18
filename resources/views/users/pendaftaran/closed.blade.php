@extends('layouts.app')

@section('title', 'Pendaftaran Ditutup - HIMA-TI')

@section('content')
<link rel="stylesheet" href="{{ asset('css/glassmorphism.css') }}">

<style>
    /* Background & Overlay managed by app.blade.php */
    body::before {
        z-index: 0 !important;
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

    .closed-section {
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

    .closed-card {
        background: rgba(255, 255, 255, 0.18);
        backdrop-filter: blur(8px);
        border: 1px solid rgba(224, 231, 255, 0.30);
        border-radius: 16px;
        padding: 60px 40px;
        text-align: center;
        box-shadow: 0 8px 32px rgba(2, 6, 23, 0.15);
    }

    .closed-icon {
        font-size: 4rem;
        color: #FB923C;
        margin-bottom: 24px;
    }

    .closed-card h2 {
        color: #F8FAFC;
        font-weight: 700;
        font-size: 2rem;
        margin-bottom: 16px;
    }

    .closed-message {
        color: #CBD5E1;
        font-size: 1.1rem;
        margin-bottom: 32px;
        line-height: 1.7;
        max-width: 600px;
        margin-left: auto;
        margin-right: auto;
    }

    .info-card {
        background: rgba(99, 102, 241, 0.2);
        border: 1px solid rgba(99, 102, 241, 0.4);
        border-radius: 12px;
        padding: 24px;
        margin: 24px 0;
        color: #F8FAFC;
        backdrop-filter: blur(4px);
    }

    .info-card h4 {
        color: #FFFFFF;
        font-weight: 700;
        margin-bottom: 16px;
        font-size: 1.1rem;
    }

    .info-details {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
        gap: 16px;
    }

    .info-item {
        display: flex;
        align-items: flex-start;
        gap: 12px;
        text-align: left;
        color: #E0E7FF;
        font-weight: 500;
    }

    .info-item i {
        color: #A5B4FC;
        font-size: 1.25rem;
        margin-top: 2px;
        flex-shrink: 0;
    }

    .info-item strong {
        color: #FFFFFF;
        display: block;
        font-size: 0.875rem;
        font-weight: 700;
    }

    .info-item span {
        display: block;
        font-size: 0.95rem;
    }

    .countdown-card {
        background: rgba(34, 197, 94, 0.2);
        border: 1px solid rgba(34, 197, 94, 0.4);
        border-radius: 12px;
        padding: 24px;
        margin: 24px 0;
        text-align: center;
        color: #86EFAC;
        backdrop-filter: blur(4px);
    }

    .countdown-card h5 {
        color: #FFFFFF;
        margin-bottom: 16px;
        font-weight: 700;
        font-size: 1.1rem;
    }

    .countdown-timer {
        display: grid;
        grid-template-columns: repeat(4, 1fr);
        gap: 12px;
        margin: 16px 0;
    }

    .time-box {
        background: rgba(255, 255, 255, 0.15);
        border: 1px solid rgba(34, 197, 94, 0.4);
        border-radius: 8px;
        padding: 12px;
        backdrop-filter: blur(4px);
    }

    .time-value {
        font-size: 2rem;
        font-weight: 800;
        color: #86EFAC;
    }

    .time-label {
        font-size: 0.75rem;
        color: #E0E7FF;
        margin-top: 4px;
        font-weight: 500;
    }

    .btn-primary {
        background: #6366F1 !important;
        border-color: #6366F1 !important;
        padding: 12px 32px;
        font-weight: 600;
    }

    .btn-primary:hover {
        background: #4F46E5 !important;
        border-color: #4F46E5 !important;
    }

    @media (max-width: 768px) {
        .page-header h1 {
            font-size: 1.8rem;
        }

        .closed-card {
            padding: 40px 20px;
        }

        .countdown-timer {
            grid-template-columns: repeat(2, 1fr);
        }

        .time-value {
            font-size: 1.5rem;
        }
    }
</style>

<!-- Page Header -->
<section class="page-header">
    <div class="container">
        <h1>Pendaftaran Sedang Ditutup</h1>
        <p>Periode pendaftaran anggota HIMA-TI saat ini tidak aktif</p>
    </div>
</section>

<!-- Closed Message -->
<section class="closed-section">
    <div class="container">
        <div class="closed-card">
            <div class="closed-icon">
                <i class="fas fa-lock"></i>
            </div>
            <h2>Pendaftaran Tidak Tersedia</h2>
            <p class="closed-message">
                Maaf, pendaftaran anggota HIMA-TI sedang ditutup untuk saat ini. 
                Silakan pantau informasi terbaru melalui website atau media sosial kami.
            </p>

            @php
                $settings = \App\Models\SystemSetting::getSettings();
            @endphp

            @if($settings->tanggal_mulai > now())
            <div class="info-card">
                <h4>Periode Pendaftaran Berikutnya</h4>
                <div class="info-details">
                    <div class="info-item">
                        <i class="fas fa-calendar-plus"></i>
                        <div>
                            <strong>Mulai:</strong>
                            <span>{{ \Carbon\Carbon::parse($settings->tanggal_mulai)->format('d F Y') }}</span>
                        </div>
                    </div>
                    <div class="info-item">
                        <i class="fas fa-calendar-minus"></i>
                        <div>
                            <strong>Selesai:</strong>
                            <span>{{ \Carbon\Carbon::parse($settings->tanggal_selesai)->format('d F Y') }}</span>
                        </div>
                    </div>
                    <div class="info-item">
                        <i class="fas fa-users"></i>
                        <div>
                            <strong>Kuota:</strong>
                            <span>{{ $settings->kuota }} anggota</span>
                        </div>
                    </div>
                </div>
            </div>
            @endif

            <div class="countdown-card" id="countdownCard" style="display: none;">
                <h4>Pendaftaran Akan Dibuka Dalam:</h4>
                <div class="countdown-timer" id="countdownTimer">
                    <div class="countdown-item">
                        <span id="days">00</span>
                        <small>Hari</small>
                    </div>
                    <div class="countdown-item">
                        <span id="hours">00</span>
                        <small>Jam</small>
                    </div>
                    <div class="countdown-item">
                        <span id="minutes">00</span>
                        <small>Menit</small>
                    </div>
                    <div class="countdown-item">
                        <span id="seconds">00</span>
                        <small>Detik</small>
                    </div>
                </div>
            </div>

            <div class="contact-info">
                <h4>Butuh Informasi?</h4>
                <p>Hubungi kami untuk informasi lebih lanjut:</p>
                <div class="contact-methods">
                    <p><i class="fas fa-envelope"></i> pendaftaran@himati.ac.id</p>
                    <p><i class="fas fa-phone"></i> +62 812 3456 7890</p>
                    <p><i class="fas fa-globe"></i> www.himati.ac.id</p>
                </div>
            </div>

            <div class="action-buttons">
                <a href="{{ url('/') }}" class="btn btn-primary">
                    <i class="fas fa-home"></i> Kembali ke Beranda
                </a>
                <a href="{{ route('pendaftaran.check-status') }}" class="btn btn-outline">
                    <i class="fas fa-search"></i> Cek Status Pendaftaran
                </a>
            </div>
        </div>
    </div>
</section>

<style>
    /* Override old styles with glassmorphism */
    .contact-info {
        background: rgba(99, 102, 241, 0.2) !important;
        border: 1px solid rgba(99, 102, 241, 0.4) !important;
        backdrop-filter: blur(4px);
        padding: 24px;
        border-radius: 12px;
        margin: 24px 0;
    }

    .contact-info h4 {
        color: #FFFFFF;
        margin-bottom: 16px;
        font-weight: 700;
    }

    .contact-info p {
        color: #E0E7FF;
    }

    .contact-methods p {
        color: #CBD5E1 !important;
    }

    .contact-methods i {
        color: #A5B4FC;
    }

    .action-buttons {
        display: flex;
        gap: 12px;
        justify-content: center;
        margin-top: 32px;
        flex-wrap: wrap;
    }

    .btn-outline {
        background: rgba(99, 102, 241, 0.2) !important;
        border: 1px solid rgba(99, 102, 241, 0.4) !important;
        color: #A5B4FC !important;
        padding: 12px 24px !important;
        font-weight: 600 !important;
        border-radius: 8px !important;
        backdrop-filter: blur(4px);
        transition: all 0.3s ease;
    }

    .btn-outline:hover {
        background: rgba(99, 102, 241, 0.4) !important;
        border-color: #6366F1 !important;
        color: #FFFFFF !important;
    }
</style>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const startDate = new Date('{{ $settings->tanggal_mulai }}').getTime();
    const countdownCard = document.getElementById('countdownCard');
    
    // Show countdown if registration will open in the future
    if (startDate > Date.now()) {
        countdownCard.style.display = 'block';
        
        function updateCountdown() {
            const now = new Date().getTime();
            const distance = startDate - now;
            
            if (distance < 0) {
                countdownCard.innerHTML = '<h4>Pendaftaran Telah Dibuka!</h4>';
                return;
            }
            
            const days = Math.floor(distance / (1000 * 60 * 60 * 24));
            const hours = Math.floor((distance % (1000 * 60 * 60 * 24)) / (1000 * 60 * 60));
            const minutes = Math.floor((distance % (1000 * 60 * 60)) / (1000 * 60));
            const seconds = Math.floor((distance % (1000 * 60)) / 1000);
            
            document.getElementById('days').textContent = days.toString().padStart(2, '0');
            document.getElementById('hours').textContent = hours.toString().padStart(2, '0');
            document.getElementById('minutes').textContent = minutes.toString().padStart(2, '0');
            document.getElementById('seconds').textContent = seconds.toString().padStart(2, '0');
        }
        
        updateCountdown();
        setInterval(updateCountdown, 1000);
    }
});
</script>
@endsection