<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class HumanDevelopmentIndex extends Model
{
    protected $fillable = [
        'location_name',
        'location_type',
        'year',
        'ipm_value',
    ];

    protected $casts = [
        'ipm_value' => 'decimal:2',
    ];
}
