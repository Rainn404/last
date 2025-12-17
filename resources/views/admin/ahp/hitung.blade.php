@extends('layouts.admin.app')

@section('title', 'Hitung AHP')

@section('content')
<div class="container-fluid">
    
    <!-- Header -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="card" style="background: linear-gradient(135deg, #4e73df 0%, #224abe 100%);">
                <div class="card-body text-white">
                    <h3 class="text-white"><i class="fas fa-calculator"></i> Perhitungan Metode AHP</h3>
                    <p class="mb-0 text-white">Analytical Hierarchy Process - Menghitung bobot kriteria berdasarkan perbandingan berpasangan</p>
                </div>
            </div>
        </div>
    </div>

    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show">
            <i class="fas fa-check-circle"></i> {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    @if(session('error'))
        <div class="alert alert-danger alert-dismissible fade show">
            <i class="fas fa-exclamation-circle"></i> {{ session('error') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    @if(isset($result))
    
    <!-- 1. Matriks Perbandingan Berpasangan -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="card">
                <div class="card-header bg-info text-white">
                    <h5 class="mb-0"><i class="fas fa-table"></i> 1. Matriks Perbandingan Berpasangan</h5>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-bordered table-sm text-center">
                            <thead class="table-dark">
                                <tr>
                                    <th>Kriteria</th>
                                    @foreach($result['criteria_names'] as $name)
                                        <th>{{ strtoupper($name) }}</th>
                                    @endforeach
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($result['criteria_names'] as $i => $name)
                                    <tr>
                                        <th class="table-secondary">{{ strtoupper($name) }}</th>
                                        @foreach($result['matrix'][$i] as $j => $value)
                                            <td class="{{ $i == $j ? 'table-warning' : '' }}">
                                                {{ $value == 1 ? '1' : number_format($value, 4) }}
                                            </td>
                                        @endforeach
                                    </tr>
                                @endforeach
                                <tr class="table-primary fw-bold">
                                    <th>TOTAL</th>
                                    @foreach($result['column_sums'] as $sum)
                                        <td>{{ number_format($sum, 4) }}</td>
                                    @endforeach
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- 2. Matriks Ternormalisasi + Bobot -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="card">
                <div class="card-header bg-success text-white">
                    <h5 class="mb-0"><i class="fas fa-percentage"></i> 2. Matriks Perbandingan Ternormalisasi</h5>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-bordered table-sm text-center">
                            <thead class="table-dark">
                                <tr>
                                    <th>Kriteria</th>
                                    @foreach($result['criteria_names'] as $name)
                                        <th>{{ strtoupper($name) }}</th>
                                    @endforeach
                                    <th class="bg-warning text-dark">BOBOT</th>
                                </tr>
                            </thead>
                            <tbody>
                                @php $totalBobot = 0; @endphp
                                @foreach($result['criteria_names'] as $i => $name)
                                    @php 
                                        $id = $result['criteria_ids'][$i];
                                        $bobot = $result['weights'][$id];
                                        $totalBobot += $bobot;
                                    @endphp
                                    <tr>
                                        <th class="table-secondary">{{ strtoupper($name) }}</th>
                                        @foreach($result['normalized_matrix'][$i] as $value)
                                            <td>{{ number_format($value, 4) }}</td>
                                        @endforeach
                                        <td class="table-warning fw-bold">{{ number_format($bobot, 2) }}</td>
                                    </tr>
                                @endforeach
                                <tr class="table-primary fw-bold">
                                    <th>TOTAL</th>
                                    @for($j = 0; $j < count($result['criteria_names']); $j++)
                                        <td>1.0000</td>
                                    @endfor
                                    <td class="table-success">{{ number_format($totalBobot, 2) }}</td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- 3. Rasio Konsistensi -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="card">
                <div class="card-header bg-warning text-dark">
                    <h5 class="mb-0"><i class="fas fa-check-double"></i> 3. Rasio Konsistensi</h5>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-3">
                            <div class="card bg-light">
                                <div class="card-body text-center">
                                    <h6 class="text-muted">Lambda Max (λmax)</h6>
                                    <h3 class="text-primary">{{ number_format($result['lambda_max'], 6) }}</h3>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="card bg-light">
                                <div class="card-body text-center">
                                    <h6 class="text-muted">Consistency Index (CI)</h6>
                                    <h3 class="text-info">{{ number_format($result['ci'], 6) }}</h3>
                                    <small class="text-muted">CI = (λmax - n) / (n - 1)</small>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="card bg-light">
                                <div class="card-body text-center">
                                    <h6 class="text-muted">Random Index (RI)</h6>
                                    <h3 class="text-secondary">{{ number_format($result['ri'], 2) }}</h3>
                                    <small class="text-muted">n = {{ count($result['criteria_names']) }}</small>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="card {{ $result['is_consistent'] ? 'bg-success' : 'bg-danger' }} text-white">
                                <div class="card-body text-center">
                                    <h6>Consistency Ratio (CR)</h6>
                                    <h3>{{ number_format($result['cr'], 6) }}</h3>
                                    <span class="badge {{ $result['is_consistent'] ? 'bg-light text-success' : 'bg-light text-danger' }}">
                                        {{ $result['is_consistent'] ? '✓ KONSISTEN (CR < 0.1)' : '✗ TIDAK KONSISTEN' }}
                                    </span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- 4. Hasil Bobot Kriteria -->
    <div class="row mb-4">
        <div class="col-md-6">
            <div class="card">
                <div class="card-header bg-primary text-white">
                    <h5 class="mb-0"><i class="fas fa-chart-pie"></i> 4. Hasil Bobot Kriteria</h5>
                </div>
                <div class="card-body">
                    <table class="table table-striped">
                        <thead>
                            <tr>
                                <th>No</th>
                                <th>Kriteria</th>
                                <th>Bobot</th>
                                <th>Persentase</th>
                            </tr>
                        </thead>
                        <tbody>
                            @php $no = 1; @endphp
                            @foreach($result['weights_by_name'] as $name => $weight)
                                <tr>
                                    <td>{{ $no++ }}</td>
                                    <td><strong>{{ strtoupper($name) }}</strong></td>
                                    <td>{{ number_format($weight, 4) }}</td>
                                    <td>
                                        <div class="progress" style="height: 20px;">
                                            <div class="progress-bar bg-primary" style="width: {{ $weight * 100 }}%">
                                                {{ number_format($weight * 100, 1) }}%
                                            </div>
                                        </div>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
        <div class="col-md-6">
            <div class="card">
                <div class="card-header bg-dark text-white">
                    <h5 class="mb-0"><i class="fas fa-info-circle"></i> Keterangan</h5>
                </div>
                <div class="card-body">
                    <div class="alert {{ $result['is_consistent'] ? 'alert-success' : 'alert-danger' }}">
                        @if($result['is_consistent'])
                            <h5><i class="fas fa-check-circle"></i> Matriks Konsisten!</h5>
                            <p>Nilai CR = {{ number_format($result['cr'], 6) }} < 0.1</p>
                            <p>Perbandingan antar kriteria sudah konsisten dan bobot dapat digunakan.</p>
                        @else
                            <h5><i class="fas fa-times-circle"></i> Matriks Tidak Konsisten!</h5>
                            <p>Nilai CR = {{ number_format($result['cr'], 6) }} ≥ 0.1</p>
                            <p>Silakan periksa dan perbaiki nilai perbandingan Anda.</p>
                        @endif
                    </div>
                    
                    <h6>Rumus yang digunakan:</h6>
                    <ul>
                        <li><strong>Normalisasi:</strong> elemen / total kolom</li>
                        <li><strong>Bobot:</strong> rata-rata baris matriks ternormalisasi</li>
                        <li><strong>λmax:</strong> Σ(total kolom × bobot)</li>
                        <li><strong>CI:</strong> (λmax - n) / (n - 1)</li>
                        <li><strong>CR:</strong> CI / RI</li>
                    </ul>
                </div>
            </div>
        </div>
    </div>

    <!-- Buttons -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="card">
                <div class="card-body d-flex justify-content-center gap-3">
                    <a href="{{ route('admin.ahp.perbandingan') }}" class="btn btn-secondary btn-lg">
                        <i class="fas fa-arrow-left"></i> Kembali ke Perbandingan
                    </a>
                    
                    @if($result['is_consistent'])
                        <form action="{{ route('admin.ahp.prosesHitung') }}" method="POST" class="d-inline">
                            @csrf
                            <button type="submit" class="btn btn-success btn-lg">
                                <i class="fas fa-save"></i> Simpan Bobot Kriteria
                            </button>
                        </form>
                        
                        <a href="{{ route('admin.ahp.ranking') }}" class="btn btn-primary btn-lg">
                            <i class="fas fa-trophy"></i> Lihat Ranking (SAW)
                        </a>
                    @endif
                </div>
            </div>
        </div>
    </div>

    @else
        <div class="alert alert-warning">
            <h5><i class="fas fa-exclamation-triangle"></i> Belum ada data perhitungan</h5>
            <p>Silakan isi perbandingan kriteria terlebih dahulu.</p>
            <a href="{{ route('admin.ahp.perbandingan') }}" class="btn btn-primary">
                <i class="fas fa-balance-scale"></i> Isi Perbandingan
            </a>
        </div>
    @endif

</div>
@endsection

@push('styles')
<style>
    .table-sm td, .table-sm th {
        padding: 0.5rem;
        font-size: 0.9rem;
    }
    .progress {
        border-radius: 10px;
    }
    .progress-bar {
        font-size: 0.75rem;
        font-weight: bold;
    }
</style>
@endpush