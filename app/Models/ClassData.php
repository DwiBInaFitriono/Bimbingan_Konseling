<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class ClassData extends Model
{
    use HasFactory, SoftDeletes;
    
    protected $table = 'classdatas';
    protected $primaryKey = 'id';
    
    protected $fillable = [
        'grade',
        'school_class_name',
        'school_class_major',
    ];

    public function student()
    {
        return $this->hasMany(Student::class, 'class_id');
    }
}
