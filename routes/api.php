<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

use App\Models\Student;

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

Route::get('/v1/students', function () {
    $students = Student::with('class')->get()->map(function ($student) {
        return [
            'id' => $student->id,
            'name' => $student->full_name,
            'nis' => $student->nis,
            'class_name' => $student->class ? $student->class->school_class_name : '',
            'major' => $student->class ? $student->class->school_class_major : '',
        ];
    });

    return response()->json([
        'success' => true,
        'data' => $students
    ])->header('Access-Control-Allow-Origin', '*')
      ->header('Access-Control-Allow-Methods', 'GET, OPTIONS');
});
