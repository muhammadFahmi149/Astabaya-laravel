<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class IPM_IndeksHidupLayak extends Model
{
    protected $table = 'i_p_m_indeks_hidup_layaks';

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

