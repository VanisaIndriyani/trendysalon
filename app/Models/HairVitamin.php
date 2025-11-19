<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class HairVitamin extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'hair_type',
    ];
}