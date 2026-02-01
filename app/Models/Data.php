<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Data extends Model
{
    protected $fillable = [
        'user_id',
        'data_name',
        'data_description',
        'data_image',
        'data_view_count',
        'data_created_at',
    ];

    protected $casts = [
        'data_view_count' => 'integer',
        'data_created_at' => 'datetime',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
