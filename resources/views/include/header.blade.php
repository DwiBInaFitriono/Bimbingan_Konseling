
<header id="header" class="header fixed-top d-flex align-items-center">
    @php
        $profileUser = auth()->user() ?? $currentUser ?? null;
        $profileInitials = collect(explode(' ', trim($profileUser?->name ?? 'Pengguna')))
            ->filter()
            ->take(2)
            ->map(fn ($part) => mb_strtoupper(mb_substr($part, 0, 1)))
            ->implode('');
    @endphp

    <div class="d-flex align-items-center gap-3">
        <a href="{{ url('template') }}" class="logo d-flex align-items-center gap-2 text-decoration-none">
            <img src="{{ asset('assets/img/logo-bk.svg') }}?v={{ time() }}" alt="Logo BK" class="header-logo-img">
            <div class="d-none d-lg-flex flex-column justify-content-center">
                <span class="brand-main">Sistem Manajemen <span class="brand-accent"></span></span>
                <span class="brand-sub">Bimbingan &amp; Konseling</span>
            </div>
            <span class="d-lg-none fw-bold brand-main">SIM-<span class="brand-accent">BK</span></span>
        </a>

        <button type="button" class="btn btn-link p-0 border-0 shadow-none d-lg-none toggle-sidebar-btn" aria-label="Toggle navigation">
            <i class="bi bi-list fs-2"></i>
        </button>
    </div>

    <nav class="header-nav ms-auto">
        <ul class="d-flex align-items-center">

            <li class="nav-item dropdown pe-3">

                <a class="nav-link nav-profile d-flex align-items-center pe-0" href="#" data-bs-toggle="dropdown" aria-expanded="false">
                    @if($profileUser?->photo)
                        <img src="{{ asset('storage/' . $profileUser->photo) }}" alt="Profile" class="rounded-circle header-profile-img">
                    @else
                        <span class="profile-avatar rounded-circle d-inline-flex align-items-center justify-content-center">
                            {{ $profileInitials ?: 'P' }}
                        </span>
                    @endif
                    <span class="d-none d-md-block dropdown-toggle ps-2 text-truncate" style="max-width: 150px;">{{ $profileUser?->name ?? 'Pengguna' }}</span>
                </a>

                <ul class="dropdown-menu dropdown-menu-end dropdown-menu-arrow profile profile-dropdown-menu">

                    {{-- Profile Card Header --}}
                    <li class="profile-dropdown-head">
                        <div class="profile-dropdown-banner"></div>
                        <div class="profile-dropdown-avatar-wrap">
                            @if($profileUser?->photo)
                                <img src="{{ asset('storage/' . $profileUser->photo) }}" alt="Foto Profil" class="profile-dropdown-avatar">
                            @else
                                <div class="profile-dropdown-avatar-fallback">
                                    {{ $profileInitials ?: 'P' }}
                                </div>
                            @endif
                        </div>
                        <h6 class="profile-dropdown-name">{{ $profileUser?->name ?? 'Pengguna' }}</h6>
                        <span class="profile-dropdown-email">{{ $profileUser?->email ?? 'Email tidak tersedia' }}</span>
                    </li>

                    <li class="pt-1">
                        <a class="dropdown-item d-flex align-items-center gap-2" href="{{ route('profile.show') }}">
                            <i class="bi bi-person-gear"></i>
                            <span class="fw-semibold">Pengaturan Akun</span>
                        </a>
                    </li>

                    <li><hr class="dropdown-divider"></li>

                    <li>
                        <a class="dropdown-item d-flex align-items-center gap-2" href="{{ route('help.center') }}">
                            <i class="bi bi-question-circle"></i>
                            <span class="fw-semibold">Bantuan</span>
                        </a>
                    </li>

                    <li><hr class="dropdown-divider"></li>

                    <li class="pb-1">
                        <form method="POST" action="{{ route('logout') }}" class="m-0">
                            @csrf
                            <button type="submit" class="dropdown-item dropdown-item-logout d-flex align-items-center gap-2 text-danger">
                                <i class="bi bi-box-arrow-right"></i>
                                <span class="fw-semibold">Keluar</span>
                            </button>
                        </form>
                    </li>

                </ul>
            </li>

        </ul>
    </nav>

</header>
