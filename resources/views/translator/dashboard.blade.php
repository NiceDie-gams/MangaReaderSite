@extends('layouts.app')

@section('content')
<div class="rounded bg-white p-6 shadow dark:bg-gray-800">
    <h1 class="mb-6 text-2xl font-bold dark:text-white">Панель переводчика</h1>

    {{-- Блок статистики с нейтральными серыми градиентами --}}
    <div class="mb-8 grid grid-cols-1 gap-4 md:grid-cols-4">
        {{-- Всего глав --}}
        <div class="group relative overflow-hidden rounded-xl bg-gradient-to-br from-gray-100 to-gray-50 p-4 shadow-sm transition-all duration-300 hover:shadow-md dark:from-zinc-800 dark:to-zinc-900">
            <div class="absolute -right-3 -top-3 opacity-10 transition-transform duration-300 group-hover:scale-110">
                <svg class="h-20 w-20" fill="currentColor" viewBox="0 0 24 24">
                    <path d="M4 6h16v2H4V6zm2-4h12v2H6V2zm16 8H2v12h20V10zm-2 10H4v-8h16v8z"/>
                </svg>
            </div>
            <div class="relative z-10">
                <p class="text-sm uppercase tracking-wide text-blue-600 dark:text-blue-400">Всего глав</p>
                <p class="mt-2 text-3xl font-bold text-gray-800 dark:text-white">{{ $stats['total'] }}</p>
            </div>
        </div>

        {{-- На модерации --}}
        <div class="group relative overflow-hidden rounded-xl bg-gradient-to-br from-gray-100 to-gray-50 p-4 shadow-sm transition-all duration-300 hover:shadow-md dark:from-zinc-800 dark:to-zinc-900">
            <div class="absolute -right-3 -top-3 opacity-10 transition-transform duration-300 group-hover:scale-110">
                <svg class="h-20 w-20" fill="currentColor" viewBox="0 0 24 24">
                    <path d="M12 2C6.48 2 2 6.48 2 12s4.48 10 10 10 10-4.48 10-10S17.52 2 12 2zm1 15h-2v-2h2v2zm0-4h-2V7h2v6z"/>
                </svg>
            </div>
            <div class="relative z-10">
                <p class="text-sm uppercase tracking-wide text-amber-600 dark:text-amber-400">На модерации</p>
                <p class="mt-2 text-3xl font-bold text-gray-800 dark:text-white">{{ $stats['pending'] }}</p>
            </div>
        </div>

        {{-- Одобрено --}}
        <div class="group relative overflow-hidden rounded-xl bg-gradient-to-br from-gray-100 to-gray-50 p-4 shadow-sm transition-all duration-300 hover:shadow-md dark:from-zinc-800 dark:to-zinc-900">
            <div class="absolute -right-3 -top-3 opacity-10 transition-transform duration-300 group-hover:scale-110">
                <svg class="h-20 w-20" fill="currentColor" viewBox="0 0 24 24">
                    <path d="M9 16.17L4.83 12l-1.42 1.41L9 19 21 7l-1.41-1.41L9 16.17z"/>
                </svg>
            </div>
            <div class="relative z-10">
                <p class="text-sm uppercase tracking-wide text-emerald-600 dark:text-emerald-400">Одобрено</p>
                <p class="mt-2 text-3xl font-bold text-gray-800 dark:text-white">{{ $stats['approved'] }}</p>
            </div>
        </div>

        {{-- Отклонено --}}
        <div class="group relative overflow-hidden rounded-xl bg-gradient-to-br from-gray-100 to-gray-50 p-4 shadow-sm transition-all duration-300 hover:shadow-md dark:from-zinc-800 dark:to-zinc-900">
            <div class="absolute -right-3 -top-3 opacity-10 transition-transform duration-300 group-hover:scale-110">
                <svg class="h-20 w-20" fill="currentColor" viewBox="0 0 24 24">
                    <path d="M19 6.41L17.59 5 12 10.59 6.41 5 5 6.41 10.59 12 5 17.59 6.41 19 12 13.41 17.59 19 19 17.59 13.41 12 19 6.41z"/>
                </svg>
            </div>
            <div class="relative z-10">
                <p class="text-sm uppercase tracking-wide text-rose-600 dark:text-rose-400">Отклонено</p>
                <p class="mt-2 text-3xl font-bold text-gray-800 dark:text-white">{{ $stats['rejected'] }}</p>
            </div>
        </div>
    </div>

    {{-- Кнопка добавления новой главы (без изменений) --}}
    <div class="mb-6 text-center">
        <a href="{{ route('translator.chapters.create') }}" class="inline-block rounded-md bg-blue-600 px-6 py-2 text-white transition hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 dark:bg-orange-600 dark:hover:bg-orange-700">
            + Добавить новую главу
        </a>
    </div>

    {{-- Список последних глав (без изменений) --}}
    <div class="mt-8">
        <div class="mb-4 flex items-center justify-between">
            <h2 class="text-xl font-semibold dark:text-white">Последние загруженные главы</h2>
            <a href="{{ route('translator.chapters.index') }}" class="text-sm text-blue-600 hover:underline dark:text-blue-400">Все главы →</a>
        </div>

        @if($recentChapters->isEmpty())
            <p class="text-gray-500 dark:text-gray-400">Вы ещё не загрузили ни одной главы.</p>
        @else
            <div class="overflow-x-auto">
                <table class="min-w-full border text-sm dark:border-gray-700">
                    <thead class="bg-slate-100 dark:bg-gray-700">
                    <tr>
                        <th class="px-4 py-2 text-left dark:text-white">Тайтл</th>
                        <th class="px-4 py-2 text-left dark:text-white">Глава</th>
                        <th class="px-4 py-2 text-left dark:text-white">Статус</th>
                        <th class="px-4 py-2 text-left dark:text-white">Дата загрузки</th>
                        <th class="px-4 py-2 dark:text-white">Действие</th>
                    </tr>
                    </thead>
                    <tbody>
                    @foreach($recentChapters as $chapter)
                        <tr class="border-t dark:border-gray-700">
                            <td class="px-4 py-2 dark:text-gray-300">{{ $chapter->titleBelong->title }}</td>
                            <td class="px-4 py-2 dark:text-gray-300">{{ $chapter->chapter_number }}</td>
                            <td class="px-4 py-2">
                                @if($chapter->isPending())
                                    <span class="inline-block rounded-full bg-yellow-100 px-2 py-0.5 text-xs font-medium text-yellow-800 dark:bg-yellow-900 dark:text-yellow-200">На модерации</span>
                                @elseif($chapter->isApproved())
                                    <span class="inline-block rounded-full bg-green-100 px-2 py-0.5 text-xs font-medium text-green-800 dark:bg-green-900 dark:text-green-200">Одобрено</span>
                                @elseif($chapter->isRejected())
                                    <span class="inline-block rounded-full bg-red-100 px-2 py-0.5 text-xs font-medium text-red-800 dark:bg-red-900 dark:text-red-200">Отклонено</span>
                                @endif
                            </td>
                            <td class="px-4 py-2 dark:text-gray-300">{{ $chapter->created_at->format('d.m.Y H:i') }}</td>
                            <td class="px-4 py-2 text-center">
                                <a href="{{ route('chapters.show', [$chapter->titleBelong, $chapter]) }}"
                                   class="text-blue-600 hover:underline dark:text-blue-400"
                                   target="_blank">
                                    Читать
                                </a>
                            </td>
                        </tr>
                    @endforeach
                    </tbody>
                </table>
            </div>
        @endif
    </div>
</div>
@endsection
