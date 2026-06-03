<?php

namespace App\Repositories;

use App\Models\Favorite;
use App\Models\Title;

class FavoriteRepository {
    
    public function addFavorite(Title $title){
        auth()->user()->favoriteTitles()->syncWithoutDetaching([$title->id]);
    }

}