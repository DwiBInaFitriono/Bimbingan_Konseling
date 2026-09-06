<?php

namespace App\Http\Controllers\Guru_BK;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Achievement;
use App\Models\Student;

class Achievements extends Controller
{
    public function indexAchievement()
    {
        $achievementList = Achievement::with('student')->get();
        return view('Guru_BK.Achievement.prestasi', ['prestasi' => $achievementList]);
    }

    public function createAchievement()
    {
        $studentList = Student::all();
        return view('Guru_BK.Achievement.tambah', ['datasiswa' => $studentList]);
    }

    public function storeAchievement(Request $request)
    {
        $request->validate([
            'student_id' => 'required|exists:students,id',
            'achievement_name' => 'required|string',
            'achievement_date' => 'required|date',
            'achievement_level' => 'required|string',
            'achievement_category' => 'required|string',
            'achievement_status' => 'required|string',
        ]);

        Achievement::create([
            'student_id' => $request->student_id,
            'achievement_name' => $request->achievement_name,
            'achievement_date' => $request->achievement_date,
            'achievement_level' => $request->achievement_level,
            'achievement_category' => $request->achievement_category,
            'achievement_status' => $request->achievement_status,
        ]);

        return redirect('dataprestasi')->with('success', 'Data prestasi berhasil ditambahkan.');
    }

    public function editAchievement($achievementId)
    {
        $parsedAchievementId = (int) $achievementId;
        $achievement = Achievement::findOrFail($parsedAchievementId);
        $studentList = Student::all();
        return view('Guru_BK.Achievement.edit', [
            'dataprestasi' => $achievement,
            'datasiswa' => $studentList,
        ]);
    }

    public function updateAchievement(Request $request, $achievementId)
    {
        $parsedAchievementId = (int) $achievementId;
        $request->validate([
            'student_id' => 'required|exists:students,id',
            'achievement_name' => 'required|string',
            'achievement_date' => 'required|date',
            'achievement_level' => 'required|string',
            'achievement_category' => 'required|string',
            'achievement_status' => 'required|string',
        ]);

        $achievement = Achievement::findOrFail($parsedAchievementId);
        $achievement->student_id = $request->student_id;
        $achievement->achievement_name = $request->achievement_name;
        $achievement->achievement_date = $request->achievement_date;
        $achievement->achievement_level = $request->achievement_level;
        $achievement->achievement_category = $request->achievement_category;
        $achievement->achievement_status = $request->achievement_status;
        $achievement->save();

        return redirect()->route('dataprestasi.tampil')->with('success', 'Data prestasi berhasil diperbarui.');
    }

    public function destroyAchievement($achievementId)
    {
        $parsedAchievementId = (int) $achievementId;
        $achievement = Achievement::findOrFail($parsedAchievementId);
        $achievement->delete();

        return redirect()->back()->with('success', 'Data prestasi berhasil dihapus.');
    }
}
