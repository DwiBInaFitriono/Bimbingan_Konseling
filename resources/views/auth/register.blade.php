<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Registrasi Guru BK - Sistem Manajemen BK</title>
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
            grid-template-columns: 0.9fr 1.1fr;
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
            width: min(100%, 460px);
            border: 0;
            border-radius: 24px;
            box-shadow: 0 24px 60px rgba(1, 41, 112, 0.14);
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
        <section class="auth-hero order-lg-2">
            <div class="brand-chip mb-4">
                <img src="{{ asset('assets/img/logo-bk.svg') }}" alt="Sistem BK">
                <div>
                    <div class="fw-bold">Sistem Manajemen BK</div>
                    <small class="text-primary fw-semibold">Pendaftaran Guru BK</small>
                </div>
            </div>
            <h1 class="display-6 fw-bold mb-3">Registrasi Akun Guru BK</h1>
            <p class="lead mb-4" style="max-width: 520px;">
                Daftar sebagai Guru BK untuk mengelola data siswa, jadwal konseling, dan catatan poin.
            </p>
            <div class="p-3 bg-white bg-opacity-75 rounded-3 shadow-sm border border-info border-opacity-25" style="max-width: 520px;">
                <div class="d-flex align-items-center mb-1">
                    <i class="bi bi-info-circle-fill text-info fs-5 me-2"></i>
                    <strong class="text-dark">Akun Siswa:</strong>
                </div>
                <small class="text-muted d-block">
                    Akun siswa didaftarkan oleh Guru BK melalui menu <strong>Data Siswa</strong> di dalam sistem.
                </small>
            </div>
        </section>

        <section class="auth-card-wrap order-lg-1">
            <div class="card auth-card">
                <div class="card-body p-4 p-md-5">
                    <div class="text-center mb-4">
                        <div class="d-inline-flex align-items-center justify-content-center bg-primary bg-opacity-10 text-primary rounded-circle mb-2" style="width: 56px; height: 56px;">
                            <i class="bi bi-person-badge fs-3"></i>
                        </div>
                        <h2 class="h4 fw-bold mb-1">Daftar Akun</h2>
                        <p class="text-muted small mb-0">Lengkapi data berikut untuk membuat akun Guru BK</p>
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

                    <form action="{{ route('register.perform') }}" method="POST" class="row g-3">
                        @csrf
                        <div class="col-12">
                            <label for="name" class="form-label fw-semibold">Nama Lengkap & Gelar</label>
                            <div class="input-group">
                                <span class="input-group-text bg-light text-muted"><i class="bi bi-person"></i></span>
                                <input type="text" class="form-control" id="name" name="name" value="{{ old('name') }}" placeholder="Contoh: Dra. Endang S.Pd" required autofocus>
                            </div>
                        </div>

                        <div class="col-12">
                            <label for="email" class="form-label fw-semibold">Email Resmi Guru BK</label>
                            <div class="input-group">
                                <span class="input-group-text bg-light text-muted"><i class="bi bi-envelope"></i></span>
                                <input type="email" class="form-control" id="email" name="email" value="{{ old('email') }}" placeholder="gurubk@school.sch.id" required>
                            </div>
                        </div>

                        <div class="col-12">
                            <label for="password" class="form-label fw-semibold">Password</label>
                            <div class="input-group">
                                <span class="input-group-text bg-light text-muted"><i class="bi bi-lock"></i></span>
                                <input type="password" class="form-control" id="password" name="password" placeholder="Minimal 6 karakter" required>
                            </div>
                        </div>

                        <div class="col-12">
                            <label for="password_confirmation" class="form-label fw-semibold">Konfirmasi Password</label>
                            <div class="input-group">
                                <span class="input-group-text bg-light text-muted"><i class="bi bi-shield-lock"></i></span>
                                <input type="password" class="form-control" id="password_confirmation" name="password_confirmation" placeholder="Ulangi password" required>
                            </div>
                        </div>

                        <div class="col-12 d-flex justify-content-between align-items-center">
                            <a href="{{ route('login') }}" class="small text-decoration-none fw-semibold"><i class="bi bi-arrow-left me-1"></i>Sudah punya akun? Login</a>
                        </div>

                        <div class="col-12">
                            <button type="submit" class="btn btn-primary w-100 py-2 fw-semibold shadow-sm">
                                <i class="bi bi-person-plus-fill me-1"></i>Daftar Sebagai Guru BK
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </section>
    </div>

</body>

</html>