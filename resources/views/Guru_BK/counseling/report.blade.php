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
            <h1>Laporan Bulanan Konseling</h1>
            <nav>
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Beranda</a></li>
                    <li class="breadcrumb-item"><a href="{{ route('counseling.index') }}">Data Konseling</a></li>
                    <li class="breadcrumb-item active">Laporan Bulanan</li>
                </ol>
            </nav>
        </div>

        <div class="col-12">
            <div class="card border-0 shadow-sm rounded-3">
                <div class="card-body pt-4">
                    {{-- Filter Form --}}
                    <form method="GET" action="{{ route('counseling.report') }}" class="row g-3 mb-4 p-3 bg-light rounded-3 border">
                        <div class="col-md-3">
                            <label class="form-label fw-bold">Bulan</label>
                            <select name="month" class="form-select">
                                @for($m = 1; $m <= 12; $m++)
                                    <option value="{{ sprintf('%02d', $m) }}" {{ $month == sprintf('%02d', $m) ? 'selected' : '' }}>
                                        {{ date('F', mktime(0, 0, 0, $m, 10)) }}
                                    </option>
                                @endfor
                            </select>
                        </div>
                        <div class="col-md-3">
                            <label class="form-label fw-bold">Tahun</label>
                            <select name="year" class="form-select">
                                @for($y = date('Y') - 5; $y <= date('Y'); $y++)
                                    <option value="{{ $y }}" {{ $year == $y ? 'selected' : '' }}>
                                        {{ $y }}
                                    </option>
                                @endfor
                            </select>
                        </div>
                        <div class="col-md-3">
                            <label class="form-label fw-bold">Status</label>
                            <select name="status" class="form-select">
                                <option value="all" {{ $status == 'all' ? 'selected' : '' }}>Semua Status</option>
                                <option value="menunggu" {{ $status == 'menunggu' ? 'selected' : '' }}>Menunggu</option>
                                <option value="disetujui" {{ $status == 'disetujui' ? 'selected' : '' }}>Disetujui</option>
                                <option value="selesai" {{ $status == 'selesai' ? 'selected' : '' }}>Selesai</option>
                                <option value="ditolak" {{ $status == 'ditolak' ? 'selected' : '' }}>Ditolak</option>
                            </select>
                        </div>
                        <div class="col-md-3 d-flex align-items-end gap-2">
                            <button type="submit" class="btn btn-primary fw-semibold w-100" title="Terapkan Filter">
                                <i class="bi bi-search me-1"></i>Filter
                            </button>
                            <a href="{{ route('counseling.report.pdf', ['month' => $month, 'year' => $year, 'status' => $status]) }}" class="btn btn-danger fw-semibold w-100" target="_blank" title="Cetak PDF">
                                <i class="bi bi-printer me-1"></i>Cetak PDF
                            </a>
                        </div>
                    </form>

                    <h5 class="fw-bold text-dark mb-3">Daftar Sesi Konseling: {{ date('F', mktime(0, 0, 0, $month, 10)) }} {{ $year }}</h5>
                    <div class="table-responsive">
                        <table class="table table-hover align-middle table-bordered">
                            <thead class="table-light text-center">
                                <tr>
                                    <th>No</th>
                                    <th>Tanggal</th>
                                    <th>Siswa</th>
                                    <th>Kelas</th>
                                    <th>Topik & Jenis</th>
                                    <th>Status</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse ($reports as $c)
                                    <tr>
                                        <td class="text-center">{{ $loop->iteration }}</td>
                                        <td class="text-center">{{ $c->requested_date ? \Carbon\Carbon::parse($c->requested_date)->format('d M Y') : '-' }}</td>
                                        <td>
                                            <strong>{{ $c->student?->full_name ?? 'Siswa Terhapus' }}</strong><br>
                                            @if($c->type === 'kelompok' && $c->additionalStudents()->isNotEmpty())
                                                <div class="my-1 small text-start">
                                                    <span class="text-muted d-block" style="font-size: 0.7rem; font-weight: 600; text-transform: uppercase;">Anggota:</span>
                                                    <div class="d-flex flex-wrap gap-1">
                                                        @foreach($c->additionalStudents() as $addStudent)
                                                            <span class="badge bg-light text-dark border px-1 py-0.5" style="font-size: 0.7rem;">{{ $addStudent->full_name }}</span>
                                                        @endforeach
                                                    </div>
                                                </div>
                                            @endif
                                            <small class="text-muted">NIS: {{ $c->student?->nis }}</small>
                                        </td>
                                        <td class="text-center">{{ $c->student?->class?->school_class_name ?? '-' }}</td>
                                        <td>
                                            <span class="fw-semibold">{{ $c->topic }}</span>
                                            <span class="badge bg-secondary ms-1">{{ ucfirst($c->type) }}</span>
                                        </td>
                                        <td class="text-center">
                                            @if($c->status == 'selesai')
                                                <span class="badge bg-success bg-opacity-10 text-success border border-success">Selesai</span>
                                            @elseif($c->status == 'disetujui')
                                                <span class="badge bg-primary bg-opacity-10 text-primary border border-primary">Disetujui</span>
                                            @elseif($c->status == 'ditolak' || $c->status == 'dibatalkan')
                                                <span class="badge bg-danger bg-opacity-10 text-danger border border-danger">{{ ucfirst($c->status) }}</span>
                                            @else
                                                <span class="badge bg-warning bg-opacity-10 text-warning border border-warning">Menunggu</span>
                                            @endif
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="6" class="text-center py-4 text-muted">Tidak ada data konseling pada bulan ini.</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </main>

    @include('include.footer')
    @include('include.script')
</body>
</html>
