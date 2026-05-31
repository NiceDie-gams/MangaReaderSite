<?php

use App\Http\Controllers\ChapterController;
use App\Http\Controllers\CommentController;
use App\Http\Controllers\TitleController;
use Illuminate\Support\Facades\Route;
use App\Models\Title;
use App\Models\Chapter;
use Illuminate\Http\Request;

Route::get('/titles', [TitleController::class, 'index']);
Route::get('/chapter/{chapter}/page/{page}', [ChapterController::class, 'page']);
Route::get('/title/{title:id}/comments', [CommentController::class, 'index']);

Route::get('/titles/search', function (Request $request) {
    $q = $request->query('q');
    if (strlen($q) < 2) {
        return response()->json([]);
    }
    $titles = Title::where('title', 'ilike', "%{$q}%")
        ->limit(10)
        ->get(['id', 'title']);
    return response()->json($titles);
});

Route::get('/titles/{id}/recent-chapters', function ($id) {
    $title = Title::findOrFail($id);
    $chapters = Chapter::where('title_id', $title->id)
        ->orderBy('chapter_number', 'desc')
        ->limit(3)
        ->get(['chapter_number']);
    return response()->json($chapters);
});
