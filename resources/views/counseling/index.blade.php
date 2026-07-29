<!DOCTYPE html>
<html lang="en">

<head>
    @include('include.style')
    <style>
        .modal-header-custom {
            background: linear-gradient(135deg, #4154f1 0%, #2c38a8 100%);
            color: #ffffff;
            border-top-left-radius: 12px;
            border-top-right-radius: 12px;
        }
        .form-floating-custom {
            position: relative;
        }
        .input-icon-group {
            position: relative;
        }
        .input-icon-group i {
            position: absolute;
            left: 14px;
            top: 50%;
            transform: translateY(-50%);
            color: #899bbd;
            z-index: 10;
        }
        .input-icon-group .form-control,
        .input-icon-group .form-select {
            padding-left: 42px;
            border-radius: 8px;
            border: 1px solid #ced4da;
            transition: all 0.2s ease-in-out;
        }
        .input-icon-group .form-control:focus,
        .input-icon-group .form-select:focus {
            border-color: #4154f1;
            box-shadow: 0 0 0 0.25rem rgba(65, 84, 241, 0.15);
        }
        .student-select-card {
            height: 200px;
            overflow-y: auto;
            border: 1px solid #e0e6ed;
            border-radius: 8px;
        }
        .student-option {
            cursor: pointer;
            padding: 10px 14px;
            border-bottom: 1px solid #f1f3f5;
            transition: background 0.2s;
        }
        .student-option:hover, .student-option.selected {
            background-color: #f0f4ff;
            border-left: 4px solid #4154f1;
        }
    </style>
</head>

<body>
    @include('include.header')
    @include('include.sidebar')

    <main id="main" class="main">
        <div class="pagetitle">
            <h1>Kelola Sesi Konseling Siswa</h1>
            <nav>
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Beranda</a></li>
                    <li class="breadcrumb-item active">Jadwal Konseling</li>
                </ol>
            </nav>
        </div>


        {{-- Stat Cards --}}
        <section class="section">
            <div class="row g-3 mb-4">
                <div class="col-md-4">
                    <div class="card info-card sales-card h-100 mb-0 border-0 shadow-sm">
                        <div class="card-body">
                            <h5 class="card-title text-muted">Menunggu Persetujuan</h5>
                            <div class="d-flex align-items-center">
                                <div class="card-icon rounded-circle d-flex align-items-center justify-content-center bg-warning bg-opacity-10 text-warning" style="width: 54px; height: 54px;">
                                    <i class="bi bi-hourglass-split fs-3"></i>
                                </div>
                                <div class="ps-3">
                                    <h3 class="fw-bold mb-0 text-dark">{{ $pendingCount }}</h3>
                                    <span class="text-muted small">Pengajuan Baru</span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-md-4">
                    <div class="card info-card revenue-card h-100 mb-0 border-0 shadow-sm">
                        <div class="card-body">
                            <h5 class="card-title text-muted">Disetujui / Mendatang</h5>
                            <div class="d-flex align-items-center">
                                <div class="card-icon rounded-circle d-flex align-items-center justify-content-center bg-primary bg-opacity-10 text-primary" style="width: 54px; height: 54px;">
                                    <i class="bi bi-calendar-check fs-3"></i>
                                </div>
                                <div class="ps-3">
                                    <h3 class="fw-bold mb-0 text-dark">{{ $approvedCount }}</h3>
                                    <span class="text-muted small">Jadwal Aktif</span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-md-4">
                    <div class="card info-card customers-card h-100 mb-0 border-0 shadow-sm">
                        <div class="card-body">
                            <h5 class="card-title text-muted">Selesai Konseling</h5>
                            <div class="d-flex align-items-center">
                                <div class="card-icon rounded-circle d-flex align-items-center justify-content-center bg-success bg-opacity-10 text-success" style="width: 54px; height: 54px;">
                                    <i class="bi bi-check2-circle fs-3"></i>
                                </div>
                                <div class="ps-3">
                                    <h3 class="fw-bold mb-0 text-dark">{{ $completedCount }}</h3>
                                    <span class="text-muted small">Konseling Tuntas</span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Table & Actions --}}
            <div class="row">
                <div class="col-12">
                    <div class="card border-0 shadow-sm rounded-3">
                        <div class="card-body pt-3">
                            <div class="d-flex justify-content-between align-items-center mb-3 pb-2 border-bottom">
                                <h5 class="card-title fw-bold text-dark m-0 p-0">Daftar Pengajuan & Jadwal Konseling</h5>
                                <div class="d-flex gap-2">
                                    <a href="{{ route('counseling.report') }}" class="btn btn-outline-danger px-3 py-2 rounded-2 fw-semibold shadow-sm">
                                        <i class="bi bi-file-earmark-pdf me-1"></i>Rekapitulasi Bulanan & PDF
                                    </a>
                                    <button type="button" class="btn btn-primary px-3 py-2 rounded-2 fw-semibold shadow-sm" data-bs-toggle="modal" data-bs-target="#modalTambahKonseling">
                                        <i class="bi bi-plus-circle me-1"></i>Buat Jadwal Baru
                                    </button>
                                </div>
                            </div>

                            {{-- Search Bar --}}
                            <div class="mb-3">
                                <div class="input-group" style="max-width: 360px;">
                                    <span class="input-group-text bg-white border-end-0"><i class="bi bi-search text-muted"></i></span>
                                    <input type="text" id="searchKonseling" class="form-control border-start-0 ps-0" placeholder="Cari jadwal konseling atau siswa...">
                                </div>
                            </div>

                            <div class="table-responsive">
                                <table class="table table-hover align-middle" id="tabelKonseling">
                                    <thead class="table-light">
                                        <tr>
                                            <th>No</th>
                                            <th>Siswa & Kelas</th>
                                            <th>Jadwal & Topik</th>
                                            <th class="text-center text-nowrap">Status</th>
                                            <th class="text-center text-nowrap" style="min-width: 170px;">Aksi</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @forelse($sessions as $session)
                                            <tr>
                                                <td>{{ $loop->iteration }}</td>
                                                <td>
                                                    <div class="fw-bold text-dark">{{ $session->student?->full_name ?? '-' }}</div>
                                                    @if($session->type === 'kelompok' && $session->additionalStudents()->isNotEmpty())
                                                        <div class="my-1 small text-start">
                                                            <span class="text-muted d-block" style="font-size: 0.75rem; font-weight: 600; text-transform: uppercase;">Anggota Kelompok:</span>
                                                            <div class="d-flex flex-wrap gap-1 mt-1">
                                                                @foreach($session->additionalStudents() as $addStudent)
                                                                    <span class="badge bg-light text-dark border px-2 py-1" style="font-size: 0.75rem;">{{ $addStudent->full_name }}</span>
                                                                @endforeach
                                                            </div>
                                                        </div>
                                                    @endif
                                                    <small class="text-muted"><i class="bi bi-person-badge me-1"></i>NIS: {{ $session->student?->nis ?? '-' }} | Kelas {{ $session->student?->class?->grade ?? '-' }} - {{ $session->student?->class?->school_class_name ?? '-' }}</small>
                                                </td>
                                                <td>
                                                    <div class="fw-semibold text-dark text-truncate" style="max-width: 250px;" title="{{ $session->topic }}">{{ $session->topic }}</div>
                                                    @if($session->description)
                                                        <div class="text-muted small text-truncate mb-1" style="max-width: 250px;" title="{{ $session->description }}">{{ Str::limit($session->description, 50) }}</div>
                                                    @endif
                                                    <div>
                                                        <span class="badge bg-secondary bg-opacity-10 text-secondary border px-2 py-1 me-1">{{ ucfirst($session->type) }}</span>
                                                        <small class="text-muted fw-semibold"><i class="bi bi-calendar3 me-1 text-primary"></i>{{ $session->requested_date?->format('d M Y') }} - {{ \Carbon\Carbon::parse($session->requested_time)->format('H:i') }} WIB</small>
                                                    </div>
                                                </td>
                                                <td class="text-center text-nowrap">
                                                    @if($session->status == 'menunggu')
                                                        <span class="badge bg-warning bg-opacity-10 text-warning border border-warning px-2 py-1"><i class="bi bi-clock-history me-1"></i>Menunggu</span>
                                                    @elseif($session->status == 'disetujui')
                                                        <span class="badge bg-primary bg-opacity-10 text-primary border border-primary px-2 py-1"><i class="bi bi-check-circle me-1"></i>Disetujui</span>
                                                    @elseif($session->status == 'selesai')
                                                        <span class="badge bg-success bg-opacity-10 text-success border border-success px-2 py-1"><i class="bi bi-check-all me-1"></i>Selesai</span>
                                                    @elseif($session->status == 'ditolak')
                                                        <span class="badge bg-danger bg-opacity-10 text-danger border border-danger px-2 py-1"><i class="bi bi-x-circle me-1"></i>Ditolak</span>
                                                    @else
                                                        <span class="badge bg-secondary">{{ ucfirst($session->status) }}</span>
                                                    @endif
                                                </td>
                                                <td class="text-center">
                                                     <div class="dropdown d-inline-block">
                                                         <button class="btn btn-sm btn-outline-secondary dropdown-toggle px-3" type="button" data-bs-toggle="dropdown" data-bs-boundary="viewport" data-bs-popper-config='{"strategy":"fixed"}' aria-expanded="false">
                                                             <i class="bi bi-gear me-1"></i> Aksi
                                                         </button>
                                                         <ul class="dropdown-menu dropdown-menu-end shadow-sm" style="max-height: 250px; overflow-y: auto;">
                                                             @if($session->status == 'menunggu')
                                                                 <li>
                                                                     <button type="button" class="dropdown-item d-flex align-items-center py-2 text-success" data-bs-toggle="modal" data-bs-target="#modalSetujui{{ $session->id }}">
                                                                         <i class="bi bi-check-lg me-2"></i> Terima
                                                                     </button>
                                                                 </li>
                                                                 <li>
                                                                     <button type="button" class="dropdown-item d-flex align-items-center py-2 text-danger" data-bs-toggle="modal" data-bs-target="#modalTolak{{ $session->id }}">
                                                                         <i class="bi bi-x-lg me-2"></i> Tolak
                                                                     </button>
                                                                 </li>
                                                             @elseif($session->status == 'disetujui')
                                                                 <li>
                                                                     <button type="button" class="dropdown-item d-flex align-items-center py-2 text-success" data-bs-toggle="modal" data-bs-target="#modalSelesai{{ $session->id }}">
                                                                         <i class="bi bi-check2-square me-2"></i> Selesaikan
                                                                     </button>
                                                                 </li>
                                                             @elseif($session->status == 'selesai')
                                                                 <li>
                                                                     <button type="button" class="dropdown-item d-flex align-items-center py-2 text-primary" onclick="triggerFollowUp('{{ $session->student_id }}', '{{ addslashes($session->student?->full_name) }}', '{{ $session->student?->nis }}', '{{ $session->student?->class?->grade ?? '' }}', '{{ addslashes($session->student?->class?->school_class_name ?? '') }}', 'Kontrol: {{ addslashes($session->topic) }}', '{{ $session->case_study_id }}')">
                                                                         <i class="bi bi-calendar-plus me-2"></i> Jadwal Kontrol
                                                                     </button>
                                                                 </li>
                                                             @endif

                                                             @if(in_array($session->status, ['menunggu', 'disetujui', 'selesai']))
                                                                 <li><hr class="dropdown-divider"></li>
                                                             @endif

                                                             <li>
                                                                 <a class="dropdown-item d-flex align-items-center py-2 text-danger" href="{{ route('counseling.destroy', $session->id) }}" onclick="return confirm('Yakin ingin menghapus jadwal konseling ini?')">
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
                                                    <i class="bi bi-calendar-x fs-2 d-block mb-2 opacity-25"></i>
                                                    Belum ada pengajuan jadwal konseling.
                                                </td>
                                            </tr>
                                        @endforelse
                                    </tbody>
                                </table>

                                <p id="noResultKonseling" class="text-center text-muted py-3 d-none">Tidak ada data yang cocok dengan pencarian.</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>

        {{-- Modals for each session --}}
        @foreach($sessions as $session)
            {{-- Modal Setujui --}}
            <div class="modal fade" id="modalSetujui{{ $session->id }}" tabindex="-1" aria-hidden="true">
                <div class="modal-dialog modal-dialog-centered">
                    <div class="modal-content border-0 shadow">
                        <form method="POST" action="{{ route('counseling.approve', $session->id) }}">
                            @csrf
                            <div class="modal-header bg-success text-white">
                                <h5 class="modal-title fw-bold"><i class="bi bi-check-circle me-2"></i>Setujui Pengajuan Konseling</h5>
                                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
                            </div>
                            <div class="modal-body p-4">
                                <p class="mb-3">Tulis arahan, lokasi pertemuan, atau instruksi awal untuk <strong>{{ $session->student?->full_name }}</strong>:</p>
                                <textarea name="notes" class="form-control" rows="3" placeholder="Contoh: Silakan datang ke Ruang BK pada jam istirahat pertama." required></textarea>
                            </div>
                            <div class="modal-footer bg-light p-3">
                                <button type="button" class="btn btn-secondary px-3" data-bs-dismiss="modal">Batal</button>
                                <button type="submit" class="btn btn-success px-4">Setujui & Kirim Arahan</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>

            {{-- Modal Tolak --}}
            <div class="modal fade" id="modalTolak{{ $session->id }}" tabindex="-1" aria-hidden="true">
                <div class="modal-dialog modal-dialog-centered">
                    <div class="modal-content border-0 shadow">
                        <form method="POST" action="{{ route('counseling.reject', $session->id) }}">
                            @csrf
                            <div class="modal-header bg-danger text-white">
                                <h5 class="modal-title fw-bold"><i class="bi bi-x-circle me-2"></i>Tolak Pengajuan Konseling</h5>
                                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
                            </div>
                            <div class="modal-body p-4">
                                <p class="mb-3">Alasan penolakan pengajuan untuk <strong>{{ $session->student?->full_name }}</strong>:</p>
                                <textarea name="notes" class="form-control" rows="3" placeholder="Masukkan alasan penolakan (misal: Bentrok dengan jadwal rapat sekolah)" required></textarea>
                            </div>
                            <div class="modal-footer bg-light p-3">
                                <button type="button" class="btn btn-secondary px-3" data-bs-dismiss="modal">Batal</button>
                                <button type="submit" class="btn btn-danger px-3">Tolak Pengajuan</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>

            {{-- Modal Selesai --}}
            <div class="modal fade" id="modalSelesai{{ $session->id }}" tabindex="-1" aria-hidden="true">
                <div class="modal-dialog modal-dialog-centered">
                    <div class="modal-content border-0 shadow">
                        <form method="POST" action="{{ route('counseling.complete', $session->id) }}">
                            @csrf
                            <div class="modal-header bg-success text-white">
                                <h5 class="modal-title fw-bold"><i class="bi bi-check-square me-2"></i>Catat Hasil Sesi Konseling</h5>
                                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
                            </div>
                            <div class="modal-body p-4">
                                <div class="mb-3">
                                    <label class="form-label fw-semibold">Siswa</label>
                                    <input type="text" class="form-control bg-light" value="{{ $session->student?->full_name }}" readonly>
                                </div>
                                <div class="mb-3">
                                    <label class="form-label fw-semibold">Topik Konseling</label>
                                    <input type="text" class="form-control bg-light" value="{{ $session->topic }}" readonly>
                                </div>
                                <div class="mb-3">
                                    <label class="form-label fw-semibold">Catatan Rangkuman & Tindak Lanjut</label>
                                    <textarea name="notes" class="form-control" rows="4" placeholder="Tuliskan poin penting pembahasan konseling dan kesepakatan tindak lanjut..." required></textarea>
                                </div>
                            </div>
                            <div class="modal-footer bg-light p-3">
                                <button type="button" class="btn btn-secondary px-3" data-bs-dismiss="modal">Batal</button>
                                <button type="submit" class="btn btn-success px-4">Simpan & Selesaikan</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        @endforeach
    </main>

    {{-- MODAL TAMBAH JADWAL KONSELING BERDESAIN MODERN DENGAN PENCARIAN SISWA --}}
    <div class="modal fade" id="modalTambahKonseling" tabindex="-1">
        <div class="modal-dialog modal-dialog-centered modal-lg">
            <div class="modal-content border-0 shadow-lg rounded-3">
                <form method="POST" action="{{ route('counseling.store') }}">
                    @csrf
                    <input type="hidden" name="case_study_id" id="caseStudyIdInput">
                    <div class="modal-header modal-header-custom p-3 px-4">
                        <h5 class="modal-title fw-bold d-flex align-items-center">
                            <i class="bi bi-calendar-plus-fill fs-4 me-2"></i>Buat Jadwal Konseling Baru
                        </h5>
                        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                    </div>
                    <div class="modal-body p-4">
                        {{-- Filter Pencarian Siswa --}}
                        <div class="mb-4" id="searchSiswaPanel">
                            <label class="form-label fw-bold text-dark">
                                <i class="bi bi-search text-primary me-1"></i>Pencarian & Pilih Siswa
                            </label>
                                                       {{-- Input & Filter Pencarian Siswa Live --}}
                            <div class="row g-2 mb-2">
                                <div class="col-md-4 col-sm-6">
                                    <div class="dropdown w-100">
                                        <button class="form-select text-start text-secondary fw-semibold w-100" type="button" id="modalTingkatBtn" data-bs-toggle="dropdown" aria-expanded="false">
                                            <span>Tingkat: Semua</span>
                                        </button>
                                        <ul class="dropdown-menu w-100 shadow-sm" aria-labelledby="modalTingkatBtn">
                                            <li><a class="dropdown-item fw-semibold py-2" href="javascript:void(0)" onclick="selectModalTingkat('', 'Tingkat: Semua')">Semua Tingkat</a></li>
                                            <li><hr class="dropdown-divider"></li>
                                            <li><a class="dropdown-item py-2" href="javascript:void(0)" onclick="selectModalTingkat('kelas 10', 'Kelas 10 (X)')">Kelas 10 (X)</a></li>
                                            <li><a class="dropdown-item py-2" href="javascript:void(0)" onclick="selectModalTingkat('kelas 11', 'Kelas 11 (XI)')">Kelas 11 (XI)</a></li>
                                            <li><a class="dropdown-item py-2" href="javascript:void(0)" onclick="selectModalTingkat('kelas 12', 'Kelas 12 (XII)')">Kelas 12 (XII)</a></li>
                                        </ul>
                                        <input type="hidden" id="modalFilterTingkat" value="">
                                    </div>
                                </div>
                                <div class="col-md-4 col-sm-6">
                                    <div class="dropdown w-100">
                                        <button class="form-select text-start text-secondary fw-semibold w-100" type="button" id="modalJurusanBtn" data-bs-toggle="dropdown" aria-expanded="false">
                                            <span>Jurusan: Semua</span>
                                        </button>
                                        <ul class="dropdown-menu w-100 shadow-sm" aria-labelledby="modalJurusanBtn" style="max-height: 200px; overflow-y: auto; border: 1px solid #ced4da;">
                                            <li><a class="dropdown-item fw-semibold py-2" href="javascript:void(0)" onclick="selectModalJurusan('', 'Semua Jurusan')">Semua Jurusan & Rombel</a></li>
                                            <li><hr class="dropdown-divider"></li>
                                            
                                            <li><h6 class="dropdown-header fw-bold text-primary pt-2 pb-1">Rekayasa Perangkat Lunak</h6></li>
                                            <li><a class="dropdown-item py-1 ps-4" href="javascript:void(0)" onclick="selectModalJurusan('rpl 1', 'RPL 1')">RPL 1</a></li>
                                            <li><a class="dropdown-item py-1 ps-4" href="javascript:void(0)" onclick="selectModalJurusan('rpl 2', 'RPL 2')">RPL 2</a></li>
                                            <li><a class="dropdown-item py-1 ps-4" href="javascript:void(0)" onclick="selectModalJurusan('rpl 3', 'RPL 3')">RPL 3</a></li>
                                            
                                            <li><h6 class="dropdown-header fw-bold text-primary pt-2 pb-1">Manajemen Perkantoran</h6></li>
                                            <li><a class="dropdown-item py-1 ps-4" href="javascript:void(0)" onclick="selectModalJurusan('mp 1', 'MP 1')">MP 1</a></li>
                                            <li><a class="dropdown-item py-1 ps-4" href="javascript:void(0)" onclick="selectModalJurusan('mp 2', 'MP 2')">MP 2</a></li>
                                            <li><a class="dropdown-item py-1 ps-4" href="javascript:void(0)" onclick="selectModalJurusan('mp 3', 'MP 3')">MP 3</a></li>

                                            <li><h6 class="dropdown-header fw-bold text-primary pt-2 pb-1">Akuntansi</h6></li>
                                            <li><a class="dropdown-item py-1 ps-4" href="javascript:void(0)" onclick="selectModalJurusan('ak 1', 'AK 1')">AK 1</a></li>
                                            <li><a class="dropdown-item py-1 ps-4" href="javascript:void(0)" onclick="selectModalJurusan('ak 2', 'AK 2')">AK 2</a></li>
                                            <li><a class="dropdown-item py-1 ps-4" href="javascript:void(0)" onclick="selectModalJurusan('ak 3', 'AK 3')">AK 3</a></li>

                                            <li><h6 class="dropdown-header fw-bold text-primary pt-2 pb-1">Bisnis Digital</h6></li>
                                            <li><a class="dropdown-item py-1 ps-4" href="javascript:void(0)" onclick="selectModalJurusan('bd 1', 'BD 1')">BD 1</a></li>
                                            <li><a class="dropdown-item py-1 ps-4" href="javascript:void(0)" onclick="selectModalJurusan('bd 2', 'BD 2')">BD 2</a></li>
                                            <li><a class="dropdown-item py-1 ps-4" href="javascript:void(0)" onclick="selectModalJurusan('bd 3', 'BD 3')">BD 3</a></li>

                                            <li><h6 class="dropdown-header fw-bold text-primary pt-2 pb-1">Desain Komunikasi Visual</h6></li>
                                            <li><a class="dropdown-item py-1 ps-4" href="javascript:void(0)" onclick="selectModalJurusan('dkv 1', 'DKV 1')">DKV 1</a></li>
                                            <li><a class="dropdown-item py-1 ps-4" href="javascript:void(0)" onclick="selectModalJurusan('dkv 2', 'DKV 2')">DKV 2</a></li>

                                            <li><h6 class="dropdown-header fw-bold text-primary pt-2 pb-1">Kriya Kreatif Batik dan Tekstil</h6></li>
                                            <li><a class="dropdown-item py-1 ps-4" href="javascript:void(0)" onclick="selectModalJurusan('kkbt 1', 'KKBT 1')">KKBT 1</a></li>
                                            <li><a class="dropdown-item py-1 ps-4" href="javascript:void(0)" onclick="selectModalJurusan('kkbt 2', 'KKBT 2')">KKBT 2</a></li>
                                        </ul>
                                        <input type="hidden" id="modalFilterJurusan" value="">
                                    </div>
                                </div>
                                <div class="col-md-4 col-sm-12">
                                    <div class="input-icon-group">
                                        <i class="bi bi-search"></i>
                                        <input type="text" id="searchSiswaInput" class="form-control" placeholder="Cari nama/NIS..." onkeyup="filterSiswaList()">
                                    </div>
                                </div>
                            </div>

                            {{-- Dropdown / List Hasil Pencarian Siswa --}}
                            <input type="hidden" name="student_id" id="selectedStudentId" required>
                            <div id="additionalStudentsContainer"></div>
                            
                            <div class="student-select-card" id="studentListContainer">
                                @foreach(\App\Models\Student::with('class')->get() as $s)
                                    <div class="student-option d-flex justify-content-between align-items-center" 
                                         data-student-id="{{ $s->id }}"
                                         onclick="selectStudent('{{ $s->id }}', '{{ addslashes($s->full_name) }}', '{{ $s->nis }}', '{{ $s->class?->grade ?? '' }}', '{{ addslashes($s->class?->school_class_name ?? 'Tanpa Kelas') }}', this)"
                                         data-search="{{ strtolower($s->full_name . ' ' . $s->nis . ' ' . ($s->class?->school_class_name ?? '') . ' kelas ' . ($s->class?->grade ?? '')) }}">
                                        <div>
                                            <strong class="text-dark d-block">{{ $s->full_name }}</strong>
                                            <small class="text-muted">NIS: {{ $s->nis }} | Kelas {{ $s->class?->grade ?? '-' }} - {{ $s->class?->school_class_name ?? 'Tanpa Kelas' }}</small>
                                        </div>
                                        <span class="badge bg-primary bg-opacity-10 text-primary border border-primary border-opacity-25 px-2 py-1">Pilih Siswa</span>
                                    </div>
                                @endforeach
                            </div>
                            <div id="selectedStudentDisplay" class="alert alert-info py-2 px-3 mt-2 d-none">
                                <i class="bi bi-check-circle-fill me-2"></i>Siswa Terpilih: <strong id="selectedStudentText"></strong>
                            </div>
                        </div>

                        {{-- Card Detail 1 Siswa untuk Mode Kontrol (Jadwal Kontrol) --}}
                        <div id="singleStudentFollowUpDisplay" class="card border-0 bg-light p-3 mb-4 d-none shadow-sm rounded-3">
                            <div class="d-flex align-items-center">
                                <div class="bg-primary text-white rounded-circle d-flex align-items-center justify-content-center me-3" style="width: 48px; height: 48px;">
                                    <i class="bi bi-person-badge fs-4"></i>
                                </div>
                                <div>
                                    <span class="text-muted d-block small" style="font-size: 0.7rem; font-weight: 700; text-transform: uppercase; letter-spacing: 0.5px;">Siswa Target Pembinaan</span>
                                    <h6 class="fw-bold text-dark mb-1" id="followUpStudentName">Nama Siswa</h6>
                                    <small class="text-muted" id="followUpStudentNis">NIS: -</small>
                                    <span class="badge bg-primary bg-opacity-10 text-primary border border-primary border-opacity-25 px-2 py-0.5 ms-2" id="followUpStudentClass" style="font-size: 0.7rem;">Kelas</span>
                                </div>
                            </div>
                        </div>

                         <div class="row g-3 mb-3">
                            <div class="col-md-6">
                                <label class="form-label fw-semibold">Tanggal Pertemuan</label>
                                <div class="input-icon-group">
                                    <i class="bi bi-calendar-event"></i>
                                    <input type="date" name="requested_date" id="requestedDateInput" class="form-control" min="{{ date('Y-m-d') }}" onchange="checkSelectedDate(this, 'requestedTimeSelect', 'weekendAlert')" required>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label fw-semibold">Waktu / Jam Pertemuan</label>
                                <div class="input-icon-group">
                                    <i class="bi bi-clock"></i>
                                    <select name="requested_time" id="requestedTimeSelect" class="form-select" disabled required>
                                        <option value="">Pilih Tanggal Dahulu...</option>
                                    </select>
                                </div>
                            </div>
                            <div class="col-12 d-none" id="weekendAlert">
                                <div class="alert alert-warning py-2 px-3 mb-0" style="font-size: 0.85rem;">
                                    <i class="bi bi-exclamation-triangle-fill me-2"></i>Layanan konseling hanya tersedia pada hari kerja (Senin - Jumat) jam 07:00 s.d 15:00.
                                </div>
                            </div>
                        </div>

                        <div class="row g-3 mb-3">
                            <div class="col-md-6">
                                <label class="form-label fw-semibold">Tipe Konseling</label>
                                <div class="input-icon-group">
                                    <i class="bi bi-people"></i>
                                    <select name="type" id="counselingTypeSelect" class="form-select" onchange="handleTypeChange()" required>
                                        <option value="individu">Konseling Individu</option>
                                        <option value="kelompok">Konseling Kelompok</option>
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label fw-semibold">Topik Bimbingan</label>
                                <div class="input-icon-group">
                                    <i class="bi bi-chat-left-text"></i>
                                    <input type="text" id="topicInput" name="topic" class="form-control" placeholder="Misal: Konsultasi Karir / Kedisiplinan / Belajar" required>
                                </div>
                            </div>
                        </div>

                        <div class="mb-3">
                            <label class="form-label fw-semibold">Keterangan / Catatan Tambahan</label>
                            <textarea name="description" class="form-control" rows="3" placeholder="Tuliskan catatan khusus atau alasan pembinaan..."></textarea>
                        </div>
                    </div>
                    <div class="modal-footer bg-light p-3 px-4">
                        <button type="button" class="btn btn-secondary px-4 rounded-2" data-bs-dismiss="modal">Batal</button>
                        <button type="submit" class="btn btn-primary px-4 rounded-2 fw-semibold"><i class="bi bi-check-lg me-1"></i>Simpan & Buat Jadwal</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    @include('include.script')

    <script>
        const timeSlots = [
            { value: "07:00", label: "07:00 WIB" },
            { value: "08:00", label: "08:00 WIB" },
            { value: "09:00", label: "09:00 WIB" },
            { value: "10:00", label: "10:00 WIB" },
            { value: "11:00", label: "11:00 WIB" },
            { value: "12:00", label: "12:00 WIB" },
            { value: "13:00", label: "13:00 WIB" },
            { value: "14:00", label: "14:00 WIB" },
            { value: "15:00", label: "15:00 WIB" }
        ];

        function checkSelectedDate(inputElement, timeSelectId, alertId) {
            const dateVal = inputElement.value;
            const timeSelect = document.getElementById(timeSelectId);
            const alertBox = document.getElementById(alertId);
            
            if (!dateVal) {
                timeSelect.innerHTML = '<option value="">Pilih Tanggal Dahulu...</option>';
                timeSelect.disabled = true;
                if (alertBox) alertBox.classList.add('d-none');
                return;
            }

            const date = new Date(dateVal);
            const day = date.getDay(); // 0 = Sunday, 6 = Saturday

            if (day === 0 || day === 6) {
                // It's a weekend
                timeSelect.innerHTML = '<option value="">Jam Tidak Tersedia (Hari Libur)</option>';
                timeSelect.disabled = true;
                if (alertBox) alertBox.classList.remove('d-none');
                inputElement.value = ''; // Reset date
            } else {
                // It's a weekday
                timeSelect.disabled = false;
                if (alertBox) alertBox.classList.add('d-none');
                
                // Populate options
                let html = '<option value="">Pilih Jam...</option>';
                timeSlots.forEach(slot => {
                    html += `<option value="${slot.value}">${slot.label}</option>`;
                });
                timeSelect.innerHTML = html;
            }
        }

        document.getElementById('searchKonseling')?.addEventListener('input', function() {
            const q = this.value.toLowerCase();
            const rows = document.querySelectorAll('#tabelKonseling tbody tr');
            let found = 0;
            rows.forEach(row => {
                const text = row.textContent.toLowerCase();
                const match = text.includes(q);
                row.style.display = match ? '' : 'none';
                if (match) found++;
            });
            const noResult = document.getElementById('noResultKonseling');
            if (noResult) {
                noResult.classList.toggle('d-none', found > 0 || q === '');
            }
        });

        // State variables to track selected students
        let selectedStudentId = '';
        let additionalStudentIds = []; // array of IDs

        // Track follow up / control mode
        let isFollowUpMode = false;
        let followUpStudentId = null;

        function handleTypeChange() {
            const type = document.getElementById('counselingTypeSelect').value;
            const searchPanel = document.getElementById('searchSiswaPanel');
            const singleDisplay = document.getElementById('singleStudentFollowUpDisplay');
            
            if (type === 'individu') {
                // In individual mode:
                // If in follow-up mode, hide search panel and show single student card
                if (isFollowUpMode && followUpStudentId) {
                    if (searchPanel) searchPanel.classList.add('d-none');
                    if (singleDisplay) {
                        singleDisplay.classList.remove('d-none');
                        
                        // Set the single student card details
                        let primaryOpt = document.querySelector(`.student-option[data-student-id="${followUpStudentId}"]`);
                        if (primaryOpt) {
                            document.getElementById('followUpStudentName').innerText = primaryOpt.querySelector('strong').innerText;
                            
                            // Parse NIS and Class from small tag
                            let smallText = primaryOpt.querySelector('small').innerText; // e.g. "NIS: 2024001 | Kelas 10 - X RPL 1"
                            let parts = smallText.split('|');
                            let nisPart = parts[0]?.trim() || '';
                            let classPart = parts[1]?.trim() || '';
                            
                            document.getElementById('followUpStudentNis').innerText = nisPart;
                            document.getElementById('followUpStudentClass').innerText = classPart;
                        }
                    }
                } else {
                    // Show search panel, hide single student card
                    if (searchPanel) searchPanel.classList.remove('d-none');
                    if (singleDisplay) singleDisplay.classList.add('d-none');
                    filterSiswaList();
                }
                
                // If multiple students are selected, keep only the primary one
                if (additionalStudentIds.length > 0) {
                    additionalStudentIds = [];
                    updateStudentSelectionUI();
                }
            } else {
                // In group mode:
                // Show search panel, hide single student card so user can select members
                if (searchPanel) searchPanel.classList.remove('d-none');
                if (singleDisplay) singleDisplay.classList.add('d-none');
                showAllStudentsInList();
            }
        }

        function showAllStudentsInList() {
            let options = document.querySelectorAll('.student-option');
            let input = document.getElementById('searchSiswaInput').value.toLowerCase();
            let fTingkat = document.getElementById('modalFilterTingkat').value.toLowerCase();
            let fJurusan = document.getElementById('modalFilterJurusan').value.toLowerCase();
            
            options.forEach(opt => {
                let text = opt.getAttribute('data-search');
                let matchSearch = text.includes(input) || input === '';
                let matchTingkat = text.includes(fTingkat) || fTingkat === '';
                let matchJurusan = text.includes(fJurusan) || fJurusan === '';
                
                if (matchSearch && matchTingkat && matchJurusan) {
                    opt.classList.remove('d-none');
                    opt.classList.add('d-flex');
                } else {
                    opt.classList.remove('d-flex');
                    opt.classList.add('d-none');
                }
            });
        }

        function filterFollowUpStudentOnly(studentId) {
            let options = document.querySelectorAll('.student-option');
            options.forEach(opt => {
                if (opt.getAttribute('data-student-id') == studentId) {
                    opt.classList.remove('d-none');
                    opt.classList.add('d-flex');
                } else {
                    opt.classList.remove('d-flex');
                    opt.classList.add('d-none');
                }
            });
        }

        function filterSiswaList() {
            const type = document.getElementById('counselingTypeSelect')?.value || 'individu';
            if (type === 'individu' && isFollowUpMode && followUpStudentId) {
                filterFollowUpStudentOnly(followUpStudentId);
                return;
            }

            let input = document.getElementById('searchSiswaInput').value.toLowerCase();
            let fTingkat = document.getElementById('modalFilterTingkat').value.toLowerCase();
            let fJurusan = document.getElementById('modalFilterJurusan').value.toLowerCase();
            let options = document.querySelectorAll('.student-option');
            
            options.forEach(opt => {
                let text = opt.getAttribute('data-search');
                
                let matchSearch = text.includes(input) || input === '';
                let matchTingkat = text.includes(fTingkat) || fTingkat === '';
                let matchJurusan = text.includes(fJurusan) || fJurusan === '';
                
                if (matchSearch && matchTingkat && matchJurusan) {
                    opt.classList.remove('d-none');
                    opt.classList.add('d-flex');
                } else {
                    opt.classList.remove('d-flex');
                    opt.classList.add('d-none');
                }
            });
        }

        function selectModalTingkat(value, label) {
            document.getElementById('modalFilterTingkat').value = value;
            document.getElementById('modalTingkatBtn').querySelector('span').textContent = label;
            filterSiswaList();
        }

        function selectModalJurusan(value, label) {
            document.getElementById('modalFilterJurusan').value = value;
            document.getElementById('modalJurusanBtn').querySelector('span').textContent = label;
            filterSiswaList();
        }

        function selectStudent(id, name, nis, grade, className, element) {
            const type = document.getElementById('counselingTypeSelect').value;

            if (type === 'individu') {
                // Individual Mode: Select exactly one student
                selectedStudentId = id;
                additionalStudentIds = [];
                
                document.querySelectorAll('.student-option').forEach(el => el.classList.remove('selected'));
                if (element) element.classList.add('selected');
            } else {
                // Group Mode: Toggle selection
                if (selectedStudentId === id) {
                    // Deselecting primary student
                    if (additionalStudentIds.length > 0) {
                        selectedStudentId = additionalStudentIds.shift();
                        // Mark new primary student as selected
                        let newPrimaryOpt = document.querySelector(`.student-option[data-student-id="${selectedStudentId}"]`);
                        if (newPrimaryOpt) newPrimaryOpt.classList.add('selected');
                    } else {
                        selectedStudentId = '';
                    }
                    if (element) element.classList.remove('selected');
                } else if (additionalStudentIds.includes(id)) {
                    // Deselecting additional student
                    additionalStudentIds = additionalStudentIds.filter(item => item !== id);
                    if (element) element.classList.remove('selected');
                } else {
                    // Selecting new student
                    if (!selectedStudentId) {
                        selectedStudentId = id;
                        if (element) element.classList.add('selected');
                    } else {
                        additionalStudentIds.push(id);
                        if (element) element.classList.add('selected');
                    }
                }
            }

            updateStudentSelectionUI();
        }

        function updateStudentSelectionUI() {
            // Set hidden inputs
            document.getElementById('selectedStudentId').value = selectedStudentId;
            
            // Build hidden inputs for additional students
            const addContainer = document.getElementById('additionalStudentsContainer');
            if (addContainer) {
                addContainer.innerHTML = '';
                additionalStudentIds.forEach(id => {
                    let input = document.createElement('input');
                    input.type = 'hidden';
                    input.name = 'additional_student_ids[]';
                    input.value = id;
                    addContainer.appendChild(input);
                });
            }

            // Update UI displays
            let display = document.getElementById('selectedStudentDisplay');
            let textSpan = document.getElementById('selectedStudentText');

            if (!selectedStudentId) {
                display.classList.add('d-none');
                textSpan.innerText = '';
                return;
            }

            // Get names from selected option elements
            let primaryOpt = document.querySelector(`.student-option[data-student-id="${selectedStudentId}"]`);
            let primaryName = primaryOpt ? primaryOpt.querySelector('strong').innerText : 'Siswa Utama';
            
            let namesList = [primaryName];
            additionalStudentIds.forEach(id => {
                let opt = document.querySelector(`.student-option[data-student-id="${id}"]`);
                if (opt) {
                    namesList.push(opt.querySelector('strong').innerText);
                }
            });

            textSpan.innerText = namesList.join(', ');
            display.classList.remove('d-none');
        }

        // Reset modal on close
        document.getElementById('modalTambahKonseling')?.addEventListener('hidden.bs.modal', function () {
            this.querySelector('form').reset();
            selectedStudentId = '';
            additionalStudentIds = [];
            isFollowUpMode = false;
            followUpStudentId = null;
            updateStudentSelectionUI();
            
            document.getElementById('searchSiswaInput').value = '';
            document.getElementById('selectedStudentId').value = '';
            document.getElementById('modalFilterTingkat').value = '';
            document.getElementById('modalFilterJurusan').value = '';
            document.getElementById('modalTingkatBtn').querySelector('span').textContent = 'Tingkat: Semua';
            document.getElementById('modalJurusanBtn').querySelector('span').textContent = 'Jurusan: Semua';
            document.getElementById('selectedStudentDisplay').classList.add('d-none');
            
            // RESET PANELS
            document.getElementById('searchSiswaPanel')?.classList.remove('d-none');
            document.getElementById('singleStudentFollowUpDisplay')?.classList.add('d-none');
            
            document.querySelectorAll('.student-option').forEach(opt => {
                opt.classList.remove('d-none');
                opt.classList.add('d-flex');
                opt.classList.remove('selected');
            });
        });

        // Check URL parameters to auto-trigger scheduling
        window.addEventListener('DOMContentLoaded', () => {
            const urlParams = new URLSearchParams(window.location.search);
            const action = urlParams.get('action');
            const studentId = urlParams.get('student_id');
            const topic = urlParams.get('topic');
            const caseStudyId = urlParams.get('case_study_id');

            if (action === 'schedule' && studentId) {
                isFollowUpMode = true;
                followUpStudentId = studentId;

                // Clear URL parameters using History API so they don't trigger the modal again on refresh/redirect
                if (window.history.pushState) {
                    const cleanUrl = window.location.protocol + "//" + window.location.host + window.location.pathname;
                    window.history.pushState({path: cleanUrl}, '', cleanUrl);
                }

                // Find and open the modal
                const modalEl = document.getElementById('modalTambahKonseling');
                if (modalEl) {
                    const modal = new bootstrap.Modal(modalEl);
                    modal.show();

                    // Pre-fill the topic
                    const topicInput = document.getElementById('topicInput');
                    if (topicInput && topic) {
                        topicInput.value = topic;
                    }

                    // Pre-fill the case study ID hidden input
                    const caseStudyInput = document.getElementById('caseStudyIdInput');
                    if (caseStudyInput && caseStudyId) {
                        caseStudyInput.value = caseStudyId;
                    }

                    // Click/Select the student after modal is fully shown
                    modalEl.addEventListener('shown.bs.modal', function () {
                        handleTypeChange(); // Hide others in individual mode
                        
                        const studentOpt = document.querySelector(`.student-option[data-student-id="${studentId}"]`);
                        if (studentOpt) {
                            // Scroll the student list to the selected student
                            studentOpt.scrollIntoView({ block: 'nearest' });
                            studentOpt.click();
                        }
                    }, { once: true });
                }
            }
        });

        function triggerFollowUp(studentId, studentName, studentNis, studentGrade, studentClass, topic, caseStudyId) {
            isFollowUpMode = true;
            followUpStudentId = studentId;

            // 1. Open the modal
            const modalEl = document.getElementById('modalTambahKonseling');
            if (modalEl) {
                const modal = new bootstrap.Modal(modalEl);
                modal.show();

                // 2. Pre-fill the topic
                const topicInput = document.getElementById('topicInput');
                if (topicInput) {
                    topicInput.value = topic;
                }

                // 3. Pre-fill the case study ID hidden input
                const caseStudyInput = document.getElementById('caseStudyIdInput');
                if (caseStudyInput) {
                    caseStudyInput.value = caseStudyId || '';
                }

                // 4. Select the student in the list
                modalEl.addEventListener('shown.bs.modal', function () {
                    handleTypeChange(); // Hide others in individual mode
                    
                    const studentOpt = document.querySelector(`.student-option[data-student-id="${studentId}"]`);
                    if (studentOpt) {
                        studentOpt.scrollIntoView({ block: 'nearest' });
                        studentOpt.click();
                    } else {
                        selectStudent(studentId, studentName, studentNis, studentGrade, studentClass, null);
                    }
                }, { once: true });
            }
        }
    </script>
</body>

</html>
