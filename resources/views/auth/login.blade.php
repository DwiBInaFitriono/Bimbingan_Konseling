<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - Sistem Manajemen BK</title>
    <link href="{{ asset('assets/img/favicon.png') }}" rel="icon">
    <link href="https://fonts.gstatic.com" rel="preconnect">
    <link href="https://fonts.googleapis.com/css?family=Nunito:300,400,600,700|Open+Sans:400,600,700" rel="stylesheet">
    <link href="{{ asset('assets/vendor/bootstrap/css/bootstrap.min.css') }}" rel="stylesheet">
    <link href="{{ asset('assets/vendor/bootstrap-icons/bootstrap-icons.css') }}" rel="stylesheet">
    <link href="{{ asset('assets/css/style.css') }}" rel="stylesheet">
    <style>
        body.auth-page {
            min-height: 100vh;
            background:
                radial-gradient(circle at top left, rgba(65, 84, 241, 0.16), transparent 32%),
                radial-gradient(circle at bottom right, rgba(1, 41, 112, 0.12), transparent 28%),
                #f6f9ff;
        }

        .auth-shell {
            min-height: 100vh;
            display: grid;
            grid-template-columns: 1.1fr 0.9fr;
        }

        .auth-hero {
            padding: 4rem;
            display: flex;
            flex-direction: column;
            justify-content: center;
            color: #012970;
        }

        .auth-card-wrap {
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 2rem;
        }

        .auth-card {
            width: min(100%, 440px);
            border: 0;
            border-radius: 24px;
            box-shadow: 0 24px 60px rgba(1, 41, 112, 0.14);
        }

        .role-tab-btn {
            border: 2px solid #e0e6ed;
            background: #ffffff;
            color: #444;
            padding: 10px 16px;
            border-radius: 12px;
            font-weight: 600;
            transition: all 0.2s ease-in-out;
            cursor: pointer;
            width: 100%;
        }

        .role-tab-btn.active-guru {
            border-color: #4154f1;
            background: rgba(65, 84, 241, 0.08);
            color: #4154f1;
        }

        .role-tab-btn.active-siswa {
            border-color: #2eca6a;
            background: rgba(46, 202, 106, 0.08);
            color: #198754;
        }

        .brand-chip {
            display: inline-flex;
            align-items: center;
            gap: 0.75rem;
            padding: 0.6rem 0.9rem;
            border-radius: 999px;
            background: rgba(255, 255, 255, 0.8);
            backdrop-filter: blur(6px);
            width: fit-content;
        }

        .brand-chip img {
            width: 42px;
            height: 42px;
            object-fit: contain;
        }

        @media (max-width: 991.98px) {
            .auth-shell {
                grid-template-columns: 1fr;
            }

            .auth-hero {
                padding: 2rem 1.5rem 0;
            }
        }
    </style>
</head>

<body class="auth-page">
    <div class="auth-shell">
        <section class="auth-hero">
            <div class="brand-chip mb-4">
                <img src="{{ asset('assets/img/logo.png') }}" alt="Sistem BK">
                <div>
                    <div class="fw-bold">Sistem Manajemen BK</div>
                    <small class="text-primary fw-semibold">Portal Masuk Terpadu</small>
                </div>
            </div>
            <h1 class="display-6 fw-bold mb-3">Bimbingan & Konseling Sekolah</h1>
            <p class="lead mb-4" style="max-width: 520px;">
                Silakan masuk menggunakan akun Anda untuk mengelola bimbingan konseling atau mengajukan jadwal bimbingan siswa.
            </p>
            <div class="d-flex gap-3">
                <div class="p-3 bg-white bg-opacity-75 rounded-3 shadow-sm border border-white">
                    <i class="bi bi-person-badge fs-3 text-primary"></i>
                    <h6 class="fw-bold mt-2 mb-1">Guru BK</h6>
                    <small class="text-muted">Mengelola data siswa, kelas & konseling</small>
                </div>
                <div class="p-3 bg-white bg-opacity-75 rounded-3 shadow-sm border border-white">
                    <i class="bi bi-mortarboard fs-3 text-success"></i>
                    <h6 class="fw-bold mt-2 mb-1">Siswa SMK</h6>
                    <small class="text-muted">Masuk menggunakan email dari Guru BK</small>
                </div>
            </div>
        </section>

        <section class="auth-card-wrap">
            <div class="card auth-card">
                <div class="card-body p-4 p-md-5">
                    <h2 class="h4 fw-bold text-center mb-1">Login Sistem</h2>
                    <p class="text-muted text-center small mb-4">Pilih peran Anda untuk melanjutkan</p>

                    {{-- Role Selector Tabs --}}
                    <div class="row g-2 mb-4">
                        <div class="col-6">
                            <button type="button" id="tabGuru" class="role-tab-btn active-guru" onclick="setLoginRole('guru_bk')">
                                <i class="bi bi-person-badge me-1"></i>Guru BK
                            </button>
                        </div>
                        <div class="col-6">
                            <button type="button" id="tabSiswa" class="role-tab-btn" onclick="setLoginRole('siswa')">
                                <i class="bi bi-mortarboard me-1"></i>Siswa SMK
                            </button>
                        </div>
                    </div>

                    @if (session('success'))
                        <div class="alert alert-success border-0 shadow-sm mb-3">
                            <i class="bi bi-check-circle me-1"></i>{{ session('success') }}
                        </div>
                    @endif

                    @if ($errors->any())
                        <div class="alert alert-danger border-0 shadow-sm mb-3">
                            <ul class="mb-0 ps-3 small">
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif

                    <form action="{{ route('login.perform') }}" method="POST" class="row g-3">
                        @csrf
                        <div class="col-12">
                            <label for="email" class="form-label fw-semibold" id="emailLabel">Email Guru BK</label>
                            <div class="input-group">
                                <span class="input-group-text bg-light text-muted"><i class="bi bi-envelope"></i></span>
                                <input type="email" class="form-control" id="email" name="email" value="{{ old('email') }}" placeholder="email@school.sch.id" required autofocus>
                            </div>
                        </div>

                        <div class="col-12">
                            <label for="password" class="form-label fw-semibold">Password</label>
                            <div class="input-group">
                                <span class="input-group-text bg-light text-muted"><i class="bi bi-lock"></i></span>
                                <input type="password" class="form-control" id="password" name="password" placeholder="Masukkan password" required>
                            </div>
                        </div>

                        <div class="col-12 d-flex justify-content-between align-items-center">
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" id="remember" name="remember">
                                <label class="form-check-label small" for="remember">Ingat saya</label>
                            </div>
                            <div id="registerLinkWrap">
                                <a href="{{ route('register') }}" class="small text-decoration-none fw-semibold">Daftar Guru BK</a>
                            </div>
                        </div>

                        <div class="col-12">
                            <button type="submit" id="btnSubmit" class="btn btn-primary w-100 py-2 fw-semibold shadow-sm">
                                <i class="bi bi-box-arrow-in-right me-1"></i>Login Guru BK
                            </button>
                        </div>
                    </form>

                    {{-- Notice khusus siswa --}}
                    <div id="siswaNotice" class="alert alert-light border p-2 px-3 mt-3 d-none text-center">
                        <small class="text-muted"><i class="bi bi-info-circle me-1 text-primary"></i>Akun Siswa dibuatkan dan diberikan resmi oleh Guru BK sekolah.</small>
                    </div>

                    {{-- Quick Demo Info --}}
                    <div class="mt-4 pt-3 border-top text-center">
                        <small class="text-muted d-block mb-2 fw-semibold">Uji Coba Demo Akun:</small>
                        <div class="d-flex justify-content-center gap-2">
                            <button type="button" class="btn btn-sm btn-outline-primary py-1" onclick="fillDemo('rdxrio45@gmail.com', 'password', 'guru_bk')">
                                <i class="bi bi-key me-1"></i>Guru BK
                            </button>
                            <button type="button" class="btn btn-sm btn-outline-success py-1" onclick="fillDemo('siswa@school.sch.id', 'password', 'siswa')">
                                <i class="bi bi-key me-1"></i>Siswa SMK
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </section>
    </div>

    <script>
        function setLoginRole(role) {
            let tabGuru = document.getElementById('tabGuru');
            let tabSiswa = document.getElementById('tabSiswa');
            let emailLabel = document.getElementById('emailLabel');
            let btnSubmit = document.getElementById('btnSubmit');
            let siswaNotice = document.getElementById('siswaNotice');
            let registerLinkWrap = document.getElementById('registerLinkWrap');

            if (role === 'guru_bk') {
                tabGuru.className = 'role-tab-btn active-guru';
                tabSiswa.className = 'role-tab-btn';
                emailLabel.innerText = 'Email Guru BK';
                btnSubmit.className = 'btn btn-primary w-100 py-2 fw-semibold shadow-sm';
                btnSubmit.innerHTML = '<i class="bi bi-box-arrow-in-right me-1"></i>Login Guru BK';
                siswaNotice.classList.add('d-none');
                registerLinkWrap.classList.remove('d-none');
            } else {
                tabSiswa.className = 'role-tab-btn active-siswa';
                tabGuru.className = 'role-tab-btn';
                emailLabel.innerText = 'Email Akun Siswa';
                btnSubmit.className = 'btn btn-success w-100 py-2 fw-semibold shadow-sm';
                btnSubmit.innerHTML = '<i class="bi bi-box-arrow-in-right me-1"></i>Login Siswa';
                siswaNotice.classList.remove('d-none');
                registerLinkWrap.classList.add('d-none');
            }
        }

        function fillDemo(email, password, role) {
            setLoginRole(role);
            document.getElementById('email').value = email;
            document.getElementById('password').value = password;
        }
    </script>
</body>

</html>