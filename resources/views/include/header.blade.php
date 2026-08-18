<header id="header" class="header fixed-top d-flex align-items-center">
    @php
        $profileUser = auth()->user() ?? $currentUser ?? \App\Models\User::latest()->first();
        $profileInitials = collect(explode(' ', trim($profileUser?->name ?? 'Pengguna')))
            ->filter()
            ->take(2)
            ->map(fn ($part) => mb_strtoupper(mb_substr($part, 0, 1)))
            ->implode('');
    @endphp

    <div class="d-flex align-items-center gap-3">
        <a href="{{ url('template') }}" class="logo d-flex align-items-center gap-2 text-decoration-none">
            <img src="{{ asset('assets/img/logo.png') }}" alt="" aria-hidden="true" class="header-logo-img">
            <span class="d-none d-lg-inline-block brand-main">Sistem Manajemen BK</span>
            <span class="d-lg-none fw-semibold lh-1">BK</span>
        </a>

        <button type="button" class="btn btn-link p-0 border-0 shadow-none d-lg-none toggle-sidebar-btn" aria-label="Toggle navigation">
            <i class="bi bi-list fs-2"></i>
        </button>
    </div><!-- End Logo -->

    <div class="search-bar">
        <form class="search-form d-flex align-items-center" method="GET" action="#">
            <input type="text" name="query" placeholder="Cari..." title="Masukkan kata kunci pencarian">
            <button type="submit" title="Cari"><i class="bi bi-search"></i></button>
        </form>
    </div><!-- End Search Bar -->

    <nav class="header-nav ms-auto">
        <ul class="d-flex align-items-center">

            <li class="nav-item d-block d-lg-none">
                <a class="nav-link nav-icon search-bar-toggle " href="#">
                    <i class="bi bi-search"></i>
                </a>
            </li><!-- End Search Icon-->

            <li class="nav-item dropdown pe-3">

                <a class="nav-link nav-profile d-flex align-items-center pe-0" href="#" data-bs-toggle="dropdown">
                    @if($profileUser?->photo)
                        <img src="{{ asset('storage/' . $profileUser->photo) }}" alt="Profile" class="rounded-circle" style="width: 36px; height: 36px; object-fit: cover;">
                    @else
                        <span class="profile-avatar rounded-circle d-inline-flex align-items-center justify-content-center">
                            {{ $profileInitials ?: 'P' }}
                        </span>
                    @endif
                    <span class="d-none d-md-block dropdown-toggle ps-2">{{ $profileUser?->name ?? 'Pengguna' }}</span>
                </a><!-- End Profile Iamge Icon -->

                <ul class="dropdown-menu dropdown-menu-end dropdown-menu-arrow profile">
                    <li class="dropdown-header">
                        @if($profileUser?->photo)
                            <img src="{{ asset('storage/' . $profileUser->photo) }}" alt="Foto Profil" class="profile-dropdown-photo rounded-circle mb-3" style="width: 72px; height: 72px; object-fit: cover;">
                        @else
                            <img src="{{ asset('assets/img/profile-img.jpg') }}" alt="Foto Profil" class="profile-dropdown-photo rounded-circle mb-3" style="width: 72px; height: 72px; object-fit: cover;">
                        @endif
                        <h6>{{ $profileUser?->name ?? 'Pengguna' }}</h6>
                        <span>{{ $profileUser?->email ?? 'Email tidak tersedia' }}</span>
                    </li>
                    <li>
                        <hr class="dropdown-divider">
                    </li>

                    <li>
                        <a class="dropdown-item d-flex align-items-center" href="{{ route('profile.show') }}">
                            <i class="bi bi-person-gear"></i>
                            <span>Pengaturan Akun</span>
                        </a>
                    </li>
                    <li>
                        <hr class="dropdown-divider">
                    </li>


                    <li>
                        <a class="dropdown-item d-flex align-items-center" href="{{ route('help.center') }}">
                            <i class="bi bi-question-circle"></i>
                            <span>Butuh Bantuan?</span>
                        </a>
                    </li>
                    <li>
                        <hr class="dropdown-divider">
                    </li>

                    <li>
                        <form method="POST" action="{{ route('logout') }}">
                            @csrf
                            <button type="submit" class="dropdown-item d-flex align-items-center">
                                <i class="bi bi-box-arrow-right"></i>
                                <span>Keluar</span>
                            </button>
                        </form>
                    </li>

                </ul><!-- End Profile Dropdown Items -->
            </li><!-- End Profile Nav -->

        </ul>
    </nav><!-- End Icons Navigation -->

</header><!-- End Header -->
