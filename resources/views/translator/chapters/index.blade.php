@extends('layouts.app')

@section('content')
<div class="rounded bg-white p-6 shadow">
    <div class="mb-4 flex items-center justify-between">
        <h1 class="text-2xl font-bold">Мои главы</h1>
        <a href="{{ route('translator.chapters.create') }}" class="rounded bg-blue-600 px-3 py-1 text-sm text-white">Новая глава</a>
    </div>

    {{-- Фильтр по статусу --}}
    <div class="mb-4">
        <form method="GET" class="flex gap-2">
            <select name="status" class="rounded border px-3 py-1">
                <option value="">Все статусы</option>
                <option value="pending" {{ request('status') == 'pending' ? 'selected' : '' }}>На модерации</option>
                <option value="approved" {{ request('status') == 'approved' ? 'selected' : '' }}>Одобрено</option>
                <option value="rejected" {{ request('status') == 'rejected' ? 'selected' : '' }}>Отклонено</option>
            </select>
            <button type="submit" class="rounded bg-slate-200 px-3 py-1">Фильтровать</button>
        </form>
    </div>

    <div class="overflow-x-auto">
        <table class="min-w-full border text-sm">
            <thead class="bg-slate-100">
                <tr>
                    <th class="px-4 py-2 text-left">Тайтл</th>
                    <th class="px-4 py-2 text-left">Глава</th>
                    <th class="px-4 py-2 text-left">Дата отправки</th>
                    <th class="px-4 py-2 text-left">Статус</th>
                    <th class="px-4 py-2 text-left">Причина отклонения</th>
                    <th class="px-4 py-2">Действия</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($chapters as $chapter)
                <tr class="border-t">
                    <td class="px-4 py-2">{{ $chapter->titleBelong->title }}</td>
                    <td class="px-4 py-2">{{ $chapter->chapter_number }}</td>
                    <td class="px-4 py-2">{{ $chapter->created_at->format('d.m.Y H:i') }}</td>
                    <td class="px-4 py-2">
                        @if($chapter->isPending())
                            <span class="rounded bg-yellow-100 px-2 py-0.5 text-xs">На модерации</span>
                        @elseif($chapter->isApproved())
                            <span class="rounded bg-green-100 px-2 py-0.5 text-xs">Одобрено</span>
                        @elseif($chapter->isRejected())
                            <span class="rounded bg-red-100 px-2 py-0.5 text-xs">Отклонено</span>
                        @endif
                    </td>
                    <td class="px-4 py-2 text-red-600">{{ $chapter->reject_reason ?? '—' }}</td>
                    <td class="px-4 py-2 text-center">
                        @if($chapter->isRejected())
                            <a href="{{ route('translator.chapters.edit', $chapter) }}" class="text-blue-600 hover:underline">Править</a>
                        @else
                            <span class="text-gray-400">—</span>
                        @endif
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="6" class="px-4 py-8 text-center text-gray-500">Глав пока нет</td>
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
