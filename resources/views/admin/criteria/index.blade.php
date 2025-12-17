{{-- resources/views/admin/criteria/index.blade.php --}}
@extends('layouts.admin.app')

@section('title', 'Kelola Kriteria AHP')

@section('action-buttons')
    <div class="d-flex align-items-center">
        <a href="{{ route('admin.criteria.create') }}" class="btn btn-primary me-2">
            <i class="fas fa-plus-circle me-1"></i> Tambah Kriteria
        </a>
    </div>
@endsection

@section('content')
<div class="row">
    <!-- Header -->
    <div class="col-12 mb-4">
        <div class="card shadow-sm">
            <div class="card-body">
                <div class="row align-items-center">
                    <div class="col-md-6">
                        <h4 class="card-title mb-1">
                            <i class="fas fa-list-check text-primary me-2"></i>
                            Kelola Kriteria AHP
                        </h4>
                        <p class="text-muted mb-0">
                            Sistem Management Kriteria untuk Perhitungan Analytical Hierarchy Process
                        </p>
                    </div>
                    <div class="col-md-6 text-md-end">
                        <a href="{{ route('admin.criteria.create') }}" 
                           class="btn btn-primary btn-lg">
                            <i class="fas fa-plus-circle me-2"></i>
                            Tambah Kriteria Baru
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- Statistik --}}
    <div class="col-md-12 mb-4">
        <div class="row g-3">
            {{-- Total --}}
            <div class="col-xl-3 col-md-6">
                <div class="card card-hover border-start border-primary border-4">
                    <div class="card-body">
                        <div class="d-flex justify-content-between align-items-center">
                            <div>
                                <h6 class="text-muted fw-semibold mb-0">Total Kriteria</h6>
                                <h2 class="mt-2 mb-0">{{ $totalCriteria }}</h2>
                                @if($totalCriteria > 0)
                                    <small class="text-success fw-semibold">
                                        <i class="fas fa-chart-line me-1"></i>
                                        {{ $activeCriteria }} Aktif
                                    </small>
                                @else
                                    <small class="text-warning fw-semibold">
                                        <i class="fas fa-exclamation-circle me-1"></i>
                                        Belum ada data
                                    </small>
                                @endif
                            </div>
                            <div class="avatar-sm">
                                <span class="avatar-title bg-primary-subtle rounded-circle">
                                    <i class="fas fa-list-alt text-primary fs-4"></i>
                                </span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Benefit --}}
            <div class="col-xl-3 col-md-6">
                <div class="card card-hover border-start border-info border-4">
                    <div class="card-body">
                        <div class="d-flex justify-content-between align-items-center">
                            <div>
                                <h6 class="text-muted fw-semibold mb-0">Tipe Benefit</h6>
                                <h2 class="mt-2 mb-0">{{ $benefitCriteria }}</h2>
                                @if($totalCriteria > 0)
                                    <small class="text-info fw-semibold">
                                        <i class="fas fa-thumbs-up me-1"></i>
                                        {{ $benefitPercentage }}% dari total
                                    </small>
                                @else
                                    <small class="text-info fw-semibold">
                                        <i class="fas fa-thumbs-up me-1"></i>
                                        Semakin besar semakin baik
                                    </small>
                                @endif
                            </div>
                            <div class="avatar-sm">
                                <span class="avatar-title bg-info-subtle rounded-circle">
                                    <i class="fas fa-thumbs-up text-info fs-4"></i>
                                </span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Cost --}}
            <div class="col-xl-3 col-md-6">
                <div class="card card-hover border-start border-warning border-4">
                    <div class="card-body">
                        <div class="d-flex justify-content-between align-items-center">
                            <div>
                                <h6 class="text-muted fw-semibold mb-0">Tipe Cost</h6>
                                <h2 class="mt-2 mb-0">{{ $costCriteria }}</h2>
                                @if($totalCriteria > 0)
                                    <small class="text-warning fw-semibold">
                                        <i class="fas fa-thumbs-down me-1"></i>
                                        {{ $costPercentage }}% dari total
                                    </small>
                                @else
                                    <small class="text-warning fw-semibold">
                                        <i class="fas fa-thumbs-down me-1"></i>
                                        Semakin kecil semakin baik
                                    </small>
                                @endif
                            </div>
                            <div class="avatar-sm">
                                <span class="avatar-title bg-warning-subtle rounded-circle">
                                    <i class="fas fa-thumbs-down text-warning fs-4"></i>
                                </span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Baru: Kriteria per Halaman --}}
            <div class="col-xl-3 col-md-6">
                <div class="card card-hover border-start border-success border-4">
                    <div class="card-body">
                        <div class="d-flex justify-content-between align-items-center">
                            <div>
                                <h6 class="text-muted fw-semibold mb-0">Per Halaman</h6>
                                <h2 class="mt-2 mb-0">{{ $criteria->count() }}</h2>
                                @if($totalCriteria > 0)
                                    <small class="text-muted">
                                        <i class="fas fa-table me-1"></i>
                                        {{ $criteria->currentPage() }} dari {{ $criteria->lastPage() }} halaman
                                    </small>
                                @else
                                    <small class="text-muted">
                                        <i class="fas fa-info-circle me-1"></i>
                                        Tambahkan data pertama
                                    </small>
                                @endif
                            </div>
                            <div class="avatar-sm">
                                <span class="avatar-title bg-success-subtle rounded-circle">
                                    <i class="fas fa-layer-group text-success fs-4"></i>
                                </span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- Filter --}}
    @if($totalCriteria > 0)
    <div class="col-md-12 mb-4">
        <div class="card">
            <div class="card-body">
                <form method="GET" action="{{ route('admin.criteria.index') }}" class="row g-3">
                    <div class="col-md-5">
                        <input type="text" name="search" class="form-control"
                               placeholder="Cari nama kriteria..." value="{{ request('search') }}">
                    </div>
                    <div class="col-md-5">
                        <select name="type" class="form-select">
                            <option value="">Semua Tipe</option>
                            <option value="benefit" {{ request('type')=='benefit'?'selected':'' }}>Benefit</option>
                            <option value="cost" {{ request('type')=='cost'?'selected':'' }}>Cost</option>
                        </select>
                    </div>
                    <div class="col-md-2">
                        <button type="submit" class="btn btn-primary w-100">
                            <i class="fas fa-search"></i> Filter
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    {{-- Tabel --}}
    <div class="col-md-12">
        <div class="card">
            <div class="card-header d-flex justify-content-between align-items-center">
                <div>
                    <h5 class="mb-0">Daftar Kriteria</h5>
                    <p class="text-muted mb-0">Total {{ $totalCriteria }} kriteria ditemukan</p>
                </div>
                <div>
                    <a href="{{ route('admin.criteria.create') }}" class="btn btn-sm btn-primary">
                        <i class="fas fa-plus me-1"></i>Tambah Kriteria
                    </a>
                </div>
            </div>

            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-hover">
                        <thead class="table-light">
                            <tr>
                                <th width="60">#</th>
                                <th>Nama Kriteria</th>
                                <th width="120">Tipe</th>
                                <th width="150" class="text-center">Aksi</th>
                            </tr>
                        </thead>

                        <tbody>
                            @foreach($criteria as $criterion)
                            <tr>
                                <td>{{ $loop->iteration }}</td>
                                <td>
                                    <div>
                                        <strong>{{ $criterion->name }}</strong>
                                        @if($criterion->description)
                                            <br>
                                            <small class="text-muted">{{ Str::limit($criterion->description, 50) }}</small>
                                        @endif
                                    </div>
                                </td>
                                <td>
                                    @if($criterion->type == 'benefit')
                                        <span class="badge bg-info">
                                            <i class="fas fa-thumbs-up me-1"></i> Benefit
                                        </span>
                                    @else
                                        <span class="badge bg-warning text-dark">
                                            <i class="fas fa-thumbs-down me-1"></i> Cost
                                        </span>
                                    @endif
                                </td>
                                <td class="text-center">
                                    <div class="btn-group btn-group-sm" role="group">
                                        <a href="{{ route('admin.criteria.edit', $criterion) }}"
                                           class="btn btn-outline-warning"
                                           title="Edit" data-bs-toggle="tooltip">
                                            <i class="fas fa-edit"></i>
                                        </a>
                                        <button type="button" 
                                                class="btn btn-outline-danger"
                                                data-bs-toggle="modal"
                                                data-bs-target="#deleteModal{{ $criterion->id }}"
                                                title="Hapus" data-bs-toggle="tooltip">
                                            <i class="fas fa-trash"></i>
                                        </button>
                                    </div>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>

                {{-- Pagination --}}
                <div class="d-flex justify-content-between align-items-center mt-4">
                    <div class="text-muted">
                        Menampilkan {{ $criteria->firstItem() }}–{{ $criteria->lastItem() }}
                        dari {{ $criteria->total() }} kriteria
                    </div>
                    <div>
                        {{ $criteria->links() }}
                    </div>
                </div>
            </div>
        </div>
    </div>
    @else
    <div class="col-md-12">
        <div class="card">
            <div class="card-body text-center py-5">
                <div class="mb-4">
                    <i class="fas fa-list-alt fa-4x text-muted"></i>
                </div>
                <h5>Belum ada kriteria</h5>
                <p class="text-muted mb-4">Mulai dengan menambahkan kriteria pertama Anda</p>
                <a href="{{ route('admin.criteria.create') }}" class="btn btn-primary btn-lg">
                    <i class="fas fa-plus-circle me-2"></i>
                    Tambah Kriteria Pertama
                </a>
            </div>
        </div>
    </div>
    @endif
</div>

{{-- Delete Modals --}}
@foreach($criteria as $criterion)
<div class="modal fade" id="deleteModal{{ $criterion->id }}" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header bg-danger text-white">
                <h5 class="modal-title"><i class="fas fa-exclamation-triangle me-2"></i>Konfirmasi Hapus</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <p>Apakah Anda yakin ingin menghapus kriteria <strong>{{ $criterion->name }}</strong>?</p>
                <div class="alert alert-warning">
                    <i class="fas fa-exclamation-triangle me-2"></i>
                    Data yang dihapus tidak dapat dikembalikan!
                </div>
            </div>
            <div class="modal-footer">
                <form action="{{ route('admin.criteria.destroy', $criterion) }}" method="POST">
                    @csrf @method('DELETE')
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-danger">Hapus</button>
                </form>
            </div>
        </div>
    </div>
</div>
@endforeach
@endsection

@push('styles')
<style>
    .card-hover:hover {
        transform: translateY(-2px);
        transition: all 0.3s ease;
        box-shadow: 0 4px 12px rgba(0,0,0,0.1);
    }
</style>
@endpush

@push('scripts')
<script>
    // Initialize tooltips
    document.addEventListener('DOMContentLoaded', function() {
        var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'))
        var tooltipList = tooltipTriggerList.map(function (tooltipTriggerEl) {
            return new bootstrap.Tooltip(tooltipTriggerEl)
        });
    });
</script>
@endpush