<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class HairModel extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'image',
        'types', // comma separated types
        'length',
        'face_shapes', // comma separated recommended face shapes
    ];
}