<!DOCTYPE html>
<html lang="en">

<head>
    @include('include.style')
    <style>
        .card-custom {
            border: none;
            border-radius: 18px;
            overflow: hidden;
            box-shadow: 0 25px 60px rgba(33, 51, 99, 0.25);
        }
        .card-header-custom {
            background: linear-gradient(135deg, #4154f1 0%, #7c3aed 100%);
            color: white;
            border-top-left-radius: 18px;
            border-top-right-radius: 18px;
            padding: 20px 28px;
            border-bottom: none;
            position: relative;
        }
        .card-header-custom::after {
            content: '';
            position: absolute;
            bottom: -1px;
            left: 0;
            right: 0;
            height: 20px;
            background: #fff;
            border-radius: 20px 20px 0 0;
        }
        .form-control, .form-select {
            border-radius: 8px;
            border: 1px solid #ced4da;
            padding: 10px 14px;
            font-size: 0.9rem;
            color: #495057;
            transition: all 0.2s ease;
        }
        .form-control:focus, .form-select:focus {
            border-color: #4154f1;
            box-shadow: 0 0 0 0.25rem rgba(65, 84, 241, 0.15);
        }
        .form-label {
            font-size: 0.88rem;
            font-weight: 600;
            color: #2c3e50;
        }
        .form-section-label {
            font-size: 0.88rem;
            font-weight: 800;
            letter-spacing: 0.3px;
            text-transform: uppercase;
            color: #2c3e50;
            margin: 18px 0 10px;
            display: flex;
            align-items: center;
            gap: 8px;
        }
        .form-section-label::after {
            content: '';
            flex: 1;
            height: 1px;
            background: #eef1f8;
        }
    </style>
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
            <div class="row">
                <div class="col-12">
                    <div class="card card-custom">
                        <div class="card-header-custom d-flex align-items-center justify-content-between">
                            <h5 class="m-0 fw-bold">
                                <i class="bi bi-pencil-square me-2"></i>Formulir Perubahan Data Siswa
                            </h5>
                            <a href="{{ route('siswa.tampil') }}" class="btn btn-light btn-sm rounded-pill px-3 fw-bold shadow-sm" style="color: #4154f1; position: relative; z-index: 2;">
                                <i class="bi bi-arrow-left me-1"></i>Kembali
                            </a>
                        </div>
                        <div class="card-body p-4 p-md-5 pt-4">
                            <form action="{{ url('/update/' . $datasiswa->id) }}" method="POST">
                                @csrf
                                
                                <div class="form-section-label"><i class="bi bi-person-badge"></i>Identitas Utama</div>
                                <div class="row g-3 mb-3">
                                    <div class="col-md-6">
                                        <label for="full_name" class="form-label">Nama Lengkap Siswa <span class="text-danger">*</span></label>
                                        <input type="text" id="full_name" name="full_name" class="form-control" value="{{ old('full_name', $datasiswa->full_name) }}" required>
                                    </div>
                                    <div class="col-md-6">
                                        <label for="nis" class="form-label">Nomor Induk Siswa (NIS) <span class="text-danger">*</span></label>
                                        <input type="text" id="nis" name="nis" class="form-control" value="{{ old('nis', $datasiswa->nis) }}" required>
                                    </div>
                                </div>

                                <div class="row g-3 mb-3">
                                    <div class="col-md-3">
                                        <label for="grade" class="form-label">Tingkat Kelas <span class="text-danger">*</span></label>
                                        <select class="form-select" id="grade" onchange="filterClasses()" required>
                                            <option value="" disabled>-- Pilih Tingkat --</option>
                                            <option value="10" {{ ($datasiswa->class?->grade == '10') ? 'selected' : '' }}>Kelas 10 (X)</option>
                                            <option value="11" {{ ($datasiswa->class?->grade == '11') ? 'selected' : '' }}>Kelas 11 (XI)</option>
                                            <option value="12" {{ ($datasiswa->class?->grade == '12') ? 'selected' : '' }}>Kelas 12 (XII)</option>
                                        </select>
                                    </div>
                                    <div class="col-md-3">
                                        <label for="class_id" class="form-label">Rombel Kelas <span class="text-danger">*</span></label>
                                        <select class="form-select" name="class_id" id="class_id" required>
                                            <option value="" disabled selected>-- Pilih Tingkat Dulu --</option>
                                        </select>
                                    </div>

                                    <div class="col-md-6">
                                        <label for="gender" class="form-label">Jenis Kelamin <span class="text-danger">*</span></label>
                                        <select class="form-select" name="gender" id="gender" required>
                                            <option value="L" {{ $datasiswa->gender == 'L' ? 'selected' : '' }}>Laki-laki</option>
                                            <option value="P" {{ $datasiswa->gender == 'P' ? 'selected' : '' }}>Perempuan</option>
                                        </select>
                                    </div>
                                </div>

                                <div class="form-section-label"><i class="bi bi-envelope"></i>Informasi Kontak</div>
                                <div class="row g-3 mb-3">
                                    <div class="col-md-6">
                                        <label for="date_of_birth" class="form-label">Tanggal Lahir <span class="text-danger">*</span></label>
                                        <input type="date" id="date_of_birth" name="date_of_birth" class="form-control" value="{{ old('date_of_birth', $datasiswa->date_of_birth) }}" required>
                                    </div>
                                    <div class="col-md-6">
                                        <label for="phone_number" class="form-label">Nomor Telepon / WA Siswa</label>
                                        <input type="text" id="phone_number" name="phone_number" class="form-control" value="{{ old('phone_number', $datasiswa->phone_number) }}">
                                    </div>
                                </div>

                                <div class="mb-3">
                                    <label for="email" class="form-label">Email Akun Login Siswa</label>
                                    <input type="email" id="email" name="email" class="form-control" value="{{ old('email', $datasiswa->user?->email) }}" placeholder="siswa@school.sch.id">
                                </div>

                                <div class="mb-4">
                                    <label for="address" class="form-label">Alamat Rumah</label>
                                    <textarea id="address" name="address" class="form-control" rows="2">{{ old('address', $datasiswa->address) }}</textarea>
                                </div>

                                <div class="form-section-label"><i class="bi bi-person-hearts"></i>Informasi Orang Tua / Wali</div>
                                <div class="row g-3 mb-4 p-4 rounded-3" style="background-color: #f8f9fa; border: 1px solid #eef1f8;">
                                    <div class="col-12 mb-2">
                                        <small class="text-muted">Data orang tua siswa.</small>
                                    </div>
                                    <div class="col-md-4">
                                        <label for="parent_full_name" class="form-label">Nama Orang Tua/Wali</label>
                                        <input type="text" name="parent_full_name" id="parent_full_name" class="form-control" placeholder="Nama Lengkap" value="{{ old('parent_full_name', $datasiswa->parent?->parent_full_name) }}">
                                    </div>
                                    <div class="col-md-4">
                                        <label for="parent_relationship" class="form-label">Hubungan</label>
                                        <select class="form-select" name="parent_relationship" id="parent_relationship">
                                            <option value="ayah" {{ ($datasiswa->parent?->relationship == 'ayah') ? 'selected' : '' }}>Ayah</option>
                                            <option value="ibu" {{ ($datasiswa->parent?->relationship == 'ibu') ? 'selected' : '' }}>Ibu</option>
                                            <option value="wali" {{ ($datasiswa->parent?->relationship == 'wali') ? 'selected' : '' }}>Wali</option>
                                        </select>
                                    </div>
                                    <div class="col-md-4">
                                        <label for="parent_phone_number" class="form-label">No. Telepon / WA</label>
                                        <input type="text" name="parent_phone_number" id="parent_phone_number" class="form-control" value="{{ old('parent_phone_number', $datasiswa->parent?->parent_phone_number) }}">
                                    </div>
                                </div>

                                <div class="d-flex justify-content-end gap-2 pt-4 mt-2" style="border-top: 1px solid #eef1f8;">
                                    <a href="{{ route('siswa.tampil') }}" class="btn btn-secondary px-4 shadow-sm" style="border-radius:10px;"><i class="bi bi-x-lg me-1" style="font-size: 0.8rem;"></i> Batal</a>
                                    <button type="submit" class="btn btn-primary px-4 shadow-sm" style="border-radius:10px; background: #4154f1; border-color: #4154f1;"><i class="bi bi-send me-2"></i>Simpan Perubahan</button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </section>
    </main>

    @include('include.footer')
    @include('include.script')
    <script>
        const classesData = @json($datakelas);
        const currentClassId = "{{ $datasiswa->class_id }}";

        function filterClasses() {
            const grade = document.getElementById('grade').value;
            const classSelect = document.getElementById('class_id');
            
            let choicesData = [{value: '', label: '-- Pilih Kelas --', selected: !currentClassId, disabled: true}];
            let html = '<option value="" disabled>-- Pilih Kelas --</option>';
            
            classesData.forEach(function(item) {
                if (item.grade == grade) {
                    const isSelected = (item.id == currentClassId) ? true : false;
                    choicesData.push({value: item.id, label: `${item.school_class_name} (${item.school_class_major})`, selected: isSelected});
                    
                    const selectedAttr = isSelected ? 'selected' : '';
                    html += `<option value="${item.id}" ${selectedAttr}>${item.school_class_name} (${item.school_class_major})</option>`;
                }
            });
            
            if (classSelect.choicesObj) {
                classSelect.choicesObj.clearChoices();
                classSelect.choicesObj.setChoices(choicesData, 'value', 'label', true);
            } else {
                classSelect.innerHTML = html;
            }
        }
        
        // Panggil fungsi saat halaman pertama kali dimuat
        document.addEventListener('DOMContentLoaded', filterClasses);
        
        // Juga panggil sekali jika script dirender via ajax (tanpa reload penuh)
        if(document.getElementById('grade').value) {
            filterClasses();
        }
    </script>
</body>

</html>
