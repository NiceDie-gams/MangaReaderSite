@extends('layouts.app')

@section('content')
<div class="space-y-4">
    <div class="flex items-center justify-between">
        <h1 class="text-2xl font-bold">Модерация глав</h1>
        <a href="{{ route('admin.dashboard') }}" class="text-sm text-blue-600 hover:underline">Назад в админ-панель</a>
    </div>

    @if(session('success'))
        <div class="rounded border border-emerald-300 bg-emerald-50 px-4 py-2 text-emerald-700">
            {{ session('success') }}
        </div>
    @endif

    @if($errors->any())
        <div class="rounded border border-rose-300 bg-rose-50 px-4 py-2 text-rose-700">
            {{ $errors->first() }}
        </div>
    @endif

    <div class="overflow-x-auto rounded bg-white shadow">
        <table class="min-w-full text-sm">
            <thead class="bg-slate-100 text-left">
                <tr>
                    <th class="px-3 py-2">ID</th>
                    <th class="px-3 py-2">Тайтл</th>
                    <th class="px-3 py-2">Глава</th>
                    <th class="px-3 py-2">Загрузил</th>
                    <th class="px-3 py-2">Статус</th>
                    <th class="px-3 py-2">Причина отклонения</th>
                    <th class="px-3 py-2">Действия</th>
                </tr>
            </thead>
            <tbody>
                @forelse($chapters as $chapter)
                    <tr class="border-t">
                        <td class="px-3 py-2">{{ $chapter->id }}</td>
                        <td class="px-3 py-2">
                            @if($chapter->titleBelong)
                                <a class="text-blue-600 hover:underline" href="{{ route('titles.show', $chapter->titleBelong) }}">
                                    {{ $chapter->titleBelong->title }}
                                </a>
                            @else
                                <span class="text-slate-500">Тайтл удалён</span>
                            @endif
                        </td>
                        <td class="px-3 py-2">#{{ $chapter->chapter_number }}</td>
                        <td class="px-3 py-2">
                            {{ $chapter->uploadedBy->name ?? '—' }}
                            @if($chapter->uploadedBy?->email)
                                <div class="text-xs text-slate-500">{{ $chapter->uploadedBy->email }}</div>
                            @endif
                        </td>
                        <td class="px-3 py-2">{{ $chapter->status }}</td>
                        <td class="px-3 py-2">{{ $chapter->reject_reason ?: '—' }}</td>
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

                                <form method="POST" action="{{ route('admin.chapters.reject', $chapter) }}" class="space-y-1">
                                    @csrf
                                    @method('PATCH')
                                    <textarea
                                        name="reject_reason"
                                        rows="2"
                                        required
                                        minlength="5"
                                        class="w-full rounded border px-2 py-1"
                                        placeholder="Причина отклонения"
                                    ></textarea>
                                    <button type="submit" class="w-full rounded bg-rose-600 px-3 py-1 text-white hover:bg-rose-700">
                                        Отклонить
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="7" class="px-3 py-6 text-center text-slate-500">Главы не найдены.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    {{ $chapters->links() }}
</div>
@endsection
