<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Comment extends Model
{
    protected $fillable = ['user_id', 'title_id', 'content'];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function title(): BelongsTo
    {
        return $this->belongsTo(Title::class);
    }

    public function commentLikes(): HasMany
    {
        return $this->hasMany(CommentLikes::class, 'comment_id');
    }
}
