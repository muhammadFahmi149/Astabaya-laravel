<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Kependudukan extends Model
{
    protected $table = 'kependudukan';

    protected $fillable = [
        'age_group',
        'year',
        'gender',
        'population',
    ];

    protected $casts = [
        'population' => 'integer',
    ];
}
