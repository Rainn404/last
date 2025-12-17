@extends('layouts.admin.app')

@section('title', 'Tambah Mahasiswa Bermasalah')

@section('content')
<div class="container-fluid">
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <h1 class="h3 mb-0 text-gray-800">Tambah Mahasiswa Bermasalah</h1>
        <a href="{{ route('admin.mahasiswa-bermasalah.index') }}" class="d-none d-sm-inline-block btn btn-sm btn-secondary shadow-sm">
            <i class="fas fa-arrow-left fa-sm text-white-50"></i> Kembali
        </a>
    </div>

    <div class="card shadow mb-4">
        <div class="card-header py-3">
            <h6 class="m-0 font-weight-bold text-primary">Form Tambah Mahasiswa Bermasalah</h6>
            <small class="text-muted">Anda dapat menambahkan beberapa mahasiswa sekaligus untuk pelanggaran yang sama</small>
        </div>
        <div class="card-body">
            <form action="{{ route('admin.mahasiswa-bermasalah.store-multiple') }}" method="POST" id="multipleMahasiswaForm">
                @csrf

                <!-- Data Mahasiswa (Bisa Multiple) -->
                <div class="mb-4">
                    <div class="d-flex justify-content-between align-items-center mb-3">
                        <div>
                            <h5 class="text-primary mb-0 d-inline">
                                <i class="fas fa-users me-2"></i>Data Mahasiswa
                            </h5>
                            <small class="text-muted ms-2">
                                (Jumlah: <span id="mahasiswa-counter">1</span>)
                            </small>
                        </div>
                        <!-- Input Jumlah Mahasiswa - Kecil di samping -->
                        <div class="d-flex align-items-center">
                            <div class="me-2">
                                <small class="text-muted me-2">Tambahkan:</small>
                            </div>
                            <div class="input-group input-group-sm" style="width: 170px;">
                                <input type="number" id="jumlah-mahasiswa" 
                                       class="form-control form-control-sm" 
                                       min="1" max="20" 
                                       value="1"
                                       required>
                                <button type="button" id="btn-tambah" class="btn btn-primary btn-sm">
                                    Tambah
                                </button>
                            </div>
                        </div>
                    </div>

                    <div class="alert alert-info mb-3">
                        <i class="fas fa-info-circle me-2"></i>
                        <small>Masukkan jumlah mahasiswa, klik "Tambah", kemudian isi data masing-masing mahasiswa. Sistem akan otomatis mengisi data lainnya ketika Anda memasukkan NIM.</small>
                    </div>

                    <div id="mahasiswa-container">
                        <!-- Item pertama -->
                        <div class="mahasiswa-item card mb-3 border-primary">
                            <div class="card-body position-relative">
                                <!-- Tombol hapus untuk item pertama (tersembunyi) -->
                                <button type="button" class="btn-remove-item btn btn-sm btn-danger position-absolute top-0 end-0 m-2 d-none">
                                    <i class="fas fa-times"></i>
                                </button>
                                
                                <!-- Badge nomor -->
                                <div class="mahasiswa-number">1</div>
                                
                                <div class="row g-3">
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label class="form-label fw-bold">NIM <span class="text-danger">*</span></label>
                                            <div class="input-group">
                                                <span class="input-group-text bg-light">
                                                    <i class="fas fa-id-card text-primary"></i>
                                                </span>
                                                <input type="text" name="mahasiswa[0][nim]"
                                                       class="form-control nim-input"
                                                       placeholder="Contoh: 123456789"
                                                       required
                                                       autocomplete="off">
                                            </div>
                                            <div class="mt-2">
                                                <small class="nim-status text-muted">Masukkan 9-10 digit NIM</small>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-5">
                                        <div class="form-group">
                                            <label class="form-label fw-bold">Nama Lengkap <span class="text-danger">*</span></label>
                                            <div class="input-group">
                                                <span class="input-group-text bg-light">
                                                    <i class="fas fa-user text-primary"></i>
                                                </span>
                                                <input type="text" name="mahasiswa[0][nama]"
                                                       class="form-control nama-input"
                                                       placeholder="Akan terisi otomatis"
                                                       readonly
                                                       required>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-3">
                                        <div class="form-group">
                                            <label class="form-label fw-bold">Semester <span class="text-danger">*</span></label>
                                            <div class="input-group">
                                                <span class="input-group-text bg-light">
                                                    <i class="fas fa-graduation-cap text-primary"></i>
                                                </span>
                                                <input type="number" name="mahasiswa[0][semester]"
                                                       class="form-control semester-input"
                                                       min="1" max="14"
                                                       placeholder="1-14"
                                                       required>
                                            </div>
                                        </div>
                                    </div>
                                    
                                    <div class="col-md-12">
                                        <div class="form-group">
                                            <label class="form-label fw-bold">Nama Orang Tua (Opsional)</label>
                                            <div class="input-group">
                                                <span class="input-group-text bg-light">
                                                    <i class="fas fa-users text-primary"></i>
                                                </span>
                                                <input type="text" name="mahasiswa[0][nama_orang_tua]" 
                                                       class="form-control orangtua-input" 
                                                       placeholder="Masukkan nama orang tua (Opsional)">
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                
                                <div class="row mt-2">
                                    <div class="col-12">
                                        <div class="mahasiswa-info bg-light p-2 rounded d-none">
                                            <div class="row">
                                                <div class="col-md-6">
                                                    <small class="text-muted">
                                                        <i class="fas fa-university me-1"></i>
                                                        <span class="prodi-info">-</span>
                                                    </small>
                                                </div>
                                                <div class="col-md-6">
                                                    <small class="text-muted">
                                                        <i class="fas fa-calendar me-1"></i>
                                                        Angkatan: <span class="angkatan-info">-</span>
                                                    </small>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Data Pelanggaran (Hanya Satu untuk Semua) -->
                <div class="mb-4">
                    <h5 class="text-primary mb-3">
                        <i class="fas fa-exclamation-triangle me-2"></i>Data Pelanggaran & Sanksi
                    </h5>
                    
                    <div class="card border-warning">
                        <div class="card-body">
                            <div class="row g-3">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label class="form-label fw-bold">Jenis Pelanggaran <span class="text-danger">*</span></label>
                                        <select name="pelanggaran_id" id="pelanggaran-select" class="form-control form-control-lg select-pelanggaran" required>
                                            <option value="">-- Pilih Pelanggaran --</option>
                                            @foreach ($pelanggaran as $p)
                                                <option value="{{ $p->id }}" {{ old('pelanggaran_id') == $p->id ? 'selected' : '' }}>
                                                    [{{ $p->kode_pelanggaran ?? 'P' . $p->id }}] {{ $p->nama_pelanggaran }}
                                                    @if($p->jenis_pelanggaran)
                                                        ({{ ucfirst($p->jenis_pelanggaran) }})
                                                    @endif
                                                </option>
                                            @endforeach
                                        </select>
                                        <small class="text-muted">Pilih jenis pelanggaran yang dilakukan</small>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label class="form-label fw-bold">Sanksi <span class="text-danger">*</span></label>
                                        <select name="sanksi_id" id="sanksi-select" class="form-control form-control-lg select-sanksi" required>
                                            <option value="">-- Pilih Sanksi --</option>
                                            @foreach ($sanksi as $s)
                                                <option value="{{ $s->id }}" {{ old('sanksi_id') == $s->id ? 'selected' : '' }}>
                                                    [{{ $s->id_sanksi }}] {{ $s->nama_sanksi }}
                                                    @if($s->jenis_sanksi)
                                                        ({{ ucfirst($s->jenis_sanksi) }})
                                                    @endif
                                                </option>
                                            @endforeach
                                        </select>
                                        <small class="text-muted">Sanksi yang akan diberikan</small>
                                    </div>
                                </div>
                                
                                <div class="col-12">
                                    <div class="form-group">
                                        <label class="form-label fw-bold">Deskripsi Pelanggaran <small class="text-muted">(opsional)</small></label>
                                        <textarea name="deskripsi" class="form-control form-control-lg" rows="4"
                                                  placeholder="Jelaskan secara detail mengenai pelanggaran yang dilakukan...">{{ old('deskripsi') }}</textarea>
                                        <small class="text-muted">Minimal 20 karakter. Jelaskan kronologi, tempat, dan waktu kejadian.</small>
                                    </div>
                                </div>
                                
                                <div class="col-12">
                                    <div class="alert alert-warning mb-0">
                                        <i class="fas fa-exclamation-triangle me-2"></i>
                                        <small>Data pelanggaran ini akan berlaku untuk semua mahasiswa di atas. Pastikan semua mahasiswa terlibat dalam pelanggaran yang sama.</small>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="d-flex justify-content-between align-items-center mt-4 pt-3 border-top">
                    <a href="{{ route('admin.mahasiswa-bermasalah.index') }}" class="btn btn-secondary">
                        <i class="fas fa-arrow-left me-2"></i>Kembali
                    </a>
                    <button type="submit" class="btn btn-success btn-lg px-4">
                        <i class="fas fa-save me-2"></i>Simpan Semua Data
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<style>
/* Basic styling */
.card {
    border: 1px solid #e3e6f0;
    border-radius: 0.5rem;
}

.card-body {
    padding: 1.5rem;
}

.form-control-lg {
    height: 48px;
    padding: 0.75rem 1rem;
    font-size: 1rem;
}

.form-group {
    margin-bottom: 1.5rem;
}

/* Custom select styling untuk dropdown yang lebih baik */
.select-pelanggaran, .select-sanksi {
    appearance: none;
    -webkit-appearance: none;
    -moz-appearance: none;
    background-image: url("data:image/svg+xml;charset=UTF-8,%3csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 24 24' fill='none' stroke='currentColor' stroke-width='2' stroke-linecap='round' stroke-linejoin='round'%3e%3cpolyline points='6 9 12 15 18 9'%3e%3c/polyline%3e%3c/svg%3e");
    background-repeat: no-repeat;
    background-position: right 1rem center;
    background-size: 1em;
    padding-right: 2.5rem;
    cursor: pointer;
}

/* Hover dan focus states */
.select-pelanggaran:hover, .select-sanksi:hover {
    border-color: #4e73df;
}

.select-pelanggaran:focus, .select-sanksi:focus {
    border-color: #4e73df;
    box-shadow: 0 0 0 0.2rem rgba(78, 115, 223, 0.25);
    outline: none;
}

/* Mahasiswa items */
.mahasiswa-item {
    border-left: 4px solid #4e73df !important;
    margin-bottom: 1rem;
    position: relative;
}

.mahasiswa-item:nth-child(n+2) {
    border-left: 4px solid #1cc88a !important;
}

/* Number badge for mahasiswa items */
.mahasiswa-number {
    position: absolute;
    top: -10px;
    left: -10px;
    background: #4e73df;
    color: white;
    width: 28px;
    height: 28px;
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 14px;
    font-weight: bold;
    z-index: 1;
    box-shadow: 0 2px 4px rgba(0,0,0,0.1);
}

.mahasiswa-item:nth-child(n+2) .mahasiswa-number {
    background: #1cc88a;
}

/* Tombol hapus */
.btn-remove-item {
    width: 28px;
    height: 28px;
    padding: 0;
    display: flex;
    align-items: center;
    justify-content: center;
    z-index: 1;
    opacity: 0.7;
    transition: opacity 0.2s;
}

.btn-remove-item:hover {
    opacity: 1;
    transform: scale(1.1);
}

/* Input group untuk jumlah mahasiswa */
.input-group.input-group-sm {
    max-width: 170px;
}

#btn-tambah {
    min-width: 70px;
    padding: 0.25rem 0.5rem;
    font-size: 0.875rem;
    font-weight: 500;
}

#btn-tambah:hover {
    background-color: #2e59d9;
    transform: translateY(-1px);
    box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
}

/* Input groups */
.input-group-text {
    background-color: #f8f9fc;
    border: 1px solid #e3e6f0;
    color: #6e707e;
}

/* Loading spinner */
.loading-spinner {
    display: inline-block;
    width: 16px;
    height: 16px;
    border: 2px solid #f3f3f3;
    border-top: 2px solid #3498db;
    border-radius: 50%;
    animation: spin 1s linear infinite;
    margin-right: 8px;
    vertical-align: middle;
}

@keyframes spin {
    0% { transform: rotate(0deg); }
    100% { transform: rotate(360deg); }
}

/* Responsive */
@media (max-width: 768px) {
    .container-fluid {
        padding-left: 1rem;
        padding-right: 1rem;
    }
    
    .card-body {
        padding: 1rem;
    }
    
    .col-md-4, .col-md-5, .col-md-3, .col-md-6 {
        margin-bottom: 1rem;
    }
    
    .d-flex.justify-content-between.align-items-center.mb-3 {
        flex-direction: column;
        align-items: flex-start !important;
    }
    
    .d-flex.align-items-center {
        margin-top: 0.5rem;
        width: 100%;
    }
    
    .input-group.input-group-sm {
        max-width: 100%;
    }
}
</style>

<!-- LOAD JQUERY DENGAN BENAR -->
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<script>
$(document).ready(function() {
    console.log('Document ready - initializing form...');
    
    // Load Select2 jika diperlukan
    loadSelect2(function() {
        if (isSelect2Loaded()) {
            initializeSelect2();
        } else {
            enableEnhancedSelects();
        }
    });
    
    // ========== MAHASISWA FUNCTIONS ==========
    let mahasiswaCount = 1;
    let searchTimeout = null;
    
    // Update counter
    function updateCounter() {
        const count = $('.mahasiswa-item').length;
        $('#mahasiswa-counter').text(count);
        
        // Update jumlah input
        $('#jumlah-mahasiswa').val(count);
        
        // Update tombol hapus
        updateDeleteButtons();
    }
    
    // Update tombol hapus (sembunyikan untuk item pertama jika hanya ada 1)
    function updateDeleteButtons() {
        const total = $('.mahasiswa-item').length;
        
        $('.mahasiswa-item').each(function(index) {
            const $deleteBtn = $(this).find('.btn-remove-item');
            
            if (total <= 1) {
                // Sembunyikan tombol hapus jika hanya ada 1 item
                $deleteBtn.addClass('d-none');
            } else {
                // Tampilkan tombol hapus untuk semua item
                $deleteBtn.removeClass('d-none');
            }
        });
    }
    
    // Function to search student by NIM
    function searchStudentByNIM(nimInput, card) {
        const nim = nimInput.val().trim();
        
        if (nim.length < 9 || nim.length > 10) {
            card.find('.nim-status').removeClass('text-success text-danger').addClass('text-muted')
                .html(`<i class="fas fa-info-circle me-1"></i>Masukkan 9-10 digit NIM`);
            card.find('.mahasiswa-info').addClass('d-none');
            card.find('.nama-input').val('');
            return;
        }
        
        // Show loading
        card.find('.nim-status').removeClass('text-success text-danger').addClass('text-info')
            .html(`<span class="loading-spinner"></span> Mencari data...`);
        card.find('.nama-input').val('Mencari...');
        
        // Clear previous timeout
        if (searchTimeout) {
            clearTimeout(searchTimeout);
        }
        
        // Debounce search
        searchTimeout = setTimeout(function() {
            $.ajax({
                url: `/admin/mahasiswa-bermasalah/get-mahasiswa/${nim}`,
                method: 'GET',
                dataType: 'json',
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                success: function(data) {
                    console.log('Search response:', data);

                    if (data && data.success) {
                        const mhs = data.mahasiswa ? data.mahasiswa : data;

                        if (mhs.nama) {
                            card.find('.nama-input').val(mhs.nama);
                        }

                        if (mhs.semester) {
                            card.find('.semester-input').val(mhs.semester);
                        }

                        if (mhs.nama_orang_tua) {
                            card.find('.orangtua-input').val(mhs.nama_orang_tua);
                        }

                        card.find('.prodi-info').text(mhs.prodi || mhs.jurusan || 'Tidak diketahui');
                        card.find('.angkatan-info').text(mhs.angkatan || 'Tidak diketahui');
                        card.find('.mahasiswa-info').removeClass('d-none');

                        card.find('.nim-status').removeClass('text-info text-danger').addClass('text-success')
                            .html(`<i class="fas fa-check-circle me-1"></i>${mhs.nama || 'Ditemukan'}`);

                        if (!card.find('.semester-input').val()) {
                            setTimeout(() => card.find('.semester-input').focus(), 100);
                        }
                    } else {
                        card.find('.nama-input').val('');
                        card.find('.mahasiswa-info').addClass('d-none');
                        card.find('.nim-status').removeClass('text-info text-success').addClass('text-danger')
                            .html(`<i class="fas fa-exclamation-circle me-1"></i>${(data && (data.message || data.error)) || 'Mahasiswa tidak ditemukan'}`);
                    }
                },
                error: function(xhr, status, error) {
                    console.error('Search error:', error);
                    card.find('.nama-input').val('');
                    card.find('.mahasiswa-info').addClass('d-none');
                    card.find('.nim-status').removeClass('text-info text-success').addClass('text-danger')
                        .html(`<i class="fas fa-exclamation-circle me-1"></i>Gagal mencari data`);
                }
            });
        }, 500);
    }
    
    // Setup NIM auto-search
    function setupNIMSearch(nimInput) {
        const card = nimInput.closest('.mahasiswa-item');
        
        nimInput.off('input').on('input', function() {
            searchStudentByNIM($(this), $(card));
        });
        
        nimInput.off('paste').on('paste', function() {
            setTimeout(() => {
                searchStudentByNIM($(this), $(card));
            }, 100);
        });
    }
    
    // Setup for all existing NIM inputs
    $('.nim-input').each(function() {
        setupNIMSearch($(this));
    });
    
    // Tambah form berdasarkan jumlah mahasiswa
    $('#btn-tambah').on('click', function() {
        const jumlah = parseInt($('#jumlah-mahasiswa').val());
        
        if (jumlah < 1 || jumlah > 20 || isNaN(jumlah)) {
            Swal.fire({
                icon: 'error',
                title: 'Jumlah Tidak Valid',
                text: 'Masukkan jumlah antara 1-20',
                confirmButtonColor: '#e74a3b'
            });
            return;
        }
        
        const currentCount = $('.mahasiswa-item').length;
        
        if (jumlah <= currentCount) {
            // Jika jumlah kurang dari atau sama dengan yang ada, hapus kelebihan
            if (jumlah < currentCount) {
                Swal.fire({
                    title: 'Kurangi Jumlah?',
                    text: `Anda akan mengurangi jumlah mahasiswa dari ${currentCount} menjadi ${jumlah}. Lanjutkan?`,
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonText: 'Ya, Kurangi',
                    cancelButtonText: 'Batal',
                    confirmButtonColor: '#e74a3b',
                    cancelButtonColor: '#6c757d'
                }).then((result) => {
                    if (result.isConfirmed) {
                        // Remove excess items
                        for (let i = currentCount - 1; i >= jumlah; i--) {
                            $(`.mahasiswa-item:eq(${i})`).remove();
                        }
                        reindexForm();
                        updateCounter();
                    }
                });
            }
            return;
        }
        
        // Add new items
        for (let i = currentCount; i < jumlah; i++) {
            addMahasiswaItem(i);
        }
        updateCounter();
        
        // Beri feedback visual
        const $btn = $(this);
        const originalText = $btn.text();
        $btn.text('Ditambahkan!').addClass('btn-success').removeClass('btn-primary');
        
        setTimeout(function() {
            $btn.text(originalText).addClass('btn-primary').removeClass('btn-success');
        }, 1000);
    });
    
    // Function to add a new mahasiswa item
    function addMahasiswaItem(index) {
        const firstItem = $('.mahasiswa-item').first();
        const newItem = firstItem.clone();
        
        // Reset values
        newItem.find('input').val('');
        newItem.find('.nim-status').removeClass('text-success text-danger').addClass('text-muted')
            .html('<i class="fas fa-info-circle me-1"></i>Masukkan 9-10 digit NIM');
        newItem.find('.mahasiswa-info').addClass('d-none');
        newItem.find('.prodi-info').text('-');
        newItem.find('.angkatan-info').text('-');
        
        // Update names with new index
        newItem.find('[name]').each(function() {
            const name = $(this).attr('name').replace(/\[\d+\]/, `[${index}]`);
            $(this).attr('name', name);
        });
        
        // Update card border color for non-first items
        if (index > 0) {
            newItem.removeClass('border-primary').addClass('border-success');
        }
        
        // Update number badge
        newItem.find('.mahasiswa-number').text(index + 1);
        
        // Show delete button
        newItem.find('.btn-remove-item').removeClass('d-none');
        
        // Setup NIM search for new input
        const newNimInput = newItem.find('.nim-input');
        setupNIMSearch(newNimInput);
        
        // Add to container
        $('#mahasiswa-container').append(newItem);
        
        // Focus on new NIM input
        setTimeout(() => newNimInput.focus(), 100);
    }
    
    // Handle delete button click
    $(document).on('click', '.btn-remove-item', function() {
        const $item = $(this).closest('.mahasiswa-item');
        const itemNumber = $item.find('.mahasiswa-number').text();
        const total = $('.mahasiswa-item').length;
        
        if (total <= 1) {
            Swal.fire({
                icon: 'warning',
                title: 'Tidak Dapat Menghapus',
                text: 'Minimal harus ada 1 mahasiswa',
                confirmButtonColor: '#6c757d'
            });
            return;
        }
        
        Swal.fire({
            title: 'Hapus Mahasiswa?',
            html: `Anda akan menghapus mahasiswa <b>#${itemNumber}</b>.<br>Data yang sudah diisi akan hilang.`,
            icon: 'warning',
            showCancelButton: true,
            confirmButtonText: 'Ya, Hapus',
            cancelButtonText: 'Batal',
            confirmButtonColor: '#e74a3b',
            cancelButtonColor: '#6c757d'
        }).then((result) => {
            if (result.isConfirmed) {
                $item.remove();
                reindexForm();
                updateCounter();
                
                // Update jumlah input
                $('#jumlah-mahasiswa').val($('.mahasiswa-item').length);
            }
        });
    });
    
    // Reindex form
    function reindexForm() {
        $('.mahasiswa-item').each(function(index) {
            $(this).find('[name]').each(function() {
                const name = $(this).attr('name').replace(/\[\d+\]/, `[${index}]`);
                $(this).attr('name', name);
            });
            
            // Update number badge
            $(this).find('.mahasiswa-number').text(index + 1);
            
            // Update border color
            if (index === 0) {
                $(this).removeClass('border-success').addClass('border-primary');
            } else {
                $(this).removeClass('border-primary').addClass('border-success');
            }
        });
        mahasiswaCount = $('.mahasiswa-item').length;
    }
    
    // Form validation
    $('#multipleMahasiswaForm').on('submit', function(e) {
        e.preventDefault();
        
        let isValid = true;
        const errors = [];
        
        // Validate students
        $('.mahasiswa-item').each(function(index) {
            const card = $(this);
            const nim = card.find('.nim-input').val().trim();
            const nama = card.find('.nama-input').val().trim();
            const semester = card.find('.semester-input').val().trim();
            const nimStatus = card.find('.nim-status');
            
            // Validate NIM
            if (!nim) {
                isValid = false;
                errors.push(`Mahasiswa ${index + 1}: NIM harus diisi`);
                card.find('.nim-input').focus();
                return false;
            } else if (nim.length < 9 || nim.length > 10) {
                isValid = false;
                errors.push(`Mahasiswa ${index + 1}: NIM harus 9-10 digit`);
                card.find('.nim-input').focus();
                return false;
            } else if (nimStatus.hasClass('text-danger')) {
                isValid = false;
                errors.push(`Mahasiswa ${index + 1}: Data mahasiswa tidak ditemukan. Periksa NIM`);
                card.find('.nim-input').focus();
                return false;
            }
            
            // Validate name
            if (!nama || nama === 'Mencari...') {
                isValid = false;
                errors.push(`Mahasiswa ${index + 1}: Data mahasiswa tidak valid. Pastikan NIM benar`);
                card.find('.nim-input').focus();
                return false;
            }
            
            // Validate semester
            if (!semester) {
                isValid = false;
                errors.push(`Mahasiswa ${index + 1}: Semester harus diisi`);
                card.find('.semester-input').focus();
                return false;
            } else if (semester < 1 || semester > 14) {
                isValid = false;
                errors.push(`Mahasiswa ${index + 1}: Semester harus antara 1-14`);
                card.find('.semester-input').focus();
                return false;
            }
        });
        
        // Validate violation data
        const pelanggaran = $('#pelanggaran-select').val();
        const sanksi = $('#sanksi-select').val();
        const deskripsi = $('textarea[name="deskripsi"]').val().trim();
        
        if (!pelanggaran) {
            isValid = false;
            errors.push('Pilih jenis pelanggaran');
            $('#pelanggaran-select').css('border-color', '#e74a3b');
        } else {
            $('#pelanggaran-select').css('border-color', '#d1d3e2');
        }
        
        if (!sanksi) {
            isValid = false;
            errors.push('Pilih sanksi yang sesuai');
            $('#sanksi-select').css('border-color', '#e74a3b');
        } else {
            $('#sanksi-select').css('border-color', '#d1d3e2');
        }
        
        if (deskripsi && deskripsi.length < 20) {
            isValid = false;
            errors.push('Deskripsi pelanggaran minimal 20 karakter');
        }
        
        if (!isValid) {
            Swal.fire({
                icon: 'error',
                title: 'Validasi Gagal',
                html: errors.join('<br>'),
                confirmButtonText: 'Perbaiki',
                confirmButtonColor: '#e74a3b'
            });
            return;
        }
        
        // Confirmation dialog
        const studentCount = $('.mahasiswa-item').length;
        Swal.fire({
            title: 'Konfirmasi Penyimpanan',
            html: `Anda akan menyimpan data untuk <b>${studentCount} mahasiswa</b> dengan pelanggaran yang sama.<br><br>Apakah data sudah benar?`,
            icon: 'question',
            showCancelButton: true,
            confirmButtonText: 'Ya, Simpan',
            cancelButtonText: 'Periksa Lagi',
            confirmButtonColor: '#1cc88a',
            cancelButtonColor: '#6c757d'
        }).then((result) => {
            if (result.isConfirmed) {
                Swal.fire({
                    title: 'Menyimpan Data',
                    text: 'Harap tunggu...',
                    allowOutsideClick: false,
                    didOpen: () => {
                        Swal.showLoading();
                    }
                });
                
                this.submit();
            }
        });
    });
    
    // Initialize
    updateCounter();
    console.log('Form initialization complete');
});

// Fungsi untuk memeriksa apakah Select2 tersedia
function isSelect2Loaded() {
    return typeof $.fn.select2 !== 'undefined';
}

// Fungsi untuk memuat Select2 secara dinamis
function loadSelect2(callback) {
    if (isSelect2Loaded()) {
        if (callback) callback();
        return;
    }
    
    // Load CSS
    var cssLink = document.createElement('link');
    cssLink.rel = 'stylesheet';
    cssLink.href = 'https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css';
    document.head.appendChild(cssLink);
    
    // Load JS
    var script = document.createElement('script');
    script.src = 'https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js';
    script.onload = function() {
        if (callback) callback();
    };
    script.onerror = function() {
        enableEnhancedSelects();
    };
    document.head.appendChild(script);
}

// Fallback function
function enableEnhancedSelects() {
    $('.select-pelanggaran, .select-sanksi').addClass('enhanced-select');
}

// Function to initialize Select2
function initializeSelect2() {
    try {
        $('#pelanggaran-select').select2({
            theme: 'classic',
            width: '100%',
            placeholder: '-- Pilih Pelanggaran --',
            allowClear: false,
            dropdownParent: $('body')
        });
        
        $('#sanksi-select').select2({
            theme: 'classic',
            width: '100%',
            placeholder: '-- Pilih Sanksi --',
            allowClear: false,
            dropdownParent: $('body')
        });
    } catch (error) {
        console.error('Error initializing Select2:', error);
        $('.select-pelanggaran, .select-sanksi').addClass('enhanced-select');
    }
}

// Handle success/error messages
@if(session('success'))
$(document).ready(function() {
    Swal.fire({
        icon: 'success',
        title: 'Berhasil!',
        text: '{{ session('success') }}',
        confirmButtonColor: '#1cc88a'
    });
});
@endif

@if($errors->any())
$(document).ready(function() {
    Swal.fire({
        icon: 'error',
        title: 'Gagal!',
        html: '{!! implode('<br>', $errors->all()) !!}',
        confirmButtonColor: '#e74a3b'
    });
});
@endif
</script>
@endsection