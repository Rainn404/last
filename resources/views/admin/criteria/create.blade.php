{{-- resources/views/criteria/create.blade.php --}}
@extends('layouts.admin.app')

@section('title', 'Tambah Kriteria Baru')

@section('action-buttons')
    <a href="{{ route('admin.criteria.index') }}" class="btn btn-secondary">
        <i class="fas fa-arrow-left"></i> Kembali
    </a>
@endsection

@section('content')
<div class="row">
    <div class="col-md-8">
        <div class="card">
            <div class="card-header">
                <h5 class="mb-0">Form Tambah Kriteria</h5>
            </div>
            <div class="card-body">
                <form action="{{ route('admin.criteria.store') }}" method="POST" id="criterionForm">
                    @csrf
                    
                    {{-- Nama Kriteria --}}
                    <div class="mb-4">
                        <label for="name" class="form-label">
                            Nama Kriteria <span class="text-danger">*</span>
                        </label>
                        <input type="text" 
                               class="form-control @error('name') is-invalid @enderror" 
                               id="name" 
                               name="name" 
                               value="{{ old('name') }}"
                               required
                               maxlength="255"
                               placeholder="Contoh: Prestasi Akademik">
                        @error('name')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    {{-- Tipe Kriteria --}}
                    <div class="mb-4">
                        <label for="type" class="form-label">
                            Tipe Kriteria <span class="text-danger">*</span>
                        </label>
                        <select class="form-select @error('type') is-invalid @enderror" 
                                id="type" 
                                name="type" 
                                required>
                            <option value="">Pilih Tipe</option>
                            <option value="benefit" {{ old('type') == 'benefit' ? 'selected' : '' }}>
                                Benefit (Semakin besar semakin baik)
                            </option>
                            <option value="cost" {{ old('type') == 'cost' ? 'selected' : '' }}>
                                Cost (Semakin kecil semakin baik)
                            </option>
                        </select>
                        @error('type')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    
                    <div class="d-grid gap-2 d-md-flex justify-content-md-end">
                        <button type="submit" class="btn btn-primary">
                            <i class="fas fa-save"></i> Simpan Kriteria
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    
    <div class="col-md-4">
        <div class="card">
            <div class="card-header">
                <h5 class="mb-0"><i class="fas fa-info-circle"></i> Panduan</h5>
            </div>
            <div class="card-body">
                <h6>Kriteria dalam AHP:</h6>
                <ul class="list-unstyled">
                    <li class="mb-2">
                        <i class="fas fa-check text-success"></i>
                        <strong>Benefit:</strong> Nilai tinggi lebih baik
                    </li>
                    <li class="mb-2">
                        <i class="fas fa-check text-success"></i>
                        <strong>Cost:</strong> Nilai rendah lebih baik
                    </li>
                </ul>
                
                <h6>Contoh Kriteria:</h6>
                <div class="table-responsive">
                    <table class="table table-sm">
                        <thead>
                            <tr>
                                <th>Kode</th>
                                <th>Nama</th>
                                <th>Tipe</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td>PK</td>
                                <td>Prestasi Akademik</td>
                                <td><span class="badge bg-info">Benefit</span></td>
                            </tr>
                            <tr>
                                <td>TK</td>
                                <td>Tingkatan Kelas</td>
                                <td><span class="badge bg-warning">Cost</span></td>
                            </tr>
                            <tr>
                                <td>JR</td>
                                <td>Juara Lomba</td>
                                <td><span class="badge bg-info">Benefit</span></td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
    // Form validation sederhana
    document.getElementById('criterionForm').addEventListener('submit', function(e) {
        const name = document.getElementById('name').value.trim();
        const type = document.getElementById('type').value;
        
        if (!name || name.length < 2) {
            e.preventDefault();
            alert('Nama kriteria minimal 2 karakter!');
            document.getElementById('name').focus();
            return false;
        }
        
        if (!type) {
            e.preventDefault();
            alert('Pilih tipe kriteria!');
            document.getElementById('type').focus();
            return false;
        }
    });
</script>
@endpush