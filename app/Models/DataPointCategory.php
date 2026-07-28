<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class DataPointCategory extends Model
{
    use HasFactory, SoftDeletes;
    protected $table = 'data_point_categories';
    protected $primarykey = 'id';
    protected $fillable = [
        'id',
        'category_of_violation',
        'category_score_min',
        'category_score_max',
        'follow_up'



    ];
}
