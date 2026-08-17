<!-- ======= Sidebar ======= -->
<aside id="sidebar" class="sidebar">
    <ul class="sidebar-nav" id="sidebar-nav">
        @php
            $currentUser = auth()->user();
        @endphp

        {{-- Menu untuk Guru BK / Admin --}}
        <li class="nav-item">
            <a class="nav-link {{ request()->routeIs('dashboard') ? '' : 'collapsed' }}" href="{{ route('dashboard') }}">
                <i class="bi bi-grid"></i>
                <span>Dashboard</span>
            </a>
        </li>

        <li class="nav-heading">LAYANAN KONSELING</li>

        <li class="nav-item">
            <a class="nav-link {{ request()->routeIs('counseling.index') ? '' : 'collapsed' }}" href="{{ route('counseling.index') }}">
                <i class="bi bi-calendar-event"></i>
                <span>Jadwal Konseling</span>
            </a>
        </li>

        <li class="nav-item">
            <a class="nav-link {{ request()->routeIs('counseling.report*') ? '' : 'collapsed' }}" href="{{ route('counseling.report') }}">
                <i class="bi bi-file-earmark-pdf"></i>
                <span>Rekapan Bulanan & PDF</span>
            </a>
        </li>

        <li class="nav-heading">DATA UTAMA</li>

        <li class="nav-item">
            <a class="nav-link {{ request()->routeIs('siswa.*') ? '' : 'collapsed' }}" href="{{ route('siswa.tampil') }}">
                <i class="bi bi-people"></i>
                <span>Data Siswa</span>
            </a>
        </li>

        <li class="nav-item">
            <a class="nav-link {{ request()->routeIs('kelas.*') ? '' : 'collapsed' }}" href="{{ route('kelas.tampil') }}">
                <i class="bi bi-building"></i>
                <span>Data Kelas</span>
            </a>
        </li>

        <li class="nav-item">
            <a class="nav-link {{ request()->routeIs('studykasus.*') ? '' : 'collapsed' }}" href="{{ route('studykasus.tampil') }}">
                <i class="bi bi-journal-bookmark"></i>
                <span>Buku Kasus</span>
            </a>
        </li>

        <li class="nav-item">
            <a class="nav-link {{ request()->routeIs('ortu.*') ? '' : 'collapsed' }}" href="{{ route('ortu.tampil') }}">
                <i class="bi bi-people-fill"></i>
                <span>Data Orang Tua</span>
            </a>
        </li>

        <li class="nav-heading">INFORMASI & SKORING</li>

        <li class="nav-item">
            <a class="nav-link {{ request()->routeIs('point.*') ? '' : 'collapsed' }}" href="{{ route('point.tampil') }}">
                <i class="bi bi-star"></i>
                <span>Data Poin Pelanggaran</span>
            </a>
        </li>

        <li class="nav-item">
            <a class="nav-link {{ request()->routeIs('kategori.*') ? '' : 'collapsed' }}" href="{{ route('kategori.tampil') }}">
                <i class="bi bi-bar-chart-steps"></i>
                <span>Kategori Poin</span>
            </a>
        </li>

        <li class="nav-item">
            <a class="nav-link {{ request()->routeIs('dataprestasi.*') ? '' : 'collapsed' }}" href="{{ route('dataprestasi.tampil') }}">
                <i class="bi bi-trophy"></i>
                <span>Prestasi Siswa</span>
            </a>
        </li>

        <li class="nav-heading">PENGATURAN</li>

        <li class="nav-item">
            <a class="nav-link {{ request()->routeIs('profile.show') ? '' : 'collapsed' }}" href="{{ route('profile.show') }}">
                <i class="bi bi-person-gear"></i>
                <span>Profil Saya</span>
            </a>
        </li>

        <li class="nav-item">
            <a class="nav-link {{ request()->routeIs('settings.index') ? '' : 'collapsed' }}" href="{{ route('settings.index') }}">
                <i class="bi bi-gear"></i>
                <span>Pengaturan Akun</span>
            </a>
        </li>

        <li class="nav-item">
            <a class="nav-link {{ request()->routeIs('help.center') ? '' : 'collapsed' }}" href="{{ route('help.center') }}">
                <i class="bi bi-question-circle"></i>
                <span>Butuh Bantuan?</span>
            </a>
        </li>
    </ul>
</aside><!-- End Sidebar-->
