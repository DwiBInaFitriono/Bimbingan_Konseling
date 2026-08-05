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
        .siswa-modal .modal-body > .form-section-label:first-child {
            margin-top: 0;
        }
        .siswa-modal .modal-content {
            border: none;
            border-radius: 18px;
            overflow: hidden;
            box-shadow: 0 25px 60px rgba(33, 51, 99, 0.25);
        }
        .siswa-modal .form-control,
        .siswa-modal .form-select {
            border-radius: 8px;
            border: 1px solid #ced4da;
            padding: 10px 14px;
            font-size: 0.9rem;
            color: #495057;
            transition: all 0.2s ease;
        }
        .siswa-modal .form-control:focus,
        .siswa-modal .form-select:focus {
            border-color: #4154f1;
            box-shadow: 0 0 0 0.25rem rgba(65, 84, 241, 0.15);
        }
        .siswa-modal .form-label {
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
    </style>
</head>

<body>
    @include('include.header')
    @include('include.sidebar')

    <main id="main" class="main">
        <div class="pagetitle">
            <h1>Data Siswa</h1>
            <nav>
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Beranda</a></li>
                    <li class="breadcrumb-item active">Data Siswa</li>
                </ol>
            </nav>
        </div>

        <div class="col-12">
            <div class="card border-0 shadow-sm rounded-3">
                <div class="card-body pt-3">
                    <div class="d-flex justify-content-between align-items-center mb-3 pb-2 border-bottom">
                        <h5 class="card-title fw-bold text-dark m-0 p-0">Daftar Seluruh Siswa Terdaftar</h5>
                        <button type="button" class="btn btn-primary px-3 py-2 rounded-2 fw-semibold shadow-sm" data-bs-toggle="modal" data-bs-target="#modalTambahSiswa">
                            <i class="bi bi-person-plus-fill me-1"></i>Tambah Siswa Baru
                        </button>
                    </div>

                    {{-- Filter & Search Bar --}}
                    <div class="row mb-3 g-2">
                        {{-- Dropdown Tingkat --}}
                        <div class="col-md-3 col-sm-6">
                            <div class="dropdown w-100">
                                <button class="form-select text-start text-secondary fw-semibold w-100" type="button" id="dropdownTingkatBtn" data-bs-toggle="dropdown" aria-expanded="false">
                                    <span>Tingkat: Semua</span>
                                </button>
                                <ul class="dropdown-menu w-100 shadow-sm" aria-labelledby="dropdownTingkatBtn">
                                    <li><a class="dropdown-item fw-semibold py-2" href="javascript:void(0)" onclick="selectTingkatFilter('', 'Tingkat: Semua')">Semua Tingkat</a></li>
                                    <li><hr class="dropdown-divider"></li>
                                    <li><a class="dropdown-item py-2" href="javascript:void(0)" onclick="selectTingkatFilter('kelas 10', 'Kelas 10 (X)')">Kelas 10 (X)</a></li>
                                    <li><a class="dropdown-item py-2" href="javascript:void(0)" onclick="selectTingkatFilter('kelas 11', 'Kelas 11 (XI)')">Kelas 11 (XI)</a></li>
                                    <li><a class="dropdown-item py-2" href="javascript:void(0)" onclick="selectTingkatFilter('kelas 12', 'Kelas 12 (XII)')">Kelas 12 (XII)</a></li>
                                </ul>
                                <input type="hidden" id="filterTingkat" value="">
                            </div>
                        </div>

                        {{-- Dropdown Jurusan & Rombel --}}
                        <div class="col-md-3 col-sm-6">
                            <div class="dropdown w-100">
                                <button class="form-select text-start text-secondary fw-semibold w-100" type="button" id="dropdownJurusanBtn" data-bs-toggle="dropdown" aria-expanded="false">
                                    <span>Jurusan: Semua</span>
                                </button>
                                <ul class="dropdown-menu w-100 shadow-sm" aria-labelledby="dropdownJurusanBtn" style="max-height: 250px; overflow-y: auto; border: 1px solid #ced4da;">
                                    <li><a class="dropdown-item fw-semibold py-2" href="javascript:void(0)" onclick="selectJurusanFilter('', 'Semua Jurusan')">Semua Jurusan & Rombel</a></li>
                                    <li><hr class="dropdown-divider"></li>
                                    
                                    <li><h6 class="dropdown-header fw-bold text-primary pt-2 pb-1">Rekayasa Perangkat Lunak</h6></li>
                                    <li><a class="dropdown-item py-1 ps-4" href="javascript:void(0)" onclick="selectJurusanFilter('rpl 1', 'RPL 1')">RPL 1</a></li>
                                    <li><a class="dropdown-item py-1 ps-4" href="javascript:void(0)" onclick="selectJurusanFilter('rpl 2', 'RPL 2')">RPL 2</a></li>
                                    <li><a class="dropdown-item py-1 ps-4" href="javascript:void(0)" onclick="selectJurusanFilter('rpl 3', 'RPL 3')">RPL 3</a></li>
                                    
                                    <li><h6 class="dropdown-header fw-bold text-primary pt-2 pb-1">Manajemen Perkantoran</h6></li>
                                    <li><a class="dropdown-item py-1 ps-4" href="javascript:void(0)" onclick="selectJurusanFilter('mp 1', 'MP 1')">MP 1</a></li>
                                    <li><a class="dropdown-item py-1 ps-4" href="javascript:void(0)" onclick="selectJurusanFilter('mp 2', 'MP 2')">MP 2</a></li>
                                    <li><a class="dropdown-item py-1 ps-4" href="javascript:void(0)" onclick="selectJurusanFilter('mp 3', 'MP 3')">MP 3</a></li>

                                    <li><h6 class="dropdown-header fw-bold text-primary pt-2 pb-1">Akuntansi</h6></li>
                                    <li><a class="dropdown-item py-1 ps-4" href="javascript:void(0)" onclick="selectJurusanFilter('ak 1', 'AK 1')">AK 1</a></li>
                                    <li><a class="dropdown-item py-1 ps-4" href="javascript:void(0)" onclick="selectJurusanFilter('ak 2', 'AK 2')">AK 2</a></li>
                                    <li><a class="dropdown-item py-1 ps-4" href="javascript:void(0)" onclick="selectJurusanFilter('ak 3', 'AK 3')">AK 3</a></li>

                                    <li><h6 class="dropdown-header fw-bold text-primary pt-2 pb-1">Bisnis Digital</h6></li>
                                    <li><a class="dropdown-item py-1 ps-4" href="javascript:void(0)" onclick="selectJurusanFilter('bd 1', 'BD 1')">BD 1</a></li>
                                    <li><a class="dropdown-item py-1 ps-4" href="javascript:void(0)" onclick="selectJurusanFilter('bd 2', 'BD 2')">BD 2</a></li>
                                    <li><a class="dropdown-item py-1 ps-4" href="javascript:void(0)" onclick="selectJurusanFilter('bd 3', 'BD 3')">BD 3</a></li>

                                    <li><h6 class="dropdown-header fw-bold text-primary pt-2 pb-1">Desain Komunikasi Visual</h6></li>
                                    <li><a class="dropdown-item py-1 ps-4" href="javascript:void(0)" onclick="selectJurusanFilter('dkv 1', 'DKV 1')">DKV 1</a></li>
                                    <li><a class="dropdown-item py-1 ps-4" href="javascript:void(0)" onclick="selectJurusanFilter('dkv 2', 'DKV 2')">DKV 2</a></li>

                                    <li><h6 class="dropdown-header fw-bold text-primary pt-2 pb-1">Kriya Kreatif Batik dan Tekstil</h6></li>
                                    <li><a class="dropdown-item py-1 ps-4" href="javascript:void(0)" onclick="selectJurusanFilter('kkbt 1', 'KKBT 1')">KKBT 1</a></li>
                                    <li><a class="dropdown-item py-1 ps-4" href="javascript:void(0)" onclick="selectJurusanFilter('kkbt 2', 'KKBT 2')">KKBT 2</a></li>
                                </ul>
                                <input type="hidden" id="filterJurusan" value="">
                            </div>
                        </div>

                        {{-- Search Input --}}
                        <div class="col-md-6 col-sm-12">
                            <div class="input-group">
                                <span class="input-group-text bg-white border-end-0"><i class="bi bi-search text-muted"></i></span>
                                <input type="text" id="searchSiswa" class="form-control border-start-0 ps-0" placeholder="Cari nama siswa, NIS, atau status...">
                            </div>
                        </div>
                    </div>

                    <div class="table-responsive">
                        <table class="table table-hover align-middle" id="tabelSiswa">
                            <thead class="table-light">
                                <tr>
                                    <th>No</th>
                                    <th>Siswa & Gender</th>
                                    <th>Kelas & Orang Tua</th>
                                    <th>Kontak & Akun</th>
                                    <th>Status BK</th>
                                    <th class="text-center text-nowrap">Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse ($datasiswa as $siswa)
                                    <tr>
                                        <td>{{ $loop->iteration }}</td>
                                        <td>
                                            <div class="fw-bold text-dark">{{ $siswa->full_name }}</div>
                                            <small class="text-muted"><i class="bi bi-person-badge me-1"></i>NIS: {{ $siswa->nis }}</small>
                                            @if (in_array(strtolower($siswa->gender), ['l', 'laki-laki']))
                                                <span class="badge bg-primary bg-opacity-10 text-primary border border-primary border-opacity-25 ms-1" title="Laki-laki"><i class="bi bi-gender-male"></i></span>
                                            @else
                                                <span class="badge bg-danger bg-opacity-10 text-danger border border-danger border-opacity-25 ms-1" title="Perempuan"><i class="bi bi-gender-female"></i></span>
                                            @endif
                                        </td>
                                        <td>
                                            <div class="mb-1">
                                                @php
                                                    $major = strtolower($siswa->class?->school_class_major ?? '');
                                                    $bgClass = 'bg-info';
                                                    $textClass = 'text-info';
                                                    $borderClass = 'border-info';
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
                                                @endphp
                                                @if($customStyle)
                                                    <span class="badge me-1" style="{{ $customStyle }}">Kelas {{ $siswa->class?->grade ?? '-' }}</span>
                                                @else
                                                    <span class="badge {{ $bgClass }} bg-opacity-10 {{ $textClass }} border {{ $borderClass }} border-opacity-25 me-1">Kelas {{ $siswa->class?->grade ?? '-' }}</span>
                                                @endif
                                                <strong class="text-dark">{{ $siswa->class?->school_class_name ?? 'Tanpa Kelas' }}</strong>
                                            </div>
                                            <small class="text-muted"><i class="bi bi-people me-1"></i>Wali: {{ $siswa->parent?->parent_full_name ?? '-' }}</small>
                                        </td>
                                        <td>
                                            @if($siswa->phone_number)
                                                <div class="mb-1"><small><i class="bi bi-whatsapp text-success me-1"></i>{{ $siswa->phone_number }}</small></div>
                                            @else
                                                <div class="mb-1 text-muted small"><i class="bi bi-telephone-x me-1"></i>-</div>
                                            @endif
                                            @if($siswa->user?->email)
                                                <small class="text-success"><i class="bi bi-check-circle me-1"></i>{{ $siswa->user->email }}</small>
                                            @else
                                                <small class="text-danger"><i class="bi bi-x-circle me-1"></i>Belum ada akun</small>
                                            @endif
                                        </td>
                                        <td>
                                            @if($siswa->status == 'aman')
                                                <span class="badge bg-success bg-opacity-10 text-success border border-success border-opacity-25"><i class="bi bi-shield-check me-1"></i>Aman ({{ $siswa->total_points }} Poin)</span>
                                            @elseif($siswa->status == 'peringatan')
                                                <span class="badge bg-warning bg-opacity-10 text-warning border border-warning border-opacity-25"><i class="bi bi-exclamation-triangle me-1"></i>Peringatan ({{ $siswa->total_points }} Poin)</span>
                                            @else
                                                <span class="badge bg-danger bg-opacity-10 text-danger border border-danger border-opacity-25"><i class="bi bi-x-octagon me-1"></i>Bahaya ({{ $siswa->total_points }} Poin)</span>
                                            @endif
                                        </td>
                                        <td class="text-center">
                                            <div class="dropdown d-inline-block">
                                                <button class="btn btn-sm btn-outline-secondary dropdown-toggle px-3" type="button" data-bs-toggle="dropdown" data-bs-boundary="viewport" data-bs-popper-config='{"strategy":"fixed"}' aria-expanded="false">
                                                    <i class="bi bi-gear me-1"></i> Aksi
                                                </button>
                                                <ul class="dropdown-menu dropdown-menu-end shadow-sm" style="max-height: 250px; overflow-y: auto;">
                                                    <li>
                                                        <a class="dropdown-item d-flex align-items-center py-2 text-primary" href="{{ route('siswa.cetak.peringatan', $siswa->id) }}" target="_blank">
                                                            <i class="bi bi-printer me-2"></i> Cetak Peringatan/SP
                                                        </a>
                                                    </li>
                                                    @if(strtolower($siswa->status) === 'bahaya')
                                                    <li>
                                                        <a class="dropdown-item d-flex align-items-center py-2 text-danger fw-semibold" href="{{ route('siswa.cetak.peringatan', $siswa->id) }}?type=expel" target="_blank">
                                                            <i class="bi bi-exclamation-octagon me-2"></i> Cetak SP Keluar
                                                        </a>
                                                    </li>
                                                    @endif
                                                    <li>
                                                        <a class="dropdown-item d-flex align-items-center py-2 text-warning" href="javascript:void(0)" onclick="openEditModal({{ $siswa->id }}, '{{ addslashes($siswa->full_name) }}', '{{ $siswa->nis }}', '{{ $siswa->class?->grade }}', '{{ $siswa->class_id }}', '{{ $siswa->gender }}', '{{ $siswa->date_of_birth }}', '{{ addslashes($siswa->phone_number) }}', '{{ addslashes($siswa->user?->email) }}', '{{ addslashes($siswa->address) }}', '{{ addslashes($siswa->parent?->parent_full_name) }}', '{{ $siswa->parent?->relationship }}', '{{ addslashes($siswa->parent?->parent_phone_number) }}')">
                                                            <i class="bi bi-pencil me-2"></i> Edit
                                                        </a>
                                                    </li>
                                                    <li>
                                                        <a class="dropdown-item d-flex align-items-center py-2 text-danger" href="{{ url('hapus/' . $siswa->id) }}" onclick="return confirm('Yakin ingin menghapus siswa ini?')">
                                                            <i class="bi bi-trash me-2"></i> Hapus
                                                        </a>
                                                    </li>
                                                </ul>
                                            </div>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="9" class="text-center py-5 text-muted">
                                            <i class="bi bi-people fs-2 d-block mb-2 opacity-25"></i>
                                            Belum ada data siswa terdaftar.
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                        <p id="noResultSiswa" class="text-center text-muted py-3 d-none">Tidak ada data yang cocok dengan pencarian.</p>
                    </div>
                </div>
            </div>
        </div>
    </main>

    {{-- Modal Tambah Siswa --}}
    <div class="modal fade siswa-modal" id="modalTambahSiswa" tabindex="-1" data-bs-backdrop="static" data-bs-keyboard="false">
        <div class="modal-dialog modal-dialog-centered modal-lg modal-dialog-scrollable">
            <div class="modal-content">
                <form action="{{ url('simpan') }}" method="POST">
                    @csrf
                    <div class="modal-header modal-header-custom">
                        <h5 class="modal-title fw-bold"><i class="bi bi-person-plus-fill me-2"></i>Tambah Siswa Baru</h5>
                        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" style="filter: brightness(0) invert(1); opacity: 0.8; position: relative; z-index: 2;"></button>
                    </div>
                    <div class="modal-body p-4" style="max-height: 70vh; overflow-y: auto;">
                        <div class="form-section-label"><i class="bi bi-person-badge"></i>Identitas Utama</div>
                        <div class="row g-3 mb-3">
                            <div class="col-md-6">
                                <label class="form-label">Nama Lengkap <span class="text-danger">*</span></label>
                                <input type="text" name="full_name" class="form-control" placeholder="Masukkan nama lengkap" required>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">NIS <span class="text-danger">*</span></label>
                                <input type="text" name="nis" class="form-control" placeholder="Contoh: 2024001" required>
                            </div>
                        </div>
                        <div class="row g-3 mb-3">
                            <div class="col-md-3">
                                <label class="form-label">Tingkat <span class="text-danger">*</span></label>
                                <select class="form-select" id="addGrade" onchange="filterAddClasses()" required>
                                    <option value="" disabled selected>-- Pilih --</option>
                                    <option value="10">Kelas 10</option>
                                    <option value="11">Kelas 11</option>
                                    <option value="12">Kelas 12</option>
                                </select>
                            </div>
                            <div class="col-md-3">
                                <label class="form-label">Rombel <span class="text-danger">*</span></label>
                                <select class="form-select" name="class_id" id="addClassId" required>
                                    <option value="" disabled selected>-- Pilih Tingkat Dulu --</option>
                                </select>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">Jenis Kelamin <span class="text-danger">*</span></label>
                                <select class="form-select" name="gender" required>
                                    <option value="L">Laki-laki</option>
                                    <option value="P">Perempuan</option>
                                </select>
                            </div>
                        </div>
                        <div class="form-section-label"><i class="bi bi-envelope"></i>Kontak & Akun</div>
                        <div class="row g-3 mb-3">
                            <div class="col-md-6">
                                <label class="form-label">Tanggal Lahir <span class="text-danger">*</span></label>
                                <input type="date" name="date_of_birth" class="form-control" required>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">No. Telepon / WA</label>
                                <input type="text" name="phone_number" class="form-control" placeholder="081234567890">
                            </div>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Email Akun Login</label>
                            <input type="email" name="email" class="form-control" placeholder="siswa@school.sch.id (Password default: NIS)">
                            <small class="text-muted">Jika diisi, akun login siswa dibuat otomatis dengan password = NIS.</small>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Alamat Rumah</label>
                            <textarea name="address" class="form-control" rows="2" placeholder="Alamat domisili siswa..."></textarea>
                        </div>
                        <div class="form-section-label"><i class="bi bi-person-hearts"></i>Orang Tua / Wali</div>
                        <div class="row g-3 p-3 rounded-3" style="background:#f8f9fa; border:1px solid #eef1f8;">
                            <div class="col-12"><small class="text-muted">Opsional. Data orang tua/wali siswa.</small></div>
                            <div class="col-md-4">
                                <label class="form-label">Nama</label>
                                <input type="text" name="parent_full_name" class="form-control" placeholder="Nama Lengkap">
                            </div>
                            <div class="col-md-4">
                                <label class="form-label">Hubungan</label>
                                <select class="form-select" name="parent_relationship">
                                    <option value="ayah">Ayah</option>
                                    <option value="ibu">Ibu</option>
                                    <option value="wali">Wali</option>
                                </select>
                            </div>
                            <div class="col-md-4">
                                <label class="form-label">No. Telepon</label>
                                <input type="text" name="parent_phone_number" class="form-control" placeholder="081234567890">
                            </div>
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

    {{-- Modal Edit Siswa --}}
    <div class="modal fade siswa-modal" id="modalEditSiswa" tabindex="-1" data-bs-backdrop="static" data-bs-keyboard="false">
        <div class="modal-dialog modal-dialog-centered modal-lg modal-dialog-scrollable">
            <div class="modal-content">
                <form id="formEditSiswa" method="POST">
                    @csrf
                    <div class="modal-header modal-header-custom">
                        <h5 class="modal-title fw-bold"><i class="bi bi-pencil-square me-2"></i>Edit Data Siswa</h5>
                        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" style="filter: brightness(0) invert(1); opacity: 0.8; position: relative; z-index: 2;"></button>
                    </div>
                    <div class="modal-body p-4" style="max-height: 70vh; overflow-y: auto;">
                        <div class="form-section-label"><i class="bi bi-person-badge"></i>Identitas Utama</div>
                        <div class="row g-3 mb-3">
                            <div class="col-md-6">
                                <label class="form-label">Nama Lengkap <span class="text-danger">*</span></label>
                                <input type="text" name="full_name" id="editFullName" class="form-control" required>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">NIS <span class="text-danger">*</span></label>
                                <input type="text" name="nis" id="editNis" class="form-control" required>
                            </div>
                        </div>
                        <div class="row g-3 mb-3">
                            <div class="col-md-3">
                                <label class="form-label">Tingkat <span class="text-danger">*</span></label>
                                <select class="form-select" id="editGrade" onchange="filterEditClasses()" required>
                                    <option value="" disabled>-- Pilih --</option>
                                    <option value="10">Kelas 10</option>
                                    <option value="11">Kelas 11</option>
                                    <option value="12">Kelas 12</option>
                                </select>
                            </div>
                            <div class="col-md-3">
                                <label class="form-label">Rombel <span class="text-danger">*</span></label>
                                <select class="form-select" name="class_id" id="editClassId" required>
                                    <option value="" disabled selected>-- Pilih --</option>
                                </select>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">Jenis Kelamin <span class="text-danger">*</span></label>
                                <select class="form-select" name="gender" id="editGender" required>
                                    <option value="L">Laki-laki</option>
                                    <option value="P">Perempuan</option>
                                </select>
                            </div>
                        </div>
                        <div class="form-section-label"><i class="bi bi-envelope"></i>Kontak & Akun</div>
                        <div class="row g-3 mb-3">
                            <div class="col-md-6">
                                <label class="form-label">Tanggal Lahir <span class="text-danger">*</span></label>
                                <input type="date" name="date_of_birth" id="editDob" class="form-control" required>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">No. Telepon / WA</label>
                                <input type="text" name="phone_number" id="editPhone" class="form-control">
                            </div>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Email Akun Login</label>
                            <input type="email" name="email" id="editEmail" class="form-control" placeholder="siswa@school.sch.id">
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Alamat Rumah</label>
                            <textarea name="address" id="editAddress" class="form-control" rows="2"></textarea>
                        </div>
                        <div class="form-section-label"><i class="bi bi-person-hearts"></i>Orang Tua / Wali</div>
                        <div class="row g-3 p-3 rounded-3" style="background:#f8f9fa; border:1px solid #eef1f8;">
                            <div class="col-md-4">
                                <label class="form-label">Nama</label>
                                <input type="text" name="parent_full_name" id="editParentName" class="form-control">
                            </div>
                            <div class="col-md-4">
                                <label class="form-label">Hubungan</label>
                                <select class="form-select" name="parent_relationship" id="editParentRel">
                                    <option value="ayah">Ayah</option>
                                    <option value="ibu">Ibu</option>
                                    <option value="wali">Wali</option>
                                </select>
                            </div>
                            <div class="col-md-4">
                                <label class="form-label">No. Telepon</label>
                                <input type="text" name="parent_phone_number" id="editParentPhone" class="form-control">
                            </div>
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
        const classesData = @json($datakelas);

        function filterAddClasses() {
            const grade = document.getElementById('addGrade').value;
            const sel = document.getElementById('addClassId');
            sel.innerHTML = '<option value="" disabled selected>-- Pilih Kelas --</option>';
            classesData.forEach(c => {
                if (c.grade == grade) {
                    sel.innerHTML += `<option value="${c.id}">${c.school_class_name} (${c.school_class_major})</option>`;
                }
            });
        }

        function filterEditClasses(preselect) {
            const grade = document.getElementById('editGrade').value;
            const sel = document.getElementById('editClassId');
            sel.innerHTML = '<option value="" disabled>-- Pilih Kelas --</option>';
            classesData.forEach(c => {
                if (c.grade == grade) {
                    const selected = (preselect && c.id == preselect) ? 'selected' : '';
                    sel.innerHTML += `<option value="${c.id}" ${selected}>${c.school_class_name} (${c.school_class_major})</option>`;
                }
            });
        }

        function openEditModal(id, name, nis, grade, classId, gender, dob, phone, email, address, parentName, parentRel, parentPhone) {
            document.getElementById('formEditSiswa').action = '/update/' + id;
            document.getElementById('editFullName').value = name;
            document.getElementById('editNis').value = nis;
            document.getElementById('editGrade').value = grade;
            filterEditClasses(classId);
            document.getElementById('editGender').value = gender;
            document.getElementById('editDob').value = dob;
            document.getElementById('editPhone').value = phone || '';
            document.getElementById('editEmail').value = email || '';
            document.getElementById('editAddress').value = address || '';
            document.getElementById('editParentName').value = parentName || '';
            document.getElementById('editParentRel').value = parentRel || 'ayah';
            document.getElementById('editParentPhone').value = parentPhone || '';
            new bootstrap.Modal(document.getElementById('modalEditSiswa')).show();
        }
    </script>
</body>

</html>
