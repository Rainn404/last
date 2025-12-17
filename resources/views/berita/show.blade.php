<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="UTF-8" />
<meta name="viewport" content="width=device-width, initial-scale=1" />
<title>{{ $berita->judul }} | HIMA-TI</title>
<link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700&display=swap" rel="stylesheet" />
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">

<style>
  :root{ --brand:#007BFF; --muted:#6b7280; --card:#fff; --ring:#edf0f4; }
  *{box-sizing:border-box}
  body{ margin:0; color:#0f172a; background:#f3f4f6; font-family:'Poppins',system-ui,-apple-system,Segoe UI,Roboto,Arial,Helvetica,sans-serif; }

  /* Navbar full width */
  .navbar{ position:sticky; top:0; z-index:50; background:#fff; border-bottom:1px solid var(--ring); box-shadow:0 4px 16px rgba(0,0,0,.06); }
  .nav-wrap{ max-width:1200px; margin:0 auto; display:flex; align-items:center; gap:16px; padding:12px 18px; height:70px; }
  .brand-logo{ display:flex; align-items:center; gap:10px; text-decoration:none; color:#1f2937; font-weight:700; font-size:20px; }
  .brand-logo svg{ background:var(--brand); border-radius:6px; padding:4px }

  /* Page layout */
.page{
  width:100%;
  max-width:none;      /* ⬅️ ini kuncinya */
  margin:0;
  padding:28px 48px;   /* biar gak nempel tepi */
}


  .article{ background:var(--card); border:1px solid var(--ring); border-radius:16px; padding:22px; box-shadow:0 4px 12px rgba(0,0,0,.06); }
  .article h1{ font-size:34px; line-height:1.25; margin:8px 0 10px }
  .article .meta{ color:var(--muted); font-size:13px; margin-bottom:18px }
  .article img{ width:100%; height:auto; border-radius:12px; margin:14px 0 16px; display:block; }
  .article .content{
  text-align: justify;
  text-justify: inter-word;
  line-height: 1.9;
  hyphens: auto;
}


  /* Sidebar komentar */
  .sidebar{ position:relative; }
  .sticky{ position:sticky; top:90px; }
  .card{ background:#fff; border:1px solid var(--ring); border-radius:14px; box-shadow:0 4px 12px rgba(0,0,0,.05); }
  .card .card-header{ padding:14px 16px; border-bottom:1px solid var(--ring); font-weight:700 }
  .card .card-body{ padding:14px 16px }
  .form-group{ margin-bottom:12px }
  .form-control{ width:100%; padding:10px 12px; border:1px solid #d1d5db; border-radius:10px; font:inherit; }
  textarea.form-control{ min-height:110px; resize:vertical }
  .btn{ display:inline-block; padding:10px 16px; border:none; border-radius:10px; font-weight:700; cursor:pointer }
  .btn-primary{ background:var(--brand); color:#fff }
  .btn-icon{ padding:6px 10px; border:1px solid #d1d5db; background:#fff; border-radius:8px; font-weight:600; font-size:12px; cursor:pointer }
  .btn-icon:hover{ border-color:#9ca3af }
  .btn-danger{ background:#ef4444; color:#fff; border:1px solid #ef4444 }
  .btn-danger:hover{ filter:brightness(.95) }
  .alert{ background:#ecfdf5; color:#065f46; border:1px solid #a7f3d0; padding:10px 12px; border-radius:10px; margin-bottom:12px; font-size:14px }

  .komentar{ display:flex; flex-direction:column; gap:10px; }
  .komentar-item{ border-bottom:1px dashed var(--ring); padding-bottom:10px }
  .komentar .meta{ font-size:12px; color:var(--muted); margin-bottom:6px; display:flex; gap:8px; align-items:center }
  .komentar .text{ white-space:pre-wrap; line-height:1.7 }
  .row-actions{ margin-left:auto; display:flex; gap:8px }
  .hidden{ display:none }
</style>
</head>
<body>
  <!-- NAVBAR -->
  <nav class="navbar">
    <div class="nav-wrap">
      <a href="{{ route('berita.index') }}" class="brand-logo">
        <svg width="28" height="28" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
          <path d="M4 17.3V6.7A.9.9 0 0 1 4.9 5.8H19.1A.9.9 0 0 1 20 6.7V17.3A.9.9 0 0 1 19.1 18.2H4.9A.9.9 0 0 1 4 17.3Z" stroke="#1f2937" stroke-width="1.5"/>
          <path d="M14.55 12L9.45 9.18V14.82L14.55 12Z" stroke="#1f2937" stroke-width="1.5" stroke-linejoin="round"/>
        </svg>
        <span>HIMA-TI</span>
      </a>
    </div>
  </nav>

 <main class="page">

  <!-- ARTIKEL -->
  <article class="article">

    <a href="{{ route('berita.index') }}"
       style="text-decoration:none;color:#007BFF;font-weight:700;display:inline-block;margin-bottom:12px">
      &larr; Kembali ke Daftar Berita
    </a>

    <h1>{{ $berita->judul }}</h1>

    <div class="meta">
      {{ \Carbon\Carbon::parse($berita->created_at)->format('d F Y') }}
      @if($berita->nama_penulis) • Penulis: {{ $berita->nama_penulis }} @endif
    </div>

    @if($berita->foto)
    <img src="{{ Storage::url($berita->foto) }}" alt="{{ $berita->judul }}">
@endif

         alt="{{ $berita->judul }}"
         class="img-fluid">

    <div class="content">
      {!! nl2br(e($berita->isi)) !!}
    </div>

    <div class="modal fade" id="loginModal" tabindex="-1">
  <div class="modal-dialog modal-dialog-centered">
    <div class="modal-content">

      <div class="modal-header">
        <h5 class="modal-title">Login Diperlukan</h5>
        <button type="button" class="btn-close" onclick="closeLoginModal()"></button>
      </div>

      <div class="modal-body text-center">
        <p>
          Anda belum mempunyai akun,<br>
          silakan login untuk menambahkan komentar.
        </p>
      </div>

      <div class="modal-footer justify-content-center">
        <a href="{{ route('login') }}" class="btn btn-primary">
          Login
        </a>
        <button type="button" class="btn btn-secondary"
                onclick="closeLoginModal()">
          Batal
        </button>
      </div>

    </div>
  </div>
</div>

      <!-- KOMENTAR -->
  <section class="card" id="komentar" style="margin-top:40px">

    <div class="card-header">Komentar</div>

    <div class="card-body komentar">

      @forelse($berita->komentar as $k)
        <div class="komentar-item">
          <div class="meta">
            <strong>{{ $k->nama }}</strong>
            <span>• {{ $k->created_at->diffForHumans() }}</span>
          </div>
          <div class="text">{{ $k->isi }}</div>
        </div>
      @empty
        <p style="color:#6b7280">Belum ada komentar.</p>
      @endforelse

      <hr style="border:none;border-top:1px solid #e5e7eb;margin:20px 0">

    @if(auth()->check())
    {{-- USER SUDAH LOGIN --}}
    <form action="{{ route('berita.komentar.store', $berita->id_berita) }}" method="POST">
        @csrf

        <input type="text" name="nama" class="form-control mb-2"
               placeholder="Nama (opsional)"
               value="{{ auth()->user()->name ?? '' }}">

        <textarea name="isi" class="form-control mb-2"
                  placeholder="Tulis komentar..." required></textarea>

        <button class="btn btn-primary">Kirim Komentar</button>
    </form>
@else
    {{-- USER BELUM LOGIN --}}
    <textarea class="form-control mb-2"
              placeholder="Tulis komentar..."
              onclick="showLoginModal()"
              readonly></textarea>

    <button class="btn btn-primary"
            onclick="showLoginModal()">
        Kirim Komentar
    </button>
@endif

    </div>
  </section>

  </article>

</main>

  <script>
    function toggleEdit(id){
      const editForm = document.getElementById('edit-form-'+id);
      const viewText = document.getElementById('view-text-'+id);
      if(editForm){ editForm.classList.toggle('hidden'); }
      if(viewText){ viewText.classList.toggle('hidden'); }
      if(editForm && !editForm.classList.contains('hidden')) {
        editForm.scrollIntoView({behavior:'smooth', block:'center'});
      }
    }
    function showLoginModal() {
    let modal = new bootstrap.Modal(document.getElementById('loginModal'));
    modal.show();
}

function closeLoginModal() {
    let modalEl = document.getElementById('loginModal');
    let modal = bootstrap.Modal.getInstance(modalEl);
    modal.hide();
}
  </script>
  <!-- LOGIN REQUIRED MODAL -->
<div class="modal fade" id="loginModal" tabindex="-1">
  <div class="modal-dialog modal-dialog-centered">
    <div class="modal-content border-0 shadow-lg rounded-4">

      <div class="modal-body text-center p-4">
        <div style="font-size:48px;margin-bottom:10px">🔒</div>

        <h5 class="fw-bold mb-2">Login Diperlukan</h5>

        <p class="text-muted mb-4">
          Silakan login/Buat Akun terlebih dahulu<br>
          untuk menambahkan komentar.
        </p>

        <div class="d-flex justify-content-center gap-2">
          <a href="{{ route('login') }}" class="btn btn-primary px-4">
            Login
          </a>
          <button type="button"
                  class="btn btn-outline-secondary px-4"
                  onclick="closeLoginModal()">
            Batal
          </button>
        </div>
      </div>

    </div>
  </div>
</div>
<!-- BOOTSTRAP JS -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>

<script>
function showLoginModal() {
    let modal = new bootstrap.Modal(document.getElementById('loginModal'));
    modal.show();
}

function closeLoginModal() {
    let modalEl = document.getElementById('loginModal');
    let modal = bootstrap.Modal.getInstance(modalEl);
    modal.hide();
}
</script>

</body>
</html>
