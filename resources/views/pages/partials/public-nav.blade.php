@php
    $headerClass = $headerClass ?? 'header';
    $navClass = $navClass ?? 'd-flex align-items-center gap-3 flex-wrap ms-auto';
@endphp

<header class="{{ $headerClass }}">
    <div class="container-lg">
        <div class="d-flex justify-content-between align-items-center gap-3">
            <a href="{{ url('/') }}" class="logo">
                <i class="bi bi-book"></i>
                <span class="d-none d-sm-inline">Perpustakaan Digital</span>
            </a>
            <nav class="{{ $navClass }}">
                <a class="nav-link" href="{{ url('/') }}">Beranda</a>
                <a class="nav-link" href="{{ url('/bantuan') }}">Bantuan</a>
                <a class="nav-link" href="{{ url('/contact') }}">Contact</a>
                <a class="nav-link" href="{{ url('/tentang') }}">Tentang</a>
                @auth
                    <a class="btn btn-outline-primary btn-sm" href="{{ route('dashboard') }}">Dashboard</a>
                    <form method="POST" action="{{ route('logout') }}" class="d-inline">
                        @csrf
                        <button type="submit" class="btn btn-primary btn-sm">Logout</button>
                    </form>
                @else
                    <a class="btn btn-outline-primary btn-sm" href="{{ route('login') }}">Masuk</a>
                    <a class="btn btn-primary btn-sm" href="{{ route('register') }}">Daftar</a>
                @endauth
                <button class="btn btn-theme" id="themeToggle" aria-label="Toggle theme">
                    <span class="icon-sun"><i class="bi bi-sun"></i></span>
                    <span class="icon-moon"><i class="bi bi-moon"></i></span>
                </button>
            </nav>
        </div>
    </div>
</header>
