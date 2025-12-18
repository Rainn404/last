@extends('layouts.app')

@section('title', 'Ajukan Prestasi - HIMA-TI')

@section('content')
<link rel="stylesheet" href="{{ asset('css/glassmorphism.css') }}">

<style>
    /* Background & Overlay managed by app.blade.php */
    body::before {
        z-index: 0 !important;
    }

    .container-fluid {
        position: relative;
        z-index: 2;
        min-height: 100vh;
    }

    .header-glass {
        background: rgba(255, 255, 255, 0.12);
        backdrop-filter: blur(8px);
        border-bottom: 1px solid rgba(224, 231, 255, 0.25);
        border-radius: 12px;
        padding: 24px;
        margin-bottom: 24px;
        margin-top: 70px;
    }

    .header-glass h1 {
        color: #F8FAFC;
        text-shadow: 0 2px 4px rgba(2, 6, 23, 0.2);
    }

    .header-glass p {
        color: #CBD5E1;
    }

    .card-glass {
        background: rgba(255, 255, 255, 0.18);
        backdrop-filter: blur(8px);
        border: 1px solid rgba(224, 231, 255, 0.30);
        border-radius: 16px;
        box-shadow: 0 8px 32px rgba(2, 6, 23, 0.15);
        color: #F8FAFC;
    }

    .card-glass .card-header {
        background: rgba(99, 102, 241, 0.2) !important;
        border-bottom: 1px solid rgba(99, 102, 241, 0.3) !important;
        color: #F8FAFC !important;
    }

    .card-glass .card-body {
        color: #E0E7FF;
    }

    .card-glass .card-body label {
        color: #FFFFFF;
        font-weight: 700;
        font-size: 0.95rem;
    }

    .card-glass input,
    .card-glass select,
    .card-glass textarea {
        background: rgba(15, 23, 42, 0.6) !important;
        border: 1px solid rgba(224, 231, 255, 0.20) !important;
        color: #F8FAFC !important;
        backdrop-filter: blur(6px);
        font-weight: 500;
    }

    .card-glass input::placeholder,
    .card-glass textarea::placeholder {
        color: #64748B !important;
        font-weight: 400;
    }

    .card-glass input:focus,
    .card-glass select:focus,
    .card-glass textarea:focus {
        outline: none;
        background: rgba(15, 23, 42, 0.8) !important;
        border-color: #6366F1 !important;
        box-shadow: 0 0 0 3px rgba(99, 102, 241, 0.15) !important;
        color: #FFFFFF !important;
    }

    .btn-primary {
        background: #6366F1 !important;
        border-color: #6366F1 !important;
        box-shadow: 0 4px 12px rgba(99, 102, 241, 0.25);
    }

    .btn-primary:hover {
        background: #4F46E5 !important;
        border-color: #4F46E5 !important;
        box-shadow: 0 6px 20px rgba(99, 102, 241, 0.35);
    }

    .btn-outline-primary {
        color: #6366F1;
        border-color: rgba(99, 102, 241, 0.3);
        background: rgba(99, 102, 241, 0.1);
    }

    .btn-outline-primary:hover {
        color: #FFF;
        background: #6366F1;
        border-color: #6366F1;
    }

    .alert-danger {
        background: rgba(239, 68, 68, 0.15) !important;
        border: 1px solid rgba(239, 68, 68, 0.3) !important;
        color: #FCA5A5 !important;
    }

    .steps {
        display: flex;
        justify-content: space-between;
        position: relative;
        z-index: 1;
    }

    .step {
        display: flex;
        flex-direction: column;
        align-items: center;
        flex: 1;
    }

    .step-number {
        width: 40px;
        height: 40px;
        border-radius: 50%;
        background: rgba(99, 102, 241, 0.2);
        border: 2px solid rgba(99, 102, 241, 0.5);
        color: #A5B4FC;
        display: flex;
        align-items: center;
        justify-content: center;
        font-weight: 700;
        margin-bottom: 8px;
    }

    .step.active .step-number {
        background: #6366F1;
        color: #FFFFFF;
        border-color: #6366F1;
    }

    .step-label {
        color: #E0E7FF;
        font-weight: 600;
        font-size: 0.875rem;
    }

    .step.active .step-label {
        color: #FFFFFF;
        font-weight: 700;
    }

    option {
        background: #1E293B;
        color: #F8FAFC;
    }

    .text-muted {
        color: #CBD5E1 !important;
        font-weight: 500 !important;
    }

    .badge {
        background: rgba(99, 102, 241, 0.25) !important;
        color: #FFFFFF !important;
        border: 1px solid rgba(99, 102, 241, 0.4);
    }

    h5, h6 {
        color: #FFFFFF;
        font-weight: 700;
    }

    table {
        color: #E0E7FF;
    }

    table th {
        color: #FFFFFF;
        font-weight: 700;
        background: rgba(99, 102, 241, 0.2);
    }

    /* Alert styling - glassmorphism */
    .alert {
        background: rgba(255, 255, 255, 0.15) !important;
        border: 1px solid rgba(224, 231, 255, 0.30) !important;
        color: #E0E7FF !important;
        backdrop-filter: blur(4px);
        border-radius: 12px;
    }

    .alert-info {
        background: rgba(59, 130, 246, 0.15) !important;
        border-color: rgba(59, 130, 246, 0.3) !important;
        color: #BFDBFE !important;
    }

    .alert-info strong {
        color: #FFFFFF;
    }

    /* Mahasiswa card styling */
    .mahasiswa-item {
        background: rgba(15, 23, 42, 0.5) !important;
        backdrop-filter: blur(8px);
        border: 1px solid rgba(224, 231, 255, 0.20) !important;
        border-radius: 16px !important;
        box-shadow: 0 4px 12px rgba(2, 6, 23, 0.3);
        color: #E0E7FF;
    }

    .mahasiswa-item .card-header {
        background: rgba(99, 102, 241, 0.15) !important;
        border-bottom: 1px solid rgba(99, 102, 241, 0.2) !important;
        color: #FFFFFF !important;
        border-radius: 16px 16px 0 0 !important;
    }

    .mahasiswa-item .card-body {
        background: transparent !important;
        color: #E0E7FF;
    }

    .mahasiswa-item .form-label {
        color: #FFFFFF;
        font-weight: 700;
    }

    .mahasiswa-item .form-control,
    .mahasiswa-item .form-select {
        background: rgba(15, 23, 42, 0.6) !important;
        border: 1px solid rgba(224, 231, 255, 0.20) !important;
        color: #FFFFFF !important;
        backdrop-filter: blur(6px);
    }

    .mahasiswa-item .form-control::placeholder {
        color: #64748B !important;
    }

    .mahasiswa-item .form-control:focus,
    .mahasiswa-item .form-select:focus {
        background: rgba(15, 23, 42, 0.8) !important;
        border-color: #6366F1 !important;
        box-shadow: 0 0 0 3px rgba(99, 102, 241, 0.15) !important;
        color: #FFFFFF !important;
    }

    .mahasiswa-item .input-group-text {
        display: none !important;
    }

    .section-header {
        border-bottom: 2px solid rgba(99, 102, 241, 0.3);
        padding-bottom: 16px;
    }

    .step-badge {
        min-width: 40px;
        height: 40px;
        display: flex !important;
        align-items: center;
        justify-content: center;
        font-weight: 700;
    }

    .form-step {
        display: none;
    }

    .form-step.active {
        display: block;
    }
</style>

<div class="container-fluid py-4">
    <div class="row justify-content-center">
        <div class="col-12 col-xl-10">
            <!-- Header -->
            <div class="header-glass d-flex justify-content-between align-items-center">
                <div>
                    <h1 class="h3 mb-2">
                        <i class="fas fa-trophy me-2"></i>
                        Form Pengajuan Prestasi
                    </h1>
                    <p class="mb-0">Isi form berikut untuk mengajukan prestasi</p>
                </div>
                <a href="{{ route('prestasi.index') }}" class="btn btn-outline-primary">
                    <i class="fas fa-arrow-left me-2"></i>Kembali ke Daftar
                </a>
            </div>

            <!-- Progress Steps -->
            <div class="card-glass mb-4">
                <div class="card-body py-3">
                    <div class="steps">
                        <div class="step active">
                            <div class="step-number">1</div>
                            <div class="step-label">Data Mahasiswa</div>
                        </div>
                        <div class="step">
                            <div class="step-number">2</div>
                            <div class="step-label">Prestasi</div>
                        </div>
                        <div class="step">
                            <div class="step-number">3</div>
                            <div class="step-label">Dokumen</div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Main Form Card -->
            <div class="card-glass shadow-lg">
                <div class="card-header bg-primary text-white py-3">
                    <h5 class="mb-0">
                        <i class="fas fa-clipboard-list me-2"></i>
                        Data Prestasi
                    </h5>
                </div>
                
                <div class="card-body p-4">
                    @if ($errors->any())
                    <div class="alert alert-danger alert-dismissible fade show mb-4">
                        <div class="d-flex align-items-center">
                            <i class="fas fa-exclamation-triangle me-3 fs-4"></i>
                            <div>
                                <h6 class="mb-1">Terjadi kesalahan:</h6>
                                <ul class="mb-0 ps-3">
                                    @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                    @endforeach
                                </ul>
                            </div>
                        </div>
                        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                    </div>
                    @endif

                    <form action="{{ route('prestasi.store') }}" method="POST" enctype="multipart/form-data" id="prestasiForm">
                        @csrf

                        <!-- Step 1: Data Mahasiswa -->
                        <div class="form-step active" data-step="1">
                            <div class="row">
                                <div class="col-12 mb-4">
                                    <div class="section-header d-flex align-items-center mb-4">
                                        <span class="step-badge bg-primary text-white rounded-circle me-3">1</span>
                                        <h5 class="mb-0 text-primary">
                                            <i class="fas fa-users me-2"></i>Data Mahasiswa
                                        </h5>
                                    </div>
                                </div>
                            </div>

                            <div class="alert alert-info mb-4">
                                <div class="d-flex">
                                    <i class="fas fa-info-circle mt-1 me-3 fs-5"></i>
                                    <div>
                                        <strong>Informasi</strong>
                                        <p class="mb-0 mt-1">
                                            Tambahkan mahasiswa yang terlibat dalam prestasi ini. 
                                            Minimal 1 mahasiswa, maksimal 10 mahasiswa.
                                        </p>
                                    </div>
                                </div>
                            </div>

                            <!-- Mahasiswa Container -->
                            <div id="mahasiswaContainer">
                                <!-- Mahasiswa 1 -->
                                <div class="mahasiswa-item card mb-4" data-index="0">
                                    <div class="card-header bg-light d-flex justify-content-between align-items-center py-3">
                                        <h6 class="mb-0 fw-semibold">
                                            <i class="fas fa-user-graduate me-2 text-primary"></i>Mahasiswa 1
                                        </h6>
                                        <button type="button" class="btn btn-sm btn-outline-danger remove-mahasiswa d-none">
                                            <i class="fas fa-times me-1"></i>Hapus
                                        </button>
                                    </div>
                                    <div class="card-body">
                                        <div class="row">
                                            <div class="col-md-6 mb-3">
                                                <label class="form-label fw-semibold">Nama Lengkap <span class="text-danger">*</span></label>
                                                <div class="input-group">
                                                    <span class="input-group-text bg-light border-end-0">
                                                        <i class="fas fa-user text-muted"></i>
                                                    </span>
                                                    <input type="text" class="form-control" 
                                                           name="mahasiswa[0][nama]" 
                                                           value="{{ old('mahasiswa.0.nama', auth()->user()->name ?? '') }}" 
                                                           placeholder="Masukkan nama lengkap" required>
                                                </div>
                                                @error('mahasiswa.0.nama')
                                                <div class="invalid-feedback d-block">{{ $message }}</div>
                                                @enderror
                                            </div>

                                            <div class="col-md-6 mb-3">
                                                <label class="form-label fw-semibold">NIM <span class="text-danger">*</span></label>
                                                <div class="input-group">
                                                    <span class="input-group-text bg-light border-end-0">
                                                        <i class="fas fa-id-card text-muted"></i>
                                                    </span>
                                                    <input type="text" class="form-control" 
                                                           name="mahasiswa[0][nim]" 
                                                           value="{{ old('mahasiswa.0.nim', auth()->user()->nim ?? '') }}" 
                                                           placeholder="Masukkan NIM" required maxlength="15">
                                                </div>
                                                @error('mahasiswa.0.nim')
                                                <div class="invalid-feedback d-block">{{ $message }}</div>
                                                @enderror
                                            </div>
                                        </div>

                                        <div class="row">
                                            <div class="col-md-6 mb-3">
                                                <label class="form-label fw-semibold">Email <span class="text-danger">*</span></label>
                                                <div class="input-group">
                                                    <span class="input-group-text bg-light border-end-0">
                                                        <i class="fas fa-envelope text-muted"></i>
                                                    </span>
                                                    <input type="email" class="form-control" 
                                                           name="mahasiswa[0][email]" 
                                                           value="{{ old('mahasiswa.0.email', auth()->user()->email ?? '') }}" 
                                                           placeholder="nama@contoh.com" required>
                                                </div>
                                                @error('mahasiswa.0.email')
                                                <div class="invalid-feedback d-block">{{ $message }}</div>
                                                @enderror
                                            </div>

                                            <div class="col-md-6 mb-3">
                                                <label class="form-label fw-semibold">No. HP/WhatsApp <span class="text-danger">*</span></label>
                                                <div class="input-group">
                                                    <span class="input-group-text bg-light border-end-0">
                                                        <i class="fas fa-phone text-muted"></i>
                                                    </span>
                                                    <input type="tel" class="form-control" 
                                                           name="mahasiswa[0][no_hp]" 
                                                           value="{{ old('mahasiswa.0.no_hp') }}" 
                                                           placeholder="08xxxxxxxxxx" required maxlength="15">
                                                </div>
                                                @error('mahasiswa.0.no_hp')
                                                <div class="invalid-feedback d-block">{{ $message }}</div>
                                                @enderror
                                            </div>
                                        </div>

                                        <div class="row">
                                            <div class="col-md-6 mb-3">
                                                <label class="form-label fw-semibold">Semester Saat Ini <span class="text-danger">*</span></label>
                                                <div class="input-group">
                                                    <span class="input-group-text bg-light border-end-0">
                                                        <i class="fas fa-graduation-cap text-muted"></i>
                                                    </span>
                                                    <select class="form-select" 
                                                            name="mahasiswa[0][semester]" required>
                                                        <option value="" selected disabled>Pilih semester</option>
                                                        @for($i = 1; $i <= 8; $i++)
                                                        <option value="{{ $i }}" {{ old('mahasiswa.0.semester') == $i ? 'selected' : '' }}>
                                                            Semester {{ $i }}
                                                        </option>
                                                        @endfor
                                                    </select>
                                                </div>
                                                @error('mahasiswa.0.semester')
                                                <div class="invalid-feedback d-block">{{ $message }}</div>
                                                @enderror
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Add Mahasiswa Button -->
                            <div class="text-center mb-4">
                                <button type="button" class="btn btn-outline-primary" id="addMahasiswa">
                                    <i class="fas fa-plus me-2"></i>Tambah Mahasiswa
                                </button>
                            </div>

                            <div class="row mt-4">
                                <div class="col-12 text-end">
                                    <button type="button" class="btn btn-primary next-step" data-next="2">
                                        Lanjut ke Informasi Prestasi <i class="fas fa-arrow-right ms-2"></i>
                                    </button>
                                </div>
                            </div>
                        </div>

                        <!-- Step 2: Informasi Prestasi -->
                        <div class="form-step" data-step="2">
                            <div class="row">
                                <div class="col-12 mb-4">
                                    <div class="section-header d-flex align-items-center mb-4">
                                        <span class="step-badge bg-primary text-white rounded-circle me-3">2</span>
                                        <h5 class="mb-0 text-primary">
                                            <i class="fas fa-trophy me-2"></i>Informasi Prestasi
                                        </h5>
                                    </div>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-12 mb-3">
                                    <label for="nama_prestasi" class="form-label fw-semibold">Nama Prestasi <span class="text-danger">*</span></label>
                                    <div class="input-group">
                                        <span class="input-group-text bg-light border-end-0">
                                            <i class="fas fa-award text-muted"></i>
                                        </span>
                                        <input type="text" class="form-control @error('nama_prestasi') is-invalid @enderror" 
                                               id="nama_prestasi" name="nama_prestasi" 
                                               value="{{ old('nama_prestasi') }}" 
                                               placeholder="Masukkan nama prestasi yang diraih" required>
                                        @error('nama_prestasi')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label for="kategori" class="form-label fw-semibold">Kategori Prestasi <span class="text-danger">*</span></label>
                                    <div class="input-group">
                                        <span class="input-group-text bg-light border-end-0">
                                            <i class="fas fa-tags text-muted"></i>
                                        </span>
                                        <select class="form-select @error('kategori') is-invalid @enderror" 
                                                id="kategori" name="kategori" required>
                                            <option value="" selected disabled>Pilih kategori prestasi</option>
                                            <option value="akademik" {{ old('kategori') == 'akademik' ? 'selected' : '' }}>Akademik</option>
                                            <option value="non-akademik" {{ old('kategori') == 'non-akademik' ? 'selected' : '' }}>Non-Akademik</option>
                                            <option value="olahraga" {{ old('kategori') == 'olahraga' ? 'selected' : '' }}>Olahraga</option>
                                            <option value="seni" {{ old('kategori') == 'seni' ? 'selected' : '' }}>Seni</option>
                                            <option value="teknologi" {{ old('kategori') == 'teknologi' ? 'selected' : '' }}>Teknologi</option>
                                            <option value="lainnya" {{ old('kategori') == 'lainnya' ? 'selected' : '' }}>Lainnya</option>
                                        </select>
                                        @error('kategori')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>

                                <div class="col-md-6 mb-3">
                                    <label for="ipk" class="form-label fw-semibold">IPK (Indeks Prestasi Kumulatif)</label>
                                    <div class="input-group">
                                        <span class="input-group-text bg-light border-end-0">
                                            <i class="fas fa-chart-line text-muted"></i>
                                        </span>
                                        <input type="number" step="0.01" min="0" max="4" 
                                               class="form-control @error('ipk') is-invalid @enderror" 
                                               id="ipk" name="ipk" 
                                               value="{{ old('ipk') }}" 
                                               placeholder="Contoh: 3.75">
                                        @error('ipk')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                    <div class="form-text text-muted">
                                        <i class="fas fa-info-circle me-1"></i>
                                        Opsional. Kosongkan jika tidak relevan dengan prestasi
                                    </div>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label for="tanggal_mulai" class="form-label fw-semibold">Tanggal Mulai <span class="text-danger">*</span></label>
                                    <div class="input-group">
                                        <span class="input-group-text bg-light border-end-0">
                                            <i class="fas fa-calendar text-muted"></i>
                                        </span>
                                        <input type="date" class="form-control @error('tanggal_mulai') is-invalid @enderror" 
                                               id="tanggal_mulai" name="tanggal_mulai" 
                                               value="{{ old('tanggal_mulai') }}" required>
                                        @error('tanggal_mulai')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>

                                <div class="col-md-6 mb-3">
                                    <label for="tanggal_selesai" class="form-label fw-semibold">Tanggal Selesai <span class="text-danger">*</span></label>
                                    <div class="input-group">
                                        <span class="input-group-text bg-light border-end-0">
                                            <i class="fas fa-calendar-check text-muted"></i>
                                        </span>
                                        <input type="date" class="form-control @error('tanggal_selesai') is-invalid @enderror" 
                                               id="tanggal_selesai" name="tanggal_selesai" 
                                               value="{{ old('tanggal_selesai') }}" required>
                                        @error('tanggal_selesai')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-12 mb-3">
                                    <label for="deskripsi" class="form-label fw-semibold">Deskripsi Prestasi <span class="text-danger">*</span></label>
                                    <textarea class="form-control @error('deskripsi') is-invalid @enderror" 
                                              id="deskripsi" name="deskripsi" 
                                              rows="5" 
                                              placeholder="Jelaskan prestasi yang diraih secara lengkap, termasuk tingkat kompetisi, pencapaian, penyelenggara, dan detail lainnya" 
                                              required>{{ old('deskripsi') }}</textarea>
                                    <div class="form-text text-muted">
                                        <span id="charCount">0</span>/500 karakter
                                    </div>
                                    @error('deskripsi')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>

                            <div class="row mt-4">
                                <div class="col-6">
                                    <button type="button" class="btn btn-outline-primary prev-step" data-prev="1">
                                        <i class="fas fa-arrow-left me-2"></i>Kembali ke Data Mahasiswa
                                    </button>
                                </div>
                                <div class="col-6 text-end">
                                    <button type="button" class="btn btn-primary next-step" data-next="3">
                                        Lanjut ke Unggah Dokumen <i class="fas fa-arrow-right ms-2"></i>
                                    </button>
                                </div>
                            </div>
                        </div>

                        <!-- Step 3: Dokumen -->
                        <div class="form-step" data-step="3">
                            <div class="row">
                                <div class="col-12 mb-4">
                                    <div class="section-header d-flex align-items-center mb-4">
                                        <span class="step-badge bg-primary text-white rounded-circle me-3">3</span>
                                        <h5 class="mb-0 text-primary">
                                            <i class="fas fa-file-upload me-2"></i>Unggah Dokumen
                                        </h5>
                                    </div>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-12 mb-4">
                                    <label for="bukti_prestasi" class="form-label fw-semibold">Bukti Prestasi <span class="text-danger">*</span></label>
                                    
                                    <div class="file-upload-card border rounded p-4 text-center">
                                        <i class="fas fa-cloud-upload-alt fa-3x text-primary mb-3"></i>
                                        <h5 class="mb-2">Klik untuk mengunggah file</h5>
                                        <p class="text-muted mb-3">
                                            Seret file ke sini atau klik untuk memilih
                                        </p>
                                        <div class="file-types mb-3">
                                            <span class="badge bg-light text-dark me-2">PDF</span>
                                            <span class="badge bg-light text-dark me-2">JPG</span>
                                            <span class="badge bg-light text-dark">PNG</span>
                                        </div>
                                        <p class="text-muted small">Maksimal ukuran file: 5MB</p>
                                        <input type="file" class="form-control d-none" 
                                               id="bukti_prestasi" name="bukti_prestasi" 
                                               accept=".pdf,.jpg,.jpeg,.png" required>
                                    </div>
                                    
                                    <div id="filePreview" class="mt-3 d-none">
                                        <div class="alert alert-success d-flex align-items-center">
                                            <i class="fas fa-check-circle text-success me-3 fs-5"></i>
                                            <div class="flex-grow-1">
                                                <strong>File berhasil diunggah:</strong>
                                                <span id="fileName" class="ms-1"></span>
                                            </div>
                                            <button type="button" class="btn btn-sm btn-outline-danger" id="removeFile">
                                                <i class="fas fa-times me-1"></i>Hapus
                                            </button>
                                        </div>
                                    </div>
                                    
                                    @error('bukti_prestasi')
                                    <div class="invalid-feedback d-block">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-12">
                                    <div class="alert alert-info">
                                        <div class="d-flex">
                                            <i class="fas fa-info-circle mt-1 me-3 fs-5"></i>
                                            <div>
                                                <strong>Informasi Penting</strong>
                                                <p class="mb-0 mt-1">
                                                    Pastikan bukti prestasi yang diunggah jelas dan terbaca. 
                                                    Prestasi akan melalui proses validasi oleh admin sebelum disetujui.
                                                </p>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="row mt-4">
                                <div class="col-6">
                                    <button type="button" class="btn btn-outline-primary prev-step" data-prev="2">
                                        <i class="fas fa-arrow-left me-2"></i>Kembali ke Prestasi
                                    </button>
                                </div>
                                <div class="col-6 text-end">
                                    <button type="submit" class="btn btn-success btn-lg">
                                        <i class="fas fa-paper-plane me-2"></i>Ajukan Prestasi
                                    </button>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
.steps {
    display: flex;
    justify-content: space-between;
    align-items: center;
    position: relative;
    max-width: 600px;
    margin: 0 auto;
}

.steps::before {
    content: '';
    position: absolute;
    top: 20px;
    left: 0;
    right: 0;
    height: 3px;
    background: #e9ecef;
    z-index: 1;
}

.step {
    display: flex;
    flex-direction: column;
    align-items: center;
    position: relative;
    z-index: 2;
    flex: 1;
}

.step-number {
    width: 40px;
    height: 40px;
    border-radius: 50%;
    background: #e9ecef;
    color: #6c757d;
    display: flex;
    align-items: center;
    justify-content: center;
    font-weight: 600;
    margin-bottom: 0.5rem;
    border: 3px solid white;
}

.step.active .step-number {
    background: #4361ee;
    color: white;
}

.step-label {
    font-weight: 600;
    color: #6c757d;
    font-size: 0.875rem;
}

.step.active .step-label {
    color: #4361ee;
}

.form-step {
    display: none;
}

.form-step.active {
    display: block;
    animation: fadeIn 0.3s ease;
}

@keyframes fadeIn {
    from { opacity: 0; transform: translateY(10px); }
    to { opacity: 1; transform: translateY(0); }
}

.step-badge {
    width: 35px;
    height: 35px;
    display: flex;
    align-items: center;
    justify-content: center;
    font-weight: 600;
    font-size: 0.875rem;
}

.section-header {
    border-bottom: 2px solid #e9ecef;
    padding-bottom: 1rem;
}

.file-upload-card {
    background: #f8f9fa;
    border: 2px dashed #dee2e6 !important;
    cursor: pointer;
    transition: all 0.3s ease;
}

.file-upload-card:hover {
    border-color: #4361ee !important;
    background: rgba(67, 97, 238, 0.05);
}

.input-group-text {
    background: #f8f9fa !important;
    border: 1px solid #ced4da !important;
}

.form-control, .form-select {
    border: 1px solid #ced4da;
    transition: all 0.3s ease;
}

.form-control:focus, .form-select:focus {
    border-color: #4361ee;
    box-shadow: 0 0 0 0.2rem rgba(67, 97, 238, 0.25);
}

.btn {
    border-radius: 8px;
    font-weight: 600;
    transition: all 0.3s ease;
}

.btn-primary {
    background: linear-gradient(135deg, #4361ee 0%, #3a0ca3 100%);
    border: none;
}

.btn-primary:hover {
    transform: translateY(-1px);
    box-shadow: 0 4px 12px rgba(67, 97, 238, 0.3);
}

.btn-success {
    background: linear-gradient(135deg, #4cc9f0 0%, #4361ee 100%);
    border: none;
}

.btn-success:hover {
    transform: translateY(-1px);
    box-shadow: 0 4px 12px rgba(76, 201, 240, 0.3);
}

.btn-outline-primary {
    border: 2px solid #4361ee;
    color: #4361ee;
    background: transparent;
}

.btn-outline-primary:hover {
    background: #4361ee;
    color: white;
    transform: translateY(-1px);
}

.alert {
    border-radius: 8px;
    border: none;
}

.card {
    border: none;
    border-radius: 12px;
    box-shadow: 0 4px 20px rgba(0,0,0,0.1);
}

.card-header {
    border-radius: 12px 12px 0 0 !important;
}

/* Mahasiswa item styles */
.mahasiswa-item {
    border: 1px solid #e9ecef;
    border-radius: 8px;
    overflow: hidden;
}

.mahasiswa-item .card-header {
    background: linear-gradient(135deg, #f8f9fa 0%, #e9ecef 100%) !important;
    border-bottom: 1px solid #dee2e6;
}

.mahasiswa-item .card-body {
    padding: 1.5rem;
}

.remove-mahasiswa {
    font-size: 0.75rem;
    padding: 0.25rem 0.75rem;
}

@media (max-width: 768px) {
    .steps {
        flex-direction: column;
        gap: 1rem;
    }
    
    .steps::before {
        display: none;
    }
    
    .step {
        flex-direction: row;
        gap: 1rem;
        width: 100%;
    }
    
    .step-number {
        margin-bottom: 0;
    }
    
    .step-label {
        text-align: left;
    }
    
    .btn {
        padding: 10px 20px;
        font-size: 0.875rem;
    }
    
    .file-upload-card {
        padding: 2rem 1rem !important;
    }
    
    .section-header {
        flex-direction: column;
        align-items: flex-start !important;
        gap: 0.5rem;
    }
    
    .step-badge {
        align-self: flex-start;
    }
    
    .mahasiswa-item .card-body {
        padding: 1rem;
    }
}
</style>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Multi-step form functionality
    const steps = document.querySelectorAll('.form-step');
    const progressSteps = document.querySelectorAll('.step');
    let currentStep = 1;

    function showStep(stepNumber) {
        steps.forEach(step => {
            step.classList.remove('active');
            if (parseInt(step.dataset.step) === stepNumber) {
                step.classList.add('active');
            }
        });

        progressSteps.forEach(step => {
            step.classList.remove('active');
            if (parseInt(step.dataset.step) <= stepNumber) {
                step.classList.add('active');
            }
        });

        currentStep = stepNumber;
        
        // Scroll to top of form
        document.querySelector('.card-body').scrollIntoView({ behavior: 'smooth' });
    }

    // Next step buttons
    document.querySelectorAll('.next-step').forEach(button => {
        button.addEventListener('click', function() {
            const nextStep = parseInt(this.dataset.next);
            if (validateStep(currentStep)) {
                showStep(nextStep);
            }
        });
    });

    // Previous step buttons
    document.querySelectorAll('.prev-step').forEach(button => {
        button.addEventListener('click', function() {
            const prevStep = parseInt(this.dataset.prev);
            showStep(prevStep);
        });
    });

    // Step validation
    function validateStep(step) {
        const currentStepElement = document.querySelector(`.form-step[data-step="${step}"]`);
        const inputs = currentStepElement.querySelectorAll('input[required], select[required], textarea[required]');
        
        let isValid = true;
        
        inputs.forEach(input => {
            if (!input.value.trim()) {
                input.classList.add('is-invalid');
                isValid = false;
            } else {
                input.classList.remove('is-invalid');
            }
        });

        if (!isValid) {
            // Scroll to first invalid input
            const firstInvalid = currentStepElement.querySelector('.is-invalid');
            if (firstInvalid) {
                firstInvalid.scrollIntoView({ behavior: 'smooth', block: 'center' });
                firstInvalid.focus();
            }
            
            // Show error message
            const errorAlert = document.createElement('div');
            errorAlert.className = 'alert alert-danger alert-dismissible fade show mb-4';
            errorAlert.innerHTML = `
                <div class="d-flex align-items-center">
                    <i class="fas fa-exclamation-triangle me-3"></i>
                    <div>Silakan lengkapi semua field yang diperlukan</div>
                </div>
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            `;
            
            const existingAlert = currentStepElement.querySelector('.alert-danger');
            if (!existingAlert) {
                currentStepElement.prepend(errorAlert);
            }
        }

        return isValid;
    }

    // Multiple Mahasiswa Functionality
    const mahasiswaContainer = document.getElementById('mahasiswaContainer');
    const addMahasiswaBtn = document.getElementById('addMahasiswa');
    let mahasiswaCount = 1;

    addMahasiswaBtn.addEventListener('click', function() {
        if (mahasiswaCount >= 10) {
            alert('Maksimal 10 mahasiswa per prestasi');
            return;
        }

        const newIndex = mahasiswaCount;
        const template = `
            <div class="mahasiswa-item card mb-4" data-index="${newIndex}">
                <div class="card-header bg-light d-flex justify-content-between align-items-center py-3">
                    <h6 class="mb-0 fw-semibold">
                        <i class="fas fa-user-graduate me-2 text-primary"></i>Mahasiswa ${newIndex + 1}
                    </h6>
                    <button type="button" class="btn btn-sm btn-outline-danger remove-mahasiswa">
                        <i class="fas fa-times me-1"></i>Hapus
                    </button>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="form-label fw-semibold">Nama Lengkap <span class="text-danger">*</span></label>
                            <div class="input-group">
                                <span class="input-group-text bg-light border-end-0">
                                    <i class="fas fa-user text-muted"></i>
                                </span>
                                <input type="text" class="form-control" 
                                       name="mahasiswa[${newIndex}][nama]" 
                                       placeholder="Masukkan nama lengkap" required>
                            </div>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label fw-semibold">NIM <span class="text-danger">*</span></label>
                            <div class="input-group">
                                <span class="input-group-text bg-light border-end-0">
                                    <i class="fas fa-id-card text-muted"></i>
                                </span>
                                <input type="text" class="form-control" 
                                       name="mahasiswa[${newIndex}][nim]" 
                                       placeholder="Masukkan NIM" required maxlength="15">
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="form-label fw-semibold">Email <span class="text-danger">*</span></label>
                            <div class="input-group">
                                <span class="input-group-text bg-light border-end-0">
                                    <i class="fas fa-envelope text-muted"></i>
                                </span>
                                <input type="email" class="form-control" 
                                       name="mahasiswa[${newIndex}][email]" 
                                       placeholder="nama@contoh.com" required>
                            </div>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label fw-semibold">No. HP/WhatsApp <span class="text-danger">*</span></label>
                            <div class="input-group">
                                <span class="input-group-text bg-light border-end-0">
                                    <i class="fas fa-phone text-muted"></i>
                                </span>
                                <input type="tel" class="form-control" 
                                       name="mahasiswa[${newIndex}][no_hp]" 
                                       placeholder="08xxxxxxxxxx" required maxlength="15">
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="form-label fw-semibold">Semester Saat Ini <span class="text-danger">*</span></label>
                            <div class="input-group">
                                <span class="input-group-text bg-light border-end-0">
                                    <i class="fas fa-graduation-cap text-muted"></i>
                                </span>
                                <select class="form-select" name="mahasiswa[${newIndex}][semester]" required>
                                    <option value="" selected disabled>Pilih semester</option>
                                    @for($i = 1; $i <= 8; $i++)
                                    <option value="{{ $i }}">Semester {{ $i }}</option>
                                    @endfor
                                </select>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        `;

        mahasiswaContainer.insertAdjacentHTML('beforeend', template);
        mahasiswaCount++;

        // Show remove button on first item if there are multiple
        if (mahasiswaCount > 1) {
            document.querySelector('.mahasiswa-item:first-child .remove-mahasiswa').classList.remove('d-none');
        }
    });

    // Remove mahasiswa
    mahasiswaContainer.addEventListener('click', function(e) {
        if (e.target.closest('.remove-mahasiswa')) {
            const item = e.target.closest('.mahasiswa-item');
            const index = parseInt(item.dataset.index);
            
            if (mahasiswaCount <= 1) {
                alert('Minimal harus ada 1 mahasiswa');
                return;
            }

            item.remove();
            mahasiswaCount--;

            // Renumber remaining items
            const items = mahasiswaContainer.querySelectorAll('.mahasiswa-item');
            items.forEach((item, index) => {
                item.dataset.index = index;
                item.querySelector('.card-header h6').textContent = `Mahasiswa ${index + 1}`;
                
                // Update input names
                const inputs = item.querySelectorAll('input, select');
                inputs.forEach(input => {
                    const name = input.name.replace(/\[\d+\]/, `[${index}]`);
                    input.name = name;
                });
            });

            // Hide remove button if only one item left
            if (mahasiswaCount === 1) {
                document.querySelector('.mahasiswa-item:first-child .remove-mahasiswa').classList.add('d-none');
            }
        }
    });

    // Character counter for description
    const deskripsi = document.getElementById('deskripsi');
    const charCount = document.getElementById('charCount');
    
    if (deskripsi && charCount) {
        deskripsi.addEventListener('input', function() {
            const count = this.value.length;
            charCount.textContent = count;
            
            if (count > 500) {
                charCount.classList.add('text-danger');
                this.classList.add('is-invalid');
            } else {
                charCount.classList.remove('text-danger');
                this.classList.remove('is-invalid');
            }
        });
    }

    // File upload functionality
    const fileUploadCard = document.querySelector('.file-upload-card');
    const fileInput = document.getElementById('bukti_prestasi');
    const filePreview = document.getElementById('filePreview');
    const fileName = document.getElementById('fileName');
    const removeFile = document.getElementById('removeFile');

    if (fileUploadCard && fileInput) {
        fileUploadCard.addEventListener('click', function() {
            fileInput.click();
        });

        fileInput.addEventListener('change', function() {
            const file = this.files[0];
            if (file) {
                // Validate file size (5MB)
                if (file.size > 5 * 1024 * 1024) {
                    alert('Ukuran file maksimal 5MB');
                    this.value = '';
                    return;
                }

                // Validate file type
                const allowedTypes = ['application/pdf', 'image/jpeg', 'image/jpg', 'image/png'];
                if (!allowedTypes.includes(file.type)) {
                    alert('Hanya file PDF, JPG, dan PNG yang diizinkan');
                    this.value = '';
                    return;
                }

                fileName.textContent = file.name;
                filePreview.classList.remove('d-none');
                fileUploadCard.style.display = 'none';
            }
        });
    }

    if (removeFile) {
        removeFile.addEventListener('click', function() {
            fileInput.value = '';
            filePreview.classList.add('d-none');
            fileUploadCard.style.display = 'block';
        });
    }

    // Date validation
    const tanggalMulai = document.getElementById('tanggal_mulai');
    const tanggalSelesai = document.getElementById('tanggal_selesai');
    
    if (tanggalMulai && tanggalSelesai) {
        tanggalMulai.addEventListener('change', function() {
            if (tanggalSelesai.value && this.value > tanggalSelesai.value) {
                alert('Tanggal mulai tidak boleh lebih besar dari tanggal selesai');
                this.value = '';
            }
        });
        
        tanggalSelesai.addEventListener('change', function() {
            if (tanggalMulai.value && this.value < tanggalMulai.value) {
                alert('Tanggal selesai tidak boleh lebih kecil dari tanggal mulai');
                this.value = '';
            }
        });
    }

    // Auto-format inputs for all mahasiswa
    function setupInputFormatting() {
        const nimInputs = document.querySelectorAll('input[name$="[nim]"]');
        nimInputs.forEach(input => {
            input.addEventListener('input', function() {
                this.value = this.value.replace(/[^0-9]/g, '');
            });
        });

        const noHpInputs = document.querySelectorAll('input[name$="[no_hp]"]');
        noHpInputs.forEach(input => {
            input.addEventListener('input', function() {
                this.value = this.value.replace(/[^0-9+]/g, '');
            });
        });
    }

    // Initialize input formatting
    setupInputFormatting();

    // Form submission
    const prestasiForm = document.getElementById('prestasiForm');
    if (prestasiForm) {
        prestasiForm.addEventListener('submit', function(e) {
            if (!validateStep(currentStep)) {
                e.preventDefault();
                showStep(currentStep);
            }
        });
    }

    // Initialize first step
    showStep(1);
});
</script>
@endsection