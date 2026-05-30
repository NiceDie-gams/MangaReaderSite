<?php

use App\Http\Controllers\ChapterController;
use App\Http\Controllers\CommentController;
use App\Http\Controllers\TitleController;
use Illuminate\Support\Facades\Route;

Route::get('/titles', [TitleController::class, 'index']);
Route::get('/chapter/{chapter}/page/{page}', [ChapterController::class, 'page']);
Route::get('/title/{title:id}/comments', [CommentController::class, 'index']);

