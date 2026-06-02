@extends('layouts.app')

@section('content')
<div class="space-y-4">
    <div class="flex items-center justify-between">
        <h1 class="text-2xl font-bold">Пользователи</h1>
        <a href="{{ route('admin.dashboard') }}" class="text-sm text-blue-600 hover:underline">Назад в админ-панель</a>
    </div>

    <div class="overflow-x-auto rounded bg-white shadow">
        <table class="min-w-full text-sm">
            <thead class="bg-slate-100 text-left">
                <tr>
                    <th class="px-3 py-2">ID</th>
                    <th class="px-3 py-2">Имя</th>
                    <th class="px-3 py-2">Email</th>
                    <th class="px-3 py-2">Роль</th>
                    <th class="px-3 py-2">Избранное</th>
                    <th class="px-3 py-2">Комментарии</th>
                    <th class="px-3 py-2">Загружено глав</th>
                    <th class="px-3 py-2">Дата регистрации</th>
                </tr>
            </thead>
            <tbody>
                @forelse($users as $user)
                    <tr class="border-t">
                        <td class="px-3 py-2">{{ $user->id }}</td>
                        <td class="px-3 py-2">{{ $user->name }}</td>
                        <td class="px-3 py-2">{{ $user->email }}</td>
                        <td class="px-3 py-2">
                            <span class="rounded px-2 py-1 text-xs {{ $user->role === 'admin' ? 'bg-purple-100 text-purple-700' : ($user->role === 'translator' ? 'bg-blue-100 text-blue-700' : 'bg-slate-200 text-slate-700') }}">
                                {{ $user->role }}
                            </span>
                        </td>
                        <td class="px-3 py-2">{{ $user->favorites_count }}</td>
                        <td class="px-3 py-2">{{ $user->comments_count }}</td>
                        <td class="px-3 py-2">{{ $user->uploaded_chapters_count }}</td>
                        <td class="px-3 py-2">{{ $user->created_at?->format('d.m.Y H:i') }}</td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="8" class="px-3 py-6 text-center text-slate-500">Пользователи не найдены.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    {{ $users->links() }}
</div>
@endsection
