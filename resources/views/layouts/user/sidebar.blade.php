<nav id="sidebar" class="sidebar bg-white">
  <div class="sidebar-header bg-primary text-white text-center py-4">
    <h4 class="mb-0 fw-bold">HIMA-TI</h4>
    <small class="opacity-75">Sistem Informasi</small>
  </div>

  <ul class="sidebar-nav list-unstyled px-2 mt-3">
    <li class="nav-item">
      <a href="{{ route('home') }}"
         class="nav-link d-flex align-items-center {{ Request::routeIs('home') ? 'active' : '' }}">
        <i class="fas fa-home me-3"></i> Home
      </a>
    </li>

    <li class="nav-item">
      <a href="{{ route('berita.index') }}"
         class="nav-link d-flex align-items-center {{ Request::routeIs('berita.*') ? 'active' : '' }}">
        <i class="fas fa-newspaper me-3"></i> Berita
      </a>
    </li>

    <li class="nav-item">
      <a href="{{ route('pendaftaran.index') }}"
         class="nav-link d-flex align-items-center {{ Request::routeIs('pendaftaran.*') ? 'active' : '' }}">
        <i class="fas fa-user-check me-3"></i> Pendaftaran
      </a>
    </li>

    <li class="nav-divider my-3"></li>

    @guest
      <li class="nav-item">
        <a href="{{ route('login') }}" class="nav-link d-flex align-items-center text-primary">
          <i class="fas fa-sign-in-alt me-3"></i> Masuk / Login
        </a>
      </li>
    @endguest
  </ul>
</nav>
