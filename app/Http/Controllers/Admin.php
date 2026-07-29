<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Carbon\Carbon;
use App\Models\CaseStudy;
use App\Models\User;
use App\Models\Student;
use App\Models\ClassData;
use App\Models\Parents;
use App\Models\CounselingSession;
use App\Models\Achievement;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;

class Admin extends Controller
{
    public function index()
    {
        $user = Auth::user();

        // Redirect jika role adalah siswa
        if ($user->isSiswa()) {
            return redirect()->route('counseling.siswa');
        }

        $currentUser = $user;
        $totalStudents = Student::count();
        $totalClass = ClassData::count();
        $reportsToday = CaseStudy::whereDate('created_at', Carbon::today())->count();
        $pendingCounseling = CounselingSession::where('status', 'menunggu')->count();
        $dangerStudents = Student::where('status', 'bahaya')->count();

        $recentAchievements = Achievement::with('student.class')->latest()->take(5)->get();
        $recentCases = CaseStudy::with('student.class')->latest()->take(5)->get();
        $recentCounseling = CounselingSession::with('student.class')->where('status', 'menunggu')->latest()->take(5)->get();

        return view('template', compact(
            'currentUser',
            'totalStudents',
            'totalClass',
            'reportsToday',
            'pendingCounseling',
            'dangerStudents',
            'recentAchievements',
            'recentCases',
            'recentCounseling'
        ));
    }

    // Menampilkan Halaman Siswa
    public function Siswa()
    {
        $data = Student::with(['class', 'parent', 'user'])->latest()->get();
        return view('Student.siswa', ['datasiswa' => $data]);
    }

    public function tambahSiswa()
    {
        $datakelas = ClassData::all();
        $dataparent = Parents::all();
        return view('Student.tambah', compact('datakelas', 'dataparent'));
    }

    public function simpanSiswa(Request $request)
    {
        $request->validate([
            'full_name' => 'required|string|max:255',
            'nis'       => 'required|string|unique:students,nis',
            'email'     => 'nullable|email|unique:users,email',
        ]);

        $userId = null;
        if ($request->filled('email')) {
            $user = User::create([
                'name'     => $request->full_name,
                'email'    => $request->email,
                'password' => Hash::make($request->nis), // default password NIS
                'role'     => 'siswa',
            ]);
            $userId = $user->id;
        }

        $parentId = null;
        if ($request->filled('parent_full_name')) {
            $parent = Parents::create([
                'parent_full_name' => $request->parent_full_name,
                'relationship'     => $request->parent_relationship,
                'phone_number'     => $request->parent_phone_number,
            ]);
            $parentId = $parent->id;
        }

        Student::create([
            'user_id'       => $userId,
            'full_name'     => $request->full_name,
            'nis'           => $request->nis,
            'class_id'      => $request->class_id,
            'parent_id'     => $parentId,
            'gender'        => $request->gender ?? 'L',
            'date_of_birth' => $request->date_of_birth,
            'address'       => $request->address,
            'phone_number'  => $request->phone_number,
        ]);

        return redirect('siswa')->with('success', 'Data siswa berhasil disimpan.');
    }

    public function hapusSiswa($id)
    {
        $datasiswa = Student::findOrFail($id);
        if ($datasiswa->user) {
            $datasiswa->user->delete();
        }
        $datasiswa->delete();

        return redirect()->back()->with('success', 'Data siswa berhasil dihapus.');
    }

    public function editSiswa($id)
    {
        $datakelas = ClassData::all();
        $dataparent = Parents::all();
        $datasiswa = Student::with('user')->findOrFail($id);

        return view('Student.edit', compact('datasiswa', 'datakelas', 'dataparent'));
    }

    public function updateSiswa(Request $request, $id)
    {
        $datasiswa = Student::findOrFail($id);

        $request->validate([
            'full_name' => 'required|string|max:255',
            'nis'       => 'required|string|unique:students,nis,' . $id,
        ]);

        if ($request->filled('parent_full_name')) {
            if ($datasiswa->parent_id) {
                $parent = Parents::find($datasiswa->parent_id);
                if ($parent) {
                    $parent->update([
                        'parent_full_name' => $request->parent_full_name,
                        'relationship'     => $request->parent_relationship,
                        'phone_number'     => $request->parent_phone_number,
                    ]);
                }
            } else {
                $parent = Parents::create([
                    'parent_full_name' => $request->parent_full_name,
                    'relationship'     => $request->parent_relationship,
                    'phone_number'     => $request->parent_phone_number,
                ]);
                $datasiswa->parent_id = $parent->id;
            }
        }

        $datasiswa->full_name     = $request->full_name;
        $datasiswa->nis           = $request->nis;
        $datasiswa->class_id      = $request->class_id;
        $datasiswa->gender        = $request->gender ?? $datasiswa->gender;
        $datasiswa->date_of_birth = $request->date_of_birth;
        $datasiswa->address       = $request->address;
        $datasiswa->phone_number  = $request->phone_number;
        $datasiswa->save();

        if ($datasiswa->user) {
            $datasiswa->user->name = $request->full_name;
            if ($request->filled('email')) {
                $datasiswa->user->email = $request->email;
            }
            $datasiswa->user->save();
        } elseif ($request->filled('email')) {
            $user = User::create([
                'name'     => $request->full_name,
                'email'    => $request->email,
                'password' => Hash::make($request->nis),
                'role'     => 'siswa',
            ]);
            $datasiswa->user_id = $user->id;
            $datasiswa->save();
        }

        return redirect()->route('siswa.tampil')->with('success', 'Data siswa berhasil diperbarui.');
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

        return view('Student.cetak_sp', compact('student', 'printType'));
    }
}
