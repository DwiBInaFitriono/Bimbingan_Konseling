@extends('dashboard')
@section('kontent')
    <div class="pagetitle d-flex align-items-center">
        <a href="{{ url()->previous() }}" class="btn btn-outline-secondary me-3">
            <i class="bi bi-arrow-left"></i>
        </a>
        <div>
            <h1>Edit Data Orang Tua / Wali</h1>
            <nav>
                <ol class="breadcrumb mb-0">
                    <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Beranda</a></li>
                    <li class="breadcrumb-item"><a href="{{ route('ortu.tampil') }}">Data Orang Tua</a></li>
                    <li class="breadcrumb-item active">Edit</li>
                </ol>
            </nav>
        </div>
    </div>

    <form action="{{ url('/updateparent/' . $dataparent->id) }}" method="post">
        @csrf
        <div class="card border-0 shadow-sm rounded-3">
            <div class="card-body p-4">
                <div class="row g-3">
                    <div class="col-md-6">
                        <label for="parent_full_name" class="form-label fw-semibold">Nama Lengkap Orang Tua / Wali <span class="text-danger">*</span></label>
                        <input type="text" id="parent_full_name" name="parent_full_name" class="form-control"
                            value="{{ $dataparent->parent_full_name }}" required>
                    </div>

                    <div class="col-md-6">
                        <label for="phone_number" class="form-label fw-semibold">Nomor Telepon <span class="text-danger">*</span></label>
                        <div class="input-group">
                            <span class="input-group-text"><i class="bi bi-telephone"></i></span>
                            <input type="text" id="phone_number" name="phone_number" class="form-control"
                                value="{{ $dataparent->phone_number }}" required>
                        </div>
                    </div>

                    <div class="col-md-6">
                        <label for="job" class="form-label fw-semibold">Pekerjaan <span class="text-danger">*</span></label>
                        <input type="text" id="job" name="job" class="form-control"
                            value="{{ $dataparent->job }}" required>
                    </div>

                    <div class="col-md-6">
                        <label for="address" class="form-label fw-semibold">Alamat Lengkap <span class="text-danger">*</span></label>
                        <input type="text" id="address" name="address" class="form-control"
                            value="{{ $dataparent->address }}" required>
                    </div>
                </div>
            </div>
            <div class="card-footer bg-white text-end py-3 border-top">
                <a href="{{ url()->previous() }}" class="btn btn-secondary me-2"><i class="bi bi-x-circle me-1"></i>Batal</a>
                <button type="submit" class="btn btn-primary fw-semibold px-4"><i class="bi bi-save me-1"></i>Simpan Perubahan</button>
            </div>
        </div>
    </form>
@endsection
