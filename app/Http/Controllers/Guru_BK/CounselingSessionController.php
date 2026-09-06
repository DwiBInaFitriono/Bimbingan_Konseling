<?php

namespace App\Http\Controllers\Guru_BK;

use App\Http\Controllers\Controller;
use App\Models\CounselingSession;
use App\Models\Student;
use App\Models\ClassData;
use App\Models\CaseStudy;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;
use Illuminate\Support\Str;

class CounselingSessionController extends Controller
{
    public function index(Request $request)
    {
        $counselingSessionQuery = CounselingSession::with(['student.class', 'guruBk'])->latest();

        if ($request->filled('status')) {
            $counselingSessionQuery->where('status', $request->status);
        }

        $sessions = $counselingSessionQuery->get();
        $pendingCount = CounselingSession::where('status', 'menunggu')->count();
        $approvedCount = CounselingSession::where('status', 'disetujui')->count();
        $completedCount = CounselingSession::where('status', 'selesai')->count();
        $currentDate = date('Y-m-d');

        $currentQueue = CounselingSession::with(['student.class', 'guruBk'])
            ->whereDate('requested_date', $currentDate)
            ->where('status_antrian', 'sekarang')
            ->orderBy('id', 'desc')
            ->first();

        $nextQueue = CounselingSession::with(['student.class', 'guruBk'])
            ->whereDate('requested_date', $currentDate)
            ->where('status', 'disetujui')
            ->where('status_antrian', 'menunggu')
            ->orderBy('no_antrian', 'asc')
            ->first();

        if (!$currentQueue && $nextQueue) {
            $nextQueue->update(['status_antrian' => 'sekarang']);
            $currentQueue = $nextQueue;
            
            $nextQueue = CounselingSession::with(['student.class', 'guruBk'])
                ->whereDate('requested_date', $currentDate)
                ->where('status', 'disetujui')
                ->where('status_antrian', 'menunggu')
                ->orderBy('no_antrian', 'asc')
                ->first();
        }

        $nextQueueNumber = null; 
        if ($nextQueue) {
            $nextQueueNumber = $nextQueue->no_antrian;
        }

        if ($request->wantsJson()) {
            return response()->json([
                'currentQueue' => $currentQueue ? [
                    'no_antrian' => $currentQueue->no_antrian,
                    'student_name' => $currentQueue->student?->full_name ?? 'Siswa',
                    'class_name' => $currentQueue->student?->class?->school_class_name ?? null,
                    'topic' => Str::limit($currentQueue->topic, 30),
                    'waktu_perkiraan' => substr((string) ($currentQueue->waktu_perkiraan ?? $currentQueue->requested_time), 0, 5),
                ] : null,
                'nextQueue' => $nextQueue ? [
                    'no_antrian' => $nextQueueNumber,
                    'student_name' => $nextQueue->student?->full_name ?? 'Siswa',
                    'class_name' => $nextQueue->student?->class?->school_class_name ?? null,
                    'topic' => Str::limit($nextQueue->topic, 30),
                    'waktu_perkiraan' => substr((string) ($nextQueue->waktu_perkiraan ?? $nextQueue->requested_time), 0, 5),
                ] : null,
                'pendingCount' => $pendingCount,
                'approvedCount' => $approvedCount,
                'completedCount' => $completedCount,
            ]);
        }

        return view('Guru_BK.counseling.index', compact(
            'sessions', 'pendingCount', 'approvedCount', 'completedCount',
            'nextQueue', 'nextQueueNumber', 'currentQueue'
        ));
    }

    public function report(Request $request)
    {
        $month = (int) $request->input('month', date('n'));
        $year = (int) $request->input('year', date('Y'));
        $studentId = $request->input('student_id') ? (int) $request->input('student_id') : null;
        $status = $request->input('status', 'all');

        $counselingReportQuery = CounselingSession::with(['student.class', 'guruBk'])
            ->whereMonth('requested_date', $month)
            ->whereYear('requested_date', $year);

        if ($studentId) {
            $counselingReportQuery->where('student_id', $studentId);
        }

        if ($status != 'all') {
            $counselingReportQuery->where('status', $status);
        }

        $reports = $counselingReportQuery->orderBy('requested_date', 'asc')->get();
        $students = Student::with('class')->orderBy('full_name')->get();
        $selectedStudent = $studentId ? Student::with('class')->find($studentId) : null;

        return view('Guru_BK.counseling.report', compact('reports', 'students', 'month', 'year', 'studentId', 'status', 'selectedStudent'));
    }

    public function exportPdf(Request $request)
    {
        $month = (int) $request->input('month', date('n'));
        $year = (int) $request->input('year', date('Y'));
        $studentId = $request->input('student_id') ? (int) $request->input('student_id') : null;
        $status = $request->input('status', 'all');

        $counselingPdfQuery = CounselingSession::with(['student.class', 'guruBk'])
            ->whereMonth('requested_date', $month)
            ->whereYear('requested_date', $year);

        if ($studentId) {
            $counselingPdfQuery->where('student_id', $studentId);
        }

        if ($status != 'all') {
            $counselingPdfQuery->where('status', $status);
        }

        $reports = $counselingPdfQuery->orderBy('requested_date', 'asc')->get();
        $selectedStudent = $studentId ? Student::with(['class', 'parent'])->find($studentId) : null;
        $guruBk = Auth::user();

        $monthNames = [
            1 => 'Januari', 2 => 'Februari', 3 => 'Maret', 4 => 'April',
            5 => 'Mei', 6 => 'Juni', 7 => 'Juli', 8 => 'Agustus',
            9 => 'September', 10 => 'Oktober', 11 => 'November', 12 => 'Desember'
        ];

        $monthName = $monthNames[(int)$month] ?? date('F');

        return view('Guru_BK.counseling.pdf_report', compact('reports', 'month', 'year', 'monthName', 'selectedStudent', 'guruBk', 'status'));
    }

    public function store(Request $request)
    {
        if ($request->has('available_time_start') && $request->has('available_time_end')) {
            $request->merge([
                'slot_waktu' => $request->available_time_start . ' - ' . $request->available_time_end
            ]);
        }

        $request->validate([
            'requested_date' => 'required|date',
            'slot_waktu'     => 'required',
            'topic'          => 'required|string|max:255',
            'description'    => 'nullable|string',
            'type'           => 'required|in:individu,kelompok',
            'student_id'     => 'nullable|exists:students,id',
            'additional_student_ids' => 'nullable|array',
            'additional_student_ids.*' => 'exists:students,id',
            'case_study_id'  => 'nullable|exists:case_studies,id',
        ]);

        $authenticatedUser = Auth::user();

        $selectedStudentId = $request->student_id;
        if (!$selectedStudentId) {
            return back()->with('error', 'Silakan pilih siswa terlebih dahulu.');
        }

        $timeSlotComponents = explode(' - ', $request->slot_waktu);
        $sessionStartTime = $timeSlotComponents[0] ?? null;
        $sessionEndTime = $timeSlotComponents[1] ?? null;

        $counselingSession = CounselingSession::create([
            'student_id'     => $selectedStudentId,
            'additional_student_ids' => $request->type === 'kelompok' ? $request->additional_student_ids : null,
            'case_study_id'  => $request->case_study_id,
            'guru_bk_id'     => $authenticatedUser->id,
            'requested_date' => $request->requested_date,
            'slot_waktu'     => $request->slot_waktu,
            'available_time_start' => $sessionStartTime,
            'available_time_end' => $sessionEndTime,
            'requested_time' => $sessionStartTime,
            'topic'          => $request->topic,
            'description'    => $request->description,
            'type'           => $request->type,
            'status'         => 'disetujui',
            'status_antrian' => 'menunggu',
            'approved_at'    => now(),
        ]);

        $queueAllocationData = $this->generateQueueNumber($counselingSession);
        if (isset($queueAllocationData['error'])) {
            $counselingSession->forceDelete();
            return back()->with('error', $queueAllocationData['error']);
        }
        
        $counselingSession->update([
            'no_antrian'      => $queueAllocationData['no_antrian'],
            'waktu_perkiraan' => $queueAllocationData['waktu_perkiraan_str']
        ]);

        if ($request->case_study_id) {
            $relatedCaseStudy = CaseStudy::find($request->case_study_id);
            if ($relatedCaseStudy) {
                $relatedCaseStudy->update([
                    'status' => 'proses',
                    'handled_by' => $authenticatedUser->id,
                ]);
            }
        }

        return redirect()->route('counseling.index')->with('success', 'Pengajuan jadwal konseling berhasil disimpan.');
    }

    public function approve(Request $request, $counselingSessionId)
    {
        $parsedSessionId = (int) $counselingSessionId;
        $counselingSession = CounselingSession::findOrFail($parsedSessionId);

        $queueAllocationData = $this->generateQueueNumber($counselingSession);
        if (isset($queueAllocationData['error'])) {
            return back()->with('error', $queueAllocationData['error']);
        }
        
        $allocatedQueueNumber = $queueAllocationData['no_antrian'];
        $allocatedTimeString = $queueAllocationData['waktu_perkiraan_str'];

        $counselingSession->update([
            'status'          => 'disetujui',
            'guru_bk_id'      => Auth::id(),
            'notes'           => $request->notes ?? $counselingSession->notes,
            'no_antrian'      => $allocatedQueueNumber,
            'waktu_perkiraan' => $allocatedTimeString,
            'status_antrian'  => 'menunggu',
            'approved_at'     => now(),
        ]);

        if ($counselingSession->case_study_id) {
            $relatedCaseStudy = CaseStudy::find($counselingSession->case_study_id);
            if ($relatedCaseStudy) {
                $relatedCaseStudy->update([
                    'status'     => 'proses',
                    'handled_by' => Auth::id(),
                ]);
            }
        }

        return redirect()->route('counseling.index')->with('success', 'Konseling disetujui. Siswa mendapat antrian ke-'.$allocatedQueueNumber);
    }

    public function reject(Request $request, $counselingSessionId)
    {
        $parsedSessionId = (int) $counselingSessionId;
        $counselingSession = CounselingSession::findOrFail($parsedSessionId);
        $counselingSession->update([
            'status'     => 'ditolak',
            'guru_bk_id' => Auth::id(),
            'notes'      => $request->notes ?? 'Jadwal tidak tersedia, silakan ajukan ulang.',
        ]);

        return redirect()->route('counseling.index')->with('success', 'Pengajuan konseling telah ditolak.');
    }

    public function complete(Request $request, $counselingSessionId)
    {
        $parsedSessionId = (int) $counselingSessionId;
        $counselingSession = CounselingSession::findOrFail($parsedSessionId);
        $counselingSession->update([
            'status'         => 'selesai',
            'status_antrian' => 'selesai',
            'notes'          => $request->notes,
            'completed_at'   => now(),
        ]);

        $this->advanceQueue($counselingSession);

        if ($counselingSession->case_study_id) {
            $relatedCaseStudy = CaseStudy::find($counselingSession->case_study_id);
            if ($relatedCaseStudy) {
                $relatedCaseStudy->update([
                    'status' => 'selesai',
                    'action_taken' => $request->notes,
                    'recommendation' => $relatedCaseStudy->recommendation ?: 'Konseling telah selesai dilakukan.',
                ]);
            }
        }

        return redirect()->route('counseling.index')->with('success', 'Sesi konseling telah selesai dicatat.');
    }

    public function destroy($counselingSessionId)
    {
        $parsedSessionId = (int) $counselingSessionId;
        $counselingSession = CounselingSession::findOrFail($parsedSessionId);
        $counselingSession->delete();

        return redirect()->route('counseling.index')->with('success', 'Jadwal konseling berhasil dihapus.');
    }

    private function generateQueueNumber(CounselingSession $counselingSession)
    {
        $queueCountingQuery = CounselingSession::where('requested_date', $counselingSession->requested_date)
            ->where('slot_waktu', $counselingSession->slot_waktu)
            ->where('guru_bk_id', $counselingSession->guru_bk_id)
            ->whereIn('status', ['disetujui', 'selesai']);

        if ($counselingSession->id) {
            $queueCountingQuery->where('id', '!=', $counselingSession->id);
        }
        
        $previousQueueCount = $queueCountingQuery->count();
        $newQueueNumber = $previousQueueCount + 1;
        $calculatedTimeString = null;

        if ($counselingSession->slot_waktu) {
            $timeSlotComponents = explode(' - ', $counselingSession->slot_waktu);
            $sessionStartTime = $timeSlotComponents[0];
            $sessionEndTime = $timeSlotComponents[1] ?? null;
            $startTimeCarbon = Carbon::parse($sessionStartTime);
            
            $latestScheduledSession = (clone $queueCountingQuery)->orderBy('no_antrian', 'desc')->first();

            if ($latestScheduledSession && $latestScheduledSession->waktu_perkiraan) {
                $calculatedTime = Carbon::parse($latestScheduledSession->waktu_perkiraan)
                    ->addMinutes(rand(25, 35));
            } else {
                $calculatedTime = $startTimeCarbon->addMinutes(rand(0, 5));
            }

            if ($sessionEndTime && $calculatedTime->greaterThan(Carbon::parse($sessionEndTime))) {
                return ['error' => 'Maaf, kuota antrean untuk jadwal tersebut sudah penuh (melebihi jam ketersediaan guru).'];
            }
            
            $calculatedTimeString = $calculatedTime->format('H:i');
        }

        return [
            'no_antrian' => $newQueueNumber,
            'waktu_perkiraan_str' => $calculatedTimeString
        ];
    }

    private function advanceQueue(CounselingSession $counselingSession)
    {
        $nextInLineSession = CounselingSession::whereDate('requested_date', $counselingSession->requested_date)
            ->where('guru_bk_id', $counselingSession->guru_bk_id)
            ->where('status', 'disetujui')
            ->where('status_antrian', 'menunggu')
            ->orderBy('no_antrian', 'asc')
            ->first();
            
        if ($nextInLineSession) {
            $nextInLineSession->update(['status_antrian' => 'sekarang']);
        }
    }
}
