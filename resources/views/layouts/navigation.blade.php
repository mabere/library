<nav class="navbar navbar-expand-lg navbar-light bg-white border-bottom">
    <div class="container">
        <a class="navbar-brand fw-semibold text-dark" href="{{ route('dashboard') }}">
            <span class="d-inline-flex align-items-center gap-2">
                <i class="bi bi-book-half"></i>
                {{ config('app.name', 'Perpustakaan') }}
            </span>
        </a>

        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#mainNavbar"
            aria-controls="mainNavbar" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>

        <div class="collapse navbar-collapse" id="mainNavbar">
            <ul class="navbar-nav me-auto mb-2 mb-lg-0">
                <li class="nav-item">
                    <a class="nav-link {{ request()->routeIs('dashboard') ? 'active' : '' }}" href="{{ route('dashboard') }}">
                        Dashboard
                    </a>
                </li>

                @auth
                    @php($user = auth()->user())

                    @if($user->hasRole('admin'))
                        <li class="nav-item dropdown">
                            <a class="nav-link dropdown-toggle {{ request()->routeIs('admin.*') ? 'active' : '' }}"
                               href="#" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                                Pengaturan
                            </a>
                            <ul class="dropdown-menu">
                                {{-- <li><hr class="dropdown-divider"></li> --}}
                                <li>
                                    <a class="dropdown-item" href="{{ route('admin.students.index') }}">
                                        Mahasiswa
                                    </a>
                                </li>
                                <li>
                                    <a class="dropdown-item" href="{{ route('admin.users.index') }}">
                                        Users
                                    </a>
                                </li>
                                <li>
                                    <a class="dropdown-item" href="{{ route('admin.departments.index') }}">
                                        Prodi
                                    </a>
                                </li>
                                <li>
                                    <a class="dropdown-item" href="{{ route('admin.activity_logs.index') }}">
                                        Activity Log
                                    </a>
                                </li>
                            </ul>
                        </li>
                        <li class="nav-item dropdown">
                            <a class="nav-link dropdown-toggle {{ request()->routeIs('admin.bebas_pustaka.*') || request()->routeIs('admin.skripsi.*') ? 'active' : '' }}"
                               href="#" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                                Layanan
                            </a>
                            <ul class="dropdown-menu">
                                <li>
                                    <a class="dropdown-item" href="{{ route('admin.bebas_pustaka.index') }}">
                                        Bebas Pustaka
                                    </a>
                                </li>
                                <li>
                                    <a class="dropdown-item" href="{{ route('admin.skripsi.index') }}">
                                        Skripsi
                                    </a>
                                </li>
                            </ul>
                        </li>
                        <li class="nav-item dropdown">
                            <a class="nav-link dropdown-toggle {{ request()->routeIs('letters.archive') || request()->routeIs('letters.report') ? 'active' : '' }}"
                               href="#" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                                Laporan
                            </a>
                            <ul class="dropdown-menu">
                                <li>
                                    <a class="dropdown-item" href="{{ route('admin.visitors.index') }}">
                                        Pengunjung
                                    </a>
                                </li>
                                <li>
                                    <a class="dropdown-item" href="{{ route('letters.archive') }}">
                                        Arsip
                                    </a>
                                </li>
                                <li>
                                    <a class="dropdown-item" href="{{ route('letters.report') }}">
                                        Laporan
                                    </a>
                                </li>
                            </ul>
                        </li>
                    @endif

                    @if($user->hasRole('mahasiswa'))
                        <li class="nav-item dropdown">
                            <a class="nav-link dropdown-toggle {{ request()->routeIs('mahasiswa.bebas_pustaka.*') || request()->routeIs('mahasiswa.skripsi.*') ? 'active' : '' }}"
                               href="#" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                                Layanan
                            </a>
                            <ul class="dropdown-menu">
                                <li>
                                    <a class="dropdown-item" href="{{ route('mahasiswa.bebas_pustaka.index') }}">
                                        Bebas Pustaka
                                    </a>
                                </li>
                                <li>
                                    <a class="dropdown-item" href="{{ route('mahasiswa.skripsi.index') }}">
                                         Skripsi
                                    </a>
                                </li>
                            </ul>
                        </li>
                    @endif

                    @if($user->hasRole('staf'))
                        <li class="nav-item dropdown">
                            <a class="nav-link dropdown-toggle {{ request()->routeIs('staf.bebas_pustaka.*') || request()->routeIs('staf.skripsi.*') ? 'active' : '' }}"
                               href="#" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                                Layanan
                            </a>
                            <ul class="dropdown-menu">
                                <li>
                                    <a class="dropdown-item" href="{{ route('staf.bebas_pustaka.index') }}">
                                        Bebas Pustaka
                                    </a>
                                </li>
                                <li>
                                    <a class="dropdown-item" href="{{ route('staf.skripsi.index') }}">
                                        Penyerahan Skripsi
                                    </a>
                                </li>
                            </ul>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link {{ request()->routeIs('staf.user_student.*') ? 'active' : '' }}"
                               href="{{ route('staf.user_student.index') }}">
                                Mapping
                            </a>
                        </li>
                        <li class="nav-item dropdown">
                            <a class="nav-link dropdown-toggle {{ request()->routeIs('letters.archive') || request()->routeIs('letters.report') ? 'active' : '' }}"
                               href="#" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                                Laporan
                            </a>
                            <ul class="dropdown-menu">
                                <li>
                                    <a class="dropdown-item" href="{{ route('staf.visitors.index') }}">
                                        Pengunjung
                                    </a>
                                </li>
                                <li>
                                    <a class="dropdown-item" href="{{ route('letters.archive') }}">
                                        Arsip
                                    </a>
                                </li>
                                <li>
                                    <a class="dropdown-item" href="{{ route('letters.report') }}">
                                        Laporan
                                    </a>
                                </li>
                            </ul>
                        </li>
                    @endif

                    @if($user->hasRole('kepala'))
                        <li class="nav-item dropdown">
                            <a class="nav-link dropdown-toggle {{ request()->routeIs('kepala.bebas_pustaka.*') || request()->routeIs('kepala.skripsi.*') ? 'active' : '' }}"
                               href="#" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                                Layanan
                            </a>
                            <ul class="dropdown-menu">
                                <li>
                                    <a class="dropdown-item" href="{{ route('kepala.bebas_pustaka.index') }}">
                                        Bebas Pustaka
                                    </a>
                                </li>
                                <li>
                                    <a class="dropdown-item" href="{{ route('kepala.skripsi.index') }}">
                                        Skripsi
                                    </a>
                                </li>
                            </ul>
                        </li>
                        <li class="nav-item dropdown">
                            <a class="nav-link dropdown-toggle {{ request()->routeIs('letters.archive') || request()->routeIs('letters.report') ? 'active' : '' }}"
                               href="#" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                                Laporan
                            </a>
                            <ul class="dropdown-menu">
                                <li>
                                    <a class="dropdown-item" href="{{ route('letters.archive') }}">
                                        Arsip
                                    </a>
                                </li>
                                <li>
                                    <a class="dropdown-item" href="{{ route('letters.report') }}">
                                        Laporan
                                    </a>
                                </li>
                            </ul>
                        </li>
                    @endif

                    @if($user->hasRole('kaprodi'))
                        <li class="nav-item dropdown">
                            <a class="nav-link dropdown-toggle {{ request()->routeIs('kaprodi.bebas_pustaka.*') || request()->routeIs('kaprodi.skripsi.*') ? 'active' : '' }}"
                               href="#" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                                Layanan
                            </a>
                            <ul class="dropdown-menu">
                                <li>
                                    <a class="dropdown-item" href="{{ route('kaprodi.bebas_pustaka.index') }}">
                                        Bebas Pustaka
                                    </a>
                                </li>
                                <li>
                                    <a class="dropdown-item" href="{{ route('kaprodi.skripsi.index') }}">
                                        Skripsi
                                    </a>
                                </li>
                            </ul>
                        </li>
                        <li class="nav-item dropdown">
                            <a class="nav-link dropdown-toggle {{ request()->routeIs('letters.archive') || request()->routeIs('letters.report') ? 'active' : '' }}"
                               href="#" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                                Laporan
                            </a>
                            <ul class="dropdown-menu">
                                <li>
                                    <a class="dropdown-item" href="{{ route('letters.archive') }}">
                                        Arsip
                                    </a>
                                </li>
                                <li>
                                    <a class="dropdown-item" href="{{ route('letters.report') }}">
                                        Laporan
                                    </a>
                                </li>
                            </ul>
                        </li>
                    @endif
                @endauth
                @auth
                    @if(!$user->hasRole('mahasiswa'))
                        <li class="nav-item">
                            <a class="nav-link {{ request()->routeIs('verify.form') ? 'active' : '' }}" href="{{ route('verify.form') }}">
                                Verifikasi Surat
                            </a>
                        </li>
                    @endif
                @endauth
                @guest
                    <li class="nav-item">
                        <a class="nav-link {{ request()->routeIs('verify.form') ? 'active' : '' }}" href="{{ route('verify.form') }}">
                            Verifikasi Surat
                        </a>
                    </li>
                @endguest
            </ul>
            <ul class="navbar-nav ms-auto mb-2 mb-lg-0">
                <li>
                    <a class="nav-link {{ request()->routeIs('notifications.*') ? 'active' : '' }}"
                        href="{{ route('notifications.index') }}">
                        <i class="bi bi-bell"></i>
                        @php($unread = auth()->user()->unreadNotifications()->count())
                        @if($unread > 0)
                            <span class="badge text-bg-danger">{{ $unread }}</span>
                        @endif
                    </a>
                </li>
            </ul>
            @auth
                <div class="dropdown">
                    <button class="btn btn-outline-secondary dropdown-toggle" type="button" data-bs-toggle="dropdown" aria-expanded="false">
                        {{ Auth::user()->name }}
                    </button>
                    <ul class="dropdown-menu dropdown-menu-end">
                        <li>
                            <a class="dropdown-item" href="{{ route('profile.edit') }}">Profile</a>
                        </li>
                        <li><hr class="dropdown-divider"></li>
                        <li>
                            <form method="POST" action="{{ route('logout') }}">
                                @csrf
                                <button type="submit" class="dropdown-item">Log Out</button>
                            </form>
                        </li>
                    </ul>
                </div>
            @endauth

        </div>
    </div>
</nav>
