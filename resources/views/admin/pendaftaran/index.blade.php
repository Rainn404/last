@php $layout = request()->is('admin-panel/*') ? 'layouts.admin-panel.app' : 'layouts.admin.app'; @endphp
@extends($layout)

@section('title', 'Kelola Pendaftaran - HIMA Sistem Manajemen')

@section('content')

<div class="container-fluid">
    <script>
        // Ensure clicks using inline onclick won't fail if real function loads later.
        if (typeof window.openChangeStatus !== 'function') {
            window.openChangeStatus = function(id, status) {
                // Try to defer to real implementation when available
                if (typeof window._openChangeStatus === 'function') {
                    return window._openChangeStatus(id, status);
                }
                console.warn('openChangeStatus called before implementation is ready; deferring...');
                const tryInvoke = () => {
                    if (typeof window._openChangeStatus === 'function') {
                        window._openChangeStatus(id, status);
                    } else {
                        // final fallback: show modal error
                        alert('Fungsi belum siap. Silakan tunggu beberapa saat lalu coba lagi.');
                    }
                };
                // attempt after short delay to allow other scripts to initialize
                setTimeout(tryInvoke, 300);
            };
        }
    </script>
    <!-- Header Section -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="page-header">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <h1 class="page-title">
                            <i class="fas fa-users me-2"></i>Kelola Pendaftaran
                        </h1>
                        <p class="page-subtitle">Validasi dan kelola pendaftaran anggota baru HIMA TI keren banget</p>
                    </div>
                    <div class="status-badge">
                        <span class="badge {{ $settings->pendaftaran_aktif ? 'bg-success' : 'bg-danger' }}">
                            <i class="fas {{ $settings->pendaftaran_aktif ? 'fa-play' : 'fa-stop' }} me-1"></i>
                            Pendaftaran {{ $settings->pendaftaran_aktif ? 'Aktif' : 'Ditutup' }}
                        </span>
                    </div>
                </div>
            </div>
        </div>
    </div>

                <!-- Modal Change Status -->
    <!-- Pengaturan Periode dan Kontrol -->
    <div class="card mb-4">
        <div class="card-header bg-primary text-white">
            <h5 class="mb-0"><i class="fas fa-calendar-alt me-2"></i>Pengaturan Periode Pendaftaran</h5>
        </div>
        <div class="card-body">
            <form id="periodeForm" action="{{ route('admin.pendaftaran.update-settings') }}" method="POST">
                @csrf
                <div class="row">
                    <div class="col-md-4">
                        <label for="tanggal_mulai" class="form-label">Tanggal Mulai Pendaftaran</label>
                           <input type="date" class="form-control" id="tanggal_mulai" name="tanggal_mulai" 
                               value="{{ \Carbon\Carbon::parse($settings->tanggal_mulai)->format('Y-m-d') }}" required>
                    </div>
                    <div class="col-md-4">
                        <label for="tanggal_selesai" class="form-label">Tanggal Selesai Pendaftaran</label>
                           <input type="date" class="form-control" id="tanggal_selesai" name="tanggal_selesai" 
                               value="{{ \Carbon\Carbon::parse($settings->tanggal_selesai)->format('Y-m-d') }}" required>
                    </div>
                    <div class="col-md-2">
                        <label for="kuota" class="form-label">Kuota Penerimaan</label>
                        <input type="number" class="form-control" id="kuota" name="kuota" 
                               value="{{ $settings->kuota }}" min="1" required>
                    </div>
                    <div class="col-md-2">
                        <label class="form-label">Aksi</label>
                        <div class="d-grid">
                            @if($settings->pendaftaran_aktif)
                                <button type="button" class="btn btn-danger" onclick="tutupSesiPendaftaran(event)">
                                    <i class="fas fa-stop me-2"></i>Tutup Pendaftaran
                                </button>
                            @else
                                <button type="button" class="btn btn-success" onclick="simpanDanBukaPendaftaran(event)">
                                    <i class="fas fa-play me-2"></i>Simpan & Buka Pendaftaran
                                </button>
                            @endif
                        </div>
                    </div>
                </div>
                <div class="row mt-3">
                    <div class="col-md-6">
                        <div class="form-check form-switch">
                            <input class="form-check-input" type="checkbox" id="auto_close" name="auto_close" 
                                   value="1" {{ $settings->auto_close ? 'checked' : '' }}>
                            <label class="form-check-label" for="auto_close">
                                Tutup otomatis ketika periode berakhir
                            </label>
                        </div>
                    </div>
                </div>
                @if($settings->pendaftaran_aktif)
                <div class="row mt-3">
                    <div class="col-12">
                        <div class="alert alert-info mb-0">
                            <div class="d-flex align-items-center">
                                <i class="fas fa-info-circle me-2"></i>
                                <div>
                                    <strong>Periode Aktif:</strong> 
                                    {{ \Carbon\Carbon::parse($settings->tanggal_mulai)->format('d M Y') }} - 
                                    {{ \Carbon\Carbon::parse($settings->tanggal_selesai)->format('d M Y') }}
                                    @if(now()->between($settings->tanggal_mulai, $settings->tanggal_selesai))
                                        <span class="badge bg-success ms-2">Sedang Berlangsung</span>
                                    @elseif(now()->gt($settings->tanggal_selesai))
                                        <span class="badge bg-danger ms-2">Telah Berakhir</span>
                                    @else
                                        <span class="badge bg-warning ms-2">Akan Datang</span>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                @endif
            </form>
        </div>
    </div>

    <!-- Quick Stats -->
    <div class="row mb-4">
        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card stat-card total-card">
                <div class="card-body">
                    <div class="d-flex align-items-center">
                        <div class="flex-grow-1">
                            <h4 class="stat-number">{{ $stats['totalPendaftaran'] }}</h4>
                            <p class="stat-label">Total Pendaftar</p>
                        </div>
                        <div class="stat-icon">
                            <i class="fas fa-users"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card stat-card pending-card">
                <div class="card-body">
                    <div class="d-flex align-items-center">
                        <div class="flex-grow-1">
                            <h4 class="stat-number">{{ $stats['pendingCount'] }}</h4>
                            <p class="stat-label">Menunggu Validasi</p>
                        </div>
                        <div class="stat-icon">
                            <i class="fas fa-clock"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card stat-card accepted-card">
                <div class="card-body">
                    <div class="d-flex align-items-center">
                        <div class="flex-grow-1">
                            <h4 class="stat-number">{{ $stats['diterimaCount'] }}</h4>
                            <p class="stat-label">Diterima</p>
                        </div>
                        <div class="stat-icon">
                            <i class="fas fa-check-circle"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card stat-card rejected-card">
                <div class="card-body">
                    <div class="d-flex align-items-center">
                        <div class="flex-grow-1">
                            <h4 class="stat-number">{{ $stats['ditolakCount'] }}</h4>
                            <p class="stat-label">Ditolak</p>
                        </div>
                        <div class="stat-icon">
                            <i class="fas fa-times-circle"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Informasi Kuota -->
    <div class="row mb-4">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">
                    <h5 class="card-title mb-0">
                        <i class="fas fa-info-circle me-2"></i>Informasi Kuota Penerimaan
                    </h5>
                </div>
                <div class="card-body">
                    <div class="kuota-progress">
                        @php
                            $kuota = $settings->kuota ?? 1;
                            $terisi = $stats['diterimaCount'];
                            $persentase = min(100, ($terisi / $kuota) * 100);
                            $sisa = max(0, $kuota - $terisi);
                        @endphp
                        <div class="d-flex justify-content-between mb-2">
                            <span class="fw-semibold">Progress Penerimaan</span>
                            <span class="text-muted">{{ $terisi }} / {{ $kuota }} ({{ $persentase }}%)</span>
                        </div>
                        <div class="progress" style="height: 20px;">
                            <div class="progress-bar bg-success" role="progressbar" 
                                 style="width: {{ $persentase }}%"
                                 aria-valuenow="{{ $persentase }}" 
                                 aria-valuemin="0" 
                                 aria-valuemax="100">
                                {{ $persentase }}%
                            </div>
                        </div>
                        <div class="mt-2">
                            <small class="text-muted">
                                <i class="fas fa-info-circle me-1"></i>
                                Sisa kuota: <strong>{{ $sisa }}</strong> penerimaan
                            </small>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card h-100">
                <div class="card-header">
                    <h5 class="card-title mb-0">
                        <i class="fas fa-chart-bar me-2"></i>Statistik Cepat
                    </h5>
                </div>
                <div class="card-body">
                    <div class="quick-stats">
                        <div class="stat-item d-flex justify-content-between align-items-center mb-2">
                            <span>Rata-rata Pendaftar/Hari</span>
                            <span class="badge bg-primary">{{ number_format($stats['totalPendaftaran'] / max(1, \Carbon\Carbon::parse($settings->tanggal_mulai)->diffInDays(now())), 1) }}</span>
                        </div>
                        <div class="stat-item d-flex justify-content-between align-items-center mb-2">
                            <span>Tingkat Penerimaan</span>
                            <span class="badge bg-info">{{ $stats['totalPendaftaran'] > 0 ? number_format(($stats['diterimaCount'] / $stats['totalPendaftaran']) * 100, 1) : 0 }}%</span>
                        </div>
                        <div class="stat-item d-flex justify-content-between align-items-center">
                            <span>Sisa Hari</span>
                            <span class="badge bg-warning">{{ max(0, \Carbon\Carbon::parse($settings->tanggal_selesai)->diffInDays(now())) }} Hari</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Filter dan Pencarian -->
    <div class="card filter-card mb-4">
        <div class="card-body">
            <div class="row g-3">
                <div class="col-md-8">
                    <div class="filter-tabs">
                        <div class="nav nav-pills" role="tablist">
                            <button class="nav-link active" onclick="filterPendaftaran('all')">
                                Semua <span class="badge bg-primary ms-1">{{ $stats['totalPendaftaran'] }}</span>
                            </button>
                            <button class="nav-link" onclick="filterPendaftaran('pending')">
                                <i class="fas fa-clock me-1"></i>Pending 
                                <span class="badge bg-warning ms-1">{{ $stats['pendingCount'] }}</span>
                            </button>
                            <button class="nav-link" onclick="filterPendaftaran('interview')">
                                <i class="fas fa-comments me-1"></i>Interview 
                                <span class="badge bg-info ms-1">{{ $stats['interviewCount'] }}</span>
                            </button>
                            <button class="nav-link" onclick="filterPendaftaran('diterima')">
                                <i class="fas fa-check me-1"></i>Diterima 
                                <span class="badge bg-success ms-1">{{ $stats['diterimaCount'] }}</span>
                            </button>
                            <button class="nav-link" onclick="filterPendaftaran('ditolak')">
                                <i class="fas fa-times me-1"></i>Ditolak 
                                <span class="badge bg-danger ms-1">{{ $stats['ditolakCount'] }}</span>
                            </button>
                        </div>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="search-box">
                        <div class="input-group">
                            <span class="input-group-text">
                                <i class="fas fa-search"></i>
                            </span>
                            <input type="text" class="form-control" placeholder="Cari nama atau NIM..." id="searchInput" value="{{ request('search') }}">
                            <button class="btn btn-outline-secondary" type="button" onclick="searchPendaftaran()">
                                Cari
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Bulk Action Buttons (hanya untuk pending) -->
    <div id="bulkActionSection" class="card mb-4" style="display: none;">
        <div class="card-body">
            <div class="d-flex align-items-center gap-2">
                <i class="fas fa-info-circle text-info"></i>
                <span class="text-muted">Filter sedang menampilkan data <strong>Pending</strong></span>
            </div>
            <div class="mt-3">
                <button class="btn btn-info" onclick="showBulkInterviewModal()">
                    <i class="fas fa-calendar-check me-2"></i>Jadwalkan Interview Massal
                </button>
            </div>
        </div>
    </div>

    <!-- Bulk Action Buttons (hanya untuk interview) -->
    <div id="bulkAcceptSection" class="card mb-4" style="display: none;">
        <div class="card-body">
            <div class="d-flex align-items-center gap-2">
                <i class="fas fa-info-circle text-success"></i>
                <span class="text-muted">Filter sedang menampilkan data <strong>Interview</strong></span>
            </div>
            <div class="mt-3">
                <button class="btn btn-success" onclick="showBulkAcceptModal()">
                    <i class="fas fa-user-check me-2"></i>Terima Massal
                </button>
            </div>
        </div>
    </div>

    <!-- Alert Notifikasi -->
    @if(session('success'))
    <div class="alert alert-success alert-dismissible fade show">
        <div class="d-flex align-items-center">
            <i class="fas fa-check-circle me-2"></i>
            <div>{{ session('success') }}</div>
        </div>
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
    @endif

    @if(session('error'))
    <div class="alert alert-danger alert-dismissible fade show">
        <div class="d-flex align-items-center">
            <i class="fas fa-exclamation-circle me-2"></i>
            <div>{{ session('error') }}</div>
        </div>
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
    @endif

    <!-- Tabel Pendaftaran -->
    <div class="card main-card">
        <div class="card-header">
            <h5 class="card-title mb-0">
                <i class="fas fa-list me-2"></i>Data Pendaftaran
            </h5>
        </div>
        <div class="card-body p-0">
            <div class="table-responsive" data-table-content>
                <table class="table table-hover table-sm mb-0" id="pendaftaranTable">
                    <thead class="table-light">
                        <tr>
                            <th width="40">#</th>
                            <th>Pendaftar</th>
                            <th>NIM</th>
                            <th>Divisi</th>
                            <th>Semester</th>
                            <th>Kontak</th>
                            <th>Status</th>
                            <th width="120" class="text-center">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($pendaftaran as $index => $item)
                        <tr class="pendaftaran-row" data-status="{{ $item->status_pendaftaran }}" data-search="{{ strtolower($item->nama . ' ' . $item->nim) }}">
                            <td class="ps-3">{{ ($pendaftaran->currentPage() - 1) * $pendaftaran->perPage() + $loop->iteration }}</td>
                            <td>
                                <div class="d-flex align-items-center" style="max-width: 200px;">
                                    <div class="avatar bg-primary rounded-circle d-flex align-items-center justify-content-center me-2" style="min-width: 32px; min-height: 32px;">
                                        <i class="fas fa-user text-white" style="font-size: 12px;"></i>
                                    </div>
                                    <div style="overflow: hidden;">
                                        <strong class="d-block truncate" title="{{ $item->nama }}" style="font-size: 14px;">{{ Str::limit($item->nama, 20) }}</strong>
                                        <small class="text-muted truncate" title="{{ $item->created_at->format('d M Y H:i') }}" style="display: block; font-size: 11px;">{{ $item->created_at->format('d M Y') }}</small>
                                    </div>
                                </div>
                            </td>
                            <td>
                                <code class="nim-code" style="font-size: 12px;">{{ $item->nim }}</code>
                            </td>
                            <td>
                                @if($item->divisi)
                                    <span class="badge bg-info" title="{{ $item->divisi->nama_divisi }}">
                                        {{ Str::limit($item->divisi->nama_divisi, 12) }}
                                    </span>
                                @else
                                    <span class="text-muted" style="font-size: 12px;">-</span>
                                @endif
                            </td>
                            <td>
                                <span class="badge semester-badge" style="font-size: 11px;">S{{ $item->semester }}</span>
                            </td>
                            <td>
                                <div class="contact-info" style="font-size: 12px;">
                                    <div class="phone">
                                        <i class="fas fa-phone me-1" style="font-size: 10px;"></i> 
                                        <small class="truncate" title="{{ $item->no_hp ?? '-' }}" style="display: block; max-width: 100px;">{{ Str::limit($item->no_hp ?? '-', 12) }}</small>
                                    </div>
                                </div>
                            </td>
                            <td>
                                @php
                                    $status = $item->status_pendaftaran;
                                    $badgeClass = 'badge bg-secondary';
                                    $icon = 'fa-circle';
                                    if ($status === 'submitted') { $badgeClass = 'badge bg-secondary'; $icon = 'fa-circle'; }
                                    elseif ($status === 'verifying') { $badgeClass = 'badge bg-primary'; $icon = 'fa-spinner'; }
                                    elseif ($status === 'interview') { $badgeClass = 'badge bg-warning text-dark'; $icon = 'fa-comments'; }
                                    elseif ($status === 'diterima') { $badgeClass = 'badge bg-success'; $icon = 'fa-check'; }
                                    elseif ($status === 'ditolak') { $badgeClass = 'badge bg-danger'; $icon = 'fa-times'; }
                                @endphp
                                <span class="status-badge {{ $badgeClass }}" style="font-size: 11px;">
                                    <i class="fas {{ $icon }} me-1"></i>
                                    {{ ucfirst($status) }}
                                </span>
                            </td>
                            <td class="text-center pe-2">
                                <div class="action-buttons">
                                    <button class="btn btn-sm btn-outline-info" 
                                        onclick="viewDetail({{ $item->id_pendaftaran }})"
                                        data-bs-toggle="tooltip" title="Detail"
                                        style="padding: 4px 8px; font-size: 12px;">
                                        <i class="fas fa-eye"></i>
                                    </button>
                                    <button class="btn btn-sm btn-outline-warning" 
                                            onclick="openChangeStatus({{ $item->id_pendaftaran }}, '{{ $item->status_pendaftaran }}')"
                                            data-bs-toggle="tooltip" title="Ubah Status"
                                            style="padding: 4px 8px; font-size: 12px;">
                                        <i class="fas fa-exchange-alt"></i>
                                    </button>
                                    <form action="{{ route('admin.pendaftaran.destroy', ['pendaftaran' => $item->id_pendaftaran]) }}" method="POST" class="d-inline">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-sm btn-outline-danger" 
                                                data-bs-toggle="tooltip" title="Hapus"
                                                onclick="return confirm('Yakin hapus?')"
                                                style="padding: 4px 8px; font-size: 12px;">
                                            <i class="fas fa-trash"></i>
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="8" class="text-center py-5">
                                <div class="empty-state">
                                    <i class="fas fa-clipboard-list fa-3x text-muted mb-3"></i>
                                    <h5 class="text-muted">Belum ada data pendaftaran</h5>
                                    <p class="text-muted">Tidak ada pendaftaran yang ditemukan</p>
                                </div>
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            
            <!-- Pagination inside card body -->
            @if($pendaftaran->hasPages())
            <div class="d-flex justify-content-between align-items-center p-3" style="border-top: 1px solid #dee2e6; gap: 1rem;">
                <div class="text-muted small">
                    Menampilkan {{ $pendaftaran->firstItem() }} - {{ $pendaftaran->lastItem() }} dari {{ $pendaftaran->total() }} data
                </div>
                <nav aria-label="Page navigation" style="margin: 0;">
                    <ul class="pagination pagination-sm mb-0" style="gap: 2px;">
                        @if ($pendaftaran->onFirstPage())
                            <li class="page-item disabled"><span class="page-link" style="padding: 0.25rem 0.5rem; font-size: 0.75rem;">« Sebelumnya</span></li>
                        @else
                            <li class="page-item"><a class="page-link pagination-link" href="{{ $pendaftaran->previousPageUrl() }}" style="padding: 0.25rem 0.5rem; font-size: 0.75rem;">« Sebelumnya</a></li>
                        @endif

                        @foreach ($pendaftaran->getUrlRange(max(1, $pendaftaran->currentPage() - 2), min($pendaftaran->lastPage(), $pendaftaran->currentPage() + 2)) as $page => $url)
                            @if ($page == $pendaftaran->currentPage())
                                <li class="page-item active"><span class="page-link" style="padding: 0.25rem 0.5rem; font-size: 0.75rem;">{{ $page }}</span></li>
                            @else
                                <li class="page-item"><a class="page-link pagination-link" href="{{ $url }}" style="padding: 0.25rem 0.5rem; font-size: 0.75rem;">{{ $page }}</a></li>
                            @endif
                        @endforeach

                        @if ($pendaftaran->hasMorePages())
                            <li class="page-item"><a class="page-link pagination-link" href="{{ $pendaftaran->nextPageUrl() }}" style="padding: 0.25rem 0.5rem; font-size: 0.75rem;">Selanjutnya »</a></li>
                        @else
                            <li class="page-item disabled"><span class="page-link" style="padding: 0.25rem 0.5rem; font-size: 0.75rem;">Selanjutnya »</span></li>
                        @endif
                    </ul>
                </nav>
            </div>
            @endif
        </div>
    </div>
</div>

<!-- Modal Detail Pendaftaran -->
<div class="modal fade" id="detailModal" tabindex="-1">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">
                    <i class="fas fa-user me-2"></i>Detail Pendaftaran
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body" id="detailContent">
                <!-- Content will be loaded by JavaScript -->
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Tutup</button>
            </div>
        </div>
    </div>
</div>

<!-- Modal Konfirmasi Status -->
<div class="modal fade" id="statusModal" tabindex="-1">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header" style="background: linear-gradient(135deg, #4361ee 0%, #3a0ca3 100%); color: white; border: none;">
                <h5 class="modal-title" id="statusTitle" style="color: white;">
                    <i class="fas fa-exchange-alt me-2"></i>Ubah Status Pendaftaran
                </h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <form id="statusForm" method="POST">
                @csrf
                @method('PUT')
                <div class="modal-body" style="padding: 2rem;">
                    <input type="hidden" id="pendaftaranId" name="id_pendaftaran">
                    
                    <div style="background: #f8f9fa; padding: 1rem; border-radius: 8px; margin-bottom: 1.5rem;">
                        <p class="mb-2"><strong>Pendaftar:</strong> <span id="userDisplay" class="text-primary">-</span></p>
                        <p class="mb-0"><strong>Tanggal Daftar:</strong> <span id="dateDisplay" class="text-muted">-</span></p>
                    </div>

                    <div class="mb-3">
                        <label for="status_select" class="form-label fw-600" style="font-weight: 600;">Pilih Status <span class="text-danger">*</span></label>
                        <select id="status_select" name="status_pendaftaran" class="form-select form-select-lg" required onchange="updateStatusContent()">
                            <option value="">-- Pilih Status --</option>
                            <option value="interview">💬 Interview</option>
                            <option value="diterima">✅ Terima</option>
                            <option value="ditolak">❌ Tolak</option>
                        </select>
                    </div>

                    <div id="diterimaContent" style="display: none;">
                        <div class="alert alert-info mb-3" style="border-left: 4px solid #4361ee;">
                            <i class="fas fa-info-circle me-2"></i>
                            <strong>Pilih divisi dan jabatan untuk penerimaan</strong>
                        </div>
                        <div class="mb-3">
                            <label for="id_divisi" class="form-label fw-600">Divisi <span class="text-danger">*</span></label>
                            <select class="form-select" id="id_divisi" name="id_divisi" onchange="filterJabatanByDivisi(this.value)">
                                <option value="">Pilih Divisi</option>
                                @foreach($divisi as $div)
                                <option value="{{ $div->id_divisi }}">{{ $div->nama_divisi }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="mb-3">
                            <label for="id_jabatan" class="form-label fw-600">Jabatan <span class="text-danger">*</span></label>
                            <select class="form-select" id="id_jabatan" name="id_jabatan">
                                <option value="">Pilih Jabatan</option>
                                @foreach($jabatan as $jab)
                                <option value="{{ $jab->id_jabatan }}" data-divisi="{{ $jab->id_divisi }}">{{ $jab->nama_jabatan }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    
                    <div id="ditolakContent" style="display: none;">
                        <div class="alert alert-warning mb-3" style="border-left: 4px solid #f8961e;">
                            <i class="fas fa-exclamation-triangle me-2"></i>
                            <strong>Berikan alasan penolakan (opsional)</strong>
                        </div>
                        <div class="mb-3">
                            <label for="notes" class="form-label fw-600">Alasan Penolakan</label>
                            <textarea class="form-control" id="notes" name="notes" rows="4" placeholder="Tuliskan alasan penolakan (opsional)..."></textarea>
                        </div>
                    </div>

                    <div id="interviewContent" style="display: none;">
                        <div class="alert alert-primary mb-3" style="border-left: 4px solid #4361ee;">
                            <i class="fas fa-calendar-alt me-2"></i>
                            <strong>Jadwalkan interview</strong>
                        </div>
                        <div class="mb-3">
                            <label for="interview_date" class="form-label fw-600">Tanggal Interview <span class="text-danger">*</span></label>
                            <input type="date" class="form-control" id="interview_date" name="interview_date">
                            <small class="text-muted d-block mt-2">
                                <i class="fas fa-info-circle me-1"></i>Pilih tanggal interview (minimal hari ini atau setelahnya)
                            </small>
                        </div>
                    </div>
                </div>
                <div class="modal-footer" style="border-top: 1px solid #e9ecef;">
                    <button type="button" class="btn btn-light" data-bs-dismiss="modal">
                        <i class="fas fa-times me-1"></i>Batal
                    </button>
                    <button type="submit" class="btn btn-primary" id="submitButton">
                        <i class="fas fa-check me-1"></i><span id="submitText">Simpan</span>
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Modal Edit Pendaftaran -->
<div class="modal fade" id="editModal" tabindex="-1">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Edit Data Pendaftaran</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form id="editForm" method="POST">
                @csrf
                @method('PUT')
                <div class="modal-body" id="editContent">
                    <!-- Content will be loaded by JavaScript -->
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-primary">Simpan Perubahan</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Modal Bulk Interview -->
<div class="modal fade" id="bulkInterviewModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header" style="background: linear-gradient(135deg, #4361ee 0%, #3a0ca3 100%); color: white; border: none;">
                <h5 class="modal-title">
                    <i class="fas fa-calendar-check me-2"></i>Jadwalkan Interview Massal
                </h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <form id="bulkInterviewForm" method="POST">
                @csrf
                <div class="modal-body" style="padding: 2rem;">
                    <div class="alert alert-info mb-3">
                        <i class="fas fa-info-circle me-2"></i>
                        Jadwalkan interview untuk <strong id="pendingCount">0</strong> pendaftar dengan status Pending
                    </div>
                    
                    <div class="mb-3">
                        <label for="bulkInterviewDate" class="form-label fw-600">Tanggal Interview <span class="text-danger">*</span></label>
                        <input type="date" class="form-control form-control-lg" id="bulkInterviewDate" required>
                        <small class="text-muted d-block mt-2">
                            <i class="fas fa-info-circle me-1"></i>Semua pendaftar akan dijadwalkan pada tanggal ini
                        </small>
                    </div>
                </div>
                <div class="modal-footer" style="border-top: 1px solid #e9ecef;">
                    <button type="button" class="btn btn-light" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-primary">
                        <i class="fas fa-check me-2"></i>Jadwalkan Semua
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Modal Bulk Accept -->
<div class="modal fade" id="bulkAcceptModal" tabindex="-1">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header" style="background: linear-gradient(135deg, #10b981 0%, #059669 100%); color: white; border: none;">
                <h5 class="modal-title">
                    <i class="fas fa-user-check me-2"></i>Terima Massal
                </h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <form id="bulkAcceptForm" method="POST">
                @csrf
                <div class="modal-body" style="padding: 2rem;">
                    <div class="alert alert-success mb-3">
                        <i class="fas fa-check-circle me-2"></i>
                        Terima <strong id="interviewCount">0</strong> pendaftar dengan status Interview
                    </div>
                    
                    <div class="alert alert-info mb-3">
                        <i class="fas fa-info-circle me-2"></i>
                        <strong>Divisi</strong> akan otomatis sesuai divisi pilihan masing-masing pendaftar saat mendaftar
                    </div>
                    
                    <div class="mb-3">
                        <label for="bulkAcceptJabatan" class="form-label fw-600">Jabatan <span class="text-danger">*</span></label>
                        <select class="form-select form-select-lg" id="bulkAcceptJabatan" required>
                            <option value="">-- Pilih Jabatan --</option>
                            @foreach($jabatan as $jab)
                            <option value="{{ $jab->id_jabatan }}">{{ $jab->nama_jabatan }}</option>
                            @endforeach
                        </select>
                        <small class="text-muted d-block mt-2">
                            <i class="fas fa-info-circle me-1"></i>Semua pendaftar akan diterima dengan jabatan yang sama
                        </small>
                    </div>
                </div>
                <div class="modal-footer" style="border-top: 1px solid #e9ecef;">
                    <button type="button" class="btn btn-light" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-success">
                        <i class="fas fa-check me-2"></i>Terima Semua
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

@endsection

@push('styles')
<style>
/* Custom Styles */
:root {
    --primary: #4361ee;
    --secondary: #3f37c9;
    --success: #4cc9f0;
    --warning: #f8961e;
    --danger: #f94144;
    --light: #f8f9fa;
    --dark: #212529;
    --gradient: linear-gradient(135deg, #4361ee 0%, #3a0ca3 100%);
    --shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
    --radius: 12px;
}

/* Header Styles */
.page-header {
    background: white;
    padding: 1.5rem;
    border-radius: var(--radius);
    box-shadow: var(--shadow);
}

.page-title {
    font-weight: 700;
    color: var(--dark);
    margin-bottom: 0.5rem;
    font-size: 1.75rem;
}

.page-subtitle {
    color: #6c757d;
    margin-bottom: 0;
}

/* Stat Cards */
.stat-card {
    border: none;
    border-radius: var(--radius);
    box-shadow: var(--shadow);
    transition: all 0.3s ease;
    height: 100%;
}

.stat-card:hover {
    transform: translateY(-5px);
    box-shadow: 0 8px 15px rgba(0, 0, 0, 0.15);
}

.total-card { background: linear-gradient(135deg, #4361ee 0%, #3a0ca3 100%); color: white; }
.pending-card { background: linear-gradient(135deg, #f8961e 0%, #f3722c 100%); color: white; }
.accepted-card { background: linear-gradient(135deg, #4cc9f0 0%, #4895ef 100%); color: white; }
.rejected-card { background: linear-gradient(135deg, #f94144 0%, #f3722c 100%); color: white; }

.stat-number {
    font-size: 2rem;
    font-weight: 700;
    margin-bottom: 0.25rem;
}

.stat-label {
    font-size: 0.9rem;
    opacity: 0.9;
    margin-bottom: 0;
}

.stat-icon {
    font-size: 2.5rem;
    opacity: 0.7;
}

/* Progress Bar */
.kuota-progress .progress {
    border-radius: 10px;
}

.quick-stats .stat-item {
    padding: 0.5rem 0;
    border-bottom: 1px solid #e9ecef;
}

.quick-stats .stat-item:last-child {
    border-bottom: none;
}

/* Filter Tabs */
.filter-tabs .nav-pills .nav-link {
    border-radius: 8px;
    margin-right: 0.5rem;
    margin-bottom: 0.5rem;
    padding: 0.5rem 1rem;
    border: 1px solid #dee2e6;
    background: white;
    color: var(--dark);
    transition: all 0.3s ease;
    cursor: pointer;
    font-weight: 500;
}

.filter-tabs .nav-pills .nav-link.active {
    background: var(--primary);
    border-color: var(--primary);
    color: white;
    box-shadow: 0 4px 12px rgba(67, 97, 238, 0.4);
    transform: translateY(-2px);
}

.filter-tabs .nav-pills .nav-link:hover {
    border-color: var(--primary);
    box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1);
}

.filter-tabs .nav-pills .nav-link:hover:not(.active) {
    background: #f8f9fa;
    color: var(--primary);
}

/* Search Box */
.search-box .input-group {
    border-radius: 8px;
    overflow: hidden;
}

.search-box .input-group-text {
    background: white;
    border-right: none;
}

.search-box .form-control {
    border-left: none;
    border-right: none;
}

.search-box .btn {
    border-radius: 0 8px 8px 0;
}

/* Table Styles */
.main-card .table {
    margin-bottom: 0;
}

.main-card .table th {
    border-top: none;
    font-weight: 600;
    color: #6c757d;
    font-size: 0.85rem;
    text-transform: uppercase;
    letter-spacing: 0.5px;
    padding: 1rem 0.75rem;
    border-bottom: 2px solid #e9ecef;
}

.main-card .table td {
    padding: 1rem 0.75rem;
    vertical-align: middle;
    border-bottom: 1px solid #e9ecef;
}

.pendaftaran-row:hover {
    background-color: #f8f9ff;
}

/* Avatar */
.avatar {
    width: 40px;
    height: 40px;
    font-size: 1rem;
}

/* Badges */
.status-badge, .semester-badge {
    font-size: 0.75rem;
    padding: 0.4rem 0.8rem;
    border-radius: 6px;
    font-weight: 500;
}

.semester-badge {
    background: #6c757d;
    color: white;
}

.nim-code {
    background: #f8f9fa;
    padding: 0.25rem 0.5rem;
    border-radius: 4px;
    font-family: 'Courier New', monospace;
    font-size: 0.85rem;
}

/* Pagination Minimal */
.pagination-sm .page-link {
    padding: 0.25rem 0.5rem;
    font-size: 0.75rem;
    border-radius: 3px;
}

.pagination-sm .page-item.active .page-link {
    background-color: #4361ee;
    border-color: #4361ee;
}

/* Utility truncate */
.truncate {
    display: inline-block;
    max-width: 100%;
    white-space: nowrap;
    overflow: hidden;
    text-overflow: ellipsis;
}

/* Contact Info */
.contact-info .phone, .contact-info .email {
    margin-bottom: 0.25rem;
}

.contact-info small {
    font-size: 0.8rem;
}

/* Action Buttons */
.action-buttons {
    display: flex;
    gap: 0.25rem;
    justify-content: center;
}

.action-btn {
    border-radius: 6px;
    padding: 0.375rem 0.5rem;
    transition: all 0.3s ease;
    border-width: 2px;
}

.action-btn:hover {
    transform: translateY(-2px);
    box-shadow: 0 3px 8px rgba(0,0,0,0.15);
}

/* Doc Button */
.doc-btn {
    border-radius: 6px;
    padding: 0.375rem 0.5rem;
}

/* Empty State */
.empty-state {
    padding: 2rem;
}

.empty-state i {
    opacity: 0.5;
}

/* Alert Styles */
.alert {
    border-radius: 8px;
    border: none;
    padding: 1rem 1.5rem;
}

/* Modal Styles */
.modal-header {
    border-bottom: 1px solid #e9ecef;
    padding: 1.25rem 1.5rem;
}

.modal-footer {
    border-top: 1px solid #e9ecef;
    padding: 1rem 1.5rem;
}

/* Responsive */
@media (max-width: 768px) {
    .page-title {
        font-size: 1.5rem;
    }
    
    .stat-number {
        font-size: 1.5rem;
    }
    
    .stat-icon {
        font-size: 2rem;
    }
    
    .filter-tabs .nav-pills .nav-link {
        font-size: 0.8rem;
        padding: 0.4rem 0.75rem;
    }
    
    .action-buttons {
        flex-direction: column;
        gap: 0.125rem;
    }
    
    .action-btn {
        width: 100%;
        justify-content: center;
    }
    
    .table-responsive {
        font-size: 0.85rem;
    }

    /* Fix pagination area spacing and ensure table doesn't create extra large bottom gap */
    .main-card {
        padding-bottom: 0.5rem;
    }
    .main-card .table-responsive {
        overflow-x: auto;
        overflow-y: visible;
    }
    .pagination {
        justify-content: flex-end !important;
    }
}

@media (max-width: 576px) {
    .page-header {
        padding: 1rem;
    }
    
    .stat-card .card-body {
        padding: 1rem;
    }
}
</style>
@endpush

@push('scripts')
<script>
// ============================================
// CORE FUNCTIONS - Load First
// ============================================

// Open Change Status Modal
// Real implementation assigned to _openChangeStatus so proxy can defer safely
window._openChangeStatus = function(id, currentStatus) {
    // Reset form fields
    const pendaftaranIdEl = document.getElementById('pendaftaranId');
    const statusSelectEl = document.getElementById('status_select');
    const alasanPenolakanEl = document.getElementById('notes');
    const pesanDiterimaEl = document.getElementById('pesan_diterima');
    const idDivisiEl = document.getElementById('id_divisi');
    const idJabatanEl = document.getElementById('id_jabatan');
    
    if (pendaftaranIdEl) pendaftaranIdEl.value = id;
    if (statusSelectEl) statusSelectEl.value = '';
    if (alasanPenolakanEl) alasanPenolakanEl.value = '';
    if (pesanDiterimaEl) pesanDiterimaEl.value = '';
    if (idDivisiEl) idDivisiEl.value = '';
    if (idJabatanEl) idJabatanEl.value = '';

    // Fetch current record data
    (async () => {
        try {
            const res = await fetch(`/admin/pendaftaran/${id}`);
            if (!res.ok) throw new Error('Tidak dapat memuat data pendaftaran');
            const payload = await res.json().catch(() => null);
            const data = payload?.data || payload || {};

            console.log('Pendaftaran data:', data);
            console.log('id_divisi dari pendaftar:', data.id_divisi);

            // Store data for use in updateStatusContent
            window.currentPendaftaranData = data;

            // Display user info
            const userDisplayEl = document.getElementById('userDisplay');
            const dateDisplayEl = document.getElementById('dateDisplay');
            
            if (userDisplayEl) userDisplayEl.textContent = data.nama || '-';
            if (dateDisplayEl) dateDisplayEl.textContent = data.created_at ? new Date(data.created_at).toLocaleDateString('id-ID') : '-';
            
            // Pre-fill divisi dengan yang dipilih pendaftar jika ada
            if (data.id_divisi && idDivisiEl) {
                idDivisiEl.value = data.id_divisi;
                console.log('Set divisi to:', data.id_divisi);
                // Trigger filter jabatan
                filterJabatanByDivisi(data.id_divisi);
            }
            
            const modal = new bootstrap.Modal(document.getElementById('statusModal'));
            modal.show();
        } catch (err) {
            console.warn('Error loading data:', err);
            showAlert('Gagal memuat data pendaftaran', 'error');
        }
    })();
};

// Handle status selection change
window.updateStatusContent = function() {
    const status = document.getElementById('status_select').value;
    const data = window.currentPendaftaranData || {};
    const interviewDateInput = document.getElementById('interview_date');

    // Hide all content sections
    document.getElementById('diterimaContent').style.display = 'none';
    document.getElementById('ditolakContent').style.display = 'none';
    document.getElementById('interviewContent').style.display = 'none';

    // Always remove required from interview_date first
    interviewDateInput.removeAttribute('required');

    if (status === 'diterima') {
        document.getElementById('diterimaContent').style.display = 'block';
        document.getElementById('submitButton').className = 'btn btn-success';
        document.getElementById('submitText').textContent = 'Terima Pendaftaran';
        
        // Auto-generate message
        const adminName = '{{ Auth::user()->name ?? "Admin" }}';
        const pesanDefault = `Selamat ${data.nama || 'Pendaftar'}, pendaftaran kamu telah diterima oleh ${adminName} sebagai anggota HIMA TI. Silahkan tunggu penjelasan lebih lanjut mengenai orientasi dan kegiatan selanjutnya. Terima kasih sudah bergabung dengan kami!`;
        document.getElementById('pesan_diterima').value = pesanDefault;
    } else if (status === 'ditolak') {
        document.getElementById('ditolakContent').style.display = 'block';
        document.getElementById('submitButton').className = 'btn btn-danger';
        document.getElementById('submitText').textContent = 'Tolak Pendaftaran';
    } else if (status === 'interview') {
        document.getElementById('interviewContent').style.display = 'block';
        document.getElementById('submitButton').className = 'btn btn-info';
        document.getElementById('submitText').textContent = 'Jadwalkan Interview';
        
        // Set required attribute ONLY when interview is selected and visible
        interviewDateInput.setAttribute('required', '');
        interviewDateInput.focus();
    }
}

// Fungsi untuk load jabatan berdasarkan divisi
window.loadJabatanByDivisi = async function(idDivisi) {
    const jabatanSelect = document.getElementById('id_jabatan');
    
    if (!idDivisi) {
        jabatanSelect.innerHTML = '<option value="">Pilih Jabatan</option>';
        return;
    }

    try {
        const response = await fetch(`/admin/pendaftaran/jabatan-by-divisi/${idDivisi}`);
        const result = await response.json();
        
        if (result.success && result.data) {
            jabatanSelect.innerHTML = '<option value="">Pilih Jabatan</option>';
            result.data.forEach(jabatan => {
                const option = document.createElement('option');
                option.value = jabatan.id_jabatan;
                option.textContent = jabatan.nama_jabatan;
                jabatanSelect.appendChild(option);
            });
        } else {
            jabatanSelect.innerHTML = '<option value="">Tidak ada jabatan tersedia</option>';
        }
    } catch (error) {
        console.error('Error loading jabatan:', error);
        jabatanSelect.innerHTML = '<option value="">Gagal memuat jabatan</option>';
    }
}

// Filter jabatan berdasarkan divisi yang dipilih (dari options yang sudah ada)
window.filterJabatanByDivisi = function(idDivisi) {
    console.log('filterJabatanByDivisi called with:', idDivisi);
    
    const jabatanSelect = document.getElementById('id_jabatan');
    const allOptions = jabatanSelect.querySelectorAll('option[data-divisi]');
    
    console.log('Total options with data-divisi:', allOptions.length);
    
    // Reset to default option
    jabatanSelect.innerHTML = '<option value="">Pilih Jabatan</option>';
    
    if (!idDivisi) {
        console.log('No divisi selected, clearing jabatan');
        return;
    }
    
    // Filter dan add options yang sesuai dengan divisi
    let count = 0;
    allOptions.forEach(option => {
        const dataDivisi = option.getAttribute('data-divisi');
        console.log('Checking option:', option.textContent, 'dataDivisi:', dataDivisi, 'selected idDivisi:', idDivisi);
        
        if (String(dataDivisi) === String(idDivisi)) {
            jabatanSelect.appendChild(option.cloneNode(true));
            count++;
        }
    });
    
    console.log('Added', count, 'jabatan options');
}

// ============================================
// UTILITY FUNCTIONS
// ============================================

// Fungsi untuk menyimpan pengaturan dan membuka pendaftaran
async function simpanDanBukaPendaftaran(event) {
    // Validasi form terlebih dahulu
    const tanggalMulai = document.getElementById('tanggal_mulai').value;
    const tanggalSelesai = document.getElementById('tanggal_selesai').value;
    const kuota = document.getElementById('kuota').value;
    
    if (!tanggalMulai || !tanggalSelesai || !kuota) {
        showAlert('Harap isi semua field pengaturan terlebih dahulu', 'error');
        return;
    }
    
    const startDate = new Date(tanggalMulai);
    const endDate = new Date(tanggalSelesai);
    const today = new Date();
    today.setHours(0, 0, 0, 0);
    
    if (endDate <= startDate) {
        showAlert('Tanggal selesai harus setelah tanggal mulai', 'error');
        return;
    }
    
    if (startDate < today) {
        showAlert('Tanggal mulai tidak boleh di masa lalu', 'error');
        return;
    }
    
    if (kuota < 1) {
        showAlert('Kuota harus minimal 1', 'error');
        return;
    }

    if (!confirm('Apakah Anda yakin ingin menyimpan pengaturan dan membuka sesi pendaftaran?\nPendaftaran akan langsung aktif setelah pengaturan disimpan.')) {
        return;
    }

    try {
        const button = event?.target || document.querySelector('button[onclick*="simpanDanBukaPendaftaran"]');
        const originalText = button.innerHTML;
        button.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Menyimpan & Membuka...';
        button.disabled = true;

        // 1. Simpan pengaturan terlebih dahulu
        const formData = new FormData(document.getElementById('periodeForm'));
        
        const saveResponse = await fetch("{{ route('admin.pendaftaran.update-settings') }}", {
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': '{{ csrf_token() }}',
                'X-Requested-With': 'XMLHttpRequest'
            },
            body: formData
        });

        const saveResult = await saveResponse.json();

        if (!saveResult.success) {
            throw new Error(saveResult.message || 'Gagal menyimpan pengaturan');
        }

        // 2. Buka sesi pendaftaran
        const openResponse = await fetch("{{ route('admin.pendaftaran.buka-sesi') }}", {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': '{{ csrf_token() }}',
                'X-Requested-With': 'XMLHttpRequest'
            },
            body: JSON.stringify({})
        });

        const openResult = await openResponse.json();

        if (openResult.success) {
            showAlert('Pengaturan berhasil disimpan dan sesi pendaftaran berhasil dibuka', 'success');
            // Update UI instead of reload
            setTimeout(() => {
                location.href = window.location.href; // Soft reload to update the page data
            }, 1500);
        } else {
            throw new Error(openResult.message || 'Gagal membuka sesi pendaftaran');
        }

    } catch (error) {
        console.error('Error:', error);
        showAlert(error.message || 'Terjadi kesalahan saat menyimpan pengaturan dan membuka sesi pendaftaran', 'error');
        
        // Reset button state dengan cara yang aman
        const button = event?.target || document.querySelector('button[onclick*="simpanDanBukaPendaftaran"]');
        if (button) {
            button.innerHTML = '<i class="fas fa-play me-2"></i>Simpan & Buka Pendaftaran';
            button.disabled = false;
        }
    }
}

// Fungsi untuk menutup sesi pendaftaran
async function tutupSesiPendaftaran(event) {
    if (!confirm('Apakah Anda yakin ingin menutup sesi pendaftaran?\nPendaftaran baru tidak akan bisa masuk.')) {
        return;
    }

    try {
        const button = event?.target || document.querySelector('button[onclick*="tutupSesiPendaftaran"]');
        const originalText = button.innerHTML;
        button.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Menutup...';
        button.disabled = true;

        const response = await fetch("{{ route('admin.pendaftaran.tutup-sesi') }}", {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': '{{ csrf_token() }}',
                'X-Requested-With': 'XMLHttpRequest'
            },
            body: JSON.stringify({})
        });

        const payload = await response.json();
        const data = payload.data || payload;

        if (data.success) {
            showAlert('Sesi pendaftaran berhasil ditutup', 'success');
            // Update UI instead of reload
            setTimeout(() => {
                location.href = window.location.href; // Soft reload to update the page data
            }, 1500);
        } else {
            throw new Error(data.message || 'Gagal menutup sesi pendaftaran');
        }

    } catch (error) {
        console.error('Error:', error);
        showAlert(error.message || 'Terjadi kesalahan saat menutup sesi pendaftaran', 'error');
        
        // Reset button state dengan cara yang aman
        const button = event?.target || document.querySelector('button[onclick*="tutupSesiPendaftaran"]');
        if (button) {
            button.innerHTML = '<i class="fas fa-stop me-2"></i>Tutup Pendaftaran';
            button.disabled = false;
        }
    }
}

// Fungsi untuk menampilkan alert
function showAlert(message, type = 'info') {
    const alertClass = {
        'success': 'alert-success',
        'error': 'alert-danger',
        'warning': 'alert-warning',
        'info': 'alert-info'
    }[type] || 'alert-info';

    const iconClass = {
        'success': 'fa-check-circle',
        'error': 'fa-exclamation-circle',
        'warning': 'fa-exclamation-triangle',
        'info': 'fa-info-circle'
    }[type] || 'fa-info-circle';

    const alertHtml = `
        <div class="alert ${alertClass} alert-dismissible fade show">
            <div class="d-flex align-items-center">
                <i class="fas ${iconClass} me-2"></i>
                <div>${message}</div>
            </div>
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    `;

    const container = document.querySelector('.container-fluid');
    const firstCard = container.querySelector('.card');
    container.insertBefore(document.createRange().createContextualFragment(alertHtml), firstCard);

    // Auto remove after 5 seconds
    setTimeout(() => {
        const alert = document.querySelector('.alert');
        if (alert) {
            const bsAlert = new bootstrap.Alert(alert);
            bsAlert.close();
        }
    }, 5000);
}

// Validasi tanggal
function validateDates() {
    const tanggalMulai = document.getElementById('tanggal_mulai');
    const tanggalSelesai = document.getElementById('tanggal_selesai');
    
    if (tanggalMulai.value && tanggalSelesai.value) {
        const startDate = new Date(tanggalMulai.value);
        const endDate = new Date(tanggalSelesai.value);
        
        if (endDate <= startDate) {
            showAlert('Tanggal selesai harus setelah tanggal mulai', 'error');
            tanggalSelesai.value = '';
            return false;
        }
    }
    return true;
}

// Filter pendaftaran (dengan smooth transition)
function filterPendaftaran(status) {
    const url = new URL(window.location.href);
    if (status === 'all') {
        url.searchParams.delete('status');
    } else {
        url.searchParams.set('status', status);
    }
    url.searchParams.delete('page'); // Reset to first page
    
    // Update active button indicator
    document.querySelectorAll('.nav-link').forEach(btn => {
        btn.classList.remove('active');
    });
    
    // Set active class on clicked button
    event.target.closest('.nav-link').classList.add('active');
    
    // Show/hide bulk action section (hanya untuk pending)
    const bulkActionSection = document.getElementById('bulkActionSection');
    const bulkAcceptSection = document.getElementById('bulkAcceptSection');
    
    if (status === 'pending') {
        bulkActionSection.style.display = 'block';
        bulkAcceptSection.style.display = 'none';
    } else if (status === 'interview') {
        bulkActionSection.style.display = 'none';
        bulkAcceptSection.style.display = 'block';
    } else {
        bulkActionSection.style.display = 'none';
        bulkAcceptSection.style.display = 'none';
    }
    
    // Save scroll position
    const scrollPos = window.scrollY;
    
    // Use history API untuk update URL tanpa reload penuh
    window.history.pushState({ scrollPos: scrollPos }, '', url.toString());
    
    // Load content via fetch
    loadFilteredContent(url);
}

// Pencarian pendaftaran (dengan smooth transition)
function searchPendaftaran() {
    const searchTerm = document.getElementById('searchInput').value.trim();
    const url = new URL(window.location.href);
    if (searchTerm === '') {
        url.searchParams.delete('search');
    } else {
        url.searchParams.set('search', searchTerm);
    }
    url.searchParams.delete('page'); // Reset to first page
    
    // Save scroll position
    const scrollPos = window.scrollY;
    
    // Use history API untuk update URL tanpa reload penuh
    window.history.pushState({ scrollPos: scrollPos }, '', url.toString());
    
    // Load content via fetch
    loadFilteredContent(url);
}

// Load filtered content via AJAX
async function loadFilteredContent(url) {
    try {
        const response = await fetch(url.toString(), {
            headers: {
                'X-Requested-With': 'XMLHttpRequest'
            }
        });
        
        if (!response.ok) {
            throw new Error('Gagal memuat data');
        }
        
        const html = await response.text();
        
        // Parse dan update card body (termasuk table + pagination)
        const parser = new DOMParser();
        const newDoc = parser.parseFromString(html, 'text/html');
        
        // Find card body dengan class "card-body p-0"
        const newCardBody = newDoc.querySelector('.card-body.p-0');
        const currentCardBody = document.querySelector('.main-card .card-body.p-0');
        
        if (newCardBody && currentCardBody) {
            currentCardBody.innerHTML = newCardBody.innerHTML;
            // Re-initialize tooltips setelah update
            const tooltips = document.querySelectorAll('[data-bs-toggle="tooltip"]');
            tooltips.forEach(tooltip => {
                new bootstrap.Tooltip(tooltip);
            });
        } else {
            // Fallback to full reload jika struktur tidak match
            location.href = url.toString();
        }
        
    } catch (error) {
        console.error('Error loading content:', error);
        // Fallback to traditional reload
        location.href = url.toString();
    }
}

// Show Bulk Interview Modal
function showBulkInterviewModal() {
    // Get count dari badge pending
    const pendingCount = document.querySelector('.nav-link[onclick="filterPendaftaran(\'pending\')"] .badge')?.textContent?.trim() || '0';
    document.getElementById('pendingCount').textContent = pendingCount;
    
    // Set min date to today
    const today = new Date().toISOString().split('T')[0];
    document.getElementById('bulkInterviewDate').min = today;
    
    const modal = new bootstrap.Modal(document.getElementById('bulkInterviewModal'));
    modal.show();
}

// Handle Bulk Interview Form Submit
document.addEventListener('DOMContentLoaded', function() {
    const bulkInterviewForm = document.getElementById('bulkInterviewForm');
    if (bulkInterviewForm) {
        bulkInterviewForm.addEventListener('submit', async function(e) {
            e.preventDefault();
            
            const interviewDate = document.getElementById('bulkInterviewDate').value;
            
            if (!interviewDate) {
                showAlert('Tanggal interview harus diisi', 'warning');
                return;
            }
            
            try {
                const button = this.querySelector('button[type="submit"]');
                const originalText = button.innerHTML;
                button.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Memproses...';
                button.disabled = true;
                
                const response = await fetch("{{ route('admin.pendaftaran.bulk-interview') }}", {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': '{{ csrf_token() }}',
                        'X-Requested-With': 'XMLHttpRequest'
                    },
                    body: JSON.stringify({
                        interview_date: interviewDate
                    })
                });
                
                const data = await response.json();
                
                if (data.success) {
                    showAlert(data.message || 'Interview berhasil dijadwalkan untuk semua pendaftar', 'success');
                    
                    // Close modal
                    const modal = bootstrap.Modal.getInstance(document.getElementById('bulkInterviewModal'));
                    if (modal) modal.hide();
                    
                    // Reload table
                    setTimeout(() => {
                        loadFilteredContent(new URL(window.location.href));
                    }, 1000);
                } else {
                    throw new Error(data.message || 'Gagal menjadwalkan interview');
                }
            } catch (error) {
                console.error('Error:', error);
                showAlert(error.message || 'Terjadi kesalahan', 'error');
                
                // Reset button
                const button = this.querySelector('button[type="submit"]');
                button.innerHTML = '<i class="fas fa-check me-2"></i>Jadwalkan Semua';
                button.disabled = false;
            }
        });
    }
});

// Show Bulk Accept Modal
function showBulkAcceptModal() {
    // Get count dari badge interview
    const interviewCount = document.querySelector('.nav-link[onclick="filterPendaftaran(\'interview\')"] .badge')?.textContent?.trim() || '0';
    document.getElementById('interviewCount').textContent = interviewCount;
    
    // Reset form
    document.getElementById('bulkAcceptJabatan').value = '';
    
    const modal = new bootstrap.Modal(document.getElementById('bulkAcceptModal'));
    modal.show();
}

// Handle Bulk Accept Form Submit
document.addEventListener('DOMContentLoaded', function() {
    const bulkAcceptForm = document.getElementById('bulkAcceptForm');
    if (bulkAcceptForm) {
        bulkAcceptForm.addEventListener('submit', async function(e) {
            e.preventDefault();
            
            const jabatanId = document.getElementById('bulkAcceptJabatan').value;
            
            if (!jabatanId) {
                showAlert('Jabatan harus dipilih', 'warning');
                return;
            }
            
            try {
                const button = this.querySelector('button[type="submit"]');
                const originalText = button.innerHTML;
                button.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Memproses...';
                button.disabled = true;
                
                const response = await fetch("{{ route('admin.pendaftaran.bulk-accept') }}", {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': '{{ csrf_token() }}',
                        'X-Requested-With': 'XMLHttpRequest'
                    },
                    body: JSON.stringify({
                        id_jabatan: jabatanId
                    })
                });
                
                const data = await response.json();
                
                if (data.success) {
                    showAlert(data.message || 'Semua pendaftar berhasil diterima', 'success');
                    
                    // Close modal
                    const modal = bootstrap.Modal.getInstance(document.getElementById('bulkAcceptModal'));
                    if (modal) modal.hide();
                    
                    // Reload table
                    setTimeout(() => {
                        loadFilteredContent(new URL(window.location.href));
                    }, 1000);
                } else {
                    throw new Error(data.message || 'Gagal menerima pendaftar');
                }
            } catch (error) {
                console.error('Error:', error);
                showAlert(error.message || 'Terjadi kesalahan', 'error');
                
                // Reset button
                const button = this.querySelector('button[type="submit"]');
                button.innerHTML = '<i class="fas fa-check me-2"></i>Terima Semua';
                button.disabled = false;
            }
        });
    }
});

// View detail pendaftaran
async function viewDetail(id) {
    try {
        const response = await fetch(`/admin/pendaftaran/${id}`);
        if (!response.ok) {
            throw new Error('Gagal memuat data detail');
        }

        const payload = await response.json();
        const data = payload.data || payload;
        
        // determine badge class for status
        let statusClass = 'bg-secondary';
        if (data.status_pendaftaran === 'submitted') statusClass = 'bg-secondary';
        else if (data.status_pendaftaran === 'verifying') statusClass = 'bg-primary';
        else if (data.status_pendaftaran === 'interview') statusClass = 'bg-warning text-dark';
        else if (data.status_pendaftaran === 'diterima') statusClass = 'bg-success';
        else if (data.status_pendaftaran === 'ditolak') statusClass = 'bg-danger';
        else if (data.status_pendaftaran === 'pending') statusClass = 'bg-warning text-dark';

        let content = `
            <style>
                .detail-section { margin-bottom: 1.5rem; }
                .detail-section h6 { 
                    font-weight: 600; 
                    color: #495057;
                    padding-bottom: 0.75rem;
                    border-bottom: 2px solid #e9ecef;
                    margin-bottom: 1rem;
                }
                .detail-row { 
                    display: flex; 
                    padding: 0.75rem 0;
                    border-bottom: 1px solid #f1f3f5;
                }
                .detail-row:last-child { border-bottom: none; }
                .detail-label { 
                    font-weight: 600;
                    color: #6c757d;
                    min-width: 150px;
                }
                .detail-value { 
                    color: #212529;
                    word-break: break-word;
                }
                .text-box {
                    background: #f8f9fa;
                    border-left: 4px solid #4361ee;
                    padding: 1rem;
                    border-radius: 4px;
                    margin: 0.5rem 0;
                    line-height: 1.6;
                }
                .badge-large { padding: 0.5rem 1rem; font-size: 0.9rem; }
            </style>
            <div class="detail-section">
                <h6><i class="fas fa-user-circle me-2"></i>Data Pribadi</h6>
                <div class="detail-row">
                    <span class="detail-label">Nama</span>
                    <span class="detail-value"><strong>${data.nama || '-'}</strong></span>
                </div>
                <div class="detail-row">
                    <span class="detail-label">NIM</span>
                    <span class="detail-value"><code>${data.nim || '-'}</code></span>
                </div>
                <div class="detail-row">
                    <span class="detail-label">Semester</span>
                    <span class="detail-value"><span class="badge bg-secondary">Semester ${data.semester || '-'}</span></span>
                </div>
                <div class="detail-row">
                    <span class="detail-label">No HP</span>
                    <span class="detail-value">${data.no_hp || '-'}</span>
                </div>
                <div class="detail-row">
                    <span class="detail-label">Email</span>
                    <span class="detail-value">${data.user?.email || '-'}</span>
                </div>
            </div>

            <div class="detail-section">
                <h6><i class="fas fa-clipboard-check me-2"></i>Informasi Pendaftaran</h6>
                <div class="detail-row">
                    <span class="detail-label">Tanggal Daftar</span>
                    <span class="detail-value">${data.created_at ? new Date(data.created_at).toLocaleDateString('id-ID', { weekday: 'long', year: 'numeric', month: 'long', day: 'numeric' }) : '-'}</span>
                </div>
                <div class="detail-row">
                    <span class="detail-label">Status</span>
                    <span class="detail-value"><span class="badge badge-large ${statusClass}">${data.status_pendaftaran?.toUpperCase() || '-'}</span></span>
                </div>
                <div class="detail-row">
                    <span class="detail-label">Divisi</span>
                    <span class="detail-value">
                        ${data.divisi ? `<span class="badge bg-info badge-large">${data.divisi.nama_divisi || data.divisi.nama || '-'}</span>` : '<span class="text-muted">-</span>'}
                    </span>
                </div>
                ${data.validator ? `
                <div class="detail-row">
                    <span class="detail-label">Divalidasi Oleh</span>
                    <span class="detail-value">${data.validator.name || 'Admin'}</span>
                </div>
                ` : ''}
                ${data.jabatan ? `
                <div class="detail-row">
                    <span class="detail-label">Jabatan</span>
                    <span class="detail-value"><span class="badge bg-success badge-large">${data.jabatan.nama_jabatan || data.jabatan.nama || '-'}</span></span>
                </div>
                ` : ''}
            </div>

            <div class="detail-section">
                <h6><i class="fas fa-comment me-2"></i>Alasan Mendaftar</h6>
                <div class="text-box">
                    ${(data.alasan_mendaftar || '-').replace(/\n/g, '<br>')}
                </div>
            </div>
        `;

        if (data.alasan_divisi) {
            content += `
                <div class="detail-section">
                    <h6><i class="fas fa-lightbulb me-2"></i>Alasan Memilih Divisi</h6>
                    <div class="text-box">
                        ${(data.alasan_divisi || '-').replace(/\n/g, '<br>')}
                    </div>
                </div>
            `;
        }
        
        if (data.pengalaman) {
            content += `
                <div class="detail-section">
                    <h6><i class="fas fa-briefcase me-2"></i>Pengalaman Organisasi</h6>
                    <div class="text-box">
                        ${data.pengalaman.replace(/\n/g, '<br>')}
                    </div>
                </div>
            `;
        }
        
        if (data.skill) {
            content += `
                <div class="detail-section">
                    <h6><i class="fas fa-star me-2"></i>Kemampuan/Keterampilan</h6>
                    <div class="text-box">
                        ${data.skill.replace(/\n/g, '<br>')}
                    </div>
                </div>
            `;
        }
        
        if (data.dokumen) {
            content += `
                <div class="detail-section">
                    <h6><i class="fas fa-file me-2"></i>Dokumen Pendaftaran</h6>
                    <a href="{{ asset('storage/') }}/${data.dokumen}" target="_blank" class="btn btn-outline-primary btn-sm">
                        <i class="fas fa-file-pdf me-2"></i>Lihat Dokumen
                    </a>
                </div>
            `;
        }
        
        document.getElementById('detailContent').innerHTML = content;
        const modal = new bootstrap.Modal(document.getElementById('detailModal'));
        modal.show();
        
    } catch (error) {
        console.error('Error:', error);
        showAlert('Gagal memuat detail pendaftaran', 'error');
    }
}

// Update status pendaftaran
function updateStatus(id, status) {
    document.getElementById('pendaftaranId').value = id;
    document.getElementById('statusValue').value = status;
    document.getElementById('statusForm').action = "{{ route('admin.pendaftaran.update-status', ':id') }}".replace(':id', id);
    
    // Reset form fields
    document.getElementById('id_divisi').value = '';
    document.getElementById('id_jabatan').value = '';
    document.getElementById('notes').value = '';
    
    // Show/hide content based on status
    document.getElementById('diterimaContent').style.display = status === 'diterima' ? 'block' : 'none';
    document.getElementById('ditolakContent').style.display = status === 'ditolak' ? 'block' : 'none';
    
    // Set required fields
    document.getElementById('id_divisi').required = status === 'diterima';
    document.getElementById('id_jabatan').required = status === 'diterima';
    
    // Update modal content
    const row = document.querySelector(`tr[data-status] button[onclick*="${id}"]`)?.closest('tr');
    const nama = row ? row.querySelector('strong').textContent : 'Pendaftar';
    
    if (status === 'diterima') {
        document.getElementById('statusTitle').innerHTML = '<i class="fas fa-check-circle me-2"></i>Terima Pendaftaran';
        document.getElementById('confirmationText').textContent = `Apakah Anda yakin ingin menerima pendaftaran ${nama}?`;
        document.getElementById('submitButton').className = 'btn btn-success';
        document.getElementById('submitText').textContent = 'Terima';
    } else {
        document.getElementById('statusTitle').innerHTML = '<i class="fas fa-times-circle me-2"></i>Tolak Pendaftaran';
        document.getElementById('confirmationText').textContent = `Apakah Anda yakin ingin menolak pendaftaran ${nama}?`;
        document.getElementById('submitButton').className = 'btn btn-danger';
        document.getElementById('submitText').textContent = 'Tolak';
    }
    
    const modal = new bootstrap.Modal(document.getElementById('statusModal'));
    modal.show();
}

// Edit pendaftaran
async function editPendaftaran(id) {
    try {
        const response = await fetch(`/admin/pendaftaran/${id}/edit`);
        if (!response.ok) {
            throw new Error('Gagal memuat form edit');
        }
        
        const html = await response.text();
        document.getElementById('editContent').innerHTML = html;
        document.getElementById('editForm').action = "{{ route('admin.pendaftaran.update', ':id') }}".replace(':id', id);
        
        const modal = new bootstrap.Modal(document.getElementById('editModal'));
        modal.show();
        
    } catch (error) {
        console.error('Error:', error);
        showAlert('Gagal memuat form edit', 'error');
    }
}

// Initialize
document.addEventListener('DOMContentLoaded', function() {
    // Tooltip initialization
    const tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'));
    const tooltipList = tooltipTriggerList.map(function(tooltipTriggerEl) {
        return new bootstrap.Tooltip(tooltipTriggerEl);
    });
    
    // Enter key for search
    document.getElementById('searchInput').addEventListener('keypress', function(e) {
        if (e.key === 'Enter') {
            searchPendaftaran();
        }
    });

    // Auto search on input (debounced)
    let searchTimeout;
    document.getElementById('searchInput').addEventListener('input', function(e) {
        clearTimeout(searchTimeout);
        searchTimeout = setTimeout(function() {
            searchPendaftaran();
        }, 500); // 500ms debounce
    });

    // Handle pagination links
    document.addEventListener('click', function(e) {
        if (e.target.matches('.pagination-link')) {
            e.preventDefault();
            const url = e.target.getAttribute('href');
            if (url) {
                loadFilteredContent(new URL(url, window.location.origin));
            }
        }
    });
    
    // Date validation
    const tanggalMulai = document.getElementById('tanggal_mulai');
    const tanggalSelesai = document.getElementById('tanggal_selesai');
    
    if (tanggalMulai && tanggalSelesai) {
        tanggalMulai.addEventListener('change', function() {
            validateDates();
        });
        
        tanggalSelesai.addEventListener('change', function() {
            validateDates();
        });
    }
    
    // Handle form submission for status modal
    document.getElementById('statusForm').addEventListener('submit', async function(e) {
        e.preventDefault();
        
        const form = this;
        const id = document.getElementById('pendaftaranId').value;
        const statusSelectEl = document.getElementById('status_select');
        const status = statusSelectEl ? statusSelectEl.value : '';
        
        console.log('=== FORM SUBMIT DEBUG ===');
        console.log('ID:', id);
        console.log('Status element:', statusSelectEl);
        console.log('Status value:', status);
        console.log('Status is empty?', !status || status === '');
        
        // Validate status selection
        if (!status || status === '') {
            showAlert('Harap pilih status terlebih dahulu', 'error');
            console.log('BLOCKED: Status tidak dipilih');
            return;
        }

        // Validate required fields for acceptance
        if (status === 'diterima') {
            const divisi = document.getElementById('id_divisi').value;
            const jabatan = document.getElementById('id_jabatan').value;
            if (!divisi || !jabatan) {
                showAlert('Harap pilih divisi dan jabatan untuk penerimaan', 'error');
                console.log('BLOCKED: divisi atau jabatan kosong');
                return;
            }
        }

        // Create fresh FormData with only required fields
        const formData = new FormData();
        
        // Add required fields only
        formData.append('status_pendaftaran', status);
        formData.append('_token', document.querySelector('#statusForm [name="_token"]').value);
        
        console.log('=== FormData being sent ===');
        console.log('status_pendaftaran:', status);
        
        // Add interview_date only if status is interview
        if (status === 'interview') {
            const interviewDate = document.getElementById('interview_date').value;
            
            // Client-side validation: interview_date is required when status is interview
            if (!interviewDate) {
                showAlert('Tanggal interview harus diisi', 'warning');
                document.getElementById('interview_date').focus();
                return;
            }
            
            formData.append('interview_date', interviewDate);
            console.log('interview_date:', interviewDate);
        }

        // Add notes for rejection or acceptance message
        if (status === 'ditolak' || status === 'diterima') {
            const notes = document.getElementById('notes').value;
            if (notes) {
                formData.append('notes', notes);
                console.log('notes:', notes);
            }
        }

        console.log('=== Sending to server ===');
        for (let [key, value] of formData.entries()) {
            console.log(`  ${key}: "${value}"`);
        }

        try {
            // Get CSRF token dari form
            const tokenEl = document.querySelector('#statusForm [name="_token"]');
            const csrfToken = tokenEl ? tokenEl.value : '';
            
            console.log('CSRF Token found?', !!csrfToken);
            console.log('Sending PUT to: /admin/pendaftaran/' + id + '/update-status');
            
            // Use POST with method override so Laravel can handle FormData properly
            const response = await fetch(`/admin/pendaftaran/${id}/update-status`, {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': csrfToken,
                    'Accept': 'application/json',
                    'X-Requested-With': 'XMLHttpRequest',
                    'X-HTTP-Method-Override': 'PUT'
                },
                body: formData
            });

            console.log('Response status:', response.status);

            const data = await response.json().catch((e) => {
                console.log('JSON parse error:', e);
                return {};
            });
            
            console.log('Response data:', data);
            
            if (!response.ok) {
                // Handle validation errors
                if (data.errors) {
                    console.log('Validation errors detail:');
                    const errorArray = [];
                    for (let field in data.errors) {
                        console.log(`  ${field}:`, data.errors[field]);
                        errorArray.push(...data.errors[field]);
                    }
                    const errorMessages = errorArray.join(', ');
                    throw new Error(errorMessages);
                }
                throw new Error(data.message || 'Gagal mengubah status pendaftaran');
            }
            
            showAlert(data.message || 'Status pendaftaran berhasil diubah', 'success');
            setTimeout(() => {
                const modal = bootstrap.Modal.getInstance(document.getElementById('statusModal'));
                if (modal) modal.hide();
                location.reload();
            }, 800);
        } catch (error) {
            console.error('=== ERROR ===', error);
            showAlert(error.message || 'Terjadi kesalahan saat menyimpan status', 'error');
        }
    });

});
</script>
@endpush