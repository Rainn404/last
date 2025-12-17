@extends('layouts.admin.app')

@section('title', 'Ranking Mahasiswa - SAW')

@section('content')
<div class="container-fluid">
    
    <!-- Header -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="card" style="background: linear-gradient(135deg, #1cc88a 0%, #13855c 100%);">
                <div class="card-body text-white">
                    <h3 class="text-white"><i class="fas fa-trophy"></i> Ranking Mahasiswa - Metode SAW</h3>
                    <p class="mb-0 text-white">Simple Additive Weighting - Perangkingan berdasarkan bobot kriteria AHP</p>
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

    @if(isset($kriteria) && $kriteria->count() > 0)

    <!-- Bobot Kriteria dari AHP -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="card">
                <div class="card-header bg-primary text-white">
                    <h5 class="mb-0"><i class="fas fa-weight"></i> Bobot Kriteria (Hasil AHP)</h5>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-bordered text-center">
                            <thead class="table-dark">
                                <tr>
                                    <th>KRITERIA</th>
                                    @foreach($kriteria as $krit)
                                        <th>{{ strtoupper($krit->name) }}</th>
                                    @endforeach
                                    <th>TOTAL</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <th class="table-secondary">BOBOT</th>
                                    @php $totalBobot = 0; @endphp
                                    @foreach($kriteria as $krit)
                                        @php $totalBobot += $krit->weight ?? 0; @endphp
                                        <td class="table-warning fw-bold">{{ number_format($krit->weight ?? 0, 2) }}</td>
                                    @endforeach
                                    <td class="table-success fw-bold">{{ number_format($totalBobot, 2) }}</td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>

    @if(isset($alternatif) && count($alternatif) > 0)

    <!-- 1. Data Alternatif (Nilai Asli) -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="card">
                <div class="card-header bg-info text-white">
                    <h5 class="mb-0"><i class="fas fa-database"></i> 1. Data Alternatif (Nilai Asli)</h5>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-bordered table-striped table-sm">
                            <thead class="table-dark">
                                <tr>
                                    <th>NAMA</th>
                                    @foreach($kriteria as $krit)
                                        <th class="text-center">{{ strtoupper($krit->name) }}</th>
                                    @endforeach
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($alternatif as $i => $alt)
                                    <tr>
                                        <td><strong>{{ $alt['nama'] }}</strong></td>
                                        @foreach($kriteria as $krit)
                                            <td class="text-center">{{ $alt['nilai'][$krit->id] ?? '-' }}</td>
                                        @endforeach
                                    </tr>
                                @endforeach
                            </tbody>
                            <tfoot class="table-secondary">
                                <tr>
                                    <th>MAX</th>
                                    @foreach($kriteria as $krit)
                                        <td class="text-center fw-bold">{{ $maxValues[$krit->id] ?? '-' }}</td>
                                    @endforeach
                                </tr>
                            </tfoot>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- 2. Matriks Normalisasi SAW -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="card">
                <div class="card-header bg-warning text-dark">
                    <h5 class="mb-0"><i class="fas fa-percentage"></i> 2. Matriks Normalisasi SAW</h5>
                </div>
                <div class="card-body">
                    <div class="alert alert-info mb-3">
                        <strong>Rumus Normalisasi (Benefit):</strong> r = nilai / max(nilai)
                    </div>
                    <div class="table-responsive">
                        <table class="table table-bordered table-sm">
                            <thead class="table-dark">
                                <tr>
                                    <th>NAMA</th>
                                    @foreach($kriteria as $krit)
                                        <th class="text-center">{{ strtoupper($krit->name) }}</th>
                                    @endforeach
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($alternatif as $i => $alt)
                                    <tr>
                                        <td><strong>{{ $alt['nama'] }}</strong></td>
                                        @foreach($kriteria as $krit)
                                            <td class="text-center">{{ number_format($normalisasi[$i][$krit->id] ?? 0, 4) }}</td>
                                        @endforeach
                                    </tr>
                                @endforeach
                            </tbody>
                            <tfoot class="table-primary">
                                <tr>
                                    <th>BOBOT KRITERIA</th>
                                    @foreach($kriteria as $krit)
                                        <td class="text-center fw-bold">{{ number_format($krit->weight ?? 0, 2) }}</td>
                                    @endforeach
                                </tr>
                            </tfoot>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- 3. Perhitungan Nilai Akhir SAW -->
    <div class="row mb-4">
        <div class="col-md-5">
            <div class="card h-100">
                <div class="card-header bg-success text-white">
                    <h5 class="mb-0"><i class="fas fa-calculator"></i> 3. Nilai SAW</h5>
                </div>
                <div class="card-body">
                    <div class="alert alert-info mb-3">
                        <strong>Rumus:</strong> V = Σ (normalisasi × bobot)
                    </div>
                    <div class="table-responsive">
                        <table class="table table-bordered table-striped table-sm">
                            <thead class="table-dark">
                                <tr>
                                    <th>NAMA</th>
                                    <th class="text-center">NILAI</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($ranking as $rank)
                                    <tr>
                                        <td>{{ $rank['nama'] }}</td>
                                        <td class="text-center table-warning fw-bold">{{ number_format($rank['nilai'], 4) }}</td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-7">
            <div class="card h-100">
                <div class="card-header bg-primary text-white">
                    <h5 class="mb-0"><i class="fas fa-trophy"></i> 4. Hasil Ranking</h5>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-hover">
                            <thead class="table-dark">
                                <tr>
                                    <th class="text-center">RANK</th>
                                    <th>NAMA</th>
                                    <th class="text-center">NILAI</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($ranking as $i => $rank)
                                    <tr class="{{ $i < 3 ? 'table-success' : '' }}">
                                        <td class="text-center">
                                            @if($i == 0)
                                                <span class="badge bg-warning text-dark fs-5">🥇 1</span>
                                            @elseif($i == 1)
                                                <span class="badge bg-secondary fs-5">🥈 2</span>
                                            @elseif($i == 2)
                                                <span class="badge bg-danger fs-5">🥉 3</span>
                                            @else
                                                <span class="badge bg-dark fs-6">{{ $i + 1 }}</span>
                                            @endif
                                        </td>
                                        <td><strong>{{ $rank['nama'] }}</strong></td>
                                        <td class="text-center">
                                            <span class="badge bg-primary fs-6">{{ number_format($rank['nilai'], 4) }}</span>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Summary Card -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="card">
                <div class="card-header bg-dark text-white">
                    <h5 class="mb-0"><i class="fas fa-info-circle"></i> Ringkasan Perhitungan</h5>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6">
                            <h6>Metode yang Digunakan:</h6>
                            <ol>
                                <li><strong>AHP (Analytical Hierarchy Process)</strong> - Menentukan bobot kriteria melalui perbandingan berpasangan</li>
                                <li><strong>SAW (Simple Additive Weighting)</strong> - Perangkingan alternatif berdasarkan bobot</li>
                            </ol>
                            
                            <h6 class="mt-3">Rumus SAW:</h6>
                            <ul>
                                <li><strong>Normalisasi Benefit:</strong> r<sub>ij</sub> = x<sub>ij</sub> / max(x<sub>j</sub>)</li>
                                <li><strong>Normalisasi Cost:</strong> r<sub>ij</sub> = min(x<sub>j</sub>) / x<sub>ij</sub></li>
                                <li><strong>Nilai Preferensi:</strong> V<sub>i</sub> = Σ w<sub>j</sub> × r<sub>ij</sub></li>
                            </ul>
                        </div>
                        <div class="col-md-6">
                            <h6>Top 3 Mahasiswa Berprestasi:</h6>
                            <div class="list-group">
                                @foreach(array_slice($ranking, 0, 3) as $i => $top)
                                    <div class="list-group-item d-flex justify-content-between align-items-center {{ $i == 0 ? 'list-group-item-warning' : ($i == 1 ? 'list-group-item-secondary' : 'list-group-item-danger') }}">
                                        <span>
                                            @if($i == 0) 🥇 @elseif($i == 1) 🥈 @else 🥉 @endif
                                            <strong>{{ $top['nama'] }}</strong>
                                        </span>
                                        <span class="badge bg-primary rounded-pill">{{ number_format($top['nilai'], 4) }}</span>
                                    </div>
                                @endforeach
                            </div>
                            
                            <div class="mt-3">
                                <p><strong>Total Kriteria:</strong> {{ $kriteria->count() }}</p>
                                <p><strong>Total Alternatif:</strong> {{ count($alternatif) }}</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    @else
        <div class="alert alert-warning">
            <h5><i class="fas fa-exclamation-triangle"></i> Belum ada data alternatif</h5>
            <p>Tidak ada data mahasiswa untuk dihitung.</p>
        </div>
    @endif

    @else
        <div class="alert alert-warning">
            <h5><i class="fas fa-exclamation-triangle"></i> Bobot kriteria belum tersedia</h5>
            <p>Silakan hitung bobot AHP terlebih dahulu.</p>
            <a href="{{ route('admin.ahp.hitung') }}" class="btn btn-primary">
                <i class="fas fa-calculator"></i> Hitung AHP
            </a>
        </div>
    @endif

    <!-- Buttons -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="card">
                <div class="card-body d-flex justify-content-center gap-3">
                    <a href="{{ route('admin.ahp.hitung') }}" class="btn btn-secondary btn-lg">
                        <i class="fas fa-arrow-left"></i> Kembali ke Perhitungan AHP
                    </a>
                    <a href="{{ route('admin.ahp.perbandingan') }}" class="btn btn-info btn-lg">
                        <i class="fas fa-balance-scale"></i> Edit Perbandingan
                    </a>
                </div>
            </div>
        </div>
    </div>

</div>
@endsection

@push('styles')
<style>
    .fs-5 { font-size: 1.1rem !important; }
    .fs-6 { font-size: 0.95rem !important; }
    .table-sm td, .table-sm th {
        padding: 0.4rem;
        font-size: 0.85rem;
    }
</style>
@endpush
