@extends('layouts.admin.app')

@section('title', 'Daftar Kriteria')

@push('style')
<style>
    .criteria-card { border-left: 4px solid #4e73df; }
    .benefit-badge { background-color: #1cc88a; }
    .cost-badge { background-color: #e74a3b; }
</style>
@endpush

@section('content')
<div class="container mt-5">
    <div class="card shadow criteria-card">
        <div class="card-header bg-white py-3">
            <div class="d-flex justify-content-between align-items-center">
                <h4 class="mb-0 text-primary">
                    <i class="fas fa-list"></i> Daftar Kriteria
                </h4>
                <a href="{{ route('ahp.perbandingan') }}" class="btn btn-primary">
                    <i class="fas fa-arrow-left"></i> Kembali ke Perbandingan
                </a>
            </div>
        </div>
        <div class="card-body">
            <div class="alert alert-info mb-4">
                <h5 class="alert-heading">
                    <i class="fas fa-info-circle"></i> Informasi Kriteria
                </h5>
                <p class="mb-0">
                    Total <strong>{{ $kriteria->count() }} kriteria</strong> ditemukan dalam sistem.
                    Semua kriteria bertipe <span class="badge benefit-badge">Benefit</span> 
                    (nilai lebih tinggi lebih baik).
                </p>
            </div>
            
            <div class="table-responsive">
                <table class="table table-bordered">
                    <thead class="thead-light">
                        <tr>
                            <th width="5%">#</th>
                            <th width="15%">Kode</th>
                            <th width="40%">Nama Kriteria</th>
                            <th width="15%">Tipe</th>
                            <th width="15%">Bobot</th>
                            <th width="10%">Urutan</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($kriteria as $index => $krit)
                        <tr>
                            <td class="text-center">{{ $index + 1 }}</td>
                            <td><strong>{{ $krit->kode }}</strong></td>
                            <td>{{ $krit->nama }}</td>
                            <td>
                                @if($krit->tipe == 'benefit')
                                <span class="badge benefit-badge">Benefit</span>
                                @else
                                <span class="badge cost-badge">Cost</span>
                                @endif
                            </td>
                            <td>
                                @if($krit->bobot)
                                <span class="font-weight-bold">{{ number_format($krit->bobot, 6) }}</span>
                                @else
                                <span class="text-muted">Belum dihitung</span>
                                @endif
                            </td>
                            <td class="text-center">{{ $krit->urutan }}</td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            
            <div class="row mt-4">
                <div class="col-md-6">
                    <div class="card">
                        <div class="card-header bg-light">
                            <h6 class="mb-0"><i class="fas fa-question-circle"></i> Keterangan</h6>
                        </div>
                        <div class="card-body">
                            <ul class="list-unstyled">
                                <li><strong>Benefit:</strong> Nilai lebih tinggi lebih baik</li>
                                <li><strong>Cost:</strong> Nilai lebih rendah lebih baik</li>
                                <li><strong>Bobot:</strong> Hasil perhitungan AHP (0-1)</li>
                                <li><strong>Urutan:</strong> Posisi kriteria dalam perbandingan</li>
                            </ul>
                        </div>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="card">
                        <div class="card-header bg-light">
                            <h6 class="mb-0"><i class="fas fa-calculator"></i> Status Perhitungan</h6>
                        </div>
                        <div class="card-body">
                            @if($kriteria->whereNotNull('bobot')->count() > 0)
                            <div class="alert alert-success">
                                <i class="fas fa-check-circle"></i>
                                <strong>Bobot sudah dihitung</strong>
                                <p class="mb-0 small">Kriteria sudah memiliki bobot AHP</p>
                            </div>
                            @else
                            <div class="alert alert-warning">
                                <i class="fas fa-exclamation-triangle"></i>
                                <strong>Bobot belum dihitung</strong>
                                <p class="mb-0 small">Lakukan perbandingan dan hitung AHP</p>
                            </div>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
    // Script khusus untuk halaman ini jika diperlukan
    $(document).ready(function() {
        // Inisialisasi DataTables jika diperlukan
        // $('table').DataTable();
        
        console.log('Halaman Daftar Kriteria siap');
    });
</script>
@endpush