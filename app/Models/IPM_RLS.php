<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class IPM_RLS extends Model
{
    protected $table = 'i_p_m__r_l_s';

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

