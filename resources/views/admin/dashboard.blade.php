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
            <svg class="action-icon icon2 absolute w-16 h-16 opacity-10 dark:opacity-20" fill="currentColor" viewBox="0 0 512 512">
                <path d="m440.025,223.698c-13.599-7.852-28.138-13.216-43.081-16.295-1.385-2.571-2.965-5.011-4.483-7.496 4.821-14.368 7.543-29.688 7.543-45.66 0-57.201-33.905-108.996-86.38-131.948-16.203-7.07-35.053,0.313-42.139,16.499-7.078,16.187 0.305,35.053 16.499,42.139 26.058,11.396 43.774,35.6 47.282,63.278-23.398-13.415-50.415-21.201-79.266-21.201s-55.868,7.786-79.265,21.201c3.508-27.678 21.224-51.882 47.282-63.278 16.195-7.086 23.577-25.952 16.499-42.139-7.078-16.187-25.952-23.569-42.139-16.499-52.475,22.952-86.38,74.747-86.38,131.948 0,15.971 2.722,31.292 7.543,45.66-1.518,2.483-3.097,4.923-4.482,7.493-14.947,3.08-29.484,8.445-43.082,16.298-49.554,28.601-77.451,83.857-71.076,140.777 1.969,17.562 17.71,30.218 35.366,28.241 17.562-1.969 30.202-17.804 28.241-35.366-3.187-28.435 9.092-56.015 31.574-72.864 0.538,58.514 32.659,109.515 80.091,136.973-26.011,11.447-56.408,8.44-79.69-8.713-14.249-10.492-34.272-7.445-44.748,6.781-10.476,14.234-7.445,34.264 6.781,44.748 25.327,18.656 55.381,28.108 85.536,28.108 24.757,0 49.592-6.375 71.927-19.273 14.036-8.104 26.263-18.408 36.602-30.281 1.148,0.024 2.265,0.173 3.42,0.173 1.154,0 2.271-0.148 3.42-0.173 10.34,11.873 22.567,22.177 36.602,30.281 22.335,12.898 47.162,19.273 71.927,19.273 30.155,0 60.209-9.453 85.536-28.108 14.226-10.484 17.257-30.514 6.781-44.748-10.468-14.21-30.507-17.234-44.748-6.781-23.288,17.152-53.693,20.159-79.691,8.714 47.434-27.458 79.555-78.459 80.093-136.974 22.482,16.849 34.762,44.428 31.574,72.864-1.961,17.562 10.679,33.397 28.241,35.366 17.679,1.992 33.389-10.679 35.366-28.241 6.374-56.92-21.523-112.176-71.076-140.777zm-184.025-36.686c22.338,0 42.866,7.731 59.193,20.573-14.654,16.247-35.642,26.667-59.193,26.667s-44.539-10.42-59.193-26.667c16.327-12.842 36.855-20.573 59.193-20.573zm-34.749,182.095c-0.27,1.009-0.564,1.893-1.086,2.892-35.221-14.235-60.162-48.727-60.162-88.99 0-4.248 0.373-8.402 0.91-12.506 1.263,0.027 2.522,0.291 3.769,0.624 20.64,5.531 37.889,18.765 48.576,37.272 10.689,18.507 13.524,40.068 7.993,60.708zm34.749-54.099c-17.674,0-31.999-14.325-31.999-31.999 0-17.675 14.325-31.999 31.999-31.999s31.999,14.324 31.999,31.999c0,17.674-14.325,31.999-31.999,31.999zm35.835,56.991c-0.529-1.01-0.815-1.883-1.086-2.892-5.531-20.64-2.695-42.202 7.992-60.709 10.687-18.507 27.936-31.741 48.576-37.272 1.248-0.333 2.527-0.532 3.769-0.623 0.537,4.103 0.91,8.258 0.91,12.505 0.001,40.264-24.94,74.756-60.161,88.991z"/>
            </svg>
            <svg class="action-icon icon3 absolute w-10 h-10 opacity-10 dark:opacity-20" fill="currentColor" viewBox="0 0 24 24">
                <path d="M12 2C6.48 2 2 6.48 2 12s4.48 10 10 10 10-4.48 10-10S17.52 2 12 2zm0 4c1.1 0 2 .9 2 2s-.9 2-2 2-2-.9-2-2 .9-2 2-2zm0 13c-2.33 0-4.31-1.46-5.11-3.5h10.22c-.8 2.04-2.78 3.5-5.11 3.5z"/>
            </svg>
            <h3 class="text-lg font-semibold relative z-10">Банворды</h3>
            <p class="mt-1 text-sm text-slate-600 dark:text-slate-400 relative z-10">Управление запрещёнными словами.</p>
        </a>
        <a href="{{ route('admin.settings.index') }}"
        class="action-card group relative overflow-hidden rounded bg-white p-5 shadow transition-all duration-300 hover:scale-105 hover:shadow-lg dark:bg-gray-800">
            <svg class="action-icon icon1 absolute w-12 h-12 opacity-10 dark:opacity-20" fill="currentColor" viewBox="0 0 24 24">
                <path d="M19.14 12.94c.04-.3.06-.61.06-.94 0-.32-.02-.64-.07-.94l2.03-1.58c.18-.14.23-.41.12-.61l-1.92-3.32c-.12-.22-.37-.29-.59-.22l-2.39.96c-.5-.38-1.03-.7-1.62-.94l-.36-2.54c-.04-.24-.24-.41-.48-.41h-3.84c-.24 0-.43.17-.47.41l-.36 2.54c-.59.24-1.13.57-1.62.94l-2.39-.96c-.22-.08-.47 0-.59.22L2.74 8.87c-.12.21-.08.47.12.61l2.03 1.58c-.05.3-.09.63-.09.94 0 .31.02.64.07.94l-2.03 1.58c-.18.14-.23.41-.12.61l1.92 3.32c.12.22.37.29.59.22l2.39-.96c.5.38 1.03.7 1.62.94l.36 2.54c.05.24.24.41.48.41h3.84c.24 0 .44-.17.47-.41l.36-2.54c.59-.24 1.13-.57 1.62-.94l2.39.96c.22.08.47 0 .59-.22l1.92-3.32c.12-.22.07-.47-.12-.61l-2.01-1.58zM12 15.6c-2 0-3.6-1.6-3.6-3.6s1.6-3.6 3.6-3.6 3.6 1.6 3.6 3.6-1.6 3.6-3.6 3.6z"/>
            </svg>
            <svg class="action-icon icon2 absolute w-16 h-16 opacity-10 dark:opacity-20" fill="currentColor" viewBox="0 0 24 24">
                <path d="M2.464 6.748c.06.942.45 1.865 1.164 2.578a3.997 3.997 0 0 0 3.866 1.036l1.114-.298 9.162 9.161a1 1 0 0 0 1.414-1.414L10.022 8.65l.298-1.115A3.997 3.997 0 0 0 9.284 3.67a3.995 3.995 0 0 0-2.578-1.164L7.93 3.728A3 3 0 1 1 3.686 7.97L2.464 6.748zm-.9-3.727L5.1 6.556a1 1 0 0 0 1.415-1.414L2.979 1.606a6.002 6.002 0 0 1 9.273 6.445l8.346 8.346a3 3 0 0 1-4.243 4.243L8.01 12.294A6.002 6.002 0 0 1 1.565 3.02zm15.5 15.496l1.42-1.41-1.42-1.414-1.419 1.414 1.418 1.41z"/>
            </svg>
            <svg class="action-icon icon3 absolute w-10 h-10 opacity-10 dark:opacity-20" fill="currentColor" viewBox="0 0 24 24">
                <path d="M20 8h-2.81c-.45-.78-1.07-1.45-1.82-1.96L17 4.41 15.59 3l-2.17 2.17C12.96 5.06 12.49 5 12 5s-.96.06-1.41.17L8.41 3 7 4.41l1.62 1.63c-.75.51-1.37 1.18-1.82 1.96H4v2h2.09c-.05.33-.09.66-.09 1v1H4v2h2v1c0 .34.04.67.09 1H4v2h2.81c1.04 1.79 2.97 3 5.19 3s4.15-1.21 5.19-3H20v-2h-2.09c.05-.33.09-.66.09-1v-1h2v-2h-2v-1c0-.34-.04-.67-.09-1H20V8z"/>
            </svg>
            <h3 class="text-lg font-semibold relative z-10">Настройки</h3>
            <p class="mt-1 text-sm text-slate-600 dark:text-slate-400 relative z-10">Глобальные настройки сайта.</p>
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
