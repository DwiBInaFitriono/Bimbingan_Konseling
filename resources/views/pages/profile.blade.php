<!DOCTYPE html>
<html lang="en">

<head>
    @include('include.style')
</head>

<body>
    @php
        $pageUser = $user ?? auth()->user();
    @endphp

    @include('include.header')
    @include('include.sidebar')

    <main id="main" class="main">
        <div class="pagetitle">
            <h1>Profil Saya</h1>
            <nav>
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Beranda</a></li>
                    <li class="breadcrumb-item active">Profil Saya</li>
                </ol>
            </nav>
        </div>

        {{-- Flash Messages --}}

        @if($errors->any())
            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                <i class="bi bi-exclamation-triangle me-1"></i>
                <ul class="mb-0">
                    @foreach($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @endif

        <section class="section profile">
            <div class="row">
                {{-- Left Column: Profile Card --}}
                <div class="col-lg-4">
                    <div class="card">
                        <div class="card-body text-center py-5">
                            <img src="{{ asset('assets/img/profile-img.jpg') }}" alt="Foto Profil" class="rounded-circle mb-3" style="width:120px;height:120px;object-fit:cover;">
                            <h4 class="mb-1">{{ $pageUser?->name ?? 'Pengguna' }}</h4>
                            <p class="text-muted mb-3">{{ $pageUser?->email ?? '-' }}</p>
                            <span class="badge bg-primary fs-6">
                                <i class="bi bi-shield-check me-1"></i>Pengguna Aktif
                            </span>
                        </div>
                    </div>

                    {{-- Quick Info Card --}}
                    <div class="card">
                        <div class="card-body pt-4">
                            <h5 class="card-title">Informasi Akun</h5>

                            <div class="d-flex align-items-center mb-3">
                                <div class="rounded-circle bg-light d-flex align-items-center justify-content-center me-3" style="width:40px;height:40px;">
                                    <i class="bi bi-person text-primary"></i>
                                </div>
                                <div>
                                    <small class="text-muted d-block">Nama Lengkap</small>
                                    <span class="fw-semibold">{{ $pageUser?->name ?? '-' }}</span>
                                </div>
                            </div>

                             <div class="d-flex align-items-center mb-3">
                                <div class="rounded-circle bg-light d-flex align-items-center justify-content-center me-3" style="width:40px;height:40px;">
                                    <i class="bi bi-card-text text-primary"></i>
                                </div>
                                <div>
                                    <small class="text-muted d-block">NIP</small>
                                    <span class="fw-semibold">{{ $pageUser?->nip ?? '-' }}</span>
                                </div>
                            </div>

                            <div class="d-flex align-items-center mb-3">
                                <div class="rounded-circle bg-light d-flex align-items-center justify-content-center me-3" style="width:40px;height:40px;">
                                    <i class="bi bi-envelope text-primary"></i>
                                </div>
                                <div>
                                    <small class="text-muted d-block">Email</small>
                                    <span class="fw-semibold">{{ $pageUser?->email ?? '-' }}</span>
                                </div>
                            </div>

                            <div class="d-flex align-items-center mb-3">
                                <div class="rounded-circle bg-light d-flex align-items-center justify-content-center me-3" style="width:40px;height:40px;">
                                    <i class="bi bi-calendar text-primary"></i>
                                </div>
                                <div>
                                    <small class="text-muted d-block">Bergabung Sejak</small>
                                    <span class="fw-semibold">{{ $pageUser?->created_at ? $pageUser->created_at->format('d M Y') : '-' }}</span>
                                </div>
                            </div>

                            <div class="d-flex align-items-center">
                                <div class="rounded-circle bg-light d-flex align-items-center justify-content-center me-3" style="width:40px;height:40px;">
                                    <i class="bi bi-clock text-primary"></i>
                                </div>
                                <div>
                                    <small class="text-muted d-block">Terakhir Diperbarui</small>
                                    <span class="fw-semibold">{{ $pageUser?->updated_at ? $pageUser->updated_at->format('d M Y, H:i') : '-' }}</span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- Right Column: Edit Profile --}}
                <div class="col-lg-8">
                    <div class="card">
                        <div class="card-body pt-3">
                            {{-- Tabs --}}
                            <ul class="nav nav-tabs nav-tabs-bordered" id="profileTab" role="tablist">
                                <li class="nav-item" role="presentation">
                                    <button class="nav-link active" id="overview-tab" data-bs-toggle="tab" data-bs-target="#profile-overview" type="button" role="tab">
                                        <i class="bi bi-eye me-1"></i>Ringkasan
                                    </button>
                                </li>
                                <li class="nav-item" role="presentation">
                                    <button class="nav-link" id="edit-tab" data-bs-toggle="tab" data-bs-target="#profile-edit" type="button" role="tab">
                                        <i class="bi bi-pencil me-1"></i>Edit Profil
                                    </button>
                                </li>
                                <li class="nav-item" role="presentation">
                                    <button class="nav-link" id="password-tab" data-bs-toggle="tab" data-bs-target="#profile-password" type="button" role="tab">
                                        <i class="bi bi-key me-1"></i>Ubah Password
                                    </button>
                                </li>
                            </ul>

                            <div class="tab-content pt-3" id="profileTabContent">
                                {{-- Overview Tab --}}
                                <div class="tab-pane fade show active" id="profile-overview" role="tabpanel">
                                    <h5 class="card-title">Detail Profil</h5>

                                    <div class="row mb-3">
                                        <div class="col-lg-3 col-md-4 label fw-semibold">Nama Lengkap</div>
                                        <div class="col-lg-9 col-md-8">{{ $pageUser?->name ?? '-' }}</div>
                                    </div>

                                    <div class="row mb-3">
                                        <div class="col-lg-3 col-md-4 label fw-semibold">NIP</div>
                                        <div class="col-lg-9 col-md-8">{{ $pageUser?->nip ?? '-' }}</div>
                                    </div>

                                    <div class="row mb-3">
                                        <div class="col-lg-3 col-md-4 label fw-semibold">Email</div>
                                        <div class="col-lg-9 col-md-8">{{ $pageUser?->email ?? '-' }}</div>
                                    </div>

                                    <div class="row mb-3">
                                        <div class="col-lg-3 col-md-4 label fw-semibold">Tanggal Daftar</div>
                                        <div class="col-lg-9 col-md-8">{{ $pageUser?->created_at ? $pageUser->created_at->format('d F Y, H:i') : '-' }}</div>
                                    </div>

                                    <div class="row mb-3">
                                        <div class="col-lg-3 col-md-4 label fw-semibold">Terakhir Diperbarui</div>
                                        <div class="col-lg-9 col-md-8">{{ $pageUser?->updated_at ? $pageUser->updated_at->diffForHumans() : '-' }}</div>
                                    </div>

                                    <div class="mt-4">
                                        <a href="{{ route('settings.index') }}" class="btn btn-primary">
                                            <i class="bi bi-gear me-1"></i>Buka Pengaturan Akun
                                        </a>
                                    </div>
                                </div>

                                {{-- Edit Profile Tab --}}
                                <div class="tab-pane fade" id="profile-edit" role="tabpanel">
                                    <h5 class="card-title">Edit Profil</h5>

                                    <form method="POST" action="{{ route('profile.update') }}">
                                        @csrf

                                        <div class="row mb-3">
                                            <label for="profileName" class="col-md-4 col-lg-3 col-form-label">Nama Lengkap</label>
                                            <div class="col-md-8 col-lg-9">
                                                <input name="name" type="text" class="form-control @error('name') is-invalid @enderror" id="profileName" value="{{ old('name', $pageUser?->name) }}">
                                                @error('name')
                                                    <div class="invalid-feedback">{{ $message }}</div>
                                                @enderror
                                            </div>
                                        </div>

                                        <div class="row mb-3">
                                            <label for="profileNip" class="col-md-4 col-lg-3 col-form-label">NIP</label>
                                            <div class="col-md-8 col-lg-9">
                                                <input name="nip" type="text" class="form-control @error('nip') is-invalid @enderror" id="profileNip" value="{{ old('nip', $pageUser?->nip) }}" placeholder="Masukkan NIP Anda...">
                                                @error('nip')
                                                    <div class="invalid-feedback">{{ $message }}</div>
                                                @enderror
                                            </div>
                                        </div>

                                        <div class="row mb-3">
                                            <label for="profileEmail" class="col-md-4 col-lg-3 col-form-label">Email</label>
                                            <div class="col-md-8 col-lg-9">
                                                <input name="email" type="email" class="form-control @error('email') is-invalid @enderror" id="profileEmail" value="{{ old('email', $pageUser?->email) }}">
                                                @error('email')
                                                    <div class="invalid-feedback">{{ $message }}</div>
                                                @enderror
                                            </div>
                                        </div>

                                        <div class="text-center">
                                            <button type="submit" class="btn btn-primary">
                                                <i class="bi bi-check-lg me-1"></i>Simpan Perubahan
                                            </button>
                                        </div>
                                    </form>
                                </div>

                                {{-- Change Password Tab --}}
                                <div class="tab-pane fade" id="profile-password" role="tabpanel">
                                    <h5 class="card-title">Ubah Password</h5>


                                    <form method="POST" action="{{ route('password.update') }}">
                                        @csrf

                                        <div class="row mb-3">
                                            <label for="currentPassword" class="col-md-4 col-lg-3 col-form-label">Password Saat Ini</label>
                                            <div class="col-md-8 col-lg-9">
                                                <input name="current_password" type="password" class="form-control @error('current_password') is-invalid @enderror" id="currentPassword">
                                                @error('current_password')
                                                    <div class="invalid-feedback">{{ $message }}</div>
                                                @enderror
                                            </div>
                                        </div>

                                        <div class="row mb-3">
                                            <label for="newPassword" class="col-md-4 col-lg-3 col-form-label">Password Baru</label>
                                            <div class="col-md-8 col-lg-9">
                                                <input name="password" type="password" class="form-control @error('password') is-invalid @enderror" id="newPassword">
                                                @error('password')
                                                    <div class="invalid-feedback">{{ $message }}</div>
                                                @enderror
                                            </div>
                                        </div>

                                        <div class="row mb-3">
                                            <label for="confirmPassword" class="col-md-4 col-lg-3 col-form-label">Konfirmasi Password</label>
                                            <div class="col-md-8 col-lg-9">
                                                <input name="password_confirmation" type="password" class="form-control" id="confirmPassword">
                                            </div>
                                        </div>

                                        <div class="text-center">
                                            <button type="submit" class="btn btn-primary">
                                                <i class="bi bi-key me-1"></i>Ubah Password
                                            </button>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>
    </main>

    {{-- Vendor JS --}}
    <script src="{{ asset('assets/vendor/bootstrap/js/bootstrap.bundle.min.js') }}"></script>
    <script src="{{ asset('assets/js/main.js') }}"></script>
</body>

</html>