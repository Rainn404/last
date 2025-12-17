@php $layout = request()->is('admin-panel/*') ? 'layouts.admin-panel.app' : 'layouts.admin.app'; @endphp
@extends($layout)

@section('title', 'Kelola Prestasi - Admin HIMA-TI')

@section('content')
<div class="container-fluid py-4">
    <!-- Header -->
    <div class="hero-section">
        <div class="container">
            <div class="row align-items-center">
                <div class="col-md-8">
                    <h1 class="page-title mb-2">
                        <i class="fas fa-trophy me-2"></i>Kelola Prestasi
                    </h1>
                    <p class="mb-0">Kelola dan validasi semua prestasi mahasiswa</p>
                </div>
                <div class="col-md-4 text-md-end">
                    <div class="admin-badges">
                        <span class="badge bg-light text-dark">
                            <i class="fas fa-user-shield me-1"></i>Administrator
                        </span>
                        <div class="badge-subtext text-muted small">Panel Admin</div>
                    </div>
                </div>
            <div class="col-md-2 col-6 mb-3">
                <div class="admin-stat-card">
                    <div class="stat-icon bg-warning">
                        <i class="fas fa-clock"></i>
                    </div>
                    <div class="stat-content">
                        <div class="stat-number">{{ $prestasiMenunggu }}</div>
                        <div class="stat-label">Menunggu</div>
                    </div>
                </div>
            </div>
            <div class="col-md-2 col-6 mb-3">
                <div class="admin-stat-card">
                    <div class="stat-icon bg-success">
                        <i class="fas fa-check-circle"></i>
                    </div>
                    <div class="stat-content">
                        <div class="stat-number">{{ $prestasiTervalidasi }}</div>
                        <div class="stat-label">Tervalidasi</div>
                    </div>
                </div>
            </div>
            <div class="col-md-2 col-6 mb-3">
                <div class="admin-stat-card">
                    <div class="stat-icon bg-danger">
                        <i class="fas fa-times-circle"></i>
                    </div>
                    <div class="stat-content">
                        <div class="stat-number">{{ $prestasiDitolak }}</div>
                        <div class="stat-label">Ditolak</div>
                    </div>
                </div>
            </div>
            <div class="col-md-2 col-6 mb-3">
                <div class="admin-stat-card">
                    <div class="stat-icon bg-info">
                        <i class="fas fa-users"></i>
                    </div>
                    <div class="stat-content">
                        <div class="stat-number">{{ $totalUsers }}</div>
                        <div class="stat-label">Mahasiswa</div>
                    </div>
                </div>
            </div>
            <div class="col-md-2 col-6 mb-3">
                <div class="admin-stat-card">
                    <div class="stat-icon bg-secondary">
                        <i class="fas fa-chart-line"></i>
                    </div>
                    <div class="stat-content">
                        <div class="stat-number">{{ number_format($rataRataIPK, 2) }}</div>
                        <div class="stat-label">Rata IPK</div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Filter Section -->
        <div class="filter-section mb-4">
            <div class="row">
                <div class="col-md-4">
                    <div class="filter-label">Filter Status</div>
                    <select class="form-select" id="statusFilter">
                        <option value="all">Semua Status</option>
                        <option value="Menunggu Validasi" {{ request('status') == 'Menunggu Validasi' ? 'selected' : '' }}>Menunggu Validasi</option>
                        <option value="Tervalidasi" {{ request('status') == 'Tervalidasi' ? 'selected' : '' }}>Tervalidasi</option>
                        <option value="Ditolak" {{ request('status') == 'Ditolak' ? 'selected' : '' }}>Ditolak</option>
                    </select>
                </div>
                <div class="col-md-4">
                    <div class="filter-label">Filter Kategori</div>
                    <select class="form-select" id="categoryFilter">
                        <option value="all">Semua Kategori</option>
                        <option value="Akademik" {{ request('kategori') == 'Akademik' ? 'selected' : '' }}>Akademik</option>
                        <option value="Non-Akademik" {{ request('kategori') == 'Non-Akademik' ? 'selected' : '' }}>Non-Akademik</option>
                        <option value="Olahraga" {{ request('kategori') == 'Olahraga' ? 'selected' : '' }}>Olahraga</option>
                        <option value="Seni" {{ request('kategori') == 'Seni' ? 'selected' : '' }}>Seni</option>
                        <option value="Teknologi" {{ request('kategori') == 'Teknologi' ? 'selected' : '' }}>Teknologi</option>
                        <option value="Lainnya" {{ request('kategori') == 'Lainnya' ? 'selected' : '' }}>Lainnya</option>
                    </select>
                </div>
                <div class="col-md-4">
                    <div class="filter-label">Cari Prestasi</div>
                    <div class="input-group">
                        <input type="text" class="form-control" placeholder="Cari prestasi..." id="searchInput" value="{{ request('search') }}">
                        <button class="btn btn-primary" type="button" id="searchButton">
                            <i class="fas fa-search"></i>
                        </button>
                    </div>
                </div>
            </div>
            <div class="row mt-3">
                <div class="col-12">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <a href="{{ route('admin.prestasi.create') }}" class="btn btn-success">
                                <i class="fas fa-plus me-2"></i>Tambah Prestasi
                            </a>
                        </div>
                        <div class="text-muted">
                            Total: <strong>{{ $prestasi->total() }}</strong> prestasi
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Main Content -->
        <div class="card">
            <div class="card-body p-0">
                <!-- Alert Notifikasi -->
                @if(session('success'))
                <div class="alert alert-success alert-dismissible fade show m-3" role="alert">
                    <div class="d-flex align-items-center">
                        <i class="fas fa-check-circle me-2"></i>
                        <div>{{ session('success') }}</div>
                    </div>
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
                @endif

                @if(session('error'))
                <div class="alert alert-danger alert-dismissible fade show m-3" role="alert">
                    <div class="d-flex align-items-center">
                        <i class="fas fa-exclamation-circle me-2"></i>
                        <div>{{ session('error') }}</div>
                    </div>
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
                @endif

                @if($prestasi->isEmpty())
                <div class="empty-state text-center py-5">
                    <i class="fas fa-trophy text-primary fa-4x mb-4"></i>
                    <h3 class="mb-3">Belum ada prestasi</h3>
                    <p class="text-muted mb-4">Tidak ada data prestasi yang ditemukan</p>
                    <a href="{{ route('admin.prestasi.create') }}" class="btn btn-primary btn-lg">
                        <i class="fas fa-plus me-2"></i>Tambah Prestasi Pertama
                    </a>
                </div>
                @else
                <div class="table-responsive">
                    <table class="table table-hover mb-0">
                        <thead class="table-light">
                            <tr>
                                <th>#</th>
                                <th>Mahasiswa</th>
                                <th>Prestasi</th>
                                <th>Kategori</th>
                                <th>Tanggal</th>
                                <th>Semester</th>
                                <th>IPK</th>
                                <th>Status</th>
                                <th class="text-center pe-4">Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($prestasi as $item)
                            <tr class="prestasi-item" data-id="{{ $item->id_prestasi ?? $item->id }}">
                                <td>{{ $loop->iteration + ($prestasi->currentPage() - 1) * $prestasi->perPage() }}</td>
                                <td>
                                    <div class="d-flex align-items-center">
                                        <div class="flex-shrink-0 me-3">
                                            <div class="bg-primary bg-opacity-10 rounded-circle d-flex align-items-center justify-content-center" style="width: 40px; height: 40px;">
                                                <i class="fas fa-user text-primary"></i>
                                            </div>
                                        </div>
                                        <div class="flex-grow-1">
                                            <strong class="d-block">{{ $item->user->name ?? $item->nama ?? 'N/A' }}</strong>
                                            <small class="text-muted">{{ $item->user->nim ?? $item->nim ?? 'N/A' }}</small>
                                        </div>
                                    </div>
                                </td>
                                <td>
                                    <strong class="d-block">{{ $item->nama_prestasi ?? $item->nama }}</strong>
                                    <small class="text-muted">
                                        @if($item->bukti_prestasi || $item->bukti)
                                        <i class="fas fa-paperclip me-1"></i>Bukti terlampir
                                        @else
                                        <i class="fas fa-exclamation-circle me-1"></i>Tidak ada bukti
                                        @endif
                                    </small>
                                </td>
                                <td>
                                    <span class="badge bg-light text-dark text-capitalize">{{ $item->kategori }}</span>
                                </td>
                                <td>
                                    <small>
                                        <div>{{ \Carbon\Carbon::parse($item->tanggal_mulai)->format('d M Y') }}</div>
                                        <div class="text-muted">s/d</div>
                                        <div>{{ \Carbon\Carbon::parse($item->tanggal_selesai)->format('d M Y') }}</div>
                                    </small>
                                </td>
                                <td>
                                    <span class="badge bg-secondary">S{{ $item->semester }}</span>
                                </td>
                                <td>
                                    @if($item->ipk)
                                    <span class="badge bg-info">{{ number_format($item->ipk, 2) }}</span>
                                    @else
                                    <span class="text-muted">-</span>
                                    @endif
                                </td>
                                <td>
                                    <div class="d-flex flex-column gap-1">
                                        <!-- Badge Status -->
                                        @if($item->status_validasi)
                                            <span class="badge status-badge 
                                                {{ $item->status_validasi == 'disetujui' ? 'bg-success' : 
                                                   ($item->status_validasi == 'ditolak' ? 'bg-danger' : 'bg-warning') }}">
                                                @if($item->status_validasi == 'disetujui') Tervalidasi @elseif($item->status_validasi == 'ditolak') Ditolak @else Menunggu Validasi @endif
                                            </span>
                                        @else
                                            <span class="badge bg-secondary">Belum Ada Status</span>
                                        @endif

                                        <!-- Tombol Validasi Cepat -->
                                        @if($item->status_validasi == 'pending' || !$item->status_validasi)
                                        <div class="d-flex gap-1 mt-1">
                                            <form action="{{ route('admin.prestasi.validasi', $item->id_prestasi ?? $item->id) }}" method="POST" class="d-inline">
                                                @csrf
                                                @method('PUT')
                                                <input type="hidden" name="status" value="Tervalidasi">
                                                <button type="submit" class="btn btn-success btn-sm w-100" 
                                                        title="Validasi Prestasi"
                                                        onclick="return confirm('Validasi prestasi ini?')">
                                                    <i class="fas fa-check me-1"></i> Validasi
                                                </button>
                                            </form>
                                            <form action="{{ route('admin.prestasi.validasi', $item->id_prestasi ?? $item->id) }}" method="POST" class="d-inline">
                                                @csrf
                                                @method('PUT')
                                                <input type="hidden" name="status" value="Ditolak">
                                                <button type="submit" class="btn btn-danger btn-sm w-100" 
                                                        title="Tolak Prestasi"
                                                        onclick="return confirm('Tolak prestasi ini?')">
                                                    <i class="fas fa-times me-1"></i> Tolak
                                                </button>
                                            </form>
                                        </div>
                                        @elseif($item->status_validasi == 'disetujui')
                                        <div class="d-flex gap-1 mt-1">
                                            <form action="{{ route('admin.prestasi.validasi', $item->id_prestasi ?? $item->id) }}" method="POST" class="d-inline">
                                                @csrf
                                                @method('PUT')
                                                <input type="hidden" name="status" value="Menunggu Validasi">
                                                <button type="submit" class="btn btn-warning btn-sm w-100" 
                                                        title="Batalkan Validasi"
                                                        onclick="return confirm('Batalkan validasi prestasi ini?')">
                                                    <i class="fas fa-undo me-1"></i> Batal
                                                </button>
                                            </form>
                                        </div>
                                        @elseif($item->status_validasi == 'ditolak')
                                        <div class="d-flex gap-1 mt-1">
                                            <form action="{{ route('admin.prestasi.validasi', $item->id_prestasi ?? $item->id) }}" method="POST" class="d-inline">
                                                @csrf
                                                @method('PUT')
                                                <input type="hidden" name="status" value="Tervalidasi">
                                                <button type="submit" class="btn btn-success btn-sm w-100" 
                                                        title="Setujui Kembali"
                                                        onclick="return confirm('Setujui kembali prestasi ini?')">
                                                    <i class="fas fa-check me-1"></i> Setujui
                                                </button>
                                            </form>
                                        </div>
                                        @endif
                                    </div>
                                </td>
                                <td class="text-center pe-4">
                                    <div class="action-buttons d-flex justify-content-center">
                                        <!-- Button Detail -->
                                        <a href="{{ route('admin.prestasi.show', $item->id_prestasi ?? $item->id) }}" 
                                           class="btn btn-sm btn-outline-info mx-1" title="Lihat Detail">
                                            <i class="fas fa-eye"></i>
                                        </a>
                                        
                                        <!-- Button Edit -->
                                        <a href="{{ route('admin.prestasi.edit', $item->id_prestasi ?? $item->id) }}" 
                                           class="btn btn-sm btn-outline-primary mx-1" title="Edit">
                                            <i class="fas fa-edit"></i>
                                        </a>
                                        
                                        <!-- Button Hapus -->
                                        @if($item->status_validasi == 'disetujui' || $item->status_validasi == 'ditolak' || !$item->status_validasi)
                                        <button type="button" class="btn btn-sm btn-outline-danger mx-1 delete-btn" 
                                                title="Hapus Prestasi"
                                                data-id="{{ $item->id_prestasi ?? $item->id }}"
                                                data-name="{{ $item->nama_prestasi ?? $item->nama }}">
                                            <i class="fas fa-trash"></i>
                                        </button>
                                        @endif
                                    </div>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
                @endif
            </div>
        </div>
        
        <!-- Pagination -->
        @if($prestasi->hasPages())
        <div class="d-flex justify-content-between align-items-center mt-4">
            <div class="text-muted">
                Menampilkan {{ $prestasi->firstItem() }} - {{ $prestasi->lastItem() }} dari {{ $prestasi->total() }} prestasi
            </div>
            <nav>
                {{ $prestasi->links() }}
            </nav>
        </div>
        @endif
    </div>
</div>

<!-- Modal Konfirmasi Hapus -->
<div class="modal fade" id="deleteModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">
                    <i class="fas fa-trash text-danger me-2"></i>
                    Hapus Prestasi
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <p>Apakah Anda yakin ingin menghapus prestasi <strong id="deletePrestasiName"></strong>?</p>
                <p class="text-danger mb-0">
                    <i class="fas fa-exclamation-triangle me-1"></i>
                    Data yang dihapus tidak dapat dikembalikan
                </p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                <form id="deleteForm" method="POST">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="btn btn-danger">
                        <i class="fas fa-trash me-1"></i>Ya, Hapus
                    </button>
                </form>
            </div>
        </div>
    </div>
</div>

<style>
:root {
    --primary: #4361ee;
    --secondary: #3f37c9;
    --success: #4cc9f0;
    --gradient: linear-gradient(135deg, #4361ee 0%, #3a0ca3 100%);
    --shadow: 0 10px 20px rgba(0,0,0,0.1);
    --radius: 12px;
}

.hero-section {
    background: var(--gradient);
    color: white;
    padding: 2rem 0;
    border-radius: var(--radius);
    margin-bottom: 2rem;
}

.page-title {
    font-weight: 700;
    font-size: 1.75rem;
}

.admin-badges .badge {
    font-size: 0.8rem;
    padding: 0.5rem 1rem;
}

/* Admin Stat Cards */
.admin-stat-card {
    background: white;
    border-radius: var(--radius);
    padding: 1.5rem;
    box-shadow: var(--shadow);
    text-align: center;
    transition: all 0.3s ease;
    border: 1px solid #e1e5ee;
    height: 100%;
}

.admin-stat-card:hover {
    transform: translateY(-5px);
    box-shadow: 0 15px 30px rgba(0,0,0,0.15);
}

.admin-stat-card .stat-icon {
    width: 50px;
    height: 50px;
    border-radius: 10px;
    display: flex;
    align-items: center;
    justify-content: center;
    margin: 0 auto 1rem;
    color: white;
    font-size: 1.25rem;
}

.admin-stat-card .stat-number {
    font-size: 1.5rem;
    font-weight: 700;
    color: #2c3e50;
    line-height: 1;
    margin-bottom: 0.25rem;
}

.admin-stat-card .stat-label {
    font-size: 0.8rem;
    color: #6c757d;
    font-weight: 500;
}

/* Filter Section */
.filter-section {
    background: white;
    border-radius: var(--radius);
    padding: 1.5rem;
    box-shadow: var(--shadow);
    margin-bottom: 1.5rem;
    border: 1px solid #e1e5ee;
}

.filter-label {
    font-weight: 600;
    margin-bottom: 0.5rem;
    color: #495057;
    font-size: 0.9rem;
}

/* Card Styles */
.card {
    border: none;
    border-radius: var(--radius);
    box-shadow: var(--shadow);
}

.card-header {
    background: white !important;
    border-bottom: 1px solid #e1e5ee;
    padding: 1.25rem 1.5rem;
}

/* Table Styles */
.table th {
    border-top: none;
    font-weight: 600;
    color: #6c757d;
    font-size: 0.85rem;
    text-transform: uppercase;
    letter-spacing: 0.5px;
    padding: 1rem 0.75rem;
    border-bottom: 2px solid #e9ecef;
    background-color: #f8f9fa;
}

.table td {
    padding: 1rem 0.75rem;
    vertical-align: middle;
    border-bottom: 1px solid #e9ecef;
}

/* Badge Styles */
.badge {
    font-weight: 500;
    padding: 0.5rem 0.75rem;
    border-radius: 6px;
    font-size: 0.75rem;
}

.status-badge {
    font-size: 0.7rem;
    padding: 0.4rem 0.8rem;
}

/* Prestasi Item */
.prestasi-item {
    transition: all 0.3s ease;
}

.prestasi-item:hover {
    background-color: #f8f9ff;
}

/* Empty State */
.empty-state {
    padding: 4rem 1rem;
}

.empty-state i {
    font-size: 4rem;
    margin-bottom: 1.5rem;
    opacity: 0.7;
}

/* Action Buttons */
.action-buttons .btn {
    border-radius: 6px;
    margin: 0 2px;
    transition: all 0.3s ease;
    padding: 0.375rem 0.75rem;
    border-width: 2px;
}

.action-buttons .btn:hover {
    transform: translateY(-2px);
    box-shadow: 0 3px 8px rgba(0,0,0,0.15);
}

/* Button Styles */
.btn {
    border-radius: 8px;
    font-weight: 500;
    transition: all 0.3s ease;
}

.btn-primary {
    background: var(--gradient);
    border: none;
}

.btn-primary:hover {
    transform: translateY(-2px);
    box-shadow: 0 5px 15px rgba(67, 97, 238, 0.4);
}

/* Form Controls */
.form-control, .form-select {
    border-radius: 8px;
    padding: 0.75rem 1rem;
    border: 2px solid #e1e5ee;
    transition: all 0.3s ease;
}

.form-control:focus, .form-select:focus {
    border-color: var(--primary);
    box-shadow: 0 0 0 0.2rem rgba(67, 97, 238, 0.25);
}

/* Alert Styles */
.alert {
    border-radius: 8px;
    border: none;
    padding: 1rem 1.5rem;
}

/* Status Column Styles */
.table td:nth-child(8) {
    min-width: 10px;
}

/* Quick Action Buttons */
.btn-sm {
    padding: 0.25rem 0.5rem;
    font-size: 0.75rem;
}

.page-container {
    display: flex;
    align-items: flex-start;
    width: 100%;
    min-height: 10vh;
    overflow-x: hidden;
}
.table-responsive {
    overflow-x: auto;
    max-width: 100%;
}

.page-container {
    overflow-x: hidden;
    width: 100%; /* Ubah dari 10% ke 100% */
}
html, body {
    overflow-x: hidden;
}

.container-fluid, .container, .row, .content {
    max-width: 100%;
    overflow-x: hidden;
}

/* Responsive */
@media (max-width: 768px) {
    .hero-section {
        padding: 1.5rem;
    }
    
    .admin-stat-card {
        padding: 1rem;
    }
    
    .admin-stat-card .stat-icon {
        width: 40px;
        height: 40px;
        font-size: 1rem;
    }
    
    .admin-stat-card .stat-number {
        font-size: 1.25rem;
    }
    
    .filter-section {
        padding: 1rem;
    }
    
    .action-buttons {
        flex-direction: column;
        gap: 0.25rem;
    }
    
    .action-buttons .btn {
        margin: 0;
        width: 100%;
        justify-content: center;
    }
    
    .table td:nth-child(8) {
        min-width: 140px;
    }
}
</style>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Filter functionality
    const statusFilter = document.getElementById('statusFilter');
    const categoryFilter = document.getElementById('categoryFilter');
    const searchInput = document.getElementById('searchInput');
    const searchButton = document.getElementById('searchButton');
    
    function applyFilters() {
        const status = statusFilter.value;
        const category = categoryFilter.value;
        const search = searchInput.value;
        
        let url = '{{ route('admin.prestasi.index') }}?';
        const params = [];
        
        if (status !== 'all') {
            params.push(`status=${status}`);
        }
        
        if (category !== 'all') {
            params.push(`kategori=${category}`);
        }
        
        if (search) {
            params.push(`search=${encodeURIComponent(search)}`);
        }
        
        if (params.length > 0) {
            url += params.join('&');
        }
        
        window.location.href = url;
    }
    
    if (statusFilter) {
        statusFilter.addEventListener('change', applyFilters);
    }
    
    if (categoryFilter) {
        categoryFilter.addEventListener('change', applyFilters);
    }
    
    if (searchButton) {
        searchButton.addEventListener('click', applyFilters);
    }
    
    if (searchInput) {
        searchInput.addEventListener('keypress', function(e) {
            if (e.key === 'Enter') {
                applyFilters();
            }
        });
    }

    // Modal functionality for delete
    let deleteModal = null;
    const deleteModalEl = document.getElementById('deleteModal');
    
    if (typeof bootstrap !== 'undefined' && deleteModalEl) {
        deleteModal = new bootstrap.Modal(deleteModalEl);
    }

    // Delete buttons
    document.querySelectorAll('.delete-btn').forEach(button => {
        button.addEventListener('click', function() {
            const id = this.getAttribute('data-id');
            const name = this.getAttribute('data-name');
            
            const deletePrestasiName = document.getElementById('deletePrestasiName');
            const deleteForm = document.getElementById('deleteForm');
            
            if (deletePrestasiName) deletePrestasiName.textContent = name;
            if (deleteForm) deleteForm.action = `/admin/prestasi/${id}`;
            
            if (deleteModal) deleteModal.show();
        });
    });

    // Quick validation feedback
    document.querySelectorAll('form[action*="validasi"]').forEach(form => {
        form.addEventListener('submit', function(e) {
            const button = this.querySelector('button[type="submit"]');
            const originalText = button.innerHTML;
            
            // Show loading state
            button.innerHTML = '<i class="fas fa-spinner fa-spin me-1"></i>Loading...';
            button.disabled = true;
            
            // Re-enable after 3 seconds if still processing
            setTimeout(() => {
                button.innerHTML = originalText;
                button.disabled = false;
            }, 3000);
        });
    });
});
</script>

@endsection