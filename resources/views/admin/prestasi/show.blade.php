@php $layout = request()->is('admin-panel/*') ? 'layouts.admin-panel.app' : 'layouts.admin.app'; @endphp
@extends($layout)

@section('title', 'Detail Prestasi - Admin HIMA-TI')

@section('content')
<div class="container-fluid py-4">
    <!-- Header -->
    <div class="hero-section">
        <div class="container">
            <div class="row align-items-center">
                <div class="col-md-8">
                    <h1 class="page-title mb-2">
                        <i class="fas fa-eye me-2"></i>Detail Prestasi
                    </h1>
                    <p class="mb-0">Detail lengkap informasi prestasi mahasiswa</p>
                </div>
                <div class="col-md-4 text-md-end">
                    <a href="{{ route('admin.prestasi.index') }}" class="btn btn-light me-2">
                        <i class="fas fa-arrow-left me-2"></i>Kembali
                    </a>
                    <a href="{{ route('admin.prestasi.edit', $prestasi->id_prestasi ?? $prestasi->id) }}" class="btn btn-warning">
                        <i class="fas fa-edit me-2"></i>Edit
                    </a>
                </div>
            </div>
        </div>
    </div>

    <div class="container">
        <div class="row">
            <!-- Main Content -->
            <div class="col-md-8">
                <div class="card">
                    <div class="card-header">
                        <h5 class="mb-0"><i class="fas fa-info-circle me-2"></i>Informasi Prestasi</h5>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label class="form-label fw-bold">Nama Prestasi</label>
                                <p class="fs-5">{{ $prestasi->nama_prestasi ?? $prestasi->nama }}</p>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="form-label fw-bold">Kategori</label>
                                <div>
                                    <span class="badge bg-primary fs-6">{{ $prestasi->kategori }}</span>
                                </div>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="form-label fw-bold">Tanggal Mulai</label>
                                <p>{{ \Carbon\Carbon::parse($prestasi->tanggal_mulai)->format('d F Y') }}</p>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="form-label fw-bold">Tanggal Selesai</label>
                                <p>{{ \Carbon\Carbon::parse($prestasi->tanggal_selesai)->format('d F Y') }}</p>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="form-label fw-bold">IPK</label>
                                <p>
                                    @if($prestasi->ipk)
                                    <span class="badge bg-info fs-6">{{ number_format($prestasi->ipk, 2) }}</span>
                                    @else
                                    <span class="text-muted">-</span>
                                    @endif
                                </p>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="form-label fw-bold">Semester</label>
                                <p><span class="badge bg-secondary fs-6">Semester {{ $prestasi->semester }}</span></p>
                            </div>
                            <div class="col-12 mb-3">
                                <label class="form-label fw-bold">Deskripsi Prestasi</label>
                                <div class="border rounded p-3 bg-light">
                                    {{ $prestasi->deskripsi ?? 'Tidak ada deskripsi' }}
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Sidebar -->
            <div class="col-md-4">
                <!-- Status Card -->
                <div class="card mb-4">
                    <div class="card-header">
                        <h5 class="mb-0"><i class="fas fa-clipboard-check me-2"></i>Status Validasi</h5>
                    </div>
                    <div class="card-body text-center">
                        <span class="badge status-badge fs-6 
                            {{ $prestasi->status_validasi == 'disetujui' ? 'bg-success' : 
                               ($prestasi->status_validasi == 'ditolak' ? 'bg-danger' : 'bg-warning') }}">
                            @if($prestasi->status_validasi == 'disetujui') Tervalidasi @elseif($prestasi->status_validasi == 'ditolak') Ditolak @else Menunggu Validasi @endif
                        </span>
                        
                        @if($prestasi->status_validasi == 'pending' || !$prestasi->status_validasi)
                        <div class="mt-3">
                            <form action="{{ route('admin.prestasi.validasi', $prestasi->id_prestasi ?? $prestasi->id) }}" method="POST" class="d-inline">
                                @csrf
                                @method('PUT')
                                <input type="hidden" name="status" value="Tervalidasi">
                                <button type="submit" class="btn btn-success btn-sm">
                                    <i class="fas fa-check me-1"></i>Setujui
                                </button>
                            </form>
                            <form action="{{ route('admin.prestasi.validasi', $prestasi->id_prestasi ?? $prestasi->id) }}" method="POST" class="d-inline">
                                @csrf
                                @method('PUT')
                                <input type="hidden" name="status" value="Ditolak">
                                <button type="submit" class="btn btn-danger btn-sm">
                                    <i class="fas fa-times me-1"></i>Tolak
                                </button>
                            </form>
                        </div>
                        @endif
                    </div>
                </div>

                <!-- Informasi Mahasiswa -->
                <div class="card">
                    <div class="card-header">
                        <h5 class="mb-0"><i class="fas fa-user me-2"></i>Informasi Mahasiswa</h5>
                    </div>
                    <div class="card-body">
                        <div class="text-center mb-3">
                            <div class="bg-primary bg-opacity-10 rounded-circle d-flex align-items-center justify-content-center mx-auto" style="width: 80px; height: 80px;">
                                <i class="fas fa-user text-primary fa-2x"></i>
                            </div>
                        </div>
                        <div class="text-center">
                            <h5>{{ $prestasi->user->name ?? 'N/A' }}</h5>
                            <p class="text-muted mb-2">{{ $prestasi->user->nim ?? 'N/A' }}</p>
                            <p class="text-muted mb-2">{{ $prestasi->user->email ?? 'N/A' }}</p>
                            <p class="text-muted">Program Studi Teknologi Informasi</p>
                        </div>
                    </div>
                </div>

                <!-- Bukti Prestasi -->
                @if($prestasi->bukti_prestasi || $prestasi->bukti)
                <div class="card mt-4">
                    <div class="card-header">
                        <h5 class="mb-0"><i class="fas fa-paperclip me-2"></i>Bukti Prestasi</h5>
                    </div>
                    <div class="card-body text-center">
                        <i class="fas fa-file-pdf fa-3x text-danger mb-3"></i>
                        <p class="mb-2">File bukti prestasi tersedia</p>
                        <a href="{{ asset('storage/' . ($prestasi->bukti_prestasi ?? $prestasi->bukti)) }}" 
                           target="_blank" class="btn btn-outline-primary btn-sm">
                            <i class="fas fa-download me-1"></i>Download Bukti
                        </a>
                    </div>
                </div>
                @endif
            </div>
        </div>
    </div>
</div>

<style>
/* Gunakan style yang sama dengan index */
:root {
    --primary: #4361ee;
    --secondary: #3f37c9;
    --gradient: linear-gradient(135deg, #4361ee 0%, #3a0ca3 100%);
    --shadow: 0 10px 20px rgba(0,0,0,0.1);
    --radius: 12px;
}

.hero-section {
    background: var(--gradient);
    color: white;
    padding: 2rem 0;
    border-radius: var(--radius);
    margin-bottom: 2rem;
}

.page-title {
    font-weight: 700;
    font-size: 1.75rem;
}

.card {
    border: none;
    border-radius: var(--radius);
    box-shadow: var(--shadow);
}

.card-header {
    background: white !important;
    border-bottom: 1px solid #e1e5ee;
    padding: 1.25rem 1.5rem;
}

.status-badge {
    font-size: 1rem !important;
    padding: 0.5rem 1rem;
}

.btn {
    border-radius: 8px;
    font-weight: 500;
}

.btn-light {
    background: rgba(255, 255, 255, 0.2);
    color: white;
    border: 1px solid rgba(255, 255, 255, 0.3);
}
</style>
@endsection