<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class CaseStudy extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'case_studies';
    protected $primaryKey = 'id';

    protected $fillable = [
        'student_id',
        'case_title',
        'case_description',
        'case_type',
        'action_taken',
        'recommendation',
        'status',
        'handled_by',
        'case_date',
        'reporter_teacher',
        'subject_name',
        'time_of_occurrence',
        'points_sanction',
        'points_applied',
        'evidence_file',
    ];

    protected $casts = [
        'case_date' => 'date',
    ];

    public function student()
    {
        return $this->belongsTo(Student::class, 'student_id');
    }

    public function handler()
    {
        return $this->belongsTo(User::class, 'handled_by');
    }
}
