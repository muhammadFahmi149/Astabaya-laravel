<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class GiniRatio extends Model
{
    protected $fillable = [
        'location_name',
        'location_type',
        'year',
        'gini_ratio_value',
    ];

    protected $casts = [
        'gini_ratio_value' => 'decimal:3',
    ];
}
