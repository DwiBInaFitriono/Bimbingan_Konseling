<?php

namespace App\Http\Controllers\Guru_BK;

use App\Http\Controllers\Controller;

use Illuminate\Http\Request;
use App\Models\DataPointCategory;

class PointCategory extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function indexPointCategory()
    {
        $data = DataPointCategory::all();
        return view('Guru_BK.PointCategory.kategori', ['datakategori' => $data]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function createPointCategory()
    {
        return view('Guru_BK.PointCategory.tambah');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function storePointCategory(Request $request)
    {
        $request->validate([
            'category_of_violation' => 'required|string',
            'category_score_min' => 'required|integer',
            'category_score_max' => 'required|integer',
            'follow_up' => 'required|string',
        ]);

        DataPointCategory::create([
            'category_of_violation' => $request->category_of_violation,
            'category_score_min' => $request->category_score_min,
            'category_score_max' => $request->category_score_max,
            'follow_up' => $request->follow_up,
        ]);

        return redirect('kategori');
    }

    /**
     * Display the specified resource.
     */
    public function showPointCategory($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function editPointCategory($id)
    {
        $id = (int) $id;
        $data = DataPointCategory::findOrFail($id);
        return view('Guru_BK.PointCategory.edit', compact('data'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function updatePointCategory(Request $request, $id)
    {
        $id = (int) $id;
        $request->validate([
            'category_of_violation' => 'required|string',
            'category_score_min' => 'required|integer',
            'category_score_max' => 'required|integer',
            'follow_up' => 'required|string',
        ]);

        $data = DataPointCategory::findOrFail($id);
        $data->category_of_violation = $request->category_of_violation;
        $data->category_score_min = $request->category_score_min;
        $data->category_score_max = $request->category_score_max;
        $data->follow_up = $request->follow_up;

        $data->save();

        return redirect()->route('kategori.tampil');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroyPointCategory($id)
    {
        $id = (int) $id;
        $data = DataPointCategory::findOrFail($id);

        $data->delete();

        return redirect()->back();
    }
}
