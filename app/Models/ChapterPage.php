<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ChapterPage extends Model
{
    protected $fillable = ['chapter_id', 'page_number', 'image_path'];

    public function chapter(): BelongsTo
    {
        return $this->belongsTo(Chapter::class);
    }
}
