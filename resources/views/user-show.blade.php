@extends('layouts.app')

@section('content')
<div class="mx-auto max-w-2xl">
    <div class="mb-8 flex items-center justify-between">
        <h1 class="text-2xl font-semibold">Профиль: {{ $user->name }}</h1>


        <button id="theme-toggle" class="rounded bg-slate-200 p-2 text-slate-700 transition hover:bg-slate-300 dark:bg-slate-700 dark:text-slate-200 dark:hover:bg-slate-600">
            <span class="sr-only">Сменить тему</span>
            <svg id="theme-icon-sun" class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 3v1m0 16v1m9-9h-1M4 12H3m15.364 6.364l-.707-.707M6.343 6.343l-.707-.707m12.728 0l-.707.707M6.343 17.657l-.707.707M16 12a4 4 0 11-8 0 4 4 0 018 0z" />
            </svg>
            <svg id="theme-icon-moon" class="hidden h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20.354 15.354A9 9 0 018.646 3.646 9.003 9.003 0 0012 21a9.003 9.003 0 008.354-5.646z" />
            </svg>
        </button>
    </div>

    <div class="rounded-lg bg-white p-6 shadow dark:bg-slate-800">
        <h2 class="mb-4 text-xl font-semibold">Редактировать профиль</h2>
        <form action="{{ route('user.update', $user) }}" method="POST" class="space-y-4">
            @csrf
            @method('PUT')
            <div>
                <label for="name" class="mb-1 block text-sm font-medium text-slate-700 dark:text-slate-300">Ваше имя</label>
                <input type="text" name="name" id="name" value="{{ old('name', $user->name) }}"
                    class="w-full rounded-md border border-slate-300 bg-white px-3 py-2 focus:border-blue-500 focus:outline-none dark:border-slate-600 dark:bg-gray-900 dark:text-white dark:focus:border-blue-400">
                @error('name')
                    <p class="mt-1 text-sm text-rose-600">{{ $message }}</p>
                @enderror
            </div>
            <button type="submit" class="rounded-md bg-blue-600 px-4 py-2 text-white hover:bg-blue-700">Сохранить изменения</button>
        </form>
    </div>

    <div class="mt-10">
        <h2 class="mb-4 text-2xl font-semibold">Избранное</h2>
        @if($user->favoriteTitles->count())
            <div class="grid grid-cols-2 gap-4 md:grid-cols-4">
                @foreach($user->favoriteTitles as $title)
                    <div class="overflow-hidden rounded bg-white shadow dark:bg-slate-800">
                        <a href="{{ route('titles.show', $title) }}">
                            <img src="{{ $title->cover_image }}" class="h-52 w-full object-cover" alt="{{ $title->title }}">
                            <div class="p-2 text-sm dark:text-white">{{ $title->title }}</div>
                        </a>
                        <button class="favorite-remove w-full bg-rose-600 px-3 py-2 text-sm text-white hover:bg-rose-700"
                                data-title-id="{{ $title->id }}">
                            Удалить
                        </button>
                    </div>
                @endforeach
            </div>
        @else
            <p class="text-slate-500 dark:text-slate-400">У вас пока нет избранных тайтлов.</p>
        @endif
    </div>
</div>
@endsection
