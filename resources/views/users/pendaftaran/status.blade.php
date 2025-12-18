@extends('layouts.app')

@section('title', 'Status Pendaftaran - HIMA-TI')

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

    .container {
        max-width: 1200px;
        margin: 0 auto;
        padding: 0 20px;
        position: relative;
        z-index: 2;
    }

    /* Page Header */
    .page-header {
        background: rgba(99, 102, 241, 0.2);
        backdrop-filter: blur(8px);
        border-bottom: 1px solid rgba(224, 231, 255, 0.30);
        padding: 3rem 0;
        text-align: center;
        color: #F8FAFC;
    }

    .page-header h1 {
        font-size: 2.5rem;
        font-weight: 700;
        margin-bottom: 1rem;
        color: #F8FAFC;
    }

    .page-header p {
        font-size: 1.1rem;
        opacity: 0.9;
        max-width: 600px;
        margin: 0 auto;
        line-height: 1.6;
        color: #CBD5E1;
    }
</style>

    <!-- Page Header -->
    <section class="page-header">
        <div class="container">
            <h1>Status Pendaftaran Anda</h1>
            <p>Pantau progress pendaftaran Anda menjadi anggota HIMA-TI</p>
        </div>
    </section>

    <!-- Thank You Message -->
    <section class="thank-you-section">
        <div class="container">
            <div class="thank-you-card">
                <div class="success-icon">
                    <i class="fas fa-check-circle"></i>
                </div>
                <h2>Terima Kasih Telah Mendaftar!</h2>
                <p class="thank-you-message">
                    Pendaftaran Anda sebagai calon anggota HIMA-TI telah berhasil kami terima. 
                    Silakan pantau progress pendaftaran Anda di bawah ini.
                </p>
                
                <div class="registration-info">
                    <div class="info-item">
                        <strong>Nama:</strong> {{ $pendaftaran->nama }}
                    </div>
                    <div class="info-item">
                        <strong>NIM:</strong> {{ $pendaftaran->nim }}
                    </div>
                    <div class="info-item">
                        <strong>Tanggal Pendaftaran:</strong> {{ $pendaftaran->submitted_at->format('d F Y H:i') }}
                    </div>
                    <div class="info-item">
                        <strong>Nomor Pendaftaran:</strong> 
                        <span class="registration-number">REG{{ $pendaftaran->id_pendaftaran }}{{ $pendaftaran->created_at->format('Ymd') }}</span>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Progress Tracking -->
    <section class="progress-section">
        <div class="container">
            <h2 class="section-title">Progress Pendaftaran</h2>
            <div class="progress-tracker">
                <div class="progress-step completed">
                    <div class="step-icon">
                        <i class="fas fa-clipboard-check"></i>
                    </div>
                    <div class="step-content">
                        <h3>Pendaftaran Diterima</h3>
                        <p>Formulir pendaftaran telah berhasil dikirim</p>
                        <span class="step-date">{{ $pendaftaran->submitted_at->format('d M Y') }}</span>
                    </div>
                    <div class="step-status">
                        <span class="status-badge completed">Selesai</span>
                    </div>
                </div>

                <div class="progress-step {{ $pendaftaran->status_pendaftaran != 'pending' ? 'completed' : 'active' }}">
                    <div class="step-icon">
                        <i class="fas fa-search"></i>
                    </div>
                    <div class="step-content">
                        <h3>Verifikasi Data</h3>
                        <p>Tim HIMA-TI sedang memverifikasi data dan dokumen Anda</p>
                        <span class="step-date">
                            @if($pendaftaran->status_pendaftaran != 'pending')
                                {{ $pendaftaran->updated_at->format('d M Y') }}
                            @else
                                Estimasi: 1-3 hari kerja
                            @endif
                        </span>
                    </div>
                    <div class="step-status">
                        @if($pendaftaran->status_pendaftaran == 'pending')
                            <span class="status-badge pending">Dalam Proses</span>
                        @else
                            <span class="status-badge completed">Selesai</span>
                        @endif
                    </div>
                </div>

                <div class="progress-step {{ $pendaftaran->status_pendaftaran == 'diterima' ? 'active' : '' }}">
                    <div class="step-icon">
                        <i class="fas fa-comments"></i>
                    </div>
                    <div class="step-content">
                        <h3>Proses Interview</h3>
                        <p>Wawancara untuk mengenal minat dan bakat Anda</p>
                        <span class="step-date">
                            @if($pendaftaran->status_pendaftaran == 'diterima')
                                Akan dijadwalkan
                            @else
                                Menunggu verifikasi
                            @endif
                        </span>
                    </div>
                    <div class="step-status">
                        @if($pendaftaran->status_pendaftaran == 'diterima')
                            <span class="status-badge active">Akan Datang</span>
                        @else
                            <span class="status-badge pending">Menunggu</span>
                        @endif
                    </div>
                </div>

                <div class="progress-step">
                    <div class="step-icon">
                        <i class="fas fa-bell"></i>
                    </div>
                    <div class="step-content">
                        <h3>Pengumuman Hasil</h3>
                        <p>Hasil seleksi akan dikirim via email dan website</p>
                        <span class="step-date">
                            @if($pendaftaran->status_pendaftaran == 'ditolak')
                                {{ $pendaftaran->updated_at->format('d M Y') }}
                            @else
                                Estimasi: 1-2 minggu
                            @endif
                        </span>
                    </div>
                    <div class="step-status">
                        @if($pendaftaran->status_pendaftaran == 'ditolak')
                            <span class="status-badge completed">Selesai</span>
                        @else
                            <span class="status-badge pending">Menunggu</span>
                        @endif
                    </div>
                </div>
            </div>

            <!-- Current Status Card -->
            <div class="current-status-card">
                <h3>Status Saat Ini</h3>
                <div class="status-content">
                    @if($pendaftaran->status_pendaftaran == 'pending')
                        <div class="status-info pending">
                            <i class="fas fa-clock"></i>
                            <div>
                                <h4>Menunggu Verifikasi</h4>
                                <p>Data pendaftaran Anda sedang dalam proses verifikasi oleh tim HIMA-TI. Silakan tunggu informasi selanjutnya.</p>
                            </div>
                        </div>
                    @elseif($pendaftaran->status_pendaftaran == 'diterima')
                        <div class="status-info accepted">
                            <i class="fas fa-check-circle"></i>
                            <div>
                                <h4>Lolos Verifikasi</h4>
                                <p>Selamat! Data Anda telah diverifikasi. Tim kami akan menghubungi Anda untuk jadwal wawancara.</p>
                            </div>
                        </div>
                    @else
                        <div class="status-info rejected">
                            <i class="fas fa-times-circle"></i>
                            <div>
                                <h4>Tidak Lolos Seleksi</h4>
                                <p>Maaf, Anda tidak lolos dalam proses seleksi kali ini. Tetap semangat dan silakan coba lagi di periode berikutnya.</p>
                            </div>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </section>

    <!-- Next Steps -->
    <section class="next-steps-section">
        <div class="container">
            <h2 class="section-title">Langkah Selanjutnya</h2>
            <div class="steps-grid">
                <div class="step-card">
                    <div class="step-icon">
                        <i class="fas fa-envelope"></i>
                    </div>
                    <h3>Pantau Email</h3>
                    <p>Periksa email Anda secara berkala untuk informasi terbaru mengenai proses seleksi</p>
                </div>
                <div class="step-card">
                    <div class="step-icon">
                        <i class="fas fa-phone"></i>
                    </div>
                    <h3>Siapkan Diri</h3>
                    <p>Persiapkan diri untuk proses wawancara jika lolos verifikasi data</p>
                </div>
                <div class="step-card">
                    <div class="step-icon">
                        <i class="fas fa-calendar"></i>
                    </div>
                    <h3>Tunggu Jadwal</h3>
                    <p>Tim kami akan mengirimkan jadwal wawancara via email dan WhatsApp</p>
                </div>
                <div class="step-card">
                    <div class="step-icon">
                        <i class="fas fa-question-circle"></i>
                    </div>
                    <h3>Butuh Bantuan?</h3>
                    <p>Hubungi kami di pendaftaran@himati.ac.id atau +62 812 3456 7890</p>
                </div>
            </div>
        </div>
    </section>

    <!-- Contact Info -->
    <section class="contact-section">
        <div class="container">
            <div class="contact-card">
                <h3>Butuh Informasi Lebih Lanjut?</h3>
                <p>Jangan ragu untuk menghubungi kami jika Anda memiliki pertanyaan mengenai proses pendaftaran</p>
                <div class="contact-methods">
                    <div class="contact-method">
                        <i class="fas fa-envelope"></i>
                        <div>
                            <strong>Email</strong>
                            <p>pendaftaran@himati.ac.id</p>
                        </div>
                    </div>
                    <div class="contact-method">
                        <i class="fas fa-phone"></i>
                        <div>
                            <strong>Telepon</strong>
                            <p>+62 812 3456 7890</p>
                        </div>
                    </div>
                    <div class="contact-method">
                        <i class="fas fa-clock"></i>
                        <div>
                            <strong>Jam Operasional</strong>
                            <p>Senin - Jumat, 08:00 - 16:00 WIB</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <style>
    /* Thank You Section */
    .thank-you-section {
        padding: 60px 0;
        background: transparent;
    }

    .thank-you-card {
        background: rgba(255, 255, 255, 0.18);
        backdrop-filter: blur(8px);
        padding: 40px;
        border-radius: 16px;
        text-align: center;
        border: 1px solid rgba(224, 231, 255, 0.30);
        box-shadow: 0 8px 32px rgba(2, 6, 23, 0.15);
        max-width: 800px;
        margin: 0 auto;
        color: #F8FAFC;
    }

    .success-icon {
        font-size: 4rem;
        color: #86EFAC;
        margin-bottom: 20px;
    }

    .thank-you-card h2 {
        margin-bottom: 15px;
        font-size: 2rem;
        color: #F8FAFC;
    }

    .thank-you-message {
        font-size: 1.1rem;
        line-height: 1.6;
        margin-bottom: 30px;
        opacity: 0.9;
        color: #CBD5E1;
    }

    .registration-info {
        background: rgba(255, 255, 255, 0.1);
        padding: 25px;
        border-radius: 12px;
        text-align: left;
        margin-top: 20px;
        border: 1px solid rgba(224, 231, 255, 0.30);
    }

    .info-item {
        display: flex;
        justify-content: space-between;
        align-items: center;
        padding: 10px 0;
        border-bottom: 1px solid rgba(224, 231, 255, 0.30);
        color: #CBD5E1;
    }

    .info-item:last-child {
        border-bottom: none;
    }

    .info-item strong {
        color: #F8FAFC;
    }

    .registration-number {
        background: rgba(99, 102, 241, 0.3);
        color: #A5B4FC;
        padding: 0.25rem 0.75rem;
        border-radius: 4px;
    }

        color: var(--primary-color);
        padding: 5px 10px;
        border-radius: 6px;
        font-weight: bold;
        font-family: 'Courier New', monospace;
    }

    /* Progress Section */
    .progress-section {
        padding: 80px 0;
        background-color: var(--white);
    }

    .progress-tracker {
        max-width: 800px;
        margin: 0 auto 50px;
    }

    .progress-step {
        display: flex;
        align-items: center;
        padding: 25px;
        margin-bottom: 20px;
        background: var(--white);
        border-radius: 12px;
        box-shadow: var(--shadow);
        border-left: 4px solid #ddd;
        transition: var(--transition);
        position: relative;
    }

    .progress-step.active {
        border-left-color: var(--primary-color);
        background: linear-gradient(135deg, #f8f9ff 0%, #e3f2fd 100%);
    }

    .progress-step.completed {
        border-left-color: #28a745;
    }

    .step-icon {
        width: 60px;
        height: 60px;
        background: var(--gray-light);
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        margin-right: 20px;
        font-size: 1.5rem;
        color: var(--text-light);
    }

    .progress-step.active .step-icon {
        background: var(--primary-color);
        color: var(--white);
    }

    .progress-step.completed .step-icon {
        background: #28a745;
        color: var(--white);
    }

    .step-content {
        flex: 1;
    }

    .step-content h3 {
        margin-bottom: 5px;
        color: var(--text-dark);
    }

    .step-content p {
        color: var(--text-light);
        margin-bottom: 5px;
    }

    .step-date {
        font-size: 0.8rem;
        color: var(--text-light);
        font-style: italic;
    }

    .step-status {
        margin-left: 20px;
    }

    .status-badge {
        padding: 6px 12px;
        border-radius: 20px;
        font-size: 0.8rem;
        font-weight: 600;
        text-transform: uppercase;
    }

    .status-badge.completed {
        background: #d4edda;
        color: #155724;
    }

    .status-badge.pending {
        background: #fff3cd;
        color: #856404;
    }

    .status-badge.active {
        background: #cce7ff;
        color: var(--primary-dark);
    }

    /* Current Status Card */
    .current-status-card {
        background: var(--white);
        padding: 30px;
        border-radius: 12px;
        box-shadow: var(--shadow);
        max-width: 600px;
        margin: 0 auto;
        border-top: 4px solid var(--primary-color);
    }

    .current-status-card h3 {
        color: var(--primary-color);
        margin-bottom: 20px;
        text-align: center;
    }

    .status-info {
        display: flex;
        align-items: flex-start;
        gap: 15px;
        padding: 20px;
        border-radius: 8px;
    }

    .status-info.pending {
        background: #fff3cd;
        border-left: 4px solid #ffc107;
    }

    .status-info.accepted {
        background: #d4edda;
        border-left: 4px solid #28a745;
    }

    .status-info.rejected {
        background: #f8d7da;
        border-left: 4px solid #dc3545;
    }

    .status-info i {
        font-size: 1.5rem;
        margin-top: 5px;
    }

    .status-info.pending i {
        color: #ffc107;
    }

    .status-info.accepted i {
        color: #28a745;
    }

    .status-info.rejected i {
        color: #dc3545;
    }

    .status-info h4 {
        margin-bottom: 5px;
        color: var(--text-dark);
    }

    .status-info p {
        margin: 0;
        color: var(--text-light);
    }

    /* Next Steps Section */
    .next-steps-section {
        padding: 80px 0;
        background-color: var(--gray-light);
    }

    .steps-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
        gap: 30px;
        margin-top: 40px;
    }

    .step-card {
        background: var(--white);
        padding: 30px;
        border-radius: 12px;
        box-shadow: var(--shadow);
        text-align: center;
        transition: var(--transition);
    }

    .step-card:hover {
        transform: translateY(-5px);
    }

    .step-card .step-icon {
        width: 70px;
        height: 70px;
        background: var(--primary-light);
        color: var(--primary-color);
        margin: 0 auto 20px;
    }

    .step-card h3 {
        color: var(--primary-color);
        margin-bottom: 10px;
    }

    .step-card p {
        color: var(--text-light);
        line-height: 1.5;
    }

    /* Contact Section */
    .contact-section {
        padding: 60px 0;
        background-color: var(--white);
    }

    .contact-card {
        background: var(--gray-light);
        padding: 40px;
        border-radius: 12px;
        text-align: center;
        max-width: 600px;
        margin: 0 auto;
    }

    .contact-card h3 {
        color: var(--primary-color);
        margin-bottom: 15px;
    }

    .contact-card > p {
        color: var(--text-light);
        margin-bottom: 30px;
    }

    .contact-methods {
        display: flex;
        flex-direction: column;
        gap: 20px;
    }

    .contact-method {
        display: flex;
        align-items: center;
        gap: 15px;
        padding: 15px;
        background: var(--white);
        border-radius: 8px;
        box-shadow: var(--shadow);
    }

    .contact-method i {
        width: 40px;
        height: 40px;
        background: var(--primary-color);
        color: var(--white);
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 1.2rem;
    }

    .contact-method strong {
        display: block;
        color: var(--text-dark);
        margin-bottom: 2px;
    }

    .contact-method p {
        margin: 0;
        color: var(--text-light);
    }

    /* Responsive Design */
    @media (max-width: 768px) {
        .thank-you-card,
        .current-status-card,
        .contact-card {
            padding: 25px;
        }

        .progress-step {
            flex-direction: column;
            text-align: center;
            padding: 20px;
        }

        .step-icon {
            margin-right: 0;
            margin-bottom: 15px;
        }

        .step-status {
            margin-left: 0;
            margin-top: 15px;
        }

        .info-item {
            flex-direction: column;
            align-items: flex-start;
            gap: 5px;
        }

        .steps-grid {
            grid-template-columns: 1fr;
        }

        .contact-method {
            flex-direction: column;
            text-align: center;
        }
    }

    @media (max-width: 576px) {
        .thank-you-section,
        .progress-section,
        .next-steps-section,
        .contact-section {
            padding: 40px 0;
        }

        .thank-you-card h2 {
            font-size: 1.5rem;
        }

        .success-icon {
            font-size: 3rem;
        }
    }
    </style>
@endsection