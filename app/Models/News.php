<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class News extends Model
{
    protected $primaryKey = 'news_id';
    public $incrementing = true;

    protected $fillable = [
        'title',
        'content',
        'category_id',
        'category_name',
        'release_date',
        'picture_url',
    ];

    protected $casts = [
        'release_date' => 'date',
    ];
}
