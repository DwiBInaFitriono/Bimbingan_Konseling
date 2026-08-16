<!DOCTYPE html>
<html lang="en">

<head>
    @include('include.style')
</head>

<body>
    @php
        $settingsUser = auth()->user();
    @endphp

    @include('include.header')
    @include('include.sidebar')

    <main id="main" class="main">
        <div class="pagetitle">
            <h1>Pengaturan Akun</h1>
            <nav>
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Beranda</a></li>
                    <li class="breadcrumb-item active">Pengaturan Akun</li>
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

        <section class="section">
            <div class="row">
                {{-- Update Profile Section --}}
                <div class="col-lg-6">
                    <div class="card">
                        <div class="card-body">
                            <h5 class="card-title">
                                <i class="bi bi-person-circle me-2 text-primary"></i>Informasi Profil
                            </h5>
                            <p class="text-muted small mb-4">Perbarui informasi nama dan email akun Anda.</p>

                            <form method="POST" action="{{ route('profile.update') }}">
                                @csrf

                                <div class="mb-3">
                                    <label for="settingsName" class="form-label">Nama Lengkap <span class="text-danger">*</span></label>
                                    <input name="name" type="text" class="form-control @error('name') is-invalid @enderror" id="settingsName" value="{{ old('name', $settingsUser?->name) }}" placeholder="Masukkan nama lengkap">
                                    @error('name')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="mb-3">
                                    <label for="settingsEmail" class="form-label">Email <span class="text-danger">*</span></label>
                                    <input name="email" type="email" class="form-control @error('email') is-invalid @enderror" id="settingsEmail" value="{{ old('email', $settingsUser?->email) }}" placeholder="Masukkan email">
                                    @error('email')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <button type="submit" class="btn btn-primary w-100">
                                    <i class="bi bi-check-lg me-1"></i>Simpan Perubahan Profil
                                </button>
                            </form>
                        </div>
                    </div>
                </div>

                {{-- Update Password Section --}}
                <div class="col-lg-6">
                    <div class="card">
                        <div class="card-body">
                            <h5 class="card-title">
                                <i class="bi bi-shield-lock me-2 text-warning"></i>Keamanan Password
                            </h5>
                            <p class="text-muted small mb-4">Pastikan akun Anda menggunakan password yang kuat dan unik.</p>

                            <form method="POST" action="{{ route('password.update') }}">
                                @csrf

                                <div class="mb-3">
                                    <label for="settingsCurrentPassword" class="form-label">Password Saat Ini <span class="text-danger">*</span></label>
                                    <input name="current_password" type="password" class="form-control @error('current_password') is-invalid @enderror" id="settingsCurrentPassword" placeholder="Masukkan password saat ini">
                                    @error('current_password')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="mb-3">
                                    <label for="settingsNewPassword" class="form-label">Password Baru <span class="text-danger">*</span></label>
                                    <input name="password" type="password" class="form-control @error('password') is-invalid @enderror" id="settingsNewPassword" placeholder="Masukkan password baru (min. 6 karakter)">
                                    @error('password')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="mb-3">
                                    <label for="settingsConfirmPassword" class="form-label">Konfirmasi Password Baru <span class="text-danger">*</span></label>
                                    <input name="password_confirmation" type="password" class="form-control" id="settingsConfirmPassword" placeholder="Ulangi password baru">
                                </div>

                                <button type="submit" class="btn btn-warning w-100">
                                    <i class="bi bi-key me-1"></i>Ubah Password
                                </button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Account Info Section --}}
            <div class="row">
                <div class="col-12">
                    <div class="card">
                        <div class="card-body">
                            <h5 class="card-title">
                                <i class="bi bi-info-circle me-2 text-info"></i>Informasi Akun
                            </h5>
                            <div class="row">
                                <div class="col-md-4">
                                    <div class="border rounded p-3 text-center">
                                        <i class="bi bi-calendar-check fs-3 text-primary"></i>
                                        <h6 class="mt-2 mb-1">Tanggal Daftar</h6>
                                        <span class="text-muted">{{ $settingsUser?->created_at ? $settingsUser->created_at->format('d F Y') : '-' }}</span>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="border rounded p-3 text-center">
                                        <i class="bi bi-clock-history fs-3 text-success"></i>
                                        <h6 class="mt-2 mb-1">Terakhir Diperbarui</h6>
                                        <span class="text-muted">{{ $settingsUser?->updated_at ? $settingsUser->updated_at->diffForHumans() : '-' }}</span>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="border rounded p-3 text-center">
                                        <i class="bi bi-shield-check fs-3 text-warning"></i>
                                        <h6 class="mt-2 mb-1">Status Akun</h6>
                                        <span class="badge bg-success">Aktif</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Danger Zone --}}
            <div class="row">
                <div class="col-12">
                    <div class="card border-danger">
                        <div class="card-body">
                            <h5 class="card-title text-danger">
                                <i class="bi bi-exclamation-triangle me-2"></i>Zona Berbahaya
                            </h5>
                            <div class="d-flex justify-content-between align-items-center">
                                <div>
                                    <h6 class="mb-1">Keluar dari Akun</h6>
                                    <p class="text-muted mb-0 small">Anda akan diarahkan kembali ke halaman login.</p>
                                </div>
                                <form method="POST" action="{{ route('logout') }}">
                                    @csrf
                                    <button type="submit" class="btn btn-outline-danger">
                                        <i class="bi bi-box-arrow-right me-1"></i>Keluar
                                    </button>
                                </form>
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