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
            height: 20px;
            background: #fff;
            border-radius: 20px 20px 0 0;
        }
        .modal-content {
            border: none;
            border-radius: 18px;
            overflow: hidden;
            box-shadow: 0 25px 60px rgba(33, 51, 99, 0.25);
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
        .dropdown-menu {
            border: 1px solid #ced4da;
            border-radius: 8px;
            padding: 8px;
            box-shadow: 0 4px 12px rgba(0,0,0,0.05) !important;
            min-width: 100%;
        }
        .dropdown-item {
            border-radius: 6px;
            padding: 8px 14px;
            font-size: 0.85rem;
            color: #2c3e50;
            transition: all 0.15s;
        }
        .dropdown-item:hover, .dropdown-item:focus {
            background-color: #f0f4ff;
            color: #4154f1;
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
        .input-icon-group.textarea-icon i {
            top: 16px;
            transform: none;
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
            <h1>Layanan Bimbingan & Konseling Siswa</h1>
            <nav>
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="{{ route('counseling.siswa') }}">Beranda Siswa</a></li>
                    <li class="breadcrumb-item active">Konseling Saya</li>
                </ol>
            </nav>
        </div>


        <section class="section">
            {{-- Welcome Banner --}}
            <div class="row mb-4">
                <div class="col-12">
                    <div class="card bg-primary text-white shadow-sm border-0">
                        <div class="card-body p-4 d-flex align-items-center justify-content-between">
                            <div>
                                <h3 class="fw-bold mb-1">Halo, {{ Auth::user()->name }}! 👋</h3>
                                <p class="mb-0 text-white-50">Ruang BK selalu terbuka untuk didengar. Ajukan jadwal pertemuan konseling kapan saja Anda membutuhkan bimbingan.</p>
                            </div>
                            <button class="btn btn-light text-primary fw-semibold rounded-pill px-4 py-2" data-bs-toggle="modal" data-bs-target="#modalAjukanKonseling">
                                <i class="bi bi-calendar-plus me-2"></i>Ajukan Konseling Baru
                            </button>
                        </div>
                    </div>
                </div>
            </div>

            <div class="row">
                <div class="col-lg-12">
                    <div class="card">
                        <div class="card-body">
                            <div class="d-flex justify-content-between align-items-center mb-3 pt-3">
                                <h5 class="card-title m-0">Riwayat Pengajuan Konseling Saya</h5>
                            </div>

                            {{-- Search Bar --}}
                            <div class="mb-3">
                                <div class="input-group" style="max-width: 360px;">
                                    <span class="input-group-text bg-white border-end-0"><i class="bi bi-search text-muted"></i></span>
                                    <input type="text" id="searchKonselingSiswa" class="form-control border-start-0 ps-0" placeholder="Cari topik atau status konseling...">
                                </div>
                            </div>

                            <div class="table-responsive">
                                <table class="table table-hover align-middle" id="tabelKonselingSiswa">
                                    <thead>
                                        <tr>
                                            <th>No</th>
                                            <th>Jadwal & Topik</th>
                                            <th>Guru BK Pembimbing</th>
                                            <th>Status</th>
                                            <th>Catatan</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @forelse($mySessions as $s)
                                            <tr>
                                                <td>{{ $loop->iteration }}</td>
                                                <td>
                                                    <div class="fw-semibold text-dark text-truncate" style="max-width: 250px;" title="{{ $s->topic }}">{{ $s->topic }}</div>
                                                    @if($s->description)
                                                        <div class="text-muted small text-truncate mb-1" style="max-width: 250px;" title="{{ $s->description }}">{{ Str::limit($s->description, 40) }}</div>
                                                    @endif
                                                    <small class="text-muted fw-semibold">
                                                        <i class="bi bi-calendar3 me-1 text-primary"></i>{{ $s->requested_date?->format('d M Y') }}
                                                        @if($s->slot_waktu)
                                                            | Slot: {{ $s->slot_waktu }}
                                                        @else
                                                            | {{ \Carbon\Carbon::parse($s->requested_time)->format('H:i') }} WIB
                                                        @endif
                                                    </small>
                                                </td>
                                                <td>{{ $s->guruBk?->name ?? 'Akan Ditentukan' }}</td>
                                                <td>
                                                    <div class="mb-1">
                                                        @if($s->status == 'menunggu')
                                                            <span class="badge bg-warning text-dark">Menunggu Persetujuan</span>
                                                        @elseif($s->status == 'disetujui')
                                                            <span class="badge bg-primary">Disetujui</span>
                                                        @elseif($s->status == 'selesai')
                                                            <span class="badge bg-success">Selesai</span>
                                                        @elseif($s->status == 'ditolak')
                                                            <span class="badge bg-danger">Ditolak</span>
                                                        @else
                                                            <span class="badge bg-secondary">{{ ucfirst($s->status) }}</span>
                                                        @endif
                                                    </div>
                                                    
                                                    @if($s->status == 'disetujui' && $s->waktu_perkiraan)
                                                        <div class="mt-2 p-2 bg-light border border-info rounded text-dark small" style="font-size: 0.8rem;">
                                                            <strong>GILIRAN KE: {{ $s->no_antrian }}</strong><br>
                                                            Perkiraan Dipanggil: <span class="text-primary fw-bold">{{ $s->waktu_perkiraan }} WIB</span>
                                                        </div>
                                                    @endif
                                                </td>
                                                <td>
                                                    @if(!empty($s->notes))
                                                        <div class="text-muted small fst-italic">
                                                            {{ $s->notes }}
                                                        </div>
                                                    @else
                                                        <div class="text-muted small">-</div>
                                                    @endif
                                                </td>
                                            </tr>
                                        @empty
                                            <tr>
                                                <td colspan="5" class="text-center py-5 text-muted">
                                                    <i class="bi bi-journal-x fs-2 d-block mb-2 opacity-25"></i>
                                                    Anda belum memiliki riwayat pengajuan konseling. Klik tombol "Ajukan Konseling Baru" di atas untuk membuat jadwal.
                                                </td>
                                            </tr>
                                        @endforelse
                                    </tbody>
                                </table>
                                <p id="noResultKonselingSiswa" class="text-center text-muted py-3 d-none">Tidak ada data yang cocok dengan pencarian.</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>
    </main>

    {{-- Modal Form Pengajuan Konseling Siswa --}}
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
                        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close" style="filter: brightness(0) invert(1); opacity: 0.8; position: relative; z-index: 2;"></button>
                    </div>
                    <div class="modal-body p-4">
                        
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
                                <select name="slot_waktu" id="slotWaktuSelect" class="form-select" disabled required>
                                    <option value="">Pilih guru BK dahulu...</option>
                                </select>
                            </div>
                        </div>

                        <script>
                            function validateWeekdaySiswa(input) {
                                const date = new Date(input.value);
                                const alertBox = document.getElementById('weekendAlertSiswa');
                                const guruSelect = document.getElementById('guruBkSelect');
                                const slotSelect = document.getElementById('slotWaktuSelect');
                                
                                // 0 = Minggu, 6 = Sabtu
                                if(date.getDay() === 0 || date.getDay() === 6) {
                                    alertBox.classList.remove('d-none');
                                    input.value = '';
                                    // Kunci kembali
                                    guruSelect.disabled = true;
                                    guruSelect.style.opacity = '0.6';
                                    guruSelect.style.cursor = 'not-allowed';
                                    guruSelect.value = '';
                                    
                                    if(slotSelect) {
                                        slotSelect.disabled = true;
                                        slotSelect.innerHTML = '<option value="">Pilih Guru BK Dahulu...</option>';
                                    }
                                } else {
                                    alertBox.classList.add('d-none');
                                    // Unlock guru
                                    guruSelect.disabled = false;
                                    guruSelect.style.opacity = '1';
                                    guruSelect.style.cursor = 'pointer';
                                    guruSelect.options[0].text = '-- Pilih Guru BK --';
                                }
                            }

                            function updateSlotWaktu() {
                                const guruSelect = document.getElementById('guruBkSelect');
                                const dateInput = document.getElementById('requestedDateInputSiswa');
                                const slotSelect = document.getElementById('slotWaktuSelect');
                                
                                const selectedOption = guruSelect.options[guruSelect.selectedIndex];
                                const selectedDate = dateInput.value;
                                
                                slotSelect.innerHTML = '<option value="">Pilih waktu pertemuan...</option>';
                                
                                if (!selectedOption.value) {
                                    slotSelect.disabled = true;
                                    slotSelect.innerHTML = '<option value="">Pilih Guru BK Dahulu...</option>';
                                    return;
                                }

                                // Unlock slot select
                                slotSelect.disabled = false;
                                slotSelect.innerHTML = '<option value="">Pilih waktu pertemuan...</option>';

                                const namaGuru = (selectedOption.getAttribute('data-nama') || '').toLowerCase();
                                let availableSlots = [];
                                
                                // Jika nama guru mengandung "rio", slotnya beda
                                if (namaGuru.includes('rio')) {
                                    availableSlots.push({ val: "08:00 - 10:00", end: "10:00" });
                                } 
                                // Jika nama guru mengandung "ratna"
                                else if (namaGuru.includes('ratna')) {
                                    availableSlots.push({ val: "12:00 - 15:00", end: "15:00" });
                                } 
                                // Jika nama guru mengandung "siti rahma"
                                else if (namaGuru.includes('siti rahma')) {
                                    availableSlots.push({ val: "08:00 - 15:00", end: "15:00" });
                                }
                                // Default Guru Lainnya
                                else {
                                    availableSlots.push({ val: "08:00 - 10:00", end: "10:00" });
                                    availableSlots.push({ val: "10:00 - 12:00", end: "12:00" });
                                    availableSlots.push({ val: "13:00 - 15:00", end: "15:00" });
                                }
                                
                                // Realtime Time Check
                                const now = new Date();
                                const todayStr = now.getFullYear() + '-' + String(now.getMonth() + 1).padStart(2, '0') + '-' + String(now.getDate()).padStart(2, '0');
                                const currentHour = now.getHours();
                                const currentMinute = now.getMinutes();
                                const currentTimeVal = currentHour * 60 + currentMinute;
                                
                                availableSlots.forEach(slot => {
                                    const endParts = slot.end.split(':');
                                    const endHour = parseInt(endParts[0], 10);
                                    const endMinute = parseInt(endParts[1], 10);
                                    const endTimeVal = endHour * 60 + endMinute;
                                    
                                    const isPast = (selectedDate === todayStr && currentTimeVal >= endTimeVal);
                                    if (isPast) {
                                        slotSelect.innerHTML += `<option value="${slot.val}" disabled>${slot.val} WIB (Lewat)</option>`;
                                    } else {
                                        slotSelect.innerHTML += `<option value="${slot.val}">${slot.val} WIB</option>`;
                                    }
                                });
                            }
                        </script>
                        {{-- Section: Tipe --}}
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

                        {{-- Panel Pencarian Teman Anggota Kelompok --}}
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
                                        <i class="bi bi-search" style="transform:none;"></i>
                                        <input type="text" id="searchSiswaInput" class="form-control" placeholder="Cari nama atau NIS..." onkeyup="filterSiswaList()" style="padding-left: 35px;">
                                    </div>
                                </div>
                            </div>
                            <input type="hidden" name="student_id" value="{{ $student?->id ?? '' }}">
                            <div id="additionalStudentsContainer"></div>
                            <div id="studentListContainer" class="student-select-card" style="max-height: 200px; overflow-y: auto;">
                                @php $currentStudent = $student ?? Auth::user()->student; @endphp
                                @foreach(\App\Models\Student::with('class')->get() as $s)
                                    @if(!$currentStudent || $s->id != $currentStudent->id)
                                        <div class="student-option d-flex justify-content-between align-items-center"
                                             data-student-id="{{ $s->id }}"
                                             onclick="selectStudent('{{ $s->id }}', '{{ addslashes($s->full_name) }}', this)"
                                             data-name="{{ strtolower($s->full_name) }}"
                                             data-tingkat="{{ strtolower('kelas ' . ($s->class?->grade ?? '')) }}">
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
                            <input type="text" name="topic" class="form-control" placeholder="Contoh: Kendala belajar, masalah pribadi" required>
                        </div>
                        <div class="mb-2">
                            <label class="form-label">Deskripsi Singkat</label>
                            <textarea name="description" class="form-control" rows="3" placeholder="Jelaskan secara singkat apa yang ingin dibicarakan..."></textarea>
                        </div>
                    </div>
                    <div class="modal-footer" style="background:#f8f9ff; border-top:1px solid #eef1f8; padding: 16px 28px;">
                        <button type="button" class="btn btn-secondary px-4" data-bs-dismiss="modal" style="border-radius:10px;">Batal</button>
                        <button type="submit" class="btn btn-primary px-4 shadow-sm" style="border-radius:10px;"><i class="bi bi-send me-2"></i>Kirim Pengajuan</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    @include('include.script')

    <script>
        // Modal reset & select Type logic
        document.addEventListener('DOMContentLoaded', function() {
            var modalEl = document.getElementById('modalAjukanKonseling');
            if (modalEl) {
                modalEl.addEventListener('hidden.bs.modal', function () {
                    modalEl.querySelector('form').reset();
                    document.getElementById('requestedDateInputSiswa').value = '';
                    
                    const slotSelect = document.getElementById('slotWaktuSelect');
                    if(slotSelect) {
                        slotSelect.disabled = true;
                        slotSelect.innerHTML = '<option value="">Pilih Guru BK Dahulu...</option>';
                    }
                    
                    document.getElementById('weekendAlertSiswa').classList.add('d-none');
                    document.getElementById('additionalStudentsContainer').innerHTML = '';
                    document.getElementById('selectedStudentDisplay').classList.add('d-none');
                    document.getElementById('selectedStudentText').innerText = '';
                    
                    document.querySelectorAll('.type-card').forEach(c => c.classList.remove('active'));
                    document.querySelector('.type-card[data-type="individu"]').classList.add('active');
                    document.getElementById('typeHidden').value = 'individu';
                    
                    const panel = document.getElementById('searchSiswaPanel');
                    if(panel) {
                        panel.classList.add('d-none');
                        document.querySelectorAll('.student-option').forEach(opt => {
                            opt.classList.remove('selected');
                            const badge = opt.querySelector('.badge');
                            if(badge) {
                                badge.textContent = 'Pilih';
                                badge.className = 'badge bg-primary bg-opacity-10 text-primary border border-primary border-opacity-25 px-2 py-1';
                            }
                        });
                    }

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
                });
            }
        });

        function selectType(type, el) {
            document.querySelectorAll('.type-card').forEach(c => c.classList.remove('active'));
            el.classList.add('active');
            document.getElementById('typeHidden').value = type;
            
            const panel = document.getElementById('searchSiswaPanel');
            if(type === 'kelompok') {
                panel.classList.remove('d-none');
            } else {
                panel.classList.add('d-none');
                document.getElementById('additionalStudentsContainer').innerHTML = '';
                document.getElementById('selectedStudentDisplay').classList.add('d-none');
                
                document.querySelectorAll('.student-option').forEach(opt => {
                    opt.classList.remove('selected');
                    const badge = opt.querySelector('.badge');
                    if(badge) {
                        badge.textContent = 'Pilih';
                        badge.className = 'badge bg-primary bg-opacity-10 text-primary border border-primary border-opacity-25 px-2 py-1';
                    }
                });
            }
        }

        // ===== Search Functions =====
        function selectModalTingkat(value, label) {
            document.getElementById('modalFilterTingkat').value = value;
            document.getElementById('modalTingkatBtn').querySelector('span').innerText = label;
            filterSiswaList();
        }

        function filterSiswaList() {
            const search = (document.getElementById('searchSiswaInput').value || '').toLowerCase();
            const tingkat = (document.getElementById('modalFilterTingkat').value || '').toLowerCase();
            let count = 0;
            
            document.querySelectorAll('#studentListContainer .student-option').forEach(function(el) {
                const dataName = (el.getAttribute('data-name') || '').toLowerCase();
                const dataTingkat = (el.getAttribute('data-tingkat') || '').toLowerCase();
                const matchSearch = !search || dataName.includes(search);
                const matchTingkat = !tingkat || dataTingkat.includes(tingkat);
                
                if (matchSearch && matchTingkat) {
                    el.style.setProperty('display', 'flex', 'important');
                    count++;
                } else {
                    el.style.setProperty('display', 'none', 'important');
                }
            });
            
            const noResult = document.getElementById('noResultKonselingSiswa');
            if(noResult) {
                if(count === 0) noResult.classList.remove('d-none');
                else noResult.classList.add('d-none');
            }
        }

        function selectStudent(id, name, el) {
            const isSelected = el.classList.contains('selected');
            const container = document.getElementById('additionalStudentsContainer');
            
            if (isSelected) {
                el.classList.remove('selected');
                const badge = el.querySelector('.badge');
                if(badge) {
                    badge.textContent = 'Pilih';
                    badge.className = 'badge bg-primary bg-opacity-10 text-primary border border-primary border-opacity-25 px-2 py-1';
                }
                const input = document.getElementById('student_input_' + id);
                if (input) input.remove();
            } else {
                el.classList.add('selected');
                const badge = el.querySelector('.badge');
                if(badge) {
                    badge.textContent = 'Terpilih';
                    badge.className = 'badge bg-success text-white px-2 py-1';
                }
                
                const input = document.createElement('input');
                input.type = 'hidden';
                input.name = 'additional_students[]';
                input.id = 'student_input_' + id;
                input.value = id;
                container.appendChild(input);
            }
            updateSelectedDisplay();
        }
        
        function updateSelectedDisplay() {
            const selectedNames = [];
            document.querySelectorAll('#studentListContainer .student-option.selected').forEach(el => {
                const name = el.querySelector('strong').innerText;
                selectedNames.push(name);
            });
            
            const display = document.getElementById('selectedStudentDisplay');
            if (selectedNames.length > 0) {
                document.getElementById('selectedStudentText').innerText = selectedNames.join(', ');
                display.classList.remove('d-none');
            } else {
                display.classList.add('d-none');
                document.getElementById('selectedStudentText').innerText = '';
            }
        }
    </script>
</body>

</html>
