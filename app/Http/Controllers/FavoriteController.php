<?php

namespace App\Http\Controllers;

use App\Models\Title;
use Illuminate\Http\JsonResponse;
use App\Repositories\FavoriteRepository;

class FavoriteController extends Controller
{

    private $factoryRepository;

    public function __construct(FavoriteRepository $_factoryRepository)
    {
        $this->factoryRepository = $_factoryRepository;
    }
    
    public function store(Title $title): JsonResponse
    {
        auth()->user()->favoriteTitles()->syncWithoutDetaching([$title->id]);

        return response()->json(['favorited' => true]);
    }

    public function destroy(Title $title): JsonResponse
    {
        auth()->user()->favoriteTitles()->detach($title->id);

        return response()->json(['favorited' => false]);
    }
}
