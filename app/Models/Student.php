<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Student extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'students';
    protected $primaryKey = 'id';

    protected $fillable = [
        'user_id',
        'full_name',
        'nis',
        'class_id',
        'parent_id',
        'gender',
        'date_of_birth',
        'address',
        'phone_number',
        'total_points',
        'status',
    ];

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function class()
    {
        return $this->belongsTo(ClassData::class, 'class_id');
    }

    public function parent()
    {
        return $this->belongsTo(Parents::class, 'parent_id');
    }

    public function casestudy()
    {
        return $this->hasMany(CaseStudy::class, 'student_id');
    }

    public function achievement()
    {
        return $this->hasMany(Achievement::class, 'student_id');
    }

    public function pointDatas()
    {
        return $this->hasMany(PointData::class, 'student_id');
    }

    public function counselingSessions()
    {
        return $this->hasMany(CounselingSession::class, 'student_id');
    }

    /**
     * Hitung ulang poin dan update status otomatis berdasarkan kategori poin
     */
    public function recalculateStatus()
    {
        $total = $this->pointDatas()->sum('point_number');
        $this->total_points = $total;

        // Cari kategori yang sesuai di DataPointCategory
        $category = DataPointCategory::where('category_score_min', '<=', $total)
            ->where('category_score_max', '>=', $total)
            ->first();

        if ($category) {
            $catName = strtolower($category->category_of_violation);
            if (str_contains($catName, 'berat') || str_contains($catName, 'bahaya') || $total >= 75) {
                $this->status = 'bahaya';
            } elseif (str_contains($catName, 'sedang') || str_contains($catName, 'peringatan') || $total >= 30) {
                $this->status = 'peringatan';
            } else {
                $this->status = 'aman';
            }
        } else {
            if ($total >= 75) {
                $this->status = 'bahaya';
            } elseif ($total >= 30) {
                $this->status = 'peringatan';
            } else {
                $this->status = 'aman';
            }
        }

        $this->save();
    }
}
