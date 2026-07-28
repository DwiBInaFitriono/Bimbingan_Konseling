@extends('dashboard')
@section('kontent')
    <div class="container mt-4">
        <div class="pagetitle d-flex align-items-center">
            <!-- Tombol Kembali -->
            <a href="{{ url()->previous() }}" class="btn btn-outline-primary me-3">
                <i class="bi bi-arrow-left"></i> <!-- Icon panah kiri -->
            </a>
            <h1>Add Point Data</h1>
        </div><!-- End Page Title -->

        <form action="simpanpoint" method="post">
            @csrf

            <div class="mb-3">
                <label for="violation" class="form-label">Violation:</label>
                <input type="text" id="violation" name="violation" class="form-control" required>
            </div>
            <div class="mb-3">
                <label for="point_number" class="form-label">Point:</label>
                <input type="text" id="point_number" name="point_number" class="form-control" required>
            </div>


            <button type="submit" class="btn btn-primary">Submit</button>
        </form>
    </div>
@endsection
