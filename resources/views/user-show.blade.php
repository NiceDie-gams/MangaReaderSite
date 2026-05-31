@extends('layouts.app')

@section('content')
<div>
    <h1 class="mb-4 text-2xl font-semibold">Пользователь: {{$user->name}}</h1>
    <div class="mb-10 mt-10">
        <form action="{{ route('user.update', $user) }}" method="POST">
            @csrf
            <label for="name">Ваше имя:</label>
            <input class= type="text" name="name" value="{{ $user->name }}"/>
            <button type="submit">Сохранить</button>
        </form>
    </div>
</div>
<h1 class="mb-4 text-2xl font-semibold">Избранное:</h1>
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
