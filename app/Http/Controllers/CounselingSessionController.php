<?php

namespace App\Http\Controllers;

use App\Models\CounselingSession;
use App\Models\Student;
use App\Models\ClassData;
use App\Models\CaseStudy;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;

class CounselingSessionController extends Controller
{
    /**
     * Tampilan daftar pengajuan konseling untuk Guru BK
     */
    public function index(Request $request)
    {
        $query = CounselingSession::with(['student.class', 'guruBk'])->latest();

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        $sessions = $query->get();
        $pendingCount = CounselingSession::where('status', 'menunggu')->count();
        $approvedCount = CounselingSession::where('status', 'disetujui')->count();
        $completedCount = CounselingSession::where('status', 'selesai')->count();

        return view('counseling.index', compact('sessions', 'pendingCount', 'approvedCount', 'completedCount'));
    }

    /**
     * Tampilan Halaman Rekapitulasi Catatan Konseling Bulanan
     */
    public function report(Request $request)
    {
        $month = $request->input('month', date('n'));
        $year = $request->input('year', date('Y'));
        $studentId = $request->input('student_id');
        $status = $request->input('status', 'all');

        $query = CounselingSession::with(['student.class', 'guruBk'])
            ->whereMonth('requested_date', $month)
            ->whereYear('requested_date', $year);

        if ($studentId) {
            $query->where('student_id', $studentId);
        }

        if ($status != 'all') {
            $query->where('status', $status);
        }

        $reports = $query->orderBy('requested_date', 'asc')->get();
        $students = Student::with('class')->orderBy('full_name')->get();
        $selectedStudent = $studentId ? Student::with('class')->find($studentId) : null;

        return view('counseling.report', compact('reports', 'students', 'month', 'year', 'studentId', 'status', 'selectedStudent'));
    }

    /**
     * Tampilan Format Cetak / Export PDF Resmi Laporan Konseling
     */
    public function exportPdf(Request $request)
    {
        $month = $request->input('month', date('n'));
        $year = $request->input('year', date('Y'));
        $studentId = $request->input('student_id');
        $status = $request->input('status', 'all');

        $query = CounselingSession::with(['student.class', 'guruBk'])
            ->whereMonth('requested_date', $month)
            ->whereYear('requested_date', $year);

        if ($studentId) {
            $query->where('student_id', $studentId);
        }

        if ($status != 'all') {
            $query->where('status', $status);
        }

        $reports = $query->orderBy('requested_date', 'asc')->get();
        $selectedStudent = $studentId ? Student::with(['class', 'parent'])->find($studentId) : null;
        $guruBk = Auth::user();

        $monthNames = [
            1 => 'Januari', 2 => 'Februari', 3 => 'Maret', 4 => 'April',
            5 => 'Mei', 6 => 'Juni', 7 => 'Juli', 8 => 'Agustus',
            9 => 'September', 10 => 'Oktober', 11 => 'November', 12 => 'Desember'
        ];

        $monthName = $monthNames[(int)$month] ?? date('F');

        return view('counseling.pdf_report', compact('reports', 'month', 'year', 'monthName', 'selectedStudent', 'guruBk', 'status'));
    }

    /**
     * Tampilan pengajuan konseling untuk Siswa
     */
    public function studentIndex()
    {
        $user = Auth::user();
        $student = $user->student;

        if (!$student) {
            $student = Student::where('full_name', $user->name)->first();
        }

        $mySessions = $student
            ? CounselingSession::with('guruBk')->where('student_id', $student->id)->latest()->get()
            : collect();

        return view('counseling.siswa_index', compact('student', 'mySessions'));
    }

    /**
     * Simpan pengajuan konseling baru (Siswa / Guru BK)
     */
     public function store(Request $request)
    {
        $request->validate([
            'requested_date' => 'required|date',
            'requested_time' => 'required',
            'topic'          => 'required|string|max:255',
            'description'    => 'nullable|string',
            'type'           => 'required|in:individu,kelompok',
            'student_id'     => 'nullable|exists:students,id',
            'additional_student_ids' => 'nullable|array',
            'additional_student_ids.*' => 'exists:students,id',
            'case_study_id'  => 'nullable|exists:case_studies,id',
        ]);

        $user = Auth::user();

        if ($user->isSiswa()) {
            $student = $user->student;
            if (!$student) {
                return back()->with('error', 'Data profil siswa Anda tidak ditemukan.');
            }
            $studentId = $student->id;
        } else {
            $studentId = $request->student_id;
            if (!$studentId) {
                return back()->with('error', 'Silakan pilih siswa terlebih dahulu.');
            }
        }

        $session = CounselingSession::create([
            'student_id'     => $studentId,
            'additional_student_ids' => $request->type === 'kelompok' ? $request->additional_student_ids : null,
            'case_study_id'  => $request->case_study_id,
            'guru_bk_id'     => $user->isGuruBk() ? $user->id : null,
            'requested_date' => $request->requested_date,
            'requested_time' => $request->requested_time,
            'topic'          => $request->topic,
            'description'    => $request->description,
            'type'           => $request->type,
            'status'         => $user->isGuruBk() ? 'disetujui' : 'menunggu',
            'approved_at'    => $user->isGuruBk() ? now() : null,
        ]);

        // Automate CaseStudy status to 'proses'
        if ($request->case_study_id) {
            $caseStudy = CaseStudy::find($request->case_study_id);
            if ($caseStudy) {
                $caseStudy->update([
                    'status' => 'proses',
                    'handled_by' => $user->isGuruBk() ? $user->id : null,
                ]);
            }
        }

        if ($user->isGuruBk()) {
            return redirect()->route('counseling.index')->with('success', 'Pengajuan jadwal konseling berhasil disimpan.');
        }
        return redirect()->route('counseling.studentIndex')->with('success', 'Pengajuan jadwal konseling berhasil disimpan.');
    }

    /**
     * Setujui pengajuan konseling (Guru BK)
     */
    public function approve(Request $request, $id)
    {
        $session = CounselingSession::findOrFail($id);
        $session->update([
            'status'      => 'disetujui',
            'guru_bk_id'  => Auth::id(),
            'notes'       => $request->notes ?? $session->notes,
            'approved_at' => now(),
        ]);

        // Automate CaseStudy status to 'proses'
        if ($session->case_study_id) {
            $caseStudy = CaseStudy::find($session->case_study_id);
            if ($caseStudy) {
                $caseStudy->update([
                    'status' => 'proses',
                    'handled_by' => Auth::id(),
                ]);
            }
        }

        return redirect()->route('counseling.index')->with('success', 'Pengajuan konseling berhasil disetujui.');
    }

    /**
     * Tolak pengajuan konseling (Guru BK)
     */
    public function reject(Request $request, $id)
    {
        $session = CounselingSession::findOrFail($id);
        $session->update([
            'status'     => 'ditolak',
            'guru_bk_id' => Auth::id(),
            'notes'      => $request->notes ?? 'Jadwal tidak tersedia, silakan ajukan ulang.',
        ]);

        return redirect()->route('counseling.index')->with('success', 'Pengajuan konseling telah ditolak.');
    }

    /**
     * Selesaikan sesi konseling & catat hasil (Guru BK)
     */
    public function complete(Request $request, $id)
    {
        $session = CounselingSession::findOrFail($id);
        $session->update([
            'status'       => 'selesai',
            'notes'        => $request->notes,
            'completed_at' => now(),
        ]);

        // Automate CaseStudy status to 'selesai'
        if ($session->case_study_id) {
            $caseStudy = CaseStudy::find($session->case_study_id);
            if ($caseStudy) {
                $caseStudy->update([
                    'status' => 'selesai',
                    'action_taken' => $request->notes,
                    'recommendation' => $caseStudy->recommendation ?: 'Konseling telah selesai dilakukan.',
                ]);
            }
        }

        return redirect()->route('counseling.index')->with('success', 'Sesi konseling telah selesai dicatat.');
    }

    /**
     * Batalkan sesi konseling
     */
    public function cancel($id)
    {
        $session = CounselingSession::findOrFail($id);
        $session->update([
            'status' => 'dibatalkan',
        ]);

        if (Auth::user()->isGuruBk()) {
            return redirect()->route('counseling.index')->with('success', 'Pengajuan konseling berhasil dibatalkan.');
        }
        return redirect()->route('counseling.studentIndex')->with('success', 'Pengajuan konseling berhasil dibatalkan.');
    }

}
