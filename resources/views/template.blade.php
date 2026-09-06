<!DOCTYPE html>
<html lang="en">

<head>
    @include('include.style')
</head>

<body>
    @php
        $welcomeUser = $currentUser ?? auth()->user();
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

        @php
            $currentHour = (int) now()->format('H');
            if ($currentHour >= 4 && $currentHour < 11) {
                $serverGreeting = 'Selamat pagi';
            } elseif ($currentHour >= 11 && $currentHour < 15) {
                $serverGreeting = 'Selamat siang';
            } elseif ($currentHour >= 15 && $currentHour < 18) {
                $serverGreeting = 'Selamat sore';
            } else {
                $serverGreeting = 'Selamat malam';
            }

            \Carbon\Carbon::setLocale('id');
            $serverDate = now()->translatedFormat('l, d F Y');
            $serverClock = now()->format('H:i:s') . ' WIB';
        @endphp

        <div class="col-12 mb-3">
            <div class="welcome-hero-card p-3 p-md-4 rounded-3 shadow-xs border position-relative overflow-hidden">
                <div class="d-flex flex-wrap justify-content-between align-items-center gap-3 mb-2">
                    <div class="d-flex align-items-center gap-3">
                        <div class="welcome-avatar-badge d-flex align-items-center justify-content-center">
                            <i class="bi bi-person-check-fill"></i>
                        </div>
                        <div>
                            <h4 class="welcome-title mb-0">
                                <span id="dynamicGreeting">{{ $serverGreeting }}</span>, 
                                <span class="welcome-name">{{ $welcomeUser?->name ?? 'Guru BK' }}</span>
                            </h4>
                        </div>
                    </div>
                    <div class="welcome-date-chip d-none d-sm-inline-flex align-items-center gap-2">
                        <i class="bi bi-calendar3 text-primary"></i>
                        <span class="welcome-date-text" id="welcomeLiveDate">{{ $serverDate }}</span>
                        <span class="welcome-clock-badge font-monospace" id="welcomeLiveClock">{{ $serverClock }}</span>
                    </div>
                </div>

                {{-- Motivational Counselor Quotes --}}
                <div class="welcome-quote-box mt-2 pt-2 border-top d-flex align-items-center gap-2">
                    <i class="bi bi-quote fs-5 text-primary opacity-50 flex-shrink-0"></i>
                    <div class="quote-text-container">
                        <span id="animatedQuote" class="welcome-quote-text">Layanan bimbingan dan konseling siap mendampingi perkembangan siswa hari ini.</span>
                    </div>
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

    <script>
        (function () {
            // Realtime Clock Ticker (detik berjalan)
            const days = ['Minggu', 'Senin', 'Selasa', 'Rabu', 'Kamis', 'Jumat', 'Sabtu'];
            const months = ['Januari', 'Februari', 'Maret', 'April', 'Mei', 'Juni', 'Juli', 'Agustus', 'September', 'Oktober', 'November', 'Desember'];

            function tickClock() {
                const now = new Date();
                const dateEl = document.getElementById('welcomeLiveDate');
                const clockEl = document.getElementById('welcomeLiveClock');

                if (dateEl) {
                    dateEl.textContent = `${days[now.getDay()]}, ${now.getDate()} ${months[now.getMonth()]} ${now.getFullYear()}`;
                }
                if (clockEl) {
                    const h = String(now.getHours()).padStart(2, '0');
                    const m = String(now.getMinutes()).padStart(2, '0');
                    const s = String(now.getSeconds()).padStart(2, '0');
                    clockEl.textContent = `${h}:${m}:${s} WIB`;
                }
            }
            tickClock();
            setInterval(tickClock, 500);

            // Grounded BK Motivational Quotations Rotator (Anti-slop)
            const quotes = [
                "Layanan bimbingan dan konseling siap mendampingi perkembangan siswa hari ini.",
                "Catat setiap sesi konseling dan pantau perkembangan karakter siswa secara berkala.",
                "Komunikasi terbuka dan empati menjadi kunci pendampingan siswa di sekolah.",
                "Pantau kedisiplinan dan apresiasi setiap capaian prestasi siswa.",
                "Bimbingan yang konsisten membantu siswa menemukan potensi terbaiknya."
            ];

            const quoteEl = document.getElementById('animatedQuote');
            if (quoteEl) {
                let currentIndex = 0;

                setInterval(() => {
                    quoteEl.classList.add('quote-fade-out');
                    setTimeout(() => {
                        currentIndex = (currentIndex + 1) % quotes.length;
                        quoteEl.textContent = quotes[currentIndex];
                        quoteEl.classList.remove('quote-fade-out');
                        quoteEl.classList.add('quote-fade-in');
                        setTimeout(() => {
                            quoteEl.classList.remove('quote-fade-in');
                        }, 400);
                    }, 300);
                }, 7000);
            }
        })();
    </script>
</body>

</html>
