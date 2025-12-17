
@extends('layouts.app')

@section('title', 'Divisi HIMA-TI')

@section('content')
<div class="container">
    <div class="text-center mb-5">
        <h1 class="fw-bold gradient-text">DIVISI HIMA-TI</h1>
        <p class="lead text-light">
            Struktur organisasi dan deskripsi divisi-divisi dalam Himpunan Mahasiswa<br>
            Teknik Informatika
        </p>
    </div>

    @if($divisis->isEmpty())
        <div class="alert alert-info text-center">
            <i class="fas fa-info-circle me-2"></i>
            Belum ada data divisi yang tersedia.
        </div>
    @else
        <div class="divisi-grid">
            @foreach($divisis as $divisi)
            <div class="col">
                <div class="divisi-card">
                    <div class="divisi-header" style="background: linear-gradient(135deg, {{ $divisi->color ?? '#1a73e8' }} 0%, {{ $divisi->color ?? '#1a73e8' }}dd 100%);">
                        <div class="d-flex align-items-center justify-content-center">
                            <div class="divisi-icon-wrapper me-3">
                                <i class="fas fa-users divisi-icon"></i>
                            </div>
                            <div class="flex-grow-1">
                                <h5 class="mb-0 text-white fw-bold">{{ $divisi->nama_divisi }}</h5>
                                <small class="text-white-50">Departemen {{ $divisi->nama_divisi }}</small>
                            </div>
                        </div>
                    </div>

                    <div class="divisi-body">
                        <div class="divisi-stats mb-4">
                            <div class="row g-2">
                                <div class="col-6">
                                    <div class="stat-item">
                                        <div class="stat-number">{{ $divisi->anggota_hima_count ?? 0 }}</div>
                                        <div class="stat-label">Anggota</div>
                                    </div>
                                </div>
                                <div class="col-6">
                                    <div class="stat-item">
                                        <div class="stat-status {{ $divisi->status ? 'active' : 'inactive' }}">
                                            <i class="fas {{ $divisi->status ? 'fa-check-circle' : 'fa-pause-circle' }}"></i>
                                            {{ $divisi->status ? 'Aktif' : 'Nonaktif' }}
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="divisi-description mb-4">
                            <p class="mb-0 text-muted">
                                {{ Str::limit($divisi->deskripsi ?? 'Divisi ini fokus pada pengembangan dan inovasi di bidang teknologi informasi.', 100) }}
                            </p>
                        </div>

                        <div class="divisi-detail d-none">
                            @if($divisi->ketua_divisi)
                            <div class="leader-info mb-3">
                                <div class="d-flex align-items-center">
                                    <div class="leader-avatar me-3">
                                        <i class="fas fa-user-tie"></i>
                                    </div>
                                    <div>
                                        <small class="text-muted d-block">Ketua Divisi</small>
                                        <strong class="text-dark">{{ $divisi->ketua_divisi }}</strong>
                                    </div>
                                </div>
                            </div>
                            @endif

                            <div class="text-center">
                                <a href="{{ route('divisi.show', $divisi->id_divisi ?? $divisi->id) }}"
                                   class="btn btn-primary-professional">
                                    <i class="fas fa-eye me-2"></i>Lihat Detail Lengkap
                                </a>
                            </div>
                        </div>
                    </div>

                    <div class="divisi-footer">
                        <button class="btn btn-professional-toggle toggle-detail">
                            <span class="show-text">
                                <i class="fas fa-plus-circle me-2"></i>Tampilkan Detail
                            </span>
                            <span class="hide-text d-none">
                                <i class="fas fa-minus-circle me-2"></i>Sembunyikan Detail
                            </span>
                        </button>
                    </div>
                </div>
            </div>
            @endforeach
        </div>
    @endif
</div>
@endsection

@push('styles')
<style>
/* Professional card styles untuk divisi dengan enterprise-grade design */
.divisi-card {
    background: linear-gradient(145deg, rgba(255, 255, 255, 0.98) 0%, rgba(249, 250, 251, 0.95) 100%);
    border: 1px solid rgba(0, 0, 0, 0.06);
    border-radius: 20px;
    overflow: hidden;
    box-shadow:
        0 1px 3px rgba(0, 0, 0, 0.12),
        0 1px 2px rgba(0, 0, 0, 0.24),
        0 20px 40px -12px rgba(0, 0, 0, 0.15);
    transition: all 0.5s cubic-bezier(0.25, 0.46, 0.45, 0.94);
    height: 100%;
    display: flex;
    flex-direction: column;
    backdrop-filter: blur(24px);
    position: relative;
    cursor: pointer;
}

.divisi-card::before {
    content: '';
    position: absolute;
    top: 0;
    left: 0;
    right: 0;
    height: 6px;
    background: linear-gradient(135deg, #1e40af 0%, #1e3a8a 50%, #0f172a 100%);
    opacity: 0;
    transition: all 0.4s cubic-bezier(0.25, 0.46, 0.45, 0.94);
    border-radius: 20px 20px 0 0;
}

.divisi-card::after {
    content: '';
    position: absolute;
    inset: 0;
    padding: 1px;
    background: linear-gradient(135deg, rgba(30, 64, 175, 0.1), rgba(30, 58, 138, 0.1), rgba(15, 23, 42, 0.1));
    border-radius: 20px;
    mask: linear-gradient(#fff 0 0) content-box, linear-gradient(#fff 0 0);
    mask-composite: exclude;
    opacity: 0;
    transition: opacity 0.4s ease;
}

.divisi-card:hover {
    transform: translateY(-16px) scale(1.03);
    box-shadow:
        0 10px 25px rgba(0, 0, 0, 0.15),
        0 20px 48px rgba(0, 0, 0, 0.1),
        0 1px 4px rgba(0, 0, 0, 0.05);
    border-color: rgba(30, 64, 175, 0.2);
}

.divisi-card:hover::before {
    opacity: 1;
    height: 8px;
}

.divisi-card:hover::after {
    opacity: 1;
}

.divisi-header {
    padding: 20px 16px 16px;
    color: white;
    position: relative;
    overflow: hidden;
    background: linear-gradient(135deg, rgba(0, 0, 0, 0.1) 0%, rgba(0, 0, 0, 0.05) 100%);
}

.divisi-header::before {
    content: '';
    position: absolute;
    top: 0;
    left: 0;
    right: 0;
    bottom: 0;
    background: inherit;
    z-index: -1;
}

.divisi-icon-wrapper {
    width: 44px;
    height: 44px;
    border-radius: 12px;
    background: linear-gradient(135deg, rgba(255, 255, 255, 0.25) 0%, rgba(255, 255, 255, 0.15) 100%);
    display: flex;
    align-items: center;
    justify-content: center;
    backdrop-filter: blur(20px);
    border: 2px solid rgba(255, 255, 255, 0.4);
    box-shadow: 0 8px 24px rgba(0, 0, 0, 0.15);
    transition: all 0.3s ease;
}

.divisi-card:hover .divisi-icon-wrapper {
    transform: scale(1.1) rotate(5deg);
    box-shadow: 0 12px 32px rgba(0, 0, 0, 0.2);
}

.divisi-icon {
    font-size: 1.6rem;
    color: white;
    filter: drop-shadow(0 2px 4px rgba(0, 0, 0, 0.2));
}

.divisi-header h5 {
    font-size: 1.4rem;
    font-weight: 800;
    margin-bottom: 6px;
    letter-spacing: -0.025em;
    text-shadow: 0 2px 8px rgba(0, 0, 0, 0.3);
    line-height: 1.2;
}

.divisi-header small {
    font-size: 0.9rem;
    opacity: 0.95;
    font-weight: 600;
    text-transform: uppercase;
    letter-spacing: 0.05em;
    text-shadow: 0 1px 4px rgba(0, 0, 0, 0.2);
}

.divisi-body {
    padding: 16px 14px;
    flex-grow: 1;
    background: white;
}

.divisi-stats {
    background: linear-gradient(135deg, #f8fafc 0%, #e2e8f0 100%);
    border-radius: 10px;
    padding: 12px;
    margin-bottom: 14px;
    border: 1px solid rgba(0, 0, 0, 0.05);
}

.stat-item {
    text-align: center;
    padding: 8px;
}

.stat-number {
    font-size: 1.5rem;
    font-weight: 700;
    color: #1e293b;
    line-height: 1;
    margin-bottom: 4px;
}

.stat-label {
    font-size: 0.75rem;
    color: #64748b;
    font-weight: 600;
    text-transform: uppercase;
    letter-spacing: 0.05em;
}

.stat-status {
    display: inline-flex;
    align-items: center;
    gap: 6px;
    font-size: 0.8rem;
    font-weight: 600;
    padding: 6px 12px;
    border-radius: 20px;
    text-transform: uppercase;
    letter-spacing: 0.05em;
}

.stat-status.active {
    background: linear-gradient(135deg, #10b981, #059669);
    color: white;
}

.stat-status.inactive {
    background: linear-gradient(135deg, #9ca3af, #6b7280);
    color: white;
}

.divisi-description {
    line-height: 1.6;
    word-wrap: break-word;
    overflow-wrap: break-word;
    hyphens: auto;
}

.divisi-description p {
    font-size: 0.9rem;
    color: #475569;
    margin: 0;
    word-break: break-word;
    overflow-wrap: break-word;
    hyphens: auto;
    max-width: 100%;
    overflow: hidden;
}

.divisi-detail {
    border-top: 1px solid #e2e8f0;
    padding-top: 20px;
    margin-top: 20px;
    animation: fadeIn 0.4s cubic-bezier(0.4, 0, 0.2, 1);
    background: linear-gradient(135deg, #fafbfc 0%, #f1f5f9 100%);
    padding: 20px;
    border-radius: 8px;
    margin: 20px -4px -4px -4px;
}

.leader-info {
    background: white;
    border-radius: 8px;
    padding: 12px;
    border: 1px solid #e2e8f0;
    box-shadow: 0 2px 8px rgba(0, 0, 0, 0.04);
}

.leader-avatar {
    width: 40px;
    height: 40px;
    border-radius: 8px;
    background: linear-gradient(135deg, #667eea, #764ba2);
    display: flex;
    align-items: center;
    justify-content: center;
    color: white;
    font-size: 1.1rem;
}

.divisi-footer {
    padding: 20px;
    background: linear-gradient(135deg, #f8fafc 0%, #f1f5f9 100%);
    border-top: 1px solid #e2e8f0;
}

.btn-primary-professional {
    background: linear-gradient(135deg, #1e40af 0%, #1e3a8a 100%);
    border: none;
    border-radius: 6px;
    padding: 8px 16px;
    font-size: 0.85rem;
    color: white;
    text-decoration: none;
    display: inline-flex;
    align-items: center;
    gap: 6px;
    transition: all 0.3s ease;
    box-shadow: 0 4px 12px rgba(30, 64, 175, 0.3);
}

.btn-primary-professional:hover {
    transform: translateY(-2px);
    box-shadow: 0 6px 20px rgba(30, 64, 175, 0.4);
    color: white;
    text-decoration: none;
}

.btn-professional-toggle {
    width: 100%;
    background: transparent;
    border: 2px solid #e2e8f0;
    border-radius: 8px;
    padding: 12px 20px;
    font-weight: 600;
    font-size: 0.9rem;
    color: #475569;
    transition: all 0.3s ease;
    display: flex;
    align-items: center;
    justify-content: center;
    gap: 8px;
}

.btn-professional-toggle:hover {
    background: linear-gradient(135deg, #1e40af 0%, #1e3a8a 100%);
    border-color: transparent;
    color: white;
    transform: translateY(-1px);
    box-shadow: 0 4px 12px rgba(30, 64, 175, 0.3);
}

@keyframes fadeIn {
    from {
        opacity: 0;
        transform: translateY(-5px);
    }
    to {
        opacity: 1;
        transform: translateY(0);
    }
}

/* Custom grid untuk divisi - selalu 3 per baris */
.divisi-grid {
    display: grid;
    grid-template-columns: repeat(3, 1fr);
    gap: 1rem;
    width: 100%;
}

/* Responsive untuk divisi grid */
@media (max-width: 768px) {
    .divisi-grid {
        grid-template-columns: repeat(3, 1fr);
        gap: 1rem;
    }
}

@media (max-width: 576px) {
    .divisi-grid {
        grid-template-columns: repeat(3, 1fr);
        gap: 0.75rem;
    }
}

/* Pastikan card selalu 3 per baris */
.row-cols-3 > * {
    flex: 0 0 auto;
    width: 33.333333%;
}

/* Responsive - tetap 3 per baris di semua ukuran layar */
@media (max-width: 992px) {
    .row-cols-3 > * {
        width: 33.333333%;
    }
}

@media (max-width: 768px) {
    .row-cols-3 > * {
        width: 33.333333%;
    }
}

@media (max-width: 576px) {
    .row-cols-3 > * {
        width: 33.333333%;
    }
}
</style>
@endpush

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    const toggleButtons = document.querySelectorAll('.toggle-detail');
    
    toggleButtons.forEach(button => {
        button.addEventListener('click', function() {
            const card = this.closest('.divisi-card');
            const detailSection = card.querySelector('.divisi-detail');
            const descSection = card.querySelector('.divisi-desc');
            const showText = card.querySelector('.show-text');
            const hideText = card.querySelector('.hide-text');
            const icon = this.querySelector('i');
            
            // Toggle detail
            if (detailSection.classList.contains('d-none')) {
                // Tutup semua detail lainnya
                document.querySelectorAll('.divisi-detail').forEach(detail => {
                    if (!detail.classList.contains('d-none') && detail !== detailSection) {
                        const otherCard = detail.closest('.divisi-card');
                        const otherDesc = otherCard.querySelector('.divisi-desc');
                        const otherShowText = otherCard.querySelector('.show-text');
                        const otherHideText = otherCard.querySelector('.hide-text');
                        const otherIcon = otherCard.querySelector('.toggle-detail i');
                        
                        detail.classList.add('d-none');
                        if (otherDesc) otherDesc.classList.remove('d-none');
                        if (otherShowText) otherShowText.classList.remove('d-none');
                        if (otherHideText) otherHideText.classList.add('d-none');
                        if (otherIcon) otherIcon.className = 'fas fa-chevron-down ms-1';
                    }
                });
                
                // Buka detail ini
                detailSection.classList.remove('d-none');
                descSection.classList.add('d-none');
                showText.classList.add('d-none');
                hideText.classList.remove('d-none');
                icon.className = 'fas fa-chevron-up ms-1';
            } else {
                // Tutup detail ini
                detailSection.classList.add('d-none');
                descSection.classList.remove('d-none');
                showText.classList.remove('d-none');
                hideText.classList.add('d-none');
                icon.className = 'fas fa-chevron-down ms-1';
            }
        });
    });
});
</script>
@endpush