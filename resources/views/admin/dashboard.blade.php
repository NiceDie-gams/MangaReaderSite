@extends('layouts.app')

@section('content')
<div class="space-y-6">
    <div>
        <h1 class="text-2xl font-bold">Панель администратора</h1>
        <p class="text-sm text-slate-600 dark:text-slate-400">Управление главами, жалобами и пользователями.</p>
    </div>

    @if(session('success'))
        <div class="rounded border border-emerald-300 bg-emerald-50 px-4 py-2 text-emerald-700 dark:bg-emerald-950 dark:text-emerald-300">
            {{ session('success') }}
        </div>
    @endif

    {{-- Блок статистики --}}
    <div class="grid gap-4 sm:grid-cols-2 lg:grid-cols-5">
        <div class="relative overflow-hidden rounded bg-white p-4 shadow dark:bg-gray-800">
            <p class="text-sm text-slate-500 dark:text-slate-400">Глав на модерации</p>
            <p class="mt-1 text-2xl font-semibold">{{ $stats['chapters_pending'] }}</p>
        </div>
        <div class="relative overflow-hidden rounded bg-white p-4 shadow dark:bg-gray-800">
            <p class="text-sm text-slate-500 dark:text-slate-400">Одобрено</p>
            <p class="mt-1 text-2xl font-semibold">{{ $stats['chapters_approved'] }}</p>
        </div>
        <div class="relative overflow-hidden rounded bg-white p-4 shadow dark:bg-gray-800">
            <p class="text-sm text-slate-500 dark:text-slate-400">Отклонено</p>
            <p class="mt-1 text-2xl font-semibold">{{ $stats['chapters_rejected'] }}</p>
        </div>
        <div class="relative overflow-hidden rounded bg-white p-4 shadow dark:bg-gray-800">
            <p class="text-sm text-slate-500 dark:text-slate-400">Открытые жалобы</p>
            <p class="mt-1 text-2xl font-semibold">{{ $stats['reports_open'] }}</p>
        </div>
        <div class="relative overflow-hidden rounded bg-white p-4 shadow dark:bg-gray-800">
            <p class="text-sm text-slate-500 dark:text-slate-400">Пользователи</p>
            <p class="mt-1 text-2xl font-semibold">{{ $stats['users_total'] }}</p>
        </div>
    </div>

    {{-- Карточки действий с фоновыми иконками --}}
    <div class="grid gap-4 md:grid-cols-2 lg:grid-cols-4">
        <a href="{{ route('admin.chapters.index') }}"
           class="action-card group relative overflow-hidden rounded bg-white p-5 shadow transition-all duration-300 hover:scale-105 hover:shadow-lg dark:bg-gray-800">
            <svg class="action-icon icon1 absolute w-12 h-12 opacity-10 dark:opacity-20" fill="currentColor" viewBox="0 0 24 24">
                <path d="M4 6h16v2H4V6zm2-4h12v2H6V2zm16 8H2v12h20V10zm-2 10H4v-8h16v8z"/>
            </svg>
            <svg class="action-icon icon2 absolute w-16 h-16 opacity-10 dark:opacity-20" fill="currentColor" viewBox="0 0 24 24">
                <path d="M19 3H5c-1.1 0-2 .9-2 2v14c0 1.1.9 2 2 2h14c1.1 0 2-.9 2-2V5c0-1.1-.9-2-2-2zm0 16H5V5h14v14zM7 7h10v2H7V7zm0 4h10v2H7v-2zm0 4h7v2H7v-2z"/>
            </svg>
            <svg class="action-icon icon3 absolute w-10 h-10 opacity-10 dark:opacity-20" fill="currentColor" viewBox="0 0 24 24">
                <path d="M20 8h-2.81c-.45-.78-1.07-1.45-1.82-1.96L17 4.41 15.59 3l-2.17 2.17C12.96 5.06 12.49 5 12 5s-.96.06-1.41.17L8.41 3 7 4.41l1.62 1.63c-.75.51-1.37 1.18-1.82 1.96H4v2h2.09c-.05.33-.09.66-.09 1v1H4v2h2v1c0 .34.04.67.09 1H4v2h2.81c1.04 1.79 2.97 3 5.19 3s4.15-1.21 5.19-3H20v-2h-2.09c.05-.33.09-.66.09-1v-1h2v-2h-2v-1c0-.34-.04-.67-.09-1H20V8zm-4 4c0 2.21-1.79 4-4 4s-4-1.79-4-4 1.79-4 4-4 4 1.79 4 4z"/>
            </svg>
            <h3 class="text-lg font-semibold relative z-10">Модерация глав</h3>
            <p class="mt-1 text-sm text-slate-600 dark:text-slate-400 relative z-10">Одобрение и отклонение глав переводчиков.</p>
        </a>

        <a href="{{ route('admin.reports.index') }}"
           class="action-card group relative overflow-hidden rounded bg-white p-5 shadow transition-all duration-300 hover:scale-105 hover:shadow-lg dark:bg-gray-800">
            <svg class="action-icon icon1 absolute w-12 h-12 opacity-10 dark:opacity-20" fill="currentColor" viewBox="0 0 24 24">
                <path d="M12 2C6.48 2 2 6.48 2 12s4.48 10 10 10 10-4.48 10-10S17.52 2 12 2zm1 15h-2v-2h2v2zm0-4h-2V7h2v6z"/>
            </svg>
            <svg class="action-icon icon2 absolute w-16 h-16 opacity-10 dark:opacity-20" fill="currentColor" viewBox="0 0 24 24">
                <path d="M20 8h-2.81c-.45-.78-1.07-1.45-1.82-1.96L17 4.41 15.59 3l-2.17 2.17C12.96 5.06 12.49 5 12 5s-.96.06-1.41.17L8.41 3 7 4.41l1.62 1.63c-.75.51-1.37 1.18-1.82 1.96H4v2h2.09c-.05.33-.09.66-.09 1v1H4v2h2v1c0 .34.04.67.09 1H4v2h2.81c1.04 1.79 2.97 3 5.19 3s4.15-1.21 5.19-3H20v-2h-2.09c.05-.33.09-.66.09-1v-1h2v-2h-2v-1c0-.34-.04-.67-.09-1H20V8z"/>
            </svg>
            <svg class="action-icon icon3 absolute w-10 h-10 opacity-10 dark:opacity-20" fill="currentColor" viewBox="0 0 24 24">
                <path d="M12 2C6.48 2 2 6.48 2 12s4.48 10 10 10 10-4.48 10-10S17.52 2 12 2zm0 4c1.1 0 2 .9 2 2s-.9 2-2 2-2-.9-2-2 .9-2 2-2zm0 13c-2.33 0-4.31-1.46-5.11-3.5h10.22c-.8 2.04-2.78 3.5-5.11 3.5z"/>
            </svg>
            <h3 class="text-lg font-semibold relative z-10">Жалобы</h3>
            <p class="mt-1 text-sm text-slate-600 dark:text-slate-400 relative z-10">Просмотр и закрытие жалоб пользователей.</p>
        </a>

        <a href="{{ route('admin.users.index') }}"
           class="action-card group relative overflow-hidden rounded bg-white p-5 shadow transition-all duration-300 hover:scale-105 hover:shadow-lg dark:bg-gray-800">
            <svg class="action-icon icon1 absolute w-12 h-12 opacity-10 dark:opacity-20" fill="currentColor" viewBox="0 0 24 24">
                <path d="M12 12c2.21 0 4-1.79 4-4s-1.79-4-4-4-4 1.79-4 4 1.79 4 4 4zm0 2c-2.67 0-8 1.34-8 4v2h16v-2c0-2.66-5.33-4-8-4z"/>
            </svg>
            <svg class="action-icon icon2 absolute w-16 h-16 opacity-10 dark:opacity-20" fill="currentColor" viewBox="0 0 24 24">
                <path d="M16 11c1.66 0 2.99-1.34 2.99-3S17.66 5 16 5c-1.66 0-3 1.34-3 3s1.34 3 3 3zm-8 0c1.66 0 2.99-1.34 2.99-3S9.66 5 8 5C6.34 5 5 6.34 5 8s1.34 3 3 3zm0 2c-2.33 0-7 1.17-7 3.5V17h14v-2.5c0-2.33-4.67-3.5-7-3.5zm8 0c-.29 0-.62.02-1 .05 1.16.84 2 1.87 2 3.45V17h6v-2.5c0-2.33-4.67-3.5-7-3.5z"/>
            </svg>
            <svg class="action-icon icon3 absolute w-10 h-10 opacity-10 dark:opacity-20" fill="currentColor" viewBox="0 0 24 24">
                <path d="M12 4C7.58 4 4 7.58 4 12s3.58 8 8 8 8-3.58 8-8-3.58-8-8-8zm0 14c-3.31 0-6-2.69-6-6s2.69-6 6-6 6 2.69 6 6-2.69 6-6 6z"/>
            </svg>
            <h3 class="text-lg font-semibold relative z-10">Пользователи</h3>
            <p class="mt-1 text-sm text-slate-600 dark:text-slate-400 relative z-10">Основная информация и роли пользователей.</p>
        </a>

        <a href="{{ route('statistics') }}"
           class="action-card group relative overflow-hidden rounded bg-white p-5 shadow transition-all duration-300 hover:scale-105 hover:shadow-lg dark:bg-gray-800">
            <svg class="action-icon icon1 absolute w-12 h-12 opacity-10 dark:opacity-20" fill="currentColor" viewBox="0 0 24 24">
                <path d="M3 13h8V3H3v10zm0 8h8v-6H3v6zm10 0h8V11h-8v10zm0-18v6h8V3h-8z"/>
            </svg>
            <svg class="action-icon icon2 absolute w-16 h-16 opacity-10 dark:opacity-20" fill="currentColor" viewBox="0 0 24 24">
                <path d="M19 3H5c-1.1 0-2 .9-2 2v14c0 1.1.9 2 2 2h14c1.1 0 2-.9 2-2V5c0-1.1-.9-2-2-2zm0 16H5V5h14v14zM7 10h10v2H7v-2zm0 4h7v2H7v-2z"/>
            </svg>
            <svg class="action-icon icon3 absolute w-10 h-10 opacity-10 dark:opacity-20" fill="currentColor" viewBox="0 0 24 24">
                <path d="M9 17H7v-7h2v7zm4 0h-2V7h2v10zm4 0h-2v-4h2v4zm2 2H5V5h14v14zm0-16H5c-1.1 0-2 .9-2 2v14c0 1.1.9 2 2 2h14c1.1 0 2-.9 2-2V5c0-1.1-.9-2-2-2z"/>
            </svg>
            <h3 class="text-lg font-semibold relative z-10">Статистика</h3>
            <p class="mt-1 text-sm text-slate-600 dark:text-slate-400 relative z-10">Статистика сайта.</p>
        </a>
    </div>
</div>

<style>

    .action-card {
        position: relative;
        overflow: hidden;
    }
    .action-icon {
        transition: transform 0.3s ease;
        pointer-events: none;
    }
    .action-card:hover .icon1 {
        transform: rotate(10deg) scale(1.05);
    }
    .action-card:hover .icon2 {
        transform: rotate(-15deg) scale(1.1);
    }
    .action-card:hover .icon3 {
        transform: rotate(5deg) scale(1.05);
    }

    .action-card .icon1 { top: 10%; left: 5%; }
    .action-card .icon2 { top: 60%; left: 80%; }
    .action-card .icon3 { top: 80%; left: 15%; }

    .action-card:nth-child(2) .icon1 { top: 20%; left: 15%; }
    .action-card:nth-child(2) .icon2 { top: 70%; left: 70%; }
    .action-card:nth-child(3) .icon1 { top: 15%; left: 10%; }
    .action-card:nth-child(3) .icon2 { top: 50%; left: 85%; }
    .action-card:nth-child(4) .icon1 { top: 25%; left: 5%; }
    .action-card:nth-child(4) .icon2 { top: 65%; left: 75%; }
    .dark .action-icon {
        opacity: 0.2 !important;
    }
</style>
@endsection
