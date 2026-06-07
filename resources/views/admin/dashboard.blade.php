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
                <path d="M7.734,0c0.334,0.184,1.469,0.938,1.469,2.297c0,1.203-1.941,4.791-1.188,5.722l4.812-6c0,0,1.197,1.456,0.478,2.688 c-0.747,1.281-2.588,2.781-3.325,3.897C9.524,9.294,9.746,9.663,9.746,9.663l5.834-3.194c0,0,0.469,1.391-0.641,2.391 c-1.172,1.056-3.931,1.475-3.931,2.575h4.006c0,0-0.409,1.216-1.184,1.384c-0.684,0.15-2.144-0.247-2.831,0.372 C10.271,13.847,9.456,16,7.015,16c-1.641,0-2.912-1.184-3.703-2.738c-0.913-1.797-1.938-2.663-2.991-2.881 c0,0,0.575-1.359,2.088-1.359c2.081,0,2.972,3.409,4.544,3.409c0.453,0,0.934-0.234,0.934-0.903c0-0.725-1.044-1.325-1.334-1.525 C5.862,9.522,5.309,8.756,5.665,7.472L7.734,0"/>
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
        <a href="{{ route('admin.titles.index') }}"
        class="action-card group relative overflow-hidden rounded bg-white p-5 shadow transition-all duration-300 hover:scale-105 hover:shadow-lg dark:bg-gray-800">
            <svg class="action-icon icon1 absolute w-12 h-12 opacity-10 dark:opacity-20" fill="currentColor" viewBox="0 0 24 24">
                <path d="M4 6H2v14c0 1.1.9 2 2 2h14v-2H4V6zm16-4H8c-1.1 0-2 .9-2 2v12c0 1.1.9 2 2 2h12c1.1 0 2-.9 2-2V4c0-1.1-.9-2-2-2zm-1 9H9V9h10v2zm-4 4H9v-2h6v2zm4-8H9V5h10v2z"/>
            </svg>
            <svg class="action-icon icon2 absolute w-16 h-16 opacity-10 dark:opacity-20" fill="currentColor" viewBox="0 0 25 25">
                <path d="M20.9999958,3.25 L20.9999958,19.5018874 C14.9999958,19.1144138 11.9999958,19.6137847 11.9999958,21 C11.9999958,21 11.9999958,9.95538748 11.9999958,5.61908 C11.9999958,3.25632105 14.9999958,2.46662772 20.9999958,3.25 Z M2.99999577,3.25 L2.99999577,19.5018874 L3.74965625,19.4572404 L3.74965625,19.4572404 L4.4667228,19.4222285 L4.4667228,19.4222285 L5.15119541,19.3968519 L5.15119541,19.3968519 L5.80307409,19.3811106 C5.90900437,19.37929 6.01357658,19.3778708 6.1167907,19.3768531 L6.71977848,19.3755647 L6.71977848,19.3755647 L7.29017232,19.3839114 L7.29017232,19.3839114 L7.82797223,19.4018935 L7.82797223,19.4018935 L8.33317821,19.4295108 C8.49614788,19.4403224 8.65368522,19.4527399 8.80579025,19.4667633 L9.24580836,19.5136511 C11.0113131,19.7290903 11.9280175,20.1954475 11.9959215,20.9127226 L11.9999958,20.9661174 L11.9999958,20.9661174 L11.9999958,5.61908 L11.9999958,5.61908 C11.9999958,3.69029719 10.0008288,2.809788 6.00249473,2.97755244 L5.38775087,3.01057916 C5.28279461,3.01739396 5.17658886,3.02486392 5.06913363,3.03298906 L4.40940852,3.08960194 C4.18450223,3.11109358 3.95459802,3.13520591 3.7196959,3.16193892 L2.99999577,3.25 L2.99999577,3.25 Z"/>
            </svg>
            <svg class="action-icon icon3 absolute w-10 h-10 opacity-10 dark:opacity-20" fill="currentColor" viewBox="0 0 21 21">
                <path d="M19 3H5c-1.1 0-2 .9-2 2v14c0 1.1.9 2 2 2h14c1.1 0 2-.9 2-2V5c0-1.1-.9-2-2-2zm0 16H5V5h14v14zM7 7h10v2H7V7zm0 4h10v2H7v-2zm0 4h7v2H7v-2z "/>
            </svg>
            <h3 class="text-lg font-semibold relative z-10">Манга</h3>
            <p class="mt-1 text-sm text-slate-600 dark:text-slate-400 relative z-10">Добавление, редактирование и удаление манги.</p>
        </a>
        <a href="{{ route('admin.banned-words.index') }}"
        class="action-card group relative overflow-hidden rounded bg-white p-5 shadow transition-all duration-300 hover:scale-105 hover:shadow-lg dark:bg-gray-800">
            <svg class="action-icon icon1 absolute w-12 h-12 opacity-10 dark:opacity-20" fill="currentColor" viewBox="0 0 24 24">
                <path d="M18 8h-1V6c0-2.76-2.24-5-5-5S7 3.24 7 6v2H6c-1.1 0-2 .9-2 2v10c0 1.1.9 2 2 2h12c1.1 0 2-.9 2-2V10c0-1.1-.9-2-2-2zm-6 9c-1.1 0-2-.9-2-2s.9-2 2-2 2 .9 2 2-.9 2-2 2zm3.1-9H8.9V6c0-1.71 1.39-3.1 3.1-3.1 1.71 0 3.1 1.39 3.1 3.1v2z"/>
            </svg>
            <svg class="action-icon icon2 absolute w-16 h-16 opacity-10 dark:opacity-20" fill="currentColor" viewBox="0 0 24 24">
                <path d="M12 2C6.48 2 2 6.48 2 12s4.48 10 10 10 10-4.48 10-10S17.52 2 12 2zm0 4c1.1 0 2 .9 2 2s-.9 2-2 2-2-.9-2-2 .9-2 2-2zm0 13c-2.33 0-4.31-1.46-5.11-3.5h10.22c-.8 2.04-2.78 3.5-5.11 3.5z"/>
            </svg>
            <svg class="action-icon icon3 absolute w-10 h-10 opacity-10 dark:opacity-20" fill="currentColor" viewBox="0 0 24 24">
                <path d="M12 2C6.48 2 2 6.48 2 12s4.48 10 10 10 10-4.48 10-10S17.52 2 12 2zm0 4c1.1 0 2 .9 2 2s-.9 2-2 2-2-.9-2-2 .9-2 2-2zm0 13c-2.33 0-4.31-1.46-5.11-3.5h10.22c-.8 2.04-2.78 3.5-5.11 3.5z"/>
            </svg>
            <h3 class="text-lg font-semibold relative z-10">Банворды</h3>
            <p class="mt-1 text-sm text-slate-600 dark:text-slate-400 relative z-10">Управление запрещёнными словами.</p>
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
