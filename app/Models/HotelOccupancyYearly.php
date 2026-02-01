<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class HotelOccupancyYearly extends Model
{
    protected $table = 'hotel_occupancy_yearly';

    protected $fillable = [
        'year',
        'mktj',
        'tpk',
        'rlmta',
        'rlmtnus',
        'rlmtgab',
        'gpr',
    ];

    protected $casts = [
        'mktj' => 'decimal:2',
        'tpk' => 'decimal:2',
        'rlmta' => 'decimal:2',
        'rlmtnus' => 'decimal:2',
        'rlmtgab' => 'decimal:2',
        'gpr' => 'decimal:2',
    ];
}
