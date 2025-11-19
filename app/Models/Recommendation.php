<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Recommendation extends Model
{
    protected $fillable = [
        'name',
        'phone',
        'hair_length',
        'hair_type',
        'hair_condition',
        'face_shape',
        'recommended_models',
    ];
}
