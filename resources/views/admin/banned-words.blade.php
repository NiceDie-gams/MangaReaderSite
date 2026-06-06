@extends('layouts.app')

@section('content')
<div class="rounded bg-white p-6 shadow dark:bg-gray-800">
    <div class="mb-4 flex items-center justify-between">
        <h1 class="text-2xl font-bold dark:text-white">Запрещённые слова</h1>
        <a href="{{ route('admin.dashboard') }}" class="text-sm text-blue-600 hover:underline dark:text-blue-400">Назад в админ-панель</a>
    </div>

    @if(session('success'))
        <div class="mb-4 rounded border border-emerald-300 bg-emerald-50 px-4 py-2 text-emerald-700 dark:bg-emerald-950 dark:text-emerald-300">
            {{ session('success') }}
        </div>
    @endif

    {{-- Форма добавления --}}
    <div class="mb-6 rounded border p-4 dark:border-gray-700">
        <h2 class="mb-3 text-lg font-semibold dark:text-white">Добавить слово</h2>
        <form method="POST" action="{{ route('admin.banned-words.store') }}" class="flex gap-2">
            @csrf
            <input type="text" name="word" placeholder="Введите слово" required
                   class="flex-1 rounded border px-3 py-2 dark:border-gray-600 dark:bg-gray-700 dark:text-white">
            <button type="submit" class="rounded bg-blue-600 px-4 py-2 text-white hover:bg-blue-700">Добавить</button>
        </form>
    </div>

    {{-- Список слов --}}
    <div class="overflow-x-auto">
        <table class="min-w-full border text-sm dark:border-gray-700">
            <thead class="bg-slate-100 dark:bg-gray-700">
            <tr>
                <th class="px-4 py-2 text-left dark:text-white">Слово</th>
                <th class="px-4 py-2 text-left dark:text-white">Добавлено</th>
                <th class="px-4 py-2 dark:text-white">Действие</th>
            </tr>
            </thead>
            <tbody>
            @forelse($bannedWords as $word)
                <tr class="border-t dark:border-gray-700">
                    <td class="px-4 py-2 dark:text-gray-300">{{ $word->word }}</td>
                    <td class="px-4 py-2 dark:text-gray-300">{{ $word->created_at->format('d.m.Y H:i') }}</td>
                    <td class="px-4 py-2 text-center">
                        <form method="POST" action="{{ route('admin.banned-words.destroy', $word) }}" onsubmit="return confirm('Удалить слово?')">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="text-red-600 hover:underline dark:text-red-400">Удалить</button>
                        </form>
                    </td>
                </tr>
            @empty
                <tr class="border-t dark:border-gray-700">
                    <td colspan="3" class="px-4 py-8 text-center text-gray-500 dark:text-gray-400">Список запрещённых слов пуст</td>
                </tr>
            @endforelse
            </tbody>
        </table>
    </div>

    <div class="mt-4">
        {{ $bannedWords->links() }}
    </div>
</div>
@endsection
