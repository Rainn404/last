
<!-- resources/views/admin/mahasiswa/edit.blade.php -->
@php $layout = request()->is('admin-panel/*') ? 'layouts.admin-panel.app' : 'layouts.admin.app'; @endphp
@extends($layout)

@section('title', 'Edit Data Mahasiswa - HIMA-TI')

@section('content')
<div class="container-fluid">
    <!-- Page Heading -->
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <h1 class="h3 mb-0 text-gray-800">Edit Data Mahasiswa</h1>
        <a href="{{ route('admin.mahasiswa.index') }}" class="d-none d-sm-inline-block btn btn-sm btn-secondary shadow-sm">
            <i class="fas fa-arrow-left fa-sm text-white-50"></i> Kembali ke Daftar
        </a>
    </div>

    <!-- Content Row -->
    <div class="row">
        <div class="col-12">
            <div class="card shadow mb-4">
                <div class="card-body">
                    <!-- Form Header -->
                    <div class="mb-5">
                        <h2 class="h4 text-gray-900 mb-2">Edit Data Mahasiswa</h2>
                        <p class="text-muted mb-0">Perbarui data mahasiswa berikut dengan informasi yang valid</p>
                    </div>

                    <form action="{{ route('admin.mahasiswa.update', $mahasiswa) }}" method="POST">
                        @csrf
                        @method('PUT')

                        <div class="row">
                            <!-- Nama Mahasiswa Field -->
                            <div class="col-md-6 mb-4">
                                <label for="nama" class="form-label fw-bold text-gray-700 mb-3">
                                    Nama Mahasiswa <span class="text-danger">*</span>
                                </label>
                                <input
                                    type="text"
                                    id="nama"
                                    name="nama"
                                    value="{{ old('nama', $mahasiswa->nama) }}"
                                    class="form-control form-control-lg @error('nama') is-invalid @enderror"
                                    placeholder="Masukkan nama lengkap mahasiswa"
                                    required
                                >
                                @error('nama')
                                    <div class="invalid-feedback d-block mt-2">
                                        <i class="fas fa-exclamation-circle me-1"></i>{{ $message }}
                                    </div>
                                @enderror
                            </div>

                            <!-- NIM Field -->
                            <div class="col-md-6 mb-4">
                                <label for="nim" class="form-label fw-bold text-gray-700 mb-3">
                                    NIM (Nomor Induk Mahasiswa) <span class="text-danger">*</span>
                                </label>
                                <input
                                    type="text"
                                    id="nim"
                                    name="nim"
                                    value="{{ old('nim', $mahasiswa->nim) }}"
                                    class="form-control form-control-lg @error('nim') is-invalid @enderror"
                                    placeholder="Masukkan NIM mahasiswa"
                                    required
                                >
                                @error('nim')
                                    <div class="invalid-feedback d-block mt-2">
                                        <i class="fas fa-exclamation-circle me-1"></i>{{ $message }}
                                    </div>
                                @enderror
                            </div>
                        </div>

                        <div class="row">
                            <!-- Angkatan Field -->
                            <div class="col-md-6 mb-4">
                                <label for="angkatan" class="form-label fw-bold text-gray-700 mb-3">
                                    Angkatan
                                </label>
                                <input
                                    type="number"
                                    id="angkatan"
                                    name="angkatan"
                                    value="{{ old('angkatan', $mahasiswa->angkatan) }}"
                                    class="form-control form-control-lg @error('angkatan') is-invalid @enderror"
                                    placeholder="Masukkan tahun angkatan"
                                    min="2000"
                                    max="{{ date('Y') + 1 }}"
                                >
                                @error('angkatan')
                                    <div class="invalid-feedback d-block mt-2">
                                        <i class="fas fa-exclamation-circle me-1"></i>{{ $message }}
                                    </div>
                                @enderror
                            </div>

                            <!-- Status Field -->
                            <div class="col-md-6 mb-4">
                                <label for="status" class="form-label fw-bold text-gray-700 mb-3">
                                    Status Mahasiswa <span class="text-danger">*</span>
                                </label>
                                <select
                                    id="status"
                                    name="status"
                                    class="form-select form-select-lg @error('status') is-invalid @enderror"
                                    required
                                >
                                    <option value="">Pilih status mahasiswa</option>
                                    <option value="Aktif" {{ old('status', $mahasiswa->status) == 'Aktif' ? 'selected' : '' }}>Aktif</option>
                                    <option value="Non-Aktif" {{ old('status', $mahasiswa->status) == 'Non-Aktif' ? 'selected' : '' }}>Non-Aktif</option>
                                    <option value="Cuti" {{ old('status', $mahasiswa->status) == 'Cuti' ? 'selected' : '' }}>Cuti</option>
                                </select>
                                @error('status')
                                    <div class="invalid-feedback d-block mt-2">
                                        <i class="fas fa-exclamation-circle me-1"></i>{{ $message }}
                                    </div>
                                @enderror
                            </div>
                        </div>

                        <!-- SAW Fields Section -->
                        <div class="mt-4 mb-4">
                            <h5 class="text-primary mb-3"><i class="fas fa-calculator me-2"></i>Data untuk Perhitungan SAW</h5>
                            <p class="text-muted small">Data berikut digunakan untuk perangkingan mahasiswa menggunakan metode SAW</p>
                        </div>

                        <div class="row">
                            <!-- IPK Field -->
                            <div class="col-md-3 mb-4">
                                <label for="ipk" class="form-label fw-bold text-gray-700 mb-3">
                                    IPK
                                </label>
                                <input
                                    type="number"
                                    step="0.01"
                                    id="ipk"
                                    name="ipk"
                                    value="{{ old('ipk', $mahasiswa->ipk) }}"
                                    class="form-control form-control-lg @error('ipk') is-invalid @enderror"
                                    placeholder="0.00 - 4.00"
                                    min="0"
                                    max="4"
                                >
                                @error('ipk')
                                    <div class="invalid-feedback d-block mt-2">
                                        <i class="fas fa-exclamation-circle me-1"></i>{{ $message }}
                                    </div>
                                @enderror
                            </div>

                            <!-- Juara Field -->
                            <div class="col-md-3 mb-4">
                                <label for="juara" class="form-label fw-bold text-gray-700 mb-3">
                                    Prestasi Juara
                                </label>
                                <select
                                    id="juara"
                                    name="juara"
                                    class="form-select form-select-lg @error('juara') is-invalid @enderror"
                                >
                                    <option value="">Pilih...</option>
                                    <option value="1" {{ old('juara', $mahasiswa->juara) == 1 ? 'selected' : '' }}>Anggota/Peserta</option>
                                    <option value="3" {{ old('juara', $mahasiswa->juara) == 3 ? 'selected' : '' }}>Juara 3</option>
                                    <option value="5" {{ old('juara', $mahasiswa->juara) == 5 ? 'selected' : '' }}>Juara 2</option>
                                    <option value="7" {{ old('juara', $mahasiswa->juara) == 7 ? 'selected' : '' }}>Juara 1</option>
                                </select>
                                @error('juara')
                                    <div class="invalid-feedback d-block mt-2">
                                        <i class="fas fa-exclamation-circle me-1"></i>{{ $message }}
                                    </div>
                                @enderror
                            </div>

                            <!-- Tingkatan Field -->
                            <div class="col-md-3 mb-4">
                                <label for="tingkatan" class="form-label fw-bold text-gray-700 mb-3">
                                    Tingkatan Lomba
                                </label>
                                <select
                                    id="tingkatan"
                                    name="tingkatan"
                                    class="form-select form-select-lg @error('tingkatan') is-invalid @enderror"
                                >
                                    <option value="">Pilih...</option>
                                    <option value="1" {{ old('tingkatan', $mahasiswa->tingkatan) == 1 ? 'selected' : '' }}>Internal Kampus</option>
                                    <option value="3" {{ old('tingkatan', $mahasiswa->tingkatan) == 3 ? 'selected' : '' }}>Kabupaten/Kota</option>
                                    <option value="5" {{ old('tingkatan', $mahasiswa->tingkatan) == 5 ? 'selected' : '' }}>Provinsi</option>
                                    <option value="7" {{ old('tingkatan', $mahasiswa->tingkatan) == 7 ? 'selected' : '' }}>Nasional</option>
                                    <option value="9" {{ old('tingkatan', $mahasiswa->tingkatan) == 9 ? 'selected' : '' }}>Internasional</option>
                                </select>
                                @error('tingkatan')
                                    <div class="invalid-feedback d-block mt-2">
                                        <i class="fas fa-exclamation-circle me-1"></i>{{ $message }}
                                    </div>
                                @enderror
                            </div>

                            <!-- Keterangan Field -->
                            <div class="col-md-3 mb-4">
                                <label for="keterangan" class="form-label fw-bold text-gray-700 mb-3">
                                    Kategori
                                </label>
                                <select
                                    id="keterangan"
                                    name="keterangan"
                                    class="form-select form-select-lg @error('keterangan') is-invalid @enderror"
                                >
                                    <option value="">Pilih...</option>
                                    <option value="1" {{ old('keterangan', $mahasiswa->keterangan) == 1 ? 'selected' : '' }}>Non-Akademik</option>
                                    <option value="3" {{ old('keterangan', $mahasiswa->keterangan) == 3 ? 'selected' : '' }}>Akademik</option>
                                </select>
                                @error('keterangan')
                                    <div class="invalid-feedback d-block mt-2">
                                        <i class="fas fa-exclamation-circle me-1"></i>{{ $message }}
                                    </div>
                                @enderror
                            </div>
                        </div>

                        <!-- Divider -->
                        <hr class="my-5">

                        <!-- Form Actions -->
                        <div class="d-flex justify-content-between align-items-center">
                            <a href="{{ route('admin.mahasiswa.index') }}" class="btn btn-lg btn-secondary px-5">
                                Batal
                            </a>
                            <button type="submit" class="btn btn-lg btn-primary px-5">
                                Update Data
                            </button>
                        </div>
                    </form>
                </div>
            </div>

            <!-- Info Card - Full Width -->
            <div class="card border-left-warning shadow">
                <div class="card-body">
                    <div class="row align-items-center">
                        <div class="col-md-1 text-center">
                            <i class="fas fa-exclamation-triangle fa-2x text-warning"></i>
                        </div>
                        <div class="col-md-11">
                            <div class="text-warning font-weight-bold mb-2">
                                Informasi Penting - Edit Data
                            </div>
                            <div class="text-gray-800">
                                <div class="row">
                                    <div class="col-md-3">
                                        <p class="mb-2"><i class="fas fa-check text-warning me-2"></i>Pastikan data yang diubah valid</p>
                                    </div>
                                    <div class="col-md-3">
                                        <p class="mb-2"><i class="fas fa-check text-warning me-2"></i>NIM harus tetap unik setelah diubah</p>
                                    </div>
                                    <div class="col-md-3">
                                        <p class="mb-2"><i class="fas fa-check text-warning me-2"></i>Angkatan berdasarkan tahun masuk</p>
                                    </div>
                                    <div class="col-md-3">
                                        <p class="mb-2"><i class="fas fa-check text-warning me-2"></i>Perubahan akan langsung tersimpan</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
.card {
    border: 1px solid #e3e6f0;
    border-radius: 0.5rem;
}

.card-body {
    padding: 2.5rem;
}

.form-control-lg, .form-select-lg {
    padding: 0.75rem 1rem;
    font-size: 1rem;
    border: 2px solid #e3e6f0;
    border-radius: 0.5rem;
    transition: all 0.3s;
    width: 100%;
}

.form-control-lg:focus, .form-select-lg:focus {
    border-color: #4e73df;
    box-shadow: 0 0 0 0.2rem rgba(78, 115, 223, 0.25);
}

.form-label {
    font-size: 1rem;
    color: #4a5568;
    display: block;
}

.btn-lg {
    padding: 0.75rem 2rem;
    font-weight: 600;
    border-radius: 0.5rem;
    min-width: 120px;
}

.btn-primary {
    background-color: #4e73df;
    border-color: #4e73df;
}

.btn-primary:hover {
    background-color: #2e59d9;
    border-color: #2e59d9;
}

.btn-secondary {
    background-color: #858796;
    border-color: #858796;
}

.btn-secondary:hover {
    background-color: #6c757d;
    border-color: #6c757d;
}

hr {
    border: 0;
    border-top: 2px solid #e3e6f0;
    opacity: 1;
}

.border-left-warning {
    border-left: 4px solid #f6c23e !important;
}

/* Ensure full width utilization */
.container-fluid {
    padding-left: 2rem;
    padding-right: 2rem;
}

.col-12 {
    padding-left: 0;
    padding-right: 0;
}

/* Responsive adjustments */
@media (max-width: 768px) {
    .container-fluid {
        padding-left: 1rem;
        padding-right: 1rem;
    }
    
    .card-body {
        padding: 1.5rem;
    }
    
    .btn-lg {
        padding: 0.6rem 1.5rem;
        min-width: 100px;
    }
}
</style>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const nimInput = document.getElementById('nim');
    nimInput.addEventListener('input', function(e) {
        this.value = this.value.replace(/[^0-9]/g, '');
    });

    const namaInput = document.getElementById('nama');
    namaInput.addEventListener('blur', function(e) {
        if (this.value) {
            this.value = this.value.replace(/\w\S*/g, function(txt) {
                return txt.charAt(0).toUpperCase() + txt.substr(1).toLowerCase();
            });
        }
    });

    // Form validation
    const form = document.querySelector('form');
    form.addEventListener('submit', function(e) {
        let isValid = true;
        const inputs = form.querySelectorAll('input[required], select[required]');
        
        inputs.forEach(input => {
            if (!input.value.trim()) {
                isValid = false;
                input.classList.add('is-invalid');
            } else {
                input.classList.remove('is-invalid');
            }
        });

        if (!isValid) {
            e.preventDefault();
            alert('Harap lengkapi semua field yang wajib diisi!');
        }
    });
});
</script>

@endsection