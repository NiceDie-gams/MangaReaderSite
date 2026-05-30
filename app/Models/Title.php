<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Str;

class Title extends Model
{
    protected $fillable = ['title', 'slug', 'description', 'cover_image'];

    protected static function booted(): void
    {
        static::creating(function (Title $title): void {
            if (! $title->slug) {
                $title->slug = Str::slug($title->title).'-'.Str::random(6);
            }
        });
    }

    public function getRouteKeyName(): string
    {
        return 'slug';
    }

    public function chapters(): HasMany
    {
        return $this->hasMany(Chapter::class)->orderBy('chapter_number');
    }

    public function tags(): BelongsToMany
    {
        return $this->belongsToMany(Tag::class, 'title_tag');
    }

    public function favorites(): HasMany
    {
        return $this->hasMany(Favorite::class);
    }

    public function comments(): HasMany
    {
        return $this->hasMany(Comment::class)->latest();
    }
}
