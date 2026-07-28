@extends('dashboard')
@section('kontent')
    <div class="pagetitle d-flex align-items-center">
        <a href="{{ url()->previous() }}" class="btn btn-outline-secondary me-3">
            <i class="bi bi-arrow-left"></i>
        </a>
        <div>
            <h1>Tambah Kategori Poin</h1>
            <nav>
                <ol class="breadcrumb mb-0">
                    <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Beranda</a></li>
                    <li class="breadcrumb-item"><a href="{{ route('kategori.tampil') }}">Kategori Poin</a></li>
                    <li class="breadcrumb-item active">Tambah</li>
                </ol>
            </nav>
        </div>
    </div>

    <form action="simpankategori" method="post">
        @csrf
        <div class="card border-0 shadow-sm rounded-3">
            <div class="card-body p-4">
                <div class="row g-3">
                    <div class="col-12">
                        <label for="category_of_violation" class="form-label fw-semibold">Jenis / Kategori Pelanggaran <span class="text-danger">*</span></label>
                        <input type="text" id="category_of_violation" name="category_of_violation" class="form-control"
                            placeholder="Contoh: Pelanggaran Ringan, Terlambat, dll." required>
                    </div>

                    <div class="col-md-6">
                        <label for="category_score_min" class="form-label fw-semibold">Poin Minimum <span class="text-danger">*</span></label>
                        <div class="input-group">
                            <span class="input-group-text bg-warning bg-opacity-10 text-warning"><i class="bi bi-dash-circle"></i></span>
                            <input type="number" id="category_score_min" name="category_score_min" class="form-control"
                                placeholder="Contoh: 10" min="0" required>
                        </div>
                        <div class="form-text">Batas poin terendah untuk kategori ini.</div>
                    </div>

                    <div class="col-md-6">
                        <label for="category_score_max" class="form-label fw-semibold">Poin Maksimum <span class="text-danger">*</span></label>
                        <div class="input-group">
                            <span class="input-group-text bg-danger bg-opacity-10 text-danger"><i class="bi bi-plus-circle"></i></span>
                            <input type="number" id="category_score_max" name="category_score_max" class="form-control"
                                placeholder="Contoh: 30" min="0" required>
                        </div>
                        <div class="form-text">Batas poin tertinggi untuk kategori ini.</div>
                    </div>

                    <div class="col-12">
                        <label for="follow_up" class="form-label fw-semibold">Tindak Lanjut <span class="text-danger">*</span></label>
                        <input type="text" id="follow_up" name="follow_up" class="form-control"
                            placeholder="Contoh: Panggilan orang tua, Skorsing, dll." required>
                        <div class="form-text">Tindakan yang diambil jika siswa mencapai kategori poin ini.</div>
                    </div>
                </div>
            </div>
            <div class="card-footer bg-white text-end py-3 border-top">
                <a href="{{ url()->previous() }}" class="btn btn-secondary me-2"><i class="bi bi-x-circle me-1"></i>Batal</a>
                <button type="submit" class="btn btn-primary fw-semibold px-4"><i class="bi bi-save me-1"></i>Simpan Kategori</button>
            </div>
        </div>
    </form>
@endsection
