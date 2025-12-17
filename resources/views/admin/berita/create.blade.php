@extends('layouts.admin.app')

@section('title', 'Tambah Berita')

@section('content')
<div class="container-fluid">
    <h1 class="mb-4">Tambah Berita</h1>

    <div class="card shadow-sm">
        <div class="card-body">
            <form action="{{ route('admin.berita.store') }}" method="POST" enctype="multipart/form-data">
                @csrf

                <div class="mb-3">
                    <label for="judul" class="form-label">Judul Berita</label>
                    <input type="text" class="form-control @error('judul') is-invalid @enderror"
                           id="judul" name="judul" value="{{ old('judul') }}" required>
                    @error('judul') <div class="invalid-feedback">{{ $message }}</div> @enderror
                </div>

                <div class="mb-3">
                    <label for="isi" class="form-label">Isi Berita</label>
                    <textarea class="form-control @error('isi') is-invalid @enderror" id="isi" 
                              name="isi" rows="6" required>{{ old('isi') }}</textarea>
                    @error('isi') <div class="invalid-feedback">{{ $message }}</div> @enderror
                </div>

                <div class="mb-3">
                    <label for="penulis" class="form-label">Nama Penulis</label>
                    <input type="text" class="form-control @error('penulis') is-invalid @enderror"
                           id="penulis" name="penulis" value="{{ old('penulis') }}">
                    @error('penulis') <div class="invalid-feedback">{{ $message }}</div> @enderror
                </div>

                <div class="mb-3">
  <label for="tanggal" class="form-label">Tanggal Berita</label>
  <input type="date"
         id="tanggal"
         name="tanggal"
         class="form-control @error('tanggal') is-invalid @enderror"
         value="{{ old('tanggal', now()->format('Y-m-d')) }}">
  @error('tanggal') <div class="invalid-feedback">{{ $message }}</div> @enderror
</div>

                <div class="mb-3">
    <label for="foto" class="form-label">Foto</label>
    <input type="file"
           class="form-control @error('foto') is-invalid @enderror"
           id="foto"
           name="foto"
           accept="image/*">
    @error('foto') <div class="invalid-feedback">{{ $message }}</div> @enderror
</div>

<button type="submit" class="btn btn-success">
    <i class="fas fa-save me-2"></i> Simpan
</button>

<a href="{{ url('/admin/berita') }}" class="btn btn-secondary">
    <i class="fas fa-times me-1"></i> Batalkan
</a>

            </form>
        </div>
    </div>
</div>
@endsection
