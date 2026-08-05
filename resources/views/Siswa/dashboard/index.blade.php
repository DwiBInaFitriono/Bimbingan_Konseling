<!DOCTYPE html>
<html lang="en">

<head>
    @include('include.style')
    <style>
        html { overflow-y: scroll; }
        html:has(.modal.show) {
            overflow-y: hidden !important;
            padding-right: 17px !important;
            box-sizing: border-box;
        }
        body.modal-open { overflow: hidden !important; padding-right: 0 !important; }
        /* ===== Stat Cards ===== */
        .stat-card {
            border: none;
            border-radius: 16px;
            overflow: hidden;
            transition: transform 0.2s ease, box-shadow 0.2s ease;
        }
        .stat-card:hover {
            transform: translateY(-4px);
            box-shadow: 0 8px 25px rgba(0,0,0,0.1);
        }
        .stat-card .stat-icon {
            width: 52px;
            height: 52px;
            border-radius: 14px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.4rem;
        }
        .stat-card .stat-number {
            font-size: 1.75rem;
            font-weight: 700;
            line-height: 1.1;
        }
        .stat-card .stat-label {
            font-size: 0.82rem;
            color: #8c939d;
            font-weight: 500;
        }

        /* Status Badge */
        .status-card {
            border: none;
            border-radius: 16px;
            overflow: hidden;
        }
        .status-badge-aman { background: linear-gradient(135deg, #0d9488 0%, #14b8a6 100%); }
        .status-badge-peringatan { background: linear-gradient(135deg, #d97706 0%, #f59e0b 100%); }
        .status-badge-bahaya { background: linear-gradient(135deg, #dc2626 0%, #ef4444 100%); }

        /* Welcome Banner */
        .welcome-banner {
            background: linear-gradient(135deg, #4154f1 0%, #7c3aed 100%);
            border: none;
            border-radius: 16px;
            color: #fff;
        }

        /* Riwayat Cards */
        .riwayat-card {
            border: none;
            border-radius: 16px;
            box-shadow: 0 2px 16px rgba(0,0,0,0.04);
        }
        .riwayat-card .card-header {
            background: #fff;
            border-bottom: 1px solid #f0f0f0;
            border-radius: 16px 16px 0 0 !important;
            padding: 16px 20px;
        }
        .riwayat-card .card-header h6 {
            font-size: 0.95rem;
            font-weight: 700;
            color: #2c3e50;
            margin: 0;
        }
        .riwayat-card .card-body {
            padding: 12px 20px 20px;
        }

        /* Riwayat Item */
        .riwayat-item {
            display: flex;
            align-items: flex-start;
            gap: 12px;
            padding: 10px 0;
            border-bottom: 1px solid #f5f5f5;
        }
        .riwayat-item:last-child {
            border-bottom: none;
        }
        .riwayat-dot {
            width: 10px;
            height: 10px;
            border-radius: 50%;
            flex-shrink: 0;
            margin-top: 5px;
        }
        .riwayat-item .riwayat-title {
            font-size: 0.85rem;
            font-weight: 600;
            color: #2c3e50;
        }
        .riwayat-item .riwayat-meta {
            font-size: 0.75rem;
            color: #8c939d;
        }

        /* Empty State */
        .empty-state {
            text-align: center;
            padding: 24px 16px;
            color: #adb5bd;
        }
        .empty-state i {
            font-size: 1.8rem;
            margin-bottom: 6px;
            display: block;
            opacity: 0.35;
        }
        .empty-state span {
            font-size: 0.8rem;
        }

        /* Tabs */
        .custom-tabs .nav-link {
            border: none;
            background: transparent;
            color: #8c939d;
            font-weight: 600;
            font-size: 0.82rem;
            padding: 8px 16px;
            border-radius: 8px;
            transition: all 0.2s;
        }
        .custom-tabs .nav-link:hover {
            color: #4154f1;
            background: rgba(65,84,241,0.06);
        }
        .custom-tabs .nav-link.active {
            color: #fff;
            background: #4154f1;
        }

        /* Action Button */
        .btn-konseling {
            background: rgba(255,255,255,0.15);
            border: 1px solid rgba(255,255,255,0.3);
            color: #fff;
            border-radius: 12px;
            padding: 10px 24px;
            font-weight: 600;
            font-size: 0.85rem;
            transition: all 0.2s;
        }
        .btn-konseling:hover {
            background: rgba(255,255,255,0.25);
            color: #fff;
        }

        /* Progress Bar Poin */
        .poin-progress {
            height: 8px;
            border-radius: 4px;
            background: #f0f0f0;
            overflow: hidden;
        }
        .poin-progress-bar {
            height: 100%;
            border-radius: 4px;
            transition: width 0.6s ease;
        }

        /* ===== Modal Konseling ===== */
        .konseling-modal .modal-content {
            border: none;
            border-radius: 18px;
            overflow: hidden;
            box-shadow: 0 25px 60px rgba(33, 51, 99, 0.25);
        }
        .konseling-modal .modal-header-custom {
            background: #4154f1;
            color: #fff;
            padding: 20px 28px;
            border-bottom: none;
            position: relative;
        }
        .konseling-modal .modal-header-custom::after {
            content: '';
            position: absolute;
            bottom: -1px;
            left: 0;
            right: 0;
            height: 20px;
            background: #fff;
            border-radius: 20px 20px 0 0;
        }
        .konseling-modal .modal-title {
            font-size: 1.05rem;
            font-weight: 700;
            letter-spacing: 0.2px;
            display: flex;
            align-items: center;
            gap: 8px;
        }
        .konseling-modal .modal-header-custom .btn-close {
            filter: brightness(0) invert(1);
            opacity: 0.8;
            position: relative;
            z-index: 2;
        }
        .konseling-modal .modal-body {
            padding: 8px 28px 22px;
            max-height: calc(100vh - 220px);
            overflow-y: auto;
        }
        .konseling-modal .modal-footer {
            background: #f8f9ff;
            border-top: 1px solid #eef1f8;
            padding: 16px 28px;
            flex-shrink: 0;
        }

        /* Section divider di form */
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

        /* Input styling */
        .konseling-modal .form-label {
            font-size: 0.83rem;
            font-weight: 600;
            color: #3d4767;
            margin-bottom: 5px;
        }
        .konseling-modal .dropdown-menu {
            border: 1.5px solid #e3e8f3;
            border-radius: 12px;
            padding: 8px;
            box-shadow: 0 10px 30px rgba(0,0,0,0.05) !important;
            min-width: 100%;
        }
        .konseling-modal .dropdown-item {
            border-radius: 8px;
            padding: 8px 14px;
            font-size: 0.85rem;
            color: #2c3e50;
            transition: all 0.15s;
        }
        .konseling-modal .dropdown-item:hover, .konseling-modal .dropdown-item:focus {
            background-color: #f0f4ff;
            color: #4154f1;
        }
        .konseling-modal .form-label .req {
            color: #ef4444;
            margin-left: 2px;
        }
        .konseling-modal .form-control,
        .konseling-modal .form-select {
            border: 1.5px solid #e3e8f3;
            border-radius: 10px;
            padding: 9px 14px;
            font-size: 0.87rem;
            color: #2c3e50;
            background: #fbfcfe;
            transition: all 0.18s ease;
        }
        .konseling-modal .form-control::placeholder {
            color: #b8c0d3;
        }
        .konseling-modal .form-control:focus,
        .konseling-modal .form-select:focus {
            border-color: #4154f1;
            background: #fff;
            box-shadow: 0 0 0 3px rgba(65, 84, 241, 0.1);
            outline: none;
        }

        /* Input Icon */
        .input-icon-group { position: relative; }
        .input-icon-group > i {
            position: absolute;
            left: 14px;
            top: 50%;
            transform: translateY(-50%);
            color: #9aa3b8;
            z-index: 10;
            font-size: 0.95rem;
        }
        .input-icon-group .form-control {
            padding-left: 40px;
        }

        /* Type selector cards */
        .type-cards {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 10px;
        }
        .type-card {
            border: 1.5px solid #e3e8f3;
            border-radius: 12px;
            padding: 12px 14px;
            cursor: pointer;
            transition: all 0.18s ease;
            display: flex;
            align-items: center;
            gap: 10px;
            background: #fbfcfe;
        }
        .type-card:hover {
            border-color: #c5ccdf;
            background: #fff;
        }
        .type-card.active {
            border-color: #4154f1;
            background: rgba(65, 84, 241, 0.04);
            box-shadow: 0 0 0 3px rgba(65, 84, 241, 0.08);
        }
        .type-card .type-icon {
            width: 38px;
            height: 38px;
            border-radius: 10px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.1rem;
            background: rgba(65, 84, 241, 0.1);
            color: #4154f1;
            flex-shrink: 0;
        }
        .type-card.active .type-icon {
            background: #4154f1;
            color: #fff;
        }
        .type-card .type-title {
            font-size: 0.82rem;
            font-weight: 600;
            color: #2c3e50;
            line-height: 1.2;
        }
        .type-card .type-desc {
            font-size: 0.7rem;
            color: #9aa3b8;
        }

        /* Student picker */
        .student-picker-panel {
            border: 1.5px solid #e3e8f3;
            border-radius: 12px;
            padding: 14px;
            background: #fbfcfe;
            animation: fadeIn 0.2s ease;
        }
        .student-list-scroll {
            max-height: 180px;
            overflow-y: auto;
            border: 1px solid #eef1f8;
            border-radius: 10px;
            background: #fff;
        }
        .student-option {
            padding: 9px 12px;
            border-bottom: 1px solid #f5f6fa;
            cursor: pointer;
            transition: background 0.15s;
        }
        .student-option:last-child { border-bottom: none; }
        .student-option:hover { background: #f8f9ff; }
        .student-option.selected {
            background: rgba(65, 84, 241, 0.06);
        }
        .student-option.selected .badge {
            background: #4154f1 !important;
            color: #fff !important;
        }

        /* Submit button */
        .btn-submit-konseling {
            background: linear-gradient(135deg, #4154f1 0%, #5a3fd6 100%);
            border: none;
            color: #fff;
            font-weight: 600;
            font-size: 0.88rem;
            padding: 10px 26px;
            border-radius: 10px;
            transition: all 0.2s;
            box-shadow: 0 4px 14px rgba(65, 84, 241, 0.25);
        }
        .btn-submit-konseling:hover {
            transform: translateY(-1px);
            box-shadow: 0 6px 18px rgba(65, 84, 241, 0.35);
            color: #fff;
        }

        @keyframes fadeIn {
            from { opacity: 0; transform: translateY(-4px); }
            to { opacity: 1; transform: translateY(0); }
        }

        /* Scrollbar mini untuk student list */
        .student-list-scroll::-webkit-scrollbar { width: 6px; }
        .student-list-scroll::-webkit-scrollbar-track { background: #f5f6fa; }
        .student-list-scroll::-webkit-scrollbar-thumb { background: #cfd5e5; border-radius: 3px; }
        .student-list-scroll::-webkit-scrollbar-thumb:hover { background: #b0b8d0; }
    </style>
</head>

<body>
    @include('include.header')
    @include('include.sidebar')

    <main id="main" class="main" style="scrollbar-gutter: stable;">
        <div class="pagetitle">
            <h1>Dashboard Siswa</h1>
            <nav>
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="{{ route('dashboard.siswa') }}">Beranda</a></li>
                    <li class="breadcrumb-item active">Dashboard</li>
                </ol>
            </nav>
        </div>

        <section class="section">
            {{-- Welcome Banner --}}
            <div class="card welcome-banner mb-4">
                <div class="card-body p-4 d-flex align-items-center justify-content-between flex-wrap gap-3">
                    <div>
                        <h4 class="fw-bold mb-1">Halo, {{ Auth::user()->name }}</h4>
                        <p class="mb-0" style="opacity:0.8; font-size:0.9rem;">
                            Kelas {{ $student->class?->school_class_name ?? '-' }}
                            {{ $student->class?->school_class_major ? '| ' . $student->class->school_class_major : '' }}
                            &middot; NIS: {{ $student->nis }}
                        </p>
                    </div>
                    <button type="button" class="btn btn-konseling" data-bs-toggle="modal" data-bs-target="#modalAjukanKonseling">
                        <i class="bi bi-calendar-plus me-2"></i>Ajukan Konseling
                    </button>
                </div>
            </div>

            {{-- Status & Stat Cards --}}
            <div class="row g-3 mb-4">
                {{-- Status Poin --}}
                <div class="col-lg-4 col-md-6">
                    <div class="card status-card status-badge-{{ $status }} text-white h-100">
                        <div class="card-body p-4">
                            <div class="d-flex align-items-center mb-3">
                                <i class="bi {{ $status == 'aman' ? 'bi-shield-check' : ($status == 'peringatan' ? 'bi-exclamation-triangle' : 'bi-x-octagon') }} fs-3 me-3" style="opacity:0.85;"></i>
                                <div>
                                    <p class="mb-0 small" style="opacity:0.8;">Status Disiplin</p>
                                    <h5 class="fw-bold mb-0">{{ ucfirst($status) }}</h5>
                                </div>
                            </div>
                            <div class="d-flex align-items-end justify-content-between">
                                <div>
                                    <span class="small" style="opacity:0.7;">Total Poin Pelanggaran</span>
                                    <div class="fs-2 fw-bold">{{ $totalPoin }}</div>
                                </div>
                                <div style="width:120px;">
                                    <div class="poin-progress">
                                        <div class="poin-progress-bar" style="width:{{ min($totalPoin, 100) }}%; background:rgba(255,255,255,0.5);"></div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- Konseling --}}
                <div class="col-lg-4 col-md-6">
                    <div class="card stat-card bg-white h-100">
                        <div class="card-body p-4">
                            <div class="d-flex align-items-center justify-content-between">
                                <div>
                                    <p class="stat-label mb-1">Total Konseling</p>
                                    <div class="stat-number text-dark">{{ $jumlahKonseling }}</div>
                                    <small class="text-muted">{{ $konselingSelesai }} selesai &middot; {{ $konselingAktif }} aktif</small>
                                </div>
                                <div class="stat-icon" style="background:rgba(65,84,241,0.1); color:#4154f1;">
                                    <i class="bi bi-chat-dots"></i>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- Kasus --}}
                <div class="col-lg-2 col-md-3 col-6">
                    <div class="card stat-card bg-white h-100">
                        <div class="card-body p-4">
                            <div class="stat-icon mb-2" style="background:rgba(239,68,68,0.1); color:#ef4444;">
                                <i class="bi bi-journal-bookmark"></i>
                            </div>
                            <div class="stat-number text-dark">{{ $jumlahKasus }}</div>
                            <p class="stat-label mb-0">Kasus</p>
                        </div>
                    </div>
                </div>

                {{-- Prestasi --}}
                <div class="col-lg-2 col-md-3 col-6">
                    <div class="card stat-card bg-white h-100">
                        <div class="card-body p-4">
                            <div class="stat-icon mb-2" style="background:rgba(16,185,129,0.1); color:#10b981;">
                                <i class="bi bi-trophy"></i>
                            </div>
                            <div class="stat-number text-dark">{{ $jumlahPrestasi }}</div>
                            <p class="stat-label mb-0">Prestasi</p>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Riwayat Section with Tabs --}}
            <div class="row">
                <div class="col-12">
                    <div class="card riwayat-card">
                        <div class="card-header d-flex align-items-center justify-content-between flex-wrap gap-2">
                            <h6><i class="bi bi-clock-history me-2 text-primary"></i>Riwayat Saya</h6>
                            <ul class="nav custom-tabs gap-1" id="riwayatTabs">
                                <li class="nav-item"><a class="nav-link active" data-bs-toggle="tab" href="#tab-konseling">Konseling</a></li>
                                <li class="nav-item"><a class="nav-link" data-bs-toggle="tab" href="#tab-poin">Pelanggaran</a></li>
                                <li class="nav-item"><a class="nav-link" data-bs-toggle="tab" href="#tab-kasus">Kasus</a></li>
                                <li class="nav-item"><a class="nav-link" data-bs-toggle="tab" href="#tab-prestasi">Prestasi</a></li>
                            </ul>
                        </div>
                        <div class="card-body">
                            <div class="tab-content" style="height: 360px; overflow-y: auto;">

                                {{-- Tab Konseling --}}
                                <div class="tab-pane fade show active" id="tab-konseling">
                                    @forelse($riwayatKonseling as $r)
                                        <div class="riwayat-item">
                                            <div class="riwayat-dot" style="background:{{ $r->status == 'selesai' ? '#10b981' : ($r->status == 'disetujui' ? '#4154f1' : ($r->status == 'ditolak' || $r->status == 'dibatalkan' ? '#ef4444' : '#f59e0b')) }};"></div>
                                            <div class="flex-grow-1">
                                                <div class="riwayat-title">{{ $r->topic }}</div>
                                                <div class="riwayat-meta">
                                                    {{ $r->requested_date?->format('d M Y') }} &middot; {{ $r->guruBk?->name ?? 'Guru BK' }}
                                                </div>
                                            </div>
                                            @if($r->status == 'menunggu')
                                                <span class="badge bg-warning text-dark" style="font-size:0.72rem;">Menunggu</span>
                                            @elseif($r->status == 'disetujui')
                                                <span class="badge bg-primary" style="font-size:0.72rem;">Disetujui</span>
                                            @elseif($r->status == 'selesai')
                                                <span class="badge bg-success" style="font-size:0.72rem;">Selesai</span>
                                            @elseif($r->status == 'ditolak' || $r->status == 'dibatalkan')
                                                <span class="badge bg-danger" style="font-size:0.72rem;">{{ ucfirst($r->status) }}</span>
                                            @else
                                                <span class="badge bg-secondary" style="font-size:0.72rem;">{{ ucfirst($r->status) }}</span>
                                            @endif
                                        </div>
                                    @empty
                                        <div class="empty-state">
                                            <i class="bi bi-chat-square"></i>
                                            <span>Belum ada riwayat konseling</span>
                                        </div>
                                    @endforelse
                                </div>

                                {{-- Tab Pelanggaran --}}
                                <div class="tab-pane fade" id="tab-poin">
                                    @forelse($riwayatPoin as $r)
                                        <div class="riwayat-item">
                                            <div class="riwayat-dot" style="background:#ef4444;"></div>
                                            <div class="flex-grow-1">
                                                <div class="riwayat-title">{{ $r->violation }}</div>
                                                <div class="riwayat-meta">
                                                    {{ $r->violation_date?->format('d M Y') }}
                                                    @if($r->recorder)
                                                        &middot; Dicatat: {{ $r->recorder->name }}
                                                    @endif
                                                </div>
                                            </div>
                                            <span class="badge bg-danger" style="font-size:0.72rem;">+{{ $r->point_number }} poin</span>
                                        </div>
                                    @empty
                                        <div class="empty-state">
                                            <i class="bi bi-star"></i>
                                            <span>Belum ada catatan pelanggaran</span>
                                        </div>
                                    @endforelse
                                </div>

                                {{-- Tab Kasus --}}
                                <div class="tab-pane fade" id="tab-kasus">
                                    @forelse($riwayatKasus as $r)
                                        <div class="riwayat-item">
                                            <div class="riwayat-dot" style="background:{{ $r->status == 'selesai' ? '#10b981' : '#f59e0b' }};"></div>
                                            <div class="flex-grow-1">
                                                <div class="riwayat-title">{{ $r->case_title }}</div>
                                                <div class="riwayat-meta">
                                                    {{ $r->case_date?->format('d M Y') }} &middot; {{ ucfirst($r->case_type) }}
                                                </div>
                                            </div>
                                            <span class="badge {{ $r->status == 'selesai' ? 'bg-success' : 'bg-warning text-dark' }}" style="font-size:0.72rem;">{{ ucfirst($r->status) }}</span>
                                        </div>
                                    @empty
                                        <div class="empty-state">
                                            <i class="bi bi-journal-bookmark"></i>
                                            <span>Belum ada catatan kasus</span>
                                        </div>
                                    @endforelse
                                </div>

                                {{-- Tab Prestasi --}}
                                <div class="tab-pane fade" id="tab-prestasi">
                                    @forelse($riwayatPrestasi as $r)
                                        <div class="riwayat-item">
                                            <div class="riwayat-dot" style="background:#10b981;"></div>
                                            <div class="flex-grow-1">
                                                <div class="riwayat-title">{{ $r->achievement_name }}</div>
                                                <div class="riwayat-meta">
                                                    {{ $r->achievement_date?->format('d M Y') }}
                                                    @if($r->achievement_level)
                                                        &middot; {{ $r->achievement_level }}
                                                    @endif
                                                </div>
                                            </div>
                                            @if($r->achievement_category)
                                                <span class="badge bg-success" style="font-size:0.72rem;">{{ $r->achievement_category }}</span>
                                            @endif
                                        </div>
                                    @empty
                                        <div class="empty-state">
                                            <i class="bi bi-trophy"></i>
                                            <span>Belum ada catatan prestasi</span>
                                        </div>
                                    @endforelse
                                </div>

                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>
    </main>

    <script src="{{ asset('assets/vendor/bootstrap/js/bootstrap.bundle.min.js') }}"></script>
    <script src="{{ asset('assets/js/main.js') }}"></script>

    {{-- Modal Pengajuan Konseling --}}
    <div class="modal fade konseling-modal" id="modalAjukanKonseling" tabindex="-1">
        <div class="modal-dialog modal-dialog-centered modal-lg modal-dialog-scrollable">
            <div class="modal-content">
                <form method="POST" action="{{ route('counseling.store') }}">
                    @csrf
                    <input type="hidden" name="type" id="typeHidden" value="individu">
                    <div class="modal-header modal-header-custom">
                        <h5 class="modal-title">
                            <i class="bi bi-calendar-plus-fill me-2"></i>Pengajuan Jadwal Konseling
                        </h5>
                        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">

                        {{-- Section: Jadwal --}}
                        <div class="form-section-label"><i class="bi bi-calendar2-week"></i>Jadwal Pertemuan</div>
                        <div class="row g-3 mb-2">
                            <div class="col-md-12">
                                <label class="form-label">Tanggal Pertemuan <span class="req">*</span></label>
                                <input type="date" name="requested_date" id="requestedDateInputSiswa" class="form-control" min="{{ date('Y-m-d') }}" onchange="validateWeekdaySiswa(this); updateSlotWaktu();" required>
                                <div class="alert alert-warning py-2 px-3 mt-2 mb-0 d-none" id="weekendAlertSiswa" style="font-size: 0.78rem; border-radius: 8px;">
                                    <i class="bi bi-exclamation-triangle-fill me-1"></i> Maaf, layanan tidak tersedia pada Sabtu/Minggu.
                                </div>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">Guru BK <span class="req">*</span></label>
                                <select name="guru_bk_id" id="guruBkSelect" class="form-select" onchange="updateSlotWaktu()" required disabled style="opacity:0.6;cursor:not-allowed;">
                                    <option value="">-- Pilih Tanggal Dahulu --</option>
                                    @foreach($guru_bk as $guru)
                                        <option value="{{ $guru->id }}" data-nama="{{ strtolower($guru->name) }}">{{ $guru->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">Sesi Waktu <span class="req">*</span></label>
                                <div class="dropdown w-100">
                                    <button class="form-control text-start d-flex justify-content-between align-items-center" type="button" id="slotWaktuBtn" data-bs-toggle="dropdown" aria-expanded="false" disabled style="opacity:0.6; cursor:not-allowed;">
                                        <span id="slotWaktuLabel" class="text-secondary">Pilih guru BK dahulu...</span>
                                        <i class="bi bi-chevron-down text-muted" style="font-size:0.8rem;"></i>
                                    </button>
                                    <ul class="dropdown-menu shadow-sm" aria-labelledby="slotWaktuBtn" id="slotWaktuDropdownList">
                                        <li><a class="dropdown-item text-muted" href="javascript:void(0)">Pilih guru BK dahulu...</a></li>
                                    </ul>
                                    <input type="hidden" name="slot_waktu" id="slotWaktuInput" required>
                                </div>
                            </div>
                        </div>

                        {{-- Section: Tipe Konseling --}}
                        <div class="form-section-label"><i class="bi bi-diagram-3"></i>Tipe Konseling</div>
                        <div class="type-cards mb-2">
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

                        {{-- Panel Kelompok --}}
                        <div class="student-picker-panel d-none mb-2" id="searchSiswaPanel">
                            <div class="d-flex align-items-center mb-2">
                                <i class="bi bi-people-fill text-primary me-2"></i>
                                <span class="fw-bold text-dark" style="font-size:0.85rem;">Pilih Anggota Kelompok</span>
                            </div>
                            <div class="row g-2 mb-2">
                                <div class="col-md-5 col-sm-6">
                                    <div class="dropdown w-100">
                                        <button class="form-control text-start d-flex justify-content-between align-items-center w-100" type="button" id="modalTingkatBtn" data-bs-toggle="dropdown" aria-expanded="false">
                                            <span class="text-secondary fw-semibold">Tingkat: Semua</span>
                                            <i class="bi bi-chevron-down text-muted" style="font-size:0.8rem;"></i>
                                        </button>
                                        <ul class="dropdown-menu shadow-sm" aria-labelledby="modalTingkatBtn">
                                            <li><a class="dropdown-item fw-semibold" href="javascript:void(0)" onclick="selectModalTingkat('', 'Tingkat: Semua')">Semua Tingkat</a></li>
                                            <li><hr class="dropdown-divider"></li>
                                            <li><a class="dropdown-item" href="javascript:void(0)" onclick="selectModalTingkat('kelas 10', 'Kelas 10 (X)')">Kelas 10 (X)</a></li>
                                            <li><a class="dropdown-item" href="javascript:void(0)" onclick="selectModalTingkat('kelas 11', 'Kelas 11 (XI)')">Kelas 11 (XI)</a></li>
                                            <li><a class="dropdown-item" href="javascript:void(0)" onclick="selectModalTingkat('kelas 12', 'Kelas 12 (XII)')">Kelas 12 (XII)</a></li>
                                        </ul>
                                        <input type="hidden" id="modalFilterTingkat" value="">
                                    </div>
                                </div>
                                <div class="col-md-7 col-sm-6">
                                    <div class="input-icon-group">
                                        <i class="bi bi-search"></i>
                                        <input type="text" id="searchSiswaInput" class="form-control" placeholder="Cari nama atau NIS..." onkeyup="filterSiswaList()">
                                    </div>
                                </div>
                            </div>
                            <input type="hidden" name="student_id" value="{{ $student?->id }}">
                            <div id="additionalStudentsContainer"></div>
                            <div id="studentListContainer" class="student-list-scroll">
                                @foreach(\App\Models\Student::with('class')->get() as $s)
                                    @if(!$student || $s->id != $student->id)
                                        <div class="student-option d-flex justify-content-between align-items-center"
                                             data-student-id="{{ $s->id }}"
                                             onclick="selectStudent('{{ $s->id }}', '{{ addslashes($s->full_name) }}', this)"
                                             data-search="{{ strtolower($s->full_name . ' ' . $s->nis . ' ' . ($s->class?->school_class_name ?? '') . ' kelas ' . ($s->class?->grade ?? '')) }}">
                                            <div>
                                                <strong class="text-dark d-block" style="font-size: 0.85rem;">{{ $s->full_name }}</strong>
                                                <small class="text-muted" style="font-size: 0.72rem;">NIS: {{ $s->nis }} | {{ $s->class?->school_class_name ?? 'Tanpa Kelas' }}</small>
                                            </div>
                                            <span class="badge bg-primary bg-opacity-10 text-primary border border-primary border-opacity-25 px-2 py-1" style="font-size: 0.68rem;">Pilih</span>
                                        </div>
                                    @endif
                                @endforeach
                            </div>
                            <div id="selectedStudentDisplay" class="alert alert-info py-2 px-3 mt-2 mb-0 d-none" style="font-size: 0.78rem; border-radius: 8px;">
                                <i class="bi bi-check-circle-fill me-1"></i> Terpilih: <strong id="selectedStudentText"></strong>
                            </div>
                        </div>

                        {{-- Section: Topik --}}
                        <div class="form-section-label"><i class="bi bi-chat-left-text"></i>Detail Konsultasi</div>
                        <div class="mb-3">
                            <label class="form-label">Topik Bahasan <span class="req">*</span></label>
                            <input type="text" name="topic" class="form-control" placeholder="Contoh: Kendala belajar, konsultasi karir, masalah pribadi" required>
                        </div>
                        <div class="mb-2">
                            <label class="form-label">Deskripsi Singkat</label>
                            <textarea name="description" class="form-control" rows="3" placeholder="Ceritakan singkat apa yang ingin didiskusikan... (Rahasia dijamin)"></textarea>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-light px-3" data-bs-dismiss="modal" style="font-size:0.85rem; border-radius:10px;">
                            <i class="bi bi-x-lg me-1"></i>Batal
                        </button>
                        <button type="submit" class="btn btn-submit-konseling">
                            <i class="bi bi-send me-1"></i>Kirim Pengajuan
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <script>
        // ===== Tipe Konseling =====
        function selectType(type, el) {
            document.querySelectorAll('.type-card').forEach(c => c.classList.remove('active'));
            el.classList.add('active');
            document.getElementById('typeHidden').value = type;
            handleTypeChange();
        }

        function handleTypeChange() {
            const type = document.getElementById('typeHidden').value;
            const panel = document.getElementById('searchSiswaPanel');
            if (type === 'kelompok') {
                panel.classList.remove('d-none');
            } else {
                panel.classList.add('d-none');
                document.getElementById('additionalStudentsContainer').innerHTML = '';
                document.getElementById('selectedStudentDisplay').classList.add('d-none');
                document.getElementById('selectedStudentText').innerText = '';
            }
        }

        // ===== Validasi Tanggal =====
        function validateWeekdaySiswa(input) {
            const date = new Date(input.value);
            const alertBox = document.getElementById('weekendAlertSiswa');
            const guruSelect = document.getElementById('guruBkSelect');
            const slotBtn = document.getElementById('slotWaktuBtn');
            const slotLabel = document.getElementById('slotWaktuLabel');
            const slotList = document.getElementById('slotWaktuDropdownList');
            const slotInput = document.getElementById('slotWaktuInput');
            
            if (date.getDay() === 0 || date.getDay() === 6) {
                alertBox.classList.remove('d-none');
                input.value = '';
                // Kunci kembali
                guruSelect.disabled = true;
                guruSelect.style.opacity = '0.6';
                guruSelect.style.cursor = 'not-allowed';
                guruSelect.value = '';
                
                if(slotBtn) {
                    slotBtn.disabled = true;
                    slotBtn.style.opacity = '0.6';
                    slotBtn.style.cursor = 'not-allowed';
                    slotLabel.innerText = 'Pilih guru BK dahulu...';
                    slotLabel.classList.add('text-secondary');
                    slotList.innerHTML = '<li><a class="dropdown-item text-muted" href="javascript:void(0)">Pilih guru BK dahulu...</a></li>';
                    slotInput.value = '';
                }
            } else {
                alertBox.classList.add('d-none');
                // Unlock guru select
                guruSelect.disabled = false;
                guruSelect.style.opacity = '1';
                guruSelect.style.cursor = 'pointer';
                guruSelect.options[0].text = '-- Pilih Guru BK --';
            }
        }

        // ===== Slot Waktu =====
        function updateSlotWaktu() {
            const guruSelect = document.getElementById('guruBkSelect');
            const dateInput = document.getElementById('requestedDateInputSiswa');
            const slotBtn = document.getElementById('slotWaktuBtn');
            const slotLabel = document.getElementById('slotWaktuLabel');
            const slotList = document.getElementById('slotWaktuDropdownList');
            const slotInput = document.getElementById('slotWaktuInput');
            
            const selectedOption = guruSelect.options[guruSelect.selectedIndex];
            const selectedDate = dateInput.value;

            slotList.innerHTML = '<li><a class="dropdown-item text-muted" href="javascript:void(0)">Pilih sesi waktu...</a></li>';
            if (!selectedOption.value) {
                slotBtn.disabled = true;
                slotBtn.style.opacity = '0.6';
                slotBtn.style.cursor = 'not-allowed';
                slotLabel.innerText = 'Pilih guru BK dahulu...';
                slotLabel.classList.add('text-secondary');
                slotInput.value = '';
                return;
            }
            
            // Unlock slot button
            slotBtn.disabled = false;
            slotBtn.style.opacity = '1';
            slotBtn.style.cursor = 'pointer';
            slotLabel.innerText = 'Pilih sesi waktu...';
            slotLabel.classList.add('text-secondary');
            slotInput.value = '';
            slotList.innerHTML = '';

            const namaGuru = (selectedOption.getAttribute('data-nama') || '').toLowerCase();
            let availableSlots = [];
            if (namaGuru.includes('rio')) {
                availableSlots.push({ val: "08:00 - 10:00", end: "10:00" });
            } else if (namaGuru.includes('ratna')) {
                availableSlots.push({ val: "12:00 - 15:00", end: "15:00" });
            } else if (namaGuru.includes('siti rahma')) {
                availableSlots.push({ val: "08:00 - 15:00", end: "15:00" });
            } else {
                availableSlots.push({ val: "08:00 - 10:00", end: "10:00" });
                availableSlots.push({ val: "10:00 - 12:00", end: "12:00" });
                availableSlots.push({ val: "13:00 - 15:00", end: "15:00" });
            }

            const now = new Date();
            const todayStr = now.getFullYear() + '-' + String(now.getMonth() + 1).padStart(2, '0') + '-' + String(now.getDate()).padStart(2, '0');
            const currentTimeVal = now.getHours() * 60 + now.getMinutes();

            availableSlots.forEach(slot => {
                const endParts = slot.end.split(':');
                const endTimeVal = parseInt(endParts[0], 10) * 60 + parseInt(endParts[1], 10);
                const isPast = (selectedDate === todayStr && currentTimeVal >= endTimeVal);
                if (isPast) {
                    slotList.innerHTML += `<li><a class="dropdown-item text-muted" href="javascript:void(0)" style="cursor:not-allowed; opacity:0.6;">${slot.val} WIB <span class="badge bg-danger ms-2" style="font-size:0.7rem;">Lewat</span></a></li>`;
                } else {
                    slotList.innerHTML += `<li><a class="dropdown-item fw-semibold" href="javascript:void(0)" onclick="selectSlotWaktu('${slot.val}')">${slot.val} WIB</a></li>`;
                }
            });
        }
        
        function selectSlotWaktu(val) {
            document.getElementById('slotWaktuInput').value = val;
            const label = document.getElementById('slotWaktuLabel');
            label.innerText = val + ' WIB';
            label.classList.remove('text-secondary');
        }

        // ===== Pilih Teman =====
        function selectStudent(id, name, el) {
            const container = document.getElementById('additionalStudentsContainer');
            const existing = container.querySelector('input[value="' + id + '"]');
            if (existing) {
                existing.remove();
                el.classList.remove('selected');
                el.querySelector('.badge').textContent = 'Pilih';
                updateSelectedDisplay();
                return;
            }
            const input = document.createElement('input');
            input.type = 'hidden';
            input.name = 'additional_students[]';
            input.value = id;
            container.appendChild(input);
            el.classList.add('selected');
            el.querySelector('.badge').textContent = 'Terpilih';
            updateSelectedDisplay();
        }

        function updateSelectedDisplay() {
            const container = document.getElementById('additionalStudentsContainer');
            const display = document.getElementById('selectedStudentDisplay');
            const text = document.getElementById('selectedStudentText');
            const ids = Array.from(container.querySelectorAll('input')).map(i => i.value);
            const names = ids.map(id => {
                const opt = document.querySelector('[data-student-id="' + id + '"]');
                return opt ? opt.querySelector('strong').innerText : '';
            });
            if (names.length) {
                display.classList.remove('d-none');
                text.innerText = names.join(', ');
            } else {
                display.classList.add('d-none');
            }
        }

        function selectModalTingkat(value, label) {
            document.getElementById('modalFilterTingkat').value = value;
            document.getElementById('modalTingkatBtn').querySelector('span').innerText = label;
            filterSiswaList();
        }

        function filterSiswaList() {
            const search = (document.getElementById('searchSiswaInput').value || '').toLowerCase();
            const tingkat = (document.getElementById('modalFilterTingkat').value || '').toLowerCase();
            document.querySelectorAll('#studentListContainer .student-option').forEach(function(el) {
                const dataSearch = (el.getAttribute('data-search') || '').toLowerCase();
                const matchSearch = !search || dataSearch.includes(search);
                const matchTingkat = !tingkat || dataSearch.includes(tingkat);
                if (matchSearch && matchTingkat) {
                    el.style.setProperty('display', 'flex', 'important');
                } else {
                    el.style.setProperty('display', 'none', 'important');
                }
            });
        }

        // ===== Reset Modal =====
        document.addEventListener('DOMContentLoaded', function() {
            var modalEl = document.getElementById('modalAjukanKonseling');
            if (modalEl) {
                modalEl.addEventListener('hidden.bs.modal', function () {
                    modalEl.querySelector('form').reset();
                    document.getElementById('requestedDateInputSiswa').value = '';
                    
                    const slotBtn = document.getElementById('slotWaktuBtn');
                    if(slotBtn) {
                        slotBtn.disabled = true;
                        slotBtn.style.opacity = '0.6';
                        slotBtn.style.cursor = 'not-allowed';
                        document.getElementById('slotWaktuLabel').innerText = 'Pilih guru BK dahulu...';
                        document.getElementById('slotWaktuLabel').classList.add('text-secondary');
                        document.getElementById('slotWaktuInput').value = '';
                        document.getElementById('slotWaktuDropdownList').innerHTML = '<li><a class="dropdown-item text-muted" href="javascript:void(0)">Pilih guru BK dahulu...</a></li>';
                    }
                    
                    document.getElementById('weekendAlertSiswa').classList.add('d-none');
                    document.getElementById('additionalStudentsContainer').innerHTML = '';
                    document.getElementById('selectedStudentDisplay').classList.add('d-none');
                    document.getElementById('selectedStudentText').innerText = '';
                    // Reset type ke individu
                    document.querySelectorAll('.type-card').forEach(c => c.classList.remove('active'));
                    document.querySelector('.type-card[data-type="individu"]').classList.add('active');
                    document.getElementById('typeHidden').value = 'individu';
                    
                    // Reset search filter & dropdowns
                    const searchInput = document.getElementById('searchSiswaInput');
                    if (searchInput) searchInput.value = '';
                    
                    const filterTingkat = document.getElementById('modalFilterTingkat');
                    if (filterTingkat) filterTingkat.value = '';
                    
                    const btnTingkat = document.getElementById('modalTingkatBtn');
                    if (btnTingkat && btnTingkat.querySelector('span')) {
                        btnTingkat.querySelector('span').innerText = 'Tingkat: Semua';
                    }
                    
                    if (typeof filterSiswaList === "function") {
                        filterSiswaList();
                    }
                    
                    handleTypeChange();
                });
            }
        });
    </script>
</body>

</html>
