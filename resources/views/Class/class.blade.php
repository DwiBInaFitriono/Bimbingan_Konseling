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
            <h1>Kelola Data Kelas SMK</h1>
            <nav>
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Beranda</a></li>
                    <li class="breadcrumb-item active">Data Kelas</li>
                </ol>
            </nav>
        </div>


        <div class="col-12">
            <div class="card border-0 shadow-sm rounded-3">
                <div class="card-body pt-3">
                    <div class="d-flex justify-content-between align-items-center mb-3 pb-2 border-bottom">
                        <h5 class="card-title fw-bold text-dark m-0 p-0">Daftar Rombongan Belajar / Kelas</h5>
                        <a href="{{ url('tambahkelas') }}" class="btn btn-primary px-3 py-2 rounded-2 fw-semibold shadow-sm">
                            <i class="bi bi-plus-circle me-1"></i>Tambah Kelas Baru
                        </a>
                    </div>

                    {{-- Search & Filter Bar --}}
                    <div class="row g-2 mb-3 align-items-center">
                        <div class="col-md-3 col-sm-6">
                            <div class="dropdown w-100">
                                <button class="form-select text-start text-secondary fw-semibold w-100" type="button" id="filterTingkatBtn" data-bs-toggle="dropdown" aria-expanded="false">
                                    <span>Tingkat: Semua</span>
                                </button>
                                <ul class="dropdown-menu w-100 shadow-sm" aria-labelledby="filterTingkatBtn">
                                    <li><a class="dropdown-item fw-semibold py-2" href="javascript:void(0)" onclick="selectFilterTingkat('', 'Tingkat: Semua')">Semua Tingkat</a></li>
                                    <li><hr class="dropdown-divider"></li>
                                    <li><a class="dropdown-item py-2" href="javascript:void(0)" onclick="selectFilterTingkat('10', 'Kelas 10 (X)')">Kelas 10 (X)</a></li>
                                    <li><a class="dropdown-item py-2" href="javascript:void(0)" onclick="selectFilterTingkat('11', 'Kelas 11 (XI)')">Kelas 11 (XI)</a></li>
                                    <li><a class="dropdown-item py-2" href="javascript:void(0)" onclick="selectFilterTingkat('12', 'Kelas 12 (XII)')">Kelas 12 (XII)</a></li>
                                </ul>
                                <input type="hidden" id="filterTingkat" value="">
                            </div>
                        </div>
                        <div class="col-md-5 col-sm-6">
                            <div class="input-group">
                                <span class="input-group-text bg-white border-end-0"><i class="bi bi-search text-muted"></i></span>
                                <input type="text" id="searchKelas" class="form-control border-start-0 ps-0" placeholder="Cari nama kelas atau tingkat...">
                            </div>
                        </div>
                    </div>

                    <div class="table-responsive">
                        <table class="table table-hover align-middle" id="tabelKelas">
                            <thead class="table-light">
                                <tr>
                                    <th>No</th>
                                    <th>Tingkat / Angkatan</th>
                                    <th>Nama Kelas</th>
                                    <th>Kompetensi Keahlian / Jurusan</th>
                                    <th>Jumlah Siswa</th>
                                    <th class="text-center text-nowrap">Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse ($datakelas as $kelas)
                                    <tr class="kelas-row" data-tingkat="{{ $kelas->grade }}">
                                        <td>{{ $loop->iteration }}</td>
                                        <td>
                                            @if($kelas->grade == '10')
                                                <span class="badge bg-primary bg-opacity-10 text-primary border border-primary px-3 py-1">Kelas 10 (X)</span>
                                            @elseif($kelas->grade == '11')
                                                <span class="badge bg-info bg-opacity-10 text-info border border-info px-3 py-1">Kelas 11 (XI)</span>
                                            @else
                                                <span class="badge bg-purple bg-opacity-10 text-dark border border-secondary px-3 py-1">Kelas 12 (XII)</span>
                                            @endif
                                        </td>
                                        <td><strong class="text-dark fs-6">{{ $kelas->school_class_name }}</strong></td>
                                        <td>{{ $kelas->school_class_major }}</td>
                                        <td>
                                            <span class="badge bg-secondary bg-opacity-10 text-secondary border px-2 py-1">
                                                <i class="bi bi-people me-1"></i>{{ $kelas->student_count ?? 0 }} Siswa
                                            </span>
                                        </td>
                                        <td class="text-center">
                                            <div class="dropdown d-inline-block">
                                                <button class="btn btn-sm btn-outline-secondary dropdown-toggle px-3" type="button" data-bs-toggle="dropdown" data-bs-boundary="viewport" data-bs-popper-config='{"strategy":"fixed"}' aria-expanded="false">
                                                    <i class="bi bi-gear me-1"></i> Aksi
                                                </button>
                                                <ul class="dropdown-menu dropdown-menu-end shadow-sm" style="max-height: 250px; overflow-y: auto;">
                                                    <li>
                                                        <a class="dropdown-item d-flex align-items-center py-2 text-warning" href="{{ url('/editkelas/' . $kelas->id) }}">
                                                            <i class="bi bi-pencil me-2"></i> Edit
                                                        </a>
                                                    </li>
                                                    <li>
                                                        <a class="dropdown-item d-flex align-items-center py-2 text-danger" href="{{ url('hapuskelas/' . $kelas->id) }}" onclick="return confirm('Yakin ingin menghapus kelas ini?')">
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
                                            <i class="bi bi-door-closed fs-2 d-block mb-2 opacity-25"></i>
                                            Belum ada data kelas terdaftar.
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                        <p id="noResultKelas" class="text-center text-muted py-3 d-none">Tidak ada data yang cocok dengan pencarian.</p>
                    </div>
                </div>
            </div>
        </div>
    </main>

    @include('include.footer')
    <a href="#" class="back-to-top d-flex align-items-center justify-content-center"><i class="bi bi-arrow-up-short"></i></a>
    @include('include.script')

    <script>
    function filterKelasList() {
        const q = document.getElementById('searchKelas').value.toLowerCase();
        const fTingkat = document.getElementById('filterTingkat').value.toLowerCase();
        const rows = document.querySelectorAll('#tabelKelas tbody tr.kelas-row');
        let found = 0;
        
        rows.forEach(row => {
            const rowTingkat = row.getAttribute('data-tingkat') || '';
            const text = row.textContent.toLowerCase();
            
            const matchSearch = text.includes(q) || q === '';
            const matchTingkat = rowTingkat === fTingkat || fTingkat === '';
            
            const match = matchSearch && matchTingkat;
            row.style.display = match ? '' : 'none';
            if (match) found++;
        });
        
        const noResult = document.getElementById('noResultKelas');
        if (noResult) {
            noResult.classList.toggle('d-none', found > 0 || (q === '' && fTingkat === ''));
        }
    }

    function selectFilterTingkat(value, label) {
        document.getElementById('filterTingkat').value = value;
        document.getElementById('filterTingkatBtn').querySelector('span').textContent = label;
        filterKelasList();
    }

    document.getElementById('searchKelas').addEventListener('input', filterKelasList);
    </script>
</body>

</html>
