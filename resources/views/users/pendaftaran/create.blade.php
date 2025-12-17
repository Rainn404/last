@extends('layouts.app')

@section('title', 'Form Pendaftaran - HIMA-TI')

@section('content')
<!-- Page Header -->
<section class="pendaftaran-header">
    <div class="pendaftaran-container">
        <div class="header-content">
            <h1 class="header-title">Form Pendaftaran Anggota HIMA-TI</h1>
            <p class="header-description">Isi form berikut dengan data yang benar dan lengkap</p>
        </div>
    </div>
</section>

<!-- Registration Form -->
<section class="pendaftaran-section">
    <div class="pendaftaran-container">
        <div class="pendaftaran-card">
            @if($errors->any())
                <div class="alert-message error">
                    <div class="alert-icon">
                        <i class="fas fa-exclamation-circle"></i>
                    </div>
                    <div class="alert-content">
                        <h4 class="alert-title">Terjadi Kesalahan</h4>
                        <p class="alert-description">Terdapat kesalahan dalam pengisian form. Silakan periksa kembali.</p>
                    </div>
                </div>
            @endif

            <!-- Registration Info -->
            <div class="pendaftaran-info">
                <div class="info-grid">
                    <div class="info-card">
                        <div class="info-icon">
                            <i class="fas fa-calendar-check"></i>
                        </div>
                        <div class="info-content">
                            <h4 class="info-title">Periode Pendaftaran</h4>
                            <p class="info-description">{{ \Carbon\Carbon::parse($settings->tanggal_mulai)->format('d M Y') }} - {{ \Carbon\Carbon::parse($settings->tanggal_selesai)->format('d M Y') }}</p>
                        </div>
                    </div>
                    <div class="info-card">
                        <div class="info-icon">
                            <i class="fas fa-users"></i>
                        </div>
                        <div class="info-content">
                            <h4 class="info-title">Kuota Tersedia</h4>
                            <p class="info-description"><span id="kuotaTersisa">{{ $kuotaTersisa }}</span> dari {{ $settings->kuota }} anggota</p>
                        </div>
                    </div>
                    <div class="info-card">
                        <div class="info-icon">
                            <i class="fas fa-clock"></i>
                        </div>
                        <div class="info-content">
                            <h4 class="info-title">Status</h4>
                            <span class="status-badge status-open">Pendaftaran Dibuka</span>
                        </div>
                    </div>
                </div>
            </div>

            <form action="{{ route('pendaftaran.store') }}" method="POST" class="pendaftaran-form" enctype="multipart/form-data">
                @csrf
                
                <!-- Data Pribadi -->
                <div class="form-section">
                    <div class="section-header">
                        <div class="section-icon">
                            <i class="fas fa-user"></i>
                        </div>
                        <div class="section-content">
                            <h3 class="section-title">Data Pribadi</h3>
                            <p class="section-description">Isi data diri Anda dengan benar</p>
                        </div>
                    </div>
                    <div class="form-grid">
                        <div class="form-field">
                            <label for="nama" class="field-label">Nama Lengkap *</label>
                            <input type="text" id="nama" name="nama" value="{{ old('nama') }}" class="field-input" required>
                            @error('nama')
                                <span class="field-error">{{ $message }}</span>
                            @enderror
                        </div>

                        <div class="form-field">
                            <label for="nim" class="field-label">NIM *</label>
                            <input type="text" id="nim" name="nim" value="{{ old('nim') }}" class="field-input" required>
                            @error('nim')
                                <span class="field-error">{{ $message }}</span>
                            @enderror
                        </div>

                        <div class="form-field">
                            <label for="semester" class="field-label">Semester *</label>
                            <select id="semester" name="semester" class="field-select" required>
                                <option value="">Pilih Semester</option>
                                @for($i = 1; $i <= 8; $i++)
                                    <option value="{{ $i }}" {{ old('semester') == $i ? 'selected' : '' }}>Semester {{ $i }}</option>
                                @endfor
                            </select>
                            @error('semester')
                                <span class="field-error">{{ $message }}</span>
                            @enderror
                        </div>

                        <div class="form-field">
                            <label for="no_hp" class="field-label">Nomor HP/WhatsApp *</label>
                            <input type="tel" id="no_hp" name="no_hp" value="{{ old('no_hp') }}" class="field-input" required>
                            @error('no_hp')
                                <span class="field-error">{{ $message }}</span>
                            @enderror
                        </div>
                    </div>
                </div>

                <!-- Alasan Mendaftar -->
                <div class="form-section">
                    <div class="section-header">
                        <div class="section-icon">
                            <i class="fas fa-edit"></i>
                        </div>
                        <div class="section-content">
                            <h3 class="section-title">Alasan Mendaftar</h3>
                            <p class="section-description">Ceritakan mengapa Anda ingin bergabung</p>
                        </div>
                    </div>
                    <div class="form-field">
                        <label for="alasan_mendaftar" class="field-label">Mengapa Anda ingin bergabung dengan HIMA-TI? *</label>
                        <textarea id="alasan_mendaftar" name="alasan_mendaftar" class="field-textarea" rows="5" required>{{ old('alasan_mendaftar') }}</textarea>
                        <div class="field-help">
                            <span class="char-count">0</span> karakter (minimal 50 karakter)
                        </div>
                        @error('alasan_mendaftar')
                            <span class="field-error">{{ $message }}</span>
                        @enderror
                    </div>
                </div>

                <!-- Pilihan Divisi -->
                <div class="form-section">
                    <div class="section-header">
                        <div class="section-icon">
                            <i class="fas fa-sitemap"></i>
                        </div>
                        <div class="section-content">
                            <h3 class="section-title">Pilihan Divisi</h3>
                            <p class="section-description">Pilih divisi yang ingin Anda ikuti</p>
                        </div>
                    </div>
                    <div class="form-field">
                        <label for="id_divisi" class="field-label">Divisi *</label>
                        <select id="id_divisi" name="id_divisi" class="field-select" required>
                            <option value="">Pilih Divisi</option>
                            @if(isset($divisi) && $divisi->count() > 0)
                                @foreach($divisi as $d)
                                    <option value="{{ $d->id_divisi }}" {{ old('id_divisi') == $d->id_divisi ? 'selected' : '' }}>
                                        {{ $d->nama_divisi ?? 'Divisi' }}
                                    </option>
                                @endforeach
                            @endif
                        </select>
                        @error('id_divisi')
                            <span class="field-error">{{ $message }}</span>
                        @enderror
                    </div>

                    <div class="form-field">
                        <label for="alasan_divisi" class="field-label">Alasan Memilih Divisi Ini *</label>
                        <textarea id="alasan_divisi" name="alasan_divisi" class="field-textarea" rows="3" required>{{ old('alasan_divisi') }}</textarea>
                        <div class="field-help">
                            Jelaskan singkat mengapa Anda memilih divisi ini (minimal 20 karakter)
                        </div>
                        @error('alasan_divisi')
                            <span class="field-error">{{ $message }}</span>
                        @enderror
                    </div>
                </div>

                <!-- Pengalaman Organisasi -->
                <div class="form-section">
                    <div class="section-header">
                        <div class="section-icon">
                            <i class="fas fa-briefcase"></i>
                        </div>
                        <div class="section-content">
                            <h3 class="section-title">Pengalaman Organisasi</h3>
                            <p class="section-description">Bagikan pengalaman organisasi Anda</p>
                        </div>
                    </div>
                    <div class="form-field">
                        <label for="pengalaman" class="field-label">Pengalaman Organisasi (Opsional)</label>
                        <textarea id="pengalaman" name="pengalaman" class="field-textarea" rows="4">{{ old('pengalaman') }}</textarea>
                        <div class="field-help">
                            Sebutkan pengalaman organisasi sebelumnya (jika ada)
                        </div>
                        @error('pengalaman')
                            <span class="field-error">{{ $message }}</span>
                        @enderror
                    </div>
                </div>

                <!-- Kemampuan/Keterampilan -->
                <div class="form-section">
                    <div class="section-header">
                        <div class="section-icon">
                            <i class="fas fa-star"></i>
                        </div>
                        <div class="section-content">
                            <h3 class="section-title">Kemampuan/Keterampilan</h3>
                            <p class="section-description">Sebutkan kemampuan yang Anda miliki</p>
                        </div>
                    </div>
                    <div class="form-field">
                        <label for="skill" class="field-label">Kemampuan atau Keterampilan (Opsional)</label>
                        <textarea id="skill" name="skill" class="field-textarea" rows="4">{{ old('skill') }}</textarea>
                        <div class="field-help">
                            Sebutkan kemampuan atau keterampilan yang Anda miliki
                        </div>
                        @error('skill')
                            <span class="field-error">{{ $message }}</span>
                        @enderror
                    </div>
                </div>

                <!-- Dokumen Pendukung -->
                <div class="form-section">
                    <div class="section-header">
                        <div class="section-icon">
                            <i class="fas fa-file-upload"></i>
                        </div>
                        <div class="section-content">
                            <h3 class="section-title">Dokumen Pendukung</h3>
                            <p class="section-description">Upload dokumen tambahan (opsional)</p>
                        </div>
                    </div>
                    <div class="form-field">
                        <label for="dokumen" class="field-label">Upload Dokumen (Opsional)</label>
                        <div class="file-upload">
                            <input type="file" id="dokumen" name="dokumen" class="file-input" accept=".pdf,.doc,.docx">
                            <label for="dokumen" class="file-label">
                                <i class="fas fa-cloud-upload-alt file-icon"></i>
                                <span class="file-text">Pilih File</span>
                            </label>
                        </div>
                        <div class="field-help">
                            Format: PDF, DOC, DOCX (Maksimal 2MB)
                        </div>
                        @error('dokumen')
                            <span class="field-error">{{ $message }}</span>
                        @enderror
                    </div>
                </div>

                <!-- Persetujuan -->
                <div class="form-section">
                    <div class="agreement-card">
                        <div class="agreement-content">
                            <h4 class="agreement-title">Persetujuan</h4>
                            <p class="agreement-description">Dengan mencentang kotak di bawah, Anda menyetujui ketentuan berikut:</p>
                            <ul class="agreement-list">
                                <li>Data yang saya berikan adalah benar dan dapat dipertanggungjawabkan</li>
                                <li>Saya siap mengikuti proses seleksi yang ditentukan</li>
                                <li>Saya akan mematuhi peraturan dan tata tertib HIMA-TI</li>
                            </ul>
                        </div>
                        <div class="form-agreement">
                            <label class="agreement-label">
                                <input type="checkbox" id="agree" name="agree" value="1" {{ old('agree') ? 'checked' : '' }} class="agreement-checkbox" required>
                                <span class="checkmark"></span>
                                <span class="agreement-text">Saya menyetujui semua ketentuan di atas</span>
                            </label>
                            @error('agree')
                                <span class="field-error">{{ $message }}</span>
                            @enderror
                        </div>
                    </div>
                </div>

                <!-- Submit Button -->
                <div class="form-actions">
                      <a href="{{ url('/') }}" class="submit-button outline">
                        <i class="fas fa-arrow-left button-icon"></i>
                        <span class="button-text">Kembali</span>
                    </a>
                    <button type="submit" class="submit-button primary" id="submitBtn">
                        <i class="fas fa-paper-plane button-icon"></i>
                        <span class="button-text">Kirim Pendaftaran</span>
                    </button>
                  
                </div>
            </form>
        </div>
    </div>
</section>

<style>
/* Variables */
:root {
    --primary-color: #3B82F6;
    --primary-dark: #1D4ED8;
    --primary-light: #EFF6FF;
    --secondary-color: #10B981;
    --accent-color: #F59E0B;
    --text-dark: #1F2937;
    --text-light: #6B7280;
    --text-lighter: #9CA3AF;
    --white: #FFFFFF;
    --gray-light: #F3F4F6;
    --gray-medium: #E5E7EB;
    --border-color: #D1D5DB;
    --error-color: #DC2626;
    --success-color: #059669;
    --warning-color: #D97706;
    --shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1), 0 2px 4px -1px rgba(0, 0, 0, 0.06);
    --shadow-lg: 0 10px 15px -3px rgba(0, 0, 0, 0.1), 0 4px 6px -2px rgba(0, 0, 0, 0.05);
    --shadow-xl: 0 20px 25px -5px rgba(0, 0, 0, 0.1), 0 10px 10px -5px rgba(0, 0, 0, 0.04);
    --border-radius: 12px;
    --border-radius-lg: 16px;
    --transition: all 0.3s ease;
}

/* Base Styles */
.pendaftaran-container {
    max-width: 1000px;
    margin: 0 auto;
    padding: 0 20px;
}

/* Header Styles */
.pendaftaran-header {
    background: linear-gradient(135deg, var(--primary-color) 0%, var(--primary-dark) 100%);
    padding: 3rem 0;
    text-align: center;
    color: var(--white);
}

.header-title {
    font-size: 2.5rem;
    font-weight: 700;
    margin-bottom: 1rem;
    line-height: 1.2;
}

.header-description {
    font-size: 1.1rem;
    opacity: 0.9;
    max-width: 600px;
    margin: 0 auto;
    line-height: 1.6;
}

/* Main Section */
.pendaftaran-section {
    padding: 3rem 0;
    background: linear-gradient(135deg, var(--primary-light) 0%, var(--gray-light) 100%);
    min-height: 100vh;
}

.pendaftaran-card {
    background: var(--white);
    border-radius: var(--border-radius-lg);
    box-shadow: var(--shadow-xl);
    overflow: hidden;
}

/* Alert Message */
.alert-message {
    display: flex;
    align-items: flex-start;
    gap: 1rem;
    padding: 1.5rem;
    margin: 2rem;
    border-radius: var(--border-radius);
    animation: slideInDown 0.3s ease-out;
}

.alert-message.error {
    background: #FEF2F2;
    border: 1px solid #FECACA;
    color: #DC2626;
}

.alert-icon {
    font-size: 1.5rem;
    flex-shrink: 0;
}

.alert-title {
    font-weight: 600;
    margin-bottom: 0.25rem;
}

.alert-description {
    margin: 0;
    font-size: 0.95rem;
}

/* Registration Info */
.pendaftaran-info {
    padding: 2rem;
    background: var(--primary-light);
    border-bottom: 1px solid var(--border-color);
}

.info-grid {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
    gap: 1.5rem;
}

.info-card {
    display: flex;
    align-items: center;
    gap: 1rem;
    padding: 1.5rem;
    background: var(--white);
    border-radius: var(--border-radius);
    box-shadow: var(--shadow);
    transition: var(--transition);
}

.info-card:hover {
    transform: translateY(-3px);
    box-shadow: var(--shadow-lg);
}

.info-icon {
    width: 50px;
    height: 50px;
    background: linear-gradient(135deg, var(--primary-color), var(--primary-dark));
    color: var(--white);
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 1.25rem;
    flex-shrink: 0;
}

.info-title {
    font-size: 0.9rem;
    font-weight: 600;
    color: var(--text-light);
    margin-bottom: 0.25rem;
}

.info-description {
    font-size: 1rem;
    font-weight: 700;
    color: var(--text-dark);
    margin: 0;
}

.status-badge {
    padding: 0.5rem 1rem;
    border-radius: 20px;
    font-size: 0.8rem;
    font-weight: 600;
    text-transform: uppercase;
    letter-spacing: 0.5px;
}

.status-open {
    background: #D1FAE5;
    color: #065F46;
}

/* Form Styles */
.pendaftaran-form {
    padding: 2rem;
}

.form-section {
    margin-bottom: 2.5rem;
    padding-bottom: 2rem;
    border-bottom: 1px solid var(--border-color);
}

.form-section:last-of-type {
    border-bottom: none;
    margin-bottom: 0;
    padding-bottom: 0;
}

.section-header {
    display: flex;
    align-items: flex-start;
    gap: 1rem;
    margin-bottom: 1.5rem;
}

.section-icon {
    width: 50px;
    height: 50px;
    background: var(--primary-light);
    color: var(--primary-color);
    border-radius: 12px;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 1.25rem;
    flex-shrink: 0;
}

.section-title {
    font-size: 1.5rem;
    font-weight: 600;
    color: var(--text-dark);
    margin-bottom: 0.5rem;
}

.section-description {
    color: var(--text-light);
    margin: 0;
    font-size: 0.95rem;
}

.form-grid {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
    gap: 1.5rem;
}

.form-field {
    margin-bottom: 1.5rem;
}

.field-label {
    display: block;
    font-weight: 600;
    color: var(--text-dark);
    margin-bottom: 0.5rem;
    font-size: 0.95rem;
}

.field-input,
.field-select,
.field-textarea {
    width: 100%;
    padding: 1rem 1.25rem;
    border: 2px solid var(--gray-medium);
    border-radius: var(--border-radius);
    font-size: 1rem;
    transition: var(--transition);
    background-color: var(--white);
    font-family: inherit;
}

.field-input:focus,
.field-select:focus,
.field-textarea:focus {
    outline: none;
    border-color: var(--primary-color);
    box-shadow: 0 0 0 3px rgba(59, 130, 246, 0.1);
}

.field-textarea {
    resize: vertical;
    min-height: 120px;
    line-height: 1.5;
}

.field-help {
    margin-top: 0.5rem;
    color: var(--text-light);
    font-size: 0.85rem;
    display: flex;
    justify-content: space-between;
    align-items: center;
}

.char-count {
    font-weight: 600;
}

.char-count.low {
    color: var(--error-color);
}

.char-count.good {
    color: var(--success-color);
}

.field-error {
    display: block;
    margin-top: 0.5rem;
    color: var(--error-color);
    font-size: 0.85rem;
    font-weight: 500;
}

/* File Upload */
.file-upload {
    position: relative;
    margin-bottom: 0.5rem;
}

.file-input {
    position: absolute;
    opacity: 0;
    width: 0;
    height: 0;
}

.file-label {
    display: inline-flex;
    align-items: center;
    gap: 0.75rem;
    padding: 1rem 1.5rem;
    background: var(--primary-light);
    border: 2px dashed var(--primary-color);
    border-radius: var(--border-radius);
    cursor: pointer;
    transition: var(--transition);
    color: var(--primary-color);
    font-weight: 500;
}

.file-label:hover {
    background: var(--primary-color);
    color: var(--white);
}

.file-icon {
    font-size: 1.25rem;
}

/* Agreement Card */
.agreement-card {
    background: var(--primary-light);
    padding: 2rem;
    border-radius: var(--border-radius);
    border: 1px solid var(--border-color);
}

.agreement-title {
    font-size: 1.25rem;
    font-weight: 600;
    color: var(--text-dark);
    margin-bottom: 0.75rem;
}

.agreement-description {
    color: var(--text-light);
    margin-bottom: 1rem;
    font-size: 0.95rem;
}

.agreement-list {
    margin: 1rem 0;
    padding-left: 1.5rem;
    color: var(--text-dark);
}

.agreement-list li {
    margin-bottom: 0.5rem;
    line-height: 1.5;
}

.form-agreement {
    margin-top: 1.5rem;
}

.agreement-label {
    display: flex;
    align-items: flex-start;
    gap: 1rem;
    cursor: pointer;
    font-weight: 600;
    color: var(--text-dark);
}

.agreement-checkbox {
    position: absolute;
    opacity: 0;
}

.checkmark {
    width: 20px;
    height: 20px;
    border: 2px solid var(--border-color);
    border-radius: 4px;
    position: relative;
    flex-shrink: 0;
    margin-top: 0.125rem;
    transition: var(--transition);
}

.agreement-checkbox:checked + .checkmark {
    background: var(--primary-color);
    border-color: var(--primary-color);
}

.agreement-checkbox:checked + .checkmark::after {
    content: '✓';
    position: absolute;
    color: var(--white);
    font-size: 14px;
    top: 50%;
    left: 50%;
    transform: translate(-50%, -50%);
}

.agreement-text {
    flex: 1;
    line-height: 1.5;
}

/* Form Actions */
.form-actions {
    display: flex;
    gap: 1rem;
    justify-content: center;
    margin-top: 2rem;
    padding-top: 2rem;
    border-top: 1px solid var(--border-color);
}

.submit-button {
    padding: 1rem 2rem;
    border-radius: var(--border-radius);
    font-size: 1rem;
    font-weight: 600;
    text-decoration: none;
    display: inline-flex;
    align-items: center;
    gap: 0.75rem;
    transition: var(--transition);
    border: none;
    cursor: pointer;
    min-width: 180px;
    justify-content: center;
}

.submit-button.primary {
    background: linear-gradient(135deg, var(--primary-color), var(--primary-dark));
    color: var(--white);
}

.submit-button.primary:hover {
    transform: translateY(-2px);
    box-shadow: var(--shadow-lg);
}

.submit-button.outline {
    background: transparent;
    color: var(--primary-color);
    border: 2px solid var(--primary-color);
}

.submit-button.outline:hover {
    background: var(--primary-color);
    color: var(--white);
    transform: translateY(-2px);
}

.button-icon {
    font-size: 1rem;
}

/* Animations */
@keyframes slideInDown {
    from {
        opacity: 0;
        transform: translateY(-20px);
    }
    to {
        opacity: 1;
        transform: translateY(0);
    }
}

/* Responsive Design */
@media (max-width: 768px) {
    .pendaftaran-container {
        padding: 0 1rem;
    }
    
    .header-title {
        font-size: 2rem;
    }
    
    .pendaftaran-form {
        padding: 1.5rem;
    }
    
    .info-grid {
        grid-template-columns: 1fr;
    }
    
    .form-grid {
        grid-template-columns: 1fr;
    }
    
    .form-actions {
        flex-direction: column;
    }
    
    .submit-button {
        width: 100%;
    }
    
    .section-header {
        flex-direction: column;
        text-align: center;
        gap: 0.75rem;
    }
    
    .section-icon {
        align-self: center;
    }
}

@media (max-width: 480px) {
    .pendaftaran-header {
        padding: 2rem 0;
    }
    
    .header-title {
        font-size: 1.75rem;
    }
    
    .header-description {
        font-size: 1rem;
    }
    
    .pendaftaran-section {
        padding: 2rem 0;
    }
    
    .pendaftaran-form {
        padding: 1rem;
    }
    
    .info-card {
        flex-direction: column;
        text-align: center;
        padding: 1rem;
    }
    
    .agreement-card {
        padding: 1.5rem;
    }
}
</style>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const alasanTextarea = document.getElementById('alasan_mendaftar');
    const charCount = document.querySelector('.char-count');
    
    // Character counter
    function updateCharCount() {
        const count = alasanTextarea.value.length;
        charCount.textContent = count;
        if (count < 50) {
            charCount.classList.add('low');
            charCount.classList.remove('good');
        } else {
            charCount.classList.remove('low');
            charCount.classList.add('good');
        }
    }
    
    alasanTextarea.addEventListener('input', updateCharCount);
    updateCharCount();
    
    // File upload label
    const fileInput = document.getElementById('dokumen');
    const fileText = document.querySelector('.file-text');
    if (fileInput) {
        fileInput.addEventListener('change', function() {
            fileText.textContent = this.files.length > 0 ? this.files[0].name : 'Pilih File';
        });
    }
    
    // ✅ SIMPLE FORM VALIDATION
    const form = document.querySelector('.pendaftaran-form');
    const submitBtn = document.getElementById('submitBtn');
    let isSubmitting = false;
    let formSubmitted = false; // Global flag for quota polling
    
    form.addEventListener('submit', function(e) {
        console.log('%c>>> FORM SUBMIT STARTED', 'color: blue; font-weight: bold;');
        
        // Prevent double submission
        if (isSubmitting) {
            console.log('%c>>> PREVENTING DOUBLE SUBMIT', 'color: red;');
            e.preventDefault();
            return;
        }
        
        // VALIDATION
        let valid = true;
        const requiredFields = form.querySelectorAll('[required]');
        
        requiredFields.forEach(field => {
            const isEmpty = field.type === 'checkbox' ? !field.checked : !field.value.trim();
            
            if (isEmpty) {
                console.log('%c>>> VALIDATION ERROR: ' + field.name, 'color: orange;');
                valid = false;
                field.style.borderColor = 'var(--error-color)';
            } else {
                console.log('%c>>> FIELD OK: ' + field.name, 'color: green;');
                field.style.borderColor = '';
            }
        });
        
        // Check alasan length
        if (alasanTextarea.value.length < 50) {
            console.log('%c>>> VALIDATION ERROR: alasan too short', 'color: orange;');
            valid = false;
            alasanTextarea.style.borderColor = 'var(--error-color)';
            alert('Alasan mendaftar harus minimal 50 karakter');
        }
        
        if (!valid) {
            console.log('%c>>> VALIDATION FAILED - NOT SUBMITTING', 'color: red; font-weight: bold;');
            e.preventDefault();
            return;
        }
        
        // ✅ VALIDATION PASSED
        console.log('%c>>> VALIDATION PASSED - ALLOWING FORM SUBMIT', 'color: green; font-weight: bold;');
        
        // Mark as submitting
        isSubmitting = true;
        formSubmitted = true;
        submitBtn.disabled = true;
        submitBtn.innerHTML = '<i class="fas fa-spinner fa-spin button-icon"></i><span class="button-text">Mengirim...</span>';
        
        console.log('%c>>> FLAGS SET: isSubmitting=true, formSubmitted=true', 'color: blue;');
        console.log('%c>>> BUTTON DISABLED AND LOADING SHOWN', 'color: blue;');
        console.log('%c>>> FORM WILL NOW SUBMIT NORMALLY TO SERVER', 'color: green; font-weight: bold;');
        
        // ✅ DO NOT PREVENT DEFAULT - Let form submit naturally!
        // This is key: we only preventDefault if validation fails
    });
    
    // Real-time field validation
    const inputs = form.querySelectorAll('input, select, textarea');
    inputs.forEach(input => {
        input.addEventListener('blur', function() {
            const isEmpty = this.type === 'checkbox' ? !this.checked : !this.value.trim();
            this.style.borderColor = isEmpty ? 'var(--error-color)' : '';
        });
        
        input.addEventListener('input', function() {
            if (this.value.trim()) {
                this.style.borderColor = 'var(--success-color)';
            }
        });
    });
    
    // Animation styles
    const style = document.createElement('style');
    style.textContent = `
        @keyframes shake {
            0%, 100% { transform: translateX(0); }
            25% { transform: translateX(-5px); }
            75% { transform: translateX(5px); }
        }
    `;
    document.head.appendChild(style);

    // ✅ QUOTA POLLING
    function updateKuota() {
        if (formSubmitted) {
            console.log('%c>>> QUOTA POLLING SKIPPED: formSubmitted=true', 'color: gray;');
            return;
        }
        
        fetch('{{ route("api.pendaftaran-status") }}')
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    const elem = document.getElementById('kuotaTersisa');
                    if (elem) {
                        elem.textContent = data.sisa_kuota;
                        console.log('%c>>> QUOTA UPDATED: ' + data.sisa_kuota, 'color: blue;');
                        
                        if (data.is_quota_full && !formSubmitted) {
                            console.log('%c>>> QUOTA FULL - REDIRECTING', 'color: red; font-weight: bold;');
                            window.location.href = '{{ route("pendaftaran.quota-full") }}';
                        }
                    }
                }
            })
            .catch(error => console.log('%c>>> QUOTA ERROR: ' + error, 'color: red;'));
    }

    setInterval(updateKuota, 5000);
    console.log('%c>>> QUOTA POLLING INITIALIZED (every 5 seconds)', 'color: blue;');
});
</script>
@endsection