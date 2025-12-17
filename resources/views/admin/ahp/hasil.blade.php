@extends('layouts.admin.app')

@section('title', 'Hasil Perhitungan AHP - Admin')

@section('content')
<div class="container-fluid">
    <!-- Page Heading -->
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <h1 class="h3 mb-0 text-gray-800">Hasil Perhitungan AHP</h1>
        <div>
            <a href="{{ route('admin.ahp.perbandingan') }}" class="btn btn-outline-primary btn-sm">
                <i class="fas fa-edit me-1"></i> Edit Perbandingan
            </a>
            <a href="{{ route('admin.ahp.ranking') }}" class="btn btn-success btn-sm">
                <i class="fas fa-trophy me-1"></i> Lihat Ranking SAW
            </a>
        </div>
    </div>

    @if(session('success'))
    <div class="alert alert-success alert-dismissible fade show" role="alert">
        <i class="fas fa-check-circle me-2"></i>{{ session('success') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
    @endif

    <!-- Bobot Kriteria Summary -->
    <div class="row mb-4">
        @foreach($kriteria as $krit)
        <div class="col-md-3 mb-3">
            <div class="card border-left-primary shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-primary text-uppercase mb-1">
                                {{ $krit->name }}
                            </div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">
                                {{ number_format(($result['weights'][$krit->id] ?? 0) * 100, 2) }}%
                            </div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-weight fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        @endforeach
    </div>

    <!-- Konsistensi -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="card shadow">
                <div class="card-header py-3 bg-success text-white">
                    <h6 class="m-0 font-weight-bold">
                        <i class="fas fa-check-circle me-2"></i>Rasio Konsistensi
                    </h6>
                </div>
                <div class="card-body">
                    <div class="row text-center">
                        <div class="col-md-3">
                            <div class="border rounded p-3">
                                <h6 class="text-muted small">Lambda Max (λmax)</h6>
                                <h4 class="text-primary mb-0">{{ number_format($result['lambda_max'], 6) }}</h4>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="border rounded p-3">
                                <h6 class="text-muted small">Consistency Index (CI)</h6>
                                <h4 class="text-warning mb-0">{{ number_format($result['ci'], 6) }}</h4>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="border rounded p-3">
                                <h6 class="text-muted small">Random Index (RI)</h6>
                                <h4 class="text-info mb-0">{{ $result['ri'] }}</h4>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="border rounded p-3 {{ $result['cr'] < 0.1 ? 'bg-success text-white' : 'bg-danger text-white' }}">
                                <h6 class="small">Consistency Ratio (CR)</h6>
                                <h4 class="mb-0">{{ number_format($result['cr'], 6) }}</h4>
                                <small>{{ $result['cr'] < 0.1 ? '✓ KONSISTEN' : '✗ TIDAK KONSISTEN' }}</small>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Matriks Perbandingan -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="card shadow">
                <div class="card-header py-3 bg-primary text-white">
                    <h6 class="m-0 font-weight-bold">
                        <i class="fas fa-table me-2"></i>Matriks Perbandingan Berpasangan
                    </h6>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-bordered table-sm">
                            <thead class="table-light">
                                <tr>
                                    <th class="text-center" style="min-width: 120px;">Kriteria</th>
                                    @foreach($kriteria as $krit)
                                    <th class="text-center">{{ strtoupper($krit->name) }}</th>
                                    @endforeach
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($kriteria as $i => $krit1)
                                <tr>
                                    <td class="font-weight-bold text-center bg-light">{{ strtoupper($krit1->name) }}</td>
                                    @foreach($kriteria as $j => $krit2)
                                    <td class="text-center {{ $i == $j ? 'bg-warning-subtle' : '' }}">
                                        {{ number_format($result['matrix'][$i][$j] ?? 1, 4) }}
                                    </td>
                                    @endforeach
                                </tr>
                                @endforeach
                                <tr class="table-secondary">
                                    <td class="font-weight-bold text-center">TOTAL</td>
                                    @foreach($result['column_sums'] as $sum)
                                    <td class="text-center font-weight-bold">{{ number_format($sum, 4) }}</td>
                                    @endforeach
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Matriks Ternormalisasi -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="card shadow">
                <div class="card-header py-3 bg-info text-white">
                    <h6 class="m-0 font-weight-bold">
                        <i class="fas fa-percentage me-2"></i>Matriks Perbandingan Ternormalisasi
                    </h6>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-bordered table-sm">
                            <thead class="table-light">
                                <tr>
                                    <th class="text-center" style="min-width: 120px;">Kriteria</th>
                                    @foreach($kriteria as $krit)
                                    <th class="text-center">{{ strtoupper($krit->name) }}</th>
                                    @endforeach
                                    <th class="text-center bg-warning">BOBOT</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($kriteria as $i => $krit1)
                                <tr>
                                    <td class="font-weight-bold text-center bg-light">{{ strtoupper($krit1->name) }}</td>
                                    @foreach($kriteria as $j => $krit2)
                                    <td class="text-center">
                                        {{ number_format($result['normalized_matrix'][$i][$j] ?? 0, 4) }}
                                    </td>
                                    @endforeach
                                    <td class="text-center bg-warning-subtle font-weight-bold">
                                        {{ number_format($result['weights'][$krit1->id] ?? 0, 2) }}
                                    </td>
                                </tr>
                                @endforeach
                                <tr class="table-secondary">
                                    <td class="font-weight-bold text-center">TOTAL</td>
                                    @for($j = 0; $j < count($kriteria); $j++)
                                    <td class="text-center font-weight-bold">1.0000</td>
                                    @endfor
                                    <td class="text-center font-weight-bold bg-warning">1.00</td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Keterangan -->
    <div class="row">
        <div class="col-12">
            <div class="card shadow">
                <div class="card-header py-3 bg-dark text-white">
                    <h6 class="m-0 font-weight-bold">
                        <i class="fas fa-info-circle me-2"></i>Keterangan
                    </h6>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6">
                            <h6 class="font-weight-bold">Rumus yang Digunakan:</h6>
                            <ul class="mb-0">
                                <li><strong>Normalisasi:</strong> elemen / total kolom</li>
                                <li><strong>Bobot:</strong> rata-rata baris matriks ternormalisasi</li>
                                <li><strong>λmax:</strong> Σ(total kolom × bobot)</li>
                                <li><strong>CI:</strong> (λmax - n) / (n - 1)</li>
                                <li><strong>CR:</strong> CI / RI</li>
                            </ul>
                        </div>
                        <div class="col-md-6">
                            <h6 class="font-weight-bold">Interpretasi:</h6>
                            <ul class="mb-0">
                                <li>CR < 0.1 → Perbandingan <span class="text-success font-weight-bold">KONSISTEN</span></li>
                                <li>CR ≥ 0.1 → Perbandingan <span class="text-danger font-weight-bold">TIDAK KONSISTEN</span>, perlu direvisi</li>
                                <li>Bobot menunjukkan tingkat kepentingan relatif kriteria</li>
                                <li>Total bobot harus = 1.00 (100%)</li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
.border-left-primary {
    border-left: 4px solid #4e73df !important;
}
.bg-warning-subtle {
    background-color: #fff3cd !important;
}
</style>
@endsection
