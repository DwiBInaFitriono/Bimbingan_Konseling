<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Achievement;
use App\Models\Student;

class Achievements extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function indexAchievement()
    {
        $dataprestasi = Achievement::with('student')->get();
        return view('Achievement.prestasi', ['prestasi' => $dataprestasi]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function createAchievement()
    {
        $datasiswa = Student::all();
        return view('Achievement.tambah', ['datasiswa' => $datasiswa]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function storeAchievement(Request $request)
    {
        Achievement::create([
            'student_id' => $request->student_id,
            'achievement_name' => $request->achievement_name,
            'achievement_date' => $request->achievement_date,
            'achievement_level' => $request->achievement_level,
            'achievement_category' => $request->achievement_category,
            'achievement_status' => $request->achievement_status,
        ]);

        return redirect('dataprestasi');
    }

    /**
     * Display the specified resource.
     */
    public function showAchievement($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function editAchievement($id)
    {
        $dataprestasi = Achievement::find($id);
        $datasiswa = Student::all();
        return view('Achievement.edit', compact('dataprestasi', 'datasiswa'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function updateAchievement(Request $request, $id)
    {
        $dataprestasi = Achievement::find($id);
        $dataprestasi->student_id = $request->student_id;
        $dataprestasi->achievement_name = $request->achievement_name;
        $dataprestasi->achievement_date = $request->achievement_date;
        $dataprestasi->achievement_level = $request->achievement_level;
        $dataprestasi->achievement_category = $request->achievement_category;
        $dataprestasi->achievement_status = $request->achievement_status;


        $dataprestasi->save();

        return redirect()->route('dataprestasi.tampil');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroyAchievement($id)
    {
        $dataprestasi = Achievement::find($id);

        $dataprestasi->delete();

        return redirect()->back();
    }
}
