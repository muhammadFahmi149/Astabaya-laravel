<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Infographic extends Model
{
    protected $fillable = [
        'bps_id',
        'title',
        'image',
        'dl',
    ];

    protected $casts = [
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];
}
