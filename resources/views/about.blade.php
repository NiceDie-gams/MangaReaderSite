@extends('layouts.app')

@section('content')
<div class="mx-auto max-w-4xl rounded bg-white p-6 shadow dark:bg-gray-800">
    <h1 class="mb-6 text-3xl font-bold dark:text-white">О нас</h1>

    {{-- Отображение текущего текста --}}
    <div class="mb-8">
        <h2 class="mb-3 text-xl font-semibold text-blue-600 dark:text-blue-400">О компании</h2>
        <div class="prose prose-slate dark:prose-invert max-w-none text-slate-700 dark:text-slate-300">
            {!! $aboutText !!}   {{-- чтобы выводить HTML, сохранённый из TinyMCE --}}
        </div>
    </div>

    {{-- Форма редактирования (только для админа) --}}
    @auth
        @if(auth()->user()->isAdmin())
        <div class="mt-8 border-t pt-6">
            <h3 class="text-lg font-semibold dark:text-white">Редактировать текст</h3>
            <form method="POST" action="{{ route('admin.about.update') }}">
                @csrf
                @method('PUT')
                <textarea id="editor" name="about_text">{{ old('about_text', $aboutText) }}</textarea>
                <button type="submit" class="mt-3 rounded bg-blue-600 px-4 py-2 text-white">Сохранить</button>
            </form>
        </div>
        @endif
    @endauth
</div>

@endsection