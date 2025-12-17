@extends('layouts.admin.app')

@section('title', 'Edit Berita')

@section('content')
<div class="container-fluid">
    <h1 class="mb-4">Edit Berita</h1>

    <div class="card shadow-sm">
        <div class="card-body">
            <form action="{{ route('admin.berita.update', $berita->id_berita) }}" method="POST" enctype="multipart/form-data">
                @csrf @method('PUT')

                <div class="mb-3">
                    <label for="judul" class="form-label">Judul Berita</label>
                    <input type="text"
                           class="form-control @error('judul') is-invalid @enderror"
                           id="judul" name="judul"
                           value="{{ old('judul', $berita->judul) }}" required>
                    @error('judul') <div class="invalid-feedback">{{ $message }}</div> @enderror
                </div>

                <div class="mb-3">
                    <label for="isi" class="form-label">Isi Berita</label>
                    <textarea class="form-control @error('isi') is-invalid @enderror"
                              id="isi" name="isi" rows="6" required>{{ old('isi', $berita->isi) }}</textarea>
                    @error('isi') <div class="invalid-feedback">{{ $message }}</div> @enderror
                </div>

                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label for="penulis" class="form-label">Nama Penulis</label>
                        <input type="text"
                               class="form-control @error('penulis') is-invalid @enderror"
                               id="penulis" name="penulis"
                               value="{{ old('penulis', $berita->penulis) }}">
                        @error('penulis') <div class="invalid-feedback">{{ $message }}</div> @enderror
                    </div>

                    <div class="col-md-6 mb-3">
                        <label for="tanggal" class="form-label">Tanggal Berita</label>
                        <input type="date"
                               class="form-control @error('tanggal') is-invalid @enderror"
                               id="tanggal" name="tanggal"
                               value="{{ old('tanggal', $berita->tanggal) }}">
                        @error('tanggal') <div class="invalid-feedback">{{ $message }}</div> @enderror
                        <small class="text-muted">Opsional. Jika dikosongkan, urutan tetap berdasarkan ID terbaru.</small>
                    </div>
                </div>
<div class="mb-3">
    <label for="kategori" class="form-label">Kategori Berita</label>
    <select
        class="form-select @error('kategori') is-invalid @enderror"
        id="kategori"
        name="kategori"
        required
    >
        <option value="">-- Pilih Kategori --</option>

        @php
            $kategoriList = [
                'Kegiatan',
                'Prestasi',
                'Pengumuman',
                'Kerja Sama',
                'Akademik'
            ];
        @endphp

        @foreach($kategoriList as $kat)
            <option value="{{ $kat }}"
                {{ old('kategori', $berita->kategori) === $kat ? 'selected' : '' }}>
                {{ $kat }}
            </option>
        @endforeach
    </select>

    @error('kategori')
        <div class="invalid-feedback">{{ $message }}</div>
    @enderror
</div>

                <div class="mb-3">
                    <label for="foto" class="form-label">Foto</label><br>
                    @if($berita->foto)
                        <img src="{{ asset('storage/'.$berita->foto) }}"
                             class="img-thumbnail mb-2" style="max-height:120px;object-fit:cover;">
                    @endif
                    <input type="file"
                           class="form-control @error('foto') is-invalid @enderror"
                           id="foto" name="foto" accept="image/*">
                    @error('foto') <div class="invalid-feedback">{{ $message }}</div> @enderror
                    <small class="text-muted">Biarkan kosong jika tidak ingin mengganti foto.</small>
                </div>

                <button type="submit" class="btn btn-success">
                    <i class="fas fa-save me-2"></i> Perbarui
                </button>
                <a href="{{ route('admin.berita.index') }}" class="btn btn-secondary">
                    Batal
                </a>
            </form>
        </div>
    </div>
</div>
@endsection
