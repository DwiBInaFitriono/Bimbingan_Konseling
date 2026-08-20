<!DOCTYPE html>
<html lang="en">

<head>
    @include('include.style')
</head>

<body>
    @include('include.header')
    @include('include.sidebar')

    <main id="main" class="main">
        <div class="pagetitle">
            <h1>Pengaturan Aplikasi</h1>
            <nav>
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Beranda</a></li>
                    <li class="breadcrumb-item active">Pengaturan Aplikasi</li>
                </ol>
            </nav>
        </div>

        <section class="section">
            <div class="row">
                {{-- Preferensi Tampilan --}}
                <div class="col-lg-6 mb-4">
                    <div class="card h-100">
                        <div class="card-body">
                            <h5 class="card-title">
                                <i class="bi bi-palette me-2 text-primary"></i>Preferensi Tampilan
                            </h5>
                            <p class="text-muted small mb-4">Atur tema dan ukuran teks pada aplikasi.</p>
                            
                            <div class="mb-3">
                                <label class="form-label fw-bold">Tema Aplikasi</label>
                                <select class="form-select">
                                    <option value="light" selected>Terang (Light Mode)</option>
                                    <option value="dark">Gelap (Dark Mode)</option>
                                    <option value="system">Ikuti Sistem</option>
                                </select>
                            </div>
                            
                            <div class="mb-3">
                                <label class="form-label fw-bold">Ukuran Teks</label>
                                <select class="form-select">
                                    <option value="small">Kecil</option>
                                    <option value="medium" selected>Sedang (Bawaan)</option>
                                    <option value="large">Besar</option>
                                </select>
                            </div>
                            
                            <button class="btn btn-outline-primary mt-2">Simpan Preferensi</button>
                        </div>
                    </div>
                </div>

                {{-- Pengaturan Notifikasi --}}
                <div class="col-lg-6 mb-4">
                    <div class="card h-100">
                        <div class="card-body">
                            <h5 class="card-title">
                                <i class="bi bi-bell me-2 text-warning"></i>Pengaturan Notifikasi
                            </h5>
                            <p class="text-muted small mb-4">Pilih jenis pemberitahuan yang ingin diterima.</p>
                            
                            <div class="form-check form-switch mb-3">
                                <input class="form-check-input" type="checkbox" id="notifJadwal" checked>
                                <label class="form-check-label" for="notifJadwal">Jadwal Konseling Baru</label>
                            </div>
                            <div class="form-check form-switch mb-3">
                                <input class="form-check-input" type="checkbox" id="notifPelanggaran" checked>
                                <label class="form-check-label" for="notifPelanggaran">Peringatan Poin Pelanggaran Siswa Tinggi</label>
                            </div>
                            <div class="form-check form-switch mb-3">
                                <input class="form-check-input" type="checkbox" id="notifLaporan">
                                <label class="form-check-label" for="notifLaporan">Pengingat Rekapan Bulanan</label>
                            </div>
                            <div class="form-check form-switch mb-3">
                                <input class="form-check-input" type="checkbox" id="notifEmail" checked>
                                <label class="form-check-label" for="notifEmail">Kirim ke Email</label>
                            </div>

                            <button class="btn btn-outline-warning mt-2">Simpan Notifikasi</button>
                        </div>
                    </div>
                </div>

                {{-- Pengaturan Sistem --}}
                <div class="col-lg-12">
                    <div class="card">
                        <div class="card-body">
                            <h5 class="card-title">
                                <i class="bi bi-gear-wide-connected me-2 text-secondary"></i>Pengaturan Umum
                            </h5>
                            
                            <div class="row mt-3">
                                <div class="col-md-4 mb-3">
                                    <label class="form-label fw-bold">Bahasa Sistem</label>
                                    <select class="form-select">
                                        <option value="id" selected>Bahasa Indonesia</option>
                                        <option value="en">English</option>
                                    </select>
                                </div>
                                <div class="col-md-4 mb-3">
                                    <label class="form-label fw-bold">Zona Waktu</label>
                                    <select class="form-select">
                                        <option value="asia-jakarta" selected>Asia/Jakarta (WIB)</option>
                                        <option value="asia-makassar">Asia/Makassar (WITA)</option>
                                        <option value="asia-jayapura">Asia/Jayapura (WIT)</option>
                                    </select>
                                </div>
                                <div class="col-md-4 mb-3">
                                    <label class="form-label fw-bold">Tahun Ajaran Aktif</label>
                                    <select class="form-select">
                                        <option value="2025-2026">2025/2026</option>
                                        <option value="2026-2027" selected>2026/2027</option>
                                    </select>
                                </div>
                            </div>

                            <div class="mt-3">
                                <button class="btn btn-primary px-4"><i class="bi bi-save me-2"></i>Simpan Konfigurasi Sistem</button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>
    </main>

    @include('include.footer')

    {{-- Vendor JS & Notification Scripts --}}
    @include('include.script')
</body>

</html>