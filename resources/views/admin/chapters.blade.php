@extends('layouts.app')

@section('content')
<div class="space-y-4">
    <div class="flex flex-col gap-3 sm:flex-row sm:items-center sm:justify-between">
        <h1 class="text-2xl font-bold">Модерация глав</h1>
        <a href="{{ route('admin.dashboard') }}" class="text-sm text-blue-600 hover:underline dark:text-blue-400">← Назад в админ-панель</a>
    </div>

    @if(session('success'))
        <div class="rounded border border-emerald-300 bg-emerald-50 px-4 py-2 text-emerald-700 dark:border-emerald-800 dark:bg-emerald-950 dark:text-emerald-300">
            {{ session('success') }}
        </div>
    @endif

    @if($errors->any())
        <div class="rounded border border-rose-300 bg-rose-50 px-4 py-2 text-rose-700 dark:border-rose-800 dark:bg-rose-950 dark:text-rose-300">
            {{ $errors->first() }}
        </div>
    @endif

    {{-- Десктопная таблица (видна на md и выше) --}}
    <div class="hidden overflow-x-auto rounded bg-white shadow dark:bg-gray-800 md:block">
        <table class="min-w-full text-sm">
            <thead class="bg-slate-100 text-left dark:bg-gray-700">
                <tr>
                    <th class="px-3 py-2 dark:text-white">ID</th>
                    <th class="px-3 py-2 dark:text-white">Тайтл</th>
                    <th class="px-3 py-2 dark:text-white">Глава</th>
                    <th class="px-3 py-2 dark:text-white">Загрузил</th>
                    <th class="px-3 py-2 dark:text-white">Статус</th>
                    <th class="px-3 py-2 dark:text-white">Причина отклонения</th>
                    <th class="px-3 py-2 dark:text-white">Действия</th>
                </tr>
            </thead>
            <tbody>
                @forelse($chapters as $chapter)
                    <tr class="border-t dark:border-gray-700">
                        <td class="px-3 py-2 dark:text-gray-300">{{ $chapter->id }}</td>
                        <td class="px-3 py-2">
                            @if($chapter->titleBelong)
                                <a class="text-blue-600 hover:underline dark:text-blue-400" href="{{ route('titles.show', $chapter->titleBelong) }}">
                                    {{ $chapter->titleBelong->title }}
                                </a>
                            @else
                                <span class="text-slate-500 dark:text-gray-400">Тайтл удалён</span>
                            @endif
                        </td>
                        <td class="px-3 py-2 dark:text-gray-300">#{{ $chapter->chapter_number }}</td>
                        <td class="px-3 py-2 dark:text-gray-300">
                            {{ $chapter->uploadedBy->name ?? '—' }}
                            @if($chapter->uploadedBy?->email)
                                <div class="text-xs text-slate-500 dark:text-gray-400">{{ $chapter->uploadedBy->email }}</div>
                            @endif
                        </td>
                        <td class="px-3 py-2">
                            <span class="rounded-full px-2 py-0.5 text-xs font-medium
                                @if($chapter->status === 'pending') bg-yellow-100 text-yellow-800 dark:bg-yellow-900 dark:text-yellow-200
                                @elseif($chapter->status === 'approved') bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-200
                                @elseif($chapter->status === 'rejected') bg-red-100 text-red-800 dark:bg-red-900 dark:text-red-200 @endif">
                                {{ $chapter->status }}
                            </span>
                        </td>
                        <td class="px-3 py-2 dark:text-gray-300">{{ $chapter->reject_reason ?: '—' }}</td>
                        <td class="px-3 py-2">
                            <div class="flex flex-col gap-2">
                                @if($chapter->status !== \App\Models\Chapter::STATUS_APPROVED)
                                    <form method="POST" action="{{ route('admin.chapters.approve', $chapter) }}">
                                        @csrf
                                        @method('PATCH')
                                        <button type="submit" class="w-full rounded bg-emerald-600 px-3 py-1 text-white hover:bg-emerald-700">
                                            Одобрить
                                        </button>
                                    </form>
                                @endif
                                <form method="POST" action="{{ route('admin.chapters.reject', $chapter) }}">
                                    @csrf
                                    @method('PATCH')
                                    <textarea name="reject_reason" rows="2" required minlength="5" class="w-full rounded border px-2 py-1 dark:border-gray-600 dark:bg-gray-700 dark:text-white" placeholder="Причина отклонения"></textarea>
                                    <button type="submit" class="mt-1 w-full rounded bg-rose-600 px-3 py-1 text-white hover:bg-rose-700">
                                        Отклонить
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr class="border-t dark:border-gray-700">
                        <td colspan="7" class="px-3 py-6 text-center text-slate-500 dark:text-gray-400">Главы не найдены.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    {{-- Мобильные карточки (видны только на экранах меньше md) --}}
    <div class="space-y-4 md:hidden">
        @forelse($chapters as $chapter)
            <div class="rounded-lg bg-white p-4 shadow dark:bg-gray-800">
                <div class="mb-3 flex flex-wrap items-center justify-between gap-2">
                    <div class="flex items-center gap-2">
                        <span class="rounded-full bg-slate-200 px-2 py-0.5 text-xs font-medium dark:bg-gray-700 dark:text-white">#{{ $chapter->id }}</span>
                        <span class="rounded-full px-2 py-0.5 text-xs font-medium
                            @if($chapter->status === 'pending') bg-yellow-100 text-yellow-800 dark:bg-yellow-900 dark:text-yellow-200
                            @elseif($chapter->status === 'approved') bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-200
                            @elseif($chapter->status === 'rejected') bg-red-100 text-red-800 dark:bg-red-900 dark:text-red-200 @endif">
                            {{ $chapter->status }}
                        </span>
                    </div>
                    <div class="text-sm font-medium">Глава #{{ $chapter->chapter_number }}</div>
                </div>

                <div class="space-y-2 text-sm">
                    <div>
                        <span class="font-semibold dark:text-white">Тайтл:</span>
                        @if($chapter->titleBelong)
                            <a href="{{ route('titles.show', $chapter->titleBelong) }}" class="text-blue-600 hover:underline dark:text-blue-400">
                                {{ $chapter->titleBelong->title }}
                            </a>
                        @else
                            <span class="text-slate-500 dark:text-gray-400">Тайтл удалён</span>
                        @endif
                    </div>
                    <div>
                        <span class="font-semibold dark:text-white">Загрузил:</span>
                        <span class="dark:text-gray-300">{{ $chapter->uploadedBy->name ?? '—' }}</span>
                        @if($chapter->uploadedBy?->email)
                            <div class="text-xs text-slate-500 dark:text-gray-400">{{ $chapter->uploadedBy->email }}</div>
                        @endif
                    </div>
                    @if($chapter->reject_reason)
                        <div>
                            <span class="font-semibold dark:text-white">Причина отклонения:</span>
                            <div class="mt-1 rounded bg-slate-100 p-2 dark:bg-gray-700 dark:text-gray-300">{{ $chapter->reject_reason }}</div>
                        </div>
                    @endif
                </div>

                <div class="mt-4 flex flex-col gap-2">
                    @if($chapter->status !== \App\Models\Chapter::STATUS_APPROVED)
                        <form method="POST" action="{{ route('admin.chapters.approve', $chapter) }}">
                            @csrf
                            @method('PATCH')
                            <button type="submit" class="w-full rounded bg-emerald-600 px-3 py-2 text-white hover:bg-emerald-700">
                                Одобрить
                            </button>
                        </form>
                    @endif
                    <form method="POST" action="{{ route('admin.chapters.reject', $chapter) }}">
                        @csrf
                        @method('PATCH')
                        <textarea name="reject_reason" rows="2" required minlength="5" class="w-full rounded border px-2 py-1 dark:border-gray-600 dark:bg-gray-700 dark:text-white" placeholder="Причина отклонения"></textarea>
                        <button type="submit" class="mt-2 w-full rounded bg-rose-600 px-3 py-2 text-white hover:bg-rose-700">
                            Отклонить
                        </button>
                    </form>
                </div>
            </div>
        @empty
            <div class="rounded-lg bg-white p-6 text-center text-slate-500 shadow dark:bg-gray-800 dark:text-gray-400">
                Главы не найдены.
            </div>
        @endforelse
    </div>

    <div class="mt-4">
        {{ $chapters->links() }}
    </div>
</div>
@endsection
