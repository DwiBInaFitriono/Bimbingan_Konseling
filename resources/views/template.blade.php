<!DOCTYPE html>
<html lang="en">

<head>
    @include('include.style')
</head>

<body>
    @php
        $welcomeUser = auth()->user() ?? $currentUser ?? \App\Models\User::latest()->first();
    @endphp

    <!-- ======= Header ======= -->
    @include('include.header')

    <!-- ======= Sidebar ======= -->
    @include('include.sidebar')

    <main id="main" class="main">

        <div class="pagetitle">
            <h1>Dashboard Sistem BK</h1>
            <nav>
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Beranda</a></li>
                    <li class="breadcrumb-item active">Dashboard</li>
                </ol>
            </nav>
        </div><!-- End Page Title -->

        <div class="col-12 mb-3">
            <div class="card bg-light border-0 shadow-sm">
                <div class="card-body py-3">
                    <h5 class="card-title m-0 text-primary text-break">Selamat datang, {{ $welcomeUser?->name ?? 'Guru BK' }}</h5>
                    <p class="mb-0 text-muted small">
                        Berikut ringkasan data siswa, pengajuan konseling, dan catatan kedisiplinan hari ini.
                    </p>
                </div>
            </div>
        </div>

        <section class="section dashboard">
            {{-- Metric Cards --}}
            <div class="row">
                <div class="col-xxl-3 col-md-6 mb-3">
                    <div class="card info-card sales-card h-100 mb-0">
                        <div class="card-body">
                            <h5 class="card-title">Total Siswa</h5>
                            <div class="d-flex align-items-center">
                                <div class="card-icon rounded-circle d-flex align-items-center justify-content-center bg-primary text-white">
                                    <i class="bi bi-people fs-3"></i>
                                </div>
                                <div class="ps-3">
                                    <h6>{{ $totalStudents }}</h6>
                                    <span class="text-muted small">Terdaftar</span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-xxl-3 col-md-6 mb-3">
                    <div class="card info-card revenue-card h-100 mb-0">
                        <div class="card-body">
                            <h5 class="card-title">Pengajuan Konseling</h5>
                            <div class="d-flex align-items-center">
                                <div class="card-icon rounded-circle d-flex align-items-center justify-content-center bg-warning text-dark">
                                    <i class="bi bi-calendar-event fs-3"></i>
                                </div>
                                <div class="ps-3">
                                    <h6>{{ $pendingCounseling ?? 0 }}</h6>
                                    <span class="text-muted small">Menunggu Respon</span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-xxl-3 col-md-6 mb-3">
                    <div class="card info-card customers-card h-100 mb-0">
                        <div class="card-body">
                            <h5 class="card-title">Studi Kasus Hari Ini</h5>
                            <div class="d-flex align-items-center">
                                <div class="card-icon rounded-circle d-flex align-items-center justify-content-center bg-info text-white">
                                    <i class="bi bi-journal-text fs-3"></i>
                                </div>
                                <div class="ps-3">
                                    <h6>{{ $reportsToday }}</h6>
                                    <span class="text-muted small">Laporan Masuk</span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-xxl-3 col-md-6 mb-3">
                    <div class="card info-card h-100 mb-0 border-start border-danger border-4">
                        <div class="card-body">
                            <h5 class="card-title text-danger">Siswa Status Bahaya</h5>
                            <div class="d-flex align-items-center">
                                <div class="card-icon rounded-circle d-flex align-items-center justify-content-center bg-danger text-white">
                                    <i class="bi bi-exclamation-triangle fs-3"></i>
                                </div>
                                <div class="ps-3">
                                    <h6 class="text-danger">{{ $dangerStudents ?? 0 }}</h6>
                                    <span class="text-muted small">Poin &gt; 75</span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Recent Activity Tables --}}
            <div class="row mt-3">
                {{-- Counseling Pending Table --}}
                <div class="col-lg-6 mb-3">
                    <div class="card h-100">
                        <div class="card-body">
                            <div class="d-flex justify-content-between align-items-center mb-2">
                                <h5 class="card-title m-0">Pengajuan Konseling Terbaru</h5>
                                <a href="{{ route('counseling.index') }}" class="btn btn-sm btn-outline-primary">Lihat Semua</a>
                            </div>

                            <div class="table-responsive">
                                <table class="table table-hover align-middle">
                                    <thead>
                                        <tr>
                                            <th>Siswa</th>
                                            <th>Tanggal & Waktu</th>
                                            <th>Topik</th>
                                            <th>Status</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @forelse(($recentCounseling ?? collect()) as $c)
                                            <tr>
                                                <td>
                                                    <strong>{{ $c->student?->full_name }}</strong><br>
                                                    <small class="text-muted">{{ $c->student?->class?->school_class_name }}</small>
                                                </td>
                                                <td>
                                                    <small><i class="bi bi-calendar me-1"></i>{{ $c->requested_date ? \Carbon\Carbon::parse($c->requested_date)->format('d/m/Y') : '-' }}</small><br>
                                                    <small class="text-muted"><i class="bi bi-clock me-1"></i>{{ $c->requested_time ? \Carbon\Carbon::parse($c->requested_time)->format('H:i') : '-' }}</small>
                                                </td>
                                                <td><small>{{ \Illuminate\Support\Str::limit($c->topic ?? '', 25) }}</small></td>
                                                <td>
                                                    <span class="badge bg-warning text-dark">Menunggu</span>
                                                </td>
                                            </tr>
                                        @empty
                                            <tr>
                                                <td colspan="4" class="text-center py-3 text-muted">Belum ada pengajuan konseling baru.</td>
                                            </tr>
                                        @endforelse
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- Recent Achievements Table --}}
                <div class="col-lg-6 mb-3">
                    <div class="card h-100">
                        <div class="card-body">
                            <div class="d-flex justify-content-between align-items-center mb-2">
                                <h5 class="card-title m-0">Prestasi Siswa Terbaru</h5>
                                <a href="{{ route('dataprestasi.tampil') }}" class="btn btn-sm btn-outline-success">Lihat Semua</a>
                            </div>

                            <div class="table-responsive">
                                <table class="table table-hover align-middle">
                                    <thead>
                                        <tr>
                                            <th>Siswa</th>
                                            <th>Prestasi</th>
                                            <th>Tingkat</th>
                                            <th>Tanggal</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @forelse(($recentAchievements ?? collect()) as $a)
                                            <tr>
                                                <td>
                                                    <strong>{{ $a->student?->full_name }}</strong><br>
                                                    <small class="text-muted">{{ $a->student?->class?->school_class_name }}</small>
                                                </td>
                                                <td><span class="fw-semibold text-success"><i class="bi bi-trophy me-1"></i>{{ $a->achievement_name }}</span></td>
                                                <td><span class="badge bg-info">{{ $a->achievement_level }}</span></td>
                                                <td><small class="text-muted">{{ \Carbon\Carbon::parse($a->achievement_date)->format('d/m/Y') }}</small></td>
                                            </tr>
                                        @empty
                                            <tr>
                                                <td colspan="4" class="text-center py-3 text-muted">Belum ada data prestasi tercatat.</td>
                                            </tr>
                                        @endforelse
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>

    </main><!-- End #main -->

    <!-- ======= Footer ======= -->
    @include('include.footer')

    <a href="#" class="back-to-top d-flex align-items-center justify-content-center"><i class="bi bi-arrow-up-short"></i></a>
    @include('include.script')

</body>

</html>
