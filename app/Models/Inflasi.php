<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Inflasi extends Model
{
    protected $table = 'inflasi';

    protected $fillable = [
        'year',
        'month',
        'bulanan',
        'kumulatif',
        'yoy',
    ];

    protected $casts = [
        'bulanan' => 'decimal:2',
        'kumulatif' => 'decimal:2',
        'yoy' => 'decimal:2',
    ];
}

