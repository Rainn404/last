<!-- resources/views/admin/sanksi/index.blade.php -->
@php $layout = request()->is('admin-panel/*') ? 'layouts.admin-panel.app' : 'layouts.admin.app'; @endphp
@extends($layout)

@section('content')
<div class="container-fluid">
    <!-- Page Header -->
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h1 class="h3 mb-0 text-gray-800">Data Sanksi</h1>
            <p class="text-muted">Kelola data sanksi Teknologi Informasi</p>
        </div>
        <a href="{{ route('admin.sanksi.create') }}" class="btn btn-primary">
            <i class="fas fa-plus me-2"></i>Tambah Sanksi
        </a>
    </div>
    
    <!-- Card -->
    <div class="card shadow mb-4">
        <div class="card-header py-3 d-flex justify-content-between align-items-center">
            <h6 class="m-0 font-weight-bold text-primary">Daftar Sanksi</h6>
            <div class="d-flex gap-2">
                <div class="input-group input-group-sm" style="width: 250px;">
                    <input type="text" class="form-control" placeholder="Cari sanksi...">
                    <button class="btn btn-outline-secondary" type="button">
                        <i class="fas fa-search"></i>
                    </button>
                </div>
                <button class="btn btn-sm btn-outline-secondary">
                    <i class="fas fa-download"></i> Export
                </button>
            </div>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-bordered table-hover" id="dataTable" width="100%" cellspacing="0">
                    <thead class="table-light">
                        <tr>
                            <th width="50">No</th>
                            <th>ID Sanksi</th>
                            <th>Nama Sanksi</th>
                            <th>Jenis Sanksi</th>
                            <th width="120">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($sanksi as $index => $item)
                        <tr>
                            <td class="text-center">{{ $sanksi->firstItem() + $index }}</td>
                            <td>
                                <strong>{{ $item->id_sanksi }}</strong>
                            </td>
                            <td>{{ $item->nama_sanksi }}</td>
                            <td>
                                @if($item->jenis_sanksi == 'ringan')
                                    <span class="badge bg-success">Ringan</span>
                                @elseif($item->jenis_sanksi == 'sedang')
                                    <span class="badge bg-warning text-dark">Sedang</span>
                                @else
                                    <span class="badge bg-danger">Berat</span>
                                @endif
                            </td>
                            <td>
                                <div class="btn-group btn-group-sm" role="group">
                                    <a href="{{ route('admin.sanksi.edit', $item->id) }}" 
                                       class="btn btn-warning" 
                                       title="Edit">
                                        <i class="fas fa-edit"></i>
                                    </a>
                                    <form action="{{ route('admin.sanksi.destroy', $item->id) }}" method="POST" class="d-inline">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" 
                                                class="btn btn-danger" 
                                                title="Hapus"
                                                onclick="return confirm('Apakah Anda yakin ingin menghapus sanksi ini?')">
                                            <i class="fas fa-trash"></i>
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="5" class="text-center py-4">
                                <div class="text-muted">
                                    <i class="fas fa-inbox fa-2x mb-3"></i>
                                    <p>Belum ada data sanksi</p>
                                    <a href="{{ route('admin.sanksi.create') }}" class="btn btn-primary btn-sm">
                                        <i class="fas fa-plus me-1"></i>Tambah Sanksi Pertama
                                    </a>
                                </div>
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <!-- Pagination -->
            @if($sanksi->hasPages())
            <div class="d-flex justify-content-between align-items-center mt-3">
                <div class="text-muted">
                    Menampilkan {{ $sanksi->firstItem() }} - {{ $sanksi->lastItem() }} dari {{ $sanksi->total() }} data
                </div>
                <nav>
                    {{ $sanksi->links() }}
                </nav>
            </div>
            @endif
        </div>
    </div>
</div>
@endsection

@push('styles')
<style>
    .table th {
        font-weight: 600;
        font-size: 0.875rem;
        text-transform: uppercase;
        letter-spacing: 0.5px;
    }
    .badge {
        font-size: 0.75rem;
        padding: 0.35em 0.65em;
    }
    .pagination {
        margin-bottom: 0;
    }
</style>
@endpush