<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\CaseStudy;
use App\Models\Student;
use Illuminate\Support\Facades\Auth;

class CaseReport extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function indexCaseReport()
    {
        $data = CaseStudy::with(['student.class', 'handler'])->latest()->get();
        return view('CaseStudy.studykasus', ['datastudykasus' => $data]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function createCaseReport()
    {
        $datasiswa = Student::with('class')->get();
        return view('CaseStudy.tambah', compact('datasiswa'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function storeCaseReport(Request $request)
    {
        $request->validate([
            'student_id'        => 'required|exists:students,id',
            'case_title'        => 'required|string|max:255',
            'case_description'  => 'required|string',
            'case_type'         => 'required|in:pelanggaran,pribadi,sosial,belajar,karir',
            'case_date'         => 'required|date',
            'reporter_teacher'  => 'required|string|max:255',
            'subject_name'      => 'nullable|string|max:255',
            'time_of_occurrence'=> 'nullable|string|max:255',
            'evidence'          => 'nullable|file|mimes:jpg,jpeg,png,webp,mp4,mov,avi|max:20480',
        ]);

        $evidencePath = null;
        if ($request->hasFile('evidence')) {
            $file = $request->file('evidence');
            $filename = time() . '_' . $file->getClientOriginalName();
            $file->storeAs('public/evidence', $filename);
            $evidencePath = 'storage/evidence/' . $filename;
        }

        CaseStudy::create([
            'student_id'        => $request->student_id,
            'case_title'        => $request->case_title,
            'case_description'  => $request->case_description,
            'case_type'         => $request->case_type,
            'action_taken'      => $request->action_taken,
            'recommendation'    => $request->recommendation,
            'status'            => $request->status ?? 'proses',
            'handled_by'        => Auth::id(),
            'case_date'         => $request->case_date,
            'reporter_teacher'  => $request->reporter_teacher,
            'subject_name'      => $request->subject_name,
            'time_of_occurrence'=> $request->time_of_occurrence,
            'evidence_file'     => $evidencePath,
        ]);

        return redirect()->route('studykasus.tampil')->with('success', 'Laporan studi kasus berhasil ditambahkan.');
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function editCaseReport($id)
    {
        $data = CaseStudy::findOrFail($id);
        $datasiswa = Student::with('class')->get();
        return view('CaseStudy.edit', compact('data', 'datasiswa'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function updateCaseReport(Request $request, $id)
    {
        $request->validate([
            'student_id'        => 'required|exists:students,id',
            'case_title'        => 'required|string|max:255',
            'case_description'  => 'required|string',
            'case_type'         => 'required|in:pelanggaran,pribadi,sosial,belajar,karir',
            'case_date'         => 'required|date',
            'reporter_teacher'  => 'required|string|max:255',
            'subject_name'      => 'nullable|string|max:255',
            'time_of_occurrence'=> 'nullable|string|max:255',
            'evidence'          => 'nullable|file|mimes:jpg,jpeg,png,webp,mp4,mov,avi|max:20480',
        ]);

        $data = CaseStudy::findOrFail($id);
        $evidencePath = $data->evidence_file;

        if ($request->hasFile('evidence')) {
            // Delete old file if exists
            if ($data->evidence_file) {
                $oldPath = str_replace('storage/', 'public/', $data->evidence_file);
                \Illuminate\Support\Facades\Storage::delete($oldPath);
            }
            $file = $request->file('evidence');
            $filename = time() . '_' . $file->getClientOriginalName();
            $file->storeAs('public/evidence', $filename);
            $evidencePath = 'storage/evidence/' . $filename;
        }

        $data->update([
            'student_id'        => $request->student_id,
            'case_title'        => $request->case_title,
            'case_description'  => $request->case_description,
            'case_type'         => $request->case_type,
            'action_taken'      => $request->action_taken,
            'recommendation'    => $request->recommendation,
            'status'            => $request->status ?? $data->status,
            'case_date'         => $request->case_date,
            'reporter_teacher'  => $request->reporter_teacher,
            'subject_name'      => $request->subject_name,
            'time_of_occurrence'=> $request->time_of_occurrence,
            'evidence_file'     => $evidencePath,
        ]);

        return redirect()->route('studykasus.tampil')->with('success', 'Laporan studi kasus berhasil diperbarui.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroyCaseReport($id)
    {
        $data = CaseStudy::findOrFail($id);
        if ($data->evidence_file) {
            $oldPath = str_replace('storage/', 'public/', $data->evidence_file);
            \Illuminate\Support\Facades\Storage::delete($oldPath);
        }
        $data->delete();
        return redirect()->back()->with('success', 'Laporan studi kasus berhasil dihapus.');
    }

    /**
     * Complete a case study with final actions and recommendations.
     */
    public function completeCase(Request $request, $id)
    {
        $request->validate([
            'action_taken'   => 'required|string',
            'recommendation' => 'required|string',
        ]);

        $data = CaseStudy::findOrFail($id);
        $data->update([
            'action_taken'   => $request->action_taken,
            'recommendation' => $request->recommendation,
            'status'         => 'selesai',
        ]);

        return redirect()->route('studykasus.tampil')->with('success', 'Kasus berhasil diselesaikan.');
    }

    /**
     * Apply violation point sanction to the student.
     */
    public function applyPointSanction(Request $request, $id)
    {
        $request->validate([
            'points_sanction' => 'required|integer|min:1',
        ]);

        $case = CaseStudy::with('student')->findOrFail($id);

        // Create the PointData entry
        $point = \App\Models\PointData::create([
            'student_id'     => $case->student_id,
            'violation'      => "Sanksi Kasus: " . $case->case_title . " (Pelapor: " . $case->reporter_teacher . ")",
            'point_number'   => $request->points_sanction,
            'violation_date' => $case->case_date,
            'description'    => "Poin sanksi diproses otomatis dari Buku Kasus." . ($case->subject_name ? " Pelajaran: " . $case->subject_name : "") . ($case->time_of_occurrence ? " (Waktu: " . $case->time_of_occurrence . ")" : ""),
            'recorded_by'    => Auth::id(),
        ]);

        // Recalculate student score & status
        if ($point->student) {
            $point->student->recalculateStatus();
        }

        // Update the case study with points info
        $case->update([
            'points_sanction' => $request->points_sanction,
            'points_applied'  => true,
        ]);

        return redirect()->route('studykasus.tampil')->with('success', 'Sanksi poin berhasil diterapkan ke siswa.');
    }

    /**
     * Print a single case study report (PDF print layout).
     */
    public function printCasePdf($id)
    {
        $case = CaseStudy::with(['student.class', 'student.parent', 'handler'])->findOrFail($id);
        return view('CaseStudy.cetak_pdf', compact('case'));
    }
}
