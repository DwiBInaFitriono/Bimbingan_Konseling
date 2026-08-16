<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Parents extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'parents';
    protected $primaryKey = 'id';

    protected $fillable = [
        'parent_full_name',
        'relationship',
        'address',
        'job',
        'phone_number',
        'email',
    ];

    public function student()
    {
        return $this->hasMany(Student::class, 'parent_id');
    }
}
