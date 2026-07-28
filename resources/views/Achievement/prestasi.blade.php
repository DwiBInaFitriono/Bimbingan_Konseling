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
            <h1>Prestasi Siswa</h1>
            <nav>
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Beranda</a></li>
                    <li class="breadcrumb-item active">Prestasi Siswa</li>
                </ol>
            </nav>
        </div>

        <div class="col-12">
            <div class="card border-0 shadow-sm rounded-3">
                <div class="card-body pt-3">
                    <div class="d-flex justify-content-between align-items-center mb-3 pb-2 border-bottom">
                        <h5 class="card-title fw-bold text-dark m-0 p-0">Daftar Prestasi Siswa</h5>
                        <a href="{{ url('tambahprestasi') }}" class="btn btn-success px-3 py-2 rounded-2 fw-semibold shadow-sm">
                            <i class="bi bi-trophy me-1"></i>Tambah Prestasi Baru
                        </a>
                    </div>

                    {{-- Search Bar --}}
                    <div class="mb-3">
                        <div class="input-group" style="max-width: 360px;">
                            <span class="input-group-text bg-white border-end-0"><i class="bi bi-search text-muted"></i></span>
                            <input type="text" id="searchPrestasi" class="form-control border-start-0 ps-0" placeholder="Cari nama siswa, prestasi, kategori...">
                        </div>
                    </div>

                    <div class="table-responsive">
                        <table class="table table-hover align-middle" id="tabelPrestasi">
                            <thead class="table-light">
                                <tr>
                                    <th style="width:45px;">No</th>
                                    <th>Nama Siswa</th>
                                    <th>Nama Prestasi</th>
                                    <th>Tanggal</th>
                                    <th class="text-center">Tingkat</th>
                                    <th>Kategori</th>
                                    <th class="text-center">Status</th>
                                    <th class="text-center">Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse ($prestasi as $data)
                                    <tr>
                                        <td>{{ $loop->iteration }}</td>
                                        <td class="fw-bold text-dark">{{ $data->student->full_name }}</td>
                                        <td>
                                            <span class="text-success fw-semibold">
                                                <i class="bi bi-trophy me-1"></i>{{ $data->achievement_name }}
                                            </span>
                                        </td>
                                        <td>
                                            <small class="text-muted">
                                                <i class="bi bi-calendar me-1"></i>
                                                {{ \Carbon\Carbon::parse($data->achievement_date)->format('d/m/Y') }}
                                            </small>
                                        </td>
                                        <td class="text-center">
                                            @php
                                                $levelClass = match(strtolower($data->achievement_level ?? '')) {
                                                    'internasional' => 'bg-danger text-white',
                                                    'nasional'      => 'bg-primary text-white',
                                                    'provinsi'      => 'bg-info text-white',
                                                    'kabupaten', 'kota' => 'bg-warning text-dark',
                                                    default         => 'bg-secondary text-white',
                                                };
                                            @endphp
                                            <span class="badge {{ $levelClass }} fw-bold px-2 py-1">{{ $data->achievement_level }}</span>
                                        </td>
                                        <td><small class="text-muted">{{ $data->achievement_category }}</small></td>
                                        <td class="text-center">
                                            @if(strtolower($data->achievement_status ?? '') === 'terverifikasi')
                                                <span class="badge bg-success text-white">
                                                    <i class="bi bi-patch-check me-1"></i>Terverifikasi
                                                </span>
                                            @else
                                                <span class="badge bg-warning text-dark">
                                                    <i class="bi bi-clock me-1"></i>{{ $data->achievement_status }}
                                                </span>
                                            @endif
                                        </td>
                                        <td class="text-center">
                                            <div class="dropdown d-inline-block">
                                                <button class="btn btn-sm btn-outline-secondary dropdown-toggle px-3" type="button" data-bs-toggle="dropdown" data-bs-boundary="viewport" data-bs-popper-config='{"strategy":"fixed"}' aria-expanded="false">
                                                    <i class="bi bi-gear me-1"></i> Aksi
                                                </button>
                                                <ul class="dropdown-menu dropdown-menu-end shadow-sm" style="max-height: 250px; overflow-y: auto;">
                                                    <li>
                                                        <a class="dropdown-item d-flex align-items-center py-2 text-warning" href="{{ url('/editprestasi/' . $data->id) }}">
                                                            <i class="bi bi-pencil me-2"></i> Edit
                                                        </a>
                                                    </li>
                                                    <li>
                                                        <a class="dropdown-item d-flex align-items-center py-2 text-danger" href="{{ url('/hapusprestasi/' . $data->id) }}" onclick="return confirm('Yakin ingin menghapus data prestasi ini?')">
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
                                            <i class="bi bi-trophy fs-2 d-block mb-2 opacity-25"></i>
                                            Belum ada data prestasi siswa tercatat.
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                        <p id="noResultPrestasi" class="text-center text-muted py-3 d-none">Tidak ada data yang cocok dengan pencarian.</p>
                    </div>
                </div>
            </div>
        </div>
    </main><!-- End #main -->

    @include('include.footer')
    <a href="#" class="back-to-top d-flex align-items-center justify-content-center"><i class="bi bi-arrow-up-short"></i></a>
    @include('include.script')

    <script>
    document.getElementById('searchPrestasi').addEventListener('input', function() {
        const q = this.value.toLowerCase();
        const rows = document.querySelectorAll('#tabelPrestasi tbody tr');
        let found = 0;
        rows.forEach(row => {
            const text = row.textContent.toLowerCase();
            const match = text.includes(q);
            row.style.display = match ? '' : 'none';
            if (match) found++;
        });
        document.getElementById('noResultPrestasi').classList.toggle('d-none', found > 0 || q === '');
    });
    </script>
</body>

</html>
