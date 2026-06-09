@extends('layouts.app')

@section('content')
<div class="space-y-4">
    <div class="flex flex-col gap-3 sm:flex-row sm:items-center sm:justify-between">
        <h1 class="text-2xl font-bold">Пользователи</h1>
        <a href="{{ route('admin.dashboard') }}" class="text-sm text-blue-600 hover:underline dark:text-blue-400">← Назад в админ-панель</a>
    </div>

    @if(session('success'))
        <div class="rounded border border-emerald-300 bg-emerald-50 px-4 py-2 text-emerald-700 dark:border-emerald-800 dark:bg-emerald-950 dark:text-emerald-300">
            {{ session('success') }}
        </div>
    @endif

    @if(session('error'))
        <div class="rounded border border-rose-300 bg-rose-50 px-4 py-2 text-rose-700 dark:border-rose-800 dark:bg-rose-950 dark:text-rose-300">
            {{ session('error') }}
        </div>
    @endif

    {{-- Десктопная таблица (видна на md и выше) --}}
    <div class="hidden overflow-x-auto rounded bg-white shadow dark:bg-gray-800 md:block">
        <table class="min-w-full text-sm">
            <thead class="bg-slate-100 text-left dark:bg-gray-700">
                <tr>
                    <th class="px-3 py-2 dark:text-white">ID</th>
                    <th class="px-3 py-2 dark:text-white">Имя</th>
                    <th class="px-3 py-2 dark:text-white">Email</th>
                    <th class="px-3 py-2 dark:text-white">Роль</th>
                    <th class="px-3 py-2 dark:text-white">Статус</th>
                    <th class="px-3 py-2 dark:text-white">Избранное</th>
                    <th class="px-3 py-2 dark:text-white">Комментарии</th>
                    <th class="px-3 py-2 dark:text-white">Загружено глав</th>
                    <th class="px-3 py-2 dark:text-white">Дата регистрации</th>
                    <th class="px-3 py-2 dark:text-white">Действия</th>
                </tr>
            </thead>
            <tbody>
                @forelse($users as $user)
                    <tr class="border-t dark:border-gray-700">
                        <td class="px-3 py-2 dark:text-gray-300">{{ $user->id }}</td>
                        <td class="px-3 py-2 dark:text-gray-300">{{ $user->name }}</td>
                        <td class="px-3 py-2 dark:text-gray-300">{{ $user->email }}</td>
                        <td class="px-3 py-2">
                            <form method="POST" action="{{ route('admin.users.role', $user) }}" class="flex items-center gap-1">
                                @csrf
                                @method('PATCH')
                                <select name="role" class="rounded border px-2 py-1 text-xs dark:border-gray-600 dark:bg-gray-700 dark:text-white">
                                    <option value="user" {{ $user->role === 'user' ? 'selected' : '' }}>user</option>
                                    <option value="translator" {{ $user->role === 'translator' ? 'selected' : '' }}>translator</option>
                                    <option value="admin" {{ $user->role === 'admin' ? 'selected' : '' }}>admin</option>
                                </select>
                                <button type="submit" class="rounded bg-blue-600 px-2 py-1 text-xs text-white hover:bg-blue-700">Сохранить</button>
                            </form>
                        </td>
                        <td class="px-3 py-2">
                            @if($user->isBanned())
                                <span class="rounded-full bg-red-100 px-2 py-0.5 text-xs font-medium text-red-800 dark:bg-red-900 dark:text-red-200">Заблокирован</span>
                            @else
                                <span class="rounded-full bg-green-100 px-2 py-0.5 text-xs font-medium text-green-800 dark:bg-green-900 dark:text-green-200">Активен</span>
                            @endif
                        </td>
                        <td class="px-3 py-2 dark:text-gray-300">{{ $user->favorites_count }}</td>
                        <td class="px-3 py-2 dark:text-gray-300">{{ $user->comments_count }}</td>
                        <td class="px-3 py-2 dark:text-gray-300">{{ $user->uploaded_chapters_count }}</td>
                        <td class="px-3 py-2 dark:text-gray-300">{{ $user->created_at?->format('d.m.Y H:i') }}</td>
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
                    <tr class="border-t dark:border-gray-700">
                        <td colspan="10" class="px-3 py-6 text-center text-slate-500 dark:text-gray-400">Пользователи не найдены.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    {{-- Мобильные карточки (видны только на экранах меньше md) --}}
    <div class="space-y-4 md:hidden">
        @forelse($users as $user)
            <div class="rounded-lg bg-white p-4 shadow dark:bg-gray-800">
                <div class="mb-3 flex flex-wrap items-start justify-between gap-2">
                    <div class="flex items-center gap-2">
                        <span class="rounded-full bg-slate-200 px-2 py-0.5 text-xs font-medium dark:bg-gray-700 dark:text-white">#{{ $user->id }}</span>
                        @if($user->isBanned())
                            <span class="rounded-full bg-red-100 px-2 py-0.5 text-xs font-medium text-red-800 dark:bg-red-900 dark:text-red-200">Заблокирован</span>
                        @else
                            <span class="rounded-full bg-green-100 px-2 py-0.5 text-xs font-medium text-green-800 dark:bg-green-900 dark:text-green-200">Активен</span>
                        @endif
                    </div>
                    <div class="text-sm font-semibold dark:text-white">{{ $user->name }}</div>
                </div>

                <div class="space-y-2 text-sm">
                    <div>
                        <span class="font-semibold dark:text-white">Email:</span>
                        <span class="dark:text-gray-300">{{ $user->email }}</span>
                    </div>
                    <div>
                        <span class="font-semibold dark:text-white">Роль:</span>
                        <form method="POST" action="{{ route('admin.users.role', $user) }}" class="mt-1 flex items-center gap-2">
                            @csrf
                            @method('PATCH')
                            <select name="role" class="rounded border px-2 py-1 text-sm dark:border-gray-600 dark:bg-gray-700 dark:text-white">
                                <option value="user" {{ $user->role === 'user' ? 'selected' : '' }}>Пользователь</option>
                                <option value="translator" {{ $user->role === 'translator' ? 'selected' : '' }}>Переводчик</option>
                                <option value="admin" {{ $user->role === 'admin' ? 'selected' : '' }}>Администратор</option>
                            </select>
                            <button type="submit" class="rounded bg-blue-600 px-3 py-1 text-xs text-white hover:bg-blue-700">Сохранить</button>
                        </form>
                    </div>
                    <div class="grid grid-cols-2 gap-1">
                        <div><span class="font-semibold dark:text-white">Избранное:</span> {{ $user->favorites_count }}</div>
                        <div><span class="font-semibold dark:text-white">Комментарии:</span> {{ $user->comments_count }}</div>
                        <div><span class="font-semibold dark:text-white">Загружено глав:</span> {{ $user->uploaded_chapters_count }}</div>
                        <div><span class="font-semibold dark:text-white">Регистрация:</span> {{ $user->created_at?->format('d.m.Y') }}</div>
                    </div>
                </div>

                <div class="mt-4">
                    <form method="POST" action="{{ route('admin.users.ban', $user) }}" onsubmit="return confirm('{{ $user->isBanned() ? 'Разблокировать пользователя?' : 'Заблокировать пользователя?' }}')">
                        @csrf
                        @method('PATCH')
                        <button type="submit" class="w-full rounded px-3 py-2 text-sm font-medium text-white {{ $user->isBanned() ? 'bg-emerald-600 hover:bg-emerald-700' : 'bg-rose-600 hover:bg-rose-700' }}">
                            {{ $user->isBanned() ? 'Разблокировать' : 'Заблокировать' }}
                        </button>
                    </form>
                </div>
            </div>
        @empty
            <div class="rounded-lg bg-white p-6 text-center text-slate-500 shadow dark:bg-gray-800 dark:text-gray-400">
                Пользователи не найдены.
            </div>
        @endforelse
    </div>

    <div class="mt-4">
        {{ $users->links() }}
    </div>
</div>
@endsection
