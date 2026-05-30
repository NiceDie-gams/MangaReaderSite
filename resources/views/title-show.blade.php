@extends('layouts.app')

@section('content')
<div class="space-y-6">
    <div class="grid gap-6 rounded bg-white p-4 shadow md:grid-cols-[280px_1fr]">
        <img src="{{ $title->cover_image }}" class="w-full rounded object-cover" alt="{{ $title->title }}">
        <div>
            <h1 class="text-2xl font-bold">{{ $title->title }}</h1>
            <div class="my-3 flex flex-wrap gap-2">
                @foreach($title->tags as $tag)
                    <span class="rounded bg-slate-200 px-2 py-1 text-xs">{{ $tag->name }}</span>
                @endforeach
            </div>
            <p class="text-slate-700">{{ $title->description }}</p>
            @auth
                <button id="favorite-btn" data-title-id="{{ $title->id }}" data-favorited="{{ $isFavorite ? '1' : '0' }}" class="mt-4 rounded bg-amber-500 px-4 py-2 text-white">
                    {{ $isFavorite ? 'В избранном' : 'В избранное' }}
                </button>
            @endauth
        </div>
    </div>

    <div class="rounded bg-white p-4 shadow">
        <h2 class="mb-3 text-lg font-semibold">Главы</h2>
        <div class="space-y-2">
            @foreach($title->chapters as $chapter)
                <a class="block rounded border p-2 hover:bg-slate-50" href="{{ route('chapters.show', [$title, $chapter]) }}">
                    Глава {{ $chapter->chapter_number }} {{ $chapter->title ? ' - '.$chapter->title : '' }}
                </a>
            @endforeach
        </div>
    </div>

    <div class="rounded bg-white p-4 shadow">
        <h2 class="mb-3 text-lg font-semibold">Комментарии</h2>
        <div id="comments-list" data-title-id="{{ $title->id }}" data-authenticated="{{ auth()->check() ? '1' : '0' }}" class="space-y-2"></div>
        @auth
            <form id="comment-form" class="mt-4 space-y-2">
                <textarea id="comment-content" class="w-full rounded border px-3 py-2" rows="3" placeholder="Напишите комментарий"></textarea>
                <button class="rounded bg-blue-600 px-4 py-2 text-white">Отправить</button>
            </form>
        @endauth
    </div>
</div>
@endsection
