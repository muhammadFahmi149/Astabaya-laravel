<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Publication extends Model
{
    protected $table = 'publications';
    
    protected $fillable = [
        'pub_id',
        'title',
        'image',
        'dl',
        'date',
        'abstract',
        'size',
    ];

    protected $casts = [
        'date' => 'date',
    ];
}
