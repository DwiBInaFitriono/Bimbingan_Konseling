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
        .poin-modal .modal-content {
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
        .poin-modal .modal-body > .form-section-label:first-child {
            margin-top: 0;
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
            <h1>Data Poin Pelanggaran & Skoring Siswa</h1>
            <nav>
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Beranda</a></li>
                    <li class="breadcrumb-item active">Poin Pelanggaran</li>
                </ol>
            </nav>
        </div>


        <div class="col-12">
            <div class="card border-0 shadow-sm rounded-3">
                <div class="card-body pt-3">
                    <div class="d-flex justify-content-between align-items-center mb-3 pb-2 border-bottom">
                        <h5 class="card-title fw-bold text-dark m-0 p-0">Riwayat Catatan Pelanggaran Siswa</h5>
                        <button type="button" class="btn btn-primary px-3 py-2 rounded-2 fw-semibold shadow-sm" data-bs-toggle="modal" data-bs-target="#modalTambahPoin">
                            <i class="bi bi-plus-circle me-1"></i>Catat Pelanggaran Baru
                        </button>
                    </div>

                    {{-- Search Bar --}}
                    <div class="mb-3">
                        <div class="input-group" style="max-width: 360px;">
                            <span class="input-group-text bg-white border-end-0"><i class="bi bi-search text-muted"></i></span>
                            <input type="text" id="searchPoin" class="form-control border-start-0 ps-0" placeholder="Cari siswa, NIS, kelas, pelanggaran...">
                        </div>
                    </div>

                    <div class="table-responsive">
                        <table class="table table-hover align-middle" id="tabelPoin">
                            <thead class="table-light">
                                <tr>
                                    <th>No</th>
                                    <th>Siswa & Kelas</th>
                                    <th>Pelanggaran & Bobot</th>
                                    <th>Total Poin & Status</th>
                                    <th class="text-center">Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse ($datapoint as $point)
                                    <tr>
                                        <td>{{ $loop->iteration }}</td>
                                        <td>
                                            <div class="fw-bold text-dark">{{ $point->student?->full_name ?? 'Siswa Tidak Ditemukan' }}</div>
                                            <small class="text-muted"><i class="bi bi-person-badge me-1"></i>NIS: {{ $point->student?->nis ?? '-' }} | Kelas {{ $point->student?->class?->grade ?? '-' }} - {{ $point->student?->class?->school_class_name ?? '-' }}</small>
                                        </td>
                                        <td>
                                            <div class="fw-semibold text-dark text-truncate" style="max-width: 250px;" title="{{ $point->violation }}">{{ $point->violation }}</div>
                                            @if($point->description)
                                                <div class="text-muted small text-truncate mb-1" style="max-width: 250px;" title="{{ $point->description }}">{{ $point->description }}</div>
                                            @endif
                                            <div>
                                                <span class="badge bg-danger bg-opacity-10 text-danger border border-danger px-2 py-1 me-1">+{{ $point->point_number }} Poin</span>
                                                <small class="text-muted fw-semibold"><i class="bi bi-calendar me-1"></i>{{ $point->violation_date ? \Carbon\Carbon::parse($point->violation_date)->format('d M Y') : $point->created_at?->format('d M Y') }}</small>
                                            </div>
                                        </td>
                                        <td>
                                            <div class="fs-6 text-dark fw-bold mb-1">{{ $point->student?->total_points ?? 0 }} Poin</div>
                                            @php $st = $point->student?->status ?? 'aman'; @endphp
                                            @if($st == 'aman')
                                                <span class="badge bg-success bg-opacity-10 text-success border border-success px-2 py-1 d-inline-block"><i class="bi bi-shield-check me-1"></i>Aman</span>
                                            @elseif($st == 'peringatan')
                                                <span class="badge bg-warning bg-opacity-10 text-warning border border-warning px-2 py-1 d-inline-block"><i class="bi bi-exclamation-triangle me-1"></i>Peringatan</span>
                                            @else
                                                <span class="badge bg-danger bg-opacity-10 text-danger border border-danger px-2 py-1 d-inline-block"><i class="bi bi-x-octagon me-1"></i>Bahaya</span>
                                            @endif
                                        </td>
                                        <td class="text-center">
                                            <div class="dropdown d-inline-block">
                                                <button class="btn btn-sm btn-outline-secondary dropdown-toggle px-3" type="button" data-bs-toggle="dropdown" data-bs-boundary="viewport" data-bs-popper-config='{"strategy":"fixed"}' aria-expanded="false">
                                                    <i class="bi bi-gear me-1"></i> Aksi
                                                </button>
                                                <ul class="dropdown-menu dropdown-menu-end shadow-sm" style="max-height: 250px; overflow-y: auto;">
                                                    <li>
                                                        <a class="dropdown-item d-flex align-items-center py-2 text-primary" href="{{ route('siswa.cetak.peringatan', $point->student_id) }}" target="_blank">
                                                            <i class="bi bi-printer me-2"></i> Cetak Peringatan/SP
                                                        </a>
                                                    </li>
                                                    @if($point->student?->status === 'bahaya')
                                                    <li>
                                                        <a class="dropdown-item d-flex align-items-center py-2 text-danger fw-semibold" href="{{ route('siswa.cetak.peringatan', $point->student_id) }}?type=expel" target="_blank">
                                                            <i class="bi bi-exclamation-octagon me-2"></i> Cetak SP Keluar
                                                        </a>
                                                    </li>
                                                    @endif
                                                    <li>
                                                        <a class="dropdown-item d-flex align-items-center py-2 text-danger" href="javascript:void(0)" onclick="swalConfirm('Yakin ingin menghapus data pelanggaran ini?', function(){ window.location='{{ url('hapuspoint/' . $point->id) }}'; })">
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
                                            <i class="bi bi-exclamation-octagon fs-2 d-block mb-2 opacity-25"></i>
                                            Belum ada data pelanggaran tercatat.
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                        <p id="noResultPoin" class="text-center text-muted py-3 d-none">Tidak ada data yang cocok dengan pencarian.</p>
                    </div>
                </div>
            </div>
        </div>
    </main>

    {{-- MODAL TAMBAH POIN PELANGGARAN DENGAN PENCARIAN SISWA LIVE --}}
    <div class="modal fade poin-modal" id="modalTambahPoin" tabindex="-1" data-bs-backdrop="static" data-bs-keyboard="false">
        <div class="modal-dialog modal-dialog-centered modal-lg">
            <div class="modal-content">
                <form method="POST" action="{{ url('simpanpoint') }}">
                    @csrf
                    <div class="modal-header modal-header-custom">
                        <h5 class="modal-title fw-bold"><i class="bi bi-exclamation-triangle-fill me-2"></i>Catat Pelanggaran Siswa</h5>
                        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" style="filter: brightness(0) invert(1); opacity: 0.8; position: relative; z-index: 2;"></button>
                    </div>
                    <div class="modal-body p-4">
                        <div class="form-section-label"><i class="bi bi-search"></i>Pencarian Siswa</div>
                            
                            {{-- Input & Filter Pencarian Siswa Live --}}
                            <div class="row g-2 mb-2">
                                <div class="col-md-4 col-sm-6">
                                    <select class="form-select text-secondary fw-semibold w-100" id="modalPoinFilterTingkat" onchange="filterSiswaPoinList()">
                                        <option value="">Tingkat: Semua</option>
                                        <option value="kelas 10">Kelas 10 (X)</option>
                                        <option value="kelas 11">Kelas 11 (XI)</option>
                                        <option value="kelas 12">Kelas 12 (XII)</option>
                                    </select>
                                </div>
                                <div class="col-md-4 col-sm-6">
                                    <select class="form-select text-secondary fw-semibold w-100" id="modalPoinFilterJurusan" onchange="filterSiswaPoinList()">
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
                                        <input type="text" id="searchSiswaPoin" class="form-control" placeholder="Cari nama/NIS..." onkeyup="filterSiswaPoinList()">
                                    </div>
                                </div>
                            </div>

                            <input type="hidden" name="student_id" id="selectedStudentPoinId" required>
                            
                            <div class="student-select-card" id="studentPoinContainer">
                                @foreach($datasiswa as $s)
                                    <div class="student-option student-poin-option d-flex justify-content-between align-items-center" 
                                         onclick="selectStudentPoin('{{ $s->id }}', '{{ addslashes($s->full_name) }}', '{{ $s->nis }}', '{{ $s->class?->grade ?? '' }}', '{{ addslashes($s->class?->school_class_name ?? 'Tanpa Kelas') }}', '{{ $s->total_points }}', this)"
                                         data-name="{{ strtolower($s->full_name) }}"
                                         data-tingkat="{{ strtolower('kelas ' . ($s->class?->grade ?? '')) }}"
                                         data-jurusan="{{ strtolower($s->class?->school_class_name ?? '') }}">
                                        <div>
                                            <strong class="text-dark d-block">{{ $s->full_name }}</strong>
                                            <small class="text-muted">NIS: {{ $s->nis }} | Kelas {{ $s->class?->grade ?? '-' }} - {{ $s->class?->school_class_name ?? 'Tanpa Kelas' }}</small>
                                        </div>
                                        <span class="badge bg-danger bg-opacity-10 text-danger border border-danger border-opacity-25 px-2 py-1">Poin Saat Ini: {{ $s->total_points }}</span>
                                    </div>
                                @endforeach
                            </div>
                            <div id="selectedStudentPoinDisplay" class="alert alert-info py-2 px-3 mt-2 d-none">
                                <i class="bi bi-check-circle-fill me-2"></i>Siswa Terpilih: <strong id="selectedStudentPoinText"></strong>
                            </div>

                        <div class="form-section-label"><i class="bi bi-info-circle"></i>Detail Pelanggaran</div>
                        <div class="mb-3">
                            <label class="form-label fw-semibold">Bentuk Pelanggaran <span class="text-danger">*</span></label>
                            <div class="input-icon-group">
                                <i class="bi bi-journal-x"></i>
                                <input type="text" name="violation" class="form-control" placeholder="Misal: Datang Terlambat / Merokok / Tidak Memakai Seragam" required>
                            </div>
                        </div>

                        <div class="row g-3 mb-3">
                            <div class="col-md-6">
                                <label class="form-label fw-semibold">Bobot Poin Pelanggaran <span class="text-danger">*</span></label>
                                <div class="input-icon-group">
                                    <i class="bi bi-calculator"></i>
                                    <input type="number" name="point_number" class="form-control" min="1" placeholder="Contoh: 10" required>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label fw-semibold">Tanggal Kejadian <span class="text-danger">*</span></label>
                                <div class="input-icon-group">
                                    <i class="bi bi-calendar-event"></i>
                                    <input type="date" name="violation_date" class="form-control" value="{{ date('Y-m-d') }}" required>
                                </div>
                            </div>
                        </div>

                        <div class="mb-3">
                            <label class="form-label fw-semibold">Keterangan / Detail Kejadian</label>
                            <textarea name="description" class="form-control" rows="3" placeholder="Tuliskan lokasi, saksi, atau kronologi singkat kejadian..."></textarea>
                        </div>
                    </div>
                    <div class="modal-footer border-0 px-4 pb-4">
                        <button type="button" class="btn btn-secondary px-4 shadow-sm" style="border-radius:10px;" data-bs-dismiss="modal"><i class="bi bi-x-lg me-1" style="font-size:0.8rem;"></i> Batal</button>
                        <button type="submit" class="btn btn-primary px-4 shadow-sm" style="border-radius:10px; background:#4154f1; border-color:#4154f1;"><i class="bi bi-send me-2"></i>Simpan Poin</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    @include('include.footer')
    @include('include.script')

    <script>
        document.getElementById('searchPoin')?.addEventListener('input', function() {
            const q = this.value.toLowerCase();
            const rows = document.querySelectorAll('#tabelPoin tbody tr');
            let found = 0;
            rows.forEach(row => {
                const text = row.textContent.toLowerCase();
                const match = text.includes(q);
                row.style.display = match ? '' : 'none';
                if (match) found++;
            });
            const noResult = document.getElementById('noResultPoin');
            if (noResult) {
                noResult.classList.toggle('d-none', found > 0 || q === '');
            }
        });

        function filterSiswaPoinList() {
            let input = document.getElementById('searchSiswaPoin').value.toLowerCase();
            let fTingkat = document.getElementById('modalPoinFilterTingkat').value.toLowerCase();
            let fJurusan = document.getElementById('modalPoinFilterJurusan').value.toLowerCase();
            let options = document.querySelectorAll('.student-poin-option');
            
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



        function selectStudentPoin(id, name, nis, grade, className, currentPoints, element) {
            document.getElementById('selectedStudentPoinId').value = id;
            
            document.querySelectorAll('.student-poin-option').forEach(el => el.classList.remove('selected'));
            element.classList.add('selected');

            let display = document.getElementById('selectedStudentPoinDisplay');
            let textSpan = document.getElementById('selectedStudentPoinText');
            textSpan.innerText = name + ' (NIS: ' + nis + ' | Kelas ' + (grade ? grade + ' - ' : '') + className + ' | Poin Saat Ini: ' + currentPoints + ')';
            display.classList.remove('d-none');
        }

        // Reset modal on close
        document.getElementById('modalTambahPoin')?.addEventListener('hidden.bs.modal', function () {
            this.querySelector('form').reset();
            document.getElementById('searchSiswaPoin').value = '';
            document.getElementById('selectedStudentPoinId').value = '';
            document.getElementById('modalPoinFilterTingkat').value = '';
            document.getElementById('modalPoinFilterJurusan').value = '';
            document.getElementById('selectedStudentPoinDisplay').classList.add('d-none');
            
            document.querySelectorAll('.student-poin-option').forEach(opt => {
                opt.classList.remove('d-none');
                opt.classList.add('d-flex');
                opt.classList.remove('selected');
            });
        });
    </script>
</body>

</html>
