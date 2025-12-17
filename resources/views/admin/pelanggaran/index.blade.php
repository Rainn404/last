<!-- resources/views/admin/pelanggaran/index.blade.php -->
@php $layout = request()->is('admin-panel/*') ? 'layouts.admin-panel.app' : 'layouts.admin.app'; @endphp
@extends($layout)

@section('title', 'Data Master Pelanggaran')

@section('content')
<div class="container-fluid">
    <!-- Page Heading -->
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <h1 class="h3 mb-0 text-gray-800">Data Pelanggaran</h1>
        <a href="{{ route('admin.pelanggaran.create') }}" class="d-none d-sm-inline-block btn btn-sm btn-primary shadow-sm">
            <i class="fas fa-plus fa-sm text-white-50"></i> Tambah Pelanggaran
        </a>
    </div>

    <!-- Content Row -->
    <div class="row">
        <div class="col-12">
            <div class="card shadow mb-4">
                <div class="card-header py-3" style="background-color: #e6f2ff;">
                    <h6 class="m-0 font-weight-bold text-center" style="color: #1a73e8;">Kode Etik Pelanggaran Prodi TI</h6>
                </div>
                <div class="card-body">
                    <!-- Search Form -->
                    <form method="GET" action="{{ route('admin.pelanggaran.index') }}" class="mb-4">
                        <div class="row">
                            <div class="col-md-5">
                                <div class="input-group">
                                    <input type="text" name="search" class="form-control" placeholder="Cari pelanggaran..." value="{{ request('search') }}">
                                    <div class="input-group-append">
                                        <button type="submit" class="btn" style="background-color: #1a73e8; color: white;">
                                            <i class="fas fa-search"></i>
                                        </button>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <select name="jenis" class="form-control" onchange="this.form.submit()">
                                    <option value="">Semua Jenis</option>
                                    <option value="ringan" {{ request('jenis') == 'ringan' ? 'selected' : '' }}>Ringan</option>
                                    <option value="sedang" {{ request('jenis') == 'sedang' ? 'selected' : '' }}>Sedang</option>
                                    <option value="berat" {{ request('jenis') == 'berat' ? 'selected' : '' }}>Berat</option>
                                </select>
                            </div>
                            <div class="col-md-3">
                                <a href="{{ route('admin.pelanggaran.index') }}" class="btn btn-secondary btn-block">
                                    <i class="fas fa-refresh me-1"></i> Reset Filter
                                </a>
                            </div>
                        </div>
                    </form>

                    <!-- Info Filter Aktif -->
                    @if(request('search') || request('jenis'))
                    <div class="alert alert-info alert-dismissible fade show" role="alert">
                        <strong>Filter Aktif:</strong>
                        @if(request('search'))
                            <span class="badge badge-primary">Pencarian: "{{ request('search') }}"</span>
                        @endif
                        @if(request('jenis'))
                            <span class="badge badge-info ml-2">Jenis: {{ ucfirst(request('jenis')) }}</span>
                        @endif
                        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    @endif

                    <!-- Table -->
                    <div class="table-responsive">
                        <table class="table table-bordered" width="100%" cellspacing="0">
                            <thead>
                                <tr style="background-color: #e6f2ff;">
                                    <th class="text-center align-middle" style="width: 20%; color: #1a73e8;">ID Pelanggaran</th>
                                    <th class="text-center align-middle" style="width: 45%; color: #1a73e8;">Nama Pelanggaran</th>
                                    <th class="text-center align-middle" style="width: 20%; color: #1a73e8;">Jenis</th>
                                    <th class="text-center align-middle" style="width: 15%; color: #1a73e8;">Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($pelanggaran as $item)
                                <tr>
                                    <td class="text-center align-middle">
                                        <strong>{{ $item->kode_pelanggaran }}</strong>
                                    </td>
                                    <td class="align-middle">
                                        <strong>{{ $item->nama_pelanggaran }}</strong>
                                    </td>
                                    <td class="text-center align-middle">
                                        @if($item->jenis_pelanggaran == 'ringan')
                                            <span class="badge badge-success px-3 py-2">Ringan</span>
                                        @elseif($item->jenis_pelanggaran == 'sedang')
                                            <span class="badge badge-warning px-3 py-2">Sedang</span>
                                        @else
                                            <span class="badge badge-danger px-3 py-2">Berat</span>
                                        @endif
                                    </td>
                                    <td class="text-center align-middle">
                                        <div class="btn-group btn-group-sm">
                                            <a href="{{ route('admin.pelanggaran.edit', $item->id) }}" class="btn btn-warning btn-icon" title="Edit" style="background-color: #fdd663; border-color: #fdd663;">
                                                <i class="fas fa-edit"></i>
                                            </a>
                                            <form action="{{ route('admin.pelanggaran.destroy', $item->id) }}" method="POST" class="d-inline">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="btn btn-danger btn-icon" title="Hapus" style="background-color: #f28b82; border-color: #f28b82;" onclick="return confirm('Apakah Anda yakin ingin menghapus data ini?')">
                                                    <i class="fas fa-trash"></i>
                                                </button>
                                            </form>
                                        </div>
                                    </td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="4" class="text-center py-4">
                                        <i class="fas fa-exclamation-circle text-3xl text-gray-300 mb-2 d-block"></i>
                                        @if(request('search') || request('jenis'))
                                            Tidak ada data pelanggaran yang sesuai dengan filter
                                        @else
                                            Tidak ada data pelanggaran
                                        @endif
                                    </td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>

                    <!-- Pagination yang Diperbaiki -->
                    @if($pelanggaran->hasPages())
                    <div class="d-flex justify-content-between align-items-center mt-4">
                        <div>
                            <p class="mb-0 text-muted">
                                Menampilkan {{ $pelanggaran->firstItem() ?? 0 }} - {{ $pelanggaran->lastItem() ?? 0 }} dari {{ $pelanggaran->total() }} hasil
                            </p>
                        </div>
                        <div>
                            <nav aria-label="Page navigation">
                                <ul class="pagination pagination-sm mb-0">
                                    <!-- Previous Page Link -->
                                    @if($pelanggaran->onFirstPage())
                                        <li class="page-item disabled">
                                            <span class="page-link">
                                                <i class="fas fa-chevron-left"></i> Sebelumnya
                                            </span>
                                        </li>
                                    @else
                                        <li class="page-item">
                                            <a class="page-link" href="{{ $pelanggaran->previousPageUrl() }}" rel="prev">
                                                <i class="fas fa-chevron-left"></i> Sebelumnya
                                            </a>
                                        </li>
                                    @endif

                                    <!-- Pagination Elements -->
                                    @php
                                        $current = $pelanggaran->currentPage();
                                        $last = $pelanggaran->lastPage();
                                        $start = max(1, $current - 2);
                                        $end = min($last, $current + 2);
                                    @endphp

                                    <!-- First Page Link -->
                                    @if($start > 1)
                                        <li class="page-item">
                                            <a class="page-link" href="{{ $pelanggaran->url(1) }}">1</a>
                                        </li>
                                        @if($start > 2)
                                            <li class="page-item disabled">
                                                <span class="page-link">...</span>
                                            </li>
                                        @endif
                                    @endif

                                    <!-- Page Number Links -->
                                    @for($page = $start; $page <= $end; $page++)
                                        @if($page == $current)
                                            <li class="page-item active">
                                                <span class="page-link">{{ $page }}</span>
                                            </li>
                                        @else
                                            <li class="page-item">
                                                <a class="page-link" href="{{ $pelanggaran->url($page) }}">{{ $page }}</a>
                                            </li>
                                        @endif
                                    @endfor

                                    <!-- Last Page Link -->
                                    @if($end < $last)
                                        @if($end < $last - 1)
                                            <li class="page-item disabled">
                                                <span class="page-link">...</span>
                                            </li>
                                        @endif
                                        <li class="page-item">
                                            <a class="page-link" href="{{ $pelanggaran->url($last) }}">{{ $last }}</a>
                                        </li>
                                    @endif

                                    <!-- Next Page Link -->
                                    @if($pelanggaran->hasMorePages())
                                        <li class="page-item">
                                            <a class="page-link" href="{{ $pelanggaran->nextPageUrl() }}" rel="next">
                                                Berikutnya <i class="fas fa-chevron-right"></i>
                                            </a>
                                        </li>
                                    @else
                                        <li class="page-item disabled">
                                            <span class="page-link">
                                                Berikutnya <i class="fas fa-chevron-right"></i>
                                            </span>
                                        </li>
                                    @endif
                                </ul>
                            </nav>
                        </div>
                    </div>
                    @else
                    <div class="d-flex justify-content-between align-items-center mt-4">
                        <div>
                            <p class="mb-0 text-muted">
                                Menampilkan {{ $pelanggaran->count() }} dari {{ $pelanggaran->total() }} hasil
                            </p>
                        </div>
                    </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>

<style>
.badge-success {
    background-color: #28a745 !important;
    color: white;
}

.badge-warning {
    background-color: #ffc107 !important;
    color: black;
}

.badge-danger {
    background-color: #dc3545 !important;
    color: white;
}

.card-header {
    background-color: #e6f2ff !important;
}

.table thead th {
    background-color: #e6f2ff !important;
    border: none;
    font-weight: 700;
    font-size: 0.9rem;
    padding: 12px 8px;
    color: #1a73e8;
}

.table tbody td {
    padding: 12px 8px;
    vertical-align: middle;
}

.btn-icon {
    width: 35px;
    height: 35px;
    display: inline-flex;
    align-items: center;
    justify-content: center;
    border-radius: 4px;
    margin: 0 2px;
}

.btn-warning {
    background-color: #fdd663;
    border-color: #fdd663;
    color: #fff;
}

.btn-warning:hover {
    background-color: #fcc72a;
    border-color: #fcc72a;
    color: #fff;
}

.btn-danger {
    background-color: #f28b82;
    border-color: #f28b82;
    color: #fff;
}

.btn-danger:hover {
    background-color: #ee675c;
    border-color: #ee675c;
    color: #fff;
}

.table-bordered {
    border: 1px solid #e3e6f0;
}

.table-bordered thead th {
    border: none;
}

.table-bordered tbody td {
    border: 1px solid #e3e6f0;
}

/* Hover effect for table rows */
.table tbody tr:hover {
    background-color: #f8f9fc;
}

.btn-primary {
    background-color: #1a73e8;
    border-color: #1a73e8;
}

.btn-primary:hover {
    background-color: #0d62d9;
    border-color: #0d62d9;
}

/* Pagination Styles */
.pagination {
    margin-bottom: 0;
}

.page-link {
    color: #1a73e8;
    border: 1px solid #dee2e6;
    padding: 0.5rem 0.75rem;
    font-size: 0.875rem;
    transition: all 0.2s ease-in-out;
}

.page-item.active .page-link {
    background-color: #1a73e8;
    border-color: #1a73e8;
    color: white;
}

.page-link:hover {
    color: #0d62d9;
    background-color: #e9ecef;
    border-color: #dee2e6;
    text-decoration: none;
}

.page-item.disabled .page-link {
    color: #6c757d;
    background-color: #fff;
    border-color: #dee2e6;
    cursor: not-allowed;
}

.page-link:focus {
    box-shadow: 0 0 0 0.2rem rgba(26, 115, 232, 0.25);
    outline: none;
}

/* Badge styles */
.badge {
    font-size: 0.75rem;
    font-weight: 600;
    border-radius: 4px;
}

/* Alert styles */
.alert {
    border: none;
    border-radius: 8px;
    font-size: 0.9rem;
}

.alert-info {
    background-color: #e6f2ff;
    color: #1a73e8;
}

/* Responsive adjustments */
@media (max-width: 768px) {
    .btn-icon {
        width: 30px;
        height: 30px;
        margin: 0 1px;
    }
    
    .table thead th {
        font-size: 0.8rem;
        padding: 8px 4px;
    }
    
    .table tbody td {
        padding: 8px 4px;
        font-size: 0.9rem;
    }
    
    .pagination {
        flex-wrap: wrap;
        justify-content: center;
    }
    
    .page-link {
        padding: 0.375rem 0.5rem;
        font-size: 0.8rem;
    }
    
    .d-flex.justify-content-between {
        flex-direction: column;
        text-align: center;
    }
    
    .d-flex.justify-content-between > div {
        margin-bottom: 10px;
    }
}
</style>
@endsection