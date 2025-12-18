@extends('layouts.app')

@section('title', 'Pendaftaran Anggota - HIMA-TI')

@section('content')
<link rel="stylesheet" href="{{ asset('css/glassmorphism.css') }}">

<style>
    /* Fixed background image */
    body {
        background-image: url('/logo_bg/gedung politala');
        background-attachment: fixed;
        background-position: center;
        background-repeat: no-repeat;
        background-size: cover;
    }

    /* Overlay backdrop */
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

    /* Content layer */
    main, section {
        position: relative;
        z-index: 2;
    }

    /* ===== PAGE HEADER - GLASSMORPHISM ===== */
    .pendaftaran-header {
        background: rgba(255, 255, 255, 0.12);
        backdrop-filter: blur(15px);
        border-bottom: 1px solid rgba(224, 231, 255, 0.25);
        color: #F8FAFC;
        text-align: center;
        padding: 80px 20px;
        margin-top: 70px;
    }

    .pendaftaran-container {
        max-width: 1200px;
        margin: 0 auto;
        padding: 0 15px;
        position: relative;
        z-index: 2;
    }

    .header-content {
        display: flex;
        flex-direction: column;
        align-items: center;
        justify-content: center;
    }

    .header-title {
        font-weight: 800;
        font-size: 2.5rem;
        margin-bottom: 15px;
        text-transform: uppercase;
        text-shadow: 0 2px 4px rgba(2, 6, 23, 0.2);
        color: #F8FAFC;
    }

    .header-description {
        max-width: 700px;
        font-size: 1.1rem;
        opacity: 0.95;
        color: #E0E7FF;
    }

    /* ===== SECTION STYLING ===== */
    .pendaftaran-process,
    .pendaftaran-form-section {
        padding: 60px 20px;
        position: relative;
        z-index: 2;
    }

    .section-title {
        text-align: center;
        font-weight: 700;
        font-size: 1.8rem;
        margin: 0 0 40px;
        color: #F8FAFC;
        text-shadow: 0 2px 4px rgba(2, 6, 23, 0.2);
    }

    /* ===== PROCESS CARDS - GLASSMORPHISM ===== */
    .process-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(280px, 1fr));
        gap: 24px;
        margin-bottom: 40px;
    }

    .process-card {
        background: rgba(255, 255, 255, 0.18);
        backdrop-filter: blur(8px);
        border: 1px solid rgba(224, 231, 255, 0.30);
        border-radius: 16px;
        padding: 32px 24px;
        text-align: center;
        transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        box-shadow: 0 4px 16px rgba(2, 6, 23, 0.08);
    }

    .process-card:hover {
        background: rgba(255, 255, 255, 0.25);
        border-color: #6366F1;
        box-shadow: 0 8px 32px rgba(99, 102, 241, 0.2);
        transform: translateY(-4px);
    }

    .process-icon {
        margin-bottom: 20px;
        display: flex;
        justify-content: center;
    }

    .step-number {
        display: flex;
        align-items: center;
        justify-content: center;
        width: 60px;
        height: 60px;
        background: linear-gradient(135deg, #6366F1, #4F46E5);
        color: #FFFFFF;
        border-radius: 50%;
        font-weight: 800;
        font-size: 1.5rem;
        box-shadow: 0 4px 16px rgba(99, 102, 241, 0.3);
    }

    .process-title {
        font-weight: 700;
        font-size: 1.2rem;
        margin-bottom: 8px;
        color: #F8FAFC;
    }

    .process-description {
        color: #CBD5E1;
        font-size: 0.95rem;
        line-height: 1.6;
    }

    /* ===== FORM CARD - GLASSMORPHISM ===== */
    .form-card {
        background: rgba(255, 255, 255, 0.18);
        backdrop-filter: blur(8px);
        border: 1px solid rgba(224, 231, 255, 0.30);
        border-radius: 16px;
        padding: 40px;
        box-shadow: 0 8px 32px rgba(2, 6, 23, 0.15);
    }

    .form-title {
        font-weight: 700;
        font-size: 1.5rem;
        margin-bottom: 30px;
        color: #F8FAFC;
        text-shadow: 0 2px 4px rgba(2, 6, 23, 0.2);
    }

    /* ===== FORM FIELDS ===== */
    .form-field {
        margin-bottom: 24px;
    }

    .form-row {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
        gap: 24px;
        margin-bottom: 24px;
    }

    .field-label {
        display: block;
        font-weight: 600;
        font-size: 0.95rem;
        margin-bottom: 8px;
        color: #E0E7FF;
    }

    .field-input,
    .field-select,
    .field-textarea {
        width: 100%;
        background: rgba(255, 255, 255, 0.12);
        border: 1px solid rgba(224, 231, 255, 0.30);
        border-radius: 8px;
        color: #F8FAFC;
        padding: 12px 16px;
        font-size: 0.95rem;
        transition: all 0.3s ease;
        font-family: inherit;
        backdrop-filter: blur(8px);
    }

    .field-input::placeholder,
    .field-textarea::placeholder {
        color: #94A3B8;
    }

    .field-input:focus,
    .field-select:focus,
    .field-textarea:focus {
        outline: none;
        background: rgba(255, 255, 255, 0.20);
        border-color: #6366F1;
        box-shadow: 0 0 0 3px rgba(99, 102, 241, 0.15);
    }

    .field-select option {
        background: #1F2937;
        color: #F8FAFC;
    }

    .field-error {
        display: block;
        color: #FCA5A5;
        font-size: 0.85rem;
        margin-top: 4px;
        font-weight: 500;
    }

    /* ===== FORM BUTTONS ===== */
    .form-button,
    .btn-submit,
    .btn-reset {
        background: #6366F1;
        color: #FFFFFF;
        border: none;
        padding: 12px 32px;
        border-radius: 10px;
        font-weight: 600;
        font-size: 0.95rem;
        transition: all 0.3s ease;
        cursor: pointer;
        box-shadow: 0 4px 12px rgba(99, 102, 241, 0.25);
    }

    .form-button:hover,
    .btn-submit:hover,
    .btn-reset:hover {
        background: #4F46E5;
        box-shadow: 0 6px 20px rgba(99, 102, 241, 0.35);
        transform: translateY(-2px);
    }

    .btn-reset {
        background: rgba(255, 255, 255, 0.15);
        color: #F8FAFC;
        border: 1px solid rgba(224, 231, 255, 0.30);
        margin-left: 12px;
    }

    .btn-reset:hover {
        background: rgba(255, 255, 255, 0.25);
        border-color: #6366F1;
        box-shadow: 0 4px 16px rgba(99, 102, 241, 0.2);
    }

    /* ===== SIDEBAR - GLASSMORPHISM ===== */
    .form-sidebar {
        background: rgba(255, 255, 255, 0.12);
        backdrop-filter: blur(8px);
        border: 1px solid rgba(224, 231, 255, 0.25);
        border-radius: 16px;
        padding: 24px;
        margin-bottom: 24px;
    }

    .sidebar-card-title {
        font-weight: 700;
        font-size: 1.1rem;
        margin-bottom: 16px;
        color: #F8FAFC;
        border-bottom: 2px solid rgba(99, 102, 241, 0.3);
        padding-bottom: 12px;
    }

    .sidebar-card-content {
        color: #CBD5E1;
        font-size: 0.9rem;
        line-height: 1.7;
    }

    .sidebar-badge {
        display: inline-block;
        background: rgba(99, 102, 241, 0.2);
        color: #E0E7FF;
        padding: 6px 12px;
        border-radius: 20px;
        font-size: 0.8rem;
        font-weight: 600;
        margin-right: 8px;
        margin-bottom: 8px;
        border: 1px solid #6366F1;
    }

    /* ===== INFO BOX ===== */
    .info-box {
        background: rgba(99, 102, 241, 0.15);
        border-left: 4px solid #6366F1;
        border-radius: 8px;
        padding: 16px;
        margin-bottom: 24px;
        color: #E0E7FF;
    }

    .info-box strong {
        color: #F8FAFC;
    }

    /* ===== RESPONSIVE ===== */
    @media (max-width: 768px) {
        .header-title {
            font-size: 1.8rem;
        }

        .form-card {
            padding: 24px 16px;
        }

        .section-title {
            font-size: 1.5rem;
        }

        .btn-reset {
            margin-left: 0;
            margin-top: 12px;
            width: 100%;
        }

        .process-grid {
            grid-template-columns: 1fr;
        }

        .form-row {
            grid-template-columns: 1fr;
        }
    }
</style>

    <!-- Page Header -->
    <section class="pendaftaran-header">
        <div class="pendaftaran-container">
            <div class="header-content">
                <h1 class="header-title">Pendaftaran Anggota HIMA-TI</h1>
                <p class="header-description">Bergabunglah dengan kami untuk mengembangkan potensi dan berkontribusi dalam dunia teknologi informasi</p>
            </div>
        </div>
    </section>

    <!-- Pendaftaran Process -->
    <section class="pendaftaran-process">
        <div class="pendaftaran-container">
            <h2 class="section-title">Proses Pendaftaran</h2>
            <div class="process-grid">
                <div class="process-card">
                    <div class="process-icon">
                        <div class="step-number">1</div>
                    </div>
                    <div class="process-content">
                        <h3 class="process-title">Isi Formulir</h3>
                        <p class="process-description">Lengkapi formulir pendaftaran dengan data diri yang valid</p>
                    </div>
                </div>
                <div class="process-card">
                    <div class="process-icon">
                        <div class="step-number">2</div>
                    </div>
                    <div class="process-content">
                        <h3 class="process-title">Verifikasi Data</h3>
                        <p class="process-description">Tim kami akan memverifikasi data yang Anda submit</p>
                    </div>
                </div>
                <div class="process-card">
                    <div class="process-icon">
                        <div class="step-number">3</div>
                    </div>
                    <div class="process-content">
                        <h3 class="process-title">Interview</h3>
                        <p class="process-description">Proses wawancara untuk mengenal minat dan bakat Anda</p>
                    </div>
                </div>
                <div class="process-card">
                    <div class="process-icon">
                        <div class="step-number">4</div>
                    </div>
                    <div class="process-content">
                        <h3 class="process-title">Pengumuman</h3>
                        <p class="process-description">Hasil seleksi akan diumumkan melalui email dan website</p>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Form Pendaftaran -->
    <section class="pendaftaran-form-section">
        <div class="pendaftaran-container">
            <div class="form-layout">
                <div class="form-main">
                    <div class="form-card">
                        <h2 class="form-title">Formulir Pendaftaran</h2>
                        <form action="{{ route('pendaftaran.store') }}" method="POST" class="pendaftaran-form" enctype="multipart/form-data">
                            @csrf
                            
                            <div class="form-field">
                                <label for="nama" class="field-label">Nama Lengkap *</label>
                                <input type="text" id="nama" name="nama" value="{{ old('nama') }}" class="field-input" required>
                                @error('nama')
                                    <span class="field-error">{{ $message }}</span>
                                @enderror
                            </div>

                            <div class="form-row">
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
                            </div>

                            <div class="form-row">
                                <div class="form-field">
                                    <label for="email" class="field-label">Email *</label>
                                    <input type="email" id="email" name="email" value="{{ old('email') }}" class="field-input" required>
                                    @error('email')
                                        <span class="field-error">{{ $message }}</span>
                                    @enderror
                                </div>
                                <div class="form-field">
                                    <label for="no_hp" class="field-label">No. HP *</label>
                                    <input type="tel" id="no_hp" name="no_hp" value="{{ old('no_hp') }}" class="field-input" required>
                                    @error('no_hp')
                                        <span class="field-error">{{ $message }}</span>
                                    @enderror
                                </div>
                            </div>

                            <div class="form-field">
                                <label for="divisi" class="field-label">Divisi yang Diminati *</label>
                                <select id="divisi" name="divisi" class="field-select" required>
                                    <option value="">Pilih Divisi</option>
                                    <option value="1" {{ old('divisi') == '1' ? 'selected' : '' }}>Divisi Teknologi</option>
                                    <option value="2" {{ old('divisi') == '2' ? 'selected' : '' }}>Divisi Keanggotaan</option>
                                    <option value="3" {{ old('divisi') == '3' ? 'selected' : '' }}>Divisi Media & Komunikasi</option>
                                    <option value="4" {{ old('divisi') == '4' ? 'selected' : '' }}>Divisi Kewirausahaan</option>
                                </select>
                                @error('divisi')
                                    <span class="field-error">{{ $message }}</span>
                                @enderror
                            </div>

                            <div class="form-field">
                                <label for="pengalaman" class="field-label">Pengalaman Organisasi/Kepanitiaan</label>
                                <textarea id="pengalaman" name="pengalaman" class="field-textarea" rows="3" placeholder="Jelaskan pengalaman organisasi atau kepanitiaan yang pernah diikuti">{{ old('pengalaman') }}</textarea>
                                @error('pengalaman')
                                    <span class="field-error">{{ $message }}</span>
                                @enderror
                            </div>

                            <div class="form-field">
                                <label for="alasan_mendaftar" class="field-label">Alasan Bergabung dengan HIMA-TI *</label>
                                <textarea id="alasan_mendaftar" name="alasan_mendaftar" class="field-textarea" rows="4" required placeholder="Jelaskan alasan Anda ingin bergabung dengan HIMA-TI">{{ old('alasan_mendaftar') }}</textarea>
                                @error('alasan_mendaftar')
                                    <span class="field-error">{{ $message }}</span>
                                @enderror
                            </div>

                            <div class="form-field">
                                <label for="skill" class="field-label">Kemampuan/Keterampilan</label>
                                <textarea id="skill" name="skill" class="field-textarea" rows="3" placeholder="Sebutkan kemampuan atau keterampilan yang Anda miliki">{{ old('skill') }}</textarea>
                                @error('skill')
                                    <span class="field-error">{{ $message }}</span>
                                @enderror
                            </div>

                            <div class="form-field">
                                <label for="dokumen" class="field-label">Upload CV/Portofolio (Opsional)</label>
                                <div class="file-upload">
                                    <input type="file" id="dokumen" name="dokumen" class="file-input" accept=".pdf,.doc,.docx">
                                    <label for="dokumen" class="file-label">
                                        <i class="fas fa-cloud-upload-alt file-icon"></i>
                                        <span class="file-text">Pilih File</span>
                                    </label>
                                </div>
                                <small class="file-hint">Format: PDF, DOC, DOCX (Maks. 2MB)</small>
                                @error('dokumen')
                                    <span class="field-error">{{ $message }}</span>
                                @enderror
                            </div>

                            <div class="form-agreement">
                                <label class="agreement-label">
                                    <input type="checkbox" id="agree" name="agree" class="agreement-checkbox" required>
                                    <span class="checkmark"></span>
                                    <span class="agreement-text">Saya menyetujui bahwa data yang saya berikan adalah benar dan siap mengikuti proses seleksi</span>
                                </label>
                                @error('agree')
                                    <span class="field-error">{{ $message }}</span>
                                @enderror
                            </div>

                            <button type="submit" class="submit-button">
                                <span class="button-text">Daftar Sekarang</span>
                                <i class="fas fa-arrow-right button-icon"></i>
                            </button>
                        </form>
                    </div>
                </div>

                <div class="form-sidebar">
                    <div class="sidebar-card">
                        <h3 class="sidebar-title">Informasi Penting</h3>
                        <div class="info-grid">
                            <div class="info-item">
                                <div class="info-icon">
                                    <i class="fas fa-calendar-alt"></i>
                                </div>
                                <div class="info-content">
                                    <h4 class="info-title">Periode Pendaftaran</h4>
                                    <p class="info-description">1 - 30 November 2024</p>
                                </div>
                            </div>
                            <div class="info-item">
                                <div class="info-icon">
                                    <i class="fas fa-users"></i>
                                </div>
                                <div class="info-content">
                                    <h4 class="info-title">Kuota Penerimaan</h4>
                                    <p class="info-description">30 Anggota Baru</p>
                                </div>
                            </div>
                            <div class="info-item">
                                <div class="info-icon">
                                    <i class="fas fa-clock"></i>
                                </div>
                                <div class="info-content">
                                    <h4 class="info-title">Proses Seleksi</h4>
                                    <p class="info-description">1-2 Minggu setelah pendaftaran</p>
                                </div>
                            </div>
                            <div class="info-item">
                                <div class="info-icon">
                                    <i class="fas fa-envelope"></i>
                                </div>
                                <div class="info-content">
                                    <h4 class="info-title">Pengumuman</h4>
                                    <p class="info-description">Via Email dan Website</p>
                                </div>
                            </div>
                        </div>

                        <div class="contact-card">
                            <h4 class="contact-title">Butuh Bantuan?</h4>
                            <p class="contact-description">Hubungi kami melalui:</p>
                            <div class="contact-list">
                                <div class="contact-item">
                                    <i class="fas fa-envelope contact-icon"></i>
                                    <span class="contact-text">pendaftaran@himati.ac.id</span>
                                </div>
                                <div class="contact-item">
                                    <i class="fas fa-phone contact-icon"></i>
                                    <span class="contact-text">+62 812 3456 7890 (Admin)</span>
                                </div>
                                <div class="contact-item">
                                    <i class="fas fa-clock contact-icon"></i>
                                    <span class="contact-text">Senin - Jumat, 08:00 - 16:00 WIB</span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- FAQ Section -->
    <section class="faq-section">
        <div class="pendaftaran-container">
            <h2 class="section-title">Pertanyaan Umum</h2>
            <div class="faq-list">
                <div class="faq-item">
                    <div class="faq-header">
                        <h3 class="faq-question">Apa syarat untuk bergabung dengan HIMA-TI?</h3>
                        <i class="fas fa-chevron-down faq-icon"></i>
                    </div>
                    <div class="faq-content">
                        <p class="faq-answer">Syarat utama adalah mahasiswa aktif Program Studi Teknik Informatika dengan semangat belajar dan berkontribusi. Tidak ada batasan IPK minimum.</p>
                    </div>
                </div>
                <div class="faq-item">
                    <div class="faq-header">
                        <h3 class="faq-question">Apakah ada biaya pendaftaran?</h3>
                        <i class="fas fa-chevron-down faq-icon"></i>
                    </div>
                    <div class="faq-content">
                        <p class="faq-answer">Tidak ada biaya pendaftaran. Proses seleksi dan pendaftaran sepenuhnya gratis.</p>
                    </div>
                </div>
                <div class="faq-item">
                    <div class="faq-header">
                        <h3 class="faq-question">Berapa lama proses seleksi berlangsung?</h3>
                        <i class="fas fa-chevron-down faq-icon"></i>
                    </div>
                    <div class="faq-content">
                        <p class="faq-answer">Proses seleksi membutuhkan waktu 1-2 minggu setelah periode pendaftaran ditutup.</p>
                    </div>
                </div>
                <div class="faq-item">
                    <div class="faq-header">
                        <h3 class="faq-question">Bolehkah memilih lebih dari satu divisi?</h3>
                        <i class="fas fa-chevron-down faq-icon"></i>
                    </div>
                    <div class="faq-content">
                        <p class="faq-answer">Anda hanya boleh memilih satu divisi utama. Namun, Anda dapat menyebutkan minat lainnya dalam kolom alasan bergabung.</p>
                    </div>
                </div>
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
        --shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1), 0 2px 4px -1px rgba(0, 0, 0, 0.06);
        --shadow-lg: 0 10px 15px -3px rgba(0, 0, 0, 0.1), 0 4px 6px -2px rgba(0, 0, 0, 0.05);
        --shadow-xl: 0 20px 25px -5px rgba(0, 0, 0, 0.1), 0 10px 10px -5px rgba(0, 0, 0, 0.04);
        --border-radius: 12px;
        --border-radius-lg: 16px;
        --transition: all 0.3s ease;
    }

    /* Base Styles */
    .pendaftaran-container {
        max-width: 1200px;
        margin: 0 auto;
        padding: 0 20px;
    }

    .section-title {
        text-align: center;
        font-size: 2rem;
        font-weight: 700;
        color: var(--text-dark);
        margin-bottom: 3rem;
        background: linear-gradient(135deg, var(--primary-color), var(--primary-dark));
        -webkit-background-clip: text;
        -webkit-text-fill-color: transparent;
        background-clip: text;
    }

    /* Header Styles */
    .pendaftaran-header {
        background: linear-gradient(135deg, var(--primary-color) 0%, var(--primary-dark) 100%);
        padding: 4rem 0;
        text-align: center;
        color: var(--white);
    }

    .header-title {
        font-size: 3rem;
        font-weight: 700;
        margin-bottom: 1rem;
        line-height: 1.2;
    }

    .header-description {
        font-size: 1.2rem;
        opacity: 0.9;
        max-width: 600px;
        margin: 0 auto;
        line-height: 1.6;
    }

    /* Process Section */
    .pendaftaran-process {
        padding: 5rem 0;
        background-color: var(--white);
    }

    .process-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(280px, 1fr));
        gap: 2rem;
        margin-top: 3rem;
    }

    .process-card {
        background: var(--white);
        padding: 2.5rem 1.5rem;
        border-radius: var(--border-radius-lg);
        box-shadow: var(--shadow);
        text-align: center;
        transition: var(--transition);
        position: relative;
        overflow: hidden;
    }

    .process-card::before {
        content: '';
        position: absolute;
        top: 0;
        left: 0;
        right: 0;
        height: 4px;
        background: linear-gradient(135deg, var(--primary-color), var(--primary-dark));
    }

    .process-card:hover {
        transform: translateY(-8px);
        box-shadow: var(--shadow-xl);
    }

    .process-icon {
        margin-bottom: 1.5rem;
    }

    .step-number {
        width: 80px;
        height: 80px;
        background: linear-gradient(135deg, var(--primary-color), var(--primary-dark));
        color: var(--white);
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 2rem;
        font-weight: 700;
        margin: 0 auto;
        box-shadow: var(--shadow-lg);
    }

    .process-title {
        font-size: 1.5rem;
        font-weight: 600;
        color: var(--text-dark);
        margin-bottom: 1rem;
    }

    .process-description {
        color: var(--text-light);
        line-height: 1.6;
        font-size: 1rem;
    }

    /* Form Section */
    .pendaftaran-form-section {
        padding: 5rem 0;
        background: linear-gradient(135deg, var(--primary-light) 0%, var(--gray-light) 100%);
    }

    .form-layout {
        display: grid;
        grid-template-columns: 2fr 1fr;
        gap: 3rem;
        align-items: start;
    }

    .form-card {
        background: var(--white);
        padding: 3rem;
        border-radius: var(--border-radius-lg);
        box-shadow: var(--shadow-xl);
    }

    .form-title {
        font-size: 2rem;
        font-weight: 700;
        color: var(--text-dark);
        text-align: center;
        margin-bottom: 2.5rem;
        position: relative;
    }

    .form-title::after {
        content: '';
        position: absolute;
        bottom: -10px;
        left: 50%;
        transform: translateX(-50%);
        width: 60px;
        height: 4px;
        background: linear-gradient(135deg, var(--primary-color), var(--primary-dark));
        border-radius: 2px;
    }

    .pendaftaran-form {
        display: flex;
        flex-direction: column;
        gap: 1.5rem;
    }

    .form-row {
        display: grid;
        grid-template-columns: 1fr 1fr;
        gap: 1.5rem;
    }

    .form-field {
        display: flex;
        flex-direction: column;
    }

    .field-label {
        font-weight: 600;
        color: var(--text-dark);
        margin-bottom: 0.5rem;
        font-size: 0.95rem;
    }

    .field-input,
    .field-select,
    .field-textarea {
        padding: 1rem 1.25rem;
        border: 2px solid var(--gray-medium);
        border-radius: var(--border-radius);
        font-size: 1rem;
        transition: var(--transition);
        background-color: var(--white);
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

    .field-error {
        color: var(--error-color);
        font-size: 0.875rem;
        margin-top: 0.5rem;
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

    .file-hint {
        color: var(--text-light);
        font-size: 0.875rem;
    }

    /* Agreement */
    .form-agreement {
        margin: 1rem 0;
    }

    .agreement-label {
        display: flex;
        align-items: flex-start;
        gap: 1rem;
        cursor: pointer;
        font-size: 0.95rem;
        line-height: 1.5;
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
    }

    /* Submit Button */
    .submit-button {
        background: linear-gradient(135deg, var(--primary-color), var(--primary-dark));
        color: var(--white);
        border: none;
        padding: 1.25rem 2rem;
        border-radius: var(--border-radius);
        font-size: 1.1rem;
        font-weight: 600;
        cursor: pointer;
        transition: var(--transition);
        display: flex;
        align-items: center;
        justify-content: center;
        gap: 0.75rem;
        margin-top: 1rem;
    }

    .submit-button:hover {
        transform: translateY(-2px);
        box-shadow: var(--shadow-lg);
    }

    .button-icon {
        transition: var(--transition);
    }

    .submit-button:hover .button-icon {
        transform: translateX(4px);
    }

    /* Sidebar */
    .sidebar-card {
        background: var(--white);
        padding: 2rem;
        border-radius: var(--border-radius-lg);
        box-shadow: var(--shadow-xl);
        position: sticky;
        top: 2rem;
    }

    .sidebar-title {
        font-size: 1.5rem;
        font-weight: 700;
        color: var(--text-dark);
        margin-bottom: 2rem;
        text-align: center;
    }

    .info-grid {
        display: flex;
        flex-direction: column;
        gap: 1.5rem;
        margin-bottom: 2.5rem;
    }

    .info-item {
        display: flex;
        align-items: center;
        gap: 1rem;
        padding: 1rem;
        background: var(--primary-light);
        border-radius: var(--border-radius);
        transition: var(--transition);
    }

    .info-item:hover {
        transform: translateX(8px);
    }

    .info-icon {
        width: 50px;
        height: 50px;
        background: var(--primary-color);
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        color: var(--white);
        font-size: 1.25rem;
        flex-shrink: 0;
    }

    .info-title {
        font-size: 0.95rem;
        font-weight: 600;
        color: var(--text-dark);
        margin-bottom: 0.25rem;
    }

    .info-description {
        font-size: 0.875rem;
        color: var(--text-light);
        margin: 0;
    }

    .contact-card {
        background: var(--gray-light);
        padding: 1.5rem;
        border-radius: var(--border-radius);
    }

    .contact-title {
        font-size: 1.1rem;
        font-weight: 600;
        color: var(--text-dark);
        margin-bottom: 0.75rem;
    }

    .contact-description {
        color: var(--text-light);
        margin-bottom: 1rem;
        font-size: 0.9rem;
    }

    .contact-list {
        display: flex;
        flex-direction: column;
        gap: 0.75rem;
    }

    .contact-item {
        display: flex;
        align-items: center;
        gap: 0.75rem;
        color: var(--text-dark);
        font-size: 0.9rem;
    }

    .contact-icon {
        color: var(--primary-color);
        width: 16px;
    }

    /* FAQ Section */
    .faq-section {
        padding: 5rem 0;
        background: var(--white);
    }

    .faq-list {
        max-width: 800px;
        margin: 0 auto;
    }

    .faq-item {
        background: var(--white);
        border-radius: var(--border-radius);
        box-shadow: var(--shadow);
        margin-bottom: 1rem;
        overflow: hidden;
        border: 1px solid var(--gray-medium);
    }

    .faq-header {
        padding: 1.5rem 2rem;
        display: flex;
        justify-content: space-between;
        align-items: center;
        cursor: pointer;
        transition: var(--transition);
    }

    .faq-header:hover {
        background: var(--primary-light);
    }

    .faq-question {
        margin: 0;
        font-size: 1.1rem;
        font-weight: 600;
        color: var(--text-dark);
    }

    .faq-icon {
        transition: var(--transition);
        color: var(--primary-color);
    }

    .faq-item.active .faq-icon {
        transform: rotate(180deg);
    }

    .faq-content {
        padding: 0 2rem;
        max-height: 0;
        overflow: hidden;
        transition: var(--transition);
    }

    .faq-item.active .faq-content {
        padding: 0 2rem 1.5rem;
        max-height: 200px;
    }

    .faq-answer {
        margin: 0;
        color: var(--text-light);
        line-height: 1.6;
        font-size: 0.95rem;
    }

    /* Responsive Design */
    @media (max-width: 1024px) {
        .form-layout {
            grid-template-columns: 1fr;
            gap: 2rem;
        }
        
        .sidebar-card {
            position: static;
        }
    }

    @media (max-width: 768px) {
        .pendaftaran-container {
            padding: 0 1rem;
        }
        
        .header-title {
            font-size: 2.5rem;
        }
        
        .section-title {
            font-size: 1.75rem;
        }
        
        .process-grid {
            grid-template-columns: 1fr;
        }
        
        .form-row {
            grid-template-columns: 1fr;
        }
        
        .form-card {
            padding: 2rem;
        }
        
        .faq-header {
            padding: 1.25rem 1.5rem;
        }
        
        .faq-content {
            padding: 0 1.5rem;
        }
        
        .faq-item.active .faq-content {
            padding: 0 1.5rem 1.25rem;
        }
    }

    @media (max-width: 480px) {
        .pendaftaran-header {
            padding: 3rem 0;
        }
        
        .header-title {
            font-size: 2rem;
        }
        
        .header-description {
            font-size: 1rem;
        }
        
        .pendaftaran-process,
        .pendaftaran-form-section,
        .faq-section {
            padding: 3rem 0;
        }
        
        .form-card {
            padding: 1.5rem;
        }
        
        .sidebar-card {
            padding: 1.5rem;
        }
    }
    </style>

    <script>
    document.addEventListener('DOMContentLoaded', function() {
        // FAQ Toggle
        document.querySelectorAll('.faq-header').forEach(header => {
            header.addEventListener('click', () => {
                const faqItem = header.parentElement;
                const isActive = faqItem.classList.contains('active');
                
                // Close all FAQ items
                document.querySelectorAll('.faq-item').forEach(item => {
                    item.classList.remove('active');
                });
                
                // Open clicked item if it wasn't active
                if (!isActive) {
                    faqItem.classList.add('active');
                }
            });
        });

        // Form Validation
        const form = document.querySelector('.pendaftaran-form');
        form.addEventListener('submit', function(e) {
            const requiredFields = form.querySelectorAll('[required]');
            let valid = true;

            requiredFields.forEach(field => {
                if (!field.value.trim()) {
                    valid = false;
                    field.style.borderColor = 'var(--error-color)';
                    
                    // Add error animation
                    field.style.animation = 'shake 0.5s ease-in-out';
                    setTimeout(() => {
                        field.style.animation = '';
                    }, 500);
                } else {
                    field.style.borderColor = '';
                }
            });

            const agreeCheckbox = document.getElementById('agree');
            if (!agreeCheckbox.checked) {
                valid = false;
                agreeCheckbox.parentElement.style.color = 'var(--error-color)';
            } else {
                agreeCheckbox.parentElement.style.color = '';
            }

            if (!valid) {
                e.preventDefault();
                // Smooth scroll to first error
                const firstError = form.querySelector('[required]:invalid');
                if (firstError) {
                    firstError.scrollIntoView({ 
                        behavior: 'smooth', 
                        block: 'center' 
                    });
                }
            }
        });

        // File input validation and styling
        const fileInput = document.getElementById('dokumen');
        const fileLabel = document.querySelector('.file-label');
        const fileText = document.querySelector('.file-text');

        if (fileInput) {
            fileInput.addEventListener('change', function() {
                const file = this.files[0];
                if (file) {
                    const fileSize = file.size / 1024 / 1024; // in MB
                    const allowedTypes = ['application/pdf', 'application/msword', 'application/vnd.openxmlformats-officedocument.wordprocessingml.document'];
                    
                    if (!allowedTypes.includes(file.type)) {
                        alert('Format file tidak didukung. Harap upload file PDF, DOC, atau DOCX.');
                        this.value = '';
                        fileText.textContent = 'Pilih File';
                    } else if (fileSize > 2) {
                        alert('Ukuran file terlalu besar. Maksimal 2MB.');
                        this.value = '';
                        fileText.textContent = 'Pilih File';
                    } else {
                        fileText.textContent = file.name;
                        fileLabel.style.background = 'var(--success-color)';
                        fileLabel.style.borderColor = 'var(--success-color)';
                        fileLabel.style.color = 'var(--white)';
                    }
                } else {
                    fileText.textContent = 'Pilih File';
                    fileLabel.style.background = '';
                    fileLabel.style.borderColor = '';
                    fileLabel.style.color = '';
                }
            });
        }

        // Add shake animation for errors
        const style = document.createElement('style');
        style.textContent = `
            @keyframes shake {
                0%, 100% { transform: translateX(0); }
                25% { transform: translateX(-5px); }
                75% { transform: translateX(5px); }
            }
        `;
        document.head.appendChild(style);

        // Real-time validation
        const inputs = form.querySelectorAll('input, select, textarea');
        inputs.forEach(input => {
            input.addEventListener('blur', function() {
                if (this.hasAttribute('required') && !this.value.trim()) {
                    this.style.borderColor = 'var(--error-color)';
                } else {
                    this.style.borderColor = '';
                }
            });
            
            input.addEventListener('input', function() {
                if (this.value.trim()) {
                    this.style.borderColor = 'var(--success-color)';
                }
            });
        });
    });
    </script>
@endsection