<!-- resources/views/admin/sanksi/edit.blade.php -->
@php $layout = request()->is('admin-panel/*') ? 'layouts.admin-panel.app' : 'layouts.admin.app'; @endphp
@extends($layout)

@section('content')
<div class="container-fluid">
    <!-- Page Header -->
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h1 class="h3 mb-0 text-gray-800">Edit Data Sanksi</h1>
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Dashboard</a></li>
                    <li class="breadcrumb-item"><a href="{{ route('admin.sanksi.index') }}">Data Sanksi</a></li>
                    <li class="breadcrumb-item active">Edit Data</li>
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
            <h6 class="m-0 font-weight-bold text-primary">Form Edit Sanksi</h6>
        </div>
        <div class="card-body">
            <form action="{{ route('admin.sanksi.update', $sanksi->id) }}" method="POST">
                @csrf
                @method('PUT')
                
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
                                   value="{{ old('id_sanksi', $sanksi->id_sanksi) }}"
                                   placeholder="Contoh: SR-001, SS-001, SB-001"
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
                                   value="{{ old('nama_sanksi', $sanksi->nama_sanksi) }}"
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
                                <option value="ringan" {{ old('jenis_sanksi', $sanksi->jenis_sanksi) == 'ringan' ? 'selected' : '' }}>
                                    Ringan
                                </option>
                                <option value="sedang" {{ old('jenis_sanksi', $sanksi->jenis_sanksi) == 'sedang' ? 'selected' : '' }}>
                                    Sedang
                                </option>
                                <option value="berat" {{ old('jenis_sanksi', $sanksi->jenis_sanksi) == 'berat' ? 'selected' : '' }}>
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
                                <span id="preview-badge" class="badge 
                                    @if($sanksi->jenis_sanksi == 'ringan') bg-success
                                    @elseif($sanksi->jenis_sanksi == 'sedang') bg-warning text-dark
                                    @elseif($sanksi->jenis_sanksi == 'berat') bg-danger
                                    @else bg-secondary @endif">
                                    @if($sanksi->jenis_sanksi)
                                        {{ ucfirst($sanksi->jenis_sanksi) }}
                                    @else
                                        Pilih jenis sanksi
                                    @endif
                                </span>
                            </div>
                            <small class="form-text text-muted">
                                Tampilan badge pada tabel
                            </small>
                        </div>

                        <!-- Info Timestamp -->
                        <div class="card bg-light">
                            <div class="card-body py-2">
                                <small class="text-muted">
                                    <i class="fas fa-info-circle me-1"></i>
                                    Dibuat: {{ $sanksi->created_at->format('d M Y H:i') }} | 
                                    Diupdate: {{ $sanksi->updated_at->format('d M Y H:i') }}
                                </small>
                            </div>
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
                                <i class="fas fa-save me-2"></i>Perbarui Sanksi
                            </button>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>

    <!-- Danger Zone Card -->
    <div class="card border-danger">
        <div class="card-header bg-danger text-white">
            <h6 class="m-0 font-weight-bold">
                <i class="fas fa-exclamation-triangle me-2"></i>Zona Berbahaya
            </h6>
        </div>
        <div class="card-body">
            <div class="row align-items-center">
                <div class="col-md-8">
                    <h6 class="text-danger mb-1">Hapus Data Sanksi</h6>
                    <p class="text-muted mb-0">
                        Tindakan ini akan menghapus permanen data sanksi. Data yang sudah dihapus tidak dapat dikembalikan.
                    </p>
                </div>
                <div class="col-md-4 text-end">
                    <form action="{{ route('admin.sanksi.destroy', $sanksi->id) }}" method="POST" class="d-inline">
                        @csrf
                        @method('DELETE')
                        <button type="submit" 
                                class="btn btn-danger"
                                onclick="return confirm('Apakah Anda yakin ingin menghapus sanksi {{ $sanksi->nama_sanksi }}? Tindakan ini tidak dapat dibatalkan.')">
                            <i class="fas fa-trash me-2"></i>Hapus Sanksi
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('styles')
<style>
    .card.border-danger {
        border-width: 2px;
    }
    .bg-light {
        background-color: #f8f9fa !important;
    }
</style>
@endpush

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