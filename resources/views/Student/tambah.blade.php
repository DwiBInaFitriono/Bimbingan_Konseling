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
            <h1>Tambah Data Siswa Baru</h1>
            <nav>
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Beranda</a></li>
                    <li class="breadcrumb-item"><a href="{{ route('siswa.tampil') }}">Data Siswa</a></li>
                    <li class="breadcrumb-item active">Tambah Siswa</li>
                </ol>
            </nav>
        </div>

        <section class="section">
            <div class="row justify-content-center">
                <div class="col-lg-10">
                    <div class="card border-0 shadow-sm rounded-3">
                        <div class="card-body p-4">
                            <div class="d-flex align-items-center justify-content-between pb-3 mb-4 border-bottom">
                                <h5 class="card-title fw-bold text-dark p-0 m-0">Formulir Pendaftaran Siswa</h5>
                                <a href="{{ route('siswa.tampil') }}" class="btn btn-outline-secondary btn-sm">
                                    <i class="bi bi-arrow-left me-1"></i>Kembali
                                </a>
                            </div>

                            <form action="{{ url('simpan') }}" method="POST">
                                @csrf

                                <div class="row g-3 mb-3">
                                    <div class="col-md-6">
                                        <label for="full_name" class="form-label fw-semibold">Nama Lengkap Siswa <span class="text-danger">*</span></label>
                                        <input type="text" id="full_name" name="full_name" class="form-control" placeholder="Masukkan nama lengkap" required>
                                    </div>
                                    <div class="col-md-6">
                                        <label for="nis" class="form-label fw-semibold">Nomor Induk Siswa (NIS) <span class="text-danger">*</span></label>
                                        <input type="text" id="nis" name="nis" class="form-control" placeholder="Contoh: 2024001" required>
                                    </div>
                                </div>

                                <div class="row g-3 mb-3">
                                    <div class="col-md-3">
                                        <label for="grade" class="form-label fw-semibold">Tingkat Kelas <span class="text-danger">*</span></label>
                                        <select class="form-select" id="grade" onchange="filterClasses()" required>
                                            <option value="" disabled selected>-- Pilih Tingkat --</option>
                                            <option value="10">Kelas 10 (X)</option>
                                            <option value="11">Kelas 11 (XI)</option>
                                            <option value="12">Kelas 12 (XII)</option>
                                        </select>
                                    </div>
                                    <div class="col-md-3">
                                        <label for="class_id" class="form-label fw-semibold">Rombel Kelas <span class="text-danger">*</span></label>
                                        <select class="form-select" name="class_id" id="class_id" required>
                                            <option value="" disabled selected>-- Pilih Tingkat Dulu --</option>
                                            {{-- Akan diisi via JavaScript --}}
                                        </select>
                                    </div>

                                    <div class="col-md-6">
                                        <label for="gender" class="form-label fw-semibold">Jenis Kelamin <span class="text-danger">*</span></label>
                                        <select class="form-select" name="gender" id="gender" required>
                                            <option value="L">Laki-laki</option>
                                            <option value="P">Perempuan</option>
                                        </select>
                                    </div>
                                </div>

                                <div class="row g-3 mb-3">
                                    <div class="col-md-6">
                                        <label for="date_of_birth" class="form-label fw-semibold">Tanggal Lahir <span class="text-danger">*</span></label>
                                        <input type="date" id="date_of_birth" name="date_of_birth" class="form-control" required>
                                    </div>
                                    <div class="col-md-6">
                                        <label for="phone_number" class="form-label fw-semibold">Nomor Telepon / WA Siswa</label>
                                        <input type="text" id="phone_number" name="phone_number" class="form-control" placeholder="Contoh: 081234567890">
                                    </div>
                                </div>

                                <div class="mb-3">
                                    <label for="email" class="form-label fw-semibold">Email untuk Akun Login Siswa</label>
                                    <input type="email" id="email" name="email" class="form-control" placeholder="siswa@school.sch.id (Password default: NIS)">
                                    <small class="text-muted">Jika diisi, akun login siswa akan dibuat otomatis dengan password sama dengan NIS.</small>
                                </div>

                                <div class="mb-4">
                                    <label for="address" class="form-label fw-semibold">Alamat Rumah</label>
                                    <textarea id="address" name="address" class="form-control" rows="2" placeholder="Masukkan alamat domisili siswa..."></textarea>
                                </div>

                                <div class="row g-3 mb-4 p-3 bg-light rounded border">
                                    <div class="col-12 mb-2">
                                        <h6 class="fw-bold text-dark m-0"><i class="bi bi-person-hearts me-2"></i>Informasi Orang Tua / Wali</h6>
                                        <small class="text-muted">Isi data ini untuk sekaligus menambahkan data orang tua.</small>
                                    </div>
                                    <div class="col-md-4">
                                        <label for="parent_full_name" class="form-label fw-semibold">Nama Orang Tua/Wali</label>
                                        <input type="text" name="parent_full_name" id="parent_full_name" class="form-control" placeholder="Nama Lengkap">
                                    </div>
                                    <div class="col-md-4">
                                        <label for="parent_relationship" class="form-label fw-semibold">Hubungan</label>
                                        <select class="form-select" name="parent_relationship" id="parent_relationship">
                                            <option value="ayah">Ayah</option>
                                            <option value="ibu">Ibu</option>
                                            <option value="wali">Wali</option>
                                        </select>
                                    </div>
                                    <div class="col-md-4">
                                        <label for="parent_phone_number" class="form-label fw-semibold">No. Telepon / WA</label>
                                        <input type="text" name="parent_phone_number" id="parent_phone_number" class="form-control" placeholder="Contoh: 081234567890">
                                    </div>
                                </div>

                                <div class="d-flex justify-content-end gap-2 pt-3 border-top">
                                    <a href="{{ route('siswa.tampil') }}" class="btn btn-secondary px-4">Batal</a>
                                    <button type="submit" class="btn btn-primary px-4 fw-semibold"><i class="bi bi-check-lg me-1"></i>Simpan Data Siswa</button>
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
    <script>
        const classesData = @json($datakelas);
        function filterClasses() {
            const grade = document.getElementById('grade').value;
            const classSelect = document.getElementById('class_id');
            
            // Kosongkan opsi sebelumnya
            classSelect.innerHTML = '<option value="" disabled selected>-- Pilih Kelas --</option>';
            
            // Filter dan tambahkan opsi sesuai tingkat kelas
            classesData.forEach(function(item) {
                if (item.grade == grade) {
                    const opt = document.createElement('option');
                    opt.value = item.id;
                    opt.textContent = item.school_class_name + ' (' + item.school_class_major + ')';
                    classSelect.appendChild(opt);
                }
            });
        }
    </script>
</body>

</html>
