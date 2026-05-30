@extends('layouts.app')

@section('content')
    <div class="mx-auto max-w-md rounded bg-white p-6 shadow">
        <h1 class="mb-4 text-2xl font-semibold">Вход</h1>
        <form method="POST" action="{{ route('auth.login.submit') }}" class="space-y-4">
            @csrf
            <div>
                <input type="email" name="email" value="{{ old('email') }}" placeholder="Email" class="w-full rounded border px-3 py-2 @error('email') border-rose-500 @enderror" required>
                @error('email')
                    <p class="mt-1 text-sm text-rose-600">{{ $message }}</p>
                @enderror
            </div>
            <div>
                <input type="password" name="password" placeholder="Пароль" class="w-full rounded border px-3 py-2 @error('password') border-rose-500 @enderror" required>
                @error('password')
                    <p class="mt-1 text-sm text-rose-600">{{ $message }}</p>
                @enderror
            </div>
            <button type="submit" class="w-full rounded bg-blue-600 px-3 py-2 text-white">Войти</button>
        </form>
        <a href="{{ route('auth.register') }}" class="mt-4 inline-block text-blue-600">Нет аккаунта? Регистрация</a>
    </div>
@endsection
