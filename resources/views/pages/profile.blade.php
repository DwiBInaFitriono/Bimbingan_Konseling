<!DOCTYPE html>
<html lang="en">

<head>
    @include('include.style')
    <style>


        /* ===== Account Settings ===== */
        .acct-card {
            border: none;
            border-radius: 14px;
            box-shadow: 0 2px 18px rgba(0,0,0,.06);
            background: #fff;
            overflow: hidden;
        }
        .acct-card .card-header {
            background: #fff;
            border-bottom: 1px solid #f0f2f5;
            padding: 20px 24px 16px;
        }
        .acct-card .card-header .card-header-icon {
            width: 38px;
            height: 38px;
            border-radius: 10px;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            font-size: 18px;
            flex-shrink: 0;
        }
        .acct-card .card-header h6 {
            font-size: 15px;
            font-weight: 700;
            color: #1e2a3b;
            margin: 0;
        }
        .acct-card .card-header p {
            font-size: 12.5px;
            color: #8c939d;
            margin: 2px 0 0;
        }
        .acct-card .card-body { padding: 24px; }

        /* Photo Upload */
        .photo-upload-area {
            display: flex;
            align-items: center;
            gap: 20px;
            padding: 18px;
            border: 2px dashed #e1e5eb;
            border-radius: 12px;
            background: #fafbfd;
            margin-bottom: 24px;
            transition: border-color .2s;
        }
        .photo-upload-area:hover {
            border-color: #4154f1;
        }
        .photo-avatar {
            width: 78px;
            height: 78px;
            border-radius: 50%;
            object-fit: cover;
            border: 3px solid #e9ecef;
            flex-shrink: 0;
        }
        .btn-upload-photo {
            display: inline-flex;
            align-items: center;
            gap: 6px;
            padding: 8px 18px;
            font-size: 13px;
            font-weight: 600;
            border: 1.5px solid #4154f1;
            border-radius: 8px;
            background: #fff;
            color: #4154f1;
            cursor: pointer;
            transition: all .2s;
        }
        .btn-upload-photo:hover {
            background: #4154f1;
            color: #fff;
        }
        .photo-upload-hint {
            font-size: 11.5px;
            color: #adb5bd;
            margin-top: 5px;
            line-height: 1.55;
        }

        /* Section divider label */
        .form-section-label {
            font-size: 11px;
            font-weight: 700;
            letter-spacing: .06em;
            text-transform: uppercase;
            color: #8c939d;
            margin: 0 0 14px;
            display: flex;
            align-items: center;
            gap: 10px;
        }
        .form-section-label::after {
            content: '';
            flex: 1;
            height: 1px;
            background: #f0f2f5;
        }

        /* Form controls */
        .acct-form .form-label {
            font-size: 13px;
            font-weight: 600;
            color: #444e5c;
            margin-bottom: 5px;
        }
        .acct-form .form-control {
            border: 1.5px solid #e1e5eb;
            border-radius: 9px;
            padding: 9px 13px;
            font-size: 13.5px;
            color: #2c3e50;
            transition: border-color .2s, box-shadow .2s;
        }
        .acct-form .form-control:focus {
            border-color: #4154f1;
            box-shadow: 0 0 0 3px rgba(65,84,241,.1);
        }
        /* Password input-group */
        .pwd-input-group .form-control {
            border-right: none;
            border-radius: 9px 0 0 9px !important;
        }
        .pwd-input-group .btn-eye {
            border: 1.5px solid #e1e5eb;
            border-left: none;
            border-radius: 0 9px 9px 0;
            background: #fff;
            color: #adb5bd;
            padding: 0 13px;
            cursor: pointer;
            transition: color .2s;
        }
        .pwd-input-group .btn-eye:hover { color: #4154f1; }
        .pwd-input-group:focus-within .form-control,
        .pwd-input-group:focus-within .btn-eye {
            border-color: #4154f1;
        }
        .pwd-input-group:focus-within .btn-eye {
            box-shadow: 0 0 0 3px rgba(65,84,241,.1);
        }

        /* Email icon */
        .email-wrap { position: relative; }
        .email-wrap .email-icon {
            position: absolute;
            left: 12px;
            top: 50%;
            transform: translateY(-50%);
            color: #adb5bd;
            font-size: 15px;
            pointer-events: none;
        }
        .email-wrap .form-control { padding-left: 36px; }

        /* Card footer / save row */
        .acct-save-row {
            display: flex;
            align-items: center;
            justify-content: flex-end;
            padding: 14px 24px;
            border-top: 1px solid #f0f2f5;
            background: #fafbfd;
        }
        .btn-save-acct {
            padding: 9px 26px;
            font-size: 13.5px;
            font-weight: 700;
            border: none;
            border-radius: 9px;
            background: #4154f1;
            color: #fff;
            cursor: pointer;
            transition: all .25s;
        }
        .btn-save-acct:hover {
            background: #3040d0;
            box-shadow: 0 4px 14px rgba(65,84,241,.3);
            transform: translateY(-1px);
        }

        /* Security tips card */
        .security-tips {
            padding: 16px 20px;
            background: linear-gradient(135deg, #f0f3ff, #faf5ff);
            border-radius: 10px;
            border: 1px solid rgba(65,84,241,.08);
        }
        .security-tips h6 {
            font-size: 13px;
            font-weight: 700;
            color: #4154f1;
            margin-bottom: 8px;
        }
        .security-tips ul {
            padding-left: 16px;
            margin: 0;
        }
        .security-tips ul li {
            font-size: 12px;
            color: #5a6275;
            margin-bottom: 4px;
            line-height: 1.5;
        }

        /* Alerts styling */
        .profile-alert {
            border-radius: 10px;
            border: none;
            font-size: 13.5px;
            padding: 12px 18px;
        }

        @media (max-width: 767px) {
            .photo-upload-area { flex-direction: column; align-items: flex-start; }
        }
    </style>
</head>

<body>
    @php
        $pageUser = $user ?? auth()->user();
        $profileInitials = collect(explode(' ', trim($pageUser?->name ?? 'Pengguna')))
            ->filter()
            ->take(2)
            ->map(fn ($part) => mb_strtoupper(mb_substr($part, 0, 1)))
            ->implode('');
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



        {{-- Flash Alerts --}}
        @if(session('success'))
            <div class="alert alert-success alert-dismissible fade show profile-alert" role="alert">
                <i class="bi bi-check-circle-fill me-1"></i>{{ session('success') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        @endif
        @if(session('password_success'))
            <div class="alert alert-success alert-dismissible fade show profile-alert" role="alert">
                <i class="bi bi-check-circle-fill me-1"></i>{{ session('password_success') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        @endif
        @if($errors->any())
            <div class="alert alert-danger alert-dismissible fade show profile-alert" role="alert">
                <i class="bi bi-exclamation-circle-fill me-1"></i>
                @foreach($errors->all() as $err)<div>{{ $err }}</div>@endforeach
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        @endif

        <section class="section profile">
            <div class="row g-4 align-items-start">

                {{-- ===== LEFT: General Information ===== --}}
                <div class="col-lg-7">
                    <form method="POST" action="{{ route('profile.update') }}"
                          enctype="multipart/form-data" class="acct-form">
                        @csrf

                        <div class="acct-card">
                            <div class="card-header">
                                <h6>Informasi Umum</h6>
                                <p>Perbarui informasi profil dan foto Anda.</p>
                            </div>
                            <div class="card-body">

                                {{-- Photo --}}
                                <div class="photo-upload-area">
                                    @if($pageUser?->photo)
                                        <img src="{{ asset('storage/' . $pageUser->photo) }}"
                                             alt="Foto Profil" class="photo-avatar" id="profilePreview">
                                    @else
                                        <img src="{{ asset('assets/img/profile-img.jpg') }}"
                                             alt="Foto Profil" class="photo-avatar" id="profilePreview">
                                    @endif
                                    <div>
                                        <input type="file" name="profile_image" id="profileImageInput"
                                               class="d-none" accept="image/*" onchange="previewImage(this)">
                                        <button type="button" class="btn-upload-photo"
                                                onclick="document.getElementById('profileImageInput').click()">
                                            <i class="bi bi-cloud-arrow-up"></i> Unggah Foto
                                        </button>
                                        <div class="photo-upload-hint">
                                            JPG, PNG atau GIF &bull; Maks 800KB<br>Disarankan 200 &times; 200 px
                                        </div>
                                    </div>
                                </div>

                                <p class="form-section-label">Informasi Dasar</p>

                                <div class="row g-3 mb-3">
                                    <div class="col-sm-6">
                                        <label class="form-label" for="profileName">Nama Lengkap</label>
                                        <input type="text" name="name" id="profileName"
                                               class="form-control @error('name') is-invalid @enderror"
                                               value="{{ old('name', $pageUser?->name) }}"
                                               placeholder="Nama lengkap">
                                        @error('name')<div class="invalid-feedback d-block">{{ $message }}</div>@enderror
                                    </div>
                                    <div class="col-sm-6">
                                        <label class="form-label" for="profileNip">NIP</label>
                                        <input type="text" name="nip" id="profileNip"
                                               class="form-control @error('nip') is-invalid @enderror"
                                               value="{{ old('nip', $pageUser?->nip) }}"
                                               placeholder="NIP (opsional)">
                                        @error('nip')<div class="invalid-feedback d-block">{{ $message }}</div>@enderror
                                    </div>
                                </div>

                                <div class="mb-3">
                                    <label class="form-label" for="profileEmail">Alamat Email</label>
                                    <div class="email-wrap">
                                        <i class="bi bi-envelope email-icon"></i>
                                        <input type="email" name="email" id="profileEmail"
                                               class="form-control @error('email') is-invalid @enderror"
                                               value="{{ old('email', $pageUser?->email) }}">
                                    </div>
                                    @error('email')<div class="invalid-feedback d-block">{{ $message }}</div>@enderror
                                </div>

                                <div class="mb-1">
                                    <label class="form-label" for="profilePhone">Nomor Telepon</label>
                                    <div class="input-group">
                                        <span class="input-group-text" style="border:1.5px solid #e1e5eb;border-radius:9px 0 0 9px;font-size:13.5px;background:#f8f9fa;color:#495057;">+62</span>
                                        <input type="text" name="phone" id="profilePhone"
                                               class="form-control @error('phone') is-invalid @enderror"
                                               value="{{ old('phone', $pageUser?->phone) }}"
                                               placeholder="812-3456-7890"
                                               style="border-left:none;border-radius:0 9px 9px 0;">
                                    </div>
                                    @error('phone')<div class="invalid-feedback d-block">{{ $message }}</div>@enderror
                                </div>

                            </div>{{-- /card-body --}}
                            <div class="acct-save-row">
                                <span class="text-muted small me-auto">
                                    Terakhir diperbarui:
                                    {{ $pageUser?->updated_at ? $pageUser->updated_at->format('d/m/Y, H:i') : '-' }}
                                </span>
                                <button type="submit" class="btn-save-acct">
                                    Simpan Perubahan
                                </button>
                            </div>
                        </div>

                    </form>
                </div>{{-- /col left --}}

                {{-- ===== RIGHT: Change Password ===== --}}
                <div class="col-lg-5">
                    <form method="POST" action="{{ route('password.update') }}" class="acct-form">
                        @csrf
                        <div class="acct-card">
                            <div class="card-header">
                                <h6>Ubah Password</h6>
                                <p>Pastikan password baru aman dan mudah diingat.</p>
                            </div>
                            <div class="card-body">

                                <div class="mb-3">
                                    <label class="form-label" for="currentPassword">Password Lama</label>
                                    <div class="input-group pwd-input-group">
                                        <input type="password" name="current_password" id="currentPassword"
                                               class="form-control @error('current_password') is-invalid @enderror"
                                               placeholder="Password saat ini">
                                        <button type="button" class="btn-eye"
                                                onclick="togglePwd('currentPassword',this)" tabindex="-1">
                                            <i class="bi bi-eye-slash"></i>
                                        </button>
                                    </div>
                                    @error('current_password')<div class="text-danger small mt-1">{{ $message }}</div>@enderror
                                </div>

                                <div class="mb-3">
                                    <label class="form-label" for="newPassword">Password Baru</label>
                                    <div class="input-group pwd-input-group">
                                        <input type="password" name="password" id="newPassword"
                                               class="form-control @error('password') is-invalid @enderror"
                                               placeholder="Minimal 6 karakter">
                                        <button type="button" class="btn-eye"
                                                onclick="togglePwd('newPassword',this)" tabindex="-1">
                                            <i class="bi bi-eye-slash"></i>
                                        </button>
                                    </div>
                                    @error('password')<div class="text-danger small mt-1">{{ $message }}</div>@enderror
                                </div>

                                <div class="mb-3">
                                    <label class="form-label" for="confirmPassword">Ulangi Password Baru</label>
                                    <div class="input-group pwd-input-group">
                                        <input type="password" name="password_confirmation" id="confirmPassword"
                                               class="form-control"
                                               placeholder="Konfirmasi password baru">
                                        <button type="button" class="btn-eye"
                                                onclick="togglePwd('confirmPassword',this)" tabindex="-1">
                                            <i class="bi bi-eye-slash"></i>
                                        </button>
                                    </div>
                                </div>

                            </div>{{-- /card-body --}}
                            <div class="acct-save-row">
                                <button type="submit" class="btn-save-acct">
                                    Perbarui Password
                                </button>
                            </div>
                        </div>
                    </form>

                    {{-- Security Tips --}}
                    <div class="security-tips mt-3">
                        <h6>Tips Keamanan</h6>
                        <ul>
                            <li>Gunakan kombinasi huruf besar, huruf kecil, angka, dan simbol.</li>
                            <li>Jangan gunakan password yang sama dengan akun lain.</li>
                            <li>Ganti password minimal setiap 3 bulan.</li>
                        </ul>
                    </div>
                </div>{{-- /col right --}}

            </div>{{-- /row --}}
        </section>

    </main>

    @include('include.footer')

    <script src="{{ asset('assets/vendor/bootstrap/js/bootstrap.bundle.min.js') }}"></script>
    <script src="{{ asset('assets/js/main.js') }}"></script>
    <script>
        function previewImage(input) {
            if (input.files && input.files[0]) {
                var reader = new FileReader();
                reader.onload = function(e) {
                    document.getElementById('profilePreview').src = e.target.result;
                };
                reader.readAsDataURL(input.files[0]);
            }
        }

        function togglePwd(fieldId, btn) {
            var field = document.getElementById(fieldId);
            var icon  = btn.querySelector('i');
            if (field.type === 'password') {
                field.type = 'text';
                icon.className = 'bi bi-eye';
            } else {
                field.type = 'password';
                icon.className = 'bi bi-eye-slash';
            }
        }
    </script>
</body>

</html>
