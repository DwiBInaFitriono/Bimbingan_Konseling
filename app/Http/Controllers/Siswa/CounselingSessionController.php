<?php

namespace App\Http\Controllers\Siswa;

use App\Http\Controllers\Controller;
use App\Models\CounselingSession;
use App\Models\Student;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CounselingSessionController extends Controller
{
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
        $guru_bk = \App\Models\User::where('role', 'guru_bk')->get();

        return view('Siswa.counseling.siswa_index', compact('student', 'mySessions', 'guru_bk'));
    }

    /**
     * Simpan pengajuan konseling baru (Khusus Siswa)
     */
    public function store(Request $request)
    {
        $request->validate([
            'requested_date' => 'required|date',
            'guru_bk_id'     => 'required',
            'slot_waktu'     => 'required',
            'topic'          => 'required|string|max:255',
            'description'    => 'nullable|string',
            'type'           => 'required|in:individu,kelompok',
        ]);

        $user = Auth::user();
        $student = $user->student;
        if (!$student) {
            return back()->with('error', 'Data profil siswa Anda tidak ditemukan.');
        }

        // Validasi: Cegah pengajuan baru di hari yang sama jika masih ada yang belum selesai (menunggu/disetujui)
        $existingSession = CounselingSession::where('student_id', $student->id)
            ->whereDate('requested_date', $request->requested_date)
            ->whereIn('status', ['menunggu', 'disetujui'])
            ->exists();

        if ($existingSession) {
            return back()->with('error', 'Jadwal di tanggal tersebut masih aktif. Harap tunggu atau pilih hari lain.');
        }

        $session = new CounselingSession();
        $session->guru_bk_id = $request->guru_bk_id; 
        $session->slot_waktu = $request->slot_waktu;
        
        // Mengakali database yang mewajibkan requested_time
        $jam_awal = explode(' - ', $request->slot_waktu)[0];
        $session->requested_time = $jam_awal;

        $session->student_id = $student->id;
        $session->requested_date = $request->requested_date;
        $session->topic = $request->topic;
        $session->description = $request->description;
        $session->type = $request->type;
        $session->status = 'menunggu';
        $session->save();

        return redirect()->route('counseling.siswa')->with('success', 'Pengajuan konseling berhasil dikirim.');
    }

    /**
     * Batalkan pengajuan konseling oleh Siswa
     */
    public function cancel($id)
    {
        $session = CounselingSession::findOrFail($id);
        
        $user = Auth::user();
        $student = $user->student;

        if (!$student || $session->student_id != $student->id) {
            return back()->with('error', 'Anda tidak berhak membatalkan pengajuan ini.');
        }

        if ($session->status != 'menunggu') {
            return back()->with('error', 'Hanya pengajuan dengan status menunggu yang bisa dibatalkan.');
        }

        $session->status = 'dibatalkan';
        $session->save();

        return back()->with('success', 'Pengajuan konseling berhasil dibatalkan.');
    }
}
