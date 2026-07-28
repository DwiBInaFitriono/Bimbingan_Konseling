@extends('dashboard')
@section('kontent')
    <main id="main" class="main">
        <div class="container mt-4">
            <div class="pagetitle d-flex align-items-center">
                <!-- Tombol Kembali -->
                <a href="{{ url()->previous() }}" class="btn btn-outline-primary me-3">
                    <i class="bi bi-arrow-left"></i> <!-- Icon panah kiri -->
                </a>
                <h1>Edit Point Data</h1>
            </div><!-- End Page Title -->

            <form action="{{ url('/updatepoint/' . $data->id) }}" method="post">

                @csrf


                <div class="mb-3">
                    Violation:
                    <input type="text" id="violation" name="violation" class="form-control"
                        value="{{ $data->violation }}" required>
                </div>
                <div class="mb-3">
                    Point:
                    <input type="number" id="point_number" name="point_number" class="form-control"
                        value="{{ $data->point_number }}" required>
                </div>


                <button type="submit" class="btn btn-primary">Save</button>
            </form>
        </div>

    </main>
