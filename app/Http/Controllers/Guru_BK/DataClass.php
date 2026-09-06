<?php

namespace App\Http\Controllers\Guru_BK;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\ClassData;

class DataClass extends Controller
{
    public function classData()
    {
        $classList = ClassData::withCount('student')->orderBy('grade')->orderBy('school_class_name')->get();
        return view('Guru_BK.Class.class', ['datakelas' => $classList]);
    }

    public function addClass()
    {
        return view('Guru_BK.Class.tambah');
    }

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

    public function editClass($classDataId)
    {
        $parsedClassId = (int) $classDataId;
        $classData = ClassData::findOrFail($parsedClassId);
        return view('Guru_BK.Class.edit', ['datakelas' => $classData]);
    }

    public function updateClass(Request $request, $classDataId)
    {
        $parsedClassId = (int) $classDataId;
        $request->validate([
            'grade'              => 'required|in:10,11,12',
            'school_class_name'  => 'required|string|max:255',
            'school_class_major' => 'required|string|max:255',
        ]);

        $classData = ClassData::findOrFail($parsedClassId);
        $classData->grade              = $request->grade;
        $classData->school_class_name  = $request->school_class_name;
        $classData->school_class_major = $request->school_class_major;
        $classData->save();

        return redirect()->route('kelas.tampil')->with('success', 'Data kelas berhasil diperbarui.');
    }

    public function destroyClass($classDataId)
    {
        $parsedClassId = (int) $classDataId;
        $classData = ClassData::findOrFail($parsedClassId);
        $classData->delete();
        return redirect()->back()->with('success', 'Data kelas berhasil dihapus.');
    }
}
