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
            <h1>Bantuan</h1>
            <nav>
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Beranda</a></li>
                    <li class="breadcrumb-item active">Bantuan</li>
                </ol>
            </nav>
        </div>

        <section class="section">
            <div class="row">
                {{-- Card 1: Panduan --}}
                <div class="col-lg-4 col-md-6">
                    <div class="card text-center">
                        <div class="card-body py-4">
                            <div class="rounded-circle bg-primary bg-opacity-10 d-inline-flex align-items-center justify-content-center mb-3" style="width:64px;height:64px;">
                                <i class="bi bi-book fs-3 text-primary"></i>
                            </div>
                            <h5>Panduan Penggunaan</h5>
                            <p class="text-muted small">Langkah-langkah dasar untuk mengelola data siswa, poin, dan konseling.</p>
                            <a href="#faq-section" class="btn btn-sm btn-outline-primary">Baca Panduan</a>
                        </div>
                    </div>
                </div>
                {{-- Card 2: Kontak --}}
                <div class="col-lg-4 col-md-6">
                    <div class="card text-center">
                        <div class="card-body py-4">
                            <div class="rounded-circle bg-success bg-opacity-10 d-inline-flex align-items-center justify-content-center mb-3" style="width:64px;height:64px;">
                                <i class="bi bi-chat-dots fs-3 text-success"></i>
                            </div>
                            <h5>Hubungi Admin</h5>
                            <p class="text-muted small">Ada kendala teknis? Langsung hubungi admin lewat email atau WhatsApp.</p>
                            <a href="#contact-section" class="btn btn-sm btn-outline-success">Lihat Kontak</a>
                        </div>
                    </div>
                </div>
                {{-- Card 3: Tips --}}
                <div class="col-lg-4 col-md-6">
                    <div class="card text-center">
                        <div class="card-body py-4">
                            <div class="rounded-circle bg-warning bg-opacity-10 d-inline-flex align-items-center justify-content-center mb-3" style="width:64px;height:64px;">
                                <i class="bi bi-lightning fs-3 text-warning"></i>
                            </div>
                            <h5>Tips Pemakaian</h5>
                            <p class="text-muted small">Beberapa kebiasaan kecil supaya kerja di sistem ini lebih cepat.</p>
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
                                <i class="bi bi-question-circle me-2 text-primary"></i>Pertanyaan Umum
                            </h5>

                            <div class="accordion" id="faqAccordion">
                                <div class="accordion-item">
                                    <h2 class="accordion-header" id="faqOne">
                                        <button class="accordion-button" type="button" data-bs-toggle="collapse" data-bs-target="#collapseOne" aria-expanded="true" aria-controls="collapseOne">
                                            Bagaimana cara menambah data siswa?
                                        </button>
                                    </h2>
                                    <div id="collapseOne" class="accordion-collapse collapse show" data-bs-parent="#faqAccordion" aria-labelledby="faqOne">
                                        <div class="accordion-body">
                                            <ol>
                                                <li>Buka menu <strong>Data Siswa</strong> di sidebar kiri.</li>
                                                <li>Klik tombol <strong>Tambah Siswa</strong> di pojok kanan atas tabel.</li>
                                                <li>Isi kolom yang wajib diisi: Nama, NIS, Kelas, dan Jenis Kelamin.</li>
                                                <li>Klik <strong>Simpan</strong>. Data siswa langsung muncul di tabel.</li>
                                            </ol>
                                        </div>
                                    </div>
                                </div>

                                <div class="accordion-item">
                                    <h2 class="accordion-header" id="faqTwo">
                                        <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapseTwo" aria-expanded="false" aria-controls="collapseTwo">
                                            Bagaimana cara mengganti password?
                                        </button>
                                    </h2>
                                    <div id="collapseTwo" class="accordion-collapse collapse" data-bs-parent="#faqAccordion" aria-labelledby="faqTwo">
                                        <div class="accordion-body">
                                            <ol>
                                                <li>Klik foto profil Anda di pojok kanan atas, lalu pilih <strong>Pengaturan Akun</strong>.</li>
                                                <li>Di kolom kanan halaman tersebut, isi password lama dan password baru.</li>
                                                <li>Ketik ulang password baru untuk konfirmasi, lalu klik <strong>Perbarui Password</strong>.</li>
                                            </ol>
                                            Anda juga bisa langsung ke halaman <a href="{{ route('profile.show') }}">Pengaturan Akun</a>.
                                        </div>
                                    </div>
                                </div>

                                <div class="accordion-item">
                                    <h2 class="accordion-header" id="faqThree">
                                        <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapseThree" aria-expanded="false" aria-controls="collapseThree">
                                            Bagaimana cara membuat laporan studi kasus?
                                        </button>
                                    </h2>
                                    <div id="collapseThree" class="accordion-collapse collapse" data-bs-parent="#faqAccordion" aria-labelledby="faqThree">
                                        <div class="accordion-body">
                                            <ol>
                                                <li>Buka menu <strong>Studi Kasus</strong> di sidebar.</li>
                                                <li>Klik <strong>Tambah Studi Kasus</strong>.</li>
                                                <li>Pilih nama siswa, isi kronologi dan tindakan yang sudah dilakukan.</li>
                                                <li>Klik <strong>Simpan</strong>. Laporan otomatis tersimpan dan bisa dicetak sebagai PDF.</li>
                                            </ol>
                                        </div>
                                    </div>
                                </div>

                                <div class="accordion-item">
                                    <h2 class="accordion-header" id="faqFour">
                                        <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapseFour" aria-expanded="false" aria-controls="collapseFour">
                                            Bagaimana sistem poin siswa bekerja?
                                        </button>
                                    </h2>
                                    <div id="collapseFour" class="accordion-collapse collapse" data-bs-parent="#faqAccordion" aria-labelledby="faqFour">
                                        <div class="accordion-body">
                                            Sistem poin terdiri dari dua bagian:
                                            <ul class="mt-2">
                                                <li><strong>Kategori Poin</strong> : buat dulu jenis pelanggaran atau penghargaan beserta bobotnya. Contoh: "Terlambat masuk kelas" = 5 poin.</li>
                                                <li><strong>Data Poin</strong> : catat poin siswa berdasarkan kategori yang sudah dibuat. Poin otomatis terakumulasi di profil siswa.</li>
                                            </ul>
                                            Urutan kerjanya: buat Kategori Poin dulu, baru catat Data Poin.
                                        </div>
                                    </div>
                                </div>

                                <div class="accordion-item">
                                    <h2 class="accordion-header" id="faqFive">
                                        <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapseFive" aria-expanded="false" aria-controls="collapseFive">
                                            Bagaimana cara mencatat prestasi siswa?
                                        </button>
                                    </h2>
                                    <div id="collapseFive" class="accordion-collapse collapse" data-bs-parent="#faqAccordion" aria-labelledby="faqFive">
                                        <div class="accordion-body">
                                            <ol>
                                                <li>Buka menu <strong>Prestasi</strong> di sidebar.</li>
                                                <li>Klik <strong>Tambah Prestasi</strong>.</li>
                                                <li>Pilih siswa, tulis nama prestasi, tingkat (sekolah/kota/provinsi/nasional), dan tanggal. Lampirkan bukti kalau ada.</li>
                                                <li>Klik <strong>Simpan</strong>.</li>
                                            </ol>
                                        </div>
                                    </div>
                                </div>

                                <div class="accordion-item">
                                    <h2 class="accordion-header" id="faqSix">
                                        <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapseSix" aria-expanded="false" aria-controls="collapseSix">
                                            Data terhapus, bisa dikembalikan?
                                        </button>
                                    </h2>
                                    <div id="collapseSix" class="accordion-collapse collapse" data-bs-parent="#faqAccordion" aria-labelledby="faqSix">
                                        <div class="accordion-body">
                                            Data yang sudah dihapus tidak bisa dikembalikan secara otomatis. Kalau ini terjadi, segera hubungi admin supaya bisa dicek dari backup database.
                                            <br><br>
                                            Saran: sebelum menghapus data, pastikan Anda sudah mengecek ulang. Sistem selalu menampilkan konfirmasi sebelum penghapusan.
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
                                <i class="bi bi-lightbulb me-2 text-warning"></i>Tips Pemakaian
                            </h5>
                            <div class="row g-3">
                                <div class="col-md-6">
                                    <div class="border rounded p-3">
                                        <div class="d-flex align-items-center mb-2">
                                            <span class="badge bg-primary me-2">1</span>
                                            <strong>Pakai Fitur Pencarian</strong>
                                        </div>
                                        <p class="text-muted small mb-0">Ketik nama atau NIS di kolom pencarian tabel. Lebih cepat daripada scroll satu per satu.</p>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="border rounded p-3">
                                        <div class="d-flex align-items-center mb-2">
                                            <span class="badge bg-success me-2">2</span>
                                            <strong>Isi Data Orang Tua Sejak Awal</strong>
                                        </div>
                                        <p class="text-muted small mb-0">Data orang tua/wali dibutuhkan saat cetak surat atau laporan konseling. Isi lengkap dari awal supaya tidak bolak-balik nanti.</p>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="border rounded p-3">
                                        <div class="d-flex align-items-center mb-2">
                                            <span class="badge bg-warning text-dark me-2">3</span>
                                            <strong>Perbarui Kelas Tiap Tahun Ajaran</strong>
                                        </div>
                                        <p class="text-muted small mb-0">Saat naik kelas atau pindah rombel, segera perbarui data kelas siswa agar laporan tidak tercampur antar angkatan.</p>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="border rounded p-3">
                                        <div class="d-flex align-items-center mb-2">
                                            <span class="badge bg-danger me-2">4</span>
                                            <strong>Ganti Password Berkala</strong>
                                        </div>
                                        <p class="text-muted small mb-0">Ganti password minimal setiap semester. Jangan pakai password yang sama dengan akun pribadi lain.</p>
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
                                <i class="bi bi-telephone me-2 text-success"></i>Hubungi Admin
                            </h5>
                            <div class="row">
                                <div class="col-md-4">
                                    <a href="mailto:admin@sistembk.sch.id" class="text-decoration-none">
                                        <div class="border rounded p-4 text-center h-100" style="transition:box-shadow .2s;cursor:pointer;" onmouseover="this.style.boxShadow='0 4px 12px rgba(0,0,0,.08)'" onmouseout="this.style.boxShadow='none'">
                                            <i class="bi bi-envelope fs-1 text-primary"></i>
                                            <h6 class="mt-3 mb-1 text-dark">Email</h6>
                                            <p class="text-muted mb-0">admin@sistembk.sch.id</p>
                                        </div>
                                    </a>
                                </div>
                                <div class="col-md-4">
                                    <a href="https://wa.me/6281234567890" target="_blank" rel="noopener" class="text-decoration-none">
                                        <div class="border rounded p-4 text-center h-100" style="transition:box-shadow .2s;cursor:pointer;" onmouseover="this.style.boxShadow='0 4px 12px rgba(0,0,0,.08)'" onmouseout="this.style.boxShadow='none'">
                                            <i class="bi bi-whatsapp fs-1 text-success"></i>
                                            <h6 class="mt-3 mb-1 text-dark">WhatsApp</h6>
                                            <p class="text-muted mb-0">+62 812-3456-7890</p>
                                        </div>
                                    </a>
                                </div>
                                <div class="col-md-4">
                                    <div class="border rounded p-4 text-center h-100">
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

    @include('include.footer')

    {{-- Vendor JS --}}
    <script src="{{ asset('assets/vendor/bootstrap/js/bootstrap.bundle.min.js') }}"></script>
    <script src="{{ asset('assets/js/main.js') }}"></script>

    {{-- Smooth scroll for anchor buttons --}}
    <script>
        document.querySelectorAll('a[href^="#"]').forEach(function(anchor) {
            anchor.addEventListener('click', function(e) {
                e.preventDefault();
                var target = document.querySelector(this.getAttribute('href'));
                if (target) {
                    target.scrollIntoView({ behavior: 'smooth', block: 'start' });
                }
            });
        });
    </script>
</body>

</html>
