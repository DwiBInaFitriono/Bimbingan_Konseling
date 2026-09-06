<?php

namespace App\Http\Controllers\Guru_BK;

use App\Http\Controllers\Controller;
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
        $currentUser = Auth::user();
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

    public function Siswa()
    {
        $studentList = Student::with(['class', 'parent', 'user'])->latest()->get();
        $classList = ClassData::all();
        return view('Guru_BK.Student.siswa', ['datasiswa' => $studentList, 'datakelas' => $classList]);
    }

    public function tambahSiswa()
    {
        $classList = ClassData::all();
        $parentList = Parents::all();
        return view('Guru_BK.Student.tambah', compact('classList', 'parentList'));
    }

    public function simpanSiswa(Request $request)
    {
        $request->validate([
            'full_name' => 'required|string|max:255',
            'nis'       => 'required|string|unique:students,nis',
            'email'     => 'nullable|email|unique:users,email',
        ]);

        $createdUserId = null;
        if ($request->filled('email')) {
            $createdUser = User::create([
                'name'     => $request->full_name,
                'email'    => $request->email,
                'password' => Hash::make($request->nis),
                'role'     => 'siswa',
            ]);
            $createdUserId = $createdUser->id;
        }

        $createdParentId = null;
        if ($request->filled('parent_full_name')) {
            $parentRecord = Parents::create([
                'parent_full_name' => $request->parent_full_name,
                'relationship'     => $request->parent_relationship,
                'phone_number'     => $request->parent_phone_number,
            ]);
            $createdParentId = $parentRecord->id;
        }

        Student::create([
            'user_id'       => $createdUserId,
            'full_name'     => $request->full_name,
            'nis'           => $request->nis,
            'class_id'      => $request->class_id,
            'parent_id'     => $createdParentId,
            'gender'        => $request->gender ?? 'L',
            'date_of_birth' => $request->date_of_birth,
            'address'       => $request->address,
            'phone_number'  => $request->phone_number,
        ]);

        return redirect('siswa')->with('success', 'Data siswa berhasil disimpan.');
    }

    public function hapusSiswa($studentId)
    {
        $student = Student::findOrFail($studentId);
        if ($student->user) {
            $student->user->delete();
        }
        $student->delete();

        return redirect()->back()->with('success', 'Data siswa berhasil dihapus.');
    }

    public function editSiswa($studentId)
    {
        $classList = ClassData::all();
        $parentList = Parents::all();
        $student = Student::with('user')->findOrFail($studentId);

        return view('Guru_BK.Student.edit', [
            'datasiswa' => $student,
            'datakelas' => $classList,
            'dataparent' => $parentList
        ]);
    }

    public function updateSiswa(Request $request, $studentId)
    {
        $student = Student::findOrFail($studentId);

        $request->validate([
            'full_name' => 'required|string|max:255',
            'nis'       => 'required|string|unique:students,nis,' . $studentId,
        ]);

        if ($request->filled('parent_full_name')) {
            if ($student->parent_id) {
                $parentRecord = Parents::find($student->parent_id);
                if ($parentRecord) {
                    $parentRecord->update([
                        'parent_full_name' => $request->parent_full_name,
                        'relationship'     => $request->parent_relationship,
                        'phone_number'     => $request->parent_phone_number,
                    ]);
                }
            } else {
                $parentRecord = Parents::create([
                    'parent_full_name' => $request->parent_full_name,
                    'relationship'     => $request->parent_relationship,
                    'phone_number'     => $request->parent_phone_number,
                ]);
                $student->parent_id = $parentRecord->id;
            }
        }

        $student->full_name     = $request->full_name;
        $student->nis           = $request->nis;
        $student->class_id      = $request->class_id;
        $student->gender        = $request->gender ?? $student->gender;
        $student->date_of_birth = $request->date_of_birth;
        $student->address       = $request->address;
        $student->phone_number  = $request->phone_number;
        $student->save();

        if ($student->user) {
            $student->user->name = $request->full_name;
            if ($request->filled('email')) {
                $student->user->email = $request->email;
            }
            $student->user->save();
        } elseif ($request->filled('email')) {
            $createdUser = User::create([
                'name'     => $request->full_name,
                'email'    => $request->email,
                'password' => Hash::make($request->nis),
                'role'     => 'siswa',
            ]);
            $student->user_id = $createdUser->id;
            $student->save();
        }

        return redirect()->route('siswa.tampil')->with('success', 'Data siswa berhasil diperbarui.');
    }

    public function printWarningLetter(Request $request, $studentId)
    {
        $student = Student::with(['class', 'parent', 'pointDatas' => function ($pointDataQuery) {
            $pointDataQuery->orderBy('violation_date', 'asc');
        }])->findOrFail($studentId);

        $warningLetterType = $request->query('type', 'default');

        return view('Guru_BK.Student.cetak_sp', ['student' => $student, 'printType' => $warningLetterType]);
    }
}
