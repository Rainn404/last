@php $layout = request()->is('admin-panel/*') ? 'layouts.admin-panel.app' : 'layouts.admin.app'; @endphp
@extends($layout)

@section('title', 'Mahasiswa Bermasalah')

@section('content')
<div class="container-fluid">
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <h1 class="h3 mb-0 text-gray-800">Daftar Mahasiswa Bermasalah</h1>
        <a href="{{ route('admin.mahasiswa-bermasalah.create') }}" class="btn btn-primary">
            <i class="fas fa-plus me-2"></i>Tambah Data
        </a>
    </div>

    @if (session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            <i class="fas fa-check-circle me-2"></i>
            {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    <div class="card shadow mb-4">
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-bordered table-hover" width="100%" cellspacing="0">
                    <thead class="table-dark">
                        <tr>
                            <th class="text-center align-middle">NIM</th>
                            <th class="text-center align-middle">Nama</th>
                            <th class="text-center align-middle">Semester</th>
                            <th class="text-center align-middle">Nama Orang Tua</th>
                            <th class="text-center align-middle">Pelanggaran</th>
                            <th class="text-center align-middle">Sanksi</th>
                            <th class="text-center align-middle">Deskripsi</th>
                            <th class="text-center align-middle">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($mahasiswaBermasalah as $item)
                        <tr>
                            <td class="text-center align-middle">{{ $item->nim }}</td>
                            <td class="align-middle">{{ $item->nama }}</td>
                            <td class="text-center align-middle">{{ $item->semester }}</td>
                            <td class="align-middle">{{ $item->nama_orang_tua }}</td>
                            <td class="align-middle">{{ $item->pelanggaran->nama_pelanggaran }}</td>
                            <td class="align-middle">{{ $item->sanksi->nama_sanksi }}</td>
                            <td class="align-middle">
                                @if($item->deskripsi)
                                    <div class="deskripsi-text" title="{{ $item->deskripsi }}">
                                        {{ Str::limit($item->deskripsi, 50) }}
                                    </div>
                                @else
                                    <span class="text-muted">-</span>
                                @endif
                            </td>
                            <td class="text-center align-middle">
                                <div class="d-flex justify-content-center gap-1">
                                    <a href="{{ route('admin.mahasiswa-bermasalah.edit', $item->id) }}" class="btn btn-warning btn-sm" title="Edit">
                                        <i class="fas fa-edit"></i>
                                    </a>
                                    <form action="{{ route('admin.mahasiswa-bermasalah.destroy', $item->id) }}" method="POST" class="d-inline">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-danger btn-sm" title="Hapus" onclick="return confirm('Yakin ingin menghapus?')">
                                            <i class="fas fa-trash"></i>
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            {{ $mahasiswaBermasalah->links() }}
        </div>
    </div>
</div>

<style>
.table {
    font-size: 0.875rem;
}

.table th {
    background-color: #e2f4ff;
    color: rgb(66, 129, 245);
    font-weight: 600;
    padding: 12px 8px;
    border: 1px solid #dee2e6;
}

.table td {
    padding: 12px 8px;
    vertical-align: middle;
    border: 1px solid #dee2e6;
}

.table-hover tbody tr:hover {
    background-color: #f8f9fa;
}

.btn-sm {
    padding: 6px 8px;
    border-radius: 4px;
}

.gap-1 {
    gap: 6px;
}

.align-middle {
    vertical-align: middle !important;
}

/* Style untuk deskripsi */
.deskripsi-text {
    font-size: 0.8rem;
    line-height: 1.3;
    color: #495057;
    max-width: 200px;
    overflow: hidden;
    text-overflow: ellipsis;
    white-space: nowrap;
}

.deskripsi-text:hover {
    white-space: normal;
    overflow: visible;
    background-color: #f8f9fa;
    padding: 4px;
    border-radius: 4px;
    position: relative;
    z-index: 10;
}

/* Responsive table */
.table-responsive {
    border-radius: 8px;
    overflow: hidden;
}

/* Header styling */
.table-dark th {
    border-color: #454d55;
    font-size: 0.9rem;
}

/* Ensure text doesn't break in table cells */
.table td, .table th {
    white-space: nowrap;
}

/* Allow text wrapping only for specific columns */
.table td:nth-child(4), /* Nama Orang Tua */
.table td:nth-child(5), /* Pelanggaran */
.table td:nth-child(6), /* Sanksi */
.table td:nth-child(7) { /* Deskripsi */
    white-space: normal;
    word-wrap: break-word;
    max-width: 200px;
}
</style>
@endsection