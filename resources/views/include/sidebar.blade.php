<!-- ======= Sidebar ======= -->
<aside id="sidebar" class="sidebar">
    <ul class="sidebar-nav" id="sidebar-nav">
        @php
            $currentUser = auth()->user();
        @endphp

        {{-- Dashboard --}}
        <li class="nav-item">
            <a class="nav-link {{ request()->routeIs('dashboard') ? '' : 'collapsed' }}" href="{{ route('dashboard') }}">
                <i class="bi bi-grid-1x2-fill"></i>
                <span>Dashboard</span>
            </a>
        </li>

        <li class="nav-heading">Layanan Konseling</li>

        <li class="nav-item">
            <a class="nav-link {{ request()->routeIs('counseling.index') ? '' : 'collapsed' }}" href="{{ route('counseling.index') }}">
                <i class="bi bi-calendar2-check"></i>
                <span>Jadwal Konseling</span>
            </a>
        </li>

        <li class="nav-item">
            <a class="nav-link {{ request()->routeIs('counseling.report*') ? '' : 'collapsed' }}" href="{{ route('counseling.report') }}">
                <i class="bi bi-file-earmark-bar-graph"></i>
                <span>Rekapan Bulanan</span>
            </a>
        </li>

        <li class="nav-heading">Data Utama</li>

        <li class="nav-item">
            <a class="nav-link {{ request()->routeIs('siswa.*') ? '' : 'collapsed' }}" href="{{ route('siswa.tampil') }}">
                <i class="bi bi-mortarboard"></i>
                <span>Data Siswa</span>
            </a>
        </li>

        <li class="nav-item">
            <a class="nav-link {{ request()->routeIs('kelas.*') ? '' : 'collapsed' }}" href="{{ route('kelas.tampil') }}">
                <i class="bi bi-door-open"></i>
                <span>Data Kelas</span>
            </a>
        </li>

        <li class="nav-item">
            <a class="nav-link {{ request()->routeIs('studykasus.*') ? '' : 'collapsed' }}" href="{{ route('studykasus.tampil') }}">
                <i class="bi bi-journal-bookmark-fill"></i>
                <span>Buku Kasus</span>
            </a>
        </li>

        <li class="nav-item">
            <a class="nav-link {{ request()->routeIs('ortu.*') ? '' : 'collapsed' }}" href="{{ route('ortu.tampil') }}">
                <i class="bi bi-person-hearts"></i>
                <span>Data Orang Tua</span>
            </a>
        </li>

        <li class="nav-heading">Informasi & Skoring</li>

        <li class="nav-item">
            <a class="nav-link {{ request()->routeIs('point.*') ? '' : 'collapsed' }}" href="{{ route('point.tampil') }}">
                <i class="bi bi-exclamation-diamond"></i>
                <span>Data Poin Pelanggaran</span>
            </a>
        </li>

        <li class="nav-item">
            <a class="nav-link {{ request()->routeIs('kategori.*') ? '' : 'collapsed' }}" href="{{ route('kategori.tampil') }}">
                <i class="bi bi-tags"></i>
                <span>Kategori Poin</span>
            </a>
        </li>

        <li class="nav-item">
            <a class="nav-link {{ request()->routeIs('dataprestasi.*') ? '' : 'collapsed' }}" href="{{ route('dataprestasi.tampil') }}">
                <i class="bi bi-trophy-fill"></i>
                <span>Prestasi Siswa</span>
            </a>
        </li>

        <li class="nav-heading">Pengaturan</li>

        <li class="nav-item">
            <a class="nav-link {{ request()->routeIs('settings.index') ? '' : 'collapsed' }}" href="{{ route('settings.index') }}">
                <i class="bi bi-gear-fill"></i>
                <span>Pengaturan</span>
            </a>
        </li>

        <li class="nav-item">
            <a class="nav-link {{ request()->routeIs('help.center') ? '' : 'collapsed' }}" href="{{ route('help.center') }}">
                <i class="bi bi-life-preserver"></i>
                <span>Bantuan</span>
            </a>
        </li>
    </ul>
</aside><!-- End Sidebar-->
