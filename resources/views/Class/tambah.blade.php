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
            <h1>Tambah Kelas Baru</h1>
            <nav>
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Beranda</a></li>
                    <li class="breadcrumb-item"><a href="{{ route('kelas.tampil') }}">Data Kelas</a></li>
                    <li class="breadcrumb-item active">Tambah Kelas</li>
                </ol>
            </nav>
        </div>

        <section class="section">
            <div class="row justify-content-center">
                <div class="col-lg-8">
                    <div class="card border-0 shadow-sm rounded-3">
                        <div class="card-body p-4">
                            <div class="d-flex align-items-center justify-content-between pb-3 mb-4 border-bottom">
                                <h5 class="card-title fw-bold text-dark p-0 m-0">Formulir Kelas SMK</h5>
                                <a href="{{ route('kelas.tampil') }}" class="btn btn-outline-secondary btn-sm">
                                    <i class="bi bi-arrow-left me-1"></i>Kembali
                                </a>
                            </div>

                            <form action="{{ url('simpankelas') }}" method="POST">
                                @csrf

                                <div class="mb-3">
                                    <label for="grade" class="form-label fw-semibold">Tingkat / Angkatan SMK <span class="text-danger">*</span></label>
                                    <select name="grade" id="grade" class="form-select" required>
                                        <option value="10">Kelas 10 (X)</option>
                                        <option value="11">Kelas 11 (XI)</option>
                                        <option value="12">Kelas 12 (XII)</option>
                                    </select>
                                </div>

                                <div class="mb-3">
                                    <label for="school_class_name" class="form-label fw-semibold">Nama Rombongan Belajar (Kelas) <span class="text-danger">*</span></label>
                                    <input type="text" id="school_class_name" name="school_class_name" class="form-control" placeholder="Contoh: X RPL 1 / XI MP 2" required>
                                </div>

                                <div class="mb-4">
                                    <label for="school_class_major" class="form-label fw-semibold">Nama Jurusan / Kompetensi Keahlian <span class="text-danger">*</span></label>
                                    <input type="text" id="school_class_major" name="school_class_major" class="form-control" placeholder="Contoh: Rekayasa Perangkat Lunak" required>
                                </div>

                                <div class="d-flex justify-content-end gap-2 pt-3 border-top">
                                    <a href="{{ route('kelas.tampil') }}" class="btn btn-secondary px-4">Batal</a>
                                    <button type="submit" class="btn btn-primary px-4 fw-semibold"><i class="bi bi-check-lg me-1"></i>Simpan Kelas</button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </section>
    </main>

    @include('include.footer')
    <script src="{{ asset('assets/vendor/bootstrap/js/bootstrap.bundle.min.js') }}"></script>
    <script src="{{ asset('assets/js/main.js') }}"></script>
</body>

</html>
