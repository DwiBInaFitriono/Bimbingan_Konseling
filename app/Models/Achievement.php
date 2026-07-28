<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Achievement extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'achievements';
    protected $primaryKey = 'id';

    protected $fillable = [
        'student_id',
        'achievement_name',
        'achievement_date',
        'achievement_level',
        'achievement_category',
        'achievement_status',
        'description',
        'recorded_by',
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
