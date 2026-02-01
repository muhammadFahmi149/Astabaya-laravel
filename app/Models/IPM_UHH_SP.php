<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class IPM_UHH_SP extends Model
{
    protected $table = 'i_p_m__u_h_h__s_ps';

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

