<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Chapter extends Model
{
    protected $fillable = ['title_id', 'chapter_number', 'title'];

    public function titleBelong(): BelongsTo
    {
        return $this->belongsTo(Title::class, 'title_id'); 
    }

    public function pages(): HasMany
    {
        return $this->hasMany(ChapterPage::class)->orderBy('page_number');
    }
}
