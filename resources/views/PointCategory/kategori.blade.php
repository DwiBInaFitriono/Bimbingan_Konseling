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
            <h1>Kategori Poin Pelanggaran</h1>
            <nav>
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Beranda</a></li>
                    <li class="breadcrumb-item active">Kategori Poin</li>
                </ol>
            </nav>
        </div>

        <div class="col-12">
            <div class="card border-0 shadow-sm rounded-3">
                <div class="card-body pt-3">
                    <div class="d-flex justify-content-between align-items-center mb-3 pb-2 border-bottom">
                        <h5 class="card-title fw-bold text-dark m-0 p-0">Daftar Kategori Poin Pelanggaran</h5>
                        <a href="{{ url('tambahkategori') }}" class="btn btn-primary px-3 py-2 rounded-2 fw-semibold shadow-sm">
                            <i class="bi bi-plus-circle me-1"></i>Tambah Kategori Baru
                        </a>
                    </div>

                    {{-- Search Bar --}}
                    <div class="mb-3">
                        <div class="input-group" style="max-width: 360px;">
                            <span class="input-group-text bg-white border-end-0"><i class="bi bi-search text-muted"></i></span>
                            <input type="text" id="searchKategori" class="form-control border-start-0 ps-0" placeholder="Cari kategori atau tindak lanjut...">
                        </div>
                    </div>

                    <div class="table-responsive">
                        <table class="table table-hover align-middle" id="tabelKategori">
                            <thead class="table-light">
                                <tr>
                                    <th style="width:45px;">No</th>
                                    <th>Kategori & Tindak Lanjut</th>
                                    <th class="text-center">Range Poin</th>
                                    <th class="text-center">Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse ($datakategori as $data)
                                    <tr>
                                        <td>{{ $loop->iteration }}</td>
                                        <td>
                                            <div class="fw-bold text-dark">{{ $data->category_of_violation }}</div>
                                            <div class="text-muted small text-truncate" style="max-width: 350px;" title="{{ $data->follow_up }}">
                                                <i class="bi bi-arrow-return-right me-1"></i>{{ $data->follow_up }}
                                            </div>
                                        </td>
                                        <td class="text-center">
                                            <span class="badge bg-warning text-dark fw-bold px-2 py-1" style="font-size:0.85em;">{{ $data->category_score_min }}</span>
                                            <span class="text-muted mx-1">-</span>
                                            <span class="badge bg-danger text-white fw-bold px-2 py-1" style="font-size:0.85em;">{{ $data->category_score_max }}</span>
                                        </td>
                                        <td class="text-center">
                                            <div class="dropdown d-inline-block">
                                                <button class="btn btn-sm btn-outline-secondary dropdown-toggle px-3" type="button" data-bs-toggle="dropdown" data-bs-boundary="viewport" data-bs-popper-config='{"strategy":"fixed"}' aria-expanded="false">
                                                    <i class="bi bi-gear me-1"></i> Aksi
                                                </button>
                                                <ul class="dropdown-menu dropdown-menu-end shadow-sm" style="max-height: 250px; overflow-y: auto;">
                                                    <li>
                                                        <a class="dropdown-item d-flex align-items-center py-2 text-warning" href="{{ url('/editkategori/' . $data->id) }}">
                                                            <i class="bi bi-pencil me-2"></i> Edit
                                                        </a>
                                                    </li>
                                                    <li>
                                                        <a class="dropdown-item d-flex align-items-center py-2 text-danger" href="{{ url('/hapuskategori/' . $data->id) }}" onclick="return confirm('Yakin ingin menghapus kategori ini?')">
                                                            <i class="bi bi-trash me-2"></i> Hapus
                                                        </a>
                                                    </li>
                                                </ul>
                                            </div>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="6" class="text-center py-5 text-muted">
                                            <i class="bi bi-collection fs-2 d-block mb-2 opacity-25"></i>
                                            Belum ada kategori poin pelanggaran.
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                        <p id="noResultKategori" class="text-center text-muted py-3 d-none">Tidak ada data yang cocok dengan pencarian.</p>
                    </div>
                </div>
            </div>
        </div>
    </main><!-- End #main -->

    @include('include.footer')
    <a href="#" class="back-to-top d-flex align-items-center justify-content-center"><i class="bi bi-arrow-up-short"></i></a>
    @include('include.script')

    <script>
    document.getElementById('searchKategori').addEventListener('input', function() {
        const q = this.value.toLowerCase();
        const rows = document.querySelectorAll('#tabelKategori tbody tr');
        let found = 0;
        rows.forEach(row => {
            const text = row.textContent.toLowerCase();
            const match = text.includes(q);
            row.style.display = match ? '' : 'none';
            if (match) found++;
        });
        document.getElementById('noResultKategori').classList.toggle('d-none', found > 0 || q === '');
    });
    </script>
</body>

</html>
