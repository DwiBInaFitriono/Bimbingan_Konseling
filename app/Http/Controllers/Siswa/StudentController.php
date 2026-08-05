<?php

namespace App\Http\Controllers\Siswa;

use App\Http\Controllers\Controller;
use App\Models\Student;
use App\Models\CounselingSession;
use App\Models\CaseStudy;
use App\Models\Achievement;
use App\Models\PointData;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class StudentController extends Controller
{
    /**
     * Dashboard siswa - ringkasan poin, kasus, prestasi, konseling
     */
    public function dashboard()
    {
        $user = Auth::user();
        $student = $user->student ?? Student::where('full_name', $user->name)->first();

        if (!$student) {
            return redirect()->route('counseling.siswa')->with('error', 'Data profil siswa belum tersedia.');
        }

        $student->load('class');
        $guru_bk = \App\Models\User::where('role', 'guru_bk')->get();

        // Satu query konseling, sisanya dihitung dari collection
        $riwayatKonseling = CounselingSession::with('guruBk')
            ->where('student_id', $student->id)
            ->latest()
            ->take(5)
            ->get();

        $konselingAktif = $riwayatKonseling->whereIn('status', ['menunggu', 'disetujui'])->count();

        return view('Siswa.dashboard.index', [
            'student'           => $student,
            'totalPoin'         => $student->total_points ?? 0,
            'status'            => $student->status ?? 'aman',
            'jumlahKonseling'   => CounselingSession::where('student_id', $student->id)->count(),
            'konselingAktif'    => $konselingAktif,
            'konselingSelesai'  => CounselingSession::where('student_id', $student->id)->where('status', 'selesai')->count(),
            'jumlahKasus'       => CaseStudy::where('student_id', $student->id)->count(),
            'jumlahPrestasi'    => Achievement::where('student_id', $student->id)->count(),
            'riwayatPoin'        => PointData::with('recorder')->where('student_id', $student->id)->latest('violation_date')->take(5)->get(),
            'riwayatKasus'       => CaseStudy::where('student_id', $student->id)->latest('case_date')->take(5)->get(),
            'riwayatPrestasi'    => Achievement::where('student_id', $student->id)->latest('achievement_date')->take(5)->get(),
            'riwayatKonseling'   => $riwayatKonseling,
            'guru_bk'            => $guru_bk,
        ]);
    }

    // Cetak Surat Peringatan / SP Siswa (Poin Pelanggaran)
    public function printWarningLetter(Request $request, $id)
    {
        $user = Auth::user();
        if ($user->isSiswa()) {
            // Siswa hanya boleh melihat/mencetak surat peringatan milik sendiri
            $studentModel = $user->student;
            if (!$studentModel) {
                $studentModel = Student::where('full_name', $user->name)->first();
            }
            if (!$studentModel || $studentModel->id != $id) {
                abort(403, 'Akses ditolak. Anda hanya dapat melihat surat peringatan milik akun Anda sendiri.');
            }
        }

        $student = Student::with(['class', 'parent', 'pointDatas' => function ($query) {
            $query->orderBy('violation_date', 'asc');
        }])->findOrFail($id);

        $printType = $request->query('type', 'default'); // default or expel

        return view('Siswa.Student.cetak_sp', compact('student', 'printType'));
    }
}
