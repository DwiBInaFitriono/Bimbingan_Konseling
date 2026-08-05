<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class PointData extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'point_datas';
    protected $primaryKey = 'id';

    protected $fillable = [
        'student_id',
        'violation',
        'point_number',
        'violation_date',
        'description',
        'recorded_by',
    ];

    protected $casts = [
        'violation_date' => 'date',
    ];

    public function student()
    {
        return $this->belongsTo(Student::class, 'student_id');
    }

    public function recorder()
    {
        return $this->belongsTo(User::class, 'recorded_by');
    }
}
