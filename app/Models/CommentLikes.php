<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class CommentLikes extends Model
{
    protected $fillable = ['comment_id', 'user_id'];

    protected $table = 'comments_likes';

    public function commentBelong(): BelongsTo
    {
        return $this->belongsTo(Comment::class, 'comment_id'); 
    }

    public function userBelong(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id');
    }
}
