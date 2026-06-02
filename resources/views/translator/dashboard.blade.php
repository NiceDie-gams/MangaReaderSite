@extends('layouts.app')

@section('content')
<div class="rounded bg-white p-6 shadow">
    <h1 class="mb-6 text-2xl font-bold">Панель переводчика</h1>

    {{-- Блок статистики (оставляем как есть) --}}
    <div class="mb-8 grid grid-cols-1 gap-4 md:grid-cols-4">
        <div class="rounded bg-blue-100 p-4 text-center">
            <div class="text-2xl font-bold">{{ $stats['total'] }}</div>
            <div class="text-sm">Всего глав</div>
        </div>
        <div class="rounded bg-yellow-100 p-4 text-center">
            <div class="text-2xl font-bold">{{ $stats['pending'] }}</div>
            <div class="text-sm">На модерации</div>
        </div>
        <div class="rounded bg-green-100 p-4 text-center">
            <div class="text-2xl font-bold">{{ $stats['approved'] }}</div>
            <div class="text-sm">Одобрено</div>
        </div>
        <div class="rounded bg-red-100 p-4 text-center">
            <div class="text-2xl font-bold">{{ $stats['rejected'] }}</div>
            <div class="text-sm">Отклонено</div>
        </div>
    </div>

    {{-- Кнопка добавления новой главы --}}
    <div class="mb-6 text-center">
        <a href="{{ route('translator.chapters.create') }}" class="rounded bg-blue-600 px-4 py-2 text-white hover:bg-blue-700">
            + Добавить новую главу
        </a>
    </div>

    {{-- Список последних глав --}}
    <div class="mt-8">
        <div class="mb-4 flex items-center justify-between">
            <h2 class="text-xl font-semibold">Последние загруженные главы</h2>
            <a href="{{ route('translator.chapters.index') }}" class="text-sm text-blue-600 hover:underline">Все главы →</a>
        </div>

        @if($recentChapters->isEmpty())
            <p class="text-gray-500">Вы ещё не загрузили ни одной главы.</p>
        @else
            <div class="overflow-x-auto">
                <table class="min-w-full border text-sm">
                    <thead class="bg-slate-100">
                    <tr>
                        <th class="px-4 py-2 text-left">Тайтл</th>
                        <th class="px-4 py-2 text-left">Глава</th>
                        <th class="px-4 py-2 text-left">Статус</th>
                        <th class="px-4 py-2 text-left">Дата загрузки</th>
                        <th class="px-4 py-2">Действие</th>
                    </tr>
                    </thead>
                    <tbody>
                    @foreach($recentChapters as $chapter)
                        <tr class="border-t">
                            <td class="px-4 py-2">{{ $chapter->titleBelong->title }}</td>
                            <td class="px-4 py-2">{{ $chapter->chapter_number }}</td>
                            <td class="px-4 py-2">
                                @if($chapter->isPending())
                                    <span class="rounded bg-yellow-100 px-2 py-0.5 text-xs">На модерации</span>
                                @elseif($chapter->isApproved())
                                    <span class="rounded bg-green-100 px-2 py-0.5 text-xs">Одобрено</span>
                                @elseif($chapter->isRejected())
                                    <span class="rounded bg-red-100 px-2 py-0.5 text-xs">Отклонено</span>
                                @endif
                            </td>
                            <td class="px-4 py-2">{{ $chapter->created_at->format('d.m.Y H:i') }}</td>
                            <td class="px-4 py-2 text-center">
                                <a href="{{ route('chapters.show', [$chapter->titleBelong, $chapter]) }}"
                                   class="text-blue-600 hover:underline"
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
