<?php

namespace App\Http\Controllers\Guru_BK;

use App\Http\Controllers\Controller;

use Illuminate\Http\Request;
use App\Models\PointData;
use App\Models\Student;
use Illuminate\Support\Facades\Auth;

class DataPoint extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function dataPoint()
    {
        $data = PointData::with(['student.class', 'recorder'])->latest()->get();
        $datasiswa = Student::with('class')->get();
        return view('Guru_BK.Point.points', ['datapoint' => $data, 'datasiswa' => $datasiswa]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function createPoint()
    {
        $datasiswa = Student::with('class')->get();
        return view('Guru_BK.Point.tambah', compact('datasiswa'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function storePoint(Request $request)
    {
        $request->validate([
            'student_id'   => 'required|exists:students,id',
            'violation'    => 'required|string|max:255',
            'point_number' => 'required|integer|min:1',
            'violation_date' => 'nullable|date',
        ]);

        $point = PointData::create([
            'student_id'     => $request->student_id,
            'violation'      => $request->violation,
            'point_number'   => $request->point_number,
            'violation_date' => $request->violation_date ?? now()->format('Y-m-d'),
            'description'    => $request->description,
            'recorded_by'    => Auth::id(),
        ]);

        // Recalculate student score & status
        if ($point->student) {
            $point->student->recalculateStatus();
        }

        return redirect()->route('point.tampil')->with('success', 'Poin pelanggaran siswa berhasil dicatat.');
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function editPoint($id)
    {
        $id = (int) $id;
        $data = PointData::findOrFail($id);
        $datasiswa = Student::with('class')->get();
        return view('Guru_BK.Point.edit', compact('data', 'datasiswa'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function updatePoint(Request $request, $id)
    {
        $id = (int) $id;
        $request->validate([
            'student_id'   => 'required|exists:students,id',
            'violation'    => 'required|string|max:255',
            'point_number' => 'required|integer|min:1',
        ]);

        $data = PointData::findOrFail($id);
        $oldStudentId = $data->student_id;

        $data->student_id     = $request->student_id;
        $data->violation      = $request->violation;
        $data->point_number   = $request->point_number;
        $data->violation_date = $request->violation_date ?? $data->violation_date;
        $data->description    = $request->description;
        $data->save();

        if ($data->student) {
            $data->student->recalculateStatus();
        }
        if ($oldStudentId != $data->student_id) {
            $oldStudent = Student::find($oldStudentId);
            if ($oldStudent) {
                $oldStudent->recalculateStatus();
            }
        }

        return redirect()->route('point.tampil')->with('success', 'Data poin pelanggaran berhasil diperbarui.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroyPoint($id)
    {
        $id = (int) $id;
        $data = PointData::findOrFail($id);
        $student = $data->student;
        $data->delete();

        if ($student) {
            $student->recalculateStatus();
        }

        return redirect()->back()->with('success', 'Data poin berhasil dihapus.');
    }
}
