@extends('layouts.app')

@section('content')
<h1 class="mb-4 text-2xl font-semibold">Избранное: {{ $user->name }}</h1>
<div class="grid grid-cols-2 gap-4 md:grid-cols-4">
    @foreach($user->favoriteTitles as $title)
        <div class="overflow-hidden rounded bg-white shadow">
            <a href="{{ route('titles.show', $title) }}">
                <img src="{{ $title->cover_image }}" class="h-52 w-full object-cover" alt="{{ $title->title }}">
                <div class="p-2 text-sm">{{ $title->title }}</div>
            </a>
            <button class="favorite-remove w-full bg-rose-600 px-3 py-2 text-sm text-white" data-title-id="{{ $title->id }}">Удалить</button>
        </div>
    @endforeach
</div>
@endsection
