<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasOne;

class Tag extends Model
{
    protected $fillable = ['name'];

    public function titles(): BelongsToMany
    {
        return $this->belongsToMany(Title::class, 'title_tag');
    }
    public function statistic(): HasOne
    {
        return $this->hasOne(TagFavoriteStatistic::class);
    }
}
