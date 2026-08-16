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
        .kategori-modal .modal-content {
            border: none;
            border-radius: 18px;
            overflow: hidden;
            box-shadow: 0 25px 60px rgba(33, 51, 99, 0.25);
        }
        .kategori-modal .form-control,
        .kategori-modal .form-select {
            border-radius: 8px;
            border: 1px solid #ced4da;
            padding: 10px 14px;
            font-size: 0.9rem;
            color: #495057;
            transition: all 0.2s ease;
        }
        .kategori-modal .form-control:focus,
        .kategori-modal .form-select:focus {
            border-color: #4154f1;
            box-shadow: 0 0 0 0.25rem rgba(65, 84, 241, 0.15);
        }
        .kategori-modal .form-label {
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
        .kategori-modal .modal-body > .form-section-label:first-child {
            margin-top: 0;
        }
    </style>
</head>

<body>
    @include('include.header')
    @include('include.sidebar')

    <main id="main" class="main">
        <div class="pagetitle">
            <h1>Kategori Poin Pelanggaran</h1>
            <nav>
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Beranda</a></li>
                    <li class="breadcrumb-item active">Kategori Poin</li>
                </ol>
            </nav>
        </div>

        <div class="col-12">
            <div class="card border-0 shadow-sm rounded-3">
                <div class="card-body pt-3">
                    <div class="d-flex justify-content-between align-items-center mb-3 pb-2 border-bottom">
                        <h5 class="card-title fw-bold text-dark m-0 p-0">Daftar Kategori Poin Pelanggaran</h5>
                        <button type="button" class="btn btn-primary px-3 py-2 rounded-2 fw-semibold shadow-sm" data-bs-toggle="modal" data-bs-target="#modalTambahKategori">
                            <i class="bi bi-plus-circle me-1"></i>Tambah Kategori Baru
                        </button>
                    </div>

                    {{-- Search Bar --}}
                    <div class="mb-3">
                        <div class="input-group" style="max-width: 360px;">
                            <span class="input-group-text bg-white border-end-0"><i class="bi bi-search text-muted"></i></span>
                            <input type="text" id="searchKategori" class="form-control border-start-0 ps-0" placeholder="Cari kategori atau tindak lanjut...">
                        </div>
                    </div>

                    <div class="table-responsive">
                        <table class="table table-hover align-middle" id="tabelKategori">
                            <thead class="table-light">
                                <tr>
                                    <th style="width:45px;">No</th>
                                    <th>Kategori & Tindak Lanjut</th>
                                    <th class="text-center">Range Poin</th>
                                    <th class="text-center">Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse ($datakategori as $data)
                                    <tr>
                                        <td>{{ $loop->iteration }}</td>
                                        <td>
                                            <div class="fw-bold text-dark">{{ $data->category_of_violation }}</div>
                                            <div class="text-muted small text-truncate" style="max-width: 350px;" title="{{ $data->follow_up }}">
                                                <i class="bi bi-arrow-return-right me-1"></i>{{ $data->follow_up }}
                                            </div>
                                        </td>
                                        <td class="text-center">
                                            <span class="badge bg-warning text-dark fw-bold px-2 py-1" style="font-size:0.85em;">{{ $data->category_score_min }}</span>
                                            <span class="text-muted mx-1">-</span>
                                            <span class="badge bg-danger text-white fw-bold px-2 py-1" style="font-size:0.85em;">{{ $data->category_score_max }}</span>
                                        </td>
                                        <td class="text-center">
                                            <div class="dropdown d-inline-block">
                                                <button class="btn btn-sm btn-outline-secondary dropdown-toggle px-3" type="button" data-bs-toggle="dropdown" data-bs-boundary="viewport" data-bs-popper-config='{"strategy":"fixed"}' aria-expanded="false">
                                                    <i class="bi bi-gear me-1"></i> Aksi
                                                </button>
                                                <ul class="dropdown-menu dropdown-menu-end shadow-sm" style="max-height: 250px; overflow-y: auto;">
                                                    <li>
                                                        <a class="dropdown-item d-flex align-items-center py-2 text-warning" href="javascript:void(0)" onclick="openEditModalKategori({{ $data->id }}, '{{ addslashes($data->category_of_violation) }}', '{{ $data->category_score_min }}', '{{ $data->category_score_max }}', '{{ addslashes($data->follow_up) }}')">
                                                            <i class="bi bi-pencil me-2"></i> Edit
                                                        </a>
                                                    </li>
                                                    <li>
                                                        <a class="dropdown-item d-flex align-items-center py-2 text-danger" href="javascript:void(0)" onclick="swalConfirm('Yakin ingin menghapus kategori ini?', function(){ window.location='{{ url('/hapuskategori/' . $data->id) }}'; })">
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
                                            <i class="bi bi-collection fs-2 d-block mb-2 opacity-25"></i>
                                            Belum ada kategori poin pelanggaran.
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                        <p id="noResultKategori" class="text-center text-muted py-3 d-none">Tidak ada data yang cocok dengan pencarian.</p>
                    </div>
                </div>
            </div>
        </div>
    </main><!-- End #main -->

    @include('include.footer')
    <a href="#" class="back-to-top d-flex align-items-center justify-content-center"><i class="bi bi-arrow-up-short"></i></a>
    @include('include.script')

    {{-- Modal Tambah Kategori --}}
    <div class="modal fade kategori-modal" id="modalTambahKategori" tabindex="-1" data-bs-backdrop="static" data-bs-keyboard="false">
        <div class="modal-dialog modal-dialog-centered modal-lg">
            <div class="modal-content">
                <form action="simpankategori" method="post">
                    @csrf
                    <div class="modal-header modal-header-custom">
                        <h5 class="modal-title fw-bold"><i class="bi bi-plus-circle me-2"></i>Tambah Kategori Poin</h5>
                        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" style="filter: brightness(0) invert(1); opacity: 0.8; position: relative; z-index: 2;"></button>
                    </div>
                    <div class="modal-body p-4">
                        <div class="form-section-label"><i class="bi bi-card-checklist"></i>Detail Kategori</div>
                        <div class="row g-3 mb-3">
                            <div class="col-12">
                                <label class="form-label">Jenis / Kategori Pelanggaran <span class="text-danger">*</span></label>
                                <input type="text" name="category_of_violation" class="form-control" placeholder="Contoh: Pelanggaran Ringan, Terlambat, dll." required>
                            </div>

                            <div class="col-md-6">
                                <label class="form-label">Poin Minimum <span class="text-danger">*</span></label>
                                <div class="input-group">
                                    <span class="input-group-text bg-warning bg-opacity-10 text-warning"><i class="bi bi-dash-circle"></i></span>
                                    <input type="number" name="category_score_min" class="form-control" placeholder="Contoh: 10" min="0" required>
                                </div>
                            </div>

                            <div class="col-md-6">
                                <label class="form-label">Poin Maksimum <span class="text-danger">*</span></label>
                                <div class="input-group">
                                    <span class="input-group-text bg-danger bg-opacity-10 text-danger"><i class="bi bi-plus-circle"></i></span>
                                    <input type="number" name="category_score_max" class="form-control" placeholder="Contoh: 30" min="0" required>
                                </div>
                            </div>

                            <div class="col-12">
                                <label class="form-label">Tindak Lanjut <span class="text-danger">*</span></label>
                                <input type="text" name="follow_up" class="form-control" placeholder="Contoh: Panggilan orang tua, Skorsing, dll." required>
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

    {{-- Modal Edit Kategori --}}
    <div class="modal fade kategori-modal" id="modalEditKategori" tabindex="-1" data-bs-backdrop="static" data-bs-keyboard="false">
        <div class="modal-dialog modal-dialog-centered modal-lg">
            <div class="modal-content">
                <form id="formEditKategori" method="post">
                    @csrf
                    <div class="modal-header modal-header-custom">
                        <h5 class="modal-title fw-bold"><i class="bi bi-pencil-square me-2"></i>Edit Kategori Poin</h5>
                        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" style="filter: brightness(0) invert(1); opacity: 0.8; position: relative; z-index: 2;"></button>
                    </div>
                    <div class="modal-body p-4">
                        <div class="form-section-label"><i class="bi bi-card-checklist"></i>Detail Kategori</div>
                        <div class="row g-3 mb-3">
                            <div class="col-12">
                                <label class="form-label">Jenis / Kategori Pelanggaran <span class="text-danger">*</span></label>
                                <input type="text" name="category_of_violation" id="edit_category_of_violation" class="form-control" required>
                            </div>

                            <div class="col-md-6">
                                <label class="form-label">Poin Minimum <span class="text-danger">*</span></label>
                                <div class="input-group">
                                    <span class="input-group-text bg-warning bg-opacity-10 text-warning"><i class="bi bi-dash-circle"></i></span>
                                    <input type="number" name="category_score_min" id="edit_category_score_min" class="form-control" min="0" required>
                                </div>
                            </div>

                            <div class="col-md-6">
                                <label class="form-label">Poin Maksimum <span class="text-danger">*</span></label>
                                <div class="input-group">
                                    <span class="input-group-text bg-danger bg-opacity-10 text-danger"><i class="bi bi-plus-circle"></i></span>
                                    <input type="number" name="category_score_max" id="edit_category_score_max" class="form-control" min="0" required>
                                </div>
                            </div>

                            <div class="col-12">
                                <label class="form-label">Tindak Lanjut <span class="text-danger">*</span></label>
                                <input type="text" name="follow_up" id="edit_follow_up" class="form-control" required>
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
        function openEditModalKategori(id, category, min, max, follow_up) {
            document.getElementById('formEditKategori').action = '/updatekategori/' + id;
            document.getElementById('edit_category_of_violation').value = category;
            document.getElementById('edit_category_score_min').value = min;
            document.getElementById('edit_category_score_max').value = max;
            document.getElementById('edit_follow_up').value = follow_up;
            new bootstrap.Modal(document.getElementById('modalEditKategori')).show();
        }
    </script>
</body>

</html>
