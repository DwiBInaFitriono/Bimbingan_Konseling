<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\ClassData;

class DataClass extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function classData()
    {
        $data = ClassData::withCount('student')->orderBy('grade')->orderBy('school_class_name')->get();
        return view('Class.class', ['datakelas' => $data]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function addClass()
    {
        return view('Class.tambah');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function storeClass(Request $request)
    {
        $request->validate([
            'grade'              => 'required|in:10,11,12',
            'school_class_name'  => 'required|string|max:255',
            'school_class_major' => 'required|string|max:255',
        ]);

        ClassData::create([
            'grade'              => $request->grade,
            'school_class_name'  => $request->school_class_name,
            'school_class_major' => $request->school_class_major,
        ]);

        return redirect()->route('kelas.tampil')->with('success', 'Data kelas berhasil disimpan.');
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function editClass($id)
    {
        $datakelas = ClassData::findOrFail($id);
        return view('Class.edit', compact('datakelas'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function updateClass(Request $request, $id)
    {
        $request->validate([
            'grade'              => 'required|in:10,11,12',
            'school_class_name'  => 'required|string|max:255',
            'school_class_major' => 'required|string|max:255',
        ]);

        $datakelas = ClassData::findOrFail($id);

        $datakelas->grade              = $request->grade;
        $datakelas->school_class_name  = $request->school_class_name;
        $datakelas->school_class_major = $request->school_class_major;
        $datakelas->save();

        return redirect()->route('kelas.tampil')->with('success', 'Data kelas berhasil diperbarui.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroyClass($id)
    {
        $data = ClassData::findOrFail($id);
        $data->delete();
        return redirect()->back()->with('success', 'Data kelas berhasil dihapus.');
    }
}
