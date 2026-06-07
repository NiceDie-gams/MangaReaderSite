@extends('layouts.app')

@section('content')
<div class="space-y-4">
    <div class="flex items-center justify-between">
        <h1 class="text-2xl font-bold">Пользователи</h1>
        <a href="{{ route('admin.dashboard') }}" class="text-sm text-blue-600 hover:underline">Назад в админ-панель</a>
    </div>

    @if(session('success'))
        <div class="rounded border border-emerald-300 bg-emerald-50 px-4 py-2 text-emerald-700">
            {{ session('success') }}
        </div>
    @endif

    @if(session('error'))
        <div class="rounded border border-rose-300 bg-rose-50 px-4 py-2 text-rose-700">
            {{ session('error') }}
        </div>
    @endif

    <div class="overflow-x-auto rounded bg-white shadow">
        <table class="min-w-full text-sm">
            <thead class="bg-slate-100 text-left">
                <tr>
                    <th class="px-3 py-2">ID</th>
                    <th class="px-3 py-2">Имя</th>
                    <th class="px-3 py-2">Email</th>
                    <th class="px-3 py-2">Роль</th>
                    <th class="px-3 py-2">Статус</th>
                    <th class="px-3 py-2">Избранное</th>
                    <th class="px-3 py-2">Комментарии</th>
                    <th class="px-3 py-2">Загружено глав</th>
                    <th class="px-3 py-2">Дата регистрации</th>
                    <th class="px-3 py-2">Действия</th>
                </tr>
            </thead>
            <tbody>
                @forelse($users as $user)
                    <tr class="border-t">
                        <td class="px-3 py-2">{{ $user->id }}</td>
                        <td class="px-3 py-2">{{ $user->name }}</td>
                        <td class="px-3 py-2">{{ $user->email }}</td>
                        <td class="px-3 py-2">
                            <form method="POST" action="{{ route('admin.users.role', $user) }}" class="flex items-center gap-1">
                                @csrf
                                @method('PATCH')
                                <select name="role" class="rounded border px-2 py-1 text-xs">
                                    <option value="user" {{ $user->role === 'user' ? 'selected' : '' }}>user</option>
                                    <option value="translator" {{ $user->role === 'translator' ? 'selected' : '' }}>translator</option>
                                    <option value="admin" {{ $user->role === 'admin' ? 'selected' : '' }}>admin</option>
                                </select>
                                <button type="submit" class="rounded bg-blue-600 px-2 py-1 text-xs text-white hover:bg-blue-700">Сохранить</button>
                            </form>
                        </td>
                        <td class="px-3 py-2">
                            @if($user->isBanned())
                                <span class="rounded-full bg-red-100 px-2 py-0.5 text-xs font-medium text-red-800">Заблокирован</span>
                            @else
                                <span class="rounded-full bg-green-100 px-2 py-0.5 text-xs font-medium text-green-800">Активен</span>
                            @endif
                        </td>
                        <td class="px-3 py-2">{{ $user->favorites_count }}</td>
                        <td class="px-3 py-2">{{ $user->comments_count }}</td>
                        <td class="px-3 py-2">{{ $user->uploaded_chapters_count }}</td>
                        <td class="px-3 py-2">{{ $user->created_at?->format('d.m.Y H:i') }}</td>
                        <td class="px-3 py-2">
                            <form method="POST" action="{{ route('admin.users.ban', $user) }}" onsubmit="return confirm('{{ $user->isBanned() ? 'Разблокировать пользователя?' : 'Заблокировать пользователя?' }}')">
                                @csrf
                                @method('PATCH')
                                <button type="submit" class="rounded px-3 py-1 text-xs text-white {{ $user->isBanned() ? 'bg-emerald-600 hover:bg-emerald-700' : 'bg-rose-600 hover:bg-rose-700' }}">
                                    {{ $user->isBanned() ? 'Разблокировать' : 'Заблокировать' }}
                                </button>
                            </form>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="10" class="px-3 py-6 text-center text-slate-500">Пользователи не найдены.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    {{ $users->links() }}
</div>
@endsection
