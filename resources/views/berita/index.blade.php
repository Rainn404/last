@extends('layouts.app')

@section('title', 'Berita HIMA-TI')

@section('content')
<style>
  /* Background & Overlay sudah di app.blade.php, kita cukup pastikan konten di z-index 2 */
  main, section, .content {
    position: relative;
    z-index: 2;
  }

  /* ===== HERO SECTION ===== */
  .hero {
    background: rgba(255, 255, 255, 0.12);
    backdrop-filter: blur(15px);
    border: 1px solid rgba(224, 231, 255, 0.25);
    color: #F8FAFC;
    text-align: center;
    padding: 60px 20px;
    border-radius: 0 0 20px 20px;
    display: flex;
    flex-direction: column;
    align-items: center;
    justify-content: center;
    min-height: 200px;
  }

  .hero h1 {
    font-weight: 800;
    font-size: 2rem;
    margin-bottom: 10px;
    text-transform: uppercase;
    text-align: center;
    color: #F8FAFC;
    text-shadow: 0 2px 4px rgba(2, 6, 23, 0.2);
  }

  .hero p {
    max-width: 700px;
    font-size: 1rem;
    opacity: 0.95;
    margin: 0 auto;
    text-align: center;
    color: #E0E7FF;
  }

  /* ===== SECTION BERITA ===== */
  .news-section {
    background: transparent;
    padding: 60px 0;
  }

  /* ===== JUDUL SECTION ===== */
  .section-title {
    text-align: center;
    font-weight: 700;
    font-size: 1.5rem;
    margin: 40px 0 25px;
    color: #F8FAFC;
    text-shadow: 0 2px 4px rgba(2, 6, 23, 0.2);
  }

  /* ===== CARD BERITA ===== */
  .card-news {
    display: flex;
    align-items: flex-start;
    background: rgba(255, 255, 255, 0.18);
    backdrop-filter: blur(8px);
    border: 1px solid rgba(224, 231, 255, 0.30);
    border-radius: 16px;
    box-shadow: 0 4px 12px rgba(2, 6, 23, 0.15);
    margin-bottom: 24px;
    overflow: hidden;
    transition: transform 0.2s ease-in-out, background 0.3s ease;
  }

  .card-news:hover {
    transform: translateY(-4px);
    background: rgba(255, 255, 255, 0.25);
    box-shadow: 0 8px 32px rgba(99, 102, 241, 0.2);
  }

  .card-news img {
    width: 250px;
    height: 180px;
    object-fit: cover;
    border-radius: 16px 0 0 16px;
    opacity: 0.9;
  }

  .card-news:hover img {
    opacity: 1;
  }

  .card-body {
    flex: 1;
    padding: 20px 24px;
  }

  .card-body h5 {
    font-weight: 700;
    font-size: 1.1rem;
    color: #F8FAFC;
  }

  .card-body p {
    font-size: 0.95rem;
    color: #CBD5E1;
  }

  .btn-warning {
    background-color: #6366F1;
    border: none;
    color: #FFFFFF;
    font-weight: 700;
    border-radius: 8px;
    transition: 0.3s;
    box-shadow: 0 4px 12px rgba(99, 102, 241, 0.25);
  }

  .btn-warning:hover {
    background-color: #4F46E5;
    color: #fff;
    box-shadow: 0 6px 20px rgba(99, 102, 241, 0.35);
  }

  /* ===== FEATURED LAYOUT (1 KIRI + 3 KANAN) ===== */
  .news-featured {
    display: grid;
    grid-template-columns: 2fr 1fr;
    gap: 24px;
    margin-bottom: 40px;
  }

  .news-featured-main {
    background: rgba(255, 255, 255, 0.18);
    backdrop-filter: blur(8px);
    border: 1px solid rgba(224, 231, 255, 0.30);
    border-radius: 16px;
    overflow: hidden;
    height: 100%;
    display: flex;
    flex-direction: column;
    transition: transform 0.2s ease-in-out, background 0.3s ease;
  }

  .news-featured-main:hover {
    transform: translateY(-4px);
    background: rgba(255, 255, 255, 0.25);
    box-shadow: 0 8px 32px rgba(99, 102, 241, 0.2);
  }

  .news-featured-main img {
    width: 100%;
    height: 300px;
    object-fit: cover;
  }

  .news-content {
    flex: 1;
    padding: 24px;
    display: flex;
    flex-direction: column;
  }

  .news-content h3 {
    color: #F8FAFC;
    font-weight: 700;
    font-size: 1.2rem;
    margin-bottom: 12px;
    line-height: 1.4;
  }

  .news-content p {
    color: #CBD5E1;
    font-size: 0.9rem;
    margin-bottom: 16px;
    flex: 1;
    line-height: 1.5;
  }

  .btn-readmore {
    display: inline-block;
    background: linear-gradient(90deg, #6366F1, #4F46E5);
    color: #FFFFFF;
    text-decoration: none;
    padding: 8px 16px;
    border-radius: 6px;
    font-weight: 600;
    font-size: 0.85rem;
    transition: all 0.3s ease;
    width: fit-content;
    box-shadow: 0 4px 12px rgba(99, 102, 241, 0.25);
  }

  .btn-readmore:hover {
    background: linear-gradient(90deg, #4F46E5, #4338CA);
    color: #fff;
    transform: translateY(-2px);
    box-shadow: 0 6px 20px rgba(99, 102, 241, 0.35);
  }

  .btn-readmore-sm {
    display: inline-block;
    background: rgba(99, 102, 241, 0.8);
    color: #F8FAFC;
    text-decoration: none;
    padding: 6px 12px;
    border-radius: 6px;
    font-weight: 600;
    font-size: 0.75rem;
    transition: all 0.3s ease;
    box-shadow: 0 2px 8px rgba(99, 102, 241, 0.2);
  }

  .btn-readmore-sm:hover {
    background: #6366F1;
    color: #fff;
    transform: translateY(-2px);
  }

  /* ===== FEATURED SIDE (3 BERITA SAMPING) ===== */
  .news-featured-side {
    display: flex;
    flex-direction: column;
    gap: 16px;
  }

  .news-side-item {
    background: rgba(255, 255, 255, 0.18);
    backdrop-filter: blur(8px);
    border: 1px solid rgba(224, 231, 255, 0.30);
    border-radius: 12px;
    overflow: hidden;
    display: flex;
    gap: 12px;
    padding: 12px;
    transition: transform 0.2s ease-in-out, background 0.3s ease;
  }

  .news-side-item:hover {
    transform: translateY(-2px);
    background: rgba(255, 255, 255, 0.25);
  }

  .news-side-item img {
    width: 80px;
    height: 80px;
    object-fit: cover;
    border-radius: 8px;
    flex-shrink: 0;
  }

  .news-side-item h4 {
    color: #F8FAFC;
    font-size: 0.9rem;
    line-height: 1.3;
    margin-bottom: 6px;
  }

  /* ===== GRID BAWAH (BERITA SISA) ===== */
  .news-grid {
    display: grid;
    grid-template-columns: repeat(auto-fill, minmax(280px, 1fr));
    gap: 24px;
    margin-bottom: 40px;
  }

  .news-card {
    background: rgba(255, 255, 255, 0.18);
    backdrop-filter: blur(8px);
    border: 1px solid rgba(224, 231, 255, 0.30);
    border-radius: 12px;
    overflow: hidden;
    display: flex;
    flex-direction: column;
    transition: transform 0.2s ease-in-out, background 0.3s ease;
  }

  .news-card:hover {
    transform: translateY(-4px);
    background: rgba(255, 255, 255, 0.25);
    box-shadow: 0 8px 32px rgba(99, 102, 241, 0.2);
  }

  .news-card img {
    width: 100%;
    height: 180px;
    object-fit: cover;
  }

  .news-body {
    padding: 16px;
    flex: 1;
    display: flex;
    flex-direction: column;
  }

  .news-body h5 {
    color: #F8FAFC;
    font-weight: 700;
    font-size: 0.95rem;
    margin-bottom: 8px;
    line-height: 1.3;
  }

  .news-body p {
    color: #CBD5E1;
    font-size: 0.85rem;
    flex: 1;
    line-height: 1.4;
    margin-bottom: 12px;
  }

  .news-footer {
    padding: 0 16px 16px;
  }

  /* ===== Tombol Lihat Semua Berita ===== */
  .btn-berita {
    background: linear-gradient(90deg, #6366F1, #4F46E5);
    color: #fff !important;
    border: none;
    border-radius: 8px;
    font-weight: 600;
    letter-spacing: 0.3px;
    padding: 10px 28px;
    transition: all 0.3s ease;
    box-shadow: 0 4px 10px rgba(99, 102, 241, 0.25);
  }

  .btn-berita:hover {
    background: linear-gradient(90deg, #4F46E5, #4338CA);
    transform: translateY(-2px);
    box-shadow: 0 6px 14px rgba(99, 102, 241, 0.35);
  }

  @media (max-width: 768px) {
    .news-featured {
      grid-template-columns: 1fr;
    }
    .card-news {
      flex-direction: column;
    }

    .card-news img {
      width: 100%;
      height: 220px;
      border-radius: 16px 16px 0 0;
    }

    .hero {
      padding: 40px 20px;
      min-height: 150px;
    }

    .hero h1 {
      font-size: 1.5rem;
    }
  }
</style>

<main class="content">
  <!-- ===== HERO ===== -->
  <section class="hero">
    <h1>BERITA HIMA-TI</h1>
    <p>
      Kumpulan berita, kegiatan, dan prestasi terkini dari
      Himpunan Mahasiswa Teknik Informatika Politeknik Negeri Tanah Laut.
    </p>
  </section>
</main>

<section class="news-section">
  <div class="container">

    <h3 class="section-title">DAFTAR BERITA</h3>

    @php
      $featured = $highlight->take(4);
      $utama = $featured->first();
      $samping = $featured->slice(1);
      $rest = $highlight->skip(4);
    @endphp

    {{-- ===============================
         FEATURED (1 KIRI + 3 KANAN)
    ================================ --}}
    <div class="news-featured">

  {{-- KIRI --}}
  <div class="news-featured-main">
    <img src="{{ Storage::url($utama->foto) }}" alt="">
    <div class="news-content">
      <h3>{{ $utama->judul }}</h3>
      <p>{{ Str::limit(strip_tags($utama->isi), 180) }}</p>

      <a href="{{ route('berita.show', $utama->id_berita) }}"
         class="btn-readmore">
        READ MORE
      </a>
    </div>
  </div>

  {{-- KANAN --}}
  <div class="news-featured-side">
    @foreach($samping as $item)
      <div class="news-side-item">
        <img src="{{ Storage::url($item->foto) }}" alt="">
        <div>
          <h4 class="fw-bold">
    {{ Str::limit($item->judul, 60) }}
</h4>
          <a href="{{ route('berita.show', $item->id_berita) }}"
             class="btn-readmore-sm">
            READ MORE
          </a>
        </div>
      </div>
    @endforeach
  </div>

</div>

{{-- ===============================
     GRID BAWAH (SISA BERITA)
================================ --}}
<div class="news-grid">
  @foreach($rest as $item)
    <div class="news-card">
      <img src="{{ Storage::url($item->foto) }}" alt="">
      <div class="news-body">
        <h5>{{ $item->judul }}</h5>
        <p>{{ Str::limit(strip_tags($item->isi), 100) }}</p>
      </div>
      <div class="news-footer">
        <a href="{{ route('berita.show', $item->id_berita) }}"
           class="btn-readmore">
          READ MORE
        </a>
      </div>
    </div>
  @endforeach
</div>

<!-- Tombol lihat semua (hanya muncul di halaman utama) -->
@if(isset($mode) && $mode === 'utama')
  <div class="text-center mt-4 mb-5">
  </div>
@endif
  </div>
  </section>
</main>
@endsection