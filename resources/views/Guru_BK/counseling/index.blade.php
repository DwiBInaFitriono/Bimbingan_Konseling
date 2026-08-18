<!DOCTYPE html>
<html lang="en">

<head>
    @include('include.style')
    <style>
        .modal-content {
            border: none;
            border-radius: 18px;
            overflow: hidden;
            box-shadow: 0 25px 60px rgba(33, 51, 99, 0.25);
        }
        .modal-header-custom {
            background: linear-gradient(135deg, #4154f1 0%, #7c3aed 100%);
            color: white;
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
            height: 20px;
            background: #fff;
            border-radius: 20px 20px 0 0;
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
        .form-control, .form-select {
            border-radius: 8px;
            border: 1px solid #ced4da;
            padding: 10px 14px;
            font-size: 0.9rem;
            color: #495057;
            transition: all 0.2s ease;
        }
        .form-control:focus, .form-select:focus {
            border-color: #667eea;
            box-shadow: 0 0 0 0.2rem rgba(102, 126, 234, 0.25);
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
        .type-cards {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 12px;
        }
        .type-card {
            border: 2px solid #eef1f8;
            border-radius: 12px;
            padding: 14px;
            cursor: pointer;
            transition: all 0.2s;
            display: flex;
            align-items: center;
            gap: 12px;
            background: #fff;
        }
        .type-card:hover {
            border-color: #c4cfff;
            background: #f8f9ff;
        }
        .type-card.active {
            border-color: #4154f1;
            background: #f0f4ff;
        }
        .type-card .type-icon {
            width: 40px;
            height: 40px;
            border-radius: 10px;
            background: #f0f4ff;
            color: #4154f1;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.2rem;
            transition: all 0.2s;
        }
        .type-card.active .type-icon {
            background: #4154f1;
            color: #fff;
        }
        .type-card .type-title {
            font-weight: 700;
            font-size: 0.95rem;
            color: #2c3e50;
            margin-bottom: 2px;
        }
        .type-card .type-desc {
            font-size: 0.75rem;
            color: #6c757d;
        }
        .req { color: #ef4444; margin-left: 2px; }
        .student-option:hover, .student-option.selected {
            background-color: #f0f4ff;
            border-left: 4px solid #4154f1;
        }
        @keyframes pulse-glow {
            0%, 100% { opacity: 1; }
            50% { opacity: 0.6; }
        }
        .form-control:disabled,
        .form-select:disabled,
        .choices.is-disabled,
        .choices.is-disabled .choices__inner,
        .choices.is-disabled .choices__input,
        .choices.is-disabled .choices__list,
        .choices.is-disabled .choices__item {
            background-color: #e9ecef !important;
            color: #495057 !important;
            opacity: 1 !important;
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


        {{-- Queue Cards --}}
        <section class="section mb-4">
            <div class="row g-3">
                <div class="col-md-6">
                    <div class="card h-100 border-0 shadow-sm overflow-hidden" style="border-radius: 14px;">
                        <div class="card-body p-0">
                            <div style="background: linear-gradient(135deg, #10b981 0%, #059669 100%); padding: 14px 20px 12px;">
                                <div class="d-flex align-items-center justify-content-between">
                                    <h6 class="m-0 text-white fw-bold" style="font-size: 0.85rem; letter-spacing: 0.3px;">
                                        <i class="bi bi-broadcast me-1"></i> SEDANG KONSELING
                                    </h6>
                                    @if($currentQueue)
                                        <span class="badge bg-white bg-opacity-25 text-white" style="font-size: 0.7rem; animation: pulse-glow 2s infinite;">
                                            <i class="bi bi-circle-fill me-1" style="font-size: 6px; color: #6ee7b7;"></i>Berlangsung
                                        </span>
                                    @endif
                                </div>
                            </div>
                            <div class="p-3" id="currentQueueCardContainer">
                                @if($currentQueue)
                                    <div class="d-flex align-items-center gap-3">
                                        <div class="d-flex align-items-center justify-content-center flex-shrink-0" style="width: 52px; height: 52px; border-radius: 14px; background: linear-gradient(135deg, #d1fae5, #a7f3d0); color: #059669; font-weight: 800; font-size: 1.25rem;">
                                            {{ $currentQueue->no_antrian ?? '-' }}
                                        </div>
                                        <div class="flex-grow-1 min-width-0">
                                            <h6 class="fw-bold mb-0 text-dark text-truncate" style="font-size: 0.95rem;">{{ $currentQueue->student->full_name ?? 'Siswa' }}</h6>
                                            <div class="d-flex align-items-center gap-2 mt-1 flex-wrap">
                                                @if($currentQueue->student && $currentQueue->student->class)
                                                    <span class="badge bg-light text-dark border" style="font-size: 0.68rem; font-weight: 600;">
                                                        {{ $currentQueue->student->class->school_class_name }}
                                                    </span>
                                                @endif
                                                <span class="text-muted" style="font-size: 0.75rem;"><i class="bi bi-chat-dots me-1"></i>{{ Str::limit($currentQueue->topic, 30) }}</span>
                                            </div>
                                        </div>
                                        <div class="text-end flex-shrink-0">
                                            <div class="d-inline-flex align-items-center gap-1 px-2 py-1" style="background: #ecfdf5; border-radius: 8px; border: 1px solid #a7f3d0;">
                                                <i class="bi bi-clock" style="font-size: 0.7rem; color: #059669;"></i>
                                                <span style="font-size: 0.8rem; font-weight: 700; color: #059669;">{{ substr($currentQueue->waktu_perkiraan ?? $currentQueue->requested_time, 0, 5) }}</span>
                                            </div>
                                        </div>
                                    </div>
                                @else
                                    <div class="text-center py-3">
                                        <div class="d-inline-flex align-items-center justify-content-center mb-2" style="width: 44px; height: 44px; border-radius: 12px; background: #f0fdf4;">
                                            <i class="bi bi-person-video3 text-success" style="font-size: 1.2rem; opacity: 0.5;"></i>
                                        </div>
                                        <p class="text-muted mb-0" style="font-size: 0.82rem;">Tidak ada sesi konseling yang sedang berlangsung.</p>
                                    </div>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-md-6">
                    <div class="card h-100 border-0 shadow-sm overflow-hidden" style="border-radius: 14px;">
                        <div class="card-body p-0">
                            <div style="background: linear-gradient(135deg, #3b82f6 0%, #6366f1 100%); padding: 14px 20px 12px;">
                                <div class="d-flex align-items-center justify-content-between">
                                    <h6 class="m-0 text-white fw-bold" style="font-size: 0.85rem; letter-spacing: 0.3px;">
                                        <i class="bi bi-skip-forward-fill me-1"></i> BERIKUTNYA
                                    </h6>
                                    @if($nextQueue)
                                        <span class="badge bg-white bg-opacity-25 text-white" style="font-size: 0.7rem;">
                                            <i class="bi bi-hourglass-split me-1" style="font-size: 0.6rem;"></i>Menunggu
                                        </span>
                                    @endif
                                </div>
                            </div>
                            <div class="p-3" id="nextQueueCardContainer">
                                @if($nextQueue)
                                    <div class="d-flex align-items-center gap-3">
                                        <div class="d-flex align-items-center justify-content-center flex-shrink-0" style="width: 52px; height: 52px; border-radius: 14px; background: linear-gradient(135deg, #dbeafe, #bfdbfe); color: #2563eb; font-weight: 800; font-size: 1.25rem;">
                                            {{ $nextQueueNumber ?? '-' }}
                                        </div>
                                        <div class="flex-grow-1 min-width-0">
                                            <h6 class="fw-bold mb-0 text-dark text-truncate" style="font-size: 0.95rem;">{{ $nextQueue->student->full_name ?? 'Siswa' }}</h6>
                                            <div class="d-flex align-items-center gap-2 mt-1 flex-wrap">
                                                @if($nextQueue->student && $nextQueue->student->class)
                                                    <span class="badge bg-light text-dark border" style="font-size: 0.68rem; font-weight: 600;">
                                                        {{ $nextQueue->student->class->school_class_name }}
                                                    </span>
                                                @endif
                                                <span class="text-muted" style="font-size: 0.75rem;"><i class="bi bi-chat-dots me-1"></i>{{ Str::limit($nextQueue->topic, 30) }}</span>
                                            </div>
                                        </div>
                                        <div class="text-end flex-shrink-0">
                                            <div class="d-inline-flex align-items-center gap-1 px-2 py-1" style="background: #eff6ff; border-radius: 8px; border: 1px solid #bfdbfe;">
                                                <i class="bi bi-clock" style="font-size: 0.7rem; color: #2563eb;"></i>
                                                <span style="font-size: 0.8rem; font-weight: 700; color: #2563eb;">{{ substr($nextQueue->waktu_perkiraan ?? $nextQueue->requested_time, 0, 5) }}</span>
                                            </div>
                                        </div>
                                    </div>
                                @else
                                    <div class="text-center py-3">
                                        <div class="d-inline-flex align-items-center justify-content-center mb-2" style="width: 44px; height: 44px; border-radius: 12px; background: #eff6ff;">
                                            <i class="bi bi-person-walking text-primary" style="font-size: 1.2rem; opacity: 0.5;"></i>
                                        </div>
                                        <p class="text-muted mb-0" style="font-size: 0.82rem;">Belum ada antrian konseling berikutnya hari ini.</p>
                                    </div>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>

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
                                    <h3 class="fw-bold mb-0 text-dark" id="pendingCountBadge">{{ $pendingCount }}</h3>
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
                                    <h3 class="fw-bold mb-0 text-dark" id="approvedCountBadge">{{ $approvedCount }}</h3>
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
                                    <h3 class="fw-bold mb-0 text-dark" id="completedCountBadge">{{ $completedCount }}</h3>
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
                                            <tr data-search-name="{{ strtolower($session->student?->full_name ?? '') }}">
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
                                                        <small class="text-muted fw-semibold"><i class="bi bi-calendar3 me-1 text-primary"></i>{{ $session->requested_date ? \Carbon\Carbon::parse($session->requested_date)->format('d M Y') : '-' }} - {{ $session->requested_time ? \Carbon\Carbon::parse($session->requested_time)->format('H:i') : '-' }} WIB</small>
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
                                                                 <form id="delete-form-counseling-{{ $session->id }}" action="{{ route('counseling.destroy', $session->id) }}" method="POST" class="d-none">
                                                                     @csrf
                                                                     @method('DELETE')
                                                                 </form>
                                                                 <a class="dropdown-item d-flex align-items-center py-2 text-danger" href="javascript:void(0)" onclick="swalConfirm('Yakin ingin menghapus jadwal konseling ini?', function(){ document.getElementById('delete-form-counseling-{{ $session->id }}').submit(); })">
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

    {{-- Modal Tambah Konseling --}}
    <div class="modal fade konseling-modal" id="modalTambahKonseling" tabindex="-1" data-bs-backdrop="static" data-bs-keyboard="false">
        <div class="modal-dialog modal-dialog-centered modal-lg modal-dialog-scrollable">
            <div class="modal-content border-0 shadow-lg">
                <form method="POST" action="{{ route('counseling.store.gurubk') }}" id="formTambahKonseling" onsubmit="return validateFormTambah()">
                    @csrf
                    <input type="hidden" name="case_study_id" id="caseStudyIdInput">
                    <div class="modal-header modal-header-custom p-3 px-4">
                        <h5 class="modal-title">
                            <i class="bi bi-calendar-plus-fill fs-4 me-2"></i>Buat Jadwal Konseling Baru
                        </h5>
                        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" style="filter: brightness(0) invert(1); opacity: 0.8; position: relative; z-index: 2;"></button>
                    </div>
                    <div class="modal-body p-4" style="max-height: 70vh; overflow-y: auto;">
                        {{-- Filter Pencarian Siswa --}}
                        <div class="mb-4" id="searchSiswaPanel">
                            <label class="form-label fw-bold text-dark">
                                <i class="bi bi-search text-primary me-1"></i>Pencarian & Pilih Siswa
                            </label>
                                                       {{-- Input & Filter Pencarian Siswa Live --}}
                            <div class="row g-2 mb-2">
                                <div class="col-md-4 col-sm-6">
                                    <select class="form-select text-secondary fw-semibold w-100" id="modalFilterTingkat" onchange="filterSiswaList()">
                                        <option value="">Tingkat: Semua</option>
                                        <option value="kelas 10">Kelas 10 (X)</option>
                                        <option value="kelas 11">Kelas 11 (XI)</option>
                                        <option value="kelas 12">Kelas 12 (XII)</option>
                                    </select>
                                </div>
                                <div class="col-md-4 col-sm-6">
                                    <select class="form-select text-secondary fw-semibold w-100" id="modalFilterJurusan" onchange="filterSiswaList()">
                                        <option value="">Jurusan: Semua</option>
                                        <optgroup label="Rekayasa Perangkat Lunak">
                                            <option value="rpl 1">RPL 1</option>
                                            <option value="rpl 2">RPL 2</option>
                                            <option value="rpl 3">RPL 3</option>
                                        </optgroup>
                                        <optgroup label="Manajemen Perkantoran">
                                            <option value="mp 1">MP 1</option>
                                            <option value="mp 2">MP 2</option>
                                            <option value="mp 3">MP 3</option>
                                        </optgroup>
                                        <optgroup label="Akuntansi">
                                            <option value="ak 1">AK 1</option>
                                            <option value="ak 2">AK 2</option>
                                            <option value="ak 3">AK 3</option>
                                        </optgroup>
                                        <optgroup label="Bisnis Digital">
                                            <option value="bd 1">BD 1</option>
                                            <option value="bd 2">BD 2</option>
                                            <option value="bd 3">BD 3</option>
                                        </optgroup>
                                        <optgroup label="Desain Komunikasi Visual">
                                            <option value="dkv 1">DKV 1</option>
                                            <option value="dkv 2">DKV 2</option>
                                        </optgroup>
                                        <optgroup label="Kriya Kreatif Batik dan Tekstil">
                                            <option value="kkbt 1">KKBT 1</option>
                                            <option value="kkbt 2">KKBT 2</option>
                                        </optgroup>
                                    </select>
                                </div>
                                <div class="col-md-4 col-sm-12">
                                    <div class="input-icon-group">
                                        <i class="bi bi-search"></i>
                                        <input type="text" id="searchSiswaInput" class="form-control" placeholder="Cari nama/NIS..." onkeyup="filterSiswaList()">
                                    </div>
                                </div>
                            </div>

                            {{-- Dropdown / List Hasil Pencarian Siswa --}}
                            <input type="hidden" name="student_id" id="selectedStudentId">
                            <div id="additionalStudentsContainer"></div>
                            
                            <div class="student-select-card" id="studentListContainer">
                                @foreach(\App\Models\Student::with('class')->get() as $s)
                                    <div class="student-option d-flex justify-content-between align-items-center" 
                                         data-student-id="{{ $s->id }}"
                                         onclick="selectStudent('{{ $s->id }}', '{{ addslashes($s->full_name) }}', '{{ $s->nis }}', '{{ $s->class?->grade ?? '' }}', '{{ addslashes($s->class?->school_class_name ?? 'Tanpa Kelas') }}', this)"
                                         data-name="{{ strtolower($s->full_name) }}"
                                         data-tingkat="{{ strtolower('kelas ' . ($s->class?->grade ?? '')) }}"
                                         data-jurusan="{{ strtolower($s->class?->school_class_name ?? '') }}">
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

                        @php
                            $namaGuru = strtolower(Auth::user()->name);
                        @endphp
                        <div class="form-section-label"><i class="bi bi-calendar2-week"></i>Jadwal Pertemuan</div>
                        <div class="row g-3 mb-4">
                                <div class="col-md-6">
                                    <label class="form-label">Tanggal Pertemuan <span class="req">*</span></label>
                                    <input type="date" name="requested_date" id="requestedDateInput" class="form-control" min="{{ date('Y-m-d') }}" onchange="checkSelectedDate(this, 'weekendAlert')" required>
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label">Waktu / Jam Pertemuan <span class="req">*</span></label>
                                    <select name="slot_waktu" id="slotWaktuSelect" class="form-select" required disabled>
                                        <option value="">-- Pilih Tanggal Dahulu --</option>
                                    </select>
                                </div>
                                <div class="col-12 d-none" id="weekendAlert">
                                <div class="alert alert-warning py-2 px-3 mb-0" style="font-size: 0.78rem; border-radius: 8px;">
                                    <i class="bi bi-exclamation-triangle-fill me-1"></i> Maaf, layanan konseling tidak tersedia pada Sabtu/Minggu.
                                </div>
                            </div>
                        </div>

                        <div class="form-section-label"><i class="bi bi-diagram-3"></i>Tipe Konseling</div>
                        <input type="hidden" name="type" id="typeHidden" value="individu">
                        <div class="type-cards mb-4">
                            <div class="type-card active" data-type="individu" onclick="selectType('individu', this)">
                                <div class="type-icon"><i class="bi bi-person"></i></div>
                                <div>
                                    <div class="type-title">Individu</div>
                                    <div class="type-desc">Sesi pribadi 1 lawan 1</div>
                                </div>
                            </div>
                            <div class="type-card" data-type="kelompok" onclick="selectType('kelompok', this)">
                                <div class="type-icon"><i class="bi bi-people"></i></div>
                                <div>
                                    <div class="type-title">Kelompok</div>
                                    <div class="type-desc">Ajak teman bergabung</div>
                                </div>
                            </div>
                        </div>

                        <div class="form-section-label"><i class="bi bi-chat-left-text"></i>Detail Konsultasi</div>
                        <div class="mb-3">
                            <label class="form-label">Topik Bahasan <span class="req">*</span></label>
                            <input type="text" id="topicInput" name="topic" class="form-control" placeholder="Contoh: Kendala belajar, konsultasi karir, masalah pribadi" required>
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Deskripsi Singkat</label>
                            <textarea name="description" class="form-control" rows="3" placeholder="Ceritakan singkat apa yang ingin didiskusikan... (Rahasia dijamin)"></textarea>
                        </div>
                    </div>
                    <div class="modal-footer" style="background:#f8f9ff; border-top:1px solid #eef1f8; padding: 16px 28px;">
                        <button type="button" class="btn btn-secondary px-4 shadow-sm" data-bs-dismiss="modal" style="border-radius:10px;"><i class="bi bi-x-lg me-1" style="font-size: 0.8rem;"></i> Batal</button>
                        <button type="submit" class="btn btn-primary px-4 shadow-sm" style="border-radius:10px; background: #4154f1; border-color: #4154f1;"><i class="bi bi-send me-2"></i>Buat Jadwal</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    @include('include.script')

    <script>
        const namaGuruBK = "{{ strtolower(Auth::user()->name) }}";
        let timeSlots = [];
        
        if (namaGuruBK.includes('rio')) {
            timeSlots.push({ value: "08:00 - 10:00", label: "08:00 - 10:00 WIB", end: "10:00" });
        } else if (namaGuruBK.includes('ratna')) {
            timeSlots.push({ value: "12:00 - 15:00", label: "12:00 - 15:00 WIB", end: "15:00" });
        } else if (namaGuruBK.includes('siti rahma')) {
            timeSlots.push({ value: "08:00 - 15:00", label: "08:00 - 15:00 WIB", end: "15:00" });
        } else {
            timeSlots.push({ value: "08:00 - 10:00", label: "08:00 - 10:00 WIB", end: "10:00" });
            timeSlots.push({ value: "10:00 - 12:00", label: "10:00 - 12:00 WIB", end: "12:00" });
            timeSlots.push({ value: "13:00 - 15:00", label: "13:00 - 15:00 WIB", end: "15:00" });
        }

        function updateSlotWaktuBK() {
            const dateVal = document.getElementById('requestedDateInput').value;
            const slotSelect = document.getElementById('slotWaktuSelect');
            if (!slotSelect) return;

            if (!dateVal) {
                if (slotSelect.choicesObj) {
                    slotSelect.choicesObj.clearChoices();
                    slotSelect.choicesObj.setChoices([{ value: "", label: "-- Pilih Tanggal Dahulu --", selected: true, disabled: true }], 'value', 'label', true);
                    slotSelect.choicesObj.disable();
                } else {
                    slotSelect.innerHTML = '<option value="">-- Pilih Tanggal Dahulu --</option>';
                    slotSelect.disabled = true;
                }
                return;
            }

            const choicesData = [{ value: "", label: "Pilih waktu pertemuan...", selected: true, disabled: true }];

            const now = new Date();
            const todayStr = now.getFullYear() + '-' + String(now.getMonth() + 1).padStart(2, '0') + '-' + String(now.getDate()).padStart(2, '0');
            const currentTimeVal = now.getHours() * 60 + now.getMinutes();

            timeSlots.forEach(slot => {
                const endParts = slot.end.split(':');
                const endTimeVal = parseInt(endParts[0], 10) * 60 + parseInt(endParts[1], 10);
                const isPast = (dateVal === todayStr && currentTimeVal >= endTimeVal);

                choicesData.push({
                    value: slot.value,
                    label: isPast ? `${slot.label} (Lewat)` : slot.label,
                    disabled: isPast,
                    selected: false
                });
            });

            if (slotSelect.choicesObj) {
                slotSelect.choicesObj.clearChoices();
                slotSelect.choicesObj.setChoices(choicesData, 'value', 'label', true);
                slotSelect.choicesObj.enable();
            } else {
                slotSelect.disabled = false;
                slotSelect.innerHTML = '';
                choicesData.forEach(c => {
                    slotSelect.innerHTML += `<option value="${c.value}" ${c.disabled ? 'disabled' : ''}>${c.label}</option>`;
                });
            }
        }

        function validateFormTambah() {
            const studentId = document.getElementById('selectedStudentId').value;
            if (!studentId) {
                swalAlert('Harap cari dan pilih setidaknya satu siswa terlebih dahulu sebelum menyimpan jadwal.', 'warning', 'Peringatan');
                if (typeof event !== 'undefined') {
                    event.preventDefault();
                }
                const form = document.getElementById('formTambahKonseling');
                if (form && window.restoreSubmitButton) {
                    window.restoreSubmitButton(form);
                }
                return false;
            }
            return true;
        }

        function checkSelectedDate(inputElement, alertId) {
            const dateVal = inputElement.value;
            const alertBox = document.getElementById(alertId);
            
            if (!dateVal) {
                if (alertBox) alertBox.classList.add('d-none');
                updateSlotWaktuBK();
                return;
            }

            const date = new Date(dateVal);
            // 0 = Minggu, 6 = Sabtu
            if (date.getDay() === 0 || date.getDay() === 6) {
                if (alertBox) alertBox.classList.remove('d-none');
                inputElement.value = '';
                updateSlotWaktuBK();
            } else {
                if (alertBox) alertBox.classList.add('d-none');
                updateSlotWaktuBK();
            }
        }

        document.getElementById('searchKonseling')?.addEventListener('input', function() {
            const q = this.value.toLowerCase();
            const rows = document.querySelectorAll('#tabelKonseling tbody tr');
            let found = 0;
            rows.forEach(row => {
                const text = (row.getAttribute('data-search-name') || row.textContent).toLowerCase();
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

        function selectType(type, el) {
            document.querySelectorAll('.type-card').forEach(c => c.classList.remove('active'));
            el.classList.add('active');
            document.getElementById('typeHidden').value = type;

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
                let name = opt.getAttribute('data-name');
                let tingkat = opt.getAttribute('data-tingkat');
                let jurusan = opt.getAttribute('data-jurusan');
                
                let matchSearch = name.includes(input) || input === '';
                let matchTingkat = tingkat.includes(fTingkat) || fTingkat === '';
                let matchJurusan = jurusan.includes(fJurusan) || fJurusan === '';
                
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
            const type = document.getElementById('typeHidden')?.value || 'individu';
            if (type === 'individu' && isFollowUpMode && followUpStudentId) {
                filterFollowUpStudentOnly(followUpStudentId);
                return;
            }

            let input = document.getElementById('searchSiswaInput').value.toLowerCase();
            let fTingkat = document.getElementById('modalFilterTingkat').value.toLowerCase();
            let fJurusan = document.getElementById('modalFilterJurusan').value.toLowerCase();
            let options = document.querySelectorAll('.student-option');
            
            options.forEach(opt => {
                let name = opt.getAttribute('data-name');
                let tingkat = opt.getAttribute('data-tingkat');
                let jurusan = opt.getAttribute('data-jurusan');
                
                let matchSearch = name.includes(input) || input === '';
                let matchTingkat = tingkat.includes(fTingkat) || fTingkat === '';
                let matchJurusan = jurusan.includes(fJurusan) || fJurusan === '';
                
                if (matchSearch && matchTingkat && matchJurusan) {
                    opt.classList.remove('d-none');
                    opt.classList.add('d-flex');
                } else {
                    opt.classList.remove('d-flex');
                    opt.classList.add('d-none');
                }
            });
        }



        function selectStudent(id, name, nis, grade, className, element) {
            const type = document.getElementById('typeHidden').value;

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
            document.getElementById('selectedStudentDisplay').classList.add('d-none');
            
            // Reset Jam Pertemuan
            const timeSelect = document.getElementById('requestedTimeSelect');
            if (timeSelect) {
                if (timeSelect.choicesObj) {
                    timeSelect.choicesObj.setChoices([{value: '', label: 'Pilih Tanggal Dahulu...', selected: true}], 'value', 'label', true);
                    timeSelect.choicesObj.disable();
                } else {
                    timeSelect.innerHTML = '<option value="">Pilih Tanggal Dahulu...</option>';
                    timeSelect.disabled = true;
                }
            }
            const weekendAlert = document.getElementById('weekendAlert');
            if (weekendAlert) weekendAlert.classList.add('d-none');
            
            // Reset Tipe Konseling
            document.querySelectorAll('.type-card').forEach(c => c.classList.remove('active'));
            const defaultTypeCard = document.querySelector('.type-card[data-type="individu"]');
            if (defaultTypeCard) defaultTypeCard.classList.add('active');
            const typeHidden = document.getElementById('typeHidden');
            if (typeHidden) typeHidden.value = 'individu';
            selectType('individu', defaultTypeCard || document.querySelector('.type-card'));

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
                        const defaultTypeCard = document.querySelector('.type-card[data-type="individu"]');
                        selectType('individu', defaultTypeCard); // Hide others in individual mode
                        
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
                    const defaultTypeCard = document.querySelector('.type-card[data-type="individu"]');
                    selectType('individu', defaultTypeCard); // Hide others in individual mode
                    
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

        // ===== Real-time Queue Polling =====
        function pollQueueData() {
            fetch(window.location.href, {
                headers: {
                    'Accept': 'application/json',
                    'X-Requested-With': 'XMLHttpRequest'
                }
            })
            .then(res => res.json())
            .then(data => {
                // Update badge statistik
                const pendingBadge = document.getElementById('pendingCountBadge');
                const approvedBadge = document.getElementById('approvedCountBadge');
                const completedBadge = document.getElementById('completedCountBadge');
                
                if (pendingBadge && pendingBadge.innerText != data.pendingCount) pendingBadge.innerText = data.pendingCount;
                if (approvedBadge && approvedBadge.innerText != data.approvedCount) approvedBadge.innerText = data.approvedCount;
                if (completedBadge && completedBadge.innerText != data.completedCount) completedBadge.innerText = data.completedCount;

                // Update Sedang Konseling Card
                const currentContainer = document.getElementById('currentQueueCardContainer');
                if (currentContainer) {
                    const currentSerialized = JSON.stringify(data.currentQueue);
                    if (currentContainer.getAttribute('data-last-json') !== currentSerialized) {
                        let html = '';
                        if (data.currentQueue) {
                            html = `
                                <div class="d-flex align-items-center gap-3">
                                    <div class="d-flex align-items-center justify-content-center flex-shrink-0" style="width: 52px; height: 52px; border-radius: 14px; background: linear-gradient(135deg, #d1fae5, #a7f3d0); color: #059669; font-weight: 800; font-size: 1.25rem;">
                                        ${data.currentQueue.no_antrian}
                                    </div>
                                    <div class="flex-grow-1 min-width-0">
                                        <h6 class="fw-bold mb-0 text-dark text-truncate" style="font-size: 0.95rem;">${data.currentQueue.student_name}</h6>
                                        <div class="d-flex align-items-center gap-2 mt-1 flex-wrap">
                                            ${data.currentQueue.class_name ? `<span class="badge bg-light text-dark border" style="font-size: 0.68rem; font-weight: 600;">${data.currentQueue.class_name}</span>` : ''}
                                            <span class="text-muted" style="font-size: 0.75rem;"><i class="bi bi-chat-dots me-1"></i>${data.currentQueue.topic}</span>
                                        </div>
                                    </div>
                                    <div class="text-end flex-shrink-0">
                                        <div class="d-inline-flex align-items-center gap-1 px-2 py-1" style="background: #ecfdf5; border-radius: 8px; border: 1px solid #a7f3d0;">
                                            <i class="bi bi-clock" style="font-size: 0.7rem; color: #059669;"></i>
                                            <span style="font-size: 0.8rem; font-weight: 700; color: #059669;">${data.currentQueue.waktu_perkiraan}</span>
                                        </div>
                                    </div>
                                </div>`;
                        } else {
                            html = `
                                <div class="text-center py-3">
                                    <div class="d-inline-flex align-items-center justify-content-center mb-2" style="width: 44px; height: 44px; border-radius: 12px; background: #f0fdf4;">
                                        <i class="bi bi-person-video3 text-success" style="font-size: 1.2rem; opacity: 0.5;"></i>
                                    </div>
                                    <p class="text-muted mb-0" style="font-size: 0.82rem;">Tidak ada sesi konseling yang sedang berlangsung.</p>
                                </div>`;
                        }
                        currentContainer.innerHTML = html;
                        currentContainer.setAttribute('data-last-json', currentSerialized);
                    }
                }

                // Update Berikutnya Card
                const nextContainer = document.getElementById('nextQueueCardContainer');
                if (nextContainer) {
                    const nextSerialized = JSON.stringify(data.nextQueue);
                    if (nextContainer.getAttribute('data-last-json') !== nextSerialized) {
                        let html = '';
                        if (data.nextQueue) {
                            html = `
                                <div class="d-flex align-items-center gap-3">
                                    <div class="d-flex align-items-center justify-content-center flex-shrink-0" style="width: 52px; height: 52px; border-radius: 14px; background: linear-gradient(135deg, #dbeafe, #bfdbfe); color: #2563eb; font-weight: 800; font-size: 1.25rem;">
                                        ${data.nextQueue.no_antrian}
                                    </div>
                                    <div class="flex-grow-1 min-width-0">
                                        <h6 class="fw-bold mb-0 text-dark text-truncate" style="font-size: 0.95rem;">${data.nextQueue.student_name}</h6>
                                        <div class="d-flex align-items-center gap-2 mt-1 flex-wrap">
                                            ${data.nextQueue.class_name ? `<span class="badge bg-light text-dark border" style="font-size: 0.68rem; font-weight: 600;">${data.nextQueue.class_name}</span>` : ''}
                                            <span class="text-muted" style="font-size: 0.75rem;"><i class="bi bi-chat-dots me-1"></i>${data.nextQueue.topic}</span>
                                        </div>
                                    </div>
                                    <div class="text-end flex-shrink-0">
                                        <div class="d-inline-flex align-items-center gap-1 px-2 py-1" style="background: #eff6ff; border-radius: 8px; border: 1px solid #bfdbfe;">
                                            <i class="bi bi-clock" style="font-size: 0.7rem; color: #2563eb;"></i>
                                            <span style="font-size: 0.8rem; font-weight: 700; color: #2563eb;">${data.nextQueue.waktu_perkiraan}</span>
                                        </div>
                                    </div>
                                </div>`;
                        } else {
                            html = `
                                <div class="text-center py-3">
                                    <div class="d-inline-flex align-items-center justify-content-center mb-2" style="width: 44px; height: 44px; border-radius: 12px; background: #eff6ff;">
                                        <i class="bi bi-person-walking text-primary" style="font-size: 1.2rem; opacity: 0.5;"></i>
                                    </div>
                                    <p class="text-muted mb-0" style="font-size: 0.82rem;">Belum ada antrian konseling berikutnya hari ini.</p>
                                </div>`;
                        }
                        nextContainer.innerHTML = html;
                        nextContainer.setAttribute('data-last-json', nextSerialized);
                    }
                }
            })
            .catch(err => console.error('Real-time polling error:', err));
        }

        // Jalankan polling setiap 5 detik
        setInterval(pollQueueData, 5000);
    </script>
</body>

</html>
