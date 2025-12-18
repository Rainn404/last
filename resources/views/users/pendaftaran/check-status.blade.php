@extends('layouts.app')

@section('title', 'Cek Status Pendaftaran - HIMA-TI')

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

    .check-status-section {
        padding: 60px 20px;
        min-height: 60vh;
        display: flex;
        align-items: center;
        justify-content: center;
    }

    .container {
        max-width: 1200px;
        margin: 0 auto;
        position: relative;
        z-index: 2;
    }

    .form-container {
        background: rgba(255, 255, 255, 0.18);
        backdrop-filter: blur(8px);
        border: 1px solid rgba(224, 231, 255, 0.30);
        border-radius: 16px;
        padding: 60px 40px;
        box-shadow: 0 8px 32px rgba(2, 6, 23, 0.15);
        max-width: 600px;
        margin: 0 auto;
    }

    .form-container h2 {
        color: #F8FAFC;
        font-weight: 700;
        font-size: 2rem;
        margin-bottom: 32px;
        text-align: center;
    }

    .form-group {
        margin-bottom: 24px;
    }

    .form-group label {
        display: block;
        color: #E0E7FF;
        font-weight: 600;
        margin-bottom: 8px;
    }

    .form-group input {
        width: 100%;
        background: rgba(255, 255, 255, 0.12);
        border: 1px solid rgba(224, 231, 255, 0.30);
        border-radius: 8px;
        color: #F8FAFC;
        padding: 12px 16px;
        font-size: 0.95rem;
        transition: all 0.3s ease;
    }

    .form-group input::placeholder {
        color: #94A3B8;
    }

    .form-group input:focus {
        outline: none;
        background: rgba(255, 255, 255, 0.20);
        border-color: #6366F1;
        box-shadow: 0 0 0 3px rgba(99, 102, 241, 0.15);
    }

    .error-message {
        display: block;
        color: #FCA5A5;
        font-size: 0.85rem;
        margin-top: 4px;
    }

    .alert {
        background: rgba(99, 102, 241, 0.15);
        border: 1px solid rgba(99, 102, 241, 0.3);
        border-radius: 8px;
        padding: 16px;
        margin-bottom: 24px;
        color: #E0E7FF;
        display: flex;
        align-items: center;
        gap: 12px;
    }

    .alert-error {
        background: rgba(239, 68, 68, 0.15) !important;
        border-color: rgba(239, 68, 68, 0.3) !important;
        color: #FCA5A5 !important;
    }

    .alert i {
        font-size: 1.25rem;
    }

    .btn-submit {
        width: 100%;
        background: #6366F1;
        color: #FFFFFF;
        border: none;
        padding: 12px 24px;
        border-radius: 8px;
        font-weight: 600;
        font-size: 0.95rem;
        transition: all 0.3s ease;
        cursor: pointer;
        display: flex;
        align-items: center;
        justify-content: center;
        gap: 8px;
    }

    .btn-submit:hover {
        background: #4F46E5;
        box-shadow: 0 6px 20px rgba(99, 102, 241, 0.35);
        transform: translateY(-2px);
    }

    .additional-info {
        background: rgba(59, 130, 246, 0.15);
        border: 1px solid rgba(59, 130, 246, 0.3);
        border-radius: 12px;
        padding: 24px;
        margin-top: 32px;
        color: #E0E7FF;
    }

    .additional-info h3 {
        color: #F8FAFC;
        font-weight: 700;
        margin-bottom: 16px;
    }

    .additional-info ul {
        list-style: none;
        padding: 0;
        margin: 0;
    }

    .additional-info li {
        color: #CBD5E1;
        padding: 8px 0;
        padding-left: 24px;
        position: relative;
    }

    .additional-info li::before {
        content: '✓';
        position: absolute;
        left: 0;
        color: #6366F1;
        font-weight: 700;
    }

    @media (max-width: 768px) {
        .page-header h1 {
            font-size: 1.8rem;
        }

        .form-container {
            padding: 40px 20px;
        }

        .form-container h2 {
            font-size: 1.5rem;
        }
    }
</style>

    <!-- Page Header -->
    <section class="page-header">
        <div class="container">
            <h1>Cek Status Pendaftaran</h1>
            <p>Pantau progress pendaftaran Anda dengan NIM</p>
        </div>
    </section>

    <!-- Check Status Form -->
    <section class="check-status-section">
        <div class="container">
            <div class="form-container">
                <h2>Masukkan NIM Anda</h2>
                
                @if(session('error'))
                    <div class="alert alert-error">
                        <i class="fas fa-exclamation-circle"></i> {{ session('error') }}
                    </div>
                @endif

                <form action="{{ route('pendaftaran.check-status') }}" method="POST" class="check-status-form">
                    @csrf
                    
                    <div class="form-group">
                        <label for="nim">Nomor Induk Mahasiswa (NIM) *</label>
                        <input type="text" id="nim" name="nim" value="{{ old('nim') }}" required 
                               placeholder="Masukkan NIM Anda">
                        @error('nim')
                            <span class="error-message">{{ $message }}</span>
                        @enderror
                    </div>

                    <button type="submit" class="btn-submit">
                        <i class="fas fa-search"></i> Cek Status
                    </button>
                </form>

                <div class="additional-info">
                    <h3>Informasi</h3>
                    <ul>
                        <li>Pastikan NIM yang dimasukkan sudah benar</li>
                        <li>Status akan menampilkan progress terbaru pendaftaran Anda</li>
                        <li>Jika mengalami kendala, hubungi admin di pendaftaran@himati.ac.id</li>
                    </ul>
                </div>
            </div>
        </div>
    </section>

    <style>
    .check-status-section {
        padding: 80px 0;
        background-color: var(--gray-light);
        min-height: 60vh;
    }

    .form-container {
        background: var(--white);
        padding: 40px;
        border-radius: 12px;
        box-shadow: var(--shadow);
        max-width: 500px;
        margin: 0 auto;
    }

    .form-container h2 {
        color: var(--primary-color);
        margin-bottom: 30px;
        text-align: center;
    }

    .check-status-form .form-group {
        margin-bottom: 25px;
    }

    .check-status-form label {
        display: block;
        margin-bottom: 8px;
        font-weight: 600;
        color: var(--text-dark);
    }

    .check-status-form input {
        width: 100%;
        padding: 12px 15px;
        border: 1px solid #ddd;
        border-radius: 6px;
        font-size: 1rem;
        transition: var(--transition);
    }

    .check-status-form input:focus {
        outline: none;
        border-color: var(--primary-color);
        box-shadow: 0 0 0 3px rgba(26, 115, 232, 0.1);
    }

    .btn-submit {
        width: 100%;
        padding: 15px;
        font-size: 1.1rem;
        font-weight: 600;
    }

    .additional-info {
        margin-top: 30px;
        padding-top: 20px;
        border-top: 1px solid #eee;
    }

    .additional-info h3 {
        color: var(--primary-color);
        margin-bottom: 15px;
    }

    .additional-info ul {
        list-style: none;
        padding-left: 0;
    }

    .additional-info li {
        padding: 8px 0;
        padding-left: 25px;
        position: relative;
        color: var(--text-light);
    }

    .additional-info li::before {
        content: '•';
        position: absolute;
        left: 0;
        color: var(--primary-color);
        font-weight: bold;
    }

    @media (max-width: 576px) {
        .form-container {
            padding: 25px;
        }
        
        .check-status-section {
            padding: 40px 0;
        }
    }
    </style>
@endsection