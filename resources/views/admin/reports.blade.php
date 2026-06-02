@extends('layouts.app')

@section('content')
<div class="space-y-4">
    <div class="flex items-center justify-between">
        <h1 class="text-2xl font-bold">Жалобы</h1>
        <a href="{{ route('admin.dashboard') }}" class="text-sm text-blue-600 hover:underline">Назад в админ-панель</a>
    </div>

    @if(session('success'))
        <div class="rounded border border-emerald-300 bg-emerald-50 px-4 py-2 text-emerald-700">
            {{ session('success') }}
        </div>
    @endif

    <div class="space-y-3">
        @forelse($reports as $report)
            <div class="rounded bg-white p-4 shadow">
                <div class="mb-2 flex items-start justify-between gap-3">
                    <div>
                        <p class="font-semibold">Жалоба #{{ $report->id }}</p>
                        <p class="text-xs text-slate-500">{{ $report->created_at?->format('d.m.Y H:i') }}</p>
                    </div>
                    <span class="rounded px-2 py-1 text-xs {{ $report->isSolved ? 'bg-emerald-100 text-emerald-700' : 'bg-amber-100 text-amber-700' }}">
                        {{ $report->isSolved ? 'Решено' : 'Открыто' }}
                    </span>
                </div>

                <p class="mb-3 text-sm">{{ $report->reportText }}</p>

                <div class="mb-3 text-sm text-slate-600">
                    Пользователь:
                    <span class="font-medium">{{ $report->user->name ?? 'Удалённый пользователь' }}</span>
                    @if($report->user)
                        ({{ $report->user->email }}, роль: {{ $report->user->role }})
                    @endif
                </div>

                @if(!$report->isSolved)
                    <form method="POST" action="{{ route('admin.reports.solve', $report) }}">
                        @csrf
                        @method('PATCH')
                        <button type="submit" class="rounded bg-blue-600 px-3 py-1 text-sm text-white hover:bg-blue-700">
                            Отметить как решённую
                        </button>
                    </form>
                @endif
            </div>
        @empty
            <div class="rounded bg-white p-6 text-center text-slate-500 shadow">
                Жалоб пока нет.
            </div>
        @endforelse
    </div>

    {{ $reports->links() }}
</div>
@endsection
