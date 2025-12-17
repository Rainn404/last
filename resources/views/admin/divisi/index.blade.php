@extends('layouts.admin.app')

@section('title', 'Kelola Divisi - Admin HIMA-TI')

@section('content')
<div class="container-fluid">
    <!-- Page Heading -->
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <h1 class="h3 mb-0 text-gray-800">Kelola Divisi</h1>
        <a href="{{ route('admin.divisi.create') }}" class="d-none d-sm-inline-block btn btn-sm btn-primary shadow-sm">
            <i class="fas fa-plus fa-sm text-white-50"></i> Tambah Divisi
        </a>
    </div>

    @if(session('error'))
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            {{ session('error') }}
            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                <span aria-hidden="true">&times;</span>
            </button>
        </div>
    @endif

    <!-- DataTables Example -->
    <div class="card shadow mb-4">
        <div class="card-header py-3">
            <h6 class="m-0 font-weight-bold text-primary">Daftar Divisi</h6>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-bordered" id="dataTable" width="100%" cellspacing="0">
                    <thead>
                        <tr>
                            <th>No</th>
                            <th style="display:none;">ID</th> {{-- Kolom ID tersembunyi --}}
                            <th>Nama Divisi</th>
                            <th>Ketua</th>
                            <th>Deskripsi</th>
                            <th>Jumlah Anggota</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($divisis as $key => $divisi)
                        <tr>
                            <td>{{ $key + 1 }}</td>
                            <td style="display:none;">{{ $divisi->id_divisi }}</td> {{-- ID tersembunyi --}}
                            <td>{{ $divisi->nama_divisi }}</td>
                            <td>
                                @if($divisi->ketua_divisi)
                                    {{ $divisi->ketua_divisi }}
                                @else
                                    <span class="text-muted">Belum ada ketua</span>
                                @endif
                            </td>
                            <td>
                                @if($divisi->deskripsi)
                                    {{ Str::limit($divisi->deskripsi, 100) }}
                                @else
                                    <span class="text-muted">Tidak ada deskripsi</span>
                                @endif
                            </td>
                            <td>
                                <span class="badge badge-primary">{{ $divisi->anggota_hima_count }} Anggota</span>
                            </td>
                            <td>
                                <div class="btn-group" role="group">
                                    <a href="{{ route('admin.divisi.show', $divisi->id_divisi) }}" 
                                       class="btn btn-info btn-sm" title="Lihat Detail">
                                        <i class="fas fa-eye"></i>
                                    </a>
                                    <a href="{{ route('admin.divisi.edit', $divisi->id_divisi) }}" 
                                       class="btn btn-warning btn-sm" title="Edit">
                                        <i class="fas fa-edit"></i>
                                    </a>
                                    <form action="{{ route('admin.divisi.destroy', $divisi->id_divisi) }}" 
                                          method="POST" class="d-inline">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-danger btn-sm"
                                                onclick="return confirm('Apakah Anda yakin ingin menghapus divisi ini?')"
                                                title="Hapus">
                                            <i class="fas fa-trash"></i>
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="7" class="text-center">Tidak ada data divisi</td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
@endsection

@section('styles')
<link href="{{ asset('vendor/datatables/dataTables.bootstrap4.min.css') }}" rel="stylesheet">
@endsection

@section('scripts')
<script src="{{ asset('vendor/datatables/jquery.dataTables.min.js') }}"></script>
<script src="{{ asset('vendor/datatables/dataTables.bootstrap4.min.js') }}"></script>
<script>
    $(document).ready(function() {
        $('#dataTable').DataTable({
            "language": {
                "url": "//cdn.datatables.net/plug-ins/1.10.25/i18n/Indonesian.json"
            },
            "order": [[1, 'asc']], // urut berdasarkan ID (kolom hidden index 1)
            "columnDefs": [
                {
                    "targets": [5], // kolom aksi
                    "orderable": false,
                    "searchable": false
                }
            ]
        });
    });
</script>
@endsection