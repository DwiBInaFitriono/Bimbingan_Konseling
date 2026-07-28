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
            <h1>Data Siswa</h1>
            <nav>
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Beranda</a></li>
                    <li class="breadcrumb-item active">Data Siswa</li>
                </ol>
            </nav>
        </div>

        <div class="col-12">
            <div class="card border-0 shadow-sm rounded-3">
                <div class="card-body pt-3">
                    <div class="d-flex justify-content-between align-items-center mb-3 pb-2 border-bottom">
                        <h5 class="card-title fw-bold text-dark m-0 p-0">Daftar Seluruh Siswa Terdaftar</h5>
                        <a href="{{ url('tambah') }}" class="btn btn-primary px-3 py-2 rounded-2 fw-semibold shadow-sm">
                            <i class="bi bi-person-plus-fill me-1"></i>Tambah Siswa Baru
                        </a>
                    </div>

                    {{-- Filter & Search Bar --}}
                    <div class="row mb-3 g-2">
                        {{-- Dropdown Tingkat --}}
                        <div class="col-md-3 col-sm-6">
                            <div class="dropdown w-100">
                                <button class="form-select text-start text-secondary fw-semibold w-100" type="button" id="dropdownTingkatBtn" data-bs-toggle="dropdown" aria-expanded="false">
                                    <span>Tingkat: Semua</span>
                                </button>
                                <ul class="dropdown-menu w-100 shadow-sm" aria-labelledby="dropdownTingkatBtn">
                                    <li><a class="dropdown-item fw-semibold py-2" href="javascript:void(0)" onclick="selectTingkatFilter('', 'Tingkat: Semua')">Semua Tingkat</a></li>
                                    <li><hr class="dropdown-divider"></li>
                                    <li><a class="dropdown-item py-2" href="javascript:void(0)" onclick="selectTingkatFilter('kelas 10', 'Kelas 10 (X)')">Kelas 10 (X)</a></li>
                                    <li><a class="dropdown-item py-2" href="javascript:void(0)" onclick="selectTingkatFilter('kelas 11', 'Kelas 11 (XI)')">Kelas 11 (XI)</a></li>
                                    <li><a class="dropdown-item py-2" href="javascript:void(0)" onclick="selectTingkatFilter('kelas 12', 'Kelas 12 (XII)')">Kelas 12 (XII)</a></li>
                                </ul>
                                <input type="hidden" id="filterTingkat" value="">
                            </div>
                        </div>

                        {{-- Dropdown Jurusan & Rombel --}}
                        <div class="col-md-3 col-sm-6">
                            <div class="dropdown w-100">
                                <button class="form-select text-start text-secondary fw-semibold w-100" type="button" id="dropdownJurusanBtn" data-bs-toggle="dropdown" aria-expanded="false">
                                    <span>Jurusan: Semua</span>
                                </button>
                                <ul class="dropdown-menu w-100 shadow-sm" aria-labelledby="dropdownJurusanBtn" style="max-height: 250px; overflow-y: auto; border: 1px solid #ced4da;">
                                    <li><a class="dropdown-item fw-semibold py-2" href="javascript:void(0)" onclick="selectJurusanFilter('', 'Semua Jurusan')">Semua Jurusan & Rombel</a></li>
                                    <li><hr class="dropdown-divider"></li>
                                    
                                    <li><h6 class="dropdown-header fw-bold text-primary pt-2 pb-1">Rekayasa Perangkat Lunak</h6></li>
                                    <li><a class="dropdown-item py-1 ps-4" href="javascript:void(0)" onclick="selectJurusanFilter('rpl 1', 'RPL 1')">RPL 1</a></li>
                                    <li><a class="dropdown-item py-1 ps-4" href="javascript:void(0)" onclick="selectJurusanFilter('rpl 2', 'RPL 2')">RPL 2</a></li>
                                    <li><a class="dropdown-item py-1 ps-4" href="javascript:void(0)" onclick="selectJurusanFilter('rpl 3', 'RPL 3')">RPL 3</a></li>
                                    
                                    <li><h6 class="dropdown-header fw-bold text-primary pt-2 pb-1">Manajemen Perkantoran</h6></li>
                                    <li><a class="dropdown-item py-1 ps-4" href="javascript:void(0)" onclick="selectJurusanFilter('mp 1', 'MP 1')">MP 1</a></li>
                                    <li><a class="dropdown-item py-1 ps-4" href="javascript:void(0)" onclick="selectJurusanFilter('mp 2', 'MP 2')">MP 2</a></li>
                                    <li><a class="dropdown-item py-1 ps-4" href="javascript:void(0)" onclick="selectJurusanFilter('mp 3', 'MP 3')">MP 3</a></li>

                                    <li><h6 class="dropdown-header fw-bold text-primary pt-2 pb-1">Akuntansi</h6></li>
                                    <li><a class="dropdown-item py-1 ps-4" href="javascript:void(0)" onclick="selectJurusanFilter('ak 1', 'AK 1')">AK 1</a></li>
                                    <li><a class="dropdown-item py-1 ps-4" href="javascript:void(0)" onclick="selectJurusanFilter('ak 2', 'AK 2')">AK 2</a></li>
                                    <li><a class="dropdown-item py-1 ps-4" href="javascript:void(0)" onclick="selectJurusanFilter('ak 3', 'AK 3')">AK 3</a></li>

                                    <li><h6 class="dropdown-header fw-bold text-primary pt-2 pb-1">Bisnis Digital</h6></li>
                                    <li><a class="dropdown-item py-1 ps-4" href="javascript:void(0)" onclick="selectJurusanFilter('bd 1', 'BD 1')">BD 1</a></li>
                                    <li><a class="dropdown-item py-1 ps-4" href="javascript:void(0)" onclick="selectJurusanFilter('bd 2', 'BD 2')">BD 2</a></li>
                                    <li><a class="dropdown-item py-1 ps-4" href="javascript:void(0)" onclick="selectJurusanFilter('bd 3', 'BD 3')">BD 3</a></li>

                                    <li><h6 class="dropdown-header fw-bold text-primary pt-2 pb-1">Desain Komunikasi Visual</h6></li>
                                    <li><a class="dropdown-item py-1 ps-4" href="javascript:void(0)" onclick="selectJurusanFilter('dkv 1', 'DKV 1')">DKV 1</a></li>
                                    <li><a class="dropdown-item py-1 ps-4" href="javascript:void(0)" onclick="selectJurusanFilter('dkv 2', 'DKV 2')">DKV 2</a></li>

                                    <li><h6 class="dropdown-header fw-bold text-primary pt-2 pb-1">Kriya Kreatif Batik dan Tekstil</h6></li>
                                    <li><a class="dropdown-item py-1 ps-4" href="javascript:void(0)" onclick="selectJurusanFilter('kkbt 1', 'KKBT 1')">KKBT 1</a></li>
                                    <li><a class="dropdown-item py-1 ps-4" href="javascript:void(0)" onclick="selectJurusanFilter('kkbt 2', 'KKBT 2')">KKBT 2</a></li>
                                </ul>
                                <input type="hidden" id="filterJurusan" value="">
                            </div>
                        </div>

                        {{-- Search Input --}}
                        <div class="col-md-6 col-sm-12">
                            <div class="input-group">
                                <span class="input-group-text bg-white border-end-0"><i class="bi bi-search text-muted"></i></span>
                                <input type="text" id="searchSiswa" class="form-control border-start-0 ps-0" placeholder="Cari nama siswa, NIS, atau status...">
                            </div>
                        </div>
                    </div>

                    <div class="table-responsive">
                        <table class="table table-hover align-middle" id="tabelSiswa">
                            <thead class="table-light">
                                <tr>
                                    <th>No</th>
                                    <th>Nama Siswa & NIS</th>
                                    <th>Angkatan & Kelas</th>
                                    <th>Gender</th>
                                    <th>Orang Tua / Wali</th>
                                    <th>No. HP</th>
                                    <th>Akun Login</th>
                                    <th>Status BK</th>
                                    <th class="text-center text-nowrap">Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse ($datasiswa as $siswa)
                                    <tr>
                                        <td>{{ $loop->iteration }}</td>
                                        <td>
                                            <div class="fw-bold text-dark">{{ $siswa->full_name }}</div>
                                            <small class="text-muted"><i class="bi bi-person-vcard me-1"></i>NIS: {{ $siswa->nis }}</small>
                                        </td>
                                        <td>
                                            <span class="badge bg-info bg-opacity-10 text-info border border-info border-opacity-25 me-1">Kelas {{ $siswa->class?->grade ?? '-' }}</span>
                                            <strong class="text-dark">{{ $siswa->class?->school_class_name ?? 'Tanpa Kelas' }}</strong>
                                        </td>
                                        <td>
                                            @if (strtolower($siswa->gender) == 'laki-laki')
                                                <span class="badge bg-primary bg-opacity-10 text-primary border border-primary border-opacity-25 px-2 py-1"><i class="bi bi-gender-male me-1"></i>Laki-laki</span>
                                            @else
                                                <span class="badge bg-danger bg-opacity-10 text-danger border border-danger border-opacity-25 px-2 py-1"><i class="bi bi-gender-female me-1"></i>Perempuan</span>
                                            @endif
                                        </td>
                                        <td>{{ $siswa->parent?->parent_full_name ?? '-' }}</td>
                                        <td>
                                            @if($siswa->phone_number)
                                                <small><i class="bi bi-whatsapp text-success me-1"></i>{{ $siswa->phone_number }}</small>
                                            @else
                                                -
                                            @endif
                                        </td>
                                        <td>
                                            @if($siswa->user?->email)
                                                <small class="text-success"><i class="bi bi-check-circle me-1"></i>{{ $siswa->user->email }}</small>
                                            @else
                                                <small class="text-danger"><i class="bi bi-x-circle me-1"></i>Belum ada akun</small>
                                            @endif
                                        </td>
                                        <td>
                                            @if($siswa->status == 'aman')
                                                <span class="badge bg-success bg-opacity-10 text-success border border-success border-opacity-25"><i class="bi bi-shield-check me-1"></i>Aman ({{ $siswa->total_points }} Poin)</span>
                                            @elseif($siswa->status == 'peringatan')
                                                <span class="badge bg-warning bg-opacity-10 text-warning border border-warning border-opacity-25"><i class="bi bi-exclamation-triangle me-1"></i>Peringatan ({{ $siswa->total_points }} Poin)</span>
                                            @else
                                                <span class="badge bg-danger bg-opacity-10 text-danger border border-danger border-opacity-25"><i class="bi bi-x-octagon me-1"></i>Bahaya ({{ $siswa->total_points }} Poin)</span>
                                            @endif
                                        </td>
                                        <td class="text-center">
                                            <div class="dropdown d-inline-block">
                                                <button class="btn btn-sm btn-outline-secondary dropdown-toggle px-3" type="button" data-bs-toggle="dropdown" data-bs-boundary="viewport" data-bs-popper-config='{"strategy":"fixed"}' aria-expanded="false">
                                                    <i class="bi bi-gear me-1"></i> Aksi
                                                </button>
                                                <ul class="dropdown-menu dropdown-menu-end shadow-sm" style="max-height: 250px; overflow-y: auto;">
                                                    <li>
                                                        <a class="dropdown-item d-flex align-items-center py-2 text-warning" href="{{ url('/edit/' . $siswa->id) }}">
                                                            <i class="bi bi-pencil me-2"></i> Edit
                                                        </a>
                                                    </li>
                                                    <li>
                                                        <a class="dropdown-item d-flex align-items-center py-2 text-danger" href="{{ url('hapus/' . $siswa->id) }}" onclick="return confirm('Yakin ingin menghapus siswa ini?')">
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
                                            <i class="bi bi-people fs-2 d-block mb-2 opacity-25"></i>
                                            Belum ada data siswa terdaftar.
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                        <p id="noResultSiswa" class="text-center text-muted py-3 d-none">Tidak ada data yang cocok dengan pencarian.</p>
                    </div>
                </div>
            </div>
        </div>
    </main>

    @include('include.footer')
    <a href="#" class="back-to-top d-flex align-items-center justify-content-center"><i class="bi bi-arrow-up-short"></i></a>
    @include('include.script')

    <script>
    function filterTableSiswa() {
        const q = document.getElementById('searchSiswa').value.toLowerCase();
        const fTingkat = document.getElementById('filterTingkat').value.toLowerCase();
        const fJurusan = document.getElementById('filterJurusan').value.toLowerCase();
        
        const rows = document.querySelectorAll('#tabelSiswa tbody tr');
        let found = 0;
        
        rows.forEach(row => {
            if (row.children.length === 1) return; // Skip empty state row
            
            const text = row.textContent.toLowerCase();
            
            // Check text match
            const matchSearch = text.includes(q) || q === '';
            
            // Check class match from column 3 (index 2)
            const classCol = row.children[2].textContent.toLowerCase();
            
            // Check tingkat match (contains "kelas 10" etc.)
            const matchTingkat = classCol.includes(fTingkat) || fTingkat === '';
            
            // Check jurusan & rombel match (contains "rpl 1" etc.)
            const matchJurusan = classCol.includes(fJurusan) || fJurusan === '';
            
            const match = matchSearch && matchTingkat && matchJurusan;
            
            row.style.display = match ? '' : 'none';
            if (match) found++;
        });
        
        document.getElementById('noResultSiswa').classList.toggle('d-none', found > 0 || (q === '' && fTingkat === '' && fJurusan === ''));
    }

    function selectTingkatFilter(value, label) {
        document.getElementById('filterTingkat').value = value;
        document.getElementById('dropdownTingkatBtn').querySelector('span').textContent = label;
        filterTableSiswa();
    }

    function selectJurusanFilter(value, label) {
        document.getElementById('filterJurusan').value = value;
        document.getElementById('dropdownJurusanBtn').querySelector('span').textContent = label;
        filterTableSiswa();
    }

    document.getElementById('searchSiswa').addEventListener('input', filterTableSiswa);
    </script>
</body>

</html>
