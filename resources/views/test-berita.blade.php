@extends('layouts.app')

@section('title', 'Test Berita')

@section('content')
<div class="container mt-5">
    <h2>Debug Berita Data</h2>
    
    <h3>Data yang diterima:</h3>
    <pre>
Jumlah berita: {{ count($beritas) }}
{{ print_r($beritas->toArray(), true) }}
    </pre>

    <h3>Query test:</h3>
    @forelse($beritas as $b)
        <div class="card mb-2">
            <div class="card-body">
                <h5>{{ $b->judul }}</h5>
                <p>ID: {{ $b->id_berita }}</p>
                <p>Isi: {{ Str::limit($b->isi, 100) }}</p>
                <p>Tanggal: {{ $b->tanggal }}</p>
                <p>Penulis: {{ $b->penulis }}</p>
                <p>Foto: {{ $b->foto }}</p>
            </div>
        </div>
    @empty
        <p style="color: red;">❌ BERITAS KOSONG!</p>
    @endforelse
</div>
@endsection
