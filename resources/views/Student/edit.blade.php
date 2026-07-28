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
            <h1>Edit Data Siswa</h1>
            <nav>
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Beranda</a></li>
                    <li class="breadcrumb-item"><a href="{{ route('siswa.tampil') }}">Data Siswa</a></li>
                    <li class="breadcrumb-item active">Edit Siswa</li>
                </ol>
            </nav>
        </div>

        <section class="section">
            <div class="row justify-content-center">
                <div class="col-lg-10">
                    <div class="card border-0 shadow-sm rounded-3">
                        <div class="card-body p-4">
                            <div class="d-flex align-items-center justify-content-between pb-3 mb-4 border-bottom">
                                <h5 class="card-title fw-bold text-dark p-0 m-0">Formulir Perubahan Data Siswa</h5>
                                <a href="{{ route('siswa.tampil') }}" class="btn btn-outline-secondary btn-sm">
                                    <i class="bi bi-arrow-left me-1"></i>Kembali
                                </a>
                            </div>

                            <form action="{{ url('/update/' . $datasiswa->id) }}" method="POST">
                                @csrf

                                <div class="row g-3 mb-3">
                                    <div class="col-md-6">
                                        <label for="full_name" class="form-label fw-semibold">Nama Lengkap Siswa <span class="text-danger">*</span></label>
                                        <input type="text" id="full_name" name="full_name" class="form-control" value="{{ old('full_name', $datasiswa->full_name) }}" required>
                                    </div>
                                    <div class="col-md-6">
                                        <label for="nis" class="form-label fw-semibold">Nomor Induk Siswa (NIS) <span class="text-danger">*</span></label>
                                        <input type="text" id="nis" name="nis" class="form-control" value="{{ old('nis', $datasiswa->nis) }}" required>
                                    </div>
                                </div>

                                <div class="row g-3 mb-3">
                                    <div class="col-md-3">
                                        <label for="grade" class="form-label fw-semibold">Tingkat Kelas <span class="text-danger">*</span></label>
                                        <select class="form-select" id="grade" onchange="filterClasses()" required>
                                            <option value="" disabled>-- Pilih Tingkat --</option>
                                            <option value="10" {{ ($datasiswa->class?->grade == '10') ? 'selected' : '' }}>Kelas 10 (X)</option>
                                            <option value="11" {{ ($datasiswa->class?->grade == '11') ? 'selected' : '' }}>Kelas 11 (XI)</option>
                                            <option value="12" {{ ($datasiswa->class?->grade == '12') ? 'selected' : '' }}>Kelas 12 (XII)</option>
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
                                            <option value="L" {{ $datasiswa->gender == 'L' ? 'selected' : '' }}>Laki-laki</option>
                                            <option value="P" {{ $datasiswa->gender == 'P' ? 'selected' : '' }}>Perempuan</option>
                                        </select>
                                    </div>
                                </div>

                                <div class="row g-3 mb-3">
                                    <div class="col-md-6">
                                        <label for="date_of_birth" class="form-label fw-semibold">Tanggal Lahir <span class="text-danger">*</span></label>
                                        <input type="date" id="date_of_birth" name="date_of_birth" class="form-control" value="{{ old('date_of_birth', $datasiswa->date_of_birth) }}" required>
                                    </div>
                                    <div class="col-md-6">
                                        <label for="phone_number" class="form-label fw-semibold">Nomor Telepon / WA Siswa</label>
                                        <input type="text" id="phone_number" name="phone_number" class="form-control" value="{{ old('phone_number', $datasiswa->phone_number) }}">
                                    </div>
                                </div>

                                <div class="mb-3">
                                    <label for="email" class="form-label fw-semibold">Email Akun Login Siswa</label>
                                    <input type="email" id="email" name="email" class="form-control" value="{{ old('email', $datasiswa->user?->email) }}" placeholder="siswa@school.sch.id">
                                </div>

                                <div class="mb-4">
                                    <label for="address" class="form-label fw-semibold">Alamat Rumah</label>
                                    <textarea id="address" name="address" class="form-control" rows="2">{{ old('address', $datasiswa->address) }}</textarea>
                                </div>

                                <div class="row g-3 mb-4 p-3 bg-light rounded border">
                                    <div class="col-12 mb-2">
                                        <h6 class="fw-bold text-dark m-0"><i class="bi bi-person-hearts me-2"></i>Informasi Orang Tua / Wali</h6>
                                        <small class="text-muted">Data orang tua siswa.</small>
                                    </div>
                                    <div class="col-md-4">
                                        <label for="parent_full_name" class="form-label fw-semibold">Nama Orang Tua/Wali</label>
                                        <input type="text" name="parent_full_name" id="parent_full_name" class="form-control" placeholder="Nama Lengkap" value="{{ old('parent_full_name', $datasiswa->parent?->parent_full_name) }}">
                                    </div>
                                    <div class="col-md-4">
                                        <label for="parent_relationship" class="form-label fw-semibold">Hubungan</label>
                                        <select class="form-select" name="parent_relationship" id="parent_relationship">
                                            <option value="ayah" {{ ($datasiswa->parent?->relationship == 'ayah') ? 'selected' : '' }}>Ayah</option>
                                            <option value="ibu" {{ ($datasiswa->parent?->relationship == 'ibu') ? 'selected' : '' }}>Ibu</option>
                                            <option value="wali" {{ ($datasiswa->parent?->relationship == 'wali') ? 'selected' : '' }}>Wali</option>
                                        </select>
                                    </div>
                                    <div class="col-md-4">
                                        <label for="parent_phone_number" class="form-label fw-semibold">No. Telepon / WA</label>
                                        <input type="text" name="parent_phone_number" id="parent_phone_number" class="form-control" placeholder="Contoh: 081234567890" value="{{ old('parent_phone_number', $datasiswa->parent?->phone_number) }}">
                                    </div>
                                </div>

                                <div class="d-flex justify-content-end gap-2 pt-3 border-top">
                                    <a href="{{ route('siswa.tampil') }}" class="btn btn-secondary px-4">Batal</a>
                                    <button type="submit" class="btn btn-primary px-4 fw-semibold"><i class="bi bi-check-lg me-1"></i>Simpan Perubahan</button>
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
        const currentClassId = "{{ $datasiswa->class_id }}";

        function filterClasses() {
            const grade = document.getElementById('grade').value;
            const classSelect = document.getElementById('class_id');
            
            // Kosongkan opsi sebelumnya
            classSelect.innerHTML = '<option value="" disabled>-- Pilih Kelas --</option>';
            
            // Filter dan tambahkan opsi sesuai tingkat kelas
            classesData.forEach(function(item) {
                if (item.grade == grade) {
                    const opt = document.createElement('option');
                    opt.value = item.id;
                    opt.textContent = item.school_class_name + ' (' + item.school_class_major + ')';
                    
                    if (item.id == currentClassId) {
                        opt.selected = true;
                    }
                    
                    classSelect.appendChild(opt);
                }
            });
        }
        
        // Panggil fungsi saat halaman pertama kali dimuat
        document.addEventListener('DOMContentLoaded', filterClasses);
        // Dukungan untuk Turbo Drive
        document.addEventListener('turbo:load', filterClasses);
        
        // Juga panggil sekali jika script dirender via ajax (tanpa reload penuh)
        if(document.getElementById('grade').value) {
            filterClasses();
        }
    </script>
</body>

</html>
