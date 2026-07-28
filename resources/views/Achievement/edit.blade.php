@extends('dashboard')
@section('kontent')
    <div class="pagetitle d-flex align-items-center">
        <a href="{{ url()->previous() }}" class="btn btn-outline-secondary me-3">
            <i class="bi bi-arrow-left"></i>
        </a>
        <div>
            <h1>Edit Data Prestasi Siswa</h1>
            <nav>
                <ol class="breadcrumb mb-0">
                    <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Beranda</a></li>
                    <li class="breadcrumb-item"><a href="{{ route('dataprestasi.tampil') }}">Prestasi Siswa</a></li>
                    <li class="breadcrumb-item active">Edit</li>
                </ol>
            </nav>
        </div>
    </div>

    <form action="{{ url('/updateprestasi/' . $dataprestasi->id) }}" method="post">
        @csrf

        <div class="card border-0 shadow-sm rounded-3">
            <div class="card-body p-4">
                <div class="row g-3">
                    <div class="col-md-6">
                        <label for="student_id" class="form-label fw-semibold">Pilih Siswa <span class="text-danger">*</span></label>
                        <select class="form-select" name="student_id" id="student_id" required>
                            <option value="" disabled>-- Pilih Siswa --</option>
                            @foreach ($datasiswa as $item)
                                <option value="{{ $item->id }}" {{ $dataprestasi->student_id == $item->id ? 'selected' : '' }}>
                                    {{ $item->full_name }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <div class="col-md-6">
                        <label for="achievement_date" class="form-label fw-semibold">Tanggal Prestasi <span class="text-danger">*</span></label>
                        <input type="date" id="achievement_date" name="achievement_date" class="form-control"
                            value="{{ $dataprestasi->achievement_date }}" required>
                    </div>

                    <div class="col-md-6">
                        <label for="achievement_name" class="form-label fw-semibold">Nama Prestasi <span class="text-danger">*</span></label>
                        <input type="text" id="achievement_name" name="achievement_name" class="form-control"
                            value="{{ $dataprestasi->achievement_name }}" required>
                    </div>

                    <div class="col-md-6">
                        <label for="achievement_level" class="form-label fw-semibold">Tingkat Prestasi <span class="text-danger">*</span></label>
                        <select id="achievement_level" name="achievement_level" class="form-select" required>
                            @foreach (['Sekolah', 'Kabupaten', 'Provinsi', 'Nasional', 'Internasional'] as $level)
                                <option value="{{ $level }}" {{ $dataprestasi->achievement_level == $level ? 'selected' : '' }}>
                                    {{ $level }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <div class="col-md-6">
                        <label for="achievement_category" class="form-label fw-semibold">Kategori Prestasi <span class="text-danger">*</span></label>
                        <select id="achievement_category" name="achievement_category" class="form-select" required>
                            @foreach (['Akademik', 'Non-Akademik', 'Olahraga', 'Seni & Budaya', 'Teknologi', 'Lainnya'] as $cat)
                                <option value="{{ $cat }}" {{ $dataprestasi->achievement_category == $cat ? 'selected' : '' }}>
                                    {{ $cat }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <div class="col-md-6">
                        <label for="achievement_status" class="form-label fw-semibold">Status Verifikasi <span class="text-danger">*</span></label>
                        <select id="achievement_status" name="achievement_status" class="form-select" required>
                            <option value="Menunggu Verifikasi" {{ $dataprestasi->achievement_status == 'Menunggu Verifikasi' ? 'selected' : '' }}>Menunggu Verifikasi</option>
                            <option value="Terverifikasi" {{ $dataprestasi->achievement_status == 'Terverifikasi' ? 'selected' : '' }}>Terverifikasi</option>
                        </select>
                    </div>
                </div>
            </div>
            <div class="card-footer bg-white text-end py-3 border-top">
                <a href="{{ url()->previous() }}" class="btn btn-secondary me-2"><i class="bi bi-x-circle me-1"></i>Batal</a>
                <button type="submit" class="btn btn-success fw-semibold px-4"><i class="bi bi-save me-1"></i>Simpan Perubahan</button>
            </div>
        </div>
    </form>
@endsection
