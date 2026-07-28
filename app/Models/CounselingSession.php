<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class CounselingSession extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'counseling_sessions';

    protected $fillable = [
        'student_id',
        'additional_student_ids',
        'case_study_id',
        'guru_bk_id',
        'requested_date',
        'requested_time',
        'topic',
        'description',
        'type',
        'status',
        'notes',
        'student_feedback',
        'approved_at',
        'completed_at',
    ];

    protected $casts = [
        'requested_date' => 'date',
        'approved_at' => 'datetime',
        'completed_at' => 'datetime',
        'additional_student_ids' => 'array',
    ];

    public function student()
    {
        return $this->belongsTo(Student::class, 'student_id');
    }

    public function additionalStudents()
    {
        if (empty($this->additional_student_ids) || !is_array($this->additional_student_ids)) {
            return collect();
        }
        return Student::whereIn('id', $this->additional_student_ids)->with('class')->get();
    }

    public function caseStudy()
    {
        return $this->belongsTo(CaseStudy::class, 'case_study_id');
    }

    public function guruBk()
    {
        return $this->belongsTo(User::class, 'guru_bk_id');
    }
}
