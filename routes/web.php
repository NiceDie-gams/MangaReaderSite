<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\ChapterController;
use App\Http\Controllers\CommentController;
use App\Http\Controllers\FavoriteController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\ReportController;
use App\Http\Controllers\TitleController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\TranslatorController;
use Illuminate\Support\Facades\Route;

Route::get('/', [TitleController::class, 'index'])->name('home');
Route::get('/title/{title:slug}', [TitleController::class, 'show'])->name('titles.show');
Route::get('/title/{title:slug}/chapter/{chapter}', [ChapterController::class, 'show'])->name('chapters.show');

Route::middleware('guest')->group(function () {
    Route::get('/auth', [AuthController::class, 'showLogin'])->name('auth.login');
    Route::post('/auth/login', [AuthController::class, 'login'])->name('auth.login.submit');
    Route::get('/register', [AuthController::class, 'showRegister'])->name('auth.register');
    Route::post('/register', [AuthController::class, 'register'])->name('auth.register.submit');
});

Route::middleware('auth')->group(function () {
    Route::post('/auth/logout', [AuthController::class, 'logout'])->name('auth.logout');
    Route::post('/favorites/{title:id}', [FavoriteController::class, 'store'])->name('favorites.store');
    Route::delete('/favorites/{title:id}', [FavoriteController::class, 'destroy'])->name('favorites.destroy');
    Route::post('/comments', [CommentController::class, 'store'])->name('comments.store');
    Route::post('/comments/{comment}/like', [CommentController::class, 'like'])->name('comments.like');
    Route::delete('/comments/{comment}/like', [CommentController::class, 'unlike'])->name('comments.unlike');
    Route::get('/user/{user}', [UserController::class, 'show'])->name('users.show');
    Route::post('/reports', [ReportController::class, 'makeReport'])->name('reports.store');
    Route::get('/reports', [ReportController::class, 'showReports'])->name('reports.index');
    Route::patch('/reports/{report}/solve', [ReportController::class, 'solveReport'])->name('reports.solve');
    Route::put('user/{user}/update', [UserController::class, 'updateUserInfo'])->name('user.update');
});

Route::middleware(['auth', 'role:translator,admin'])->prefix('translator')->group(function () {
    Route::get('/dashboard', [TranslatorController::class, 'dashboard'])->name('translator.dashboard');
    Route::get('/chapters/create', [TranslatorController::class, 'create'])->name('translator.chapters.create');
    Route::post('/chapters', [TranslatorController::class, 'store'])->name('translator.chapters.store');
    Route::get('/chapters', [TranslatorController::class, 'index'])->name('translator.chapters.index');
    Route::get('/chapters/{chapter}/edit', [TranslatorController::class, 'edit'])->name('translator.chapters.edit');
    Route::put('/chapters/{chapter}', [TranslatorController::class, 'update'])->name('translator.chapters.update');
});

Route::middleware(['auth', 'role:admin'])->prefix('admin')->group(function () {
    Route::get('/dashboard', [AdminController::class, 'dashboard'])->name('admin.dashboard');
    Route::post('/titles', [AdminController::class, 'storeTitle'])->name('admin.titles.store');
    Route::get('/chapters', [AdminController::class, 'chapters'])->name('admin.chapters.index');
    Route::patch('/chapters/{chapter}/approve', [AdminController::class, 'approveChapter'])->name('admin.chapters.approve');
    Route::patch('/chapters/{chapter}/reject', [AdminController::class, 'rejectChapter'])->name('admin.chapters.reject');
    Route::get('/reports', [AdminController::class, 'reports'])->name('admin.reports.index');
    Route::patch('/reports/{report}/solve', [AdminController::class, 'solveReport'])->name('admin.reports.solve');
    Route::get('/users', [AdminController::class, 'users'])->name('admin.users.index');
    
    Route::get('/titles-list', [AdminController::class, 'titles'])->name('admin.titles.index');
    Route::delete('/titles-list/{title}', [AdminController::class, 'deleteTitle'])->name('admin.titles.delete');
    Route::patch('/titles-list/update', [AdminController::class, 'updateTitle'])->name('admin.titles.update');

    Route::get('/statistics', [AdminController::class, 'statistics'])->name('statistics');
    Route::post('/statistics/update', [AdminController::class, 'updateStatistics'])->name('statistics.update');
});
