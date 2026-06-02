<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class TagFavoriteStatistic extends Model
{
    protected $table = 'tag_favorites_statistics';

    protected $fillable = ['tag_id', 'favorites_count', 'last_calculated_at'];

    protected $casts = [
        'last_calculated_at' => 'datetime',
    ];

    public function tag(): BelongsTo
    {
        return $this->belongsTo(Tag::class);
    }
}
