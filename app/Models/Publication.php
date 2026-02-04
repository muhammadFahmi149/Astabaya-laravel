<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\MorphMany;

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

    /**
     * Get all of the bookmarks for the publication.
     */
    public function bookmarks(): MorphMany
    {
        return $this->morphMany(Bookmark::class, 'bookmarkable');
    }
}
