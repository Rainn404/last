@php $layout = request()->is('admin-panel/*') ? 'layouts.admin-panel.app' : 'layouts.admin.app'; @endphp
@extends($layout)

@section('title', $title . ' - Admin HIMA-TI')

@section('content')
<div class="container-fluid py-4">
    <!-- Header Section -->
    <div class="hero-section mb-4">
        <div class="container">
            <div class="row align-items-center">
                <div class="col-md-8">
                    <h1 class="page-title mb-2">
                        <i class="fas fa-trophy me-2"></i>{{ $title }}
                    </h1>
                    <p class="mb-0">
                        @if(isset($prestasi))
                            Edit data prestasi mahasiswa
                        @else
                            Tambah data prestasi mahasiswa baru
                        @endif
                    </p>
                </div>
                <div class="col-md-4 text-md-end">
                    <div class="admin-badges">
                        <span class="badge bg-light text-dark">
                            <i class="fas fa-user-shield me-1"></i>Administrator
                        </span>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="container">
        <!-- Progress Steps -->
        <div class="progress-steps mb-5">
            <div class="step-progress">
                <div class="step active" data-step="1">
                    <div class="step-icon">
                        <i class="fas fa-user"></i>
                    </div>
                    <span class="step-label">Data Mahasiswa</span>
                </div>
                <div class="step" data-step="2">
                    <div class="step-icon">
                        <i class="fas fa-trophy"></i>
                    </div>
                    <span class="step-label">Detail Prestasi</span>
                </div>
                <div class="step" data-step="3">
                    <div class="step-icon">
                        <i class="fas fa-cog"></i>
                    </div>
                    <span class="step-label">Pengaturan</span>
                </div>
            </div>
        </div>

        <div class="card shadow-sm">
            <div class="card-header bg-primary text-white py-3">
                <h4 class="mb-0">
                    <i class="fas fa-clipboard-list me-2"></i>
                    Form {{ isset($prestasi) ? 'Edit' : 'Tambah' }} Prestasi
                </h4>
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

                <form action="{{ $action }}" method="POST" enctype="multipart/form-data" id="prestasiForm">
                    @csrf
                    @if(isset($prestasi))
                        @method('PUT')
                    @endif
                    
                    <!-- Step 1: Data Mahasiswa -->
                    <div class="form-step active" data-step="1">
                        <div class="form-section mb-5">
                            <div class="section-header mb-4">
                                <div class="step-badge">1</div>
                                <h5 class="section-title mb-0">
                                    <i class="fas fa-user-graduate me-2"></i>Data Mahasiswa
                                </h5>
                            </div>
                            
                            <div class="row">
                                <!-- Nama -->
                                <div class="col-md-6 mb-4">
                                    <label for="nama" class="form-label required">Nama Lengkap</label>
                                    <div class="input-group">
                                        <span class="input-group-text"><i class="fas fa-user"></i></span>
                                        <input type="text" class="form-control @error('nama') is-invalid @enderror" 
                                               id="nama" name="nama" 
                                               value="{{ old('nama', $prestasi->nama ?? '') }}" 
                                               placeholder="Masukkan nama lengkap" required>
                                    </div>
                                    @error('nama')
                                        <div class="invalid-feedback d-block">{{ $message }}</div>
                                    @enderror
                                </div>

                                <!-- NIM -->
                                <div class="col-md-6 mb-4">
                                    <label for="nim" class="form-label required">NIM</label>
                                    <div class="input-group">
                                        <span class="input-group-text"><i class="fas fa-id-card"></i></span>
                                        <input type="text" class="form-control @error('nim') is-invalid @enderror" 
                                               id="nim" name="nim" 
                                               value="{{ old('nim', $prestasi->nim ?? '') }}" 
                                               placeholder="Masukkan NIM" required maxlength="15">
                                    </div>
                                    @error('nim')
                                        <div class="invalid-feedback d-block">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>

                            <div class="row">
                                <!-- Email -->
                                <div class="col-md-6 mb-4">
                                    <label for="email" class="form-label required">Email</label>
                                    <div class="input-group">
                                        <span class="input-group-text"><i class="fas fa-envelope"></i></span>
                                        <input type="email" class="form-control @error('email') is-invalid @enderror" 
                                               id="email" name="email" 
                                               value="{{ old('email', $prestasi->email ?? '') }}" 
                                               placeholder="nama@contoh.com" required>
                                    </div>
                                    @error('email')
                                        <div class="invalid-feedback d-block">{{ $message }}</div>
                                    @enderror
                                </div>

                                <!-- No. HP -->
                                <div class="col-md-6 mb-4">
                                    <label for="no_hp" class="form-label required">No. HP/WhatsApp</label>
                                    <div class="input-group">
                                        <span class="input-group-text"><i class="fas fa-phone"></i></span>
                                        <input type="tel" class="form-control @error('no_hp') is-invalid @enderror" 
                                               id="no_hp" name="no_hp" 
                                               value="{{ old('no_hp', $prestasi->no_hp ?? '') }}" 
                                               placeholder="08xxxxxxxxxx" required maxlength="15">
                                    </div>
                                    @error('no_hp')
                                        <div class="invalid-feedback d-block">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>

                            <!-- Semester -->
                            <div class="col-md-6 mb-4">
                                <label for="semester" class="form-label required">Semester</label>
                                <div class="input-group">
                                    <span class="input-group-text"><i class="fas fa-graduation-cap"></i></span>
                                    <select class="form-select @error('semester') is-invalid @enderror" 
                                            id="semester" name="semester" required>
                                        <option value="" selected disabled>Pilih semester</option>
                                        @for($i = 1; $i <= 8; $i++)
                                            <option value="{{ $i }}" 
                                                {{ old('semester', $prestasi->semester ?? '') == $i ? 'selected' : '' }}>
                                                Semester {{ $i }}
                                            </option>
                                        @endfor
                                    </select>
                                </div>
                                @error('semester')
                                    <div class="invalid-feedback d-block">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <div class="d-flex justify-content-between mt-4">
                            <a href="{{ route('admin.prestasi.index') }}" class="btn btn-outline-secondary">
                                <i class="fas fa-arrow-left me-2"></i>Kembali
                            </a>
                            <button type="button" class="btn btn-primary next-step" data-next="2">
                                Lanjut <i class="fas fa-arrow-right ms-2"></i>
                            </button>
                        </div>
                    </div>

                    <!-- Step 2: Detail Prestasi -->
                    <div class="form-step" data-step="2">
                        <div class="form-section mb-5">
                            <div class="section-header mb-4">
                                <div class="step-badge">2</div>
                                <h5 class="section-title mb-0">
                                    <i class="fas fa-trophy me-2"></i>Detail Prestasi
                                </h5>
                            </div>
                            
                            <!-- Nama Prestasi -->
                            <div class="mb-4">
                                <label for="nama_prestasi" class="form-label required">Nama Prestasi</label>
                                <div class="input-group">
                                    <span class="input-group-text"><i class="fas fa-award"></i></span>
                                    <input type="text" class="form-control @error('nama_prestasi') is-invalid @enderror" 
                                           id="nama_prestasi" name="nama_prestasi" 
                                           value="{{ old('nama_prestasi', $prestasi->nama_prestasi ?? $prestasi->nama ?? '') }}" 
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
                                        <option value="Akademik" {{ old('kategori', $prestasi->kategori ?? '') == 'Akademik' ? 'selected' : '' }}>Akademik</option>
                                        <option value="Non-Akademik" {{ old('kategori', $prestasi->kategori ?? '') == 'Non-Akademik' ? 'selected' : '' }}>Non-Akademik</option>
                                        <option value="Olahraga" {{ old('kategori', $prestasi->kategori ?? '') == 'Olahraga' ? 'selected' : '' }}>Olahraga</option>
                                        <option value="Seni" {{ old('kategori', $prestasi->kategori ?? '') == 'Seni' ? 'selected' : '' }}>Seni</option>
                                        <option value="Teknologi" {{ old('kategori', $prestasi->kategori ?? '') == 'Teknologi' ? 'selected' : '' }}>Teknologi</option>
                                        <option value="Lainnya" {{ old('kategori', $prestasi->kategori ?? '') == 'Lainnya' ? 'selected' : '' }}>Lainnya</option>
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
                                               value="{{ old('tanggal_mulai', isset($prestasi) ? $prestasi->tanggal_mulai->format('Y-m-d') : '') }}" required>
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
                                               value="{{ old('tanggal_selesai', isset($prestasi) ? $prestasi->tanggal_selesai->format('Y-m-d') : '') }}" required>
                                    </div>
                                    @error('tanggal_selesai')
                                        <div class="invalid-feedback d-block">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>

                            <!-- IPK -->
                            <div class="mb-4">
                                <label for="ipk" class="form-label">IPK (Indeks Prestasi Kumulatif)</label>
                                <div class="input-group">
                                    <span class="input-group-text"><i class="fas fa-chart-line"></i></span>
                                    <input type="number" step="0.01" min="0" max="4" 
                                           class="form-control @error('ipk') is-invalid @enderror" 
                                           id="ipk" name="ipk" 
                                           value="{{ old('ipk', $prestasi->ipk ?? '') }}" 
                                           placeholder="Contoh: 3.75">
                                </div>
                                <div class="form-text">
                                    <i class="fas fa-info-circle me-1"></i>
                                    Opsional. Kosongkan jika tidak relevan dengan prestasi
                                </div>
                                @error('ipk')
                                    <div class="invalid-feedback d-block">{{ $message }}</div>
                                @enderror
                            </div>

                            <!-- Deskripsi Prestasi -->
                            <div class="mb-4">
                                <label for="deskripsi" class="form-label required">Deskripsi Prestasi</label>
                                <textarea class="form-control @error('deskripsi') is-invalid @enderror" 
                                          id="deskripsi" name="deskripsi" 
                                          rows="5" 
                                          placeholder="Jelaskan prestasi yang diraih secara lengkap, termasuk tingkat kompetisi, pencapaian, penyelenggara, dan detail lainnya" 
                                          required>{{ old('deskripsi', $prestasi->deskripsi ?? '') }}</textarea>
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

                    <!-- Step 3: Pengaturan -->
                    <div class="form-step" data-step="3">
                        <div class="form-section mb-5">
                            <div class="section-header mb-4">
                                <div class="step-badge">3</div>
                                <h5 class="section-title mb-0">
                                    <i class="fas fa-cog me-2"></i>Pengaturan
                                </h5>
                            </div>
                            
                            <!-- Status -->
                            <div class="mb-4">
                                <label for="status" class="form-label required">Status Prestasi</label>
                                <div class="input-group">
                                    <span class="input-group-text"><i class="fas fa-check-circle"></i></span>
                                    <select class="form-select @error('status') is-invalid @enderror" 
                                            id="status" name="status" required>
                                        <option value="Menunggu Validasi" {{ old('status', $prestasi->status ?? '') == 'Menunggu Validasi' ? 'selected' : '' }}>Menunggu Validasi</option>
                                        <option value="Tervalidasi" {{ old('status', $prestasi->status ?? '') == 'Tervalidasi' ? 'selected' : '' }}>Tervalidasi</option>
                                        <option value="Ditolak" {{ old('status', $prestasi->status ?? '') == 'Ditolak' ? 'selected' : '' }}>Ditolak</option>
                                    </select>
                                </div>
                                @error('status')
                                    <div class="invalid-feedback d-block">{{ $message }}</div>
                                @enderror
                            </div>

                            <!-- Bukti Prestasi -->
                            <div class="mb-4">
                                <label for="bukti" class="form-label">
                                    Bukti Prestasi
                                    @if(!isset($prestasi))
                                        <span class="required"></span>
                                    @endif
                                </label>
                                
                                @if(isset($prestasi) && $prestasi->bukti)
                                    <div class="alert alert-info mb-3">
                                        <div class="d-flex align-items-center">
                                            <i class="fas fa-file me-2"></i>
                                            <div>
                                                <strong>File saat ini:</strong>
                                                <a href="{{ Storage::url($prestasi->bukti) }}" target="_blank" class="ms-2">
                                                    Lihat Bukti Prestasi
                                                </a>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="form-text mb-3">
                                        <i class="fas fa-info-circle me-1"></i>
                                        Kosongkan jika tidak ingin mengubah file bukti
                                    </div>
                                @endif
                                
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
                                    <input type="file" class="file-input @error('bukti') is-invalid @enderror" 
                                           id="bukti" name="bukti" 
                                           accept=".pdf,.jpg,.jpeg,.png"
                                           {{ !isset($prestasi) ? 'required' : '' }}>
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
                                
                                @error('bukti')
                                    <div class="invalid-feedback d-block">{{ $message }}</div>
                                @enderror
                            </div>

                            <!-- Informasi Tambahan -->
                            <div class="alert alert-info">
                                <div class="d-flex">
                                    <i class="fas fa-info-circle mt-1 me-3"></i>
                                    <div>
                                        <strong>Informasi Administrator</strong>
                                        <p class="mb-0 mt-1">
                                            Pastikan semua data sudah benar sebelum menyimpan. 
                                            Status prestasi dapat diubah kapan saja melalui panel admin.
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
                                <i class="fas fa-save me-2"></i>
                                {{ isset($prestasi) ? 'Update Prestasi' : 'Simpan Prestasi' }}
                            </button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection

@push('styles')
<style>
:root {
    --primary: #4361ee;
    --secondary: #3f37c9;
    --success: #4cc9f0;
    --light-bg: #f8f9ff;
    --border: #e1e5ee;
    --radius: 12px;
}

.required::after {
    content: " *";
    color: #dc3545;
}

.hero-section {
    background: linear-gradient(135deg, #4361ee 0%, #3a0ca3 100%);
    color: white;
    padding: 2rem 0;
    border-radius: var(--radius);
    margin-bottom: 2rem;
}

.page-title {
    font-weight: 700;
    font-size: 1.75rem;
}

.admin-badges .badge {
    font-size: 0.8rem;
    padding: 0.5rem 1rem;
}

.card {
    border: none;
    border-radius: var(--radius);
    box-shadow: 0 10px 30px rgba(0,0,0,0.1);
}

.card-header {
    border-radius: var(--radius) var(--radius) 0 0 !important;
    font-weight: 600;
}

/* Progress Steps */
.progress-steps {
    margin: 2rem 0;
}

.step-progress {
    display: flex;
    justify-content: space-between;
    align-items: center;
    position: relative;
    max-width: 600px;
    margin: 0 auto;
}

.step-progress::before {
    content: '';
    position: absolute;
    top: 25px;
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

.step-icon {
    width: 50px;
    height: 50px;
    border-radius: 50%;
    background: #e9ecef;
    display: flex;
    align-items: center;
    justify-content: center;
    margin-bottom: 0.5rem;
    transition: all 0.3s ease;
    border: 3px solid white;
}

.step.active .step-icon {
    background: var(--primary);
    color: white;
    transform: scale(1.1);
}

.step-label {
    font-weight: 600;
    color: #6c757d;
    font-size: 0.875rem;
    text-align: center;
}

.step.active .step-label {
    color: var(--primary);
}

/* Form Steps */
.form-step {
    display: none;
}

.form-step.active {
    display: block;
    animation: fadeIn 0.5s ease;
}

@keyframes fadeIn {
    from { opacity: 0; transform: translateY(20px); }
    to { opacity: 1; transform: translateY(0); }
}

.section-header {
    display: flex;
    align-items: center;
    gap: 1rem;
}

.step-badge {
    width: 35px;
    height: 35px;
    border-radius: 50%;
    background: var(--primary);
    color: white;
    display: flex;
    align-items: center;
    justify-content: center;
    font-weight: 600;
    font-size: 0.875rem;
}

.form-section {
    background: var(--light-bg);
    padding: 2rem;
    border-radius: var(--radius);
    border-left: 4px solid var(--primary);
}

.section-title {
    color: var(--primary);
    font-weight: 600;
    font-size: 1.1rem;
}

.form-control, .form-select {
    border-radius: 8px;
    padding: 12px 15px;
    border: 2px solid var(--border);
    transition: all 0.3s ease;
}

.form-control:focus, .form-select:focus {
    border-color: var(--primary);
    box-shadow: 0 0 0 0.2rem rgba(67, 97, 238, 0.25);
}

.form-label {
    font-weight: 600;
    color: #495057;
    margin-bottom: 0.5rem;
}

.form-text {
    color: #6c757d;
    font-size: 0.875rem;
    margin-top: 0.5rem;
}

.input-group-text {
    background: white;
    border: 2px solid var(--border);
    border-right: none;
}

.input-group .form-control {
    border-left: none;
}

.input-group .form-control:focus {
    border-left: none;
}

/* File Upload */
.file-upload-area {
    border: 3px dashed var(--border);
    border-radius: var(--radius);
    padding: 3rem 2rem;
    text-align: center;
    background: white;
    cursor: pointer;
    transition: all 0.3s ease;
    position: relative;
}

.file-upload-area:hover {
    border-color: var(--primary);
    background: rgba(67, 97, 238, 0.05);
}

.file-upload-area.dragover {
    border-color: var(--primary);
    background: rgba(67, 97, 238, 0.1);
}

.file-input {
    position: absolute;
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;
    opacity: 0;
    cursor: pointer;
}

.file-types {
    margin: 1rem 0;
}

/* Buttons */
.btn {
    border-radius: 8px;
    padding: 12px 30px;
    font-weight: 600;
    transition: all 0.3s ease;
    border: none;
}

.btn-primary {
    background: linear-gradient(135deg, #4361ee 0%, #3a0ca3 100%);
}

.btn-primary:hover {
    transform: translateY(-2px);
    box-shadow: 0 5px 15px rgba(67, 97, 238, 0.4);
}

.btn-success {
    background: linear-gradient(135deg, #4cc9f0 0%, #4361ee 100%);
}

.btn-success:hover {
    transform: translateY(-2px);
    box-shadow: 0 5px 15px rgba(76, 201, 240, 0.4);
}

.btn-outline-secondary {
    border: 2px solid #6c757d;
    color: #6c757d;
}

.btn-outline-secondary:hover {
    background: #6c757d;
    color: white;
}

.alert {
    border-radius: 8px;
    border: none;
}

.invalid-feedback {
    font-weight: 500;
}

/* Responsive */
@media (max-width: 768px) {
    .hero-section {
        padding: 1.5rem;
    }
    
    .page-title {
        font-size: 1.5rem;
    }
    
    .form-section {
        padding: 1.5rem;
    }
    
    .step-progress {
        flex-direction: column;
        gap: 1rem;
    }
    
    .step-progress::before {
        display: none;
    }
    
    .step {
        flex-direction: row;
        gap: 1rem;
        width: 100%;
    }
    
    .step-icon {
        margin-bottom: 0;
    }
    
    .step-label {
        text-align: left;
    }
    
    .btn {
        padding: 10px 20px;
    }
    
    .file-upload-area {
        padding: 2rem 1rem;
    }
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
            // Scroll to first invalid input
            const firstInvalid = currentStepElement.querySelector('.is-invalid');
            if (firstInvalid) {
                firstInvalid.scrollIntoView({ behavior: 'smooth', block: 'center' });
                firstInvalid.focus();
            }
        }

        return isValid;
    }

    // Character counter for description
    const deskripsi = document.getElementById('deskripsi');
    const charCount = document.getElementById('charCount');
    
    if (deskripsi && charCount) {
        charCount.textContent = deskripsi.value.length;
        
        deskripsi.addEventListener('input', function() {
            const count = this.value.length;
            charCount.textContent = count;
            
            if (count > 500) {
                charCount.classList.add('text-danger');
            } else {
                charCount.classList.remove('text-danger');
            }
        });
    }

    // File upload functionality
    const fileUploadArea = document.getElementById('fileUploadArea');
    const fileInput = document.getElementById('bukti');
    const filePreview = document.getElementById('filePreview');
    const fileName = document.getElementById('fileName');
    const removeFile = document.getElementById('removeFile');

    if (fileUploadArea && fileInput) {
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
                // Validate file size (5MB)
                if (file.size > 5 * 1024 * 1024) {
                    alert('Ukuran file maksimal 5MB');
                    fileInput.value = '';
                    return;
                }

                // Validate file type
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

        if (removeFile) {
            removeFile.addEventListener('click', function() {
                fileInput.value = '';
                filePreview.classList.add('d-none');
            });
        }
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

    // Auto-format inputs
    const nimInput = document.getElementById('nim');
    if (nimInput) {
        nimInput.addEventListener('input', function() {
            this.value = this.value.replace(/[^0-9]/g, '');
        });
    }

    const noHpInput = document.getElementById('no_hp');
    if (noHpInput) {
        noHpInput.addEventListener('input', function() {
            this.value = this.value.replace(/[^0-9+]/g, '');
        });
    }

    // Form submission
    const prestasiForm = document.getElementById('prestasiForm');
    if (prestasiForm) {
        prestasiForm.addEventListener('submit', function(e) {
            if (!validateStep(currentStep)) {
                e.preventDefault();
                alert('Silakan lengkapi semua field yang diperlukan');
            }
        });
    }
});
</script>
@endpush