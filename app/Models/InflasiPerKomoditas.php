<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class InflasiPerKomoditas extends Model
{
    protected $table = 'inflasi_per_komoditas';

    protected $fillable = [
        'commodity_code',
        'commodity_name',
        'flag',
        'year',
        'month',
        'value',
    ];

    protected $casts = [
        'value' => 'decimal:2',
    ];
}

