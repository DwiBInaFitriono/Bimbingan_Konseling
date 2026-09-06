<?php

namespace App\Http\Controllers\Guru_BK;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\CaseStudy;
use App\Models\Student;
use App\Models\PointData;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class CaseReport extends Controller
{
    public function indexCaseReport()
    {
        $caseStudyList = CaseStudy::with(['student.class', 'handler'])->latest()->get();
        return view('Guru_BK.CaseStudy.studykasus', ['datastudykasus' => $caseStudyList]);
    }

    public function createCaseReport()
    {
        $studentList = Student::with('class')->get();
        return view('Guru_BK.CaseStudy.tambah', ['datasiswa' => $studentList]);
    }

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

        $evidenceFilePath = null;
        if ($request->hasFile('evidence')) {
            $evidenceFile = $request->file('evidence');
            $uniqueEvidenceFileName = time() . '_' . $evidenceFile->getClientOriginalName();
            $evidenceFile->storeAs('public/evidence', $uniqueEvidenceFileName);
            $evidenceFilePath = 'storage/evidence/' . $uniqueEvidenceFileName;
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
            'evidence_file'     => $evidenceFilePath,
        ]);

        return redirect()->route('studykasus.tampil')->with('success', 'Laporan studi kasus berhasil ditambahkan.');
    }

    public function editCaseReport($caseStudyId)
    {
        $caseStudy = CaseStudy::findOrFail($caseStudyId);
        $studentList = Student::with('class')->get();
        return view('Guru_BK.CaseStudy.edit', [
            'data' => $caseStudy,
            'datasiswa' => $studentList,
        ]);
    }

    public function updateCaseReport(Request $request, $caseStudyId)
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

        $caseStudy = CaseStudy::findOrFail($caseStudyId);
        $evidenceFilePath = $caseStudy->evidence_file;

        if ($request->hasFile('evidence')) {
            if ($caseStudy->evidence_file) {
                $existingFilePath = str_replace('storage/', 'public/', $caseStudy->evidence_file);
                Storage::delete($existingFilePath);
            }
            $evidenceFile = $request->file('evidence');
            $uniqueEvidenceFileName = time() . '_' . $evidenceFile->getClientOriginalName();
            $evidenceFile->storeAs('public/evidence', $uniqueEvidenceFileName);
            $evidenceFilePath = 'storage/evidence/' . $uniqueEvidenceFileName;
        }

        $caseStudy->update([
            'student_id'        => $request->student_id,
            'case_title'        => $request->case_title,
            'case_description'  => $request->case_description,
            'case_type'         => $request->case_type,
            'action_taken'      => $request->action_taken,
            'recommendation'    => $request->recommendation,
            'status'            => $request->status ?? $caseStudy->status,
            'case_date'         => $request->case_date,
            'reporter_teacher'  => $request->reporter_teacher,
            'subject_name'      => $request->subject_name,
            'time_of_occurrence'=> $request->time_of_occurrence,
            'evidence_file'     => $evidenceFilePath,
        ]);

        return redirect()->route('studykasus.tampil')->with('success', 'Laporan studi kasus berhasil diperbarui.');
    }

    public function destroyCaseReport($caseStudyId)
    {
        $caseStudy = CaseStudy::findOrFail($caseStudyId);
        if ($caseStudy->evidence_file) {
            $existingFilePath = str_replace('storage/', 'public/', $caseStudy->evidence_file);
            Storage::delete($existingFilePath);
        }
        $caseStudy->delete();
        return redirect()->back()->with('success', 'Laporan studi kasus berhasil dihapus.');
    }

    public function completeCase(Request $request, $caseStudyId)
    {
        $request->validate([
            'action_taken'   => 'required|string',
            'recommendation' => 'required|string',
        ]);

        $caseStudy = CaseStudy::findOrFail($caseStudyId);
        $caseStudy->update([
            'action_taken'   => $request->action_taken,
            'recommendation' => $request->recommendation,
            'status'         => 'selesai',
        ]);

        return redirect()->route('studykasus.tampil')->with('success', 'Kasus berhasil diselesaikan.');
    }

    public function applyPointSanction(Request $request, $caseStudyId)
    {
        $request->validate([
            'points_sanction' => 'required|integer|min:1',
        ]);

        $caseStudy = CaseStudy::with('student')->findOrFail($caseStudyId);

        $createdPointData = PointData::create([
            'student_id'     => $caseStudy->student_id,
            'violation'      => "Sanksi Kasus: " . $caseStudy->case_title . " (Pelapor: " . $caseStudy->reporter_teacher . ")",
            'point_number'   => $request->points_sanction,
            'violation_date' => $caseStudy->case_date,
            'description'    => "Poin sanksi diproses otomatis dari Buku Kasus." . ($caseStudy->subject_name ? " Pelajaran: " . $caseStudy->subject_name : "") . ($caseStudy->time_of_occurrence ? " (Waktu: " . $caseStudy->time_of_occurrence . ")" : ""),
            'recorded_by'    => Auth::id(),
        ]);

        if ($createdPointData->student) {
            $createdPointData->student->recalculateStatus();
        }

        $caseStudy->update([
            'points_sanction' => $request->points_sanction,
            'points_applied'  => true,
        ]);

        return redirect()->route('studykasus.tampil')->with('success', 'Sanksi poin berhasil diterapkan ke siswa.');
    }

    public function printCasePdf($caseStudyId)
    {
        $caseStudy = CaseStudy::with(['student.class', 'student.parent', 'handler'])->findOrFail($caseStudyId);
        return view('Guru_BK.CaseStudy.cetak_pdf', ['case' => $caseStudy]);
    }
}
