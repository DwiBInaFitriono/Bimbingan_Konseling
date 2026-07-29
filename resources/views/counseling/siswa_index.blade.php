<!DOCTYPE html>
<html lang="en">

<head>
    @include('include.style')
    <style>
        .input-icon-group {
            position: relative;
        }
        .input-icon-group i {
            position: absolute;
            left: 12px;
            top: 50%;
            transform: translateY(-50%);
            color: #6c757d;
        }
        .input-icon-group .form-control {
            padding-left: 36px;
            border-radius: 8px;
            border: 1px solid #ced4da;
            transition: all 0.2s ease-in-out;
        }
        .student-select-card {
            height: 180px;
            overflow-y: auto;
            border: 1px solid #e0e6ed;
            border-radius: 8px;
        }
        .student-option {
            cursor: pointer;
            padding: 8px 12px;
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

            {{-- Ringkasan Status Siswa --}}
            <div class="row">
                <div class="col-lg-4">
                    <div class="card">
                        <div class="card-body pt-4">
                            <h5 class="card-title p-0 mb-3">Profil Singkat Siswa</h5>
                            <div class="d-flex align-items-center mb-3">
                                <div class="rounded-circle bg-light p-3 me-3 text-primary fs-3">
                                    <i class="bi bi-person-badge"></i>
                                </div>
                                <div>
                                    <small class="text-muted d-block">Nama & NIS</small>
                                    <strong>{{ $student?->full_name ?? Auth::user()->name }}</strong>
                                    <div class="text-muted small">NIS: {{ $student?->nis ?? '-' }}</div>
                                </div>
                            </div>
                            <div class="d-flex align-items-center mb-3">
                                <div class="rounded-circle bg-light p-3 me-3 text-info fs-3">
                                    <i class="bi bi-building"></i>
                                </div>
                                <div>
                                    <small class="text-muted d-block">Kelas & Jurusan</small>
                                    <strong>{{ $student?->class?->school_class_name ?? 'Belum terdaftar di kelas' }}</strong>
                                </div>
                            </div>
                            <div class="d-flex align-items-center">
                                <div class="rounded-circle bg-light p-3 me-3 text-warning fs-3">
                                    <i class="bi bi-shield-check"></i>
                                </div>
                                <div>
                                    <small class="text-muted d-block">Status Kedisiplinan</small>
                                    @if(($student?->status ?? 'aman') == 'aman')
                                        <span class="badge bg-success fs-6"><i class="bi bi-check-circle me-1"></i>Aman ({{ $student?->total_points ?? 0 }} Poin)</span>
                                    @elseif(($student?->status ?? 'aman') == 'peringatan')
                                        <span class="badge bg-warning text-dark fs-6"><i class="bi bi-exclamation-triangle me-1"></i>Peringatan ({{ $student?->total_points ?? 0 }} Poin)</span>
                                    @else
                                        <span class="badge bg-danger fs-6"><i class="bi bi-x-octagon me-1"></i>Bahaya ({{ $student?->total_points ?? 0 }} Poin)</span>
                                    @endif
                                </div>
                            </div>

                            @if($student && in_array($student->status, ['peringatan', 'bahaya']))
                                <hr class="my-3">
                                <div class="alert alert-danger border-0 d-flex flex-column align-items-start p-3 mb-0 rounded-3" style="background-color: rgba(220, 53, 69, 0.05);">
                                    <div class="d-flex align-items-center mb-2">
                                        <i class="bi bi-exclamation-octagon-fill fs-4 me-2 text-danger"></i>
                                        <strong class="text-danger" style="font-size: 0.95rem;">
                                            @if($student->status === 'peringatan')
                                                Surat Peringatan 1 (SP 1) Diterbitkan
                                            @else
                                                Surat Peringatan 2 (SP 2 / Berat) Diterbitkan
                                            @endif
                                        </strong>
                                    </div>
                                    <p class="small text-muted mb-3" style="font-size: 0.825rem; line-height: 1.5;">Pihak Bimbingan Konseling (BK) telah menerbitkan surat peringatan resmi untuk Anda berdasarkan akumulasi poin pelanggaran tata tertib.</p>
                                    <a href="{{ route('siswa.cetak.peringatan', $student->id) }}" target="_blank" class="btn btn-sm btn-danger px-3 py-1.5 fw-semibold shadow-sm rounded-2">
                                        <i class="bi bi-file-earmark-pdf me-1"></i>Lihat & Cetak Surat SP
                                    </a>
                                </div>
                            @endif
                        </div>
                    </div>
                </div>

                <div class="col-lg-8">
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
                                            <th>Status & Catatan</th>
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
                                                    <small class="text-muted fw-semibold"><i class="bi bi-calendar3 me-1 text-primary"></i>{{ $s->requested_date?->format('d M Y') }} - {{ \Carbon\Carbon::parse($s->requested_time)->format('H:i') }} WIB</small>
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
                                                    <div class="text-muted small text-truncate" style="max-width: 250px;" title="{{ $s->notes }}">{{ $s->notes ?? '-' }}</div>
                                                </td>
                                            </tr>
                                        @empty
                                            <tr>
                                                <td colspan="6" class="text-center py-5 text-muted">
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
    <div class="modal fade" id="modalAjukanKonseling" tabindex="-1">
        <div class="modal-dialog modal-dialog-centered modal-lg">
            <div class="modal-content border-0 shadow-lg rounded-3">
                <form method="POST" action="{{ route('counseling.store') }}">
                    @csrf
                    <div class="modal-header modal-header-custom p-3 px-4">
                        <h5 class="modal-title fw-bold d-flex align-items-center">
                            <i class="bi bi-calendar-plus-fill fs-4 me-2 text-primary"></i>Form Pengajuan Jadwal Konseling
                        </h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                    </div>
                    <div class="modal-body p-4">
                        <div class="row g-3 mb-3">
                            <div class="col-md-6">
                                <label class="form-label fw-semibold">Tanggal Usulan Pertemuan</label>
                                <input type="date" name="requested_date" id="requestedDateInputSiswa" class="form-control" min="{{ date('Y-m-d') }}" onchange="checkSelectedDate(this, 'requestedTimeSelectSiswa', 'weekendAlertSiswa')" required>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label fw-semibold">Pilihan Jam</label>
                                <select name="requested_time" id="requestedTimeSelectSiswa" class="form-select" disabled required>
                                    <option value="">Pilih Tanggal Dahulu...</option>
                                </select>
                            </div>
                            <div class="col-12 d-none" id="weekendAlertSiswa">
                                <div class="alert alert-warning py-2 px-3 mb-0" style="font-size: 0.85rem;">
                                    <i class="bi bi-exclamation-triangle-fill me-2"></i>Layanan konseling hanya tersedia pada hari kerja (Senin - Jumat) jam 07:00 s.d 15:00.
                                </div>
                            </div>
                        </div>
                        <div class="mb-3">
                            <label class="form-label fw-semibold">Tipe Konseling</label>
                            <select name="type" id="counselingTypeSelect" class="form-select" onchange="handleTypeChange()" required>
                                <option value="individu">Konseling Individu (Pribadi)</option>
                                <option value="kelompok">Konseling Kelompok / Teman</option>
                            </select>
                        </div>

                        {{-- Panel Pencarian Teman Anggota Kelompok --}}
                        <div class="mb-3 d-none border rounded p-3 bg-light bg-opacity-50" id="searchSiswaPanel">
                            <label class="form-label fw-bold text-dark">
                                <i class="bi bi-people text-primary me-1"></i>Pilih Teman / Anggota Kelompok
                            </label>
                            
                            <div class="row g-2 mb-2">
                                <div class="col-md-5 col-sm-6">
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
                                <div class="col-md-7 col-sm-6">
                                    <div class="input-icon-group">
                                        <i class="bi bi-search"></i>
                                        <input type="text" id="searchSiswaInput" class="form-control" placeholder="Cari nama atau NIS teman..." onkeyup="filterSiswaList()">
                                    </div>
                                </div>
                            </div>

                            <input type="hidden" name="student_id" value="{{ $student?->id }}">
                            <div id="additionalStudentsContainer"></div>
                            
                            <div class="student-select-card" id="studentListContainer">
                                @foreach(\App\Models\Student::with('class')->get() as $s)
                                    @if(!$student || $s->id != $student->id)
                                        <div class="student-option d-flex justify-content-between align-items-center" 
                                             data-student-id="{{ $s->id }}"
                                             onclick="selectStudent('{{ $s->id }}', '{{ addslashes($s->full_name) }}', this)"
                                             data-search="{{ strtolower($s->full_name . ' ' . $s->nis . ' ' . ($s->class?->school_class_name ?? '') . ' kelas ' . ($s->class?->grade ?? '')) }}">
                                            <div>
                                                <strong class="text-dark d-block" style="font-size: 0.9rem;">{{ $s->full_name }}</strong>
                                                <small class="text-muted" style="font-size: 0.75rem;">NIS: {{ $s->nis }} | Kelas {{ $s->class?->grade ?? '-' }} - {{ $s->class?->school_class_name ?? 'Tanpa Kelas' }}</small>
                                            </div>
                                            <span class="badge bg-primary bg-opacity-10 text-primary border border-primary border-opacity-25 px-2 py-1" style="font-size: 0.7rem;">Pilih Teman</span>
                                        </div>
                                    @endif
                                @endforeach
                            </div>
                            <div id="selectedStudentDisplay" class="alert alert-info py-2 px-3 mt-2 mb-0 d-none" style="font-size: 0.85rem;">
                                <i class="bi bi-check-circle-fill me-2"></i>Teman Terpilih: <strong id="selectedStudentText"></strong>
                            </div>
                        </div>

                        <div class="mb-3">
                            <label class="form-label fw-semibold">Topik atau Topik Bahasan</label>
                            <input type="text" name="topic" class="form-control" placeholder="Contoh: Konsultasi Karir / Kendala Belajar / Masalah Pribadi" required>
                        </div>
                        <div class="mb-3">
                            <label class="form-label fw-semibold">Ceritakan Singkat Permasalahan / Tujuan Konseling</label>
                            <textarea name="description" class="form-control" rows="4" placeholder="Ketikkan apa yang ingin Anda diskusikan secara singkat... (Rahasia dijamin)"></textarea>
                        </div>
                    </div>
                    <div class="modal-footer bg-light p-3">
                        <button type="button" class="btn btn-secondary px-3" data-bs-dismiss="modal">Batal</button>
                        <button type="submit" class="btn btn-primary px-4">Kirim Pengajuan</button>
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

        document.getElementById('searchKonselingSiswa')?.addEventListener('input', function() {
            const q = this.value.toLowerCase();
            const rows = document.querySelectorAll('#tabelKonselingSiswa tbody tr');
            let found = 0;
            rows.forEach(row => {
                const text = row.textContent.toLowerCase();
                const match = text.includes(q);
                row.style.display = match ? '' : 'none';
                if (match) found++;
            });
            const noResult = document.getElementById('noResultKonselingSiswa');
            if (noResult) {
                noResult.classList.toggle('d-none', found > 0 || q === '');
            }
        });

        // State variables to track additional students for group counseling
        let additionalStudentIds = [];

        function handleTypeChange() {
            const type = document.getElementById('counselingTypeSelect').value;
            const searchPanel = document.getElementById('searchSiswaPanel');
            if (type === 'kelompok') {
                searchPanel.classList.remove('d-none');
            } else {
                searchPanel.classList.add('d-none');
                // Reset selected additional students if switched back to individu
                additionalStudentIds = [];
                updateStudentSelectionUI();
                document.querySelectorAll('.student-option').forEach(opt => opt.classList.remove('selected'));
            }
        }

        function filterSiswaList() {
            let input = document.getElementById('searchSiswaInput').value.toLowerCase();
            let fTingkat = document.getElementById('modalFilterTingkat').value.toLowerCase();
            let options = document.querySelectorAll('.student-option');
            
            options.forEach(opt => {
                let text = opt.getAttribute('data-search');
                let matchSearch = text.includes(input) || input === '';
                let matchTingkat = text.includes(fTingkat) || fTingkat === '';
                
                if (matchSearch && matchTingkat) {
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

        function selectStudent(id, name, element) {
            if (additionalStudentIds.includes(id)) {
                // Deselect student
                additionalStudentIds = additionalStudentIds.filter(item => item !== id);
                if (element) element.classList.remove('selected');
            } else {
                // Select student
                additionalStudentIds.push(id);
                if (element) element.classList.add('selected');
            }
            updateStudentSelectionUI();
        }

        function updateStudentSelectionUI() {
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

            let display = document.getElementById('selectedStudentDisplay');
            let textSpan = document.getElementById('selectedStudentText');

            if (additionalStudentIds.length === 0) {
                display.classList.add('d-none');
                textSpan.innerText = '';
                return;
            }

            let namesList = [];
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
        document.getElementById('modalAjukanKonseling')?.addEventListener('hidden.bs.modal', function () {
            this.querySelector('form').reset();
            additionalStudentIds = [];
            updateStudentSelectionUI();
            document.getElementById('searchSiswaInput').value = '';
            document.getElementById('modalFilterTingkat').value = '';
            document.getElementById('modalTingkatBtn').querySelector('span').textContent = 'Tingkat: Semua';
            document.getElementById('searchSiswaPanel').classList.add('d-none');
            document.querySelectorAll('.student-option').forEach(opt => {
                opt.classList.remove('d-none');
                opt.classList.add('d-flex');
                opt.classList.remove('selected');
            });
        });
    </script>
</body>

</html>
