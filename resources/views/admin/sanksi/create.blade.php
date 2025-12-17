<!-- resources/views/admin/sanksi/create.blade.php -->
@php $layout = request()->is('admin-panel/*') ? 'layouts.admin-panel.app' : 'layouts.admin.app'; @endphp
@extends($layout)

@section('content')
<div class="container-fluid">
    <!-- Page Header -->
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h1 class="h3 mb-0 text-gray-800">Tambah Data Sanksi</h1>
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Dashboard</a></li>
                    <li class="breadcrumb-item"><a href="{{ route('admin.sanksi.index') }}">Data Sanksi</a></li>
                    <li class="breadcrumb-item active">Tambah Data</li>
                </ol>
            </nav>
        </div>
        <a href="{{ route('admin.sanksi.index') }}" class="btn btn-secondary">
            <i class="fas fa-arrow-left me-2"></i>Kembali
        </a>
    </div>

    <!-- Card -->
    <div class="card shadow mb-4">
        <div class="card-header py-3">
            <h6 class="m-0 font-weight-bold text-primary">Form Tambah Sanksi</h6>
        </div>
        <div class="card-body">
            <form action="{{ route('admin.sanksi.store') }}" method="POST">
                @csrf
                
                <div class="row">
                    <div class="col-md-6">
                        <!-- ID Sanksi -->
                        <div class="mb-3">
                            <label for="id_sanksi" class="form-label">
                                ID Sanksi <span class="text-danger">*</span>
                            </label>
                            <input type="text" 
                                   class="form-control @error('id_sanksi') is-invalid @enderror" 
                                   id="id_sanksi" 
                                   name="id_sanksi" 
                                   value="{{ old('id_sanksi') }}"
                                   placeholder="Id sanksi"
                                   required>
                            @error('id_sanksi')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                            <small class="form-text text-muted">
                            </small>
                        </div>

                        <!-- Nama Sanksi -->
                        <div class="mb-3">
                            <label for="nama_sanksi" class="form-label">
                                Nama Sanksi <span class="text-danger">*</span>
                            </label>
                            <input type="text" 
                                   class="form-control @error('nama_sanksi') is-invalid @enderror" 
                                   id="nama_sanksi" 
                                   name="nama_sanksi" 
                                   value="{{ old('nama_sanksi') }}"
                                   placeholder="Masukkan nama sanksi"
                                   required>
                            @error('nama_sanksi')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    <div class="col-md-6">
                        <!-- Jenis Sanksi -->
                        <div class="mb-3">
                            <label for="jenis_sanksi" class="form-label">
                                Jenis Sanksi <span class="text-danger">*</span>
                            </label>
                            <select class="form-select @error('jenis_sanksi') is-invalid @enderror" 
                                    id="jenis_sanksi" 
                                    name="jenis_sanksi" 
                                    required>
                                <option value="">Pilih Jenis Sanksi</option>
                                <option value="ringan" {{ old('jenis_sanksi') == 'ringan' ? 'selected' : '' }}>
                                    Ringan
                                </option>
                                <option value="sedang" {{ old('jenis_sanksi') == 'sedang' ? 'selected' : '' }}>
                                    Sedang
                                </option>
                                <option value="berat" {{ old('jenis_sanksi') == 'berat' ? 'selected' : '' }}>
                                    Berat
                                </option>
                            </select>
                            @error('jenis_sanksi')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- Preview Badge -->
                        <div class="mb-3">
                            <label class="form-label">Preview Jenis Sanksi</label>
                            <div>
                                <span id="preview-badge" class="badge bg-secondary">Pilih jenis sanksi</span>
                            </div>
                            <small class="form-text text-muted">
                                Tampilan badge pada tabel
                            </small>
                        </div>
                    </div>
                </div>

                <!-- Action Buttons -->
                <div class="row mt-4">
                    <div class="col-12">
                        <div class="d-flex justify-content-end gap-2">
                            <a href="{{ route('admin.sanksi.index') }}" class="btn btn-secondary">
                                <i class="fas fa-times me-2"></i>Batal
                            </a>
                            <button type="submit" class="btn btn-primary">
                                <i class="fas fa-save me-2"></i>Simpan Sanksi
                            </button>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const jenisSanksiSelect = document.getElementById('jenis_sanksi');
        const previewBadge = document.getElementById('preview-badge');
        
        jenisSanksiSelect.addEventListener('change', function() {
            const value = this.value;
            let badgeClass = 'bg-secondary';
            let badgeText = 'Pilih jenis sanksi';
            
            if (value === 'ringan') {
                badgeClass = 'bg-success';
                badgeText = 'Ringan';
            } else if (value === 'sedang') {
                badgeClass = 'bg-warning text-dark';
                badgeText = 'Sedang';
            } else if (value === 'berat') {
                badgeClass = 'bg-danger';
                badgeText = 'Berat';
            }
            
            previewBadge.className = `badge ${badgeClass}`;
            previewBadge.textContent = badgeText;
        });
        
        // Trigger change event on page load if there's a value
        if (jenisSanksiSelect.value) {
            jenisSanksiSelect.dispatchEvent(new Event('change'));
        }
    });
</script>
@endpush