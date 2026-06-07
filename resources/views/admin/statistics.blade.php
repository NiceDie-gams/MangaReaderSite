@extends('layouts.app')

@section('content')
<div class="rounded bg-white p-6 shadow">
    < class="mb-4 flex items-center justify-between">
        <h1 class="text-2xl font-bold">Статистика тегов по избранному</h1>
        <a href="{{ route('admin.dashboard') }}" class="text-sm text-blue-600 hover:underline">Назад в админ-панель</a>

        <form action="{{ route('statistics.update') }}" method="POST" id="update-form">
            @csrf
            <button type="submit" class="rounded bg-blue-600 px-4 py-2 text-white hover:bg-blue-700">
                Пересчитать статистику
            </button>
        </form>
    </div>

    @if(session('success'))
        <div class="mb-4 rounded bg-green-100 p-3 text-green-700">{{ session('success') }}</div>
    @endif

    <div class="overflow-x-auto">
        <table class="min-w-full border text-sm">
            <thead class="bg-slate-100">
            <tr>
                <th class="px-4 py-2 text-left">Тег</th>
                <th class="px-4 py-2 text-left">Количество в избранном</th>
                <th class="px-4 py-2 text-left">Последний расчёт</th>
            </tr>
            </thead>
            <tbody>
            @forelse($tags as $tag)
            <tr class="border-t">
                <td class="px-4 py-2">{{ $tag->name }}</td>
                <td class="px-4 py-2 font-semibold">{{ $tag->statistic?->favorites_count ?? 0 }}</td>
                <td class="px-4 py-2 text-gray-500">
                    {{ $tag->statistic?->last_calculated_at?->format('d.m.Y H:i') ?? '—' }}
                </td>
            </tr>
            @empty
            <tr>
                <td colspan="3" class="px-4 py-8 text-center text-gray-500">Нет данных</td>
            </tr>
            @endforelse
            </tbody>
        </table>
    </div>

    <div class="mt-4">{{ $tags->links() }}</div>
</div>

<script>
    document.getElementById('update-form')?.addEventListener('submit', function(e) {
        if (!confirm('Пересчёт статистики может занять некоторое время. Продолжить?')) {
            e.preventDefault();
        }
    });
</script>
@endsection
