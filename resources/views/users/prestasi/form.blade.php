@extends('layouts.app')

@section('title', 'Ajukan Prestasi - HIMA-TI')

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

    main, section, .container {
        position: relative;
        z-index: 2;
    }

    .page-title {
        color: #F8FAFC !important;
    }

    .hero-section p {
        color: #CBD5E1 !important;
    }

    .btn-light {
        background: rgba(255, 255, 255, 0.15) !important;
        border: 1px solid rgba(224, 231, 255, 0.30) !important;
        color: #F8FAFC !important;
    }

    .btn-light:hover {
        background: rgba(255, 255, 255, 0.25) !important;
    }

    .card {
        background: rgba(255, 255, 255, 0.18) !important;
        backdrop-filter: blur(8px);
        border: 1px solid rgba(224, 231, 255, 0.30) !important;
        border-radius: 16px;
        box-shadow: 0 8px 32px rgba(2, 6, 23, 0.15) !important;
    }

    .card-header {
        background: rgba(99, 102, 241, 0.3) !important;
        border-bottom: 1px solid rgba(224, 231, 255, 0.30) !important;
        color: #F8FAFC !important;
    }

    .card-body {
        color: #F8FAFC;
    }

    .card-header h4 {
        color: #F8FAFC;
    }

    .step-badge {
        background: rgba(99, 102, 241, 0.3);
        color: #A5B4FC;
        width: 40px;
        height: 40px;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        font-weight: 700;
    }

    .section-title {
        color: #F8FAFC !important;
    }

    .form-label {
        color: #F8FAFC;
    }

    .form-control, .input-group-text, input, select, textarea {
        background: rgba(255, 255, 255, 0.15) !important;
        border: 2px solid rgba(224, 231, 255, 0.30) !important;
        color: #F8FAFC !important;
        backdrop-filter: blur(4px);
    }

    .form-control::placeholder,
    input::placeholder,
    select::placeholder,
    textarea::placeholder {
        color: #94A3B8 !important;
    }

    .form-control:focus, input:focus, select:focus, textarea:focus {
        outline: none;
        border-color: #6366F1 !important;
        background: rgba(255, 255, 255, 0.25) !important;
        box-shadow: 0 0 0 3px rgba(99, 102, 241, 0.2) !important;
    }

    .input-group-text {
        background: rgba(99, 102, 241, 0.2) !important;
        border-color: rgba(224, 231, 255, 0.30) !important;
        color: #A5B4FC !important;
    }

    select option {
        background-color: #1E293B;
        color: #F8FAFC;
    }

    .alert-danger {
        background: rgba(220, 38, 38, 0.15) !important;
        border: 1px solid rgba(220, 38, 38, 0.3) !important;
        color: #FCA5A5 !important;
    }

    .alert-info {
        background: rgba(59, 130, 246, 0.15) !important;
        border: 1px solid rgba(59, 130, 246, 0.3) !important;
        color: #CBD5E1 !important;
    }

    .alert-info strong {
        color: #A5B4FC;
    }

    .btn-primary {
        background: #6366F1 !important;
        border-color: #6366F1 !important;
    }

    .btn-primary:hover {
        background: #4F46E5 !important;
        border-color: #4F46E5 !important;
        box-shadow: 0 6px 20px rgba(99, 102, 241, 0.35) !important;
    }

    .btn-outline-danger {
        border-color: #FCA5A5 !important;
        color: #FCA5A5 !important;
    }

    .btn-outline-danger:hover {
        background: #FCA5A5 !important;
        border-color: #FCA5A5 !important;
    }

    .bg-light {
        background: rgba(255, 255, 255, 0.1) !important;
        border-bottom: 1px solid rgba(224, 231, 255, 0.30);
    }

    h5, h6 {
        color: #F8FAFC;
    }

    .step-progress {
        display: flex;
        gap: 1rem;
        margin-bottom: 2rem;
        flex-wrap: wrap;
    }

    .step {
        display: flex;
        flex-direction: column;
        align-items: center;
        gap: 0.5rem;
        padding: 1rem;
        border-radius: 12px;
        background: rgba(255, 255, 255, 0.1);
        border: 2px solid rgba(224, 231, 255, 0.30);
        color: #CBD5E1;
        flex: 1;
        min-width: 150px;
        transition: all 0.3s ease;
    }

    .step.active {
        background: rgba(99, 102, 241, 0.2);
        border-color: #6366F1;
        color: #A5B4FC;
    }

    .step-icon {
        font-size: 1.5rem;
        color: #A5B4FC;
    }

    .step.active .step-icon {
        color: #E0E7FF;
    }
</style>

<div class="container py-4">
    <div class="row justify-content-center">
        <div class="col-12 col-lg-10">
            <!-- Header Section -->
            <div class="hero-section mb-4">
                <div class="row align-items-center">
                    <div class="col-md-8">
                        <h1 class="page-title mb-2">
                            <i class="fas fa-trophy me-2"></i>Form Pengajuan Prestasi
                        </h1>
                        <p class="mb-0">Isi form berikut untuk mengajukan prestasi</p>
                    </div>
                    <div class="col-md-4 text-md-end">
                        <a href="{{ route('prestasi.index') }}" class="btn btn-light">
                            <i class="fas fa-arrow-left me-2"></i>Kembali ke Daftar
                        </a>
                    </div>
                </div>
            </div>

            <!-- Progress Steps -->
            <div class="progress-steps mb-5">
                <div class="step-progress">
                    <div class="step active" data-step="1">
                        <div class="step-icon">
                            <i class="fas fa-users"></i>
                        </div>
                        <span class="step-label">Data Mahasiswa</span>
                    </div>
                    <div class="step" data-step="2">
                        <div class="step-icon">
                            <i class="fas fa-trophy"></i>
                        </div>
                        <span class="step-label">Prestasi</span>
                    </div>
                    <div class="step" data-step="3">
                        <div class="step-icon">
                            <i class="fas fa-file-upload"></i>
                        </div>
                        <span class="step-label">Dokumen</span>
                    </div>
                </div>
            </div>

            <div class="card shadow-sm">
                <div class="card-header bg-primary text-white py-3">
                    <h4 class="mb-0"><i class="fas fa-clipboard-list me-2"></i>Data Prestasi</h4>
                </div>
                <div class="card-body p-4">
                    @if ($errors->any())
                        <div class="alert alert-danger alert-dismissible fade show">
                            <div class="d-flex align-items-center">
                                <i class="fas fa-exclamation-triangle me-2"></i>
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
                            <div class="form-section mb-5">
                                <div class="section-header mb-4">
                                    <div class="step-badge">1</div>
                                    <h5 class="section-title mb-0">
                                        <i class="fas fa-users me-2"></i>Data Mahasiswa
                                    </h5>
                                </div>
                                
                                <div class="alert alert-info mb-4">
                                    <div class="d-flex">
                                        <i class="fas fa-info-circle mt-1 me-3"></i>
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
                                    <div class="mahasiswa-item card mb-3" data-index="0">
                                        <div class="card-header bg-light d-flex justify-content-between align-items-center">
                                            <h6 class="mb-0">Mahasiswa 1</h6>
                                            <button type="button" class="btn btn-sm btn-outline-danger remove-mahasiswa d-none">
                                                <i class="fas fa-times"></i> Hapus
                                            </button>
                                        </div>
                                        <div class="card-body">
                                            <div class="row">
                                                <!-- Nama -->
                                                <div class="col-md-6 mb-3">
                                                    <label class="form-label required">Nama Lengkap</label>
                                                    <div class="input-group">
                                                        <span class="input-group-text"><i class="fas fa-user"></i></span>
                                                        <input type="text" class="form-control" 
                                                               name="mahasiswa[0][nama]" 
                                                               value="{{ old('mahasiswa.0.nama', auth()->user()->name ?? '') }}" 
                                                               placeholder="Masukkan nama lengkap" required>
                                                    </div>
                                                    @error('mahasiswa.0.nama')
                                                        <div class="invalid-feedback d-block">{{ $message }}</div>
                                                    @enderror
                                                </div>

                                                <!-- NIM -->
                                                <div class="col-md-6 mb-3">
                                                    <label class="form-label required">NIM</label>
                                                    <div class="input-group">
                                                        <span class="input-group-text"><i class="fas fa-id-card"></i></span>
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
                                                <!-- Email -->
                                                <div class="col-md-6 mb-3">
                                                    <label class="form-label required">Email</label>
                                                    <div class="input-group">
                                                        <span class="input-group-text"><i class="fas fa-envelope"></i></span>
                                                        <input type="email" class="form-control" 
                                                               name="mahasiswa[0][email]" 
                                                               value="{{ old('mahasiswa.0.email', auth()->user()->email ?? '') }}" 
                                                               placeholder="nama@contoh.com" required>
                                                    </div>
                                                    @error('mahasiswa.0.email')
                                                        <div class="invalid-feedback d-block">{{ $message }}</div>
                                                    @enderror
                                                </div>

                                                <!-- No. HP -->
                                                <div class="col-md-6 mb-3">
                                                    <label class="form-label required">No. HP/WhatsApp</label>
                                                    <div class="input-group">
                                                        <span class="input-group-text"><i class="fas fa-phone"></i></span>
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

                                            <!-- Semester -->
                                            <div class="col-md-6 mb-3">
                                                <label class="form-label required">Semester Saat Ini</label>
                                                <div class="input-group">
                                                    <span class="input-group-text"><i class="fas fa-graduation-cap"></i></span>
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

                                <!-- Add Mahasiswa Button -->
                                <div class="text-center mb-4">
                                    <button type="button" class="btn btn-outline-primary" id="addMahasiswa">
                                        <i class="fas fa-plus me-2"></i>Tambah Mahasiswa
                                    </button>
                                </div>
                            </div>

                            <div class="d-flex justify-content-between mt-4">
                                <div></div>
                                <button type="button" class="btn btn-primary next-step" data-next="2">
                                    Lanjut <i class="fas fa-arrow-right ms-2"></i>
                                </button>
                            </div>
                        </div>

                        <!-- Step 2: Informasi Prestasi -->
                        <div class="form-step" data-step="2">
                            <div class="form-section mb-5">
                                <div class="section-header mb-4">
                                    <div class="step-badge">2</div>
                                    <h5 class="section-title mb-0">
                                        <i class="fas fa-trophy me-2"></i>Informasi Prestasi
                                    </h5>
                                </div>
                                
                                <!-- Nama Prestasi -->
                                <div class="mb-4">
                                    <label for="nama_prestasi" class="form-label required">Nama Prestasi</label>
                                    <div class="input-group">
                                        <span class="input-group-text"><i class="fas fa-award"></i></span>
                                        <input type="text" class="form-control @error('nama_prestasi') is-invalid @enderror" 
                                               id="nama_prestasi" name="nama_prestasi" 
                                               value="{{ old('nama_prestasi') }}" 
                                               placeholder="Masukkan nama prestasi yang diraih" required>
                                    </div>
                                    @error('nama_prestasi')
                                        <div class="invalid-feedback d-block">{{ $message }}</div>
                                    @enderror
                                </div>

                                <!-- Kategori -->
                                <div class="mb-4">
                                    <label for="kategori" class="form-label required">Kategori Prestasi</label>
                                    <div class="input-group">
                                        <span class="input-group-text"><i class="fas fa-tags"></i></span>
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
                                    </div>
                                    @error('kategori')
                                        <div class="invalid-feedback d-block">{{ $message }}</div>
                                    @enderror
                                </div>

                                <!-- Tanggal Mulai & Tanggal Selesai -->
                                <div class="row mb-4">
                                    <div class="col-md-6">
                                        <label for="tanggal_mulai" class="form-label required">Tanggal Mulai</label>
                                        <div class="input-group">
                                            <span class="input-group-text"><i class="fas fa-calendar"></i></span>
                                            <input type="date" class="form-control @error('tanggal_mulai') is-invalid @enderror" 
                                                   id="tanggal_mulai" name="tanggal_mulai" 
                                                   value="{{ old('tanggal_mulai') }}" required>
                                        </div>
                                        @error('tanggal_mulai')
                                            <div class="invalid-feedback d-block">{{ $message }}</div>
                                        @enderror
                                    </div>
                                    <div class="col-md-6">
                                        <label for="tanggal_selesai" class="form-label required">Tanggal Selesai</label>
                                        <div class="input-group">
                                            <span class="input-group-text"><i class="fas fa-calendar-check"></i></span>
                                            <input type="date" class="form-control @error('tanggal_selesai') is-invalid @enderror" 
                                                   id="tanggal_selesai" name="tanggal_selesai" 
                                                   value="{{ old('tanggal_selesai') }}" required>
                                        </div>
                                        @error('tanggal_selesai')
                                            <div class="invalid-feedback d-block">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>

                                <!-- Deskripsi Prestasi -->
                                <div class="mb-4">
                                    <label for="deskripsi" class="form-label required">Deskripsi Prestasi</label>
                                    <textarea class="form-control @error('deskripsi') is-invalid @enderror" 
                                              id="deskripsi" name="deskripsi" 
                                              rows="5" 
                                              placeholder="Jelaskan prestasi yang diraih secara lengkap, termasuk tingkat kompetisi, pencapaian, penyelenggara, dan detail lainnya" 
                                              required>{{ old('deskripsi') }}</textarea>
                                    <div class="form-text">
                                        <span id="charCount">0</span>/500 karakter
                                    </div>
                                    @error('deskripsi')
                                        <div class="invalid-feedback d-block">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>

                            <div class="d-flex justify-content-between mt-4">
                                <button type="button" class="btn btn-outline-primary prev-step" data-prev="1">
                                    <i class="fas fa-arrow-left me-2"></i>Kembali
                                </button>
                                <button type="button" class="btn btn-primary next-step" data-next="3">
                                    Lanjut <i class="fas fa-arrow-right ms-2"></i>
                                </button>
                            </div>
                        </div>

                        <!-- Step 3: Bukti Prestasi -->
                        <div class="form-step" data-step="3">
                            <div class="form-section mb-5">
                                <div class="section-header mb-4">
                                    <div class="step-badge">3</div>
                                    <h5 class="section-title mb-0">
                                        <i class="fas fa-file-upload me-2"></i>Bukti Prestasi
                                    </h5>
                                </div>
                                
                                <div class="mb-4">
                                    <label for="bukti_prestasi" class="form-label required">Unggah Bukti Prestasi</label>
                                    
                                    <div class="file-upload-area" id="fileUploadArea">
                                        <div class="file-upload-content">
                                            <i class="fas fa-cloud-upload-alt fa-3x text-primary mb-3"></i>
                                            <h5 class="mb-2">Klik untuk mengunggah file</h5>
                                            <p class="text-muted mb-3">
                                                Seret file ke sini atau klik untuk memilih
                                            </p>
                                            <div class="file-types">
                                                <span class="badge bg-light text-dark me-2">PDF</span>
                                                <span class="badge bg-light text-dark me-2">JPG</span>
                                                <span class="badge bg-light text-dark">PNG</span>
                                            </div>
                                            <p class="text-muted mt-2 small">Maksimal ukuran file: 5MB</p>
                                        </div>
                                        <input type="file" class="file-input @error('bukti_prestasi') is-invalid @enderror" 
                                               id="bukti_prestasi" name="bukti_prestasi" 
                                               accept=".pdf,.jpg,.jpeg,.png">
                                    </div>
                                    
                                    <div id="filePreview" class="mt-3 d-none">
                                        <div class="alert alert-success d-flex align-items-center">
                                            <i class="fas fa-check-circle text-success me-2"></i>
                                            <div>
                                                <strong>File berhasil diunggah:</strong>
                                                <span id="fileName" class="ms-1"></span>
                                                <button type="button" class="btn btn-sm btn-outline-danger ms-2" id="removeFile">
                                                    <i class="fas fa-times"></i>
                                                </button>
                                            </div>
                                        </div>
                                    </div>
                                    
                                    @error('bukti_prestasi')
                                        <div class="invalid-feedback d-block">{{ $message }}</div>
                                    @enderror
                                </div>

                                <!-- Informasi Tambahan -->
                                <div class="alert alert-info">
                                    <div class="d-flex">
                                        <i class="fas fa-info-circle mt-1 me-3"></i>
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

                            <div class="d-flex justify-content-between mt-4">
                                <button type="button" class="btn btn-outline-primary prev-step" data-prev="2">
                                    <i class="fas fa-arrow-left me-2"></i>Kembali
                                </button>
                                <button type="submit" class="btn btn-success btn-lg">
                                    <i class="fas fa-paper-plane me-2"></i>Ajukan Prestasi
                                </button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('styles')
<style>
/* Existing styles remain the same, adding new styles for mahasiswa items */
.mahasiswa-item {
    border-radius: 8px;
    border: 1px solid #e1e5ee;
}

.mahasiswa-item .card-header {
    background: #f8f9fa !important;
    border-bottom: 1px solid #e1e5ee;
}

.remove-mahasiswa {
    font-size: 0.75rem;
    padding: 0.25rem 0.5rem;
}
</style>
@endpush

@push('scripts')
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
            const firstInvalid = currentStepElement.querySelector('.is-invalid');
            if (firstInvalid) {
                firstInvalid.scrollIntoView({ behavior: 'smooth', block: 'center' });
                firstInvalid.focus();
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
            <div class="mahasiswa-item card mb-3" data-index="${newIndex}">
                <div class="card-header bg-light d-flex justify-content-between align-items-center">
                    <h6 class="mb-0">Mahasiswa ${newIndex + 1}</h6>
                    <button type="button" class="btn btn-sm btn-outline-danger remove-mahasiswa">
                        <i class="fas fa-times"></i> Hapus
                    </button>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="form-label required">Nama Lengkap</label>
                            <div class="input-group">
                                <span class="input-group-text"><i class="fas fa-user"></i></span>
                                <input type="text" class="form-control" 
                                       name="mahasiswa[${newIndex}][nama]" 
                                       placeholder="Masukkan nama lengkap" required>
                            </div>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label required">NIM</label>
                            <div class="input-group">
                                <span class="input-group-text"><i class="fas fa-id-card"></i></span>
                                <input type="text" class="form-control" 
                                       name="mahasiswa[${newIndex}][nim]" 
                                       placeholder="Masukkan NIM" required maxlength="15">
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="form-label required">Email</label>
                            <div class="input-group">
                                <span class="input-group-text"><i class="fas fa-envelope"></i></span>
                                <input type="email" class="form-control" 
                                       name="mahasiswa[${newIndex}][email]" 
                                       placeholder="nama@contoh.com" required>
                            </div>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label required">No. HP/WhatsApp</label>
                            <div class="input-group">
                                <span class="input-group-text"><i class="fas fa-phone"></i></span>
                                <input type="tel" class="form-control" 
                                       name="mahasiswa[${newIndex}][no_hp]" 
                                       placeholder="08xxxxxxxxxx" required maxlength="15">
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6 mb-3">
                        <label class="form-label required">Semester Saat Ini</label>
                        <div class="input-group">
                            <span class="input-group-text"><i class="fas fa-graduation-cap"></i></span>
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
    
    deskripsi.addEventListener('input', function() {
        const count = this.value.length;
        charCount.textContent = count;
        
        if (count > 500) {
            charCount.classList.add('text-danger');
        } else {
            charCount.classList.remove('text-danger');
        }
    });

    // File upload functionality (same as before)
    const fileUploadArea = document.getElementById('fileUploadArea');
    const fileInput = document.getElementById('bukti_prestasi');
    const filePreview = document.getElementById('filePreview');
    const fileName = document.getElementById('fileName');
    const removeFile = document.getElementById('removeFile');

    fileUploadArea.addEventListener('click', function() {
        fileInput.click();
    });

    fileUploadArea.addEventListener('dragover', function(e) {
        e.preventDefault();
        this.classList.add('dragover');
    });

    fileUploadArea.addEventListener('dragleave', function() {
        this.classList.remove('dragover');
    });

    fileUploadArea.addEventListener('drop', function(e) {
        e.preventDefault();
        this.classList.remove('dragover');
        
        if (e.dataTransfer.files.length) {
            fileInput.files = e.dataTransfer.files;
            handleFileSelect();
        }
    });

    fileInput.addEventListener('change', handleFileSelect);

    function handleFileSelect() {
        const file = fileInput.files[0];
        if (file) {
            if (file.size > 5 * 1024 * 1024) {
                alert('Ukuran file maksimal 5MB');
                fileInput.value = '';
                return;
            }

            const allowedTypes = ['application/pdf', 'image/jpeg', 'image/jpg', 'image/png'];
            if (!allowedTypes.includes(file.type)) {
                alert('Hanya file PDF, JPG, dan PNG yang diizinkan');
                fileInput.value = '';
                return;
            }

            fileName.textContent = file.name;
            filePreview.classList.remove('d-none');
        }
    }

    removeFile.addEventListener('click', function() {
        fileInput.value = '';
        filePreview.classList.add('d-none');
    });

    // Date validation
    const tanggalMulai = document.getElementById('tanggal_mulai');
    const tanggalSelesai = document.getElementById('tanggal_selesai');
    
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

    // Auto-format inputs
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

    // Form submission
    document.getElementById('prestasiForm').addEventListener('submit', function(e) {
        if (!validateStep(currentStep)) {
            e.preventDefault();
            alert('Silakan lengkapi semua field yang diperlukan');
        }
    });
});
</script>
@endpush