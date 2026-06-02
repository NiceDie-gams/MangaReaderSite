@extends('layouts.app')

@section('content')
<div class="space-y-6">
    <div>
        <h1 class="text-2xl font-bold">Панель администратора</h1>
        <p class="text-sm text-slate-600">Управление главами, жалобами и пользователями.</p>
    </div>

    @if(session('success'))
        <div class="rounded border border-emerald-300 bg-emerald-50 px-4 py-2 text-emerald-700">
            {{ session('success') }}
        </div>
    @endif

    <div class="grid gap-4 sm:grid-cols-2 lg:grid-cols-5">
        <div class="rounded bg-white p-4 shadow">
            <p class="text-sm text-slate-500">Глав на модерации</p>
            <p class="mt-1 text-2xl font-semibold">{{ $stats['chapters_pending'] }}</p>
        </div>
        <div class="rounded bg-white p-4 shadow">
            <p class="text-sm text-slate-500">Одобрено</p>
            <p class="mt-1 text-2xl font-semibold">{{ $stats['chapters_approved'] }}</p>
        </div>
        <div class="rounded bg-white p-4 shadow">
            <p class="text-sm text-slate-500">Отклонено</p>
            <p class="mt-1 text-2xl font-semibold">{{ $stats['chapters_rejected'] }}</p>
        </div>
        <div class="rounded bg-white p-4 shadow">
            <p class="text-sm text-slate-500">Открытые жалобы</p>
            <p class="mt-1 text-2xl font-semibold">{{ $stats['reports_open'] }}</p>
        </div>
        <div class="rounded bg-white p-4 shadow">
            <p class="text-sm text-slate-500">Пользователи</p>
            <p class="mt-1 text-2xl font-semibold">{{ $stats['users_total'] }}</p>
        </div>
    </div>

    <div class="grid gap-4 md:grid-cols-3">
        <a href="{{ route('admin.chapters.index') }}" class="rounded bg-white p-5 shadow hover:bg-slate-50">
            <p class="text-lg font-semibold">Модерация глав</p>
            <p class="mt-1 text-sm text-slate-600">Одобрение и отклонение глав переводчиков.</p>
        </a>
        <a href="{{ route('admin.reports.index') }}" class="rounded bg-white p-5 shadow hover:bg-slate-50">
            <p class="text-lg font-semibold">Жалобы</p>
            <p class="mt-1 text-sm text-slate-600">Просмотр и закрытие жалоб пользователей.</p>
        </a>
        <a href="{{ route('admin.users.index') }}" class="rounded bg-white p-5 shadow hover:bg-slate-50">
            <p class="text-lg font-semibold">Пользователи</p>
            <p class="mt-1 text-sm text-slate-600">Основная информация и роли пользователей.</p>
        </a>
        <a href="{{ route('statistics') }}" class="rounded bg-white p-5 shadow hover:bg-slate-50">
            <p class="text-lg font-semibold">Статистика</p>
            <p class="mt-1 text-sm text-slate-600">Статистика сайта</p>
        </a>
    </div>
</div>
@endsection
