<?php

namespace App\Http\Controllers\Guru_BK;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Parents;

class ParentStudent extends Controller
{
    public function parentData()
    {
        $parentList = Parents::with(['student.class'])->get();
        return view('Guru_BK.Parents.parent', ['parentdata' => $parentList]);
    }

    public function addParent()
    {
        return view('Guru_BK.Parents.tambah');
    }

    public function storeParent(Request $request)
    {
        $request->validate([
            'parent_full_name' => 'required|string|max:255',
            'address'          => 'nullable|string|max:500',
            'job'              => 'nullable|string|max:255',
            'phone_number'     => 'nullable|string|max:20',
        ]);

        Parents::create([
            'parent_full_name' => $request->parent_full_name,
            'address'          => $request->address,
            'job'              => $request->job,
            'phone_number'     => $request->phone_number,
        ]);

        return redirect('ortu')->with('success', 'Data orang tua berhasil disimpan.');
    }

    public function editParent($parentId)
    {
        $parsedParentId = (int) $parentId;
        $parentData = Parents::findOrFail($parsedParentId);
        return view('Guru_BK.Parents.edit', ['dataparent' => $parentData]);
    }

    public function updateParent(Request $request, $parentId)
    {
        $parsedParentId = (int) $parentId;
        $request->validate([
            'parent_full_name' => 'required|string|max:255',
            'address'          => 'nullable|string|max:500',
            'job'              => 'nullable|string|max:255',
            'phone_number'     => 'nullable|string|max:20',
        ]);

        $parentData = Parents::findOrFail($parsedParentId);
        $parentData->parent_full_name = $request->parent_full_name;
        $parentData->address          = $request->address;
        $parentData->job              = $request->job;
        $parentData->phone_number     = $request->phone_number;
        $parentData->save();

        return redirect()->route('ortu.tampil')->with('success', 'Data orang tua berhasil diperbarui.');
    }

    public function destroyParent($parentId)
    {
        $parsedParentId = (int) $parentId;
        $parentData = Parents::findOrFail($parsedParentId);
        $parentData->delete();

        return redirect()->back()->with('success', 'Data orang tua berhasil dihapus.');
    }
}
