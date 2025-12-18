@extends('layouts.admin.app')

@section('title', 'Perbandingan AHP')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="card">

                <!-- ================= HEADER ================= -->
                <div class="card-header">
                    <h3 class="card-title">
                        <i class="fas fa-balance-scale"></i>
                        Perbandingan Kriteria AHP
                    </h3>
                </div>

                <!-- ================= BODY ================= -->
                <div class="card-body">

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

                    @if($kriteria->count() < 2)
                        <div class="alert alert-warning">
                            <i class="fas fa-exclamation-triangle"></i>
                            Minimal diperlukan 2 kriteria aktif untuk melakukan perbandingan.
                            <a href="{{ route('admin.criteria.index') }}" class="alert-link">Tambah Kriteria</a>
                        </div>
                    @else

                    <!-- Info Box -->
                    <div class="alert alert-info mb-4">
                        <h5><i class="fas fa-info-circle"></i> Petunjuk Pengisian</h5>
                        <p class="mb-2">Bandingkan <strong>Kriteria 1</strong> terhadap <strong>Kriteria 2</strong>:</p>
                        <ul class="mb-0">
                            <li><strong>1</strong> = Sama pentingnya</li>
                            <li><strong>3</strong> = Kriteria 1 sedikit lebih penting dari Kriteria 2</li>
                            <li><strong>5</strong> = Kriteria 1 lebih penting dari Kriteria 2</li>
                            <li><strong>7</strong> = Kriteria 1 sangat lebih penting dari Kriteria 2</li>
                            <li><strong>9</strong> = Kriteria 1 mutlak lebih penting dari Kriteria 2</li>
                            <li><strong>1/3, 1/5, 1/7, 1/9</strong> = Kebalikannya (Kriteria 2 lebih penting)</li>
                        </ul>
                    </div>

                    <!-- Progress Info -->
                    <div class="row mb-4">
                        <div class="col-md-4">
                            <div class="card bg-primary text-white">
                                <div class="card-body text-center">
                                    <h4>{{ $kriteria->count() }}</h4>
                                    <small>Total Kriteria</small>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="card bg-info text-white">
                                <div class="card-body text-center">
                                    <h4>{{ ($kriteria->count() * ($kriteria->count() - 1)) / 2 }}</h4>
                                    <small>Pasangan Perbandingan</small>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="card bg-success text-white">
                                <div class="card-body text-center">
                                    <h4>{{ $terisi ?? 0 }}</h4>
                                    <small>Sudah Terisi</small>
                                </div>
                            </div>
                        </div>
                    </div>

                    <form method="POST" action="{{ route('admin.ahp.storePerbandingan') }}" id="formPerbandingan">
                        @csrf

                        <div class="table-responsive">
                            <table class="table table-bordered table-hover align-middle">
                                <thead class="table-dark">
                                    <tr>
                                        <th width="5%" class="text-center">#</th>
                                        <th width="25%">Kriteria 1</th>
                                        <th width="25%">Kriteria 2</th>
                                        <th width="35%">Nilai Perbandingan</th>
                                        <th width="10%" class="text-center">Keterangan</th>
                                    </tr>
                                </thead>

                                <tbody>
                                @php 
                                    $no = 1; 
                                    $kriteriaArray = $kriteria->values();
                                @endphp

                                {{-- Loop untuk pasangan unik (segitiga atas matriks) --}}
                                @for($i = 0; $i < count($kriteriaArray); $i++)
                                    @for($j = $i + 1; $j < count($kriteriaArray); $j++)
                                        @php
                                            $krit1 = $kriteriaArray[$i];
                                            $krit2 = $kriteriaArray[$j];
                                            $key = $krit1->id_criterion . '_' . $krit2->id_criterion;
                                            $existingValue = $perbandingan[$key]->value ?? ($perbandingan[$key]->nilai ?? null);
                                        @endphp
                                        <tr>
                                            <!-- NO -->
                                            <td class="text-center">{{ $no++ }}</td>

                                            <!-- KRITERIA 1 -->
                                            <td>
                                                <span class="badge bg-primary fs-6">{{ $krit1->code }}</span>
                                                <strong class="ms-2">{{ $krit1->name }}</strong>
                                            </td>

                                            <!-- KRITERIA 2 (OTOMATIS TERISI) -->
                                            <td>
                                                <span class="badge bg-secondary fs-6">{{ $krit2->code }}</span>
                                                <strong class="ms-2">{{ $krit2->name }}</strong>
                                                <input type="hidden" name="pairs[{{ $no - 1 }}][krit1]" value="{{ $krit1->id_criterion }}">
                                                <input type="hidden" name="pairs[{{ $no - 1 }}][krit2]" value="{{ $krit2->id_criterion }}">
                                            </td>

                                            <!-- PERBANDINGAN -->
                                            <td>
                                                <select name="pairs[{{ $no - 1 }}][nilai]"
                                                        class="form-select nilai-select"
                                                        required>
                                                    <option value="">-- Pilih Nilai --</option>
                                                    <optgroup label="Kriteria 1 Lebih Penting">
                                                        <option value="9" {{ $existingValue == 9 ? 'selected' : '' }}>9 - Mutlak lebih penting</option>
                                                        <option value="8" {{ $existingValue == 8 ? 'selected' : '' }}>8</option>
                                                        <option value="7" {{ $existingValue == 7 ? 'selected' : '' }}>7 - Sangat lebih penting</option>
                                                        <option value="6" {{ $existingValue == 6 ? 'selected' : '' }}>6</option>
                                                        <option value="5" {{ $existingValue == 5 ? 'selected' : '' }}>5 - Lebih penting</option>
                                                        <option value="4" {{ $existingValue == 4 ? 'selected' : '' }}>4</option>
                                                        <option value="3" {{ $existingValue == 3 ? 'selected' : '' }}>3 - Sedikit lebih penting</option>
                                                        <option value="2" {{ $existingValue == 2 ? 'selected' : '' }}>2</option>
                                                    </optgroup>
                                                    <optgroup label="Sama Penting">
                                                        <option value="1" {{ $existingValue == 1 ? 'selected' : '' }}>1 - Sama penting</option>
                                                    </optgroup>
                                                    <optgroup label="Kriteria 2 Lebih Penting">
                                                        <option value="0.5" {{ $existingValue == 0.5 ? 'selected' : '' }}>1/2</option>
                                                        <option value="0.333333" {{ ($existingValue >= 0.33 && $existingValue <= 0.34) ? 'selected' : '' }}>1/3 - Sedikit kurang penting</option>
                                                        <option value="0.25" {{ $existingValue == 0.25 ? 'selected' : '' }}>1/4</option>
                                                        <option value="0.2" {{ $existingValue == 0.2 ? 'selected' : '' }}>1/5 - Kurang penting</option>
                                                        <option value="0.166667" {{ ($existingValue >= 0.16 && $existingValue <= 0.17) ? 'selected' : '' }}>1/6</option>
                                                        <option value="0.142857" {{ ($existingValue >= 0.14 && $existingValue <= 0.15) ? 'selected' : '' }}>1/7 - Sangat kurang penting</option>
                                                        <option value="0.125" {{ $existingValue == 0.125 ? 'selected' : '' }}>1/8</option>
                                                        <option value="0.111111" {{ ($existingValue >= 0.11 && $existingValue <= 0.12) ? 'selected' : '' }}>1/9 - Mutlak kurang penting</option>
                                                    </optgroup>
                                                </select>
                                            </td>

                                            <!-- KETERANGAN -->
                                            <td class="text-center">
                                                <span class="keterangan-badge badge bg-light text-dark">-</span>
                                            </td>
                                        </tr>
                                    @endfor
                                @endfor
                                </tbody>
                            </table>
                        </div>

                        <!-- ================= BUTTON AREA ================= -->
                        <div class="d-flex justify-content-center gap-3 mt-4">

                            <!-- SIMPAN -->
                            <button type="submit" class="btn btn-primary btn-lg px-5">
                                <i class="fas fa-save"></i> Simpan Perbandingan
                            </button>

                            <!-- HITUNG AHP -->
                            <a href="{{ route('admin.ahp.hitung') }}"
                               class="btn btn-success btn-lg px-5">
                                <i class="fas fa-calculator"></i> Hitung AHP
                            </a>

                        </div>

                    </form>
                    @endif

                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Update keterangan badge saat nilai dipilih
    document.querySelectorAll('.nilai-select').forEach(function(select) {
        select.addEventListener('change', function() {
            const row = this.closest('tr');
            const badge = row.querySelector('.keterangan-badge');
            const value = parseFloat(this.value);
            
            if (!value) {
                badge.textContent = '-';
                badge.className = 'keterangan-badge badge bg-light text-dark';
                return;
            }
            
            if (value > 1) {
                badge.textContent = 'K1 > K2';
                badge.className = 'keterangan-badge badge bg-primary';
            } else if (value === 1) {
                badge.textContent = 'K1 = K2';
                badge.className = 'keterangan-badge badge bg-warning text-dark';
            } else {
                badge.textContent = 'K1 < K2';
                badge.className = 'keterangan-badge badge bg-secondary';
            }
        });
        
        // Trigger untuk nilai yang sudah ada
        select.dispatchEvent(new Event('change'));
    });
});
</script>
@endpush
