@extends('layouts.app')

@section('content')
<div class="space-y-6">
    <div class="flex items-center justify-between">
        <h1 class="text-2xl font-bold">Настройки сайта</h1>
        <a href="{{ route('admin.dashboard') }}" class="text-sm text-blue-600 hover:underline">Назад в админ-панель</a>
    </div>

    @if(session('success'))
        <div class="rounded border border-emerald-300 bg-emerald-50 px-4 py-2 text-emerald-700 dark:bg-emerald-950 dark:text-emerald-300">
            {{ session('success') }}
        </div>
    @endif

    <div class="rounded bg-white p-6 shadow dark:bg-gray-800">
        <form method="POST" action="{{ route('admin.settings.update') }}">
            @csrf
            @method('PUT')

            {{-- Комментарии --}}
            <div class="mb-6 flex items-center justify-between border-b pb-4 dark:border-gray-700">
                <div>
                    <h3 class="text-lg font-semibold dark:text-white">Комментарии</h3>
                    <p class="text-sm text-slate-500 dark:text-slate-400">Разрешить пользователям оставлять комментарии под тайтлами.</p>
                </div>
                <label class="relative inline-flex cursor-pointer items-center">
                    <input type="checkbox" name="comments_enabled" value="1" class="peer sr-only" {{ $settings['comments_enabled'] ?? true ? 'checked' : '' }}>
                    <div class="peer h-6 w-11 rounded-full bg-gray-200 after:absolute after:start-[2px] after:top-[2px] after:h-5 after:w-5 after:rounded-full after:border after:border-gray-300 after:bg-white after:transition-all after:content-[''] peer-checked:bg-blue-600 peer-checked:after:translate-x-full peer-checked:after:border-white peer-focus:outline-none dark:border-gray-600 dark:bg-gray-700"></div>
                </label>
            </div>

            {{-- Жалобы --}}
            <div class="mb-6 flex items-center justify-between border-b pb-4 dark:border-gray-700">
                <div>
                    <h3 class="text-lg font-semibold dark:text-white">Жалобы</h3>
                    <p class="text-sm text-slate-500 dark:text-slate-400">Разрешить пользователям отправлять жалобы.</p>
                </div>
                <label class="relative inline-flex cursor-pointer items-center">
                    <input type="checkbox" name="reports_enabled" value="1" class="peer sr-only" {{ $settings['reports_enabled'] ?? true ? 'checked' : '' }}>
                    <div class="peer h-6 w-11 rounded-full bg-gray-200 after:absolute after:start-[2px] after:top-[2px] after:h-5 after:w-5 after:rounded-full after:border after:border-gray-300 after:bg-white after:transition-all after:content-[''] peer-checked:bg-blue-600 peer-checked:after:translate-x-full peer-checked:after:border-white peer-focus:outline-none dark:border-gray-600 dark:bg-gray-700"></div>
                </label>
            </div>

            {{-- Регистрация --}}
            <div class="mb-6 flex items-center justify-between border-b pb-4 dark:border-gray-700">
                <div>
                    <h3 class="text-lg font-semibold dark:text-white">Регистрация новых пользователей</h3>
                    <p class="text-sm text-slate-500 dark:text-slate-400">Разрешить новые регистрации на сайте.</p>
                </div>
                <label class="relative inline-flex cursor-pointer items-center">
                    <input type="checkbox" name="registration_enabled" value="1" class="peer sr-only" {{ $settings['registration_enabled'] ?? true ? 'checked' : '' }}>
                    <div class="peer h-6 w-11 rounded-full bg-gray-200 after:absolute after:start-[2px] after:top-[2px] after:h-5 after:w-5 after:rounded-full after:border after:border-gray-300 after:bg-white after:transition-all after:content-[''] peer-checked:bg-blue-600 peer-checked:after:translate-x-full peer-checked:after:border-white peer-focus:outline-none dark:border-gray-600 dark:bg-gray-700"></div>
                </label>
            </div>

            <div class="flex justify-end">
                <button type="submit" class="rounded bg-blue-600 px-4 py-2 text-white hover:bg-blue-700">Сохранить настройки</button>
            </div>
        </form>
    </div>
</div>
@endsection
