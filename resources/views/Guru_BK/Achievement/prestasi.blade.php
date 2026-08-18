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
        .prestasi-modal .modal-content {
            border: none;
            border-radius: 18px;
            overflow: hidden;
            box-shadow: 0 25px 60px rgba(33, 51, 99, 0.25);
        }
        .prestasi-modal .form-control,
        .prestasi-modal .form-select {
            border-radius: 8px;
            border: 1px solid #ced4da;
            padding: 10px 14px;
            font-size: 0.9rem;
            color: #495057;
            transition: all 0.2s ease;
        }
        .prestasi-modal .form-control:focus,
        .prestasi-modal .form-select:focus {
            border-color: #4154f1;
            box-shadow: 0 0 0 0.25rem rgba(65, 84, 241, 0.15);
        }
        .prestasi-modal .form-label {
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
        .prestasi-modal .modal-body > .form-section-label:first-child {
            margin-top: 0;
        }
    </style>
</head>

<body>
    @include('include.header')
    @include('include.sidebar')

    <main id="main" class="main">
        <div class="pagetitle">
            <h1>Prestasi Siswa</h1>
            <nav>
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Beranda</a></li>
                    <li class="breadcrumb-item active">Prestasi Siswa</li>
                </ol>
            </nav>
        </div>

        <div class="col-12">
            <div class="card border-0 shadow-sm rounded-3">
                <div class="card-body pt-3">
                    <div class="d-flex justify-content-between align-items-center mb-3 pb-2 border-bottom">
                        <h5 class="card-title fw-bold text-dark m-0 p-0">Daftar Prestasi Siswa</h5>
                        <button type="button" class="btn btn-primary px-3 py-2 rounded-2 fw-semibold shadow-sm" data-bs-toggle="modal" data-bs-target="#modalTambahPrestasi">
                            <i class="bi bi-trophy me-1"></i>Tambah Prestasi Baru
                        </button>
                    </div>

                    {{-- Search Bar --}}
                    <div class="mb-3">
                        <div class="input-group" style="max-width: 360px;">
                            <span class="input-group-text bg-white border-end-0"><i class="bi bi-search text-muted"></i></span>
                            <input type="text" id="searchPrestasi" class="form-control border-start-0 ps-0" placeholder="Cari nama siswa, prestasi, kategori...">
                        </div>
                    </div>

                    <div class="table-responsive">
                        <table class="table table-hover align-middle" id="tabelPrestasi">
                            <thead class="table-light">
                                <tr>
                                    <th style="width:45px;">No</th>
                                    <th>Nama Siswa</th>
                                    <th>Prestasi & Kategori</th>
                                    <th>Tanggal & Status</th>
                                    <th class="text-center">Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse ($prestasi as $data)
                                    <tr>
                                        <td>{{ $loop->iteration }}</td>
                                        <td class="fw-bold text-dark">{{ $data->student?->full_name ?? 'Siswa Terhapus' }}</td>
                                        <td>
                                            <div class="fw-bold text-success text-truncate" style="max-width: 250px;" title="{{ $data->achievement_name }}">
                                                <i class="bi bi-trophy me-1"></i>{{ $data->achievement_name }}
                                            </div>
                                            <div class="mt-1">
                                                @php
                                                    $levelClass = match(strtolower($data->achievement_level ?? '')) {
                                                        'internasional' => 'bg-danger text-white',
                                                        'nasional'      => 'bg-primary text-white',
                                                        'provinsi'      => 'bg-info text-white',
                                                        'kabupaten', 'kota' => 'bg-warning text-dark',
                                                        default         => 'bg-secondary text-white',
                                                    };
                                                @endphp
                                                <span class="badge {{ $levelClass }} px-2 py-1">{{ $data->achievement_level }}</span>
                                                <small class="text-muted ms-1"><i class="bi bi-tag me-1"></i>{{ $data->achievement_category }}</small>
                                            </div>
                                        </td>
                                        <td>
                                            <small class="text-muted d-block fw-semibold mb-1"><i class="bi bi-calendar me-1"></i>{{ \Carbon\Carbon::parse($data->achievement_date)->format('d M Y') }}</small>
                                            @if(strtolower($data->achievement_status ?? '') === 'terverifikasi')
                                                <span class="badge bg-success bg-opacity-10 text-success border border-success"><i class="bi bi-patch-check me-1"></i>Terverifikasi</span>
                                            @else
                                                <span class="badge bg-warning bg-opacity-10 text-warning border border-warning"><i class="bi bi-clock me-1"></i>{{ $data->achievement_status }}</span>
                                            @endif
                                        </td>
                                        <td class="text-center">
                                            <div class="dropdown d-inline-block">
                                                <button class="btn btn-sm btn-outline-secondary dropdown-toggle px-3" type="button" data-bs-toggle="dropdown" data-bs-boundary="viewport" data-bs-popper-config='{"strategy":"fixed"}' aria-expanded="false">
                                                    <i class="bi bi-gear me-1"></i> Aksi
                                                </button>
                                                <ul class="dropdown-menu dropdown-menu-end shadow-sm" style="max-height: 250px; overflow-y: auto;">
                                                    <li>
                                                        <a class="dropdown-item d-flex align-items-center py-2 text-warning" href="javascript:void(0)" onclick="openEditModalPrestasi({{ $data->id }}, '{{ $data->student_id }}', '{{ $data->achievement_date }}', '{{ addslashes($data->achievement_name) }}', '{{ $data->achievement_level }}', '{{ $data->achievement_category }}', '{{ $data->achievement_status }}')">
                                                            <i class="bi bi-pencil me-2"></i> Edit
                                                        </a>
                                                    </li>
                                                    <li>
                                                        <form id="delete-form-prestasi-{{ $data->id }}" action="{{ url('/hapusprestasi/' . $data->id) }}" method="POST" class="d-none">
                                                            @csrf
                                                            @method('DELETE')
                                                        </form>
                                                        <a class="dropdown-item d-flex align-items-center py-2 text-danger" href="javascript:void(0)" onclick="swalConfirm('Yakin ingin menghapus data prestasi ini?', function(){ document.getElementById('delete-form-prestasi-{{ $data->id }}').submit(); })">
                                                            <i class="bi bi-trash me-2"></i> Hapus
                                                        </a>
                                                    </li>
                                                </ul>
                                            </div>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="8" class="text-center py-5 text-muted">
                                            <i class="bi bi-trophy fs-2 d-block mb-2 opacity-25"></i>
                                            Belum ada data prestasi siswa tercatat.
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                        <p id="noResultPrestasi" class="text-center text-muted py-3 d-none">Tidak ada data yang cocok dengan pencarian.</p>
                    </div>
                </div>
            </div>
        </div>
    </main><!-- End #main -->

    @include('include.footer')
    <a href="#" class="back-to-top d-flex align-items-center justify-content-center"><i class="bi bi-arrow-up-short"></i></a>
    @include('include.script')

    {{-- Modal Tambah Prestasi --}}
    <div class="modal fade prestasi-modal" id="modalTambahPrestasi" tabindex="-1" data-bs-backdrop="static" data-bs-keyboard="false">
        <div class="modal-dialog modal-dialog-centered modal-lg">
            <div class="modal-content">
                <form action="simpanprestasi" method="post">
                    @csrf
                    <div class="modal-header modal-header-custom">
                        <h5 class="modal-title fw-bold"><i class="bi bi-plus-circle me-2"></i>Tambah Data Prestasi</h5>
                        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" style="filter: brightness(0) invert(1); opacity: 0.8; position: relative; z-index: 2;"></button>
                    </div>
                    <div class="modal-body p-4">
                        <div class="form-section-label"><i class="bi bi-person"></i>Identitas Siswa</div>
                        <div class="row g-3 mb-3">
                            <div class="col-md-6">
                                <label class="form-label">Pilih Siswa <span class="text-danger">*</span></label>
                                <select class="form-select" name="student_id" required>
                                    <option disabled selected value="">-- Pilih Siswa --</option>
                                    @php $datasiswa = \App\Models\Student::all(); @endphp
                                    @foreach ($datasiswa as $item)
                                        <option value="{{ $item->id }}">{{ $item->full_name }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">Tanggal Prestasi <span class="text-danger">*</span></label>
                                <input type="date" name="achievement_date" class="form-control" value="{{ date('Y-m-d') }}" required>
                            </div>
                        </div>
                        <div class="form-section-label"><i class="bi bi-trophy"></i>Detail Prestasi</div>
                        <div class="row g-3 mb-3">
                            <div class="col-md-6">
                                <label class="form-label">Nama Prestasi <span class="text-danger">*</span></label>
                                <input type="text" name="achievement_name" class="form-control" placeholder="Contoh: Juara 1 Lomba Web Design" required>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">Tingkat Prestasi <span class="text-danger">*</span></label>
                                <select name="achievement_level" class="form-select" required>
                                    <option value="" disabled selected>-- Pilih Tingkat --</option>
                                    <option value="Sekolah">Sekolah</option>
                                    <option value="Kabupaten">Kabupaten / Kota</option>
                                    <option value="Provinsi">Provinsi</option>
                                    <option value="Nasional">Nasional</option>
                                    <option value="Internasional">Internasional</option>
                                </select>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">Kategori Prestasi <span class="text-danger">*</span></label>
                                <select name="achievement_category" class="form-select" required>
                                    <option value="" disabled selected>-- Pilih Kategori --</option>
                                    <option value="Akademik">Akademik</option>
                                    <option value="Non-Akademik">Non-Akademik</option>
                                    <option value="Olahraga">Olahraga</option>
                                    <option value="Seni & Budaya">Seni & Budaya</option>
                                    <option value="Teknologi">Teknologi</option>
                                    <option value="Lainnya">Lainnya</option>
                                </select>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">Status Verifikasi <span class="text-danger">*</span></label>
                                <select name="achievement_status" class="form-select" required>
                                    <option value="Menunggu Verifikasi">Menunggu Verifikasi</option>
                                    <option value="Terverifikasi">Terverifikasi</option>
                                </select>
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

    {{-- Modal Edit Prestasi --}}
    <div class="modal fade prestasi-modal" id="modalEditPrestasi" tabindex="-1" data-bs-backdrop="static" data-bs-keyboard="false">
        <div class="modal-dialog modal-dialog-centered modal-lg">
            <div class="modal-content">
                <form id="formEditPrestasi" method="post">
                    @csrf
                    <div class="modal-header modal-header-custom">
                        <h5 class="modal-title fw-bold"><i class="bi bi-pencil-square me-2"></i>Edit Data Prestasi</h5>
                        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" style="filter: brightness(0) invert(1); opacity: 0.8; position: relative; z-index: 2;"></button>
                    </div>
                    <div class="modal-body p-4">
                        <div class="form-section-label"><i class="bi bi-person"></i>Identitas Siswa</div>
                        <div class="row g-3 mb-3">
                            <div class="col-md-6">
                                <label class="form-label">Tanggal Prestasi <span class="text-danger">*</span></label>
                                <input type="date" name="achievement_date" id="edit_achievement_date" class="form-control" required>
                            </div>
                        </div>
                        <div class="form-section-label"><i class="bi bi-trophy"></i>Detail Prestasi</div>
                        <div class="row g-3 mb-3">
                            <div class="col-md-6">
                                <label class="form-label">Nama Prestasi <span class="text-danger">*</span></label>
                                <input type="text" name="achievement_name" id="edit_achievement_name" class="form-control" required>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">Tingkat Prestasi <span class="text-danger">*</span></label>
                                <select name="achievement_level" id="edit_achievement_level" class="form-select" required>
                                    <option value="Sekolah">Sekolah</option>
                                    <option value="Kabupaten">Kabupaten / Kota</option>
                                    <option value="Provinsi">Provinsi</option>
                                    <option value="Nasional">Nasional</option>
                                    <option value="Internasional">Internasional</option>
                                </select>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">Kategori Prestasi <span class="text-danger">*</span></label>
                                <select name="achievement_category" id="edit_achievement_category" class="form-select" required>
                                    <option value="Akademik">Akademik</option>
                                    <option value="Non-Akademik">Non-Akademik</option>
                                    <option value="Olahraga">Olahraga</option>
                                    <option value="Seni & Budaya">Seni & Budaya</option>
                                    <option value="Teknologi">Teknologi</option>
                                    <option value="Lainnya">Lainnya</option>
                                </select>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">Status Verifikasi <span class="text-danger">*</span></label>
                                <select name="achievement_status" id="edit_achievement_status" class="form-select" required>
                                    <option value="Menunggu Verifikasi">Menunggu Verifikasi</option>
                                    <option value="Terverifikasi">Terverifikasi</option>
                                </select>
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
        document.addEventListener('DOMContentLoaded', function () {
            const searchInput = document.getElementById('searchPrestasi');
            const tableRows = document.querySelectorAll('#tabelPrestasi tbody tr');
            const noResultMsg = document.getElementById('noResultPrestasi');

            if(searchInput) {
                searchInput.addEventListener('keyup', function () {
                    const filter = this.value.toLowerCase();
                    let hasVisibleRow = false;

                    tableRows.forEach(row => {
                        if(row.cells.length === 1) return; // Skip empty row
                        
                        const text = row.textContent.toLowerCase();
                        if (text.includes(filter)) {
                            row.style.display = '';
                            hasVisibleRow = true;
                        } else {
                            row.style.display = 'none';
                        }
                    });

                    if (noResultMsg) {
                        const isEmptyRowVisible = tableRows.length === 1 && tableRows[0].cells.length === 1;
                        noResultMsg.classList.toggle('d-none', hasVisibleRow || isEmptyRowVisible);
                    }
                });
            }
        });

        function openEditModalPrestasi(id, student_id, date, name, level, category, status) {
            document.getElementById('formEditPrestasi').action = '/updateprestasi/' + id;
            document.getElementById('edit_achievement_date').value = date;
            document.getElementById('edit_achievement_name').value = name;
            document.getElementById('edit_achievement_level').value = level;
            document.getElementById('edit_achievement_category').value = category;
            document.getElementById('edit_achievement_status').value = status;
            new bootstrap.Modal(document.getElementById('modalEditPrestasi')).show();
        }
    </script>
</body>

</html>
