<?php

namespace App\Http\Controllers\Guru_BK;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\DataPointCategory;

class PointCategory extends Controller
{
    public function indexPointCategory()
    {
        $categoryList = DataPointCategory::all();
        return view('Guru_BK.PointCategory.kategori', ['datakategori' => $categoryList]);
    }

    public function createPointCategory()
    {
        return view('Guru_BK.PointCategory.tambah');
    }

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

        return redirect('kategori')->with('success', 'Kategori poin berhasil ditambahkan.');
    }

    public function editPointCategory($pointCategoryId)
    {
        $parsedCategoryId = (int) $pointCategoryId;
        $pointCategory = DataPointCategory::findOrFail($parsedCategoryId);
        return view('Guru_BK.PointCategory.edit', ['data' => $pointCategory]);
    }

    public function updatePointCategory(Request $request, $pointCategoryId)
    {
        $parsedCategoryId = (int) $pointCategoryId;
        $request->validate([
            'category_of_violation' => 'required|string',
            'category_score_min' => 'required|integer',
            'category_score_max' => 'required|integer',
            'follow_up' => 'required|string',
        ]);

        $pointCategory = DataPointCategory::findOrFail($parsedCategoryId);
        $pointCategory->category_of_violation = $request->category_of_violation;
        $pointCategory->category_score_min = $request->category_score_min;
        $pointCategory->category_score_max = $request->category_score_max;
        $pointCategory->follow_up = $request->follow_up;
        $pointCategory->save();

        return redirect()->route('kategori.tampil')->with('success', 'Kategori poin berhasil diperbarui.');
    }

    public function destroyPointCategory($pointCategoryId)
    {
        $parsedCategoryId = (int) $pointCategoryId;
        $pointCategory = DataPointCategory::findOrFail($parsedCategoryId);
        $pointCategory->delete();

        return redirect()->back()->with('success', 'Kategori poin berhasil dihapus.');
    }
}
