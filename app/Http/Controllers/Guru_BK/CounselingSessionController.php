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
        $today = date('Y-m-d');

        $currentQueue = CounselingSession::with(['student.class', 'guruBk'])
            ->whereDate('requested_date', $today)
            ->where('status_antrian', 'sekarang')
            ->orderBy('id', 'desc')
            ->first();

        $nextQueue = CounselingSession::with(['student.class', 'guruBk'])
            ->whereDate('requested_date', $today)
            ->where('status', 'disetujui')
            ->where('status_antrian', 'menunggu')
            ->orderBy('no_antrian', 'asc')
            ->first();

        // Jika tidak ada antrean berjalan hari ini, promosikan antrean pertama yang menunggu
        if (!$currentQueue && $nextQueue) {
            $nextQueue->update(['status_antrian' => 'sekarang']);
            $currentQueue = $nextQueue;
            
            // Ambil ulang antrean berikutnya
            $nextQueue = CounselingSession::with(['student.class', 'guruBk'])
                ->whereDate('requested_date', $today)
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
                    'student_name' => $currentQueue->student->full_name ?? 'Siswa',
                    'class_name' => $currentQueue->student->class->school_class_name ?? null,
                    'topic' => \Illuminate\Support\Str::limit($currentQueue->topic, 30),
                    'waktu_perkiraan' => substr($currentQueue->waktu_perkiraan ?? $currentQueue->requested_time, 0, 5),
                ] : null,
                'nextQueue' => $nextQueue ? [
                    'no_antrian' => $nextQueueNumber,
                    'student_name' => $nextQueue->student->full_name ?? 'Siswa',
                    'class_name' => $nextQueue->student->class->school_class_name ?? null,
                    'topic' => \Illuminate\Support\Str::limit($nextQueue->topic, 30),
                    'waktu_perkiraan' => substr($nextQueue->waktu_perkiraan ?? $nextQueue->requested_time, 0, 5),
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

        return view('Guru_BK.counseling.report', compact('reports', 'students', 'month', 'year', 'studentId', 'status', 'selectedStudent'));
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

        return view('Guru_BK.counseling.pdf_report', compact('reports', 'month', 'year', 'monthName', 'selectedStudent', 'guruBk', 'status'));
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

        return view('Guru_BK.counseling.siswa_index', compact('student', 'mySessions'));
    }

    /**
     * Simpan pengajuan konseling baru (Siswa / Guru BK)
     */
    public function store(Request $request)
    {
        // Gabungkan slot_waktu jika dari form Guru BK (menggunakan jam tersedia)
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

        $parts = explode(' - ', $request->slot_waktu);
        $jam_awal = $parts[0] ?? null;
        $jam_akhir = $parts[1] ?? null;

        $session = CounselingSession::create([
            'student_id'     => $studentId,
            'additional_student_ids' => $request->type === 'kelompok' ? $request->additional_student_ids : null,
            'case_study_id'  => $request->case_study_id,
            'guru_bk_id'     => $user->isGuruBk() ? $user->id : $request->guru_bk_id,
            'requested_date' => $request->requested_date,
            'slot_waktu'     => $request->slot_waktu,
            'available_time_start' => $jam_awal,
            'available_time_end' => $jam_akhir,
            'requested_time' => $jam_awal,
            'topic'          => $request->topic,
            'description'    => $request->description,
            'type'           => $request->type,
            'status'         => $user->isGuruBk() ? 'disetujui' : 'menunggu',
            'status_antrian' => 'menunggu',
            'approved_at'    => $user->isGuruBk() ? now() : null,
        ]);

        // Jika Guru BK yang membuat, karena auto disetujui, langsung buatkan nomor antrian dan waktu perkiraan
        if ($user->isGuruBk()) {
            $antrian_sebelumnya = CounselingSession::where('requested_date', $session->requested_date)
                ->where('slot_waktu', $session->slot_waktu)
                ->where('guru_bk_id', $session->guru_bk_id)
                ->whereIn('status', ['disetujui', 'selesai'])
                ->where('id', '!=', $session->id) // kecualikan data ini sendiri
                ->count();
            
            $no_antrian_baru = $antrian_sebelumnya + 1;

            $waktu_perkiraan_str = null;
            if ($session->slot_waktu) {
                $parts = explode(' - ', $session->slot_waktu);
                $jam_awal = $parts[0];
                $jam_akhir = $parts[1] ?? null;
                $waktu_awal_obj = \Carbon\Carbon::parse($jam_awal);
                
                $sesi_terakhir = CounselingSession::where('requested_date', $session->requested_date)
                    ->where('slot_waktu', $session->slot_waktu)
                    ->where('guru_bk_id', $session->guru_bk_id)
                    ->whereIn('status', ['disetujui', 'selesai'])
                    ->where('id', '!=', $session->id)
                    ->orderBy('no_antrian', 'desc')
                    ->first();

                if ($sesi_terakhir && $sesi_terakhir->waktu_perkiraan) {
                    $waktu_perkiraan = \Carbon\Carbon::parse($sesi_terakhir->waktu_perkiraan)
                        ->addMinutes(rand(25, 35));
                } else {
                    $waktu_perkiraan = $waktu_awal_obj->addMinutes(rand(0, 5));
                }

                // Cek apakah melebihi batas jam akhir
                if ($jam_akhir && $waktu_perkiraan->greaterThan(\Carbon\Carbon::parse($jam_akhir))) {
                    $session->forceDelete();
                    return back()->with('error', 'Maaf, kuota antrean untuk jadwal tersebut sudah penuh (melebihi jam ketersediaan guru).');
                }
                
                $waktu_perkiraan_str = $waktu_perkiraan->format('H:i');
            }
            
            $session->update([
                'no_antrian' => $no_antrian_baru,
                'waktu_perkiraan' => $waktu_perkiraan_str
            ]);
        }

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
        return redirect()->route('counseling.siswa')->with('success', 'Pengajuan jadwal konseling berhasil disimpan.');
    }

    /**
     * Setujui pengajuan konseling (Guru BK)
     */
    public function approve(Request $request, $id)
    {
        $session = CounselingSession::findOrFail($id);

        // 1. Hitung antrian sebelumnya (gunakan whereIn untuk array)
        $antrian_sebelumnya = CounselingSession::where('requested_date', $session->requested_date)
            ->where('slot_waktu', $session->slot_waktu)
            ->where('guru_bk_id', $session->guru_bk_id)
            ->whereIn('status', ['disetujui', 'selesai'])
            ->count();
        
        $no_antrian_baru = $antrian_sebelumnya + 1;

        $waktu_perkiraan_str = null;
        if ($session->slot_waktu) {
            $parts = explode(' - ', $session->slot_waktu);
            $jam_awal = $parts[0];
            $jam_akhir = $parts[1] ?? null;
            $waktu_awal_obj = \Carbon\Carbon::parse($jam_awal);
            
            // Cari sesi terakhir yang disetujui pada slot yang sama untuk meneruskan jamnya
            $sesi_terakhir = CounselingSession::where('requested_date', $session->requested_date)
                ->where('slot_waktu', $session->slot_waktu)
                ->where('guru_bk_id', $session->guru_bk_id)
                ->whereIn('status', ['disetujui', 'selesai'])
                ->orderBy('no_antrian', 'desc')
                ->first();

            if ($sesi_terakhir && $sesi_terakhir->waktu_perkiraan) {
                // Tambahkan waktu acak sekitar 30 menit (25 s.d 35) dari jam orang sebelumnya
                $waktu_perkiraan = \Carbon\Carbon::parse($sesi_terakhir->waktu_perkiraan)
                    ->addMinutes(rand(25, 35));
            } else {
                // Jika ini antrian pertama, jam awal + sedikit delay acak (0 s.d 5 menit)
                $waktu_perkiraan = $waktu_awal_obj->addMinutes(rand(0, 5));
            }

            // Cek apakah melebihi batas jam akhir ketersediaan guru
            if ($jam_akhir && $waktu_perkiraan->greaterThan(\Carbon\Carbon::parse($jam_akhir))) {
                return back()->with('error', 'Maaf, kuota antrean untuk jadwal tersebut sudah penuh (melebihi jam ketersediaan guru). Mohon tolak atau atur ulang jadwal.');
            }

            $waktu_perkiraan_str = $waktu_perkiraan->format('H:i');
        }

        // 3. Update status & antrian
        $session->update([
            'status'          => 'disetujui',
            'guru_bk_id'      => Auth::id(),
            'notes'           => $request->notes ?? $session->notes,
            'no_antrian'      => $no_antrian_baru,
            'waktu_perkiraan' => $waktu_perkiraan_str,
            'status_antrian'  => 'menunggu',
            'approved_at'     => now(),
        ]);

        // Automate CaseStudy status to 'proses'
        if ($session->case_study_id) {
            $caseStudy = CaseStudy::find($session->case_study_id);
            if ($caseStudy) {
                $caseStudy->update([
                    'status'     => 'proses',
                    'handled_by' => Auth::id(),
                ]);
            }
        }

        return redirect()->route('counseling.index')->with('success', 'Konseling disetujui. Siswa mendapat antrian ke-'.$no_antrian_baru);
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
            'status'         => 'selesai',
            'status_antrian' => 'selesai',
            'notes'          => $request->notes,
            'completed_at'   => now(),
        ]);

        // Cari antrean berikutnya hari ini untuk guru tersebut yang berstatus menunggu
        $next = CounselingSession::whereDate('requested_date', $session->requested_date)
            ->where('guru_bk_id', $session->guru_bk_id)
            ->where('status', 'disetujui')
            ->where('status_antrian', 'menunggu')
            ->orderBy('no_antrian', 'asc')
            ->first();
            
        if ($next) {
            $next->update(['status_antrian' => 'sekarang']);
        }

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
            'status'         => 'dibatalkan',
            'status_antrian' => 'selesai',
        ]);

        // Cari antrean berikutnya hari ini untuk guru tersebut yang berstatus menunggu
        $next = CounselingSession::whereDate('requested_date', $session->requested_date)
            ->where('guru_bk_id', $session->guru_bk_id)
            ->where('status', 'disetujui')
            ->where('status_antrian', 'menunggu')
            ->orderBy('no_antrian', 'asc')
            ->first();
            
        if ($next) {
            $next->update(['status_antrian' => 'sekarang']);
        }

        if (Auth::user()->isGuruBk()) {
            return redirect()->route('counseling.index')->with('success', 'Pengajuan konseling berhasil dibatalkan.');
        }
        return redirect()->route('counseling.siswa')->with('success', 'Pengajuan konseling berhasil dibatalkan.');
    }

    /**
     * Hapus sesi konseling
     */
    public function destroy($id)
    {
        $session = CounselingSession::findOrFail($id);
        $session->delete();

        return redirect()->route('counseling.index')->with('success', 'Jadwal konseling berhasil dihapus.');
    }

}
