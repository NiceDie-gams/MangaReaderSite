<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta name="theme-color" content="#0f172a">
    <meta name="apple-mobile-web-app-capable" content="yes">
    <meta name="apple-mobile-web-app-status-bar-style" content="black-translucent">
    <meta name="apple-mobile-web-app-title" content="MangaReader">
    <link rel="manifest" href="/manifest.webmanifest">
    <link rel="apple-touch-icon" href="/icons/icon-192.png">
    <title>{{ config('app.name', 'MangaReader') }}</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="min-h-screen bg-slate-100 text-slate-900">
    <div id="page-loader" class="fixed inset-0 z-[10000] flex items-center justify-center bg-slate-900/50">
        <div class="flex flex-col items-center">
            <div class="h-14 w-14 animate-spin rounded-full border-4 border-white/30 border-t-white"></div>
            <p id="page-loader-message" class="mt-3 hidden text-sm font-medium text-white">Загрузка...</p>
        </div>
    </div>

    <header class="bg-white border-b border-slate-200">
        <div class="mx-auto flex max-w-7xl items-center justify-between px-4 py-3">
            <a href="{{ route('home') }}" class="text-xl font-bold">MangaReader</a>
            <nav class="flex items-center gap-3 text-sm">
                <a href="{{ route('home') }}" class="hover:text-blue-600">Главная</a>
                <!-- <button id="install-app-btn" type="button" class="hidden rounded bg-blue-600 px-3 py-1 text-white">Установить</button> -->
                @auth
                    <a href="{{ route('users.show', auth()->user()) }}" class="hover:text-blue-600">Профиль</a>
                    <form method="POST" action="{{ route('auth.logout') }}">
                        @csrf
                        <button type="submit" class="rounded bg-slate-800 px-3 py-1 text-white">Выход</button>
                    </form>
                @else
                    <a href="{{ route('auth.login') }}" class="rounded bg-slate-800 px-3 py-1 text-white">Вход</a>
                @endauth
            </nav>
        </div>
    </header>

    <main class="mx-auto max-w-7xl px-4 py-6">
        @yield('content')
    </main>

    @auth
        <button
            id="report-open-btn"
            type="button"
            class="fixed bottom-4 left-1/2 z-[10002] -translate-x-1/2 rounded-full bg-slate-800 px-5 py-2 text-sm font-medium text-white shadow-lg hover:bg-slate-700"
        >
            Жалоба
        </button>

        <div id="report-modal" class="fixed inset-0 z-[10003] hidden items-center justify-center bg-slate-900/50 p-4">
            <div class="w-full max-w-lg rounded-lg bg-white p-4 shadow-xl">
                <div class="mb-3 flex items-center justify-between">
                    <h2 class="text-lg font-semibold">Отправить жалобу</h2>
                    <button id="report-close-btn" type="button" class="text-slate-500 hover:text-slate-800" aria-label="Закрыть">✕</button>
                </div>
                <form id="report-form" class="space-y-3" novalidate>
                    <div>
                        <label for="report-text" class="mb-1 block text-sm text-slate-600">Опишите проблему</label>
                        <textarea
                            id="report-text"
                            name="reportText"
                            class="w-full rounded border px-3 py-2"
                            rows="5"
                            minlength="10"
                            maxlength="2000"
                            required
                            placeholder="Не менее 10 символов"
                        ></textarea>
                        <p id="report-text-error" class="mt-1 hidden text-sm text-rose-600"></p>
                    </div>
                    <div class="flex justify-end gap-2">
                        <button type="button" id="report-cancel-btn" class="rounded border px-4 py-2 text-sm">Отмена</button>
                        <button type="submit" class="rounded bg-blue-600 px-4 py-2 text-sm text-white">Отправить</button>
                    </div>
                </form>
            </div>
        </div>
    @endauth

    <div id="global-loader" class="fixed right-4 bottom-20 z-[10001] hidden rounded bg-slate-900 px-3 py-2 text-sm text-white">Загрузка...</div>
    <div id="global-toast" class="fixed left-1/2 top-4 z-[10004] hidden -translate-x-1/2 rounded px-4 py-2 text-sm text-white"></div>
    <script>
        window.addEventListener('load', function () {
            document.getElementById('page-loader')?.classList.add('hidden');
        });
    </script>
</body>
</html>
