@extends('dashboard')
@section('kontent')
    <div class="container mt-4">
        <div class="pagetitle">
            <h1>Tambah Catatan Laporan Kasus</h1>
            <nav>
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Beranda</a></li>
                    <li class="breadcrumb-item"><a href="{{ route('studykasus.tampil') }}">Buku Kasus</a></li>
                    <li class="breadcrumb-item active">Tambah Laporan</li>
                </ol>
            </nav>
        </div><!-- End Page Title -->

        <section class="section">
            <div class="row">
                <div class="col-lg-12">
                    <div class="card border-0 shadow-sm rounded-3">
                        <div class="card-body p-4">
                            <div class="d-flex align-items-center justify-content-between pb-3 mb-4 border-bottom">
                                <h5 class="card-title fw-bold text-dark p-0 m-0">Formulir Pencatatan Laporan Kasus Siswa</h5>
                                <a href="{{ route('studykasus.tampil') }}" class="btn btn-outline-secondary btn-sm">
                                    <i class="bi bi-arrow-left me-1"></i>Kembali
                                </a>
                            </div>

                            <form action="{{ url('simpanstudykasus') }}" method="post" enctype="multipart/form-data">
                                @csrf
                                
                                <div class="row g-3 mb-3">
                                    <div class="col-md-6">
                                        <label for="student_id" class="form-label fw-semibold">Pilih Siswa <span class="text-danger">*</span></label>
                                        <select class="form-select select2" name="student_id" id="student_id" required>
                                            <option disabled selected value="">-- Pilih Siswa --</option>
                                            @foreach ($datasiswa as $item)
                                                <option value="{{ $item->id }}">{{ $item->full_name }} ({{ $item->class?->school_class_name ?? '-' }})</option>
                                            @endforeach
                                        </select>
                                    </div>
                                    
                                    <div class="col-md-6">
                                        <label for="case_date" class="form-label fw-semibold">Tanggal Kasus <span class="text-danger">*</span></label>
                                        <input type="date" class="form-control" name="case_date" id="case_date" value="{{ date('Y-m-d') }}" required>
                                    </div>
                                </div>

                                <div class="row g-3 mb-3">
                                    <div class="col-md-6">
                                        <label for="reporter_teacher" class="form-label fw-semibold">Guru Pelapor (Pengajar Kelas) <span class="text-danger">*</span></label>
                                        <input type="text" class="form-control" name="reporter_teacher" id="reporter_teacher" placeholder="Nama Guru yang mengajar/melaporkan" required>
                                    </div>

                                    <div class="col-md-6">
                                        <div class="row g-3">
                                            <div class="col-md-6">
                                                <label for="subject_name" class="form-label fw-semibold">Mata Pelajaran</label>
                                                <input type="text" class="form-control" name="subject_name" id="subject_name" placeholder="Misal: Fisika, Matematika">
                                            </div>

                                            <div class="col-md-6">
                                                <label for="time_of_occurrence" class="form-label fw-semibold">Waktu / Jam Pelajaran</label>
                                                <input type="text" class="form-control" name="time_of_occurrence" id="time_of_occurrence" placeholder="Misal: 08:30 atau Jam Ke-3">
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div class="row g-3 mb-3">
                                    <div class="col-md-6">
                                        <label for="case_title" class="form-label fw-semibold">Judul / Singkatan Kasus <span class="text-danger">*</span></label>
                                        <input type="text" class="form-control" name="case_title" id="case_title" placeholder="Contoh: Terlambat Masuk Kelas" required>
                                    </div>

                                    <div class="col-md-6">
                                        <label for="case_type" class="form-label fw-semibold">Jenis Kasus <span class="text-danger">*</span></label>
                                        <select class="form-select" name="case_type" id="case_type" required>
                                            <option value="pelanggaran">Pelanggaran Tata Tertib</option>
                                            <option value="pribadi">Masalah Pribadi</option>
                                            <option value="sosial">Masalah Sosial</option>
                                            <option value="belajar">Masalah Belajar</option>
                                            <option value="karir">Masalah Karir</option>
                                        </select>
                                    </div>
                                </div>

                                <div class="mb-3">
                                    <label for="case_description" class="form-label fw-semibold">Deskripsi Kasus / Kronologi <span class="text-danger">*</span></label>
                                    <textarea class="form-control" name="case_description" id="case_description" rows="3" placeholder="Jelaskan detail kasus yang terjadi..." required></textarea>
                                </div>
                                
                                <div class="mb-3">
                                    <label for="action_taken" class="form-label fw-semibold">Tindakan Penanganan</label>
                                    <textarea class="form-control" name="action_taken" id="action_taken" rows="2" placeholder="Tindakan apa yang sudah dilakukan oleh Guru BK?"></textarea>
                                </div>

                                <div class="mb-3">
                                    <label for="recommendation" class="form-label fw-semibold">Rekomendasi</label>
                                    <textarea class="form-control" name="recommendation" id="recommendation" rows="2" placeholder="Rekomendasi tindak lanjut..."></textarea>
                                </div>
                                
                                <div class="row g-3 mb-4">
                                    <div class="col-md-8">
                                        <label for="evidence" class="form-label fw-semibold">Bukti Media (Foto / Video)</label>
                                        <input type="file" class="form-control" name="evidence" id="evidence" accept="image/*,video/*">
                                        <small class="text-muted">Mendukung format gambar (JPG, JPEG, PNG, WebP) dan video (MP4, MOV, AVI) maks 20MB.</small>
                                    </div>

                                    <div class="col-md-4">
                                        <label for="status" class="form-label fw-semibold">Status Penanganan <span class="text-danger">*</span></label>
                                        <select class="form-select" name="status" id="status" required>
                                            <option value="proses">Sedang Diproses</option>
                                            <option value="selesai">Selesai / Tuntas</option>
                                            <option value="tindak_lanjut">Perlu Tindak Lanjut</option>
                                        </select>
                                    </div>
                                </div>

                                <div class="text-end pt-3 border-top">
                                    <button type="submit" class="btn btn-primary fw-semibold px-4"><i class="bi bi-save me-1"></i> Simpan Catatan</button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </section>
    </div>
@endsection
