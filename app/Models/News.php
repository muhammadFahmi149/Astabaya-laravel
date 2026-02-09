<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\MorphMany;

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

    /**
     * Get all of the bookmarks for the news.
     */
    public function bookmarks(): MorphMany
    {
        return $this->morphMany(Bookmark::class, 'bookmarkable');
    }
}
