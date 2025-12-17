@extends('layouts.admin.app')

@section('title', 'Dashboard AHP')

@section('content')

{{-- ====== PERBAIKAN PANAH PAGINATION ====== --}}
<style>
    /* Untuk mengecilkan panah besar yang muncul di halaman */
    .pagination-arrow {
        width: 32px !important;
        height: 32px !important;
        cursor: pointer;
        object-fit: contain;
    }

    /* Kalau panah itu berupa SVG / FontAwesome */
    .pagination-arrow svg,
    .pagination-arrow img {
        width: 100% !important;
        height: 100% !important;
    }

    /* Jika panah berasal dari Bootstrap page-link */
    .page-link {
        padding: 4px 10px !important;
        font-size: 14px !important;
    }
</style>

<div class="container-fluid">
    <div class="row">
        <div class="col-12">

            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">
                        <i class="fas fa-calculator mr-2"></i>
                        Sistem Analytical Hierarchy Process (AHP)
                    </h3>
                </div>

                <div class="card-body">

                    {{-- INFORMASI AHP --}}
                    <div class="alert alert-info">
                        <h5><i class="fas fa-info-circle mr-2"></i> Tentang AHP</h5>
                        <p>
                            Analytical Hierarchy Process (AHP) adalah metode untuk mengambil keputusan 
                            dengan membandingkan kriteria secara berpasangan.
                        </p>
                    </div>

                    {{-- MENU UTAMA --}}
                    <div class="row">

                        {{-- PERBANDINGAN --}}
                        <div class="col-md-4 col-sm-6 mb-3">
                            <div class="info-box">
                                <span class="info-box-icon bg-info elevation-1">
                                    <i class="fas fa-balance-scale"></i>
                                </span>
                                <div class="info-box-content">
                                    <span class="info-box-text">Perbandingan AHP</span>
                                    <a href="{{ route('admin.ahp.perbandingan') }}" class="info-box-number text-dark">
                                        Mulai Perbandingan
                                    </a>
                                </div>
                            </div>
                        </div>

                        {{-- HITUNG --}}
                        <div class="col-md-4 col-sm-6 mb-3">
                            <div class="info-box">
                                <span class="info-box-icon bg-success elevation-1">
                                    <i class="fas fa-calculator"></i>
                                </span>
                                <div class="info-box-content">
                                    <span class="info-box-text">Hitung AHP</span>
                                    <a href="{{ route('admin.ahp.hitung') }}" class="info-box-number text-dark">
                                        Proses Perhitungan
                                    </a>
                                </div>
                            </div>
                        </div>

                        {{-- HASIL --}}
                        <div class="col-md-4 col-sm-6 mb-3">
                            <div class="info-box">
                                <span class="info-box-icon bg-warning elevation-1">
                                    <i class="fas fa-chart-bar"></i>
                                </span>
                                <div class="info-box-content">
                                    <span class="info-box-text">Hasil & Ranking</span>
                                    <a href="{{ route('admin.ahp.hasil') }}" class="info-box-number text-dark">
                                        Lihat Hasil
                                    </a>
                                </div>
                            </div>
                        </div>

                    </div>

                    {{-- LANGKAH AHP --}}
                    <div class="row mt-4">
                        <div class="col-md-12">

                            <h5>Langkah-langkah AHP:</h5>
                            <ol>
                                <li><strong>Pastikan kriteria sudah terdaftar di sistem</strong></li>
                                <li><strong>Lakukan perbandingan berpasangan</strong> antar kriteria</li>
                                <li><strong>Hitung bobot kriteria</strong> menggunakan metode AHP</li>
                                <li><strong>Lihat hasil perhitungan dan ranking</strong></li>
                            </ol>

                            <div class="text-center mt-4">
                                <a href="{{ route('admin.ahp.perbandingan') }}" class="btn btn-primary btn-lg">
                                    <i class="fas fa-play mr-1"></i> Mulai AHP
                                </a>

                                <a href="{{ route('admin.criteria.index') }}" class="btn btn-success btn-lg ml-2">
                                    <i class="fas fa-list mr-1"></i> Lihat Kriteria
                                </a>
                            </div>

                        </div>
                    </div>

                </div> {{-- end card-body --}}
            </div>

        </div>
    </div>
</div>

@endsection