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
            <h1>Butuh Bantuan?</h1>
            <nav>
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Beranda</a></li>
                    <li class="breadcrumb-item active">Pusat Bantuan</li>
                </ol>
            </nav>
        </div>

        <section class="section">
            <div class="row">
                {{-- Quick Help Cards --}}
                <div class="col-lg-4 col-md-6">
                    <div class="card text-center">
                        <div class="card-body py-4">
                            <div class="rounded-circle bg-primary bg-opacity-10 d-inline-flex align-items-center justify-content-center mb-3" style="width:64px;height:64px;">
                                <i class="bi bi-book fs-3 text-primary"></i>
                            </div>
                            <h5>Panduan Penggunaan</h5>
                            <p class="text-muted small">Pelajari cara menggunakan semua fitur dalam sistem BK.</p>
                            <a href="#faq-section" class="btn btn-sm btn-outline-primary">Baca FAQ</a>
                        </div>
                    </div>
                </div>
                <div class="col-lg-4 col-md-6">
                    <div class="card text-center">
                        <div class="card-body py-4">
                            <div class="rounded-circle bg-success bg-opacity-10 d-inline-flex align-items-center justify-content-center mb-3" style="width:64px;height:64px;">
                                <i class="bi bi-chat-dots fs-3 text-success"></i>
                            </div>
                            <h5>Hubungi Admin</h5>
                            <p class="text-muted small">Butuh bantuan lebih lanjut? Hubungi administrator sistem.</p>
                            <a href="#contact-section" class="btn btn-sm btn-outline-success">Lihat Kontak</a>
                        </div>
                    </div>
                </div>
                <div class="col-lg-4 col-md-6">
                    <div class="card text-center">
                        <div class="card-body py-4">
                            <div class="rounded-circle bg-warning bg-opacity-10 d-inline-flex align-items-center justify-content-center mb-3" style="width:64px;height:64px;">
                                <i class="bi bi-lightning fs-3 text-warning"></i>
                            </div>
                            <h5>Tips & Trik</h5>
                            <p class="text-muted small">Optimalkan penggunaan sistem dengan tips berguna.</p>
                            <a href="#tips-section" class="btn btn-sm btn-outline-warning">Lihat Tips</a>
                        </div>
                    </div>
                </div>
            </div>

            {{-- FAQ Section --}}
            <div class="row" id="faq-section">
                <div class="col-12">
                    <div class="card">
                        <div class="card-body">
                            <h5 class="card-title">
                                <i class="bi bi-question-circle me-2 text-primary"></i>Pertanyaan yang Sering Diajukan (FAQ)
                            </h5>

                            <div class="accordion" id="faqAccordion">
                                <div class="accordion-item">
                                    <h2 class="accordion-header" id="faqOne">
                                        <button class="accordion-button" type="button" data-bs-toggle="collapse" data-bs-target="#collapseOne" aria-expanded="true">
                                            Bagaimana cara menambahkan data siswa baru?
                                        </button>
                                    </h2>
                                    <div id="collapseOne" class="accordion-collapse collapse show" data-bs-parent="#faqAccordion">
                                        <div class="accordion-body">
                                            Untuk menambahkan data siswa baru:
                                            <ol>
                                                <li>Klik menu <strong>Data Siswa</strong> di sidebar kiri</li>
                                                <li>Klik tombol <strong>Tambah Siswa</strong></li>
                                                <li>Isi semua data yang diminta (Nama, NIS, Kelas, dll)</li>
                                                <li>Klik <strong>Simpan</strong> untuk menyimpan data</li>
                                            </ol>
                                        </div>
                                    </div>
                                </div>

                                <div class="accordion-item">
                                    <h2 class="accordion-header" id="faqTwo">
                                        <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapseTwo">
                                            Bagaimana cara mengubah password akun saya?
                                        </button>
                                    </h2>
                                    <div id="collapseTwo" class="accordion-collapse collapse" data-bs-parent="#faqAccordion">
                                        <div class="accordion-body">
                                            Untuk mengubah password:
                                            <ol>
                                                <li>Klik foto profil Anda di pojok kanan atas</li>
                                                <li>Pilih <strong>Pengaturan Akun</strong></li>
                                                <li>Pada bagian <strong>Keamanan Password</strong>, masukkan password lama dan password baru Anda</li>
                                                <li>Klik <strong>Ubah Password</strong></li>
                                            </ol>
                                            Anda juga bisa mengubah password dari halaman <a href="{{ route('profile.show') }}">Profil Saya</a> → tab <strong>Ubah Password</strong>.
                                        </div>
                                    </div>
                                </div>

                                <div class="accordion-item">
                                    <h2 class="accordion-header" id="faqThree">
                                        <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapseThree">
                                            Bagaimana cara membuat laporan studi kasus?
                                        </button>
                                    </h2>
                                    <div id="collapseThree" class="accordion-collapse collapse" data-bs-parent="#faqAccordion">
                                        <div class="accordion-body">
                                            Untuk membuat laporan studi kasus:
                                            <ol>
                                                <li>Klik menu <strong>Studi Kasus</strong> di sidebar</li>
                                                <li>Klik tombol <strong>Tambah Studi Kasus</strong></li>
                                                <li>Pilih siswa yang bersangkutan dan isi detail kasusnya</li>
                                                <li>Klik <strong>Simpan</strong></li>
                                            </ol>
                                        </div>
                                    </div>
                                </div>

                                <div class="accordion-item">
                                    <h2 class="accordion-header" id="faqFour">
                                        <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapseFour">
                                            Bagaimana cara mengelola data poin siswa?
                                        </button>
                                    </h2>
                                    <div id="collapseFour" class="accordion-collapse collapse" data-bs-parent="#faqAccordion">
                                        <div class="accordion-body">
                                            Sistem poin siswa terdiri dari dua bagian:
                                            <ul>
                                                <li><strong>Data Poin</strong> — untuk menambah/mengurangi poin siswa berdasarkan pelanggaran atau prestasi</li>
                                                <li><strong>Kategori Poin</strong> — untuk mengelola kategori poin (jenis pelanggaran, kategori prestasi, dll)</li>
                                            </ul>
                                            Pastikan Anda sudah membuat kategori poin terlebih dahulu sebelum memberikan poin kepada siswa.
                                        </div>
                                    </div>
                                </div>

                                <div class="accordion-item">
                                    <h2 class="accordion-header" id="faqFive">
                                        <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapseFive">
                                            Bagaimana cara mencatat prestasi siswa?
                                        </button>
                                    </h2>
                                    <div id="collapseFive" class="accordion-collapse collapse" data-bs-parent="#faqAccordion">
                                        <div class="accordion-body">
                                            Untuk mencatat prestasi siswa:
                                            <ol>
                                                <li>Klik menu <strong>Prestasi</strong> di sidebar</li>
                                                <li>Klik tombol <strong>Tambah Prestasi</strong></li>
                                                <li>Pilih siswa, isi jenis prestasi, deskripsi, dan lampirkan bukti jika ada</li>
                                                <li>Klik <strong>Simpan</strong></li>
                                            </ol>
                                        </div>
                                    </div>
                                </div>

                                <div class="accordion-item">
                                    <h2 class="accordion-header" id="faqSix">
                                        <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapseSix">
                                            Data saya hilang, bagaimana cara mengembalikannya?
                                        </button>
                                    </h2>
                                    <div id="collapseSix" class="accordion-collapse collapse" data-bs-parent="#faqAccordion">
                                        <div class="accordion-body">
                                            Jika data Anda hilang atau terhapus secara tidak sengaja, segera hubungi administrator sistem. Data yang sudah dihapus mungkin tidak bisa dikembalikan, jadi berhati-hatilah saat menggunakan fitur hapus. Untuk pencegahan, pastikan selalu mengecek ulang sebelum menghapus data apapun.
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Tips Section --}}
            <div class="row" id="tips-section">
                <div class="col-12">
                    <div class="card">
                        <div class="card-body">
                            <h5 class="card-title">
                                <i class="bi bi-lightbulb me-2 text-warning"></i>Tips & Trik
                            </h5>
                            <div class="row g-3">
                                <div class="col-md-6">
                                    <div class="border rounded p-3">
                                        <div class="d-flex align-items-center mb-2">
                                            <span class="badge bg-primary me-2">1</span>
                                            <strong>Gunakan Pencarian</strong>
                                        </div>
                                        <p class="text-muted small mb-0">Gunakan fitur pencarian di header untuk menemukan data dengan cepat tanpa harus scroll halaman.</p>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="border rounded p-3">
                                        <div class="d-flex align-items-center mb-2">
                                            <span class="badge bg-success me-2">2</span>
                                            <strong>Lengkapi Data Orang Tua</strong>
                                        </div>
                                        <p class="text-muted small mb-0">Selalu pastikan data orang tua/wali siswa sudah terisi lengkap untuk keperluan komunikasi dan laporan.</p>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="border rounded p-3">
                                        <div class="d-flex align-items-center mb-2">
                                            <span class="badge bg-warning me-2">3</span>
                                            <strong>Rutin Perbarui Data</strong>
                                        </div>
                                        <p class="text-muted small mb-0">Perbarui data siswa secara berkala, terutama saat ada perubahan kelas atau status siswa.</p>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="border rounded p-3">
                                        <div class="d-flex align-items-center mb-2">
                                            <span class="badge bg-danger me-2">4</span>
                                            <strong>Amankan Akun Anda</strong>
                                        </div>
                                        <p class="text-muted small mb-0">Ganti password secara rutin dan jangan bagikan kredensial login Anda kepada orang lain.</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Contact Section --}}
            <div class="row" id="contact-section">
                <div class="col-12">
                    <div class="card">
                        <div class="card-body">
                            <h5 class="card-title">
                                <i class="bi bi-telephone me-2 text-success"></i>Hubungi Kami
                            </h5>
                            <div class="row">
                                <div class="col-md-4">
                                    <div class="border rounded p-4 text-center">
                                        <i class="bi bi-envelope fs-1 text-primary"></i>
                                        <h6 class="mt-3 mb-1">Email</h6>
                                        <p class="text-muted mb-0">admin@sistembk.sch.id</p>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="border rounded p-4 text-center">
                                        <i class="bi bi-whatsapp fs-1 text-success"></i>
                                        <h6 class="mt-3 mb-1">WhatsApp</h6>
                                        <p class="text-muted mb-0">+62 812-3456-7890</p>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="border rounded p-4 text-center">
                                        <i class="bi bi-geo-alt fs-1 text-danger"></i>
                                        <h6 class="mt-3 mb-1">Lokasi</h6>
                                        <p class="text-muted mb-0">Ruang BK, Lantai 2</p>
                                    </div>
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