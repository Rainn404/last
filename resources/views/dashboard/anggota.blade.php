@extends('layouts.app')

@section('title', 'Dashboard Anggota - HIMA TI')

@section('content')
<div class="container-fluid py-4">
    <!-- Header Section -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="d-flex justify-content-between align-items-center">
                <div>
                    <h1 class="h2 fw-bold text-dark mb-1">
                        <i class="fas fa-home me-2"></i>Selamat Datang, {{ Auth::user()->name }}!
                    </h1>
                    <p class="text-muted mb-0">Kelola profil dan prestasi Anda di sini</p>
                </div>
                <div>
                    <a href="{{ route('profile.edit') }}" class="btn btn-outline-primary">
                        <i class="fas fa-edit me-2"></i>Edit Profil
                    </a>
                </div>
            </div>
        </div>
    </div>

    <!-- Anggota Info Card -->
    @if($anggota)
    <div class="row mb-4">
        <div class="col-md-8">
            <div class="card border-0 shadow-sm">
                <div class="card-body">
                    <h5 class="card-title fw-bold mb-4">
                        <i class="fas fa-user-circle text-primary me-2"></i>Data Anggota Anda
                    </h5>
                    
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="text-muted small fw-600">Nama Lengkap</label>
                            <p class="mb-0 fw-600">{{ $anggota->nama }}</p>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="text-muted small fw-600">NIM</label>
                            <p class="mb-0 fw-600">{{ $anggota->nim }}</p>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="text-muted small fw-600">Divisi</label>
                            <p class="mb-0 fw-600">
                                @if($anggota->divisi)
                                    <span class="badge bg-info">{{ $anggota->divisi->nama_divisi }}</span>
                                @else
                                    <span class="badge bg-secondary">Belum ditentukan</span>
                                @endif
                            </p>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="text-muted small fw-600">Jabatan</label>
                            <p class="mb-0 fw-600">
                                @if($anggota->jabatan)
                                    <span class="badge bg-success">{{ $anggota->jabatan->nama_jabatan }}</span>
                                @else
                                    <span class="badge bg-secondary">Belum ditentukan</span>
                                @endif
                            </p>
                        </div>
                        <div class="col-md-6 mb-0">
                            <label class="text-muted small fw-600">Semester</label>
                            <p class="mb-0 fw-600">{{ $anggota->semester }} (Aktif)</p>
                        </div>
                        <div class="col-md-6 mb-0">
                            <label class="text-muted small fw-600">Email</label>
                            <p class="mb-0 fw-600">{{ Auth::user()->email }}</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Statistics Card -->
        <div class="col-md-4">
            <div class="card border-0 shadow-sm mb-3">
                <div class="card-body text-center">
                    <i class="fas fa-trophy text-warning fa-2x mb-2"></i>
                    <h6 class="text-muted mb-1">Total Prestasi</h6>
                    <h2 class="fw-bold text-dark">{{ $prestasiCount }}</h2>
                </div>
            </div>
            
            <div class="card border-0 shadow-sm">
                <div class="card-body text-center">
                    <i class="fas fa-check-circle text-success fa-2x mb-2"></i>
                    <h6 class="text-muted mb-1">Prestasi Disetujui</h6>
                    <h2 class="fw-bold text-dark">{{ $prestasiApproved }}</h2>
                </div>
            </div>
        </div>
    </div>
    @else
    <div class="alert alert-warning alert-dismissible fade show" role="alert">
        <i class="fas fa-exclamation-triangle me-2"></i>
        <strong>Perhatian!</strong> Data anggota Anda belum tersedia. Hubungi admin untuk memastikan data Anda sudah terdaftar.
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
    @endif

    <!-- Prestasi Section -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="card border-0 shadow-sm">
                <div class="card-header bg-light border-bottom">
                    <div class="d-flex justify-content-between align-items-center">
                        <h5 class="card-title fw-bold mb-0">
                            <i class="fas fa-star text-warning me-2"></i>Daftar Prestasi Anda
                        </h5>
                        <a href="#" class="btn btn-sm btn-primary" data-bs-toggle="modal" data-bs-target="#addPrestasiModal">
                            <i class="fas fa-plus me-1"></i>Tambah Prestasi
                        </a>
                    </div>
                </div>
                <div class="card-body">
                    @if($prestasi->count() > 0)
                    <div class="table-responsive">
                        <table class="table table-hover">
                            <thead class="table-light">
                                <tr>
                                    <th>Nama Prestasi</th>
                                    <th>Tingkat</th>
                                    <th>Tanggal</th>
                                    <th>Status</th>
                                    <th>Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($prestasi as $p)
                                <tr>
                                    <td class="fw-600">{{ $p->nama_prestasi }}</td>
                                    <td>
                                        <span class="badge bg-info">{{ $p->tingkat_prestasi ?? 'N/A' }}</span>
                                    </td>
                                    <td>{{ $p->tanggal_prestasi ? \Carbon\Carbon::parse($p->tanggal_prestasi)->format('d M Y') : 'N/A' }}</td>
                                    <td>
                                        @if($p->status_validasi === 'disetujui')
                                            <span class="badge bg-success"><i class="fas fa-check me-1"></i>Disetujui</span>
                                        @elseif($p->status_validasi === 'pending')
                                            <span class="badge bg-warning text-dark"><i class="fas fa-clock me-1"></i>Pending</span>
                                        @else
                                            <span class="badge bg-danger"><i class="fas fa-times me-1"></i>Ditolak</span>
                                        @endif
                                    </td>
                                    <td>
                                        <a href="#" class="btn btn-sm btn-outline-secondary" title="Lihat Detail">
                                            <i class="fas fa-eye"></i>
                                        </a>
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                    @else
                    <div class="text-center py-5">
                        <i class="fas fa-inbox fa-3x text-muted mb-3" style="opacity: 0.5;"></i>
                        <p class="text-muted">Anda belum memiliki prestasi apapun</p>
                        <a href="#" class="btn btn-primary btn-sm" data-bs-toggle="modal" data-bs-target="#addPrestasiModal">
                            Tambah Prestasi Pertama
                        </a>
                    </div>
                    @endif
                </div>
            </div>
        </div>
    </div>

    <!-- Registration Info (if exists) -->
    @if($registration)
    <div class="row">
        <div class="col-12">
            <div class="card border-0 shadow-sm">
                <div class="card-header bg-light border-bottom">
                    <h5 class="card-title fw-bold mb-0">
                        <i class="fas fa-file-alt text-info me-2"></i>Data Pendaftaran
                    </h5>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="text-muted small fw-600">Status Pendaftaran</label>
                            <p class="mb-0">
                                <span class="badge bg-success"><i class="fas fa-check-circle me-1"></i>{{ ucfirst($registration->status_pendaftaran) }}</span>
                            </p>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="text-muted small fw-600">Tanggal Disetujui</label>
                            <p class="mb-0 fw-600">{{ $registration->validated_at ? $registration->validated_at->format('d M Y H:i') : 'Belum divalidasi' }}</p>
                        </div>
                        <div class="col-12">
                            <label class="text-muted small fw-600">Alasan Mendaftar</label>
                            <p class="mb-0">{{ $registration->alasan_mendaftar ?? '-' }}</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    @endif

</div>

<!-- Modal Tambah Prestasi (placeholder) -->
<div class="modal fade" id="addPrestasiModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Tambah Prestasi Baru</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <p class="text-muted">Fitur penambahan prestasi akan segera tersedia.</p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Tutup</button>
            </div>
        </div>
    </div>
</div>

<style>
    .card {
        transition: transform 0.2s, box-shadow 0.2s;
    }
    
    .card:hover {
        transform: translateY(-2px);
        box-shadow: 0 4px 12px rgba(0,0,0,0.1) !important;
    }
    
    .badge {
        font-weight: 500;
        padding: 0.5rem 0.75rem;
    }
</style>
@endsection
