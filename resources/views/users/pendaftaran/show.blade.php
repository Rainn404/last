@extends('layouts.app')

@section('title', 'Detail Pendaftaran - HIMA-TI')

@section('content')
<link rel="stylesheet" href="{{ asset('css/glassmorphism.css') }}">

<style>
    body {
        background-image: url('/logo_bg/gedung politala');
        background-attachment: fixed;
        background-position: center;
        background-repeat: no-repeat;
        background-size: cover;
    }

    body::before {
        content: '';
        position: fixed;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        background: linear-gradient(rgba(15, 23, 42, 0.55), rgba(49, 46, 129, 0.25));
        pointer-events: none;
        z-index: 0;
    }

    main, section {
        position: relative;
        z-index: 2;
    }

    .container {
        max-width: 1200px;
        margin: 0 auto;
        padding: 80px 20px;
        position: relative;
        z-index: 2;
    }

    .max-w-4xl {
        max-width: 56rem;
    }

    .bg-white {
        background: rgba(255, 255, 255, 0.18);
        backdrop-filter: blur(8px);
        border: 1px solid rgba(224, 231, 255, 0.30);
        border-radius: 16px;
        box-shadow: 0 8px 32px rgba(2, 6, 23, 0.15);
    }

    h1, h3 {
        color: #F8FAFC;
        font-weight: 700;
    }

    dt {
        color: #94A3B8 !important;
    }

    dd {
        color: #F8FAFC !important;
    }

    .bg-gray-50 {
        background: rgba(255, 255, 255, 0.1);
        border: 1px solid rgba(224, 231, 255, 0.30);
        border-radius: 8px;
    }

    .bg-gray-50 p {
        color: #CBD5E1;
    }

    .px-3, .px-2 {
        padding-left: 0.75rem;
        padding-right: 0.75rem;
    }

    .rounded-full {
        border-radius: 9999px;
    }

    .bg-green-100 {
        background: rgba(34, 197, 94, 0.2);
        color: #86EFAC;
        border: 1px solid rgba(34, 197, 94, 0.3);
    }

    .bg-yellow-100 {
        background: rgba(250, 204, 21, 0.2);
        color: #FBBF24;
        border: 1px solid rgba(250, 204, 21, 0.3);
    }

    .bg-red-100 {
        background: rgba(220, 38, 38, 0.2);
        color: #FCA5A5;
        border: 1px solid rgba(220, 38, 38, 0.3);
    }

    a {
        text-decoration: none;
        color: #A5B4FC;
        transition: all 0.3s ease;
    }

    a:hover {
        color: #E0E7FF;
    }

    .bg-gray-300, .bg-blue-600 {
        background: rgba(255, 255, 255, 0.15) !important;
        border: 1px solid rgba(224, 231, 255, 0.30);
        color: #F8FAFC !important;
    }

    a.bg-blue-600 {
        background: #6366F1 !important;
        color: #FFFFFF !important;
    }

    a.bg-blue-600:hover {
        background: #4F46E5 !important;
        box-shadow: 0 6px 20px rgba(99, 102, 241, 0.35);
    }

    a.bg-gray-300:hover {
        background: rgba(255, 255, 255, 0.25) !important;
    }

    .text-blue-600 {
        color: #A5B4FC !important;
    }

    .text-blue-600:hover {
        color: #E0E7FF !important;
    }
</style>

<div class="container mx-auto px-4 py-8">
    <div class="max-w-4xl mx-auto">
        <div class="bg-white rounded-lg shadow-md p-6">
            <div class="flex justify-between items-center mb-6">
                <h1 class="text-2xl font-bold text-gray-800">Detail Pendaftaran</h1>
                <span class="px-3 py-1 rounded-full text-sm font-semibold {{ $pendaftaran->status_badge }}">
                    {{ $pendaftaran->status_label }}
                </span>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <h3 class="text-lg font-semibold text-gray-700 mb-4">Data Pribadi</h3>
                    <dl class="space-y-3">
                        <div>
                            <dt class="text-sm font-medium text-gray-500">Nama Lengkap</dt>
                            <dd class="text-sm text-gray-900">{{ $pendaftaran->nama }}</dd>
                        </div>
                        <div>
                            <dt class="text-sm font-medium text-gray-500">NIM</dt>
                            <dd class="text-sm text-gray-900">{{ $pendaftaran->nim }}</dd>
                        </div>
                        <div>
                            <dt class="text-sm font-medium text-gray-500">Semester</dt>
                            <dd class="text-sm text-gray-900">Semester {{ $pendaftaran->semester }}</dd>
                        </div>
                        <div>
                            <dt class="text-sm font-medium text-gray-500">No. HP</dt>
                            <dd class="text-sm text-gray-900">{{ $pendaftaran->no_hp }}</dd>
                        </div>
                    </dl>
                </div>

                <div>
                    <h3 class="text-lg font-semibold text-gray-700 mb-4">Informasi Pendaftaran</h3>
                    <dl class="space-y-3">
                        <div>
                            <dt class="text-sm font-medium text-gray-500">Tanggal Daftar</dt>
                            <dd class="text-sm text-gray-900">{{ $pendaftaran->submitted_at->format('d/m/Y H:i') }}</dd>
                        </div>
                        <div>
                            <dt class="text-sm font-medium text-gray-500">Status</dt>
                            <dd class="text-sm">
                                <span class="px-2 py-1 rounded-full {{ $pendaftaran->status_badge }}">
                                    {{ $pendaftaran->status_label }}
                                </span>
                            </dd>
                        </div>
                        @if($pendaftaran->dokumen)
                        <div>
                            <dt class="text-sm font-medium text-gray-500">Dokumen</dt>
                            <dd class="text-sm text-gray-900">
                                <a href="{{ Storage::url($pendaftaran->dokumen) }}" target="_blank" class="text-blue-600 hover:text-blue-800">
                                    <i class="fas fa-download mr-1"></i> Download Dokumen
                                </a>
                            </dd>
                        </div>
                        @endif
                    </dl>
                </div>
            </div>

            <div class="mt-6">
                <h3 class="text-lg font-semibold text-gray-700 mb-4">Alasan Bergabung</h3>
                <div class="bg-gray-50 p-4 rounded-lg">
                    <p class="text-sm text-gray-700">{{ $pendaftaran->alasan_mendaftar }}</p>
                </div>
            </div>

            <div class="mt-6 flex justify-end space-x-3">
                <a href="{{ route('pendaftaran.index') }}" class="bg-gray-300 hover:bg-gray-400 text-gray-800 px-4 py-2 rounded-md">
                    Kembali
                </a>
                <a href="{{ route('pendaftaran.edit', $pendaftaran->id_pendaftaran) }}" class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-md">
                    Edit
                </a>
            </div>
        </div>
    </div>
</div>
@endsection