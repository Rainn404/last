@extends('layouts.app')

@section('content')
<link rel="stylesheet" href="{{ asset('css/glassmorphism.css') }}">

<div class="min-h-screen" style="background-image: url('/logo_bg/gedung politala'); background-attachment: fixed; background-position: center; background-repeat: no-repeat; background-size: cover;">
    <!-- Overlay backdrop -->
    <div style="position: fixed; top: 0; left: 0; width: 100%; height: 100%; background: linear-gradient(rgba(15, 23, 42, 0.55), rgba(49, 46, 129, 0.25)); pointer-events: none; z-index: 1;"></div>

    <!-- Hero Section -->
    <div style="background: rgba(255, 255, 255, 0.12); backdrop-filter: blur(15px); border-bottom: 1px solid rgba(224, 231, 255, 0.25); color: #F8FAFC; position: relative; z-index: 2; margin-top: 70px;">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-16">
            <div class="flex flex-col sm:flex-row sm:justify-between sm:items-center gap-6">
                <div class="flex-1">
                    <h1 class="text-4xl sm:text-5xl font-bold mb-3" style="color: #F8FAFC; text-shadow: 0 2px 4px rgba(2, 6, 23, 0.2);">
                        Prestasi Mahasiswa
                    </h1>
                    <p style="color: #E0E7FF; font-size: 1.125rem;">
                        Daftar prestasi mahasiswa yang telah diverifikasi oleh HIMA-TI
                    </p>
                </div>
                @auth
                    <a href="{{ route('prestasi.create') }}" 
                        style="background: #6366F1; color: #FFFFFF; border: none; padding: 12px 24px; border-radius: 10px; font-weight: 600; transition: all 0.3s ease; box-shadow: 0 4px 12px rgba(99, 102, 241, 0.25); display: inline-flex; align-items: center; gap: 8px; text-decoration: none; white-space: nowrap;"
                        onmouseover="this.style.background='#4F46E5'; this.style.boxShadow='0 6px 20px rgba(99, 102, 241, 0.35)'; this.style.transform='translateY(-2px)';"
                        onmouseout="this.style.background='#6366F1'; this.style.boxShadow='0 4px 12px rgba(99, 102, 241, 0.25)'; this.style.transform='none';">
                        <i class="fas fa-plus"></i>
                        <span>Ajukan Prestasi</span>
                    </a>
                @endauth
            </div>
        </div>
    </div>

    <!-- Search & Filter Section -->
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-6" style="position: relative; z-index: 2;">
        <form method="GET" action="{{ route('prestasi.index') }}" class="space-y-4">
            <!-- Search Bar -->
            <div class="relative">
                <input 
                    type="text" 
                    name="search" 
                    placeholder="Cari nama mahasiswa..." 
                    value="{{ request('search') }}"
                    style="background: rgba(255, 255, 255, 0.12); border: 1px solid rgba(224, 231, 255, 0.30); border-radius: 8px; color: #F8FAFC; width: 100%; padding: 10px 16px 10px 40px; font-size: 0.875rem; transition: all 0.3s ease; backdrop-filter: blur(8px);"
                    onfocus="this.style.background='rgba(255, 255, 255, 0.20)'; this.style.borderColor='#6366F1'; this.style.boxShadow='0 0 0 3px rgba(99, 102, 241, 0.15)';"
                    onblur="this.style.background='rgba(255, 255, 255, 0.12)'; this.style.borderColor='rgba(224, 231, 255, 0.30)'; this.style.boxShadow='none';"
                >
                <i class="fas fa-search" style="position: absolute; left: 12px; top: 12px; color: #94A3B8;"></i>
            </div>

            <!-- Filters Row -->
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-3 items-end">
                <!-- Tahun Filter -->
                <div>
                    <label style="display: block; font-size: 0.75rem; font-weight: 600; color: #E0E7FF; margin-bottom: 4px;">Tahun</label>
                    <select name="tahun" 
                        style="background: rgba(255, 255, 255, 0.12); border: 1px solid rgba(224, 231, 255, 0.30); border-radius: 8px; color: #F8FAFC; width: 100%; padding: 8px 12px; height: 40px; font-size: 0.875rem; transition: all 0.3s ease; backdrop-filter: blur(8px);"
                        onfocus="this.style.background='rgba(255, 255, 255, 0.20)'; this.style.borderColor='#6366F1'; this.style.boxShadow='0 0 0 3px rgba(99, 102, 241, 0.15)';"
                        onblur="this.style.background='rgba(255, 255, 255, 0.12)'; this.style.borderColor='rgba(224, 231, 255, 0.30)'; this.style.boxShadow='none';">
                        <option value="">Semua Tahun</option>
                        @foreach($tahunList as $tahun)
                            <option value="{{ $tahun }}" {{ request('tahun') == $tahun ? 'selected' : '' }}>
                                {{ $tahun }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <!-- Kategori Filter -->
                <div>
                    <label style="display: block; font-size: 0.75rem; font-weight: 600; color: #E0E7FF; margin-bottom: 4px;">Kategori</label>
                    <select name="kategori" 
                        style="background: rgba(255, 255, 255, 0.12); border: 1px solid rgba(224, 231, 255, 0.30); border-radius: 8px; color: #F8FAFC; width: 100%; padding: 8px 12px; height: 40px; font-size: 0.875rem; transition: all 0.3s ease; backdrop-filter: blur(8px);"
                        onfocus="this.style.background='rgba(255, 255, 255, 0.20)'; this.style.borderColor='#6366F1'; this.style.boxShadow='0 0 0 3px rgba(99, 102, 241, 0.15)';"
                        onblur="this.style.background='rgba(255, 255, 255, 0.12)'; this.style.borderColor='rgba(224, 231, 255, 0.30)'; this.style.boxShadow='none';">
                        <option value="">Semua Kategori</option>
                        <option value="Akademik" {{ request('kategori') === 'Akademik' ? 'selected' : '' }}>Akademik</option>
                        <option value="Non-Akademik" {{ request('kategori') === 'Non-Akademik' ? 'selected' : '' }}>Non-Akademik</option>
                        <option value="Olahraga" {{ request('kategori') === 'Olahraga' ? 'selected' : '' }}>Olahraga</option>
                        <option value="Seni" {{ request('kategori') === 'Seni' ? 'selected' : '' }}>Seni & Budaya</option>
                        <option value="Lainnya" {{ request('kategori') === 'Lainnya' ? 'selected' : '' }}>Lainnya</option>
                    </select>
                </div>

                <!-- Apply Button -->
                <div class="flex gap-2">
                    <button type="submit" 
                        style="flex: 1; padding: 8px 16px; background: #6366F1; color: #FFFFFF; border: none; border-radius: 8px; font-weight: 600; font-size: 0.875rem; transition: all 0.3s ease; cursor: pointer;"
                        onmouseover="this.style.background='#4F46E5';"
                        onmouseout="this.style.background='#6366F1';">
                        <i class="fas fa-filter mr-2"></i>
                        Terapkan
                    </button>
                    <a href="{{ route('prestasi.index') }}" 
                        style="padding: 8px 16px; background: rgba(255, 255, 255, 0.12); color: #F8FAFC; border: 1px solid rgba(224, 231, 255, 0.30); border-radius: 8px; font-weight: 600; font-size: 0.875rem; transition: all 0.3s ease; cursor: pointer; display: inline-flex; align-items: center; text-decoration: none;"
                        onmouseover="this.style.background='rgba(255, 255, 255, 0.20)';"
                        onmouseout="this.style.background='rgba(255, 255, 255, 0.12)';">
                        <i class="fas fa-redo"></i>
                    </a>
                </div>
            </div>
        </form>
    </div>

    <!-- Content Section -->
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 pb-12" style="position: relative; z-index: 2;">
        @if($prestasi->count() > 0)
            <!-- Prestasi Grid -->
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4 mb-8">
                @foreach($prestasi as $item)
                <div style="background: rgba(255, 255, 255, 0.18); backdrop-filter: blur(8px); border: 1px solid rgba(224, 231, 255, 0.30); border-radius: 12px; padding: 12px; overflow: hidden; box-shadow: 0 4px 16px rgba(2, 6, 23, 0.08); transition: all 0.3s ease;"
                    onmouseover="this.style.background='rgba(255, 255, 255, 0.25)'; this.style.borderColor='#6366F1'; this.style.boxShadow='0 8px 32px rgba(99, 102, 241, 0.2)'; this.style.transform='translateY(-2px)';"
                    onmouseout="this.style.background='rgba(255, 255, 255, 0.18)'; this.style.borderColor='rgba(224, 231, 255, 0.30)'; this.style.boxShadow='0 4px 16px rgba(2, 6, 23, 0.08)'; this.style.transform='none';">
                    <!-- Header dengan Badge -->
                    <div style="display: flex; justify-content: space-between; align-items: flex-start; margin-bottom: 8px;">
                        <span style="font-size: 0.875rem; font-weight: 700; color: #F8FAFC; flex: 1;">
                            {{ $item->nama_prestasi }}
                        </span>
                        @php
                            $categoryColors = [
                                'Akademik' => 'background: rgba(59, 130, 246, 0.2); color: #60A5FA;',
                                'Olahraga' => 'background: rgba(34, 197, 94, 0.2); color: #86EFAC;',
                                'Seni' => 'background: rgba(168, 85, 247, 0.2); color: #D8B4FE;',
                                'Non-Akademik' => 'background: rgba(251, 146, 60, 0.2); color: #FDBA74;',
                                'Lainnya' => 'background: rgba(148, 163, 184, 0.2); color: #CBD5E1;',
                            ];
                            $badgeStyle = $categoryColors[$item->kategori] ?? 'background: rgba(148, 163, 184, 0.2); color: #CBD5E1;';
                        @endphp
                        <span style="display: inline-block; padding: 4px 8px; font-size: 0.75rem; font-weight: 700; border-radius: 4px; margin-left: 8px; white-space: nowrap; {{ $badgeStyle }}">
                            {{ $item->kategori }}
                        </span>
                    </div>

                    <!-- Mahasiswa Name -->
                    <p style="font-size: 0.75rem; font-weight: 600; color: #CBD5E1; margin-bottom: 4px; overflow: hidden; text-overflow: ellipsis; white-space: nowrap;">
                        {{ $item->user->name ?? 'Tidak diketahui' }}
                    </p>

                    <!-- Date -->
                    <p style="font-size: 0.75rem; color: #94A3B8; margin-bottom: 8px;">
                        {{ \Carbon\Carbon::parse($item->tanggal_mulai)->format('d M Y') }}
                    </p>

                    <!-- Description -->
                    <p style="font-size: 0.75rem; color: #CBD5E1; margin-bottom: 12px; display: -webkit-box; -webkit-line-clamp: 2; -webkit-box-orient: vertical; overflow: hidden;">
                        {{ $item->deskripsi ?? '-' }}
                    </p>

                    <!-- Action Buttons -->
                    <div style="display: flex; gap: 8px;">
                        <!-- Share Button -->
                        <button onclick="shareItem('{{ urlencode($item->nama_prestasi) }}', '{{ urlencode($item->user->name ?? 'Unknown') }}')" 
                            style="width: 100%; display: flex; align-items: center; justify-content: center; gap: 4px; padding: 8px; background: rgba(99, 102, 241, 0.2); color: #818CF8; border: 1px solid rgba(99, 102, 241, 0.3); border-radius: 6px; transition: all 0.3s ease; font-size: 0.75rem; font-weight: 600; cursor: pointer;"
                            onmouseover="this.style.background='rgba(99, 102, 241, 0.3)';"
                            onmouseout="this.style.background='rgba(99, 102, 241, 0.2)';">
                            <i class="fas fa-share-alt"></i>
                            Share
                        </button>
                    </div>
                </div>
                @endforeach
            </div>

            <!-- Pagination -->
            <div style="display: flex; justify-content: center; margin-top: 32px; position: relative; z-index: 2;">
                {{ $prestasi->links('pagination::tailwind') }}
            </div>
        @else
            <!-- Empty State -->
            <div style="text-align: center; padding: 64px 16px; background: rgba(255, 255, 255, 0.12); backdrop-filter: blur(8px); border: 1px solid rgba(224, 231, 255, 0.25); border-radius: 12px;">
                <div style="display: inline-flex; align-items: center; justify-content: center; width: 64px; height: 64px; background: rgba(255, 255, 255, 0.12); border-radius: 50%; margin-bottom: 16px;">
                    <i class="fas fa-inbox" style="font-size: 24px; color: #94A3B8;"></i>
                </div>
                <h3 style="font-size: 1.125rem; font-weight: 700; color: #F8FAFC; margin-bottom: 8px;">Tidak ada prestasi ditemukan</h3>
                <p style="color: #CBD5E1; margin-bottom: 24px; max-width: 448px; margin-left: auto; margin-right: auto; font-size: 0.875rem;">
                    Coba ubah kriteria pencarian atau filter yang Anda gunakan untuk menemukan prestasi yang Anda cari.
                </p>
                @auth
                    <a href="{{ route('prestasi.create') }}" 
                        style="display: inline-flex; align-items: center; gap: 8px; padding: 10px 20px; background: #6366F1; color: #FFFFFF; border: none; border-radius: 8px; transition: all 0.3s ease; font-weight: 600; font-size: 0.875rem; text-decoration: none; cursor: pointer;"
                        onmouseover="this.style.background='#4F46E5'; this.style.transform='translateY(-2px)'; this.style.boxShadow='0 6px 20px rgba(99, 102, 241, 0.35)';"
                        onmouseout="this.style.background='#6366F1'; this.style.transform='none'; this.style.boxShadow='none';">
                        <i class="fas fa-plus"></i>
                        Ajukan Prestasi Baru
                    </a>
                @endauth
            </div>
        @endif
    </div>
</div>

<style>
    option {
        background: #1F2937;
        color: #F8FAFC;
    }
    
    .line-clamp-2 {
        display: -webkit-box;
        -webkit-line-clamp: 2;
        -webkit-box-orient: vertical;
        overflow: hidden;
    }
</style>

<!-- Share Modal -->
<div id="shareModal" style="display: none; position: fixed; inset: 0; background: rgba(0, 0, 0, 0.5); z-index: 50; display: flex; align-items: flex-end; justify-content: center;">
    <div style="background: rgba(15, 23, 42, 0.95); backdrop-filter: blur(12px); border: 1px solid rgba(224, 231, 255, 0.25); border-radius: 12px 12px 0 0; box-shadow: 0 8px 32px rgba(2, 6, 23, 0.3); padding: 16px; width: 100%; max-width: 384px;">
        <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 16px;">
            <h4 style="font-weight: 700; color: #F8FAFC;">Bagikan Prestasi</h4>
            <button onclick="closeShareModal()" style="background: none; border: none; color: #94A3B8; cursor: pointer; font-size: 1.25rem;">
                <i class="fas fa-times"></i>
            </button>
        </div>
        <div style="space: 8px;">
            <button onclick="copyToClipboard()" style="width: 100%; text-align: left; padding: 12px 16px; background: rgba(255, 255, 255, 0.08); border: none; border-radius: 8px; display: flex; align-items: center; gap: 12px; transition: all 0.3s ease; cursor: pointer; color: #F8FAFC; font-weight: 600; font-size: 0.875rem;"
                onmouseover="this.style.background='rgba(255, 255, 255, 0.15)';"
                onmouseout="this.style.background='rgba(255, 255, 255, 0.08)';">
                <i class="fas fa-copy" style="color: #6366F1;"></i>
                <span>Salin Link</span>
            </button>
            <a href="" id="shareWhatsapp" target="_blank" style="width: 100%; text-align: left; padding: 12px 16px; background: rgba(255, 255, 255, 0.08); border: none; border-radius: 8px; display: flex; align-items: center; gap: 12px; transition: all 0.3s ease; cursor: pointer; color: #F8FAFC; font-weight: 600; font-size: 0.875rem; text-decoration: none; margin-top: 8px;"
                onmouseover="this.style.background='rgba(255, 255, 255, 0.15)';"
                onmouseout="this.style.background='rgba(255, 255, 255, 0.08)';">
                <i class="fas fa-whatsapp" style="color: #22C55E;"></i>
                <span>WhatsApp</span>
            </a>
            <a href="" id="shareTwitter" target="_blank" style="width: 100%; text-align: left; padding: 12px 16px; background: rgba(255, 255, 255, 0.08); border: none; border-radius: 8px; display: flex; align-items: center; gap: 12px; transition: all 0.3s ease; cursor: pointer; color: #F8FAFC; font-weight: 600; font-size: 0.875rem; text-decoration: none; margin-top: 8px;"
                onmouseover="this.style.background='rgba(255, 255, 255, 0.15)';"
                onmouseout="this.style.background='rgba(255, 255, 255, 0.08)';">
                <i class="fas fa-twitter" style="color: #60A5FA;"></i>
                <span>Twitter</span>
            </a>
            <a href="" id="shareFacebook" target="_blank" style="width: 100%; text-align: left; padding: 12px 16px; background: rgba(255, 255, 255, 0.08); border: none; border-radius: 8px; display: flex; align-items: center; gap: 12px; transition: all 0.3s ease; cursor: pointer; color: #F8FAFC; font-weight: 600; font-size: 0.875rem; text-decoration: none; margin-top: 8px;"
                onmouseover="this.style.background='rgba(255, 255, 255, 0.15)';"
                onmouseout="this.style.background='rgba(255, 255, 255, 0.08)';">
                <i class="fas fa-facebook" style="color: #3B82F6;"></i>
                <span>Facebook</span>
            </a>
        </div>
    </div>
</div>

<script>
    function shareItem(prestasi, mahasiswa) {
        const text = `Prestasi: ${decodeURIComponent(prestasi)} | Oleh: ${decodeURIComponent(mahasiswa)}`;
        const url = window.location.href;
        
        document.getElementById('shareWhatsapp').href = `https://wa.me/?text=${encodeURIComponent(text + ' ' + url)}`;
        document.getElementById('shareTwitter').href = `https://twitter.com/intent/tweet?text=${encodeURIComponent(text)}&url=${encodeURIComponent(url)}`;
        document.getElementById('shareFacebook').href = `https://www.facebook.com/sharer/sharer.php?u=${encodeURIComponent(url)}`;
        
        document.getElementById('shareModal').style.display = 'flex';
    }

    function closeShareModal() {
        document.getElementById('shareModal').style.display = 'none';
    }

    function copyToClipboard() {
        navigator.clipboard.writeText(window.location.href).then(() => {
            alert('Link berhasil disalin!');
            closeShareModal();
        });
    }

    document.addEventListener('click', function(event) {
        const modal = document.getElementById('shareModal');
        if (modal.style.display === 'flex' && !event.target.closest('button[onclick*="shareItem"]') && !modal.contains(event.target)) {
            modal.style.display = 'none';
        }
    });
</script>
@endsection