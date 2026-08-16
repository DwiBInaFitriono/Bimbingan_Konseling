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
    <link href="{{ asset('assets/css/auth.css') }}" rel="stylesheet">
</head>

<body class="auth-page">
    <div class="auth-shell">
        <section class="auth-hero">
            <div class="brand-chip mb-4">
                <img src="{{ asset('assets/img/logo-bk-baru.jpg') }}" alt="Logo BK" style="border-radius: 50%;">
                <div>
                    <div class="fw-bold">Sistem Manajemen BK</div>
                    <small class="text-primary fw-semibold">Portal Masuk Terpadu</small>
                </div>
            </div>
            <h1 class="fw-bold mb-3 display-5">Bimbingan & Konseling Sekolah</h1>
            <p class="text-secondary fs-5 mb-5" style="max-width: 500px;">
                Silakan masuk menggunakan akun Anda untuk mengelola bimbingan konseling atau mengajukan jadwal bimbingan siswa.
            </p>
            
            <div class="d-flex gap-3">
                <!-- Fitur Utama -->
                <div class="p-3 bg-white rounded-4 shadow-sm border border-light w-100">
                    <i class="bi bi-shield-check fs-3 text-primary"></i>
                    <h6 class="fw-bold mt-2 mb-1 text-dark">Aman & Terpusat</h6>
                    <small class="text-muted">Kelola seluruh data secara rahasia dan aman.</small>
                </div>
                <div class="p-3 bg-white rounded-4 shadow-sm border border-light w-100">
                    <i class="bi bi-calendar-check fs-3 text-primary"></i>
                    <h6 class="fw-bold mt-2 mb-1 text-dark">Jadwal Efisien</h6>
                    <small class="text-muted">Kemudahan pemantauan jadwal dan aktivitas.</small>
                </div>
            </div>
        </section>

        <section class="auth-card-wrap theme-siswa">
            <div class="card auth-card shadow-lg">
                <div class="card-body p-4 pb-3">
                    <h2 class="h4 fw-bold text-center mb-1 text-success">Portal Siswa</h2>
                    <p class="text-muted text-center small mb-4">Masuk menggunakan email dari Guru BK</p>

                    @if (session('status'))
                        <div class="alert alert-success mb-3" role="alert">
                            {{ session('status') }}
                        </div>
                    @endif

                    @if ($errors->any())
                        <div class="alert-modern" role="alert">
                            <i class="bi bi-exclamation-circle-fill"></i>
                            <div>
                                @foreach ($errors->all() as $error)
                                    <div class="small fw-semibold">{{ $error }}</div>
                                @endforeach
                            </div>
                        </div>
                    @endif

                    <form action="{{ route('login.perform') }}" method="POST" class="row g-2">
                        @csrf
                        <input type="hidden" name="role" value="siswa">

                        <div class="col-12">
                            <label for="email" class="fk-form-label">Email Siswa</label>
                            <div class="fk-input-icon-group">
                                <i class="bi bi-envelope"></i>
                                <input type="email" name="email" class="fk-form-control" id="email" value="{{ old('email') }}" required autofocus placeholder="siswa@sekolah.sch.id">
                            </div>
                        </div>

                        <div class="col-12 mt-3">
                            <label for="password" class="fk-form-label">Password</label>
                            <div class="fk-input-icon-group">
                                <i class="bi bi-lock"></i>
                                <input type="password" name="password" class="fk-form-control" id="password" required placeholder="••••••••">
                            </div>
                        </div>

                        <div class="col-12 mt-2">
                            <div class="fk-form-check">
                                <input type="checkbox" id="remember" name="remember">
                                <label for="remember">Ingat saya</label>
                            </div>
                        </div>

                        <div class="col-12 mt-4">
                            <button type="submit" class="fk-btn-primary w-100" style="justify-content:center; padding:12px 26px;">
                                <i class="bi bi-box-arrow-in-right"></i>Login Siswa
                            </button>
                        </div>
                    </form>

                    {{-- Quick Demo Info --}}
                    <div class="mt-3 mb-0 text-center">
                        <small class="text-dark d-block mb-2 fw-bold"><i class="bi bi-magic me-1"></i> Uji Coba Demo Akun Cepat:</small>
                        <div class="d-flex justify-content-center gap-2">
                            <button type="button" class="fk-btn-light" style="font-size:0.8rem; padding:6px 16px;" onclick="fillDemo('siswa@school.sch.id', 'password')">
                                <i class="bi bi-person-badge"></i>Isi Demo Siswa
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </section>
    </div>

    <!-- Script to fill Demo Data -->
    <script>
        function fillDemo(email, password) {
            document.getElementById('email').value = email;
            document.getElementById('password').value = password;
        }
    </script>

</body>

</html>