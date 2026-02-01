<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class IPM_IndeksKesehatan extends Model
{
    protected $table = 'i_p_m_indeks_kesehatans';

    protected $fillable = [
        'location_name',
        'location_type',
        'year',
        'value',
    ];

    protected $casts = [
        'value' => 'decimal:2',
    ];
}

