<?php

namespace App\Http\Controllers;

use App\Models\Title;
use Illuminate\Http\JsonResponse;

class FavoriteController extends Controller
{
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
