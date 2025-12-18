@extends('layouts.app')

@section('title', 'Edit Pendaftaran - HIMA-TI')

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

    h1 {
        color: #F8FAFC;
        font-weight: 700;
    }

    .block {
        display: block;
    }

    label {
        color: #F8FAFC;
        font-weight: 600;
    }

    input[type="text"],
    input[type="tel"],
    input[type="file"],
    select,
    textarea {
        background: rgba(255, 255, 255, 0.15);
        border: 2px solid rgba(224, 231, 255, 0.30);
        color: #F8FAFC;
        border-radius: 8px;
        padding: 10px;
        transition: all 0.3s ease;
        backdrop-filter: blur(4px);
    }

    input[type="text"]::placeholder,
    input[type="tel"]::placeholder,
    select::placeholder,
    textarea::placeholder {
        color: #94A3B8;
    }

    input[type="text"]:focus,
    input[type="tel"]:focus,
    input[type="file"]:focus,
    select:focus,
    textarea:focus {
        outline: none;
        border-color: #6366F1;
        background: rgba(255, 255, 255, 0.25);
        box-shadow: 0 0 0 3px rgba(99, 102, 241, 0.2);
    }

    select option {
        background-color: #1E293B;
        color: #F8FAFC;
    }

    .text-red-500 {
        color: #FCA5A5;
    }

    .text-gray-600, .text-xs, .text-sm {
        color: #CBD5E1;
    }

    a {
        color: #A5B4FC;
        transition: all 0.3s ease;
    }

    a:hover {
        color: #E0E7FF;
    }

    .bg-gray-300, .bg-blue-600 {
        background: rgba(255, 255, 255, 0.15);
        border: 1px solid rgba(224, 231, 255, 0.30);
    }

    button[type="submit"],
    a.bg-blue-600 {
        background: #6366F1 !important;
        color: #FFFFFF !important;
    }

    button[type="submit"]:hover,
    a.bg-blue-600:hover {
        background: #4F46E5 !important;
        box-shadow: 0 6px 20px rgba(99, 102, 241, 0.35);
    }

    a.bg-gray-300 {
        background: rgba(255, 255, 255, 0.15);
        color: #F8FAFC !important;
        border: 1px solid rgba(224, 231, 255, 0.30);
    }

    a.bg-gray-300:hover {
        background: rgba(255, 255, 255, 0.25);
    }

    .text-blue-600 {
        color: #A5B4FC;
    }

    .text-blue-600:hover {
        color: #E0E7FF;
    }
</style>

<div class="container mx-auto px-4 py-8">
    <div class="max-w-4xl mx-auto">
        <div class="bg-white rounded-lg shadow-md p-6">
            <h1 class="text-2xl font-bold text-gray-800 mb-6">Edit Data Pendaftaran</h1>

            <form action="{{ route('pendaftaran.update', $pendaftaran->id_pendaftaran) }}" method="POST" enctype="multipart/form-data">
                @csrf
                @method('PUT')
                
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <!-- Nama -->
                    <div class="md:col-span-2">
                        <label for="nama" class="block text-sm font-medium text-gray-700">Nama Lengkap *</label>
                        <input type="text" id="nama" name="nama" value="{{ old('nama', $pendaftaran->nama) }}" 
                               class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm p-2 focus:ring-blue-500 focus:border-blue-500" required>
                        @error('nama')
                            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- NIM dan Semester -->
                    <div>
                        <label for="nim" class="block text-sm font-medium text-gray-700">NIM *</label>
                        <input type="text" id="nim" name="nim" value="{{ old('nim', $pendaftaran->nim) }}"
                               class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm p-2 focus:ring-blue-500 focus:border-blue-500" required>
                        @error('nim')
                            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="semester" class="block text-sm font-medium text-gray-700">Semester *</label>
                        <select id="semester" name="semester" 
                                class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm p-2 focus:ring-blue-500 focus:border-blue-500" required>
                            <option value="">Pilih Semester</option>
                            @for($i = 1; $i <= 8; $i++)
                                <option value="{{ $i }}" {{ old('semester', $pendaftaran->semester) == $i ? 'selected' : '' }}>Semester {{ $i }}</option>
                            @endfor
                        </select>
                        @error('semester')
                            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- No HP -->
                    <div>
                        <label for="no_hp" class="block text-sm font-medium text-gray-700">No. HP *</label>
                        <input type="tel" id="no_hp" name="no_hp" value="{{ old('no_hp', $pendaftaran->no_hp) }}"
                               class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm p-2 focus:ring-blue-500 focus:border-blue-500" required>
                        @error('no_hp')
                            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Status -->
                    <div>
                        <label for="status_pendaftaran" class="block text-sm font-medium text-gray-700">Status *</label>
                        <select id="status_pendaftaran" name="status_pendaftaran" 
                                class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm p-2 focus:ring-blue-500 focus:border-blue-500" required>
                            <option value="pending" {{ old('status_pendaftaran', $pendaftaran->status_pendaftaran) == 'pending' ? 'selected' : '' }}>Menunggu</option>
                            <option value="diterima" {{ old('status_pendaftaran', $pendaftaran->status_pendaftaran) == 'diterima' ? 'selected' : '' }}>Diterima</option>
                            <option value="ditolak" {{ old('status_pendaftaran', $pendaftaran->status_pendaftaran) == 'ditolak' ? 'selected' : '' }}>Ditolak</option>
                        </select>
                        @error('status_pendaftaran')
                            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Alasan -->
                    <div class="md:col-span-2">
                        <label for="alasan_mendaftar" class="block text-sm font-medium text-gray-700">Alasan Bergabung dengan HIMA-TI *</label>
                        <textarea id="alasan_mendaftar" name="alasan_mendaftar" rows="4" required
                                  class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm p-2 focus:ring-blue-500 focus:border-blue-500">{{ old('alasan_mendaftar', $pendaftaran->alasan_mendaftar) }}</textarea>
                        @error('alasan_mendaftar')
                            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Dokumen -->
                    <div class="md:col-span-2">
                        <label for="dokumen" class="block text-sm font-medium text-gray-700">Upload CV/Portofolio (Opsional)</label>
                        @if($pendaftaran->dokumen)
                        <div class="mb-2">
                            <p class="text-sm text-gray-600">File saat ini:</p>
                            <a href="{{ Storage::url($pendaftaran->dokumen) }}" target="_blank" class="text-blue-600 hover:text-blue-800 text-sm">
                                <i class="fas fa-download mr-1"></i> Download Dokumen
                            </a>
                        </div>
                        @endif
                        <input type="file" id="dokumen" name="dokumen" 
                               class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm p-2 focus:ring-blue-500 focus:border-blue-500"
                               accept=".pdf,.doc,.docx">
                        <p class="text-xs text-gray-500 mt-1">Format: PDF, DOC, DOCX (Maks. 2MB)</p>
                        @error('dokumen')
                            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                <div class="mt-6 flex justify-end space-x-3">
                    <a href="{{ route('pendaftaran.index') }}" class="bg-gray-300 hover:bg-gray-400 text-gray-800 px-4 py-2 rounded-md">
                        Batal
                    </a>
                    <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-md">
                        Update Data
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection