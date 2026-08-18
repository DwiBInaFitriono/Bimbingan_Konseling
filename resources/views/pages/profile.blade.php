<!DOCTYPE html>
<html lang="en">

<head>
    @include('include.style')
    <style>
        /* ===== Profile Edit Page Styles ===== */
        .profile-edit-tabs {
            display: flex;
            gap: 0;
            border-bottom: 2px solid #e9ecef;
            margin-bottom: 0;
            padding: 0 24px;
            background: #fff;
            border-radius: 12px 12px 0 0;
        }

        .profile-edit-tabs .tab-link {
            padding: 14px 22px;
            font-size: 14px;
            font-weight: 600;
            color: #6c757d;
            background: transparent;
            border: none;
            border-bottom: 3px solid transparent;
            cursor: pointer;
            transition: all 0.2s ease;
            white-space: nowrap;
            text-decoration: none;
            display: inline-block;
        }

        .profile-edit-tabs .tab-link:hover {
            color: #4154f1;
        }

        .profile-edit-tabs .tab-link.active {
            color: #fff;
            background: #4154f1;
            border-radius: 8px;
            border-bottom-color: transparent;
            margin: 8px 4px;
            padding: 8px 20px;
        }

        .profile-edit-card {
            border: none;
            border-radius: 0 0 12px 12px;
            box-shadow: 0 2px 20px rgba(0, 0, 0, 0.06);
            background: #fff;
        }

        .profile-edit-card .card-body {
            padding: 32px 40px;
        }

        .profile-manage-title {
            font-size: 20px;
            font-weight: 700;
            color: #2c3e50;
            margin-bottom: 4px;
        }

        .profile-manage-subtitle {
            font-size: 14px;
            color: #8c939d;
            margin-bottom: 28px;
        }

        .profile-content-wrapper {
            display: flex;
            gap: 40px;
            align-items: flex-start;
        }

        .profile-photo-section {
            flex-shrink: 0;
            display: flex;
            flex-direction: column;
            align-items: center;
            min-width: 140px;
        }

        .profile-photo-section .photo-wrapper {
            width: 120px;
            height: 120px;
            border-radius: 50%;
            overflow: hidden;
            margin-bottom: 12px;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
        }

        .profile-photo-section .photo-wrapper img {
            width: 100%;
            height: 100%;
            object-fit: cover;
        }

        .profile-photo-actions {
            display: flex;
            align-items: center;
            gap: 8px;
        }

        .profile-photo-actions .btn-change-photo {
            padding: 6px 16px;
            font-size: 12px;
            font-weight: 600;
            border: 1.5px solid #dee2e6;
            border-radius: 8px;
            background: #fff;
            color: #495057;
            cursor: pointer;
            transition: all 0.2s;
        }

        .profile-photo-actions .btn-change-photo:hover {
            border-color: #4154f1;
            color: #4154f1;
        }

        .profile-photo-actions .btn-delete-photo {
            width: 32px;
            height: 32px;
            display: flex;
            align-items: center;
            justify-content: center;
            border: 1.5px solid #f5c6cb;
            border-radius: 8px;
            background: #fff;
            color: #dc3545;
            cursor: pointer;
            transition: all 0.2s;
            font-size: 14px;
        }

        .profile-photo-actions .btn-delete-photo:hover {
            background: #dc3545;
            color: #fff;
        }

        .profile-photo-hint {
            font-size: 11px;
            color: #adb5bd;
            margin-top: 6px;
            text-align: center;
        }

        .profile-form-section {
            flex: 1;
            min-width: 0;
        }

        .profile-form-grid {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 20px;
        }

        .profile-form-grid .form-group {
            display: flex;
            flex-direction: column;
        }

        .profile-form-grid .form-group.full-width {
            grid-column: 1 / -1;
        }

        .profile-form-grid .form-group label {
            font-size: 13px;
            font-weight: 600;
            color: #495057;
            margin-bottom: 6px;
        }

        .profile-form-grid .form-group label .required {
            color: #dc3545;
            font-size: 10px;
            margin-left: 4px;
            vertical-align: super;
        }

        .profile-form-grid .form-group label .optional {
            color: #4154f1;
            font-size: 10px;
            margin-left: 4px;
            vertical-align: super;
        }

        .profile-form-grid .form-control {
            border: 1.5px solid #e1e5eb;
            border-radius: 10px;
            padding: 10px 14px;
            font-size: 14px;
            color: #2c3e50;
            transition: border-color 0.2s, box-shadow 0.2s;
            background: #fff;
        }

        .profile-form-grid .form-control:focus {
            border-color: #4154f1;
            box-shadow: 0 0 0 3px rgba(65, 84, 241, 0.1);
        }

        .profile-form-grid .form-control:disabled,
        .profile-form-grid .form-control[readonly] {
            background: #f8f9fa;
            color: #8c939d;
            cursor: not-allowed;
        }

        .profile-form-grid .form-control.email-field {
            padding-left: 38px;
        }

        .email-input-wrapper {
            position: relative;
        }

        .email-input-wrapper .email-icon {
            position: absolute;
            left: 12px;
            top: 50%;
            transform: translateY(-50%);
            color: #adb5bd;
            font-size: 16px;
        }

        .profile-footer {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-top: 28px;
            padding-top: 20px;
            border-top: 1px solid #f0f0f0;
        }

        .profile-footer .last-update {
            font-size: 13px;
            color: #adb5bd;
        }

        .profile-footer .btn-save {
            padding: 10px 28px;
            font-size: 14px;
            font-weight: 600;
            border: none;
            border-radius: 10px;
            background: #4154f1;
            color: #fff;
            cursor: pointer;
            transition: all 0.2s;
        }

        .profile-footer .btn-save:hover {
            background: #3040d0;
            box-shadow: 0 4px 12px rgba(65, 84, 241, 0.3);
        }

        /* Responsive */
        @media (max-width: 768px) {
            .profile-content-wrapper {
                flex-direction: column;
                align-items: center;
            }

            .profile-form-grid {
                grid-template-columns: 1fr;
            }

            .profile-edit-card .card-body {
                padding: 20px;
            }

            .profile-edit-tabs {
                padding: 0 12px;
                overflow-x: auto;
            }

            .profile-footer {
                flex-direction: column;
                gap: 16px;
                text-align: center;
            }
        }

        @keyframes profileSlideIn {
            from { transform: translateX(120%); opacity: 0; }
            to { transform: translateX(0); opacity: 1; }
        }
        @keyframes profileSlideOut {
            from { transform: translateX(0); opacity: 1; }
            to { transform: translateX(120%); opacity: 0; }
        }
    </style>
</head>

<body>
    @php
        $pageUser = $user ?? auth()->user();
    @endphp

    @include('include.header')
    @include('include.sidebar')

    <main id="main" class="main">
        <div class="pagetitle">
            <h1>Edit Profil</h1>
            <nav>
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Beranda</a></li>
                    <li class="breadcrumb-item active">Edit Profil</li>
                </ol>
            </nav>
        </div>

        {{-- Flash Messages --}}
        @if(session('success'))
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                <i class="bi bi-check-circle me-1"></i>{{ session('success') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @endif

        @if(session('password_success'))
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                <i class="bi bi-check-circle me-1"></i>{{ session('password_success') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @endif

        @if($errors->any())
            <div style="position:fixed;top:24px;right:24px;z-index:9999;min-width:320px;border-radius:12px;background-color:#fff1f2;color:#e11d48;border:1px solid #ffe4e6;padding:16px 20px;display:flex;align-items:center;box-shadow:0 10px 30px rgba(0,0,0,0.1);animation:profileSlideIn 0.5s cubic-bezier(0.175,0.885,0.32,1.275) forwards,profileSlideOut 0.5s ease-in 4s forwards;" role="alert">
                <i class="bi bi-exclamation-circle-fill" style="font-size:1.5rem;color:#f43f5e;margin-right:12px;"></i>
                <div>
                    @foreach($errors->all() as $error)
                        <div class="small fw-semibold">{{ $error }}</div>
                    @endforeach
                </div>
            </div>
        @endif

        <section class="section profile">
            {{-- Horizontal Tabs --}}
            <div class="profile-edit-tabs" id="profileTabs">
                <button class="tab-link active" data-target="#tab-edit-profile" type="button">Edit Profil</button>
                <button class="tab-link" data-target="#tab-change-password" type="button">Ubah Password</button>
            </div>

            {{-- Tab Content Cards --}}
            <div class="card profile-edit-card mb-0">
                <div class="card-body">

                    {{-- ========== TAB: Edit Profil ========== --}}
                    <div class="profile-tab-pane" id="tab-edit-profile">
                        <h5 class="profile-manage-title">Kelola Profil</h5>
                        <p class="profile-manage-subtitle">Perbarui informasi profil Anda kapan saja.</p>

                        <form method="POST" action="{{ route('profile.update') }}" enctype="multipart/form-data">
                            @csrf

                            <div class="profile-content-wrapper">
                                {{-- Photo Section --}}
                                <div class="profile-photo-section">
                                    <div class="photo-wrapper">
                                        @if($pageUser?->photo)
                                            <img src="{{ asset('storage/' . $pageUser->photo) }}" alt="Foto Profil" id="profilePreview">
                                        @else
                                            <img src="{{ asset('assets/img/profile-img.jpg') }}" alt="Foto Profil" id="profilePreview">
                                        @endif
                                    </div>
                                    <div class="profile-photo-actions">
                                        <input type="file" name="profile_image" id="profileImageInput" class="d-none" accept="image/*" onchange="previewImage(this)">
                                        <button type="button" class="btn-change-photo" onclick="document.getElementById('profileImageInput').click()">Ubah Foto</button>
                                        <button type="button" class="btn-delete-photo" title="Hapus foto"><i class="bi bi-trash"></i></button>
                                    </div>
                                    <span class="profile-photo-hint">JPG, JPEG atau PNG.<br>Maks 800KB</span>
                                </div>

                                {{-- Form Section --}}
                                <div class="profile-form-section">
                                    <div class="profile-form-grid">
                                        {{-- Nama Lengkap --}}
                                        <div class="form-group">
                                            <label for="profileName">Nama Lengkap <span class="optional">●</span></label>
                                            <input name="name" type="text" class="form-control @error('name') is-invalid @enderror" id="profileName" value="{{ old('name', $pageUser?->name) }}" placeholder="Masukkan nama lengkap">
                                            @error('name')
                                                <div class="invalid-feedback d-block">{{ $message }}</div>
                                            @enderror
                                        </div>

                                        {{-- NIP --}}
                                        <div class="form-group">
                                            <label for="profileNip">NIP <span class="required">●</span></label>
                                            <input name="nip" type="text" class="form-control @error('nip') is-invalid @enderror" id="profileNip" value="{{ old('nip', $pageUser?->nip) }}" placeholder="Masukkan NIP">
                                            @error('nip')
                                                <div class="invalid-feedback d-block">{{ $message }}</div>
                                            @enderror
                                        </div>

                                        {{-- Email --}}
                                        <div class="form-group full-width">
                                            <label for="profileEmail">Alamat Email</label>
                                            <div class="email-input-wrapper">
                                                <i class="bi bi-envelope email-icon"></i>
                                                <input name="email" type="email" class="form-control email-field @error('email') is-invalid @enderror" id="profileEmail" value="{{ old('email', $pageUser?->email) }}">
                                            </div>
                                            @error('email')
                                                <div class="invalid-feedback d-block">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>

                                    {{-- Footer --}}
                                    <div class="profile-footer">
                                        <span class="last-update">Terakhir diperbarui: {{ $pageUser?->updated_at ? $pageUser->updated_at->format('d/m/Y, H:i') : '-' }}</span>
                                        <button type="submit" class="btn-save">Simpan Perubahan</button>
                                    </div>
                                </div>
                            </div>
                        </form>
                    </div>

                    {{-- ========== TAB: Ubah Password ========== --}}
                    <div class="profile-tab-pane" id="tab-change-password" style="display:none;">
                        <h5 class="profile-manage-title">Ubah Password</h5>
                        <p class="profile-manage-subtitle">Pastikan password baru Anda aman dan mudah diingat.</p>

                        <form method="POST" action="{{ route('password.update') }}">
                            @csrf

                            <div class="profile-form-section">
                                <div class="profile-form-grid">
                                    <div class="form-group full-width">
                                        <label for="currentPassword">Password Saat Ini <span class="required">●</span></label>
                                        <input name="current_password" type="password" class="form-control @error('current_password') is-invalid @enderror" id="currentPassword" placeholder="Masukkan password saat ini">
                                        @error('current_password')
                                            <div class="invalid-feedback d-block">{{ $message }}</div>
                                        @enderror
                                    </div>

                                    <div class="form-group">
                                        <label for="newPassword">Password Baru <span class="required">●</span></label>
                                        <input name="password" type="password" class="form-control @error('password') is-invalid @enderror" id="newPassword" placeholder="Minimal 6 karakter">
                                        @error('password')
                                            <div class="invalid-feedback d-block">{{ $message }}</div>
                                        @enderror
                                    </div>

                                    <div class="form-group">
                                        <label for="confirmPassword">Konfirmasi Password <span class="required">●</span></label>
                                        <input name="password_confirmation" type="password" class="form-control" id="confirmPassword" placeholder="Ulangi password baru">
                                    </div>
                                </div>

                                <div class="profile-footer">
                                    <span class="last-update"></span>
                                    <button type="submit" class="btn-save">
                                        <i class="bi bi-key me-1"></i>Ubah Password
                                    </button>
                                </div>
                            </div>
                        </form>
                    </div>

                </div>
            </div>
        </section>
    </main>

    {{-- Vendor JS --}}
    <script src="{{ asset('assets/vendor/bootstrap/js/bootstrap.bundle.min.js') }}"></script>
    <script src="{{ asset('assets/js/main.js') }}"></script>

    <script>
        // Tab switching
        document.querySelectorAll('.profile-edit-tabs .tab-link').forEach(function(btn) {
            btn.addEventListener('click', function() {
                // Deactivate all tabs
                document.querySelectorAll('.profile-edit-tabs .tab-link').forEach(function(t) {
                    t.classList.remove('active');
                });
                // Hide all panes
                document.querySelectorAll('.profile-tab-pane').forEach(function(p) {
                    p.style.display = 'none';
                });
                // Activate clicked tab
                btn.classList.add('active');
                // Show target pane
                var target = document.querySelector(btn.getAttribute('data-target'));
                if (target) target.style.display = 'block';
            });
        });

        // Photo preview
        function previewImage(input) {
            if (input.files && input.files[0]) {
                var reader = new FileReader();
                reader.onload = function(e) {
                    document.getElementById('profilePreview').src = e.target.result;
                }
                reader.readAsDataURL(input.files[0]);
            }
        }

        // Auto-switch to password tab if password errors exist
        @if($errors->has('current_password') || $errors->has('password'))
            document.querySelector('.tab-link[data-target="#tab-change-password"]').click();
        @endif
    </script>
</body>

</html>