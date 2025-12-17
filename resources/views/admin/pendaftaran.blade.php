@php $layout = request()->is('admin-panel/*') ? 'layouts.admin-panel.app' : 'layouts.admin.app'; @endphp
@extends($layout)

@section('title', 'Kelola Pendaftaran - HIMA Sistem Manajemen')

@section('content')
<div class="fade-in-up">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h1 class="h3 fw-bold gradient-text mb-1">Kelola Pendaftaran</h1>
            <p class="text-muted">Validasi dan kelola pendaftaran anggota baru</p>
        </div>
        <div class="btn-group">
            <button class="btn btn-outline-primary active" onclick="filterPendaftaran('all')">
                Semua <span class="badge bg-primary ms-1">{{ count($pendaftaran) }}</span>
            </button>
            <button class="btn btn-outline-warning" onclick="filterPendaftaran('pending')">
                Pending <span class="badge bg-warning ms-1">{{ $pendaftaran->where('status_pendaftaran', 'pending')->count() }}</span>
            </button>
            <button class="btn btn-outline-success" onclick="filterPendaftaran('diterima')">
                Diterima <span class="badge bg-success ms-1">{{ $pendaftaran->where('status_pendaftaran', 'diterima')->count() }}</span>
            </button>
            <button class="btn btn-outline-danger" onclick="filterPendaftaran('ditolak')">
                Ditolak <span class="badge bg-danger ms-1">{{ $pendaftaran->where('status_pendaftaran', 'ditolak')->count() }}</span>
            </button>
        </div>
    </div>

    <!-- Alert Notifikasi -->
    @if(session('success'))
    <div class="alert alert-success alert-dismissible fade show">
        <i class="fas fa-check-circle me-2"></i>{{ session('success') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
    @endif

    @if(session('error'))
    <div class="alert alert-danger alert-dismissible fade show">
        <i class="fas fa-exclamation-circle me-2"></i>{{ session('error') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
    @endif

    <div class="table-card">
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-hover" id="pendaftaranTable">
                    <thead>
                        <tr>
                            <th width="50">#</th>
                            <th>Data Pendaftar</th>
                            <th>NIM</th>
                            <th>Semester</th>
                            <th>Kontak</th>
                            <th>Alasan Mendaftar</th>
                            <th>Dokumen</th>
                            <th>Status</th>
                            <th width="150">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($pendaftaran as $index => $item)
                        <tr data-status="{{ $item['status_pendaftaran'] }}">
                            <td>{{ $index + 1 }}</td>
                            <td>
                                <div class="d-flex align-items-center">
                                    <div class="bg-primary rounded-circle d-flex align-items-center justify-content-center me-3" 
                                         style="width: 40px; height: 40px;">
                                        <i class="fas fa-user text-white"></i>
                                    </div>
                                    <div>
                                        <strong>{{ $item['nama'] }}</strong>
                                        <br>
                                        <small class="text-muted">{{ \Carbon\Carbon::parse($item['submitted_at'])->format('d M Y H:i') }}</small>
                                    </div>
                                </div>
                            </td>
                            <td>
                                <code>{{ $item['nim'] }}</code>
                            </td>
                            <td>
                                <span class="badge bg-info">Semester {{ $item['semester'] }}</span>
                            </td>
                            <td>
                                <small>
                                    <i class="fas fa-phone me-1"></i> {{ $item['no_hp'] ?? '-' }}<br>
                                    <i class="fas fa-envelope me-1"></i> {{ $item['email'] ?? '-' }}
                                </small>
                            </td>
                            <td>
                                <span class="text-muted">{{ Str::limit($item['alasan_mendaftar'], 50) }}</span>
                            </td>
                            <td>
                                @if($item['dokumen'])
                                <a href="{{ asset('storage/' . $item['dokumen']) }}" 
                                   target="_blank" 
                                   class="btn btn-sm btn-outline-primary"
                                   data-bs-toggle="tooltip" title="Lihat Dokumen">
                                    <i class="fas fa-file-pdf"></i>
                                </a>
                                @else
                                <span class="text-muted">-</span>
                                @endif
                            </td>
                            <td>
                                <span class="badge 
                                    @if($item['status_pendaftaran'] == 'pending') bg-warning
                                    @elseif($item['status_pendaftaran'] == 'diterima') bg-success
                                    @else bg-danger @endif">
                                    {{ ucfirst($item['status_pendaftaran']) }}
                                </span>
                                @if($item['divalidasi_oleh'] && $item['status_pendaftaran'] != 'pending')
                                <br>
                                <small class="text-muted">Oleh: {{ $item['validator'] ?? 'Admin' }}</small>
                                @endif
                            </td>
                            <td>
                                <div class="btn-group">
                                    <button class="btn btn-sm btn-outline-info" 
                                            onclick="viewDetail({{ $item['id_pendaftaran'] }})"
                                            data-bs-toggle="tooltip" title="Detail">
                                        <i class="fas fa-eye"></i>
                                    </button>
                                    @if($item['status_pendaftaran'] == 'pending')
                                    <button class="btn btn-sm btn-outline-success" 
                                            onclick="updateStatus({{ $item['id_pendaftaran'] }}, 'diterima')"
                                            data-bs-toggle="tooltip" title="Terima">
                                        <i class="fas fa-check"></i>
                                    </button>
                                    <button class="btn btn-sm btn-outline-danger" 
                                            onclick="updateStatus({{ $item['id_pendaftaran'] }}, 'ditolak')"
                                            data-bs-toggle="tooltip" title="Tolak">
                                        <i class="fas fa-times"></i>
                                    </button>
                                    @endif
                                </div>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="9" class="text-center py-4">
                                <div class="text-muted">
                                    <i class="fas fa-clipboard-list fa-2x mb-3"></i>
                                    <p>Belum ada data pendaftaran</p>
                                </div>
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<!-- Modal Detail Pendaftaran -->
<div class="modal fade" id="detailModal" tabindex="-1">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Detail Pendaftaran</h5>
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
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="statusTitle">Konfirmasi Status</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form id="statusForm" method="POST">
                @csrf
                @method('PUT')
                <div class="modal-body">
                    <input type="hidden" id="pendaftaranId" name="id_pendaftaran">
                    <input type="hidden" id="statusValue" name="status_pendaftaran">
                    
                    <div id="diterimaContent" style="display: none;">
                        <div class="mb-3">
                            <label for="id_divisi" class="form-label">Divisi *</label>
                            <select class="form-select" id="id_divisi" name="id_divisi">
                                <option value="">Pilih Divisi</option>
                                @foreach($divisi as $div)
                                <option value="{{ $div['id_divisi'] }}">{{ $div['nama'] }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="mb-3">
                            <label for="id_jabatan" class="form-label">Jabatan *</label>
                            <select class="form-select" id="id_jabatan" name="id_jabatan">
                                <option value="">Pilih Jabatan</option>
                                @foreach($jabatan as $jab)
                                <option value="{{ $jab['id_jabatan'] }}">{{ $jab['nama_jabatan'] }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    
                    <div id="ditolakContent" style="display: none;">
                        <div class="mb-3">
                            <label for="notes" class="form-label">Alasan Penolakan</label>
                            <textarea class="form-control" id="notes" name="notes" 
                                      rows="3" placeholder="Berikan alasan penolakan..."></textarea>
                        </div>
                    </div>
                    
                    <p id="confirmationText"></p>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn" id="submitButton">Konfirmasi</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
// Data pendaftaran dari controller
const pendaftaranData = @json($pendaftaran);

function filterPendaftaran(status) {
    const rows = document.querySelectorAll('#pendaftaranTable tbody tr');
    rows.forEach(row => {
        if (status === 'all' || row.getAttribute('data-status') === status) {
            row.style.display = '';
        } else {
            row.style.display = 'none';
        }
    });
    
    // Update active button
    document.querySelectorAll('.btn-group .btn').forEach(btn => {
        btn.classList.remove('active');
    });
    event.target.classList.add('active');
}

function viewDetail(id) {
    const data = pendaftaranData.find(p => p.id_pendaftaran === id);
    if (data) {
        let content = `
            <div class="row">
                <div class="col-md-6">
                    <h6>Data Pribadi</h6>
                    <table class="table table-sm">
                        <tr><td><strong>Nama</strong></td><td>${data.nama}</td></tr>
                        <tr><td><strong>NIM</strong></td><td>${data.nim}</td></tr>
                        <tr><td><strong>Semester</strong></td><td>Semester ${data.semester}</td></tr>
                        <tr><td><strong>No HP</strong></td><td>${data.no_hp || '-'}</td></tr>
                        <tr><td><strong>Email</strong></td><td>${data.email || '-'}</td></tr>
                    </table>
                </div>
                <div class="col-md-6">
                    <h6>Informasi Pendaftaran</h6>
                    <table class="table table-sm">
                        <tr><td><strong>Tanggal Daftar</strong></td><td>${new Date(data.submitted_at).toLocaleDateString('id-ID')}</td></tr>
                        <tr><td><strong>Status</strong></td>
                            <td>
                                <span class="badge ${data.status_pendaftaran === 'pending' ? 'bg-warning' : data.status_pendaftaran === 'diterima' ? 'bg-success' : 'bg-danger'}">
                                    ${data.status_pendaftaran}
                                </span>
                            </td>
                        </tr>
                        ${data.divalidasi_oleh ? `<tr><td><strong>Divalidasi Oleh</strong></td><td>${data.validator || 'Admin'}</td></tr>` : ''}
                    </table>
                </div>
            </div>
            <div class="row mt-3">
                <div class="col-12">
                    <h6>Alasan Mendaftar</h6>
                    <div class="border rounded p-3">
                        ${data.alasan_mendaftar.replace(/\n/g, '<br>')}
                    </div>
                </div>
            </div>
            ${data.dokumen ? `
            <div class="row mt-3">
                <div class="col-12">
                    <h6>Dokumen Pendaftaran</h6>
                    <a href="{{ asset('storage/') }}/${data.dokumen}" target="_blank" class="btn btn-outline-primary">
                        <i class="fas fa-file-pdf me-2"></i>Lihat Dokumen
                    </a>
                </div>
            </div>
            ` : ''}
        `;
        
        document.getElementById('detailContent').innerHTML = content;
        const modal = new bootstrap.Modal(document.getElementById('detailModal'));
        modal.show();
    }
}

function updateStatus(id, status) {
    const data = pendaftaranData.find(p => p.id_pendaftaran === id);
    if (data) {
        document.getElementById('pendaftaranId').value = id;
        document.getElementById('statusValue').value = status;
        document.getElementById('statusForm').action = "{{ url('pendaftaran') }}/" + id;
        
        // Show/hide content based on status
        document.getElementById('diterimaContent').style.display = status === 'diterima' ? 'block' : 'none';
        document.getElementById('ditolakContent').style.display = status === 'ditolak' ? 'block' : 'none';
        
        // Update modal content
        if (status === 'diterima') {
            document.getElementById('statusTitle').textContent = 'Terima Pendaftaran';
            document.getElementById('confirmationText').textContent = `Apakah Anda yakin ingin menerima pendaftaran ${data.nama}?`;
            document.getElementById('submitButton').className = 'btn btn-success';
            document.getElementById('submitButton').textContent = 'Terima';
        } else {
            document.getElementById('statusTitle').textContent = 'Tolak Pendaftaran';
            document.getElementById('confirmationText').textContent = `Apakah Anda yakin ingin menolak pendaftaran ${data.nama}?`;
            document.getElementById('submitButton').className = 'btn btn-danger';
            document.getElementById('submitButton').textContent = 'Tolak';
        }
        
        const modal = new bootstrap.Modal(document.getElementById('statusModal'));
        modal.show();
    }
}

// Reset modal ketika ditutup
document.addEventListener('DOMContentLoaded', function() {
    const statusModal = document.getElementById('statusModal');
    statusModal.addEventListener('hidden.bs.modal', function() {
        document.getElementById('statusForm').reset();
        document.getElementById('diterimaContent').style.display = 'none';
        document.getElementById('ditolakContent').style.display = 'none';
    });
    
    // Tooltip initialization
    const tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'));
    const tooltipList = tooltipTriggerList.map(function(tooltipTriggerEl) {
        return new bootstrap.Tooltip(tooltipTriggerEl);
    });
});
</script>

<style>
.table-card {
    border: none;
    border-radius: 15px;
    box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
}

.gradient-text {
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    -webkit-background-clip: text;
    -webkit-text-fill-color: transparent;
}

.btn-group .btn.active {
    background-color: #0d6efd;
    color: white;
    border-color: #0d6efd;
}
</style>
@endpush