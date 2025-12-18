@extends('layouts.app')

@section('title', 'Detail Prestasi - HIMA Sistem Manajemen')

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

    .container-fluid {
        position: relative;
        z-index: 2;
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
        color: #CBD5E1;
    }

    .card-glass h4, .card-glass h5 {
        color: #F8FAFC;
        border-bottom-color: rgba(99, 102, 241, 0.3) !important;
        padding-bottom: 12px !important;
    }

    .card-glass table {
        color: #CBD5E1;
    }

    .card-glass table th {
        color: #E0E7FF;
        font-weight: 600;
    }

    .badge {
        background: rgba(99, 102, 241, 0.2) !important;
        color: #E0E7FF !important;
    }

    .btn-light {
        background: rgba(255, 255, 255, 0.15) !important;
        color: #F8FAFC !important;
        border: 1px solid rgba(224, 231, 255, 0.30);
    }

    .btn-light:hover {
        background: rgba(255, 255, 255, 0.25) !important;
        border-color: #6366F1;
    }

    .btn-primary {
        background: #6366F1 !important;
        border-color: #6366F1 !important;
    }

    .btn-primary:hover {
        background: #4F46E5 !important;
        border-color: #4F46E5 !important;
    }

    .btn-danger {
        background: rgba(239, 68, 68, 0.2) !important;
        color: #FCA5A5 !important;
        border: 1px solid rgba(239, 68, 68, 0.3) !important;
    }

    .btn-danger:hover {
        background: rgba(239, 68, 68, 0.3) !important;
    }

    .alert {
        background: rgba(99, 102, 241, 0.15) !important;
        border: 1px solid rgba(99, 102, 241, 0.3) !important;
        color: #E0E7FF !important;
    }

    .alert-info {
        background: rgba(59, 130, 246, 0.15) !important;
        border-color: rgba(59, 130, 246, 0.3) !important;
        color: #BFDBFE !important;
    }

    .text-muted {
        color: #94A3B8 !important;
    }

    .border-bottom {
        border-bottom-color: rgba(99, 102, 241, 0.3) !important;
    }

    h6 {
        color: #E0E7FF;
    }
</style>

<div class="container-fluid py-4">
    <div class="row justify-content-center">
        <div class="col-12 col-lg-10">
            <div class="card-glass shadow-sm">
                <div class="card-header bg-primary text-white d-flex justify-content-between align-items-center">
                    <h4 class="mb-0">
                        <i class="fas fa-trophy me-2"></i>Detail Prestasi
                    </h4>
                    <a href="{{ route('prestasi.index') }}" class="btn btn-light btn-sm">
                        <i class="fas fa-arrow-left me-1"></i>Kembali
                    </a>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-4">
                                <h5 class="border-bottom pb-2">Informasi Prestasi</h5>
                                <table class="table table-borderless">
                                    <tr>
                                        <th width="40%">Nama Prestasi</th>
                                        <td>{{ $prestasi->nama }}</td>
                                    </tr>
                                    <tr>
                                        <th>Kategori</th>
                                        <td>{{ $prestasi->kategori }}</td>
                                    </tr>
                                    <tr>
                                        <th>Deskripsi</th>
                                        <td>{{ $prestasi->deskripsi }}</td>
                                    </tr>
                                    <tr>
                                        <th>Status</th>
                                        <td>
                                            <span class="badge {{ $prestasi->status == 'Tervalidasi' ? 'bg-success' : ($prestasi->status == 'Ditolak' ? 'bg-danger' : 'bg-warning') }}">
                                                {{ $prestasi->status }}
                                            </span>
                                        </td>
                                    </tr>
                                </table>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-4">
                                <h5 class="border-bottom pb-2">Informasi Mahasiswa</h5>
                                <table class="table table-borderless">
                                    <tr>
                                        <th width="40%">NIM</th>
                                        <td>{{ $prestasi->nim }}</td>
                                    </tr>
                                    <tr>
                                        <th>Semester</th>
                                        <td>{{ $prestasi->semester }}</td>
                                    </tr>
                                    <tr>
                                        <th>IPK</th>
                                        <td>
                                            @if($prestasi->ipk)
                                            <span class="badge bg-info">{{ $prestasi->ipk }}</span>
                                            @else
                                            <span class="text-muted">-</span>
                                            @endif
                                        </td>
                                    </tr>
                                    <tr>
                                        <th>Email</th>
                                        <td>{{ $prestasi->email }}</td>
                                    </tr>
                                    <tr>
                                        <th>No. HP</th>
                                        <td>{{ $prestasi->no_hp }}</td>
                                    </tr>
                                </table>
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-4">
                                <h5 class="border-bottom pb-2">Periode Kegiatan</h5>
                                <table class="table table-borderless">
                                    <tr>
                                        <th width="40%">Tanggal Mulai</th>
                                        <td>{{ $prestasi->tanggal_mulai->format('d F Y') }}</td>
                                    </tr>
                                    <tr>
                                        <th>Tanggal Selesai</th>
                                        <td>{{ $prestasi->tanggal_selesai->format('d F Y') }}</td>
                                    </tr>
                                    <tr>
                                        <th>Durasi</th>
                                        <td>{{ $prestasi->tanggal_mulai->diffInDays($prestasi->tanggal_selesai) + 1 }} hari</td>
                                    </tr>
                                </table>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-4">
                                <h5 class="border-bottom pb-2">Bukti Prestasi</h5>
                                @if($prestasi->bukti)
                                <div class="d-flex align-items-center">
                                    <i class="fas fa-paperclip fa-2x text-muted me-3"></i>
                                    <div>
                                        <p class="mb-1">File bukti terlampir</p>
                                        <a href="{{ Storage::url($prestasi->bukti) }}" target="_blank" 
                                           class="btn btn-outline-primary btn-sm">
                                            <i class="fas fa-download me-1"></i>Download Bukti
                                        </a>
                                    </div>
                                </div>
                                @else
                                <div class="text-muted">
                                    <i class="fas fa-times-circle me-2"></i>Tidak ada bukti terlampir
                                </div>
                                @endif
                            </div>
                        </div>
                    </div>

                    <div class="row mt-4">
                        <div class="col-12">
                            <div class="d-flex justify-content-end">
                                @if($prestasi->status === 'Menunggu Validasi')
                                <a href="{{ route('prestasi.edit', $prestasi->id_prestasi) }}" class="btn btn-primary me-2">
                                    <i class="fas fa-edit me-1"></i>Edit
                                </a>
                                <form action="{{ route('prestasi.destroy', $prestasi->id_prestasi) }}" method="POST" class="d-inline">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-danger" 
                                            onclick="return confirm('Apakah Anda yakin ingin menghapus prestasi ini?')">
                                        <i class="fas fa-trash me-1"></i>Hapus
                                    </button>
                                </form>
                                @else
                                <div class="alert alert-warning mb-0">
                                    <i class="fas fa-exclamation-triangle me-2"></i>
                                    Prestasi yang sudah divalidasi tidak dapat diubah atau dihapus.
                                </div>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection