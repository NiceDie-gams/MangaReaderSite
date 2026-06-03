@extends('layouts.app')

@section('content')
<div class="rounded bg-white p-6 shadow dark:bg-gray-800">
    <div class="mb-4 flex items-center justify-between">
        <h1 class="text-2xl font-bold dark:text-white">Мои главы</h1>
        <a href="{{ route('translator.chapters.create') }}" class="rounded bg-blue-600 px-3 py-1 text-sm text-white hover:bg-blue-700 dark:bg-orange-600 dark:hover:bg-orange-700">
            Новая глава
        </a>
    </div>

    {{-- Фильтр по статусу --}}
    <div class="mb-4">
        <form method="GET" class="flex gap-2">
            <select name="status" class="rounded border border-gray-300 px-3 py-1 dark:border-gray-600 dark:bg-gray-700 dark:text-white">
                <option value="">Все статусы</option>
                <option value="pending" {{ request('status') == 'pending' ? 'selected' : '' }}>На модерации</option>
                <option value="approved" {{ request('status') == 'approved' ? 'selected' : '' }}>Одобрено</option>
                <option value="rejected" {{ request('status') == 'rejected' ? 'selected' : '' }}>Отклонено</option>
            </select>
            <button type="submit" class="rounded bg-slate-200 px-3 py-1 text-slate-800 hover:bg-slate-300 dark:bg-gray-700 dark:text-white dark:hover:bg-gray-600">Фильтровать</button>
        </form>
    </div>

    <div class="overflow-x-auto">
        <table class="min-w-full border text-sm dark:border-gray-700">
            <thead class="bg-slate-100 dark:bg-gray-700">
                <tr>
                    <th class="px-4 py-2 text-left dark:text-white">Тайтл</th>
                    <th class="px-4 py-2 text-left dark:text-white">Глава</th>
                    <th class="px-4 py-2 text-left dark:text-white">Дата отправки</th>
                    <th class="px-4 py-2 text-left dark:text-white">Статус</th>
                    <th class="px-4 py-2 text-left dark:text-white">Причина отклонения</th>
                    <th class="px-4 py-2 dark:text-white">Действия</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($chapters as $chapter)
                <tr class="border-t dark:border-gray-700">
                    <td class="px-4 py-2 dark:text-gray-300">{{ $chapter->titleBelong->title }}</td>
                    <td class="px-4 py-2 dark:text-gray-300">{{ $chapter->chapter_number }}</td>
                    <td class="px-4 py-2 dark:text-gray-300">{{ $chapter->created_at->format('d.m.Y H:i') }}</td>
                    <td class="px-4 py-2">
                        @if($chapter->isPending())
                            <span class="inline-block rounded-full bg-yellow-100 px-2 py-0.5 text-xs font-medium text-yellow-800 dark:bg-yellow-900 dark:text-yellow-200">На модерации</span>
                        @elseif($chapter->isApproved())
                            <span class="inline-block rounded-full bg-green-100 px-2 py-0.5 text-xs font-medium text-green-800 dark:bg-green-900 dark:text-green-200">Одобрено</span>
                        @elseif($chapter->isRejected())
                            <span class="inline-block rounded-full bg-red-100 px-2 py-0.5 text-xs font-medium text-red-800 dark:bg-red-900 dark:text-red-200">Отклонено</span>
                        @endif
                    </td>
                    <td class="px-4 py-2 text-red-600 dark:text-red-400">{{ $chapter->reject_reason ?? '—' }}</td>
                    <td class="px-4 py-2 text-center">
                        @if($chapter->isRejected())
                            <a href="{{ route('translator.chapters.edit', $chapter) }}" class="text-blue-600 hover:underline dark:text-blue-400">Править</a>
                        @else
                            <span class="text-gray-400 dark:text-gray-500">—</span>
                        @endif
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="6" class="px-4 py-8 text-center text-gray-500 dark:text-gray-400">Глав пока нет</td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <div class="mt-4">
        {{ $chapters->links() }}
    </div>
</div>
@endsection
