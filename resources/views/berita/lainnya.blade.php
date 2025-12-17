@extends('layouts.app')

@section('title', 'Semua Berita HIMA-TI')

@section('content')
<style>
  body {
    font-family: 'Poppins', sans-serif;
    background-color: #f8fafc;
  }

  .card-news {
    border-radius: 16px;
    box-shadow: 0 4px 12px rgba(0,0,0,.08);
    border: none;
    overflow: hidden;
    margin-bottom: 24px;
    background: #fff;
    transition: transform .2s;
  }
  .card-news:hover {
    transform: translateY(-3px);
  }

  .card-news img {
    width: 100%;
    height: 200px;
    object-fit: cover;
    border-radius: 10px;
  }

  .btn-read {
    background-color: #ffc107;
    border: none;
    color: #000;
    font-weight: 600;
    border-radius: 8px;
    padding: 8px 16px;
    transition: 0.3s;
  }
  .btn-read:hover {
    background-color: #ffb100;
    color: #000;
  }

  .title-section {
    font-weight: 800;
    text-align: center;
    color: #1e293b;
    margin-bottom: 30px;
  }

  .title-section i {
    color: #0d6efd;
    margin-right: 10px;
  }

  .back-btn {
    display: inline-block;
    margin-bottom: 20px;
    color: #111827;
    text-decoration: none;
    font-weight: 600;
  }
  .back-btn i {
    margin-right: 6px;
  }

  /* Layout baru untuk gambar kiri + teks kanan */
  .news-item {
    display: flex;
    align-items: flex-start;
    gap: 20px;
    padding: 20px;
    border-radius: 12px;
  }

  .news-item .news-image {
    flex: 0 0 35%;
  }

  .news-item .news-content {
    flex: 1;
  }

  .news-item h5 {
    font-weight: 700;
    color: #111827;
  }

  .news-item p {
    font-size: 0.95rem;
    color: #374151;
  }

  @media (max-width: 768px) {
    .news-item {
      flex-direction: column;
    }
    .news-item .news-image {
      width: 100%;
    }
  }
</style>

<div class="container py-4">

  <!-- Tombol kembali -->
  <a href="{{ route('berita.index') }}" class="back-btn">
    <i class="fas fa-arrow-left"></i> Kembali ke Berita Utama
  </a>

  <!-- Judul Halaman -->
  <h3 class="title-section">
    <i class="fas fa-layer-group"></i> Semua Berita HIMA-TI
  </h3>

  <!-- Daftar Berita -->
  @foreach($beritaLainnya as $berita)
  <div class="card card-news">
    <div class="news-item">
      <!-- Gambar kiri -->
      <div class="news-image">
        @if($berita->foto)
          <img src="{{ Storage::url($berita->foto) }}" alt="Gambar Berita">
        @else
          <img src="https://via.placeholder.com/400x250?text=No+Image" alt="Tidak ada gambar">
        @endif
      </div>

      <!-- Teks kanan -->
      <div class="news-content">
        <h5>{{ $berita->judul }}</h5>
        <p class="text-muted mb-1">
          Posted by <strong>{{ $berita->nama_penulis ?? 'Anonim' }}</strong> —
          {{ $berita->created_at?->format('d M Y') }}
        </p>
        <p>{{ Str::limit(strip_tags($berita->isi), 220, '...') }}</p>
        <a href="{{ route('berita.show', $berita->id_berita) }}" class="btn btn-read mt-2">READ MORE</a>
      </div>
    </div>
  </div>
  @endforeach

  @if($beritaLainnya->isEmpty())
  <div class="alert alert-info text-center mt-4">
    Belum ada berita yang dapat ditampilkan.
  </div>
  @endif

</div>
@endsection
