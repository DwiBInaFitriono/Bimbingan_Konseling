<?php

namespace App\Http\Controllers\Guru_BK;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\PointData;
use App\Models\Student;
use Illuminate\Support\Facades\Auth;

class DataPoint extends Controller
{
    public function dataPoint()
    {
        $violationPointList = PointData::with(['student.class', 'recorder'])->latest()->get();
        $studentList = Student::with('class')->get();
        return view('Guru_BK.Point.points', ['datapoint' => $violationPointList, 'datasiswa' => $studentList]);
    }

    public function createPoint()
    {
        $studentList = Student::with('class')->get();
        return view('Guru_BK.Point.tambah', ['datasiswa' => $studentList]);
    }

    public function storePoint(Request $request)
    {
        $request->validate([
            'student_id'   => 'required|exists:students,id',
            'violation'    => 'required|string|max:255',
            'point_number' => 'required|integer|min:1',
            'violation_date' => 'nullable|date',
        ]);

        $createdPointData = PointData::create([
            'student_id'     => $request->student_id,
            'violation'      => $request->violation,
            'point_number'   => $request->point_number,
            'violation_date' => $request->violation_date ?? now()->format('Y-m-d'),
            'description'    => $request->description,
            'recorded_by'    => Auth::id(),
        ]);

        if ($createdPointData->student) {
            $createdPointData->student->recalculateStatus();
        }

        return redirect()->route('point.tampil')->with('success', 'Poin pelanggaran siswa berhasil dicatat.');
    }

    public function editPoint($pointDataId)
    {
        $parsedPointId = (int) $pointDataId;
        $violationPointData = PointData::findOrFail($parsedPointId);
        $studentList = Student::with('class')->get();
        return view('Guru_BK.Point.edit', [
            'data' => $violationPointData,
            'datasiswa' => $studentList,
        ]);
    }

    public function updatePoint(Request $request, $pointDataId)
    {
        $parsedPointId = (int) $pointDataId;
        $request->validate([
            'student_id'   => 'required|exists:students,id',
            'violation'    => 'required|string|max:255',
            'point_number' => 'required|integer|min:1',
        ]);

        $violationPointData = PointData::findOrFail($parsedPointId);
        $originalStudentId = $violationPointData->student_id;

        $violationPointData->student_id     = $request->student_id;
        $violationPointData->violation      = $request->violation;
        $violationPointData->point_number   = $request->point_number;
        $violationPointData->violation_date = $request->violation_date ?? $violationPointData->violation_date;
        $violationPointData->description    = $request->description;
        $violationPointData->save();

        if ($violationPointData->student) {
            $violationPointData->student->recalculateStatus();
        }
        if ($originalStudentId != $violationPointData->student_id) {
            $previousStudent = Student::find($originalStudentId);
            if ($previousStudent) {
                $previousStudent->recalculateStatus();
            }
        }

        return redirect()->route('point.tampil')->with('success', 'Data poin pelanggaran berhasil diperbarui.');
    }

    public function destroyPoint($pointDataId)
    {
        $parsedPointId = (int) $pointDataId;
        $violationPointData = PointData::findOrFail($parsedPointId);
        $affectedStudent = $violationPointData->student;
        $violationPointData->delete();

        if ($affectedStudent) {
            $affectedStudent->recalculateStatus();
        }

        return redirect()->back()->with('success', 'Data poin berhasil dihapus.');
    }
}
