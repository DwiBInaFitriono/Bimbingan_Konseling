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
            <h1>Data Orang Tua / Wali</h1>
            <nav>
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Beranda</a></li>
                    <li class="breadcrumb-item active">Data Orang Tua</li>
                </ol>
            </nav>
        </div>

        <div class="col-12">
            <div class="card border-0 shadow-sm rounded-3">
                <div class="card-body pt-3">
                    <div class="d-flex justify-content-between align-items-center mb-3 pb-2 border-bottom">
                        <h5 class="card-title fw-bold text-dark m-0 p-0">Daftar Data Orang Tua / Wali Siswa</h5>
                        <a href="{{ url('tambahparent') }}" class="btn btn-primary px-3 py-2 rounded-2 fw-semibold shadow-sm">
                            <i class="bi bi-person-plus-fill me-1"></i>Tambah Orang Tua Baru
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
                                <input type="text" id="searchParent" class="form-control border-start-0 ps-0" placeholder="Cari nama orang tua, pekerjaan, atau nama siswa...">
                            </div>
                        </div>
                    </div>

                    <div class="table-responsive">
                        <table class="table table-hover align-middle" id="tabelParent">
                            <thead class="table-light">
                                <tr>
                                    <th style="width:45px;">No</th>
                                    <th>Nama Orang Tua / Wali</th>
                                    <th>Orang Tua Dari (Siswa & Kelas)</th>
                                    <th>Alamat</th>
                                    <th>Pekerjaan</th>
                                    <th>No. Telepon</th>
                                    <th class="text-center">Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse ($parentdata as $parent)
                                    <tr>
                                        <td>{{ $loop->iteration }}</td>
                                        <td>
                                            <div class="fw-bold text-dark">{{ $parent->parent_full_name }}</div>
                                        </td>
                                        <td>
                                            @forelse($parent->student as $s)
                                                <div class="mb-1">
                                                    <span class="fw-semibold text-dark">{{ $s->full_name }}</span>
                                                    @if($s->class)
                                                        <span class="badge bg-primary bg-opacity-10 text-primary border border-primary border-opacity-25 ms-1">
                                                            Kelas {{ $s->class->grade }} - {{ $s->class->school_class_name }}
                                                        </span>
                                                    @else
                                                        <span class="badge bg-secondary bg-opacity-10 text-secondary border ms-1">Tanpa Kelas</span>
                                                    @endif
                                                </div>
                                            @empty
                                                <span class="text-muted small">Belum terhubung ke siswa</span>
                                            @endforelse
                                        </td>
                                        <td>{{ $parent->address }}</td>
                                        <td>{{ $parent->job }}</td>
                                        <td>
                                            <small><i class="bi bi-whatsapp me-1 text-success"></i>{{ $parent->phone_number }}</small>
                                        </td>
                                        <td class="text-center">
                                            <div class="dropdown d-inline-block">
                                                <button class="btn btn-sm btn-outline-secondary dropdown-toggle px-3" type="button" data-bs-toggle="dropdown" data-bs-boundary="viewport" data-bs-popper-config='{"strategy":"fixed"}' aria-expanded="false">
                                                    <i class="bi bi-gear me-1"></i> Aksi
                                                </button>
                                                <ul class="dropdown-menu dropdown-menu-end shadow-sm" style="max-height: 250px; overflow-y: auto;">
                                                    @php
                                                        $rawPhone = $parent->phone_number;
                                                        $cleanPhone = preg_replace('/[^0-9]/', '', $rawPhone);
                                                        if (str_starts_with($cleanPhone, '0')) {
                                                            $cleanPhone = '62' . substr($cleanPhone, 1);
                                                        }
                                                        
                                                        $studentNames = $parent->student->pluck('full_name')->implode(', ');
                                                        $templateText = "Halo Bapak/Ibu " . $parent->parent_full_name . ", saya dari pihak Bimbingan Konseling (BK) sekolah. Ingin berkonsultasi mengenai perkembangan/bimbingan ananda " . ($studentNames ?: 'siswa') . ". Apakah Bapak/Ibu ada waktu luang untuk berkomunikasi?";
                                                        $waUrl = "https://wa.me/" . $cleanPhone . "?text=" . rawurlencode($templateText);
                                                    @endphp
                                                    <li>
                                                        <a class="dropdown-item d-flex align-items-center py-2 text-success" href="{{ $waUrl }}" target="_blank">
                                                            <i class="bi bi-whatsapp me-2"></i> Hubungi WA
                                                        </a>
                                                    </li>
                                                    <li>
                                                        <a class="dropdown-item d-flex align-items-center py-2 text-warning" href="{{ url('/editparent/' . $parent->id) }}">
                                                            <i class="bi bi-pencil me-2"></i> Edit
                                                        </a>
                                                    </li>
                                                    <li>
                                                        <a class="dropdown-item d-flex align-items-center py-2 text-danger" href="{{ url('/hapusparent/' . $parent->id) }}" onclick="return confirm('Yakin ingin menghapus data orang tua ini?')">
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
                                            <i class="bi bi-people fs-2 d-block mb-2 opacity-25"></i>
                                            Belum ada data orang tua terdaftar.
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                        <p id="noResultParent" class="text-center text-muted py-3 d-none">Tidak ada data yang cocok dengan pencarian.</p>
                    </div>
                </div>
            </div>
        </div>
    </main><!-- End #main -->

    @include('include.footer')
    <a href="#" class="back-to-top d-flex align-items-center justify-content-center"><i class="bi bi-arrow-up-short"></i></a>
    @include('include.script')

    <script>
    function filterTableParents() {
        const q = document.getElementById('searchParent').value.toLowerCase();
        const fTingkat = document.getElementById('filterTingkat').value.toLowerCase();
        const fJurusan = document.getElementById('filterJurusan').value.toLowerCase();
        
        const rows = document.querySelectorAll('#tabelParent tbody tr');
        let found = 0;
        
        rows.forEach(row => {
            if (row.children.length === 1) return; // Skip empty state row
            
            const text = row.textContent.toLowerCase();
            
            // Check text match
            const matchSearch = text.includes(q) || q === '';
            
            // For parents, the student & class column is the 3rd column (index 2)
            const classCol = row.children[2].textContent.toLowerCase();
            
            // Check tingkat match (contains "kelas 10" etc.)
            const matchTingkat = classCol.includes(fTingkat) || fTingkat === '';
            
            // Check jurusan & rombel match (contains "rpl 1" etc.)
            const matchJurusan = classCol.includes(fJurusan) || fJurusan === '';
            
            const match = matchSearch && matchTingkat && matchJurusan;
            
            row.style.display = match ? '' : 'none';
            if (match) found++;
        });
        
        document.getElementById('noResultParent').classList.toggle('d-none', found > 0 || (q === '' && fTingkat === '' && fJurusan === ''));
    }

    function selectTingkatFilter(value, label) {
        document.getElementById('filterTingkat').value = value;
        document.getElementById('dropdownTingkatBtn').querySelector('span').textContent = label;
        filterTableParents();
    }

    function selectJurusanFilter(value, label) {
        document.getElementById('filterJurusan').value = value;
        document.getElementById('dropdownJurusanBtn').querySelector('span').textContent = label;
        filterTableParents();
    }

    document.getElementById('searchParent').addEventListener('input', filterTableParents);
    </script>
</body>

</html>
