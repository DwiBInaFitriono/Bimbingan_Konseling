<!DOCTYPE html>
<html lang="en">

<head>
    @include('include.style')
    <style>
        .modal-header-custom {
            background: linear-gradient(135deg, #4154f1 0%, #7c3aed 100%);
            color: #ffffff;
            border-top-left-radius: 18px;
            border-top-right-radius: 18px;
            padding: 20px 28px;
            border-bottom: none;
            position: relative;
        }
        .modal-header-custom::after {
            content: '';
            position: absolute;
            bottom: -1px;
            left: 0;
            right: 0;
            height: 12px;
            background: #fff;
            border-radius: 12px 12px 0 0;
        }
        .kelas-modal .modal-content {
            border: none;
            border-radius: 18px;
            overflow: hidden;
            box-shadow: 0 25px 60px rgba(33, 51, 99, 0.25);
        }
        .kelas-modal .form-control,
        .kelas-modal .form-select {
            border-radius: 8px;
            border: 1px solid #ced4da;
            padding: 10px 14px;
            font-size: 0.9rem;
            color: #495057;
            transition: all 0.2s ease;
        }
        .kelas-modal .form-control:focus,
        .kelas-modal .form-select:focus {
            border-color: #4154f1;
            box-shadow: 0 0 0 0.25rem rgba(65, 84, 241, 0.15);
        }
        .kelas-modal .form-label {
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
            margin: 14px 0 10px;
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
        .kelas-modal .modal-body > .form-section-label:first-child {
            margin-top: 0;
        }
    </style>
</head>

<body>
    @include('include.header')
    @include('include.sidebar')

    <main id="main" class="main">
        <div class="pagetitle">
            <h1>Kelola Data Kelas SMK</h1>
            <nav>
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Beranda</a></li>
                    <li class="breadcrumb-item active">Data Kelas</li>
                </ol>
            </nav>
        </div>


        <div class="col-12">
            <div class="card border-0 shadow-sm rounded-3">
                <div class="card-body pt-3">
                    <div class="d-flex justify-content-between align-items-center mb-3 pb-2 border-bottom">
                        <h5 class="card-title fw-bold text-dark m-0 p-0">Daftar Rombongan Belajar / Kelas</h5>
                        <button type="button" class="btn btn-primary px-3 py-2 rounded-2 fw-semibold shadow-sm" data-bs-toggle="modal" data-bs-target="#modalTambahKelas">
                            <i class="bi bi-plus-circle me-1"></i>Tambah Kelas Baru
                        </button>
                    </div>

                    {{-- Search & Filter Bar --}}
                    <div class="row g-2 mb-3 align-items-center">
                        <div class="col-md-3 col-sm-6">
                            <div class="dropdown w-100">
                                <button class="form-select text-start text-secondary fw-semibold w-100" type="button" id="filterTingkatBtn" data-bs-toggle="dropdown" aria-expanded="false">
                                    <span>Tingkat: Semua</span>
                                </button>
                                <ul class="dropdown-menu w-100 shadow-sm" aria-labelledby="filterTingkatBtn">
                                    <li><a class="dropdown-item fw-semibold py-2" href="javascript:void(0)" onclick="selectFilterTingkat('', 'Tingkat: Semua')">Semua Tingkat</a></li>
                                    <li><hr class="dropdown-divider"></li>
                                    <li><a class="dropdown-item py-2" href="javascript:void(0)" onclick="selectFilterTingkat('10', 'Kelas 10 (X)')">Kelas 10 (X)</a></li>
                                    <li><a class="dropdown-item py-2" href="javascript:void(0)" onclick="selectFilterTingkat('11', 'Kelas 11 (XI)')">Kelas 11 (XI)</a></li>
                                    <li><a class="dropdown-item py-2" href="javascript:void(0)" onclick="selectFilterTingkat('12', 'Kelas 12 (XII)')">Kelas 12 (XII)</a></li>
                                </ul>
                                <input type="hidden" id="filterTingkat" value="">
                            </div>
                        </div>
                        <div class="col-md-5 col-sm-6">
                            <div class="input-group">
                                <span class="input-group-text bg-white border-end-0"><i class="bi bi-search text-muted"></i></span>
                                <input type="text" id="searchKelas" class="form-control border-start-0 ps-0" placeholder="Cari nama kelas atau tingkat...">
                            </div>
                        </div>
                    </div>

                    <div class="table-responsive">
                        <table class="table table-hover align-middle" id="tabelKelas">
                            <thead class="table-light">
                                <tr>
                                    <th>No</th>
                                    <th>Kelas & Tingkat</th>
                                    <th>Jurusan & Siswa</th>
                                    <th class="text-center text-nowrap">Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse ($datakelas as $kelas)
                                    <tr class="kelas-row" data-tingkat="{{ $kelas->grade }}">
                                        <td>{{ $loop->iteration }}</td>
                                        <td>
                                            <div class="fw-bold text-dark fs-6">{{ $kelas->school_class_name }}</div>
                                            <div class="mt-1">
                                                @php
                                                    $major = strtolower($kelas->school_class_major);
                                                    $bgClass = 'bg-primary';
                                                    $textClass = 'text-primary';
                                                    $borderClass = 'border-primary';
                                                    $customStyle = '';
                                                    
                                                    if (str_contains($major, 'rpl') || str_contains($major, 'rekayasa perangkat lunak')) {
                                                        $bgClass = 'bg-danger';
                                                        $textClass = 'text-danger';
                                                        $borderClass = 'border-danger';
                                                    } elseif (str_contains($major, 'dkv') || str_contains($major, 'desain komunikasi visual')) {
                                                        $bgClass = 'bg-dark';
                                                        $textClass = 'text-dark';
                                                        $borderClass = 'border-dark';
                                                    } elseif (str_contains($major, 'mp') || str_contains($major, 'manajemen perkantoran')) {
                                                        $bgClass = 'bg-secondary';
                                                        $textClass = 'text-secondary';
                                                        $borderClass = 'border-secondary';
                                                    } elseif (str_contains($major, 'aku') || str_contains($major, 'akuntansi')) {
                                                        $bgClass = 'bg-success';
                                                        $textClass = 'text-success';
                                                        $borderClass = 'border-success';
                                                    } elseif (str_contains($major, 'bd') || str_contains($major, 'bisnis digital')) {
                                                        $bgClass = 'bg-warning';
                                                        $textClass = 'text-warning text-dark';
                                                        $borderClass = 'border-warning';
                                                    } elseif (str_contains($major, 'kkbt') || str_contains($major, 'busana') || str_contains($major, 'kecantikan')) {
                                                        $bgClass = '';
                                                        $customStyle = 'background-color: rgba(139, 69, 19, 0.1); color: #8B4513; border: 1px solid rgba(139, 69, 19, 0.3);';
                                                    }
                                                    
                                                    $gradeRomawi = $kelas->grade == '10' ? 'X' : ($kelas->grade == '11' ? 'XI' : 'XII');
                                                @endphp
                                                
                                                @if($customStyle)
                                                    <span class="badge px-2 py-1" style="{{ $customStyle }}">Kelas {{ $kelas->grade }} ({{ $gradeRomawi }})</span>
                                                @else
                                                    <span class="badge {{ $bgClass }} bg-opacity-10 {{ $textClass }} border {{ $borderClass }} border-opacity-25 px-2 py-1">Kelas {{ $kelas->grade }} ({{ $gradeRomawi }})</span>
                                                @endif
                                            </div>
                                        </td>
                                        <td>
                                            <div class="text-dark fw-semibold mb-1 text-truncate" style="max-width: 250px;" title="{{ $kelas->school_class_major }}">{{ $kelas->school_class_major }}</div>
                                            <span class="badge bg-secondary bg-opacity-10 text-secondary border px-2 py-1">
                                                <i class="bi bi-people me-1"></i>{{ $kelas->student_count ?? 0 }} Siswa
                                            </span>
                                        </td>
                                        <td class="text-center">
                                            <div class="dropdown d-inline-block">
                                                <button class="btn btn-sm btn-outline-secondary dropdown-toggle px-3" type="button" data-bs-toggle="dropdown" data-bs-boundary="viewport" data-bs-popper-config='{"strategy":"fixed"}' aria-expanded="false">
                                                    <i class="bi bi-gear me-1"></i> Aksi
                                                </button>
                                                <ul class="dropdown-menu dropdown-menu-end shadow-sm" style="max-height: 250px; overflow-y: auto;">
                                                    <li>
                                                        <a class="dropdown-item d-flex align-items-center py-2 text-warning" href="javascript:void(0)" onclick="openEditModal({{ $kelas->id }}, '{{ $kelas->grade }}', '{{ addslashes($kelas->school_class_name) }}', '{{ addslashes($kelas->school_class_major) }}')">
                                                            <i class="bi bi-pencil me-2"></i> Edit
                                                        </a>
                                                    </li>
                                                    <li>
                                                        <form id="delete-form-kelas-{{ $kelas->id }}" action="{{ url('hapuskelas/' . $kelas->id) }}" method="POST" class="d-none">
                                                            @csrf
                                                            @method('DELETE')
                                                        </form>
                                                        <a class="dropdown-item d-flex align-items-center py-2 text-danger" href="javascript:void(0)" onclick="swalConfirm('Yakin ingin menghapus kelas ini?', function(){ document.getElementById('delete-form-kelas-{{ $kelas->id }}').submit(); })">
                                                            <i class="bi bi-trash me-2"></i> Hapus
                                                        </a>
                                                    </li>
                                                </ul>
                                            </div>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="6" class="text-center py-5 text-muted">
                                            <i class="bi bi-door-closed fs-2 d-block mb-2 opacity-25"></i>
                                            Belum ada data kelas terdaftar.
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                        <p id="noResultKelas" class="text-center text-muted py-3 d-none">Tidak ada data yang cocok dengan pencarian.</p>
                    </div>
                </div>
            </div>
        </div>
    </main>

    {{-- Modal Tambah Kelas --}}
    <div class="modal fade kelas-modal" id="modalTambahKelas" tabindex="-1" data-bs-backdrop="static" data-bs-keyboard="false">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <form action="{{ url('simpankelas') }}" method="POST">
                    @csrf
                    <div class="modal-header modal-header-custom">
                        <h5 class="modal-title fw-bold"><i class="bi bi-plus-circle me-2"></i>Tambah Kelas Baru</h5>
                        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" style="filter: brightness(0) invert(1); opacity: 0.8; position: relative; z-index: 2;"></button>
                    </div>
                    <div class="modal-body p-4">
                        <div class="form-section-label"><i class="bi bi-info-circle"></i>Detail Kelas</div>
                        <div class="mb-3">
                            <label class="form-label">Tingkat / Angkatan SMK <span class="text-danger">*</span></label>
                            <select name="grade" class="form-select" required>
                                <option value="10">Kelas 10 (X)</option>
                                <option value="11">Kelas 11 (XI)</option>
                                <option value="12">Kelas 12 (XII)</option>
                            </select>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Nama Rombongan Belajar (Kelas) <span class="text-danger">*</span></label>
                            <input type="text" name="school_class_name" class="form-control" placeholder="Contoh: X RPL 1 / XI MP 2" required>
                        </div>
                        <div class="mb-4">
                            <label class="form-label">Nama Jurusan / Kompetensi Keahlian <span class="text-danger">*</span></label>
                            <input type="text" name="school_class_major" class="form-control" placeholder="Contoh: Rekayasa Perangkat Lunak" required>
                        </div>
                    </div>
                    <div class="modal-footer border-0 px-4 pb-4">
                        <button type="button" class="btn btn-secondary px-4 shadow-sm" style="border-radius:10px;" data-bs-dismiss="modal"><i class="bi bi-x-lg me-1" style="font-size:0.8rem;"></i> Batal</button>
                        <button type="submit" class="btn btn-primary px-4 shadow-sm" style="border-radius:10px; background:#4154f1; border-color:#4154f1;"><i class="bi bi-send me-2"></i>Simpan</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    {{-- Modal Edit Kelas --}}
    <div class="modal fade kelas-modal" id="modalEditKelas" tabindex="-1" data-bs-backdrop="static" data-bs-keyboard="false">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <form id="formEditKelas" method="POST">
                    @csrf
                    <div class="modal-header modal-header-custom">
                        <h5 class="modal-title fw-bold"><i class="bi bi-pencil-square me-2"></i>Edit Data Kelas</h5>
                        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" style="filter: brightness(0) invert(1); opacity: 0.8; position: relative; z-index: 2;"></button>
                    </div>
                    <div class="modal-body p-4">
                        <div class="form-section-label"><i class="bi bi-info-circle"></i>Detail Kelas</div>
                        <div class="mb-3">
                            <label class="form-label">Tingkat / Angkatan SMK <span class="text-danger">*</span></label>
                            <select name="grade" id="editGrade" class="form-select" required>
                                <option value="10">Kelas 10 (X)</option>
                                <option value="11">Kelas 11 (XI)</option>
                                <option value="12">Kelas 12 (XII)</option>
                            </select>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Nama Rombongan Belajar (Kelas) <span class="text-danger">*</span></label>
                            <input type="text" name="school_class_name" id="editClassName" class="form-control" required>
                        </div>
                        <div class="mb-4">
                            <label class="form-label">Nama Jurusan / Kompetensi Keahlian <span class="text-danger">*</span></label>
                            <input type="text" name="school_class_major" id="editClassMajor" class="form-control" required>
                        </div>
                    </div>
                    <div class="modal-footer border-0 px-4 pb-4">
                        <button type="button" class="btn btn-secondary px-4 shadow-sm" style="border-radius:10px;" data-bs-dismiss="modal"><i class="bi bi-x-lg me-1" style="font-size:0.8rem;"></i> Batal</button>
                        <button type="submit" class="btn btn-primary px-4 shadow-sm" style="border-radius:10px; background:#4154f1; border-color:#4154f1;"><i class="bi bi-send me-2"></i>Simpan Perubahan</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    @include('include.footer')
    <a href="#" class="back-to-top d-flex align-items-center justify-content-center"><i class="bi bi-arrow-up-short"></i></a>
    @include('include.script')

    <script>
        function openEditModal(id, grade, name, major) {
            document.getElementById('formEditKelas').action = '/updatekelas/' + id;
            document.getElementById('editGrade').value = grade;
            document.getElementById('editClassName').value = name;
            document.getElementById('editClassMajor').value = major;
            new bootstrap.Modal(document.getElementById('modalEditKelas')).show();
        }
    </script>
</body>

</html>
