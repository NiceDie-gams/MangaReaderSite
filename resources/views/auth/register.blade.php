@extends('layouts.app')

@section('content')
    <div class="mx-auto max-w-md rounded bg-white p-6 shadow">
        <h1 class="mb-4 text-2xl font-semibold">Регистрация</h1>
        <form method="POST" action="{{ route('auth.register.submit') }}" class="space-y-4">
            @csrf
            <div>
                <input type="text" name="name" value="{{ old('name') }}" placeholder="Имя" class="w-full rounded border px-3 py-2 @error('name') border-rose-500 @enderror" required>
                @error('name')
                    <p class="mt-1 text-sm text-rose-600">{{ $message }}</p>
                @enderror
            </div>
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
            <div>
                <input type="password" name="password_confirmation" placeholder="Повторите пароль" class="w-full rounded border px-3 py-2" required>
            </div>
            <button type="submit" class="w-full rounded bg-blue-600 px-3 py-2 text-white">Зарегистрироваться</button>
        </form>
    </div>
@endsection
