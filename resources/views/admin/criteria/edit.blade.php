{{-- resources/views/criteria/edit.blade.php --}}
@extends('layouts.admin.app')

@section('title', 'Edit Kriteria: ' . $criterion->name)

@section('action-buttons')
    <a href="{{ route('admin.criteria.index') }}" class="btn btn-secondary">
        <i class="fas fa-arrow-left"></i> Kembali
    </a>
@endsection

@section('content')
<div class="row">
    <div class="col-md-8">
        <div class="card">
            <div class="card-header bg-warning">
                <h5 class="mb-0">Edit Kriteria</h5>
            </div>
            <div class="card-body">
                <form action="{{ route('admin.criteria.update', $criterion->id_criterion) }}" method="POST">
                    @csrf
                    @method('PUT')
                    
                    <div class="mb-4">
                        <label for="name" class="form-label">
                            Nama Kriteria <span class="text-danger">*</span>
                        </label>
                        <input type="text" 
                               class="form-control @error('name') is-invalid @enderror" 
                               id="name" 
                               name="name" 
                               value="{{ old('name', $criterion->name) }}"
                               required
                               maxlength="255">
                        @error('name')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    
                    <div class="mb-4">
                        <label for="type" class="form-label">
                            Tipe Kriteria <span class="text-danger">*</span>
                        </label>
                        <select class="form-select @error('type') is-invalid @enderror" 
                                id="type" 
                                name="type" 
                                required>
                            <option value="">Pilih Tipe</option>
                            <option value="benefit" {{ old('type', $criterion->type) == 'benefit' ? 'selected' : '' }}>
                                Benefit (Semakin besar semakin baik)
                            </option>
                            <option value="cost" {{ old('type', $criterion->type) == 'cost' ? 'selected' : '' }}>
                                Cost (Semakin kecil semakin baik)
                            </option>
                        </select>
                        @error('type')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    
                    <div class="d-grid gap-2 d-md-flex justify-content-md-end">
                        <button type="submit" class="btn btn-primary">
                            <i class="fas fa-save"></i> Update Kriteria
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    
    <div class="col-md-4">
        <div class="card">
            <div class="card-header">
                <h5 class="mb-0"><i class="fas fa-info-circle"></i> Informasi</h5>
            </div>
            <div class="card-body">
                <p><strong>Kode:</strong> {{ $criterion->code }}</p>
                <p><strong>Status:</strong> 
                    @if($criterion->is_active)
                        <span class="badge bg-success">Aktif</span>
                    @else
                        <span class="badge bg-secondary">Nonaktif</span>
                    @endif
                </p>
                <p><strong>Dibuat:</strong> {{ $criterion->created_at->format('d/m/Y H:i') }}</p>
                <p><strong>Diubah:</strong> {{ $criterion->updated_at->format('d/m/Y H:i') }}</p>
            </div>
        </div>
    </div>
</div>
@endsection