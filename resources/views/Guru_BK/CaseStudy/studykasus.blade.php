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
        .kasus-modal .modal-content {
            border: none;
            border-radius: 18px;
            overflow: hidden;
            box-shadow: 0 25px 60px rgba(33, 51, 99, 0.25);
        }
        .kasus-modal .form-control,
        .kasus-modal .form-select {
            border-radius: 8px;
            border: 1px solid #ced4da;
            padding: 10px 14px;
            font-size: 0.9rem;
            color: #495057;
            transition: all 0.2s ease;
        }
        .kasus-modal .form-control:focus,
        .kasus-modal .form-select:focus {
            border-color: #4154f1;
            box-shadow: 0 0 0 0.25rem rgba(65, 84, 241, 0.15);
        }
        .kasus-modal .form-label {
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
        .kasus-modal .modal-body > .form-section-label:first-child {
            margin-top: 0;
        }

        /* Drag & Drop File Upload Style */
        .file-upload-wrapper {
            position: relative;
            width: 100%;
            height: 80px;
            border: 2px dashed #a0b5e8;
            border-radius: 8px;
            background-color: #f8fbff;
            display: flex;
            align-items: center;
            justify-content: center;
            transition: all 0.3s ease;
            cursor: pointer;
            overflow: hidden;
            margin-top: 5px;
        }
        .file-upload-wrapper:hover {
            border-color: #4154f1;
            background-color: #f0f4ff;
        }
        .file-upload-wrapper input[type="file"] {
            position: absolute;
            left: 0;
            top: 0;
            width: 100%;
            height: 100%;
            opacity: 0;
            cursor: pointer;
            z-index: 2;
        }
        .file-upload-text {
            color: #6c7d93;
            font-size: 0.95rem;
            pointer-events: none;
            z-index: 1;
        }
        .file-upload-text .browse-link {
            color: #4154f1;
            font-weight: 600;
            text-decoration: underline;
        }
    </style>
</head>

<body>
    <!-- ======= Header ======= -->
    @include('include.header')
    {{-- Modal Tambah Kasus --}}
    <div class="modal fade kasus-modal" id="modalTambahKasus" tabindex="-1" data-bs-backdrop="static" data-bs-keyboard="false">
        <div class="modal-dialog modal-dialog-centered modal-lg">
            <div class="modal-content">
                <form action="{{ url('simpanstudykasus') }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    <div class="modal-header modal-header-custom">
                        <h5 class="modal-title fw-bold"><i class="bi bi-plus-circle me-2"></i>Catat Pelanggaran Baru</h5>
                        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" style="filter: brightness(0) invert(1); opacity: 0.8; position: relative; z-index: 2;"></button>
                    </div>
                    <div class="modal-body p-4">
                        <div class="form-section-label"><i class="bi bi-person"></i>Identitas Siswa</div>
                        <div class="row g-3 mb-3">
                            <div class="col-md-6">
                                <label class="form-label">Pilih Siswa <span class="text-danger">*</span></label>
                                <select class="form-select" name="student_id" required>
                                    <option disabled selected value="">-- Pilih Siswa --</option>
                                    @php $datasiswa = \App\Models\Student::with('class')->get(); @endphp
                                    @foreach ($datasiswa as $item)
                                        <option value="{{ $item->id }}">{{ $item->full_name }} ({{ $item->class?->school_class_name ?? '-' }})</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">Tanggal Kasus <span class="text-danger">*</span></label>
                                <input type="date" class="form-control" name="case_date" value="{{ date('Y-m-d') }}" required>
                            </div>
                        </div>

                        <div class="form-section-label"><i class="bi bi-journal-text"></i>Detail Kejadian</div>
                        <div class="row g-3 mb-3">
                            <div class="col-md-6">
                                <label class="form-label">Guru Pelapor <span class="text-danger">*</span></label>
                                <input type="text" class="form-control" name="reporter_teacher" placeholder="Nama Guru yang mengajar/melaporkan" required>
                            </div>
                            <div class="col-md-6">
                                <div class="row g-3">
                                    <div class="col-md-6">
                                        <label class="form-label">Mata Pelajaran</label>
                                        <input type="text" class="form-control" name="subject_name" placeholder="Misal: Fisika">
                                    </div>
                                    <div class="col-md-6">
                                        <label class="form-label">Waktu / Jam</label>
                                        <input type="text" class="form-control" name="time_of_occurrence" placeholder="Misal: 08:30">
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="row g-3 mb-3">
                            <div class="col-md-6">
                                <label class="form-label">Judul / Singkatan Kasus <span class="text-danger">*</span></label>
                                <input type="text" class="form-control" name="case_title" placeholder="Contoh: Terlambat Masuk Kelas" required>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">Jenis Kasus <span class="text-danger">*</span></label>
                                <select class="form-select" name="case_type" required>
                                    <option value="pelanggaran">Pelanggaran Tata Tertib</option>
                                    <option value="pribadi">Masalah Pribadi</option>
                                    <option value="sosial">Masalah Sosial</option>
                                    <option value="belajar">Masalah Belajar</option>
                                    <option value="karir">Masalah Karir</option>
                                </select>
                            </div>
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Deskripsi Kasus / Kronologi <span class="text-danger">*</span></label>
                            <textarea class="form-control" name="case_description" rows="3" placeholder="Jelaskan detail kasus..." required></textarea>
                        </div>
                        
                        <div class="mb-3">
                            <label class="form-label">Tindakan Penanganan</label>
                            <textarea class="form-control" name="action_taken" rows="2" placeholder="Tindakan apa yang sudah dilakukan?"></textarea>
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Rekomendasi</label>
                            <textarea class="form-control" name="recommendation" rows="2" placeholder="Rekomendasi tindak lanjut..."></textarea>
                        </div>

                        <div class="row g-3 mb-4">
                            <div class="col-md-8">
                                <label class="form-label">Bukti Media (Foto / Video)</label>
                                <div class="file-upload-wrapper">
                                    <input type="file" name="evidence" accept="image/*,video/*" onchange="updateFileName(this)">
                                    <div class="file-upload-text">Drag & drop or <span class="browse-link">browse</span></div>
                                </div>
                                <small class="text-muted d-block mt-1">Mendukung format gambar dan video maks 20MB.</small>
                            </div>
                            <div class="col-md-4">
                                <label class="form-label">Status Penanganan <span class="text-danger">*</span></label>
                                <select class="form-select" name="status" required>
                                    <option value="proses">Sedang Diproses</option>
                                    <option value="selesai">Selesai / Tuntas</option>
                                    <option value="tindak_lanjut">Perlu Tindak Lanjut</option>
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

    {{-- Modal Edit Kasus --}}
    <div class="modal fade kasus-modal" id="modalEditKasus" tabindex="-1" data-bs-backdrop="static" data-bs-keyboard="false">
        <div class="modal-dialog modal-dialog-centered modal-lg">
            <div class="modal-content">
                <form id="formEditKasus" method="POST" enctype="multipart/form-data">
                    @csrf
                    <div class="modal-header modal-header-custom">
                        <h5 class="modal-title fw-bold"><i class="bi bi-pencil-square me-2"></i>Edit Data Kasus</h5>
                        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" style="filter: brightness(0) invert(1); opacity: 0.8; position: relative; z-index: 2;"></button>
                    </div>
                    <div class="modal-body p-4">
                        <div class="form-section-label"><i class="bi bi-person"></i>Identitas Siswa</div>
                        <div class="row g-3 mb-3">
                            <div class="col-md-6">
                                <label class="form-label">Tanggal Kasus <span class="text-danger">*</span></label>
                                <input type="date" class="form-control" name="case_date" id="edit_case_date" required>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">Status Penanganan</label>
                                <select class="form-select" name="status" id="edit_status" required>
                                    <option value="proses">Sedang Diproses</option>
                                    <option value="selesai">Selesai / Tuntas</option>
                                    <option value="tindak_lanjut">Perlu Tindak Lanjut</option>
                                </select>
                            </div>
                        </div>

                        <div class="form-section-label"><i class="bi bi-journal-text"></i>Detail Kejadian</div>
                        <div class="row g-3 mb-3">
                            <div class="col-md-6">
                                <label class="form-label">Guru Pelapor <span class="text-danger">*</span></label>
                                <input type="text" class="form-control" name="reporter_teacher" id="edit_reporter_teacher" required>
                            </div>
                            <div class="col-md-6">
                                <div class="row g-3">
                                    <div class="col-md-6">
                                        <label class="form-label">Mata Pelajaran</label>
                                        <input type="text" class="form-control" name="subject_name" id="edit_subject_name">
                                    </div>
                                    <div class="col-md-6">
                                        <label class="form-label">Waktu / Jam</label>
                                        <input type="text" class="form-control" name="time_of_occurrence" id="edit_time_of_occurrence">
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="row g-3 mb-3">
                            <div class="col-md-6">
                                <label class="form-label">Judul / Singkatan Kasus <span class="text-danger">*</span></label>
                                <input type="text" class="form-control" name="case_title" id="edit_case_title" required>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">Jenis Kasus <span class="text-danger">*</span></label>
                                <select class="form-select" name="case_type" id="edit_case_type" required>
                                    <option value="pelanggaran">Pelanggaran Tata Tertib</option>
                                    <option value="pribadi">Masalah Pribadi</option>
                                    <option value="sosial">Masalah Sosial</option>
                                    <option value="belajar">Masalah Belajar</option>
                                    <option value="karir">Masalah Karir</option>
                                </select>
                            </div>
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Deskripsi Kasus / Kronologi <span class="text-danger">*</span></label>
                            <textarea class="form-control" name="case_description" id="edit_case_description" rows="3" required></textarea>
                        </div>
                        
                        <div class="mb-3">
                            <label class="form-label">Tindakan Penanganan</label>
                            <textarea class="form-control" name="action_taken" id="edit_action_taken" rows="2"></textarea>
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Rekomendasi</label>
                            <textarea class="form-control" name="recommendation" id="edit_recommendation" rows="2"></textarea>
                        </div>

                        <div class="mb-4">
                            <label class="form-label">Bukti Media Baru (Opsional)</label>
                            <div class="file-upload-wrapper">
                                <input type="file" name="evidence" accept="image/*,video/*" onchange="updateFileName(this)">
                                <div class="file-upload-text">Drag & drop or <span class="browse-link">browse</span></div>
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
    <!-- End Header -->

    <!-- ======= Sidebar ======= -->
    @include('include.sidebar')

    <main id="main" class="main">
        <div class="pagetitle">
            <h1>Buku Kasus / Pelanggaran Siswa</h1>
            <nav>
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="{{ url('/') }}">Beranda</a></li>
                    <li class="breadcrumb-item active">Buku Kasus</li>
                </ol>
            </nav>
        </div><!-- End Page Title -->

        <section class="section">
            <div class="row">
                <div class="col-lg-12">
                    <div class="card border-0 shadow-sm rounded-3">
                        <div class="card-body p-4">
                            <div class="d-flex align-items-center justify-content-between pb-3 mb-4 border-bottom">
                                <h5 class="card-title fw-bold text-dark p-0 m-0">Data Catatan Pelanggaran</h5>
                                <button type="button" class="btn btn-primary fw-semibold shadow-sm" data-bs-toggle="modal" data-bs-target="#modalTambahKasus">
                                    <i class="bi bi-plus-circle me-1"></i>Catat Pelanggaran Baru
                                </button>
                            </div>

                            {{-- Search Bar --}}
                            <div class="mb-3">
                                <div class="input-group" style="max-width: 360px;">
                                    <span class="input-group-text bg-white border-end-0"><i class="bi bi-search text-muted"></i></span>
                                    <input type="text" id="searchStudiKasus" class="form-control border-start-0 ps-0" placeholder="Cari nama siswa, judul kasus, atau status...">
                                </div>
                            </div>

                            <div class="table-responsive" style="min-height: 350px;">
                                <table class="table table-hover align-middle" id="tabelStudiKasus">
                                    <thead class="table-light">
                                        <tr>
                                            <th>No</th>
                                            <th>Siswa & Kelas</th>
                                            <th>Kasus & Pelapor</th>
                                            <th>Tanggal</th>
                                            <th>Status & Sanksi</th>
                                            <th class="text-center" style="width: 15%;">Aksi</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @forelse ($datastudykasus as $index => $data)
                                            <tr>
                                                <td>{{ $index + 1 }}</td>
                                                <td>
                                                    <div class="fw-bold text-dark">{{ $data->student->full_name ?? 'N/A' }}</div>
                                                    <small class="text-muted"><i class="bi bi-person-badge me-1"></i>{{ $data->student->class?->grade ?? '-' }} - {{ $data->student->class?->school_class_name ?? 'Tanpa Kelas' }}</small>
                                                </td>
                                                <td>
                                                    <div class="fw-bold text-dark text-truncate" style="max-width: 250px;" title="{{ $data->case_title }}">{{ $data->case_title }}</div>
                                                    <div class="mt-1">
                                                        <span class="badge bg-secondary me-1">{{ ucfirst($data->case_type) }}</span>
                                                        <small class="text-muted"><i class="bi bi-person-workspace me-1"></i>{{ $data->reporter_teacher ?? '-' }}</small>
                                                    </div>
                                                </td>
                                                <td>
                                                    <small class="text-muted fw-semibold">{{ \Carbon\Carbon::parse($data->case_date)->format('d M Y') }}</small>
                                                </td>
                                                <td>
                                                    @if(strtolower($data->status) == 'selesai')
                                                        <span class="badge bg-success bg-opacity-10 text-success border border-success px-2 py-1 d-inline-block mb-1"><i class="bi bi-check-all me-1"></i>Selesai</span>
                                                    @elseif(strtolower($data->status) == 'tindak_lanjut')
                                                        <span class="badge bg-warning bg-opacity-10 text-warning border border-warning px-2 py-1 d-inline-block mb-1"><i class="bi bi-arrow-right-circle me-1"></i>Tindak Lanjut</span>
                                                    @else
                                                        <span class="badge bg-primary bg-opacity-10 text-primary border border-primary px-2 py-1 d-inline-block mb-1"><i class="bi bi-clock-history me-1"></i>Proses</span>
                                                    @endif

                                                    @if($data->points_applied)
                                                        <span class="badge bg-danger bg-opacity-10 text-danger border border-danger px-2 py-1 d-inline-block"><i class="bi bi-exclamation-triangle-fill me-1"></i>+{{ $data->points_sanction }} Poin</span>
                                                    @else
                                                        <span class="badge bg-secondary bg-opacity-10 text-secondary border border-secondary px-2 py-1 d-inline-block"><i class="bi bi-dash-circle me-1"></i>Belum Diproses</span>
                                                    @endif
                                                </td>
                                                <td class="text-center">
                                                    <div class="dropdown d-inline-block">
                                                        <button class="btn btn-sm btn-outline-secondary dropdown-toggle px-3" type="button" data-bs-toggle="dropdown" data-bs-boundary="viewport" data-bs-popper-config='{"strategy":"fixed"}' aria-expanded="false">
                                                            <i class="bi bi-gear me-1"></i> Aksi
                                                        </button>
                                                        <ul class="dropdown-menu dropdown-menu-end shadow-sm" style="max-height: 250px; overflow-y: auto;">
                                                            <li>
                                                                <button type="button" class="dropdown-item d-flex align-items-center py-2" data-bs-toggle="modal" data-bs-target="#modalDetailKasus{{ $data->id }}">
                                                                    <i class="bi bi-eye text-info me-2"></i> Detail Kasus
                                                                </button>
                                                            </li>
                                                            @if(strtolower($data->status) != 'selesai')
                                                                <li>
                                                                    <button type="button" class="dropdown-item d-flex align-items-center py-2" data-bs-toggle="modal" data-bs-target="#modalSelesaikanKasus{{ $data->id }}">
                                                                        <i class="bi bi-check-circle text-success me-2"></i> Selesaikan Kasus
                                                                    </button>
                                                                </li>
                                                                <li>
                                                                    <a class="dropdown-item d-flex align-items-center py-2" href="{{ route('counseling.index', ['student_id' => $data->student_id, 'topic' => 'Kasus: ' . $data->case_title, 'action' => 'schedule', 'case_study_id' => $data->id]) }}">
                                                                        <i class="bi bi-calendar-plus text-primary me-2"></i> Jadwal Konseling
                                                                    </a>
                                                                </li>
                                                            @endif
                                                            
                                                            @if(!$data->points_applied)
                                                                <li>
                                                                    <button type="button" class="dropdown-item d-flex align-items-center py-2 text-danger" data-bs-toggle="modal" data-bs-target="#modalSanksiPoin{{ $data->id }}">
                                                                        <i class="bi bi-exclamation-triangle text-danger me-2"></i> Proses Sanksi Poin
                                                                    </button>
                                                                </li>
                                                            @endif

                                                            <li>
                                                                <a class="dropdown-item d-flex align-items-center py-2" href="{{ route('studykasus.pdf', $data->id) }}" target="_blank">
                                                                    <i class="bi bi-printer text-secondary me-2"></i> Cetak Laporan
                                                                </a>
                                                            </li>
                                                            <li><hr class="dropdown-divider"></li>
                                                            <li>
                                                                <a class="dropdown-item d-flex align-items-center py-2 text-warning" href="javascript:void(0)" onclick="openEditModalKasus({{ $data->id }}, '{{ $data->student_id }}', '{{ $data->case_date }}', '{{ addslashes($data->reporter_teacher) }}', '{{ addslashes($data->subject_name) }}', '{{ addslashes($data->time_of_occurrence) }}', '{{ addslashes($data->case_title) }}', '{{ $data->case_type }}', '{{ addslashes(str_replace(["\r", "\n"], ['\r', '\n'], $data->case_description)) }}', '{{ addslashes(str_replace(["\r", "\n"], ['\r', '\n'], $data->action_taken)) }}', '{{ addslashes(str_replace(["\r", "\n"], ['\r', '\n'], $data->recommendation)) }}', '{{ $data->status }}')">
                                                                    <i class="bi bi-pencil-square me-2"></i> Edit
                                                                </a>
                                                            </li>
                                                            <li>
                                                                <a class="dropdown-item d-flex align-items-center py-2 text-danger" href="javascript:void(0)" onclick="swalConfirm('Yakin ingin menghapus catatan pelanggaran ini?', function(){ window.location='{{ url('hapusstudykasus/' . $data->id) }}'; })">
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
                                                    <i class="bi bi-journal-x fs-2 d-block mb-2 opacity-25"></i>
                                                    Belum ada catatan pelanggaran.
                                                </td>
                                            </tr>
                                        @endforelse
                                    </tbody>
                                </table>
                                <p id="noResultStudiKasus" class="text-center text-muted py-3 d-none">Tidak ada data yang cocok dengan pencarian.</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>

        {{-- Modals for each case study --}}
        @foreach ($datastudykasus as $data)
            <!-- Modal Detail Kasus -->
            <div class="modal fade text-start" id="modalDetailKasus{{ $data->id }}" tabindex="-1" aria-hidden="true">
                <div class="modal-dialog modal-dialog-centered modal-lg">
                    <div class="modal-content border-0 shadow-lg rounded-3">
                        <div class="modal-header bg-info text-white p-3 px-4">
                            <h5 class="modal-title fw-bold d-flex align-items-center">
                                <i class="bi bi-info-circle-fill me-2 fs-4"></i>Detail Catatan Kasus Siswa
                            </h5>
                            <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
                        </div>
                        <div class="modal-body p-4">
                            <div class="row g-3 mb-4">
                                <div class="col-md-6 border-end">
                                    <small class="text-muted d-block text-uppercase fw-bold mb-1">Informasi Siswa</small>
                                    <h6 class="fw-bold text-dark mb-0">{{ $data->student->full_name ?? '-' }}</h6>
                                    <small class="text-muted d-block">NIS: {{ $data->student->nis ?? '-' }}</small>
                                    <small class="text-muted d-block mb-2">Kelas: {{ $data->student->class?->grade ?? '-' }} - {{ $data->student->class?->school_class_name ?? 'Tanpa Kelas' }}</small>
                                </div>
                                <div class="col-md-6">
                                    <small class="text-muted d-block text-uppercase fw-bold mb-1">Detail Laporan</small>
                                    <small class="text-muted d-block">Tanggal: <strong class="text-dark">{{ \Carbon\Carbon::parse($data->case_date)->format('d M Y') }}</strong></small>
                                    <small class="text-muted d-block">Pelapor: <strong class="text-dark">{{ $data->reporter_teacher }}</strong></small>
                                    <small class="text-muted d-block">Pelajaran: <strong class="text-dark">{{ $data->subject_name ?: '-' }}</strong> @if($data->time_of_occurrence) (Waktu: {{ $data->time_of_occurrence }}) @endif</small>
                                    <small class="text-muted d-block">Kategori: <span class="badge bg-secondary ms-1">{{ ucfirst($data->case_type) }}</span></small>
                                </div>
                            </div>

                            <div class="mb-3 bg-light p-3 rounded-2">
                                <h6 class="fw-bold text-dark mb-2">Judul Laporan / Kasus</h6>
                                <p class="text-dark mb-0 font-monospace">{{ $data->case_title }}</p>
                            </div>

                            <div class="mb-3">
                                <h6 class="fw-bold text-dark mb-2"><i class="bi bi-card-text text-primary me-1"></i>Keterangan & Kronologi</h6>
                                <div class="border p-3 rounded bg-white text-secondary text-wrap" style="white-space: pre-line;">{{ $data->case_description }}</div>
                            </div>

                            @if($data->evidence_file)
                                <div class="mb-3">
                                    <h6 class="fw-bold text-dark mb-2"><i class="bi bi-image text-danger me-1"></i>Bukti Lampiran Media</h6>
                                    <div class="border p-3 rounded bg-white text-center">
                                        @if(preg_match('/\.(jpg|jpeg|png|webp)$/i', $data->evidence_file))
                                            <img src="{{ asset($data->evidence_file) }}" alt="Bukti Kasus" class="img-fluid rounded shadow-sm" style="max-height: 350px;">
                                        @else
                                            <video src="{{ asset($data->evidence_file) }}" controls class="w-100 rounded shadow-sm" style="max-height: 350px;"></video>
                                        @endif
                                    </div>
                                </div>
                            @endif

                            <div class="row g-3">
                                <div class="col-md-6">
                                    <h6 class="fw-bold text-dark mb-2"><i class="bi bi-shield-check text-success me-1"></i>Tindakan yang Telah Diambil</h6>
                                    <div class="border p-3 rounded bg-white text-secondary text-wrap" style="white-space: pre-line; min-height: 80px;">{{ $data->action_taken ?: 'Belum ada tindakan tercatat.' }}</div>
                                </div>
                                <div class="col-md-6">
                                    <h6 class="fw-bold text-dark mb-2"><i class="bi bi-lightbulb text-warning me-1"></i>Rekomendasi Tindak Lanjut</h6>
                                    <div class="border p-3 rounded bg-white text-secondary text-wrap" style="white-space: pre-line; min-height: 80px;">{{ $data->recommendation ?: 'Belum ada rekomendasi tercatat.' }}</div>
                                </div>
                            </div>

                            <div class="mt-4 pt-3 border-top d-flex justify-content-between align-items-center text-muted small">
                                <div>Diproses oleh BK: <strong>{{ $data->handler?->name ?? 'Guru BK' }}</strong></div>
                                <div>Status Sanksi: 
                                    @if($data->points_applied)
                                        <span class="badge bg-danger">+{{ $data->points_sanction }} Poin Pelanggaran</span>
                                    @else
                                        <span class="badge bg-secondary">Poin Belum Diproses</span>
                                    @endif
                                </div>
                            </div>
                        </div>
                        <div class="modal-footer bg-light">
                            <button type="button" class="btn btn-secondary btn-sm" data-bs-dismiss="modal">Tutup</button>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Modal Selesaikan Kasus -->
            <div class="modal fade text-start" id="modalSelesaikanKasus{{ $data->id }}" tabindex="-1" aria-hidden="true">
                <div class="modal-dialog modal-dialog-centered">
                    <div class="modal-content border-0 shadow-lg rounded-3">
                        <form method="POST" action="{{ route('studykasus.complete', $data->id) }}">
                            @csrf
                            <div class="modal-header bg-success text-white p-3 px-4">
                                <h5 class="modal-title fw-bold d-flex align-items-center">
                                    <i class="bi bi-check-circle-fill me-2 fs-4"></i>Selesaikan Kasus Siswa
                                </h5>
                                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
                            </div>
                            <div class="modal-body p-4">
                                <div class="alert alert-info py-2 px-3 mb-3">
                                    Anda akan mengubah status kasus <strong>"{{ $data->case_title }}"</strong> yang dialami oleh <strong>{{ $data->student->full_name ?? '-' }}</strong> menjadi <strong>Selesai</strong>.
                                </div>
                                
                                <div class="mb-3">
                                    <label class="form-label fw-bold">Tindakan yang Telah Diambil</label>
                                    <textarea name="action_taken" class="form-control" rows="3" placeholder="Tuliskan tindakan konseling atau pembinaan yang telah dilakukan..." required>{{ $data->action_taken }}</textarea>
                                </div>

                                <div class="mb-3">
                                    <label class="form-label fw-bold">Rekomendasi Tindak Lanjut</label>
                                    <textarea name="recommendation" class="form-control" rows="3" placeholder="Tuliskan saran untuk wali kelas, orang tua, atau guru mapel..." required>{{ $data->recommendation }}</textarea>
                                </div>
                            </div>
                            <div class="modal-footer bg-light">
                                <button type="button" class="btn btn-secondary btn-sm" data-bs-dismiss="modal">Batal</button>
                                <button type="submit" class="btn btn-success btn-sm"><i class="bi bi-check-lg me-1"></i>Selesaikan Sekarang</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>

            <!-- Modal Sanksi Poin -->
            <div class="modal fade text-start" id="modalSanksiPoin{{ $data->id }}" tabindex="-1" aria-hidden="true">
                <div class="modal-dialog modal-dialog-centered">
                    <div class="modal-content border-0 shadow-lg rounded-3">
                        <form method="POST" action="{{ route('studykasus.sanction', $data->id) }}">
                            @csrf
                            <div class="modal-header bg-danger text-white p-3 px-4">
                                <h5 class="modal-title fw-bold d-flex align-items-center">
                                    <i class="bi bi-exclamation-triangle-fill me-2 fs-4"></i>Berikan Sanksi Poin Pelanggaran
                                </h5>
                                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
                            </div>
                            <div class="modal-body p-4">
                                <div class="alert alert-warning py-2 px-3 mb-3">
                                    Anda akan memproses sanksi poin pelanggaran atas laporan kasus <strong>"{{ $data->case_title }}"</strong> siswa <strong>{{ $data->student->full_name ?? '-' }}</strong>.
                                </div>
                                
                                <div class="mb-3">
                                    <label class="form-label fw-bold">Jumlah Sanksi Poin Pelanggaran</label>
                                    <input type="number" name="points_sanction" class="form-control" min="1" placeholder="Masukkan jumlah poin (misal: 10, 15, 20)" required>
                                    <small class="text-muted">Poin ini akan otomatis dicatat pada menu <strong>Data Poin Pelanggaran</strong> dan diakumulasikan ke total skor poin siswa.</small>
                                </div>
                            </div>
                            <div class="modal-footer bg-light">
                                <button type="button" class="btn btn-secondary btn-sm" data-bs-dismiss="modal">Batal</button>
                                <button type="submit" class="btn btn-danger btn-sm"><i class="bi bi-check-lg me-1"></i>Terapkan Sanksi Poin</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        @endforeach

    </main><!-- End #main -->

    <!-- ======= Footer ======= -->
    @include('include.footer')

    <a href="#" class="back-to-top d-flex align-items-center justify-content-center"><i class="bi bi-arrow-up-short"></i></a>
    @include('include.script')

    <script>
        function openEditModalKasus(id, student_id, case_date, reporter, subject, time, title, type, desc, action, rec, status) {
            document.getElementById('formEditKasus').action = '/updatestudykasus/' + id;
            document.getElementById('edit_case_date').value = case_date.split(' ')[0];
            document.getElementById('edit_reporter_teacher').value = reporter;
            document.getElementById('edit_subject_name').value = subject;
            document.getElementById('edit_time_of_occurrence').value = time;
            document.getElementById('edit_case_title').value = title;
            document.getElementById('edit_case_type').value = type;
            document.getElementById('edit_case_description').value = desc.replace(/\\n/g, '\n').replace(/\\r/g, '\r');
            document.getElementById('edit_action_taken').value = action.replace(/\\n/g, '\n').replace(/\\r/g, '\r');
            document.getElementById('edit_recommendation').value = rec.replace(/\\n/g, '\n').replace(/\\r/g, '\r');
            document.getElementById('edit_status').value = status;
            
            // Reset file inputs UI on open
            document.querySelectorAll('.file-upload-wrapper input[type="file"]').forEach(input => {
                input.value = '';
                updateFileName(input);
            });

            new bootstrap.Modal(document.getElementById('modalEditKasus')).show();
        }

        // File upload UI updater
        function updateFileName(input) {
            const textDiv = input.nextElementSibling;
            if (input.files && input.files.length > 0) {
                textDiv.innerHTML = `<span class='fw-semibold text-primary'><i class='bi bi-file-earmark-check-fill me-1'></i> ${input.files[0].name}</span>`;
                input.parentElement.style.borderColor = '#4154f1';
                input.parentElement.style.backgroundColor = '#eef2ff';
            } else {
                textDiv.innerHTML = `Drag & drop or <span class='browse-link'>browse</span>`;
                input.parentElement.style.borderColor = '#a0b5e8';
                input.parentElement.style.backgroundColor = '#f8fbff';
            }
        }
    </script>
</body>

</html>
