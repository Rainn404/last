@extends('layouts.app')

@section('title', 'Divisi HIMA-TI')

@section('content')
<div class="container-fluid py-5 px-4 px-lg-5">
    <!-- Header Section -->
    <div style="text-align: center; margin-bottom: 3rem;">
        <h1 style="font-size: 2.5rem; font-weight: 700; color: white; margin-bottom: 1rem;">DIVISI HIMA-TI</h1>
        <p style="font-size: 1.1rem; color: #d1d5db; max-width: 42rem; margin: 0 auto 1rem; display: block;">
            Struktur organisasi dan deskripsi divisi-divisi dalam Himpunan Mahasiswa Teknik Informatika
        </p>
        <div style="width: 6rem; height: 0.25rem; background: linear-gradient(to right, #3b82f6, #a855f7); margin: 0 auto; border-radius: 9999px;"></div>
    </div>

    @if($divisis->isEmpty())
        <div style="background: rgba(255, 255, 255, 0.1); backdrop-filter: blur(15px); padding: 2rem; text-align: center; max-width: 28rem; margin: 0 auto; border-radius: 20px;">
            <i class="fas fa-info-circle" style="font-size: 2.25rem; color: #60a5fa; margin-bottom: 1rem; display: block;"></i>
            <p style="color: #d1d5db; font-size: 1.1rem;">Belum ada data divisi yang tersedia.</p>
        </div>
    @else
        <!-- Cards Grid - 3 cards per row -->
        <div class="divisi-grid">
            @foreach($divisis as $divisi)
            <div class="divisi-grid-item">
                <div class="glass-divisi-card">
                    <!-- Card Header -->
                    <div class="card-header-glass" style="background: linear-gradient(135deg, {{ $divisi->color ?? '#4f46e5' }} 0%, {{ $divisi->color ? adjustBrightness($divisi->color, -30) : '#3730a3' }} 100%);">
                        <div style="display: flex; align-items: center; justify-content: space-between;">
                            <div class="divisi-title">
                                <h3 style="color: white; font-weight: 700; margin: 0;">{{ $divisi->nama_divisi }}</h3>
                            </div>
                            <div class="divisi-icon-glass">
                                @php
                                    $icon = 'fas fa-users';
                                    $namaDivisi = strtolower($divisi->nama_divisi);
                                    
                                    if (str_contains($namaDivisi, 'teknologi') || str_contains($namaDivisi, 'pengembangan')) {
                                        $icon = 'fas fa-code';
                                    } elseif (str_contains($namaDivisi, 'humas') || str_contains($namaDivisi, 'kemitraan')) {
                                        $icon = 'fas fa-handshake';
                                    } elseif (str_contains($namaDivisi, 'akademik') || str_contains($namaDivisi, 'penelitian')) {
                                        $icon = 'fas fa-graduation-cap';
                                    } elseif (str_contains($namaDivisi, 'minat') || str_contains($namaDivisi, 'bakat')) {
                                        $icon = 'fas fa-trophy';
                                    } elseif (str_contains($namaDivisi, 'kewirausahaan')) {
                                        $icon = 'fas fa-chart-line';
                                    } elseif (str_contains($namaDivisi, 'sekretariat') || str_contains($namaDivisi, 'administrasi')) {
                                        $icon = 'fas fa-file-alt';
                                    } elseif (str_contains($namaDivisi, 'bendahara')) {
                                        $icon = 'fas fa-money-bill-wave';
                                    }
                                @endphp
                                <i class="{{ $icon }}"></i>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Card Body -->
                    <div class="card-body-glass">
                        <!-- Stats Row -->
                        <div class="stats-row mb-4" style="display: flex; justify-content: space-between; align-items: center;">
                            <div class="stat-item" style="display: flex; align-items: center; font-size: 0.95rem; font-weight: 500;">
                                <i class="fas fa-users" style="color: #60a5fa; margin-right: 0.5rem;"></i>
                                <span style="color: #d1d5db;">{{ $divisi->anggota_hima_count ?? 0 }} Anggota</span>
                            </div>
                            <div class="status-badge {{ $divisi->status ? 'status-active' : 'status-inactive' }}" style="padding: 0.5rem 1rem; border-radius: 9999px; font-size: 0.85rem; font-weight: 600; text-transform: uppercase; letter-spacing: 0.5px; backdrop-filter: blur(10px); border: 1px solid;">
                                {{ $divisi->status ? 'Aktif' : 'Nonaktif' }}
                            </div>
                        </div>
                        
                        <!-- Description -->
                        <p class="description-text" style="color: #e5e7eb; line-height: 1.7; font-size: 0.95rem; margin: 0; flex: 1; display: -webkit-box; -webkit-line-clamp: 3; -webkit-box-orient: vertical; overflow: hidden; text-overflow: ellipsis;">
                            {{ Str::limit($divisi->deskripsi ?? 'Deskripsi divisi belum tersedia.', 100) }}
                        </p>
                        
                        <!-- Detail Section (Hidden) -->
                        <div class="detail-section d-none mt-4" style="border-top: 1px solid rgba(255, 255, 255, 0.1); padding-top: 1.25rem; margin-top: 1rem; animation: slideDown 0.3s ease-out; display: none;">
                            @if($divisi->ketua_divisi)
                            <div class="detail-item mb-3" style="margin-bottom: 1.125rem;">
                                <div class="detail-label" style="color: #9ca3af; font-size: 0.8rem; font-weight: 600; text-transform: uppercase; letter-spacing: 0.5px; margin-bottom: 0.375rem;">Ketua Divisi</div>
                                <div class="detail-value" style="color: #f3f4f6; font-size: 0.95rem; font-weight: 500; display: flex; align-items: center;">
                                    <i class="fas fa-crown" style="margin-right: 0.5rem; color: #fbbf24;"></i>
                                    {{ $divisi->ketua_divisi }}
                                </div>
                            </div>
                            @endif
                            
                            <div style="margin-top: 1rem;">
                                <a href="{{ route('divisi.show', $divisi->id_divisi ?? $divisi->id) }}" 
                                   class="btn-detail-action" style="display: inline-flex; align-items: center; justify-content: center; width: 100%; padding: 0.75rem 1.25rem; background: rgba(99, 102, 241, 0.2); color: #c7d2fe; text-decoration: none; border-radius: 0.75rem; font-weight: 500; font-size: 0.95rem; transition: all 0.3s ease; border: 1px solid rgba(99, 102, 241, 0.3); backdrop-filter: blur(5px);">
                                    <i class="fas fa-external-link-alt" style="margin-right: 0.5rem;"></i>
                                    Lihat Detail Lengkap
                                </a>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Card Footer -->
                    <div class="card-footer-glass" style="padding: 1.25rem 1.875rem; background: rgba(0, 0, 0, 0.05); border-top: 1px solid rgba(255, 255, 255, 0.1);">
                        <button class="toggle-detail-btn" data-divisi-id="{{ $divisi->id_divisi ?? $divisi->id }}" style="background: rgba(255, 255, 255, 0.1); border: 1px solid rgba(255, 255, 255, 0.2); color: #e5e7eb; padding: 0.75rem 1.25rem; border-radius: 0.75rem; font-size: 0.95rem; font-weight: 500; width: 100%; display: flex; align-items: center; justify-content: center; cursor: pointer; transition: all 0.3s ease; backdrop-filter: blur(5px);">
                            <span class="show-text">
                                <i class="fas fa-chevron-down" style="margin-right: 0.5rem;"></i>
                                Selengkapnya
                            </span>
                            <span class="hide-text d-none" style="display: none;">
                                <i class="fas fa-chevron-up" style="margin-right: 0.5rem;"></i>
                                Sembunyikan
                            </span>
                        </button>
                    </div>
                </div>
            </div>
            @endforeach
        </div>
    @endif
    
    <!-- Helper Function for Color Adjustment -->
    @php
        function adjustBrightness($hex, $steps) {
            $steps = max(-255, min(255, $steps));
            $hex = str_replace('#', '', $hex);
            if (strlen($hex) == 3) {
                $hex = str_repeat(substr($hex,0,1), 2).str_repeat(substr($hex,1,1), 2).str_repeat(substr($hex,2,1), 2);
            }
            $color_parts = str_split($hex, 2);
            $return = '#';
            foreach ($color_parts as $color) {
                $color   = hexdec($color);
                $color   = max(0, min(255, $color + $steps));
                $return .= str_pad(dechex($color), 2, '0', STR_PAD_LEFT);
            }
            return $return;
        }
    @endphp
</div>
@endsection

@push('styles')
<style>
/* ===== GLOBAL STYLES ===== */
.container-fluid {
    max-width: 1400px;
    margin: 0 auto;
    width: 100%;
    padding: 0 20px;
}

.text-gray-300 {
    color: #d1d5db;
}

.text-blue-400 {
    color: #60a5fa;
}

.text-yellow-400 {
    color: #fbbf24;
}

/* ===== GLASS CARD STYLES ===== */
.glass-divisi-card {
    background: rgba(255, 255, 255, 0.1);
    backdrop-filter: blur(15px);
    -webkit-backdrop-filter: blur(15px);
    border-radius: 20px;
    border: 1px solid rgba(255, 255, 255, 0.2);
    box-shadow: 
        0 8px 32px rgba(0, 0, 0, 0.1),
        inset 0 1px 0 rgba(255, 255, 255, 0.1);
    overflow: hidden;
    transition: all 0.4s cubic-bezier(0.4, 0, 0.2, 1);
    height: 100%;
    display: flex;
    flex-direction: column;
    position: relative;
}

.glass-divisi-card:hover {
    transform: translateY(-8px);
    box-shadow: 
        0 20px 40px rgba(0, 0, 0, 0.2),
        inset 0 1px 0 rgba(255, 255, 255, 0.15);
    border-color: rgba(255, 255, 255, 0.3);
    background: rgba(255, 255, 255, 0.12);
}

.glass-divisi-card::before {
    content: '';
    position: absolute;
    top: 0;
    left: 0;
    right: 0;
    height: 1px;
    background: linear-gradient(90deg, 
        transparent, 
        rgba(255, 255, 255, 0.4), 
        transparent);
    z-index: 1;
}

/* ===== CARD HEADER ===== */
.card-header-glass {
    padding: 28px 30px;
    position: relative;
    overflow: hidden;
    min-height: 120px;
    display: flex;
    align-items: center;
}

.card-header-glass::after {
    content: '';
    position: absolute;
    top: -50%;
    right: -50%;
    width: 100px;
    height: 100px;
    background: rgba(255, 255, 255, 0.1);
    border-radius: 50%;
    opacity: 0.5;
}

.divisi-title {
    flex: 1;
    min-width: 0;
    padding-right: 15px;
}

.divisi-title h3 {
    font-size: 1.5rem;
    line-height: 1.3;
    text-shadow: 0 2px 4px rgba(0, 0, 0, 0.2);
    word-break: break-word;
}

.divisi-icon-glass {
    width: 60px;
    height: 60px;
    background: rgba(255, 255, 255, 0.2);
    border-radius: 15px;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 1.8rem;
    color: white;
    flex-shrink: 0;
    transition: all 0.3s ease;
    backdrop-filter: blur(5px);
    border: 1px solid rgba(255, 255, 255, 0.3);
}

.glass-divisi-card:hover .divisi-icon-glass {
    transform: rotate(5deg) scale(1.1);
    background: rgba(255, 255, 255, 0.25);
}

/* ===== CARD BODY ===== */
.card-body-glass {
    padding: 28px 30px;
    flex: 1;
    display: flex;
    flex-direction: column;
}

.stats-row {
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-bottom: 25px;
}

.stat-item {
    display: flex;
    align-items: center;
    font-size: 0.95rem;
    font-weight: 500;
}

.status-badge {
    padding: 8px 16px;
    border-radius: 50px;
    font-size: 0.85rem;
    font-weight: 600;
    text-transform: uppercase;
    letter-spacing: 0.5px;
    backdrop-filter: blur(10px);
    border: 1px solid;
    transition: all 0.3s ease;
}

.status-active {
    background: rgba(34, 197, 94, 0.2);
    color: #4ade80;
    border-color: rgba(34, 197, 94, 0.4);
}

.status-inactive {
    background: rgba(156, 163, 175, 0.2);
    color: #9ca3af;
    border-color: rgba(156, 163, 175, 0.4);
}

.description-text {
    color: #e5e7eb;
    line-height: 1.7;
    font-size: 0.95rem;
    margin: 0;
    flex: 1;
    display: -webkit-box;
    -webkit-line-clamp: 3;
    -webkit-box-orient: vertical;
    overflow: hidden;
    text-overflow: ellipsis;
}

/* ===== DETAIL SECTION ===== */
.detail-section {
    border-top: 1px solid rgba(255, 255, 255, 0.1);
    padding-top: 20px;
    animation: slideDown 0.3s ease-out;
}

.detail-item {
    margin-bottom: 18px;
}

.detail-label {
    color: #9ca3af;
    font-size: 0.8rem;
    font-weight: 600;
    text-transform: uppercase;
    letter-spacing: 0.5px;
    margin-bottom: 6px;
}

.detail-value {
    color: #f3f4f6;
    font-size: 0.95rem;
    font-weight: 500;
    display: flex;
    align-items: center;
}

.btn-detail-action {
    display: inline-flex;
    align-items: center;
    justify-content: center;
    width: 100%;
    padding: 12px 20px;
    background: rgba(99, 102, 241, 0.2);
    color: #c7d2fe;
    text-decoration: none;
    border-radius: 12px;
    font-weight: 500;
    font-size: 0.95rem;
    transition: all 0.3s ease;
    border: 1px solid rgba(99, 102, 241, 0.3);
    backdrop-filter: blur(5px);
}

.btn-detail-action:hover {
    background: rgba(99, 102, 241, 0.3);
    color: white;
    transform: translateY(-2px);
    border-color: rgba(99, 102, 241, 0.5);
}

/* ===== CARD FOOTER ===== */
.card-footer-glass {
    padding: 20px 30px;
    background: rgba(0, 0, 0, 0.05);
    border-top: 1px solid rgba(255, 255, 255, 0.1);
}

.toggle-detail-btn {
    background: rgba(255, 255, 255, 0.1);
    border: 1px solid rgba(255, 255, 255, 0.2);
    color: #e5e7eb;
    padding: 12px 20px;
    border-radius: 12px;
    font-size: 0.95rem;
    font-weight: 500;
    width: 100%;
    display: flex;
    align-items: center;
    justify-content: center;
    cursor: pointer;
    transition: all 0.3s ease;
    backdrop-filter: blur(5px);
}

.toggle-detail-btn:hover {
    background: rgba(255, 255, 255, 0.15);
    color: white;
    border-color: rgba(255, 255, 255, 0.3);
    transform: translateY(-1px);
}

/* ===== ANIMATIONS ===== */
@keyframes slideDown {
    from {
        opacity: 0;
        transform: translateY(-10px);
    }
    to {
        opacity: 1;
        transform: translateY(0);
    }
}

/* ===== GRID LAYOUT ===== */
.divisi-grid {
    display: flex !important;
    flex-wrap: wrap !important;
    gap: 30px !important;
    width: 100% !important;
    padding: 0 !important;
    margin: 0 !important;
    justify-content: flex-start !important;
}

.divisi-grid-item {
    width: calc(33.333% - 20px) !important;
    min-width: 0 !important;
    display: block !important;
    flex: 0 1 auto !important;
}

/* ===== RESPONSIVE DESIGN ===== */
@media (max-width: 1200px) {
    .container-fluid {
        max-width: 100%;
        padding-left: 20px;
        padding-right: 20px;
    }
    
    .divisi-grid-item {
        width: calc(50% - 12px) !important;
    }
}

@media (max-width: 992px) {
    .divisi-grid-item {
        width: calc(50% - 12px) !important;
    }
    
    .glass-divisi-card {
        max-width: 100%;
    }
    
    .card-header-glass,
    .card-body-glass,
    .card-footer-glass {
        padding: 24px;
    }
    
    .divisi-title h3 {
        font-size: 1.4rem;
    }
    
    .divisi-icon-glass {
        width: 50px;
        height: 50px;
        font-size: 1.5rem;
    }
}

@media (max-width: 768px) {
    .container-fluid {
        padding-left: 15px;
        padding-right: 15px;
    }
    
    .divisi-grid-item {
        width: 100% !important;
    }
    
    .card-header-glass,
    .card-body-glass,
    .card-footer-glass {
        padding: 20px;
    }
    
    .divisi-title h3 {
        font-size: 1.3rem;
    }
    
    .stats-row {
        flex-direction: column;
        align-items: flex-start;
        gap: 12px;
    }
    
    .status-badge {
        align-self: flex-start;
    }
}

@media (max-width: 576px) {
    .glass-divisi-card {
        max-width: 100%;
    }
    
    .divisi-icon-glass {
        width: 45px;
        height: 45px;
        font-size: 1.3rem;
    }
}
</style>
@endpush

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Toggle detail functionality
    document.querySelectorAll('.toggle-detail-btn').forEach(button => {
        button.addEventListener('click', function(e) {
            e.preventDefault();
            e.stopPropagation();
            
            const card = this.closest('.glass-divisi-card');
            const detailSection = card.querySelector('.detail-section');
            const description = card.querySelector('.description-text');
            const showText = card.querySelector('.show-text');
            const hideText = card.querySelector('.hide-text');
            
            // Close all other open details
            document.querySelectorAll('.detail-section').forEach(section => {
                if (!section.classList.contains('d-none') && section !== detailSection) {
                    const otherCard = section.closest('.glass-divisi-card');
                    const otherDesc = otherCard.querySelector('.description-text');
                    const otherShowText = otherCard.querySelector('.show-text');
                    const otherHideText = otherCard.querySelector('.hide-text');
                    
                    section.classList.add('d-none');
                    if (otherDesc) otherDesc.classList.remove('d-none');
                    if (otherShowText) otherShowText.classList.remove('d-none');
                    if (otherHideText) otherHideText.classList.add('d-none');
                }
            });
            
            // Toggle current detail
            if (detailSection.classList.contains('d-none')) {
                // Open detail
                detailSection.classList.remove('d-none');
                description.classList.add('d-none');
                showText.classList.add('d-none');
                hideText.classList.remove('d-none');
                
                // Smooth scroll to card on mobile
                if (window.innerWidth < 768) {
                    setTimeout(() => {
                        card.scrollIntoView({ 
                            behavior: 'smooth', 
                            block: 'nearest' 
                        });
                    }, 300);
                }
            } else {
                // Close detail
                detailSection.classList.add('d-none');
                description.classList.remove('d-none');
                showText.classList.remove('d-none');
                hideText.classList.add('d-none');
            }
        });
    });
    
    // Close details when clicking outside
    document.addEventListener('click', function(e) {
        if (!e.target.closest('.glass-divisi-card') && 
            !e.target.closest('.toggle-detail-btn')) {
            document.querySelectorAll('.detail-section').forEach(section => {
                if (!section.classList.contains('d-none')) {
                    const card = section.closest('.glass-divisi-card');
                    const description = card.querySelector('.description-text');
                    const showText = card.querySelector('.show-text');
                    const hideText = card.querySelector('.hide-text');
                    
                    section.classList.add('d-none');
                    if (description) description.classList.remove('d-none');
                    if (showText) showText.classList.remove('d-none');
                    if (hideText) hideText.classList.add('d-none');
                }
            });
        }
    });
    
    // ESC key to close details
    document.addEventListener('keydown', function(e) {
        if (e.key === 'Escape') {
            document.querySelectorAll('.detail-section').forEach(section => {
                if (!section.classList.contains('d-none')) {
                    const card = section.closest('.glass-divisi-card');
                    const description = card.querySelector('.description-text');
                    const showText = card.querySelector('.show-text');
                    const hideText = card.querySelector('.hide-text');
                    
                    section.classList.add('d-none');
                    if (description) description.classList.remove('d-none');
                    if (showText) showText.classList.remove('d-none');
                    if (hideText) hideText.classList.add('d-none');
                }
            });
        }
    });
});
</script>
@endpush