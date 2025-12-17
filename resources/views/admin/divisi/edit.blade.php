@extends('layouts.admin.app')

@section('title', 'Edit Divisi - Admin HIMA-TI')

@section('content')
<div class="container-fluid">
    <!-- Page Heading -->
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <h1 class="h3 mb-0 text-gray-900-bold">Edit Divisi</h1>
        <a href="{{ route('admin.divisi.index') }}" class="d-none d-sm-inline-block btn btn-sm btn-secondary shadow-sm">
            <i class="fas fa-arrow-left fa-sm text-white-50"></i> Kembali
        </a>
    </div>

    <!-- Form -->
    <div class="card shadow mb-4">
        <div class="card-header py-3">
            <h6 class="m-0 font-weight-bold text-primary">Form Edit Divisi</h6>
        </div>
        <div class="card-body">
            <!-- Informasi Divisi -->
            <div class="row mb-4">
                <div class="col-md-6">
                    <div class="info-box bg-light p-3 rounded">
                        <h6 class="font-weight-bold text-primary">Informasi Divisi</h6>
                        <p class="mb-1"><strong>ID Divisi:</strong> {{ $divisi->id_divisi }}</p>
                        <p class="mb-1"><strong>Nama Divisi:</strong> {{ $divisi->nama }}</p>
                        <p class="mb-1"><strong>Tanggal Dibuat:</strong> 
                            {{ \Carbon\Carbon::parse($divisi->created_at)->setTimezone('Asia/Makassar')->format('d/m/Y H:i') }} WITA
                        </p>
                        <p class="mb-1"><strong>Terakhir Diupdate:</strong> 
                            {{ \Carbon\Carbon::parse($divisi->updated_at)->setTimezone('Asia/Makassar')->format('d/m/Y H:i') }} WITA
                        </p>
                    </div>
                </div>
            </div>

            <form action="{{ route('admin.divisi.update', $divisi->id_divisi) }}" method="POST">
                @csrf
                @method('PUT')
                <div class="form-group">
                    <label for="nama">Nama Divisi <span class="text-danger">*</span></label>
                    <input type="text" class="form-control @error('nama') is-invalid @enderror" 
                           id="nama" name="nama" value="{{ old('nama', $divisi->nama) }}" 
                           placeholder="Masukkan nama divisi" required>
                    @error('nama')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="form-group">
                    <label for="deskripsi">Deskripsi</label>
                    <textarea class="form-control @error('deskripsi') is-invalid @enderror" 
                              id="deskripsi" name="deskripsi" rows="4" 
                              placeholder="Masukkan deskripsi divisi">{{ old('deskripsi', $divisi->deskripsi) }}</textarea>
                    @error('deskripsi')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

            <div class="form-group">
    <label for="nama" class="font-weight-bold">Nama Divisi *</label>
    <select class="form-control @error('nama') is-invalid @enderror"
            id="nama" name="nama" required>
        <option value="">-- Pilih Divisi --</option>

        <option value="Teknologi & Pengembangan"
            {{ old('nama', $divisi->nama) == 'Teknologi & Pengembangan' ? 'selected' : '' }}>
            Teknologi & Pengembangan
        </option>

        <option value="Humas & Kemitraan"
            {{ old('nama', $divisi->nama) == 'Humas & Kemitraan' ? 'selected' : '' }}>
            Humas & Kemitraan
        </option>

        <option value="Akademik & Penelitian"
            {{ old('nama', $divisi->nama) == 'Akademik & Penelitian' ? 'selected' : '' }}>
            Akademik & Penelitian
        </option>

        <option value="Minat & Bakat"
            {{ old('nama', $divisi->nama) == 'Minat & Bakat' ? 'selected' : '' }}>
            Minat & Bakat
        </option>

        <option value="Kewirausahaan"
            {{ old('nama', $divisi->nama) == 'Kewirausahaan' ? 'selected' : '' }}>
            Kewirausahaan
        </option>
    </select>

    @error('nama')
        <div class="invalid-feedback">{{ $message }}</div>
    @enderror
</div>

                <div class="form-group">
                    <button type="submit" class="btn btn-primary">
                        <i class="fas fa-save"></i> Perbarui
                    </button>
                    <a href="{{ route('admin.divisi.index') }}" class="btn btn-secondary">
                        <i class="fas fa-times"></i> Batal
                    </a>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection