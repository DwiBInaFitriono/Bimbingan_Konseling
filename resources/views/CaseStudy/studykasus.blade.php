<!DOCTYPE html>
<html lang="en">

<head>
    @include('include.style')
</head>

<body>
    <!-- ======= Header ======= -->
    @include('include.header')
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
                                <a href="{{ url('tambahstudykasus') }}" class="btn btn-primary fw-semibold shadow-sm">
                                    <i class="bi bi-plus-circle me-1"></i>Catat Pelanggaran Baru
                                </a>
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
                                            <th>Nama Siswa</th>
                                            <th>Kelas & Jurusan</th>
                                            <th>Pelapor (Guru)</th>
                                            <th>Jenis Kasus</th>
                                            <th>Judul Kasus</th>
                                            <th>Tanggal</th>
                                            <th>Status / Sanksi</th>
                                            <th class="text-center" style="width: 15%;">Aksi</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @forelse ($datastudykasus as $index => $data)
                                            <tr>
                                                <td>{{ $index + 1 }}</td>
                                                <td>
                                                    <div class="fw-bold text-dark">{{ $data->student->full_name ?? 'N/A' }}</div>
                                                </td>
                                                <td>
                                                    {{ $data->student->class?->grade ?? '-' }} - {{ $data->student->class?->school_class_name ?? 'Tanpa Kelas' }}
                                                </td>
                                                <td>
                                                    <span class="text-muted"><i class="bi bi-person-workspace me-1"></i>{{ $data->reporter_teacher ?? '-' }}</span>
                                                </td>
                                                <td>
                                                    <span class="badge bg-secondary">{{ ucfirst($data->case_type) }}</span>
                                                </td>
                                                <td>{{ $data->case_title }}</td>
                                                <td>{{ \Carbon\Carbon::parse($data->case_date)->format('d M Y') }}</td>
                                                <td>
                                                    @if(strtolower($data->status) == 'selesai')
                                                        <span class="badge bg-success bg-opacity-10 text-success border border-success px-2 py-1 d-block mb-1"><i class="bi bi-check-all me-1"></i>Selesai</span>
                                                    @elseif(strtolower($data->status) == 'tindak_lanjut')
                                                        <span class="badge bg-warning bg-opacity-10 text-warning border border-warning px-2 py-1 d-block mb-1"><i class="bi bi-arrow-right-circle me-1"></i>Tindak Lanjut</span>
                                                    @else
                                                        <span class="badge bg-primary bg-opacity-10 text-primary border border-primary px-2 py-1 d-block mb-1"><i class="bi bi-clock-history me-1"></i>Proses</span>
                                                    @endif

                                                    @if($data->points_applied)
                                                        <span class="badge bg-danger bg-opacity-10 text-danger border border-danger px-2 py-1 d-block"><i class="bi bi-exclamation-triangle-fill me-1"></i>+{{ $data->points_sanction }} Poin</span>
                                                    @else
                                                        <span class="badge bg-secondary bg-opacity-10 text-secondary border border-secondary px-2 py-1 d-block"><i class="bi bi-dash-circle me-1"></i>Poin Belum Diproses</span>
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
                                                                <a class="dropdown-item d-flex align-items-center py-2 text-warning" href="{{ url('/editstudykasus/' . $data->id) }}">
                                                                    <i class="bi bi-pencil-square me-2"></i> Edit
                                                                </a>
                                                            </li>
                                                            <li>
                                                                <a class="dropdown-item d-flex align-items-center py-2 text-danger" href="{{ url('hapusstudykasus/' . $data->id) }}" onclick="return confirm('Yakin ingin menghapus catatan pelanggaran ini?')">
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
    document.getElementById('searchStudiKasus').addEventListener('input', function() {
        const q = this.value.toLowerCase();
        const rows = document.querySelectorAll('#tabelStudiKasus tbody tr');
        let found = 0;
        rows.forEach(row => {
            const text = row.textContent.toLowerCase();
            const match = text.includes(q);
            row.style.display = match ? '' : 'none';
            if (match) found++;
        });
        document.getElementById('noResultStudiKasus').classList.toggle('d-none', found > 0 || q === '');
    });
    </script>
</body>

</html>
