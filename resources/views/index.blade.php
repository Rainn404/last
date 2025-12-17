@extends('layouts.app')

@section('title', 'HIMA-TI - Beranda')

@section('content')

<style>
/* === Hero Section === */
.hero {
    background: rgba(255, 255, 255, 0.15);
    color: #F8FAFC;
    padding: 80px 40px;
    text-align: center;
    min-height: 600px;
    display: flex;
    align-items: center;
    justify-content: center;
    position: relative;
    overflow: hidden;
    backdrop-filter: blur(10px);
    border-bottom: 1px solid rgba(224, 231, 255, 0.3);
    margin-top: 20px;
}

.hero::before {
    display: none;
}

.hero-content {
    position: relative;
    z-index: 1;
    max-width: 800px;
    margin: 0 auto;
}

.hero h1 {
    font-size: 3.5rem;
    font-weight: 800;
    margin-bottom: 20px;
    line-height: 1.2;
    letter-spacing: -1px;
    color: #F8FAFC;
}

.hero p {
    font-size: 1.3rem;
    margin-bottom: 40px;
    opacity: 0.95;
    line-height: 1.6;
    color: #CBD5E1;
}

.hero-buttons {
    display: flex;
    gap: 16px;
    justify-content: center;
    flex-wrap: wrap;
}

.btn {
    padding: 14px 32px;
    font-size: 1rem;
    font-weight: 600;
    border-radius: 8px;
    text-decoration: none;
    transition: all 0.3s ease;
    border: 2px solid transparent;
    cursor: pointer;
    display: inline-block;
}

.btn-primary {
    background: #6366F1;
    color: #FFFFFF;
}

.btn-primary:hover {
    transform: translateY(-3px);
    box-shadow: 0 8px 20px rgba(99, 102, 241, 0.4);
    background: #4F46E5;
}

.btn-secondary {
    background: rgba(255, 255, 255, 0.15);
    color: #F8FAFC;
    border-color: rgba(224, 231, 255, 0.5);
    backdrop-filter: blur(10px);
}

.btn-secondary:hover {
    background: rgba(255, 255, 255, 0.25);
    color: #E0E7FF;
    border-color: rgba(224, 231, 255, 0.8);
}

.btn-outline {
    background: transparent;
    color: #F8FAFC;
    border-color: #E0E7FF;
}

.btn-outline:hover {
    background: rgba(255, 255, 255, 0.1);
    color: #E0E7FF;
}

/* === About Section === */
.about {
    padding: 80px 40px;
    background: rgba(255, 255, 255, 0.08);
    backdrop-filter: blur(12px);
    border: 1px solid rgba(224, 231, 255, 0.2);
    margin: 20px;
    border-radius: 16px;
}

.container {
    max-width: 1200px;
    margin: 0 auto;
}

.section-title {
    font-size: 2.5rem;
    font-weight: 800;
    text-align: center;
    margin-bottom: 50px;
    color: #F8FAFC;
}

.about-content {
    display: grid;
    grid-template-columns: 1fr 1fr;
    gap: 60px;
    align-items: center;
}

.about-text p {
    font-size: 1.1rem;
    line-height: 1.8;
    color: #CBD5E1;
    margin-bottom: 20px;
}

.about-stats {
    display: grid;
    grid-template-columns: repeat(3, 1fr);
    gap: 30px;
}

.stat {
    text-align: center;
    padding: 30px;
    background: rgba(255, 255, 255, 0.18);
    border: 1px solid rgba(224, 231, 255, 0.3);
    border-radius: 12px;
    box-shadow: 0 4px 6px rgba(2, 6, 23, 0.2);
    transition: all 0.3s ease;
    backdrop-filter: blur(8px);
}

.stat:hover {
    transform: translateY(-5px);
    box-shadow: 0 8px 16px rgba(99, 102, 241, 0.25);
    border-color: #6366F1;
}

.stat h3 {
    font-size: 2.5rem;
    color: #6366F1;
    margin-bottom: 10px;
    font-weight: 800;
}

.stat p {
    color: #CBD5E1;
    font-size: 1rem;
    margin: 0;
}

/* === Divisi Section === */
.divisi {
    padding: 80px 40px;
    background: rgba(255, 255, 255, 0.08);
    backdrop-filter: blur(12px);
    border: 1px solid rgba(224, 231, 255, 0.2);
    margin: 20px;
    border-radius: 16px;
}

.divisi-grid {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(280px, 1fr));
    gap: 30px;
}

.divisi-card {
    padding: 40px 30px;
    background: rgba(255, 255, 255, 0.18);
    border: 1px solid rgba(224, 231, 255, 0.3);
    border-radius: 12px;
    text-align: center;
    transition: all 0.3s ease;
    backdrop-filter: blur(10px);
}

.divisi-card:hover {
    transform: translateY(-8px);
    border-color: #6366F1;
    box-shadow: 0 12px 30px rgba(99, 102, 241, 0.2);
    background: rgba(255, 255, 255, 0.25);
}

.divisi-icon {
    font-size: 3.5rem;
    color: #6366F1;
    margin-bottom: 20px;
}

.divisi-card h3 {
    font-size: 1.3rem;
    font-weight: 700;
    color: #F8FAFC;
    margin-bottom: 15px;
}

.divisi-card p {
    color: #CBD5E1;
    line-height: 1.6;
    font-size: 0.95rem;
    margin: 0;
}

/* === Berita Section === */
.berita {
    padding: 80px 40px;
    background: rgba(255, 255, 255, 0.08);
    backdrop-filter: blur(12px);
    border: 1px solid rgba(224, 231, 255, 0.2);
    margin: 20px;
    border-radius: 16px;
}

.berita-grid {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(320px, 1fr));
    gap: 30px;
    margin-bottom: 50px;
}

.berita-card {
    background: rgba(255, 255, 255, 0.18);
    border: 1px solid rgba(224, 231, 255, 0.3);
    border-radius: 12px;
    overflow: hidden;
    box-shadow: 0 4px 6px rgba(2, 6, 23, 0.2);
    transition: all 0.3s ease;
    display: flex;
    flex-direction: column;
    backdrop-filter: blur(10px);
}

.berita-card:hover {
    transform: translateY(-8px);
    border-color: #6366F1;
    box-shadow: 0 12px 30px rgba(99, 102, 241, 0.2);
    background: rgba(255, 255, 255, 0.25);
}
    box-shadow: 0 12px 30px rgba(0,0,0,0.12);
}

.berita-image {
    height: 200px;
    background: linear-gradient(135deg, rgba(99, 102, 241, 0.4) 0%, rgba(79, 70, 229, 0.4) 100%);
    display: flex;
    align-items: center;
    justify-content: center;
    color: white;
    font-size: 3rem;
    overflow: hidden;
    backdrop-filter: blur(5px);
}

.berita-image img {
    width: 100%;
    height: 100%;
    object-fit: cover;
}

.berita-content {
    padding: 30px;
    flex: 1;
    display: flex;
    flex-direction: column;
}

.berita-date {
    color: #6366F1;
    font-size: 0.85rem;
    font-weight: 600;
    text-transform: uppercase;
    letter-spacing: 0.5px;
}

.berita-card h3 {
    font-size: 1.3rem;
    font-weight: 700;
    color: #F8FAFC;
    margin: 12px 0;
    line-height: 1.4;
}

.berita-card p {
    color: #CBD5E1;
    line-height: 1.6;
    font-size: 0.95rem;
    margin-bottom: 20px;
    flex: 1;
}

.berita-link {
    color: #6366F1;
    text-decoration: none;
    font-weight: 600;
    display: inline-flex;
    align-items: center;
    gap: 8px;
    transition: all 0.2s ease;
}

.berita-link:hover {
    gap: 12px;
    color: #818CF8;
}
}

.berita-more {
    text-align: center;
}

/* === Prestasi Section === */
.prestasi {
    padding: 80px 40px;
    background: rgba(255, 255, 255, 0.08);
    backdrop-filter: blur(12px);
    border: 1px solid rgba(224, 231, 255, 0.2);
    margin: 20px;
    border-radius: 16px;
}

.prestasi-grid {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
    gap: 30px;
    margin-bottom: 50px;
}

.prestasi-card {
    padding: 40px 30px;
    text-align: center;
    background: linear-gradient(135deg, rgba(99, 102, 241, 0.3) 0%, rgba(79, 70, 229, 0.3) 100%);
    border: 1px solid rgba(224, 231, 255, 0.3);
    border-radius: 12px;
    color: #F8FAFC;
    transition: all 0.3s ease;
    position: relative;
    overflow: hidden;
    backdrop-filter: blur(10px);
}

.prestasi-card::before {
    content: '';
    position: absolute;
    top: -50%;
    right: -50%;
    width: 200px;
    height: 200px;
    background: rgba(99, 102, 241, 0.2);
    border-radius: 50%;
}

.prestasi-card::after {
    content: '';
    position: absolute;
    bottom: -50%;
    left: -50%;
    width: 200px;
    height: 200px;
    background: rgba(99, 102, 241, 0.15);
    border-radius: 50%;
}

.prestasi-card:hover {
    transform: translateY(-8px);
    box-shadow: 0 12px 30px rgba(99, 102, 241, 0.3);
    border-color: #6366F1;
    background: linear-gradient(135deg, rgba(99, 102, 241, 0.4) 0%, rgba(79, 70, 229, 0.4) 100%);
}

.prestasi-badge {
    font-size: 3rem;
    margin-bottom: 20px;
    position: relative;
    z-index: 1;
}

.prestasi-card h3 {
    font-size: 1.2rem;
    font-weight: 700;
    margin-bottom: 10px;
    position: relative;
    z-index: 1;
}

.prestasi-card p {
    opacity: 0.9;
    margin-bottom: 15px;
    position: relative;
    z-index: 1;
}

.prestasi-anggota {
    display: block;
    font-size: 0.85rem;
    opacity: 0.8;
    position: relative;
    z-index: 1;
}

.prestasi-more {
    text-align: center;
}

/* === Responsive === */
@media (max-width: 768px) {
    .hero h1 {
        font-size: 2rem;
    }

    .hero p {
        font-size: 1rem;
    }

    .about-content {
        grid-template-columns: 1fr;
        gap: 40px;
    }

    .about-stats {
        grid-template-columns: 1fr;
    }

    .section-title {
        font-size: 2rem;
    }

    .divisi-card {
        padding: 30px 20px;
    }
}
</style>

<!-- Hero Section -->
<section class="hero">
    <div class="hero-content">
        <h1>SISTEM INFORMASI KEMAHASISWAAN<br>TEKNOLOGI INFORMASI</h1>
        <p>Mengembangkan potensi mahasiswa di bidang teknologi, kepemimpinan, dan kontribusi sosial</p>
        <div class="hero-buttons">
            <a href="{{ url('/pendaftaran') }}" class="btn btn-primary">Menjadi Pengurus</a>
            <a href="{{ url('/berita') }}" class="btn btn-secondary">Lihat Berita</a>
        </div>
    </div>
</section>

<!-- About Section -->
<section class="about">
    <div class="container">
        <h2 class="section-title">Tentang HIMA-TI</h2>
        <div class="about-content">
            <div class="about-text">
                <p>Himpunan Mahasiswa Teknik Informatika (HIMA-TI) adalah wadah pengembangan diri bagi mahasiswa Program Studi Teknik Informatika. Kami berkomitmen untuk membangun generasi profesional yang kompeten dan beretika.</p>
                <p>Melalui berbagai program pelatihan, seminar, kompetisi, dan pengabdian masyarakat, kami memastikan setiap anggota mendapatkan kesempatan untuk mengembangkan kemampuan akademik, soft skills, dan karakter kepemimpinan.</p>
            </div>
            <div class="about-stats">
                <div class="stat">
                    <h3>{{ $jumlahAnggota ?? '150' }}+</h3>
                    <p>Anggota Aktif</p>
                </div>
                <div class="stat">
                    <h3>{{ $divisis->count() ?? '4' }}</h3>
                    <p>Divisi/Departemen</p>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Divisi Section -->
<section class="divisi">
    <div class="container">
        <h2 class="section-title">Divisi HIMA-TI</h2>
        <div class="divisi-grid">
            @forelse($divisis as $divisi)
                <div class="divisi-card">
                    <div class="divisi-icon">
                        <i class="fas fa-folder-open"></i>
                    </div>
                    <h3>{{ $divisi->nama_divisi ?? 'Divisi' }}</h3>
                    <p>{{ $divisi->deskripsi ?? 'Bagian dari struktur organisasi HIMA-TI' }}</p>
                </div>
            @empty
               
            @endforelse
        </div>
    </div>
</section>

<!-- Berita Section -->
<section class="berita">
    <div class="container">
        <h2 class="section-title">Berita & Pengumuman Terbaru</h2>
        <div class="berita-grid">
            @forelse($beritas as $berita)
                <div class="berita-card">
                    <div class="berita-image">
                        @if($berita->foto)
                            <img src="{{ asset('storage/' . $berita->foto) }}" alt="{{ $berita->judul }}">
                        @else
                            <div style="display: flex; align-items: center; justify-content: center; height: 100%; background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);">
                                <i class="fas fa-newspaper" style="font-size: 3rem; color: white;"></i>
                            </div>
                        @endif
                    </div>
                    <div class="berita-content">
                        <span class="berita-date">{{ $berita->tanggal ? \Carbon\Carbon::parse($berita->tanggal)->format('d M Y') : 'Terbaru' }}</span>
                        <h3>{{ $berita->judul }}</h3>
                        <p>{{ Str::limit($berita->isi, 100) }}</p>
                        <a href="{{ route('berita.show', $berita->id_berita) }}" class="berita-link">Baca Selengkapnya <i class="fas fa-arrow-right"></i></a>
                    </div>
                </div>
            @empty
                <div class="berita-card">
                    <div class="berita-image" style="display: flex; align-items: center; justify-content: center; background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);">
                        <i class="fas fa-newspaper" style="font-size: 3rem; color: white;"></i>
                    </div>
                    <div class="berita-content">
                        <span class="berita-date">Terbaru</span>
                        <h3>Ikuti Update HIMA-TI</h3>
                        <p>Dapatkan informasi terkini tentang kegiatan dan pengumuman dari HIMA-TI.</p>
                        <a href="{{ url('/berita') }}" class="berita-link">Lihat Semua <i class="fas fa-arrow-right"></i></a>
                    </div>
                </div>
                <div class="berita-card">
                    <div class="berita-image" style="display: flex; align-items: center; justify-content: center; background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);">
                        <i class="fas fa-newspaper" style="font-size: 3rem; color: white;"></i>
                    </div>
                    <div class="berita-content">
                        <span class="berita-date">Terbaru</span>
                        <h3>Pendaftaran Anggota</h3>
                        <p>HIMA-TI membuka kesempatan bagi mahasiswa untuk bergabung dengan organisasi kami.</p>
                        <a href="{{ url('/pendaftaran') }}" class="berita-link">Daftar Sekarang <i class="fas fa-arrow-right"></i></a>
                    </div>
                </div>
                <div class="berita-card">
                    <div class="berita-image" style="display: flex; align-items: center; justify-content: center; background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);">
                        <i class="fas fa-newspaper" style="font-size: 3rem; color: white;"></i>
                    </div>
                    <div class="berita-content">
                        <span class="berita-date">Terbaru</span>
                        <h3>Program Pelatihan</h3>
                        <p>Kami menyediakan berbagai program untuk meningkatkan kompetensi dan potensi anggota.</p>
                        <a href="{{ url('/berita') }}" class="berita-link">Pelajari Lebih Lanjut <i class="fas fa-arrow-right"></i></a>
                    </div>
                </div>
            @endforelse
        </div>
        <div class="berita-more">
            <a href="{{ url('/berita') }}" class="btn btn-outline">Lihat Semua Berita</a>
        </div>
    </div>
</section>

<!-- Prestasi Section -->
<section class="prestasi">
    <div class="container">
        <h2 class="section-title">Pencapaian Terbaru</h2>
        <div class="prestasi-grid">
            @forelse($prestasis as $prestasi)
                <div class="prestasi-card">
                    <div class="prestasi-badge">
                        <i class="fas fa-trophy"></i>
                    </div>
                    <h3>{{ $prestasi->judul ?? $prestasi->nama_prestasi }}</h3>
                    <p>{{ $prestasi->kategori ?? 'Prestasi' }}</p>
                    @if($prestasi->tahun)
                        <span class="prestasi-anggota">Tahun: {{ $prestasi->tahun }}</span>
                    @endif
                </div>
            @empty
                <div class="prestasi-card">
                    <div class="prestasi-badge">
                        <i class="fas fa-trophy"></i>
                    </div>
                    <h3>Prestasi Anggota</h3>
                    <p>Pencapaian demi pencapaian dari anggota HIMA-TI</p>
                </div>
                <div class="prestasi-card">
                    <div class="prestasi-badge">
                        <i class="fas fa-star"></i>
                    </div>
                    <h3>Pengembangan Diri</h3>
                    <p>Program pelatihan dan mentoring untuk kompetensi</p>
                </div>
                <div class="prestasi-card">
                    <div class="prestasi-badge">
                        <i class="fas fa-medal"></i>
                    </div>
                    <h3>Kolaborasi Strategis</h3>
                    <p>Kemitraan dengan institusi terkemuka</p>
                </div>
            @endforelse
        </div>
        <div class="prestasi-more">
            <a href="{{ url('/prestasi') }}" class="btn btn-outline">Lihat Semua Prestasi</a>
        </div>
    </div>
</section>

@endsection