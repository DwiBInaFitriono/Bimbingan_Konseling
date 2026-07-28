<!DOCTYPE html>
<html lang="en">

<head>
    @include('include.style')
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
                                            <th>Tanggal & Jam</th>
                                            <th>Topik Pertemuan</th>
                                            <th>Guru BK Pembimbing</th>
                                            <th>Status</th>
                                            <th>Catatan Guru BK</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @forelse($mySessions as $s)
                                            <tr>
                                                <td>{{ $loop->iteration }}</td>
                                                <td>
                                                    <strong>{{ $s->requested_date?->format('d M Y') }}</strong><br>
                                                    <small class="text-muted">{{ \Carbon\Carbon::parse($s->requested_time)->format('H:i') }} WIB</small>
                                                </td>
                                                <td>
                                                    <span class="fw-semibold">{{ $s->topic }}</span>
                                                    @if($s->description)
                                                        <br><small class="text-muted">{{ Str::limit($s->description, 40) }}</small>
                                                    @endif
                                                </td>
                                                <td>{{ $s->guruBk?->name ?? 'Akan Ditentukan' }}</td>
                                                <td>
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
                                                </td>
                                                <td>
                                                    <small class="text-muted">{{ $s->notes ?? '-' }}</small>
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
        <div class="modal-dialog">
            <div class="modal-content">
                <form method="POST" action="{{ route('counseling.store') }}">
                    @csrf
                    <div class="modal-header">
                        <h5 class="modal-title">Form Pengajuan Jadwal Konseling</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                    </div>
                    <div class="modal-body">
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label class="form-label">Tanggal Usulan Pertemuan</label>
                                <input type="date" name="requested_date" class="form-control" min="{{ date('Y-m-d') }}" required>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="form-label">Pilihan Jam</label>
                                <input type="time" name="requested_time" class="form-control" required>
                            </div>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Tipe Konseling</label>
                            <select name="type" class="form-select" required>
                                <option value="individu">Konseling Individu (Pribadi)</option>
                                <option value="kelompok">Konseling Kelompok / Teman</option>
                            </select>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Topik atau Topik Bahasan</label>
                            <input type="text" name="topic" class="form-control" placeholder="Contoh: Konsultasi Karir / Kendala Belajar / Masalah Pribadi" required>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Ceritakan Singkat Permasalahan / Tujuan Konseling</label>
                            <textarea name="description" class="form-control" rows="4" placeholder="Ketikkan apa yang ingin Anda diskusikan secara singkat... (Rahasia dijamin)"></textarea>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary btn-sm" data-bs-dismiss="modal">Batal</button>
                        <button type="submit" class="btn btn-primary btn-sm">Kirim Pengajuan</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    @include('include.script')

    <script>
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
    </script>
</body>

</html>
