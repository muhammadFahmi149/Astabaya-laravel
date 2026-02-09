<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\MorphTo;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Bookmark extends Model
{
    /**
     * Indicates if the model should use updated_at timestamp.
     * Since we only use created_at, disable updated_at.
     */
    const UPDATED_AT = null;

    protected $fillable = [
        'user_id',
        'bookmarkable_type',
        'bookmarkable_id',
    ];

    protected $casts = [
        'created_at' => 'datetime',
    ];

    /**
     * Get the user that owns the bookmark.
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Get the bookmarkable model (polymorphic relation).
     * Equivalent to Django's GenericForeignKey.
     */
    public function bookmarkable(): MorphTo
    {
        return $this->morphTo();
    }
}
