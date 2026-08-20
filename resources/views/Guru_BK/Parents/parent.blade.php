<!DOCTYPE html>
<html lang="en">

<head>
    @include('include.style')
    <style>
        .modal-header-custom {
            background: linear-gradient(135deg, #4154f1 0%, #012970 100%);
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
        .parent-modal .modal-content {
            border: none;
            border-radius: 18px;
            overflow: hidden;
            box-shadow: 0 25px 60px rgba(33, 51, 99, 0.25);
        }
        .parent-modal .form-control,
        .parent-modal .form-select {
            border-radius: 8px;
            border: 1px solid #ced4da;
            padding: 10px 14px;
            font-size: 0.9rem;
            color: #495057;
            transition: all 0.2s ease;
        }
        .parent-modal .form-control:focus,
        .parent-modal .form-select:focus {
            border-color: #4154f1;
            box-shadow: 0 0 0 0.25rem rgba(65, 84, 241, 0.15);
        }
        .parent-modal .form-label {
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
        .parent-modal .modal-body > .form-section-label:first-child {
            margin-top: 0;
        }
    </style>
</head>

<body>
    @include('include.header')
    @include('include.sidebar')

    <main id="main" class="main">
        <div class="pagetitle">
            <h1>Data Orang Tua / Wali</h1>
            <nav>
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Beranda</a></li>
                    <li class="breadcrumb-item active">Data Orang Tua</li>
                </ol>
            </nav>
        </div>

        <div class="col-12">
            <div class="card border-0 shadow-sm rounded-3">
                <div class="card-body pt-3">
                    <div class="d-flex justify-content-between align-items-center mb-3 pb-2 border-bottom">
                        <h5 class="card-title fw-bold text-dark m-0 p-0">Daftar Data Orang Tua / Wali Siswa</h5>
                        <button type="button" class="btn btn-primary px-3 py-2 rounded-2 fw-semibold shadow-sm" data-bs-toggle="modal" data-bs-target="#modalTambahParent">
                            <i class="bi bi-person-plus-fill me-1"></i>Tambah Orang Tua Baru
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
                                <input type="text" id="searchParent" class="form-control border-start-0 ps-0" placeholder="Cari nama orang tua, pekerjaan, atau nama siswa...">
                            </div>
                        </div>
                    </div>

                    <div class="table-responsive">
                        <table class="table table-hover align-middle" id="tabelParent">
                            <thead class="table-light">
                                <tr>
                                    <th style="width:45px;">No</th>
                                    <th>Nama Wali & Pekerjaan</th>
                                    <th>Orang Tua Dari (Siswa & Kelas)</th>
                                    <th>Alamat & Kontak</th>
                                    <th class="text-center">Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse ($parentdata as $parent)
                                    <tr>
                                        <td>{{ $loop->iteration }}</td>
                                        <td>
                                            <div class="fw-bold text-dark">{{ $parent->parent_full_name }}</div>
                                            <div class="text-muted small"><i class="bi bi-briefcase me-1"></i>{{ $parent->job }}</div>
                                        </td>
                                        <td>
                                            @forelse($parent->student as $s)
                                                <div class="mb-1">
                                                    <span class="fw-semibold text-dark">{{ $s->full_name }}</span>
                                                    @if($s->class)
                                                        <span class="badge bg-primary bg-opacity-10 text-primary border border-primary border-opacity-25 ms-1">
                                                            Kelas {{ $s->class->grade }} - {{ $s->class->school_class_name }}
                                                        </span>
                                                    @else
                                                        <span class="badge bg-secondary bg-opacity-10 text-secondary border ms-1">Tanpa Kelas</span>
                                                    @endif
                                                </div>
                                            @empty
                                                <span class="text-muted small">Belum terhubung ke siswa</span>
                                            @endforelse
                                        </td>
                                        <td>
                                            <div class="text-truncate mb-1" style="max-width: 250px;" title="{{ $parent->address }}"><i class="bi bi-geo-alt me-1 text-muted"></i>{{ $parent->address }}</div>
                                            <small><i class="bi bi-whatsapp me-1 text-success"></i>{{ $parent->phone_number }}</small>
                                        </td>
                                        <td class="text-center">
                                            <div class="dropdown d-inline-block">
                                                <button class="btn btn-sm btn-outline-secondary dropdown-toggle px-3" type="button" data-bs-toggle="dropdown" data-bs-boundary="viewport" data-bs-popper-config='{"strategy":"fixed"}' aria-expanded="false">
                                                    <i class="bi bi-gear me-1"></i> Aksi
                                                </button>
                                                <ul class="dropdown-menu dropdown-menu-end shadow-sm" style="max-height: 250px; overflow-y: auto;">
                                                    @php
                                                        $rawPhone = $parent->phone_number;
                                                        $cleanPhone = preg_replace('/[^0-9]/', '', $rawPhone);
                                                        if (str_starts_with($cleanPhone, '0')) {
                                                            $cleanPhone = '62' . substr($cleanPhone, 1);
                                                        }
                                                        
                                                        $studentNames = $parent->student->pluck('full_name')->implode(', ');
                                                        $templateText = "Halo Bapak/Ibu " . $parent->parent_full_name . ", saya dari pihak Bimbingan Konseling (BK) sekolah. Ingin berkonsultasi mengenai perkembangan/bimbingan ananda " . ($studentNames ?: 'siswa') . ". Apakah Bapak/Ibu ada waktu luang untuk berkomunikasi?";
                                                        $waUrl = "https://wa.me/" . $cleanPhone . "?text=" . rawurlencode($templateText);
                                                    @endphp
                                                    <li>
                                                        <a class="dropdown-item d-flex align-items-center py-2 text-success" href="{{ $waUrl }}" target="_blank">
                                                            <i class="bi bi-whatsapp me-2"></i> Hubungi WA
                                                        </a>
                                                    </li>
                                                    <li>
                                                        <a class="dropdown-item d-flex align-items-center py-2 text-warning" href="javascript:void(0)" onclick="openEditModalParent({{ $parent->id }}, '{{ addslashes($parent->parent_full_name) }}', '{{ addslashes($parent->phone_number) }}', '{{ addslashes($parent->job) }}', '{{ addslashes($parent->address) }}')">
                                                            <i class="bi bi-pencil me-2"></i> Edit
                                                        </a>
                                                    </li>
                                                    <li>
                                                        <form id="delete-form-parent-{{ $parent->id }}" action="{{ url('/hapusparent/' . $parent->id) }}" method="POST" class="d-none">
                                                            @csrf
                                                            @method('DELETE')
                                                        </form>
                                                        <a class="dropdown-item d-flex align-items-center py-2 text-danger" href="javascript:void(0)" onclick="swalConfirm('Yakin ingin menghapus data orang tua ini?', function(){ document.getElementById('delete-form-parent-{{ $parent->id }}').submit(); })">
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
                                            <i class="bi bi-people fs-2 d-block mb-2 opacity-25"></i>
                                            Belum ada data orang tua terdaftar.
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                        <p id="noResultParent" class="text-center text-muted py-3 d-none">Tidak ada data yang cocok dengan pencarian.</p>
                    </div>
                </div>
            </div>
        </div>
    </main><!-- End #main -->

    @include('include.footer')
    <a href="#" class="back-to-top d-flex align-items-center justify-content-center"><i class="bi bi-arrow-up-short"></i></a>
    @include('include.script')

    {{-- Modal Tambah Parent --}}
    <div class="modal fade parent-modal" id="modalTambahParent" tabindex="-1" data-bs-backdrop="static" data-bs-keyboard="false">
        <div class="modal-dialog modal-dialog-centered modal-lg">
            <div class="modal-content">
                <form action="simpanparent" method="post">
                    @csrf
                    <div class="modal-header modal-header-custom">
                        <h5 class="modal-title fw-bold"><i class="bi bi-person-plus-fill me-2"></i>Tambah Data Orang Tua</h5>
                        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" style="filter: brightness(0) invert(1); opacity: 0.8; position: relative; z-index: 2;"></button>
                    </div>
                    <div class="modal-body p-4">
                        <div class="form-section-label"><i class="bi bi-person"></i>Identitas Diri</div>
                        <div class="row g-3 mb-3">
                            <div class="col-md-6">
                                <label class="form-label">Nama Lengkap Orang Tua / Wali <span class="text-danger">*</span></label>
                                <input type="text" name="parent_full_name" class="form-control" placeholder="Contoh: Budi Santoso" required>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">Pekerjaan <span class="text-danger">*</span></label>
                                <input type="text" name="job" class="form-control" placeholder="Contoh: Wiraswasta" required>
                            </div>
                        </div>

                        <div class="form-section-label"><i class="bi bi-telephone"></i>Kontak & Alamat</div>
                        <div class="row g-3 mb-3">
                            <div class="col-md-6">
                                <label class="form-label">Nomor Telepon (WA) <span class="text-danger">*</span></label>
                                <div class="input-group">
                                    <span class="input-group-text"><i class="bi bi-telephone"></i></span>
                                    <input type="text" name="phone_number" class="form-control" placeholder="Contoh: 08123456789" required>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">Alamat Lengkap <span class="text-danger">*</span></label>
                                <input type="text" name="address" class="form-control" placeholder="Contoh: Jl. Merdeka No. 10" required>
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

    {{-- Modal Edit Parent --}}
    <div class="modal fade parent-modal" id="modalEditParent" tabindex="-1" data-bs-backdrop="static" data-bs-keyboard="false">
        <div class="modal-dialog modal-dialog-centered modal-lg">
            <div class="modal-content">
                <form id="formEditParent" method="post">
                    @csrf
                    <div class="modal-header modal-header-custom">
                        <h5 class="modal-title fw-bold"><i class="bi bi-pencil-square me-2"></i>Edit Data Orang Tua</h5>
                        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" style="filter: brightness(0) invert(1); opacity: 0.8; position: relative; z-index: 2;"></button>
                    </div>
                    <div class="modal-body p-4">
                        <div class="form-section-label"><i class="bi bi-person"></i>Identitas Diri</div>
                        <div class="row g-3 mb-3">
                            <div class="col-md-6">
                                <label class="form-label">Nama Lengkap Orang Tua / Wali <span class="text-danger">*</span></label>
                                <input type="text" name="parent_full_name" id="edit_parent_full_name" class="form-control" required>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">Pekerjaan <span class="text-danger">*</span></label>
                                <input type="text" name="job" id="edit_job" class="form-control" required>
                            </div>
                        </div>

                        <div class="form-section-label"><i class="bi bi-telephone"></i>Kontak & Alamat</div>
                        <div class="row g-3 mb-3">
                            <div class="col-md-6">
                                <label class="form-label">Nomor Telepon (WA) <span class="text-danger">*</span></label>
                                <div class="input-group">
                                    <span class="input-group-text"><i class="bi bi-telephone"></i></span>
                                    <input type="text" name="phone_number" id="edit_phone_number" class="form-control" required>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">Alamat Lengkap <span class="text-danger">*</span></label>
                                <input type="text" name="address" id="edit_address" class="form-control" required>
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

    <script>
        function openEditModalParent(id, name, phone, job, address) {
            document.getElementById('formEditParent').action = '/updateparent/' + id;
            document.getElementById('edit_parent_full_name').value = name;
            document.getElementById('edit_phone_number').value = phone;
            document.getElementById('edit_job').value = job;
            document.getElementById('edit_address').value = address;
            new bootstrap.Modal(document.getElementById('modalEditParent')).show();
        }
    </script>
</body>

</html>

