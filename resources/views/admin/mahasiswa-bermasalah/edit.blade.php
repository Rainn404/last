@php $layout = request()->is('admin-panel/*') ? 'layouts.admin-panel.app' : 'layouts.admin.app'; @endphp
@extends($layout)

@section('title', 'Edit Mahasiswa Bermasalah')

@section('content')
<div class="container-fluid">
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <h1 class="h3 mb-0 text-gray-800">Edit Mahasiswa Bermasalah</h1>
        <a href="{{ route('admin.mahasiswa-bermasalah.index') }}" class="d-none d-sm-inline-block btn btn-sm btn-secondary shadow-sm">
            <i class="fas fa-arrow-left fa-sm text-white-50"></i> Kembali
        </a>
    </div>

    <div class="card shadow mb-4">
        <div class="card-header py-3">
            <h6 class="m-0 font-weight-bold text-primary">Form Edit Mahasiswa Bermasalah</h6>
        </div>
        <div class="card-body">
            <form action="{{ route('admin.mahasiswa-bermasalah.update', $mahasiswaBermasalah->id) }}" method="POST" id="editForm">
                @csrf
                @method('PUT')

                <div class="row">
                    <div class="col-md-6">
                        <div class="mb-3">
                            <label for="nim" class="form-label">NIM <span class="text-danger">*</span></label>
                            <input type="text" name="nim" id="nim" class="form-control" required 
                                   placeholder="Masukkan NIM mahasiswa" value="{{ old('nim', $mahasiswaBermasalah->nim) }}">
                            <div id="nim-error" class="small mt-1"></div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="mb-3">
                            <label for="nama" class="form-label">Nama <span class="text-danger">*</span></label>
                            <input type="text" name="nama" id="nama" class="form-control" readonly 
                                   placeholder="Nama akan terisi otomatis" value="{{ old('nama', $mahasiswaBermasalah->nama) }}">
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-6">
                        <div class="mb-3">
                            <label for="semester" class="form-label">Semester <span class="text-danger">*</span></label>
                            <input type="number" name="semester" id="semester" class="form-control" min="1" max="14" 
                                   placeholder="Masukkan semester" value="{{ old('semester', $mahasiswaBermasalah->semester) }}" required>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="mb-3">
                            <label for="nama_orang_tua" class="form-label">Nama Orang Tua <small class="text-muted">(opsional)</small></label>
                            <input type="text" name="nama_orang_tua" id="nama_orang_tua" class="form-control" 
                                placeholder="Masukkan nama orang tua" value="{{ old('nama_orang_tua', $mahasiswaBermasalah->nama_orang_tua) }}">
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-6">
                        <div class="mb-3">
                            <label for="pelanggaran_id" class="form-label">Pelanggaran <span class="text-danger">*</span></label>
                            <select name="pelanggaran_id" id="pelanggaran_id" class="form-control" required>
                                <option value="">-- Pilih Pelanggaran --</option>
                                @foreach ($pelanggaran as $p)
                                    <option value="{{ $p->id }}" {{ old('pelanggaran_id', $mahasiswaBermasalah->pelanggaran_id) == $p->id ? 'selected' : '' }}>
                                        {{ $p->nama_pelanggaran }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="mb-3">
                            <label for="sanksi_id" class="form-label">Sanksi <span class="text-danger">*</span></label>
                            <select name="sanksi_id" id="sanksi_id" class="form-control" required>
                                <option value="">-- Pilih Sanksi --</option>
                                @foreach ($sanksi as $s)
                                    <option value="{{ $s->id }}" {{ old('sanksi_id', $mahasiswaBermasalah->sanksi_id) == $s->id ? 'selected' : '' }}>
                                        {{ $s->nama_sanksi }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                </div>

                <div class="mb-3">
                    <label for="deskripsi" class="form-label">Deskripsi <small class="text-muted">(opsional)</small></label>
                    <textarea name="deskripsi" id="deskripsi" class="form-control" rows="4" 
                              placeholder="Masukkan deskripsi lengkap mengenai masalah yang terjadi...">{{ old('deskripsi', $mahasiswaBermasalah->deskripsi) }}</textarea>
                </div>

                <div class="d-flex justify-content-between align-items-center">
                    <a href="{{ route('admin.mahasiswa-bermasalah.index') }}" class="btn btn-secondary">
                        <i class="fas fa-arrow-left me-2"></i>Kembali
                    </a>
                    <button type="submit" class="btn btn-primary" id="submitBtn">
                        <i class="fas fa-save me-2"></i>Update Data
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const nimInput = document.getElementById('nim');
    const namaInput = document.getElementById('nama');
    const nimError = document.getElementById('nim-error');
    const semesterInput = document.getElementById('semester');
    const orangTuaInput = document.getElementById('nama_orang_tua');
    const submitBtn = document.getElementById('submitBtn');
    const editForm = document.getElementById('editForm');

    // Fungsi untuk mencari data mahasiswa berdasarkan NIM
    function searchMahasiswa(nim) {
        if (nim.length >= 8) {
            // Show loading state
            nimError.textContent = 'Mencari data mahasiswa...';
            nimError.className = 'text-info small mt-1';
            
            fetch(`/admin/mahasiswa-bermasalah/get-mahasiswa/${nim}`)
                .then(response => {
                    if (!response.ok) {
                        throw new Error('Mahasiswa tidak ditemukan');
                    }
                    return response.json();
                })
                .then(data => {
                    if (data.error) {
                        throw new Error(data.error);
                    }
                    
                    // Isi data otomatis
                    namaInput.value = data.nama || '';
                    
                    // Auto-fill semester dan nama orang tua jika kosong
                    if (semesterInput && !semesterInput.value && data.semester && data.semester !== 'Tidak diketahui') {
                        semesterInput.value = data.semester;
                    }
                    if (orangTuaInput && !orangTuaInput.value && data.nama_orang_tua && data.nama_orang_tua !== 'Tidak diketahui') {
                        orangTuaInput.value = data.nama_orang_tua;
                    }
                    
                    nimError.textContent = '✓ Data ditemukan';
                    nimError.className = 'text-success small mt-1';
                    
                })
                .catch(error => {
                    nimError.textContent = error.message;
                    nimError.className = 'text-danger small mt-1';
                    namaInput.value = '';
                });
        } else if (nim.length > 0) {
            nimError.textContent = 'NIM harus minimal 8 karakter';
            nimError.className = 'text-danger small mt-1';
            namaInput.value = '';
        } else {
            nimError.textContent = '';
            nimError.className = 'small mt-1';
            namaInput.value = '';
        }
    }

    nimInput.addEventListener('blur', function() {
        const nim = this.value.trim();
        searchMahasiswa(nim);
    });

    // Validate form before submission
    editForm.addEventListener('submit', function(e) {
        const nim = nimInput.value.trim();
        const nama = namaInput.value.trim();
        const semester = semesterInput.value.trim();
        const namaOrangTua = orangTuaInput.value.trim();
        const pelanggaran = document.getElementById('pelanggaran_id').value;
        const sanksi = document.getElementById('sanksi_id').value;
        const deskripsi = document.getElementById('deskripsi').value.trim();
        
        let errors = [];
        
        if (!nim || !nama) {
            errors.push('Harap isi NIM dan pastikan data mahasiswa ditemukan');
        }
        
        if (!semester) {
            errors.push('Harap isi semester');
        }
        
        if (!namaOrangTua) {
            errors.push('Harap isi nama orang tua');
        }
        
        if (!pelanggaran) {
            errors.push('Harap pilih pelanggaran');
        }
        
        if (!sanksi) {
            errors.push('Harap pilih sanksi');
        }
        
        if (!deskripsi) {
            errors.push('Harap isi deskripsi');
        }

        if (errors.length > 0) {
            e.preventDefault();
            alert('Terjadi kesalahan:\n' + errors.join('\n'));
        }
    });

    // Trigger search on page load if NIM already has value
    if (nimInput.value.trim().length >= 8) {
        searchMahasiswa(nimInput.value.trim());
    }
});
</script>
@endsection