
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
            <svg class="header-logo-img" viewBox="0 0 48 48" width="36" height="36" fill="none" aria-hidden="true">
                <defs>
                    <linearGradient id="headerLogoBg" x1="0%" y1="0%" x2="100%" y2="100%">
                        <stop offset="0%" stop-color="#4154f1" />
                        <stop offset="100%" stop-color="#012970" />
                    </linearGradient>
                    <linearGradient id="headerLogoSpark" x1="0%" y1="0%" x2="100%" y2="100%">
                        <stop offset="0%" stop-color="#38bdf8" />
                        <stop offset="100%" stop-color="#0ea5e9" />
                    </linearGradient>
                </defs>
                <rect width="48" height="48" rx="12" fill="url(#headerLogoBg)" />
                <rect x="0.75" y="0.75" width="46.5" height="46.5" rx="11.25" stroke="#ffffff" stroke-opacity="0.25" stroke-width="1.5" />
                <path d="M24 8L12 13V22C12 29.5 17.1 36.5 24 38.5C30.9 36.5 36 29.5 36 22V13L24 8Z" fill="#ffffff" fill-opacity="0.12" />
                <path d="M24 16C20 13 14 13.5 14 13.5V28C14 28 20 27.5 24 30.5V16Z" fill="#ffffff" />
                <path d="M24 16C28 13 34 13.5 34 13.5V28C34 28 28 27.5 24 30.5V16Z" fill="url(#headerLogoSpark)" />
                <circle cx="24" cy="11.5" r="2.2" fill="#38bdf8" stroke="#ffffff" stroke-width="1" />
                <line x1="24" y1="16" x2="24" y2="30.5" stroke="#012970" stroke-width="1.2" stroke-linecap="round" />
            </svg>
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

                @php
                    $headerPhotoSrc = $profileUser?->photo ? (str_starts_with($profileUser->photo, 'data:') || str_starts_with($profileUser->photo, 'http') ? $profileUser->photo : asset('storage/' . $profileUser->photo)) : null;
                @endphp

                <a class="nav-link nav-profile d-flex align-items-center pe-0" href="#" data-bs-toggle="dropdown" aria-expanded="false">
                    @if($headerPhotoSrc)
                        <img src="{{ $headerPhotoSrc }}" alt="Profile" class="rounded-circle header-profile-img">
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
                            @if($headerPhotoSrc)
                                <img src="{{ $headerPhotoSrc }}" alt="Foto Profil" class="profile-dropdown-avatar">
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
