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
        <div class="row row-cols-1 row-cols-md-3 g-4">
            @foreach($divisis as $divisi)
            <div class="col">
                <div class="divisi-card">
                    <div class="divisi-header" style="background-color: {{ $divisi->color ?? '#1a73e8' }}">
                        <div class="d-flex justify-content-between align-items-center">
                            <h5 class="mb-0 text-white">{{ $divisi->nama_divisi }}</h5>
                            <div class="divisi-icon">
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
                                    }
                                @endphp
                                <i class="{{ $icon }}"></i>
                            </div>
                        </div>
                    </div>
                    
                    <div class="divisi-body">
                        <div class="mb-3">
                            <div class="d-flex justify-content-between align-items-center">
                                <span class="badge bg-light text-dark">
                                    <i class="fas fa-users me-1"></i>
                                    {{ $divisi->anggota_hima_count ?? 0 }} Anggota
                                </span>
                                <span class="badge {{ $divisi->status ? 'bg-success' : 'bg-secondary' }}">
                                    {{ $divisi->status ? 'Aktif' : 'Nonaktif' }}
                                </span>
                            </div>
                        </div>
                        
                        <p class="divisi-desc mb-3">
                            {{ Str::limit($divisi->deskripsi ?? 'Divisi ini belum memiliki deskripsi.', 80) }}
                        </p>

                        <div class="divisi-detail d-none">
                            @if($divisi->ketua_divisi)
                            <div class="mb-2">
                                <small class="text-muted">Ketua Divisi:</small>
                                <p class="mb-0 fw-semibold">{{ $divisi->ketua_divisi }}</p>
                            </div>
                            @endif
                            
                            <div class="text-center mt-3">
                                <a href="{{ route('divisi.show', $divisi->id_divisi ?? $divisi->id) }}" 
                                   class="btn btn-sm btn-primary">
                                    <i class="fas fa-external-link-alt me-1"></i> Detail
                                </a>
                            </div>
                        </div>
                    </div>
                    
                    <div class="divisi-footer">
                        <button class="btn btn-sm btn-link w-100 text-decoration-none toggle-detail">
                            <span class="show-text">Selengkapnya</span>
                            <span class="hide-text d-none">Sembunyikan</span>
                            <i class="fas fa-chevron-down ms-1"></i>
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
/* Custom styles untuk card divisi */
.divisi-card {
    background: white;
    border-radius: 10px;
    overflow: hidden;
    box-shadow: 0 2px 8px rgba(0,0,0,0.1);
    transition: transform 0.2s;
    height: 100%;
    display: flex;
    flex-direction: column;
    border: 1px solid #dee2e6;
}

.divisi-card:hover {
    transform: translateY(-3px);
    box-shadow: 0 4px 12px rgba(0,0,0,0.15);
}

.divisi-header {
    padding: 15px;
    color: white;
}

.divisi-header h5 {
    font-size: 1.1rem;
    font-weight: 600;
}

.divisi-icon {
    font-size: 1.2rem;
    opacity: 0.9;
}

.divisi-body {
    padding: 15px;
    flex-grow: 1;
}

.divisi-desc {
    font-size: 0.9rem;
    color: #666;
    line-height: 1.5;
    margin-bottom: 0;
}

.divisi-detail {
    border-top: 1px solid #eee;
    padding-top: 10px;
    margin-top: 10px;
    animation: fadeIn 0.3s;
}

.divisi-footer {
    padding: 10px 15px;
    background-color: #f8f9fa;
    border-top: 1px solid #dee2e6;
}

.btn-link {
    color: #0d6efd;
    font-size: 0.85rem;
}

.btn-link:hover {
    color: #0a58ca;
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

/* Pastikan card tidak melebar */
.row-cols-md-3 > * {
    flex: 0 0 auto;
    width: 33.333333%;
}

/* Responsive */
@media (max-width: 992px) {
    .row-cols-md-3 > * {
        width: 50%;
    }
}

@media (max-width: 768px) {
    .row-cols-md-3 > * {
        width: 100%;
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