@php $layout = request()->is('admin-panel/*') ? 'layouts.admin-panel.app' : 'layouts.admin.app'; @endphp
@extends($layout)
@section('title', 'Detail Mahasiswa - HIMA-TI')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header bg-primary text-white d-flex justify-content-between align-items-center">
                    <h4 class="mb-0">
                        <i class="fas fa-user me-2"></i>Detail Mahasiswa
                    </h4>
                    <a href="{{ route('admin.mahasiswa.index') }}" class="btn btn-light btn-sm">
                        <i class="fas fa-arrow-left me-1"></i>Kembali
                    </a>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6">
                            <table class="table table-borderless">
                                <tr>
                                    <th width="30%">NIM</th>
                                    <td>{{ $mahasiswa->nim }}</td>
                                </tr>
                                <tr>
                                    <th>Nama</th>
                                    <td>{{ $mahasiswa->nama }}</td>
                                </tr>
                                <tr>
                                    <th>Email</th>
                                    <td>{{ $mahasiswa->email ?? '-' }}</td>
                                </tr>
                                <tr>
                                    <th>Program Studi</th>
                                    <td>{{ $mahasiswa->prodi ?? '-' }}</td>
                                </tr>
                            </table>
                        </div>
                        <div class="col-md-6">
                            <table class="table table-borderless">
                                <tr>
                                    <th width="30%">Angkatan</th>
                                    <td>{{ $mahasiswa->angkatan ?? '-' }}</td>
                                </tr>
                                <tr>
                                    <th>No. HP</th>
                                    <td>{{ $mahasiswa->no_hp ?? '-' }}</td>
                                </tr>
                                <tr>
                                    <th>Status</th>
                                    <td>
                                        <span class="badge {{ $mahasiswa->status == 'Aktif' ? 'bg-success' : ($mahasiswa->status == 'Cuti' ? 'bg-warning' : 'bg-danger') }}">
                                            {{ $mahasiswa->status }}
                                        </span>
                                    </td>
                                </tr>
                                <tr>
                                    <th>Tanggal Input</th>
                                    <td>{{ $mahasiswa->created_at ? $mahasiswa->created_at->format('d M Y H:i') : '-' }}</td>
                                </tr>
                            </table>
                        </div>
                    </div>

                    @if($mahasiswa->alamat)
                    <div class="row mt-3">
                        <div class="col-12">
                            <h5>Alamat</h5>
                            <p class="border p-3 rounded">{{ $mahasiswa->alamat }}</p>
                        </div>
                    </div>
                    @endif

                    <div class="row mt-4">
                        <div class="col-12">
                            <div class="d-flex justify-content-end">
                                <a href="{{ route('admin.mahasiswa.edit', $mahasiswa->id) }}" class="btn btn-primary me-2">
                                    <i class="fas fa-edit me-1"></i>Edit
                                </a>
                                <form action="{{ route('admin.mahasiswa.destroy', $mahasiswa->id) }}" method="POST" class="d-inline">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-danger" 
                                            onclick="return confirm('Apakah Anda yakin ingin menghapus data ini?')">
                                        <i class="fas fa-trash me-1"></i>Hapus
                                    </button>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection