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
<body class="min-h-screen bg-slate-100 text-slate-900 dark:bg-slate-900 dark:text-slate-100">
    <div id="page-loader" class="fixed inset-0 z-[10000] flex items-center justify-center bg-slate-900/50">
        <div class="flex flex-col items-center">
            <div class="h-14 w-14 animate-spin rounded-full border-4 border-white/30 border-t-white"></div>
            <p id="page-loader-message" class="mt-3 hidden text-sm font-medium text-white">Загрузка...</p>
        </div>
    </div>

    <header class="border-b border-slate-200 bg-white dark:border-slate-700 dark:bg-slate-800">
        <div class="mx-auto flex max-w-7xl items-center justify-between px-4 py-3">
            <a href="{{ route('home') }}" class="logo-link flex items-center gap-0 text-xl font-bold transition-all duration-500 ease-out">
                <span class="text-slate-800 dark:text-white">Manga</span>
                <span class="ml-0.5 rounded-md bg-transparent px-1.5 py-0.5 text-slate-800 transition-all duration-300 dark:bg-[#ff9000] dark:text-black">Reader</span>
            </a>
            <nav class="flex items-center gap-3 text-sm">
                <a href="{{ route('home') }}" class="hover:text-blue-600 dark:hover:text-blue-400">Главная</a>

                @auth
                    @if(auth()->user()->isTranslator() || auth()->user()->isAdmin())
                         <a href="{{ route('translator.dashboard') }}" class="hover:text-blue-600 dark:hover:text-blue-400">Переводчику</a>
                    @endif
                    @if(auth()->user()->isAdmin())
                        <a href="{{ route('admin.dashboard') }}" class="hover:text-blue-600 dark:hover:text-blue-400">Админка</a>
                    @endif
                    <a href="{{ route('users.show', auth()->user()) }}" class="hover:text-blue-600 dark:hover:text-blue-400">Профиль</a>
                    <form method="POST" action="{{ route('auth.logout') }}">
                        @csrf
                        <button type="submit" class="rounded bg-slate-800 px-3 py-1 text-white dark:bg-slate-700 dark:hover:bg-slate-600">Выход</button>
                    </form>
                @else
                    <a href="{{ route('auth.login') }}" class="rounded bg-slate-800 px-3 py-1 text-white dark:bg-slate-700 dark:hover:bg-slate-600">Вход</a>
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
            class="fixed bottom-4 left-1/2 z-[10002] -translate-x-1/2 rounded-full bg-slate-800 px-5 py-2 text-sm font-medium text-white shadow-lg hover:bg-slate-700 dark:bg-slate-700 dark:hover:bg-slate-600"
        >
            Жалоба
        </button>

        <div id="report-modal" class="fixed inset-0 z-[10003] hidden items-center justify-center bg-slate-900/50 p-4">
            <div class="w-full max-w-lg rounded-lg bg-white p-4 shadow-xl dark:bg-slate-800">
                <div class="mb-3 flex items-center justify-between">
                    <h2 class="text-lg font-semibold dark:text-white">Отправить жалобу</h2>
                    <button id="report-close-btn" type="button" class="text-slate-500 hover:text-slate-800 dark:text-slate-400 dark:hover:text-slate-200" aria-label="Закрыть">✕</button>
                </div>
                <form id="report-form" class="space-y-3" novalidate>
                    <div>
                        <label for="report-text" class="mb-1 block text-sm text-slate-600 dark:text-slate-300">Опишите проблему</label>
                        <textarea
                            id="report-text"
                            name="reportText"
                            class="w-full rounded border px-3 py-2 dark:border-slate-600 dark:bg-slate-700 dark:text-white"
                            rows="5"
                            minlength="10"
                            maxlength="2000"
                            required
                            placeholder="Не менее 10 символов"
                        ></textarea>
                        <p id="report-text-error" class="mt-1 hidden text-sm text-rose-600"></p>
                    </div>
                    <div class="flex justify-end gap-2">
                        <button type="button" id="report-cancel-btn" class="rounded border px-4 py-2 text-sm dark:border-slate-600 dark:text-slate-300">Отмена</button>
                        <button type="submit" class="rounded bg-blue-600 px-4 py-2 text-sm text-white hover:bg-blue-700">Отправить</button>
                    </div>
                </form>
            </div>
        </div>
    @endauth

    <div id="global-loader" class="fixed right-4 bottom-20 z-[10001] hidden rounded bg-slate-900 px-3 py-2 text-sm text-white dark:bg-slate-800">Загрузка...</div>
    <div id="global-toast" class="fixed left-1/2 top-4 z-[10004] hidden -translate-x-1/2 rounded px-4 py-2 text-sm text-white"></div>

    <script>
        (function() {
            function setTheme(theme) {
                if (theme === 'dark') {
                    document.documentElement.classList.add('dark');
                    localStorage.setItem('theme', 'dark');
                } else {
                    document.documentElement.classList.remove('dark');
                    localStorage.setItem('theme', 'light');
                }
                const sunIcon = document.getElementById('theme-icon-sun');
                const moonIcon = document.getElementById('theme-icon-moon');
                if (sunIcon && moonIcon) {
                    if (theme === 'dark') {
                        sunIcon.classList.add('hidden');
                        moonIcon.classList.remove('hidden');
                    } else {
                        sunIcon.classList.remove('hidden');
                        moonIcon.classList.add('hidden');
                    }
                }
            }

            const savedTheme = localStorage.getItem('theme');
            if (savedTheme) {
                setTheme(savedTheme);
            } else {
                const prefersDark = window.matchMedia('(prefers-color-scheme: dark)').matches;
                setTheme(prefersDark ? 'dark' : 'light');
            }

            const themeToggle = document.getElementById('theme-toggle');
            if (themeToggle) {
                themeToggle.addEventListener('click', () => {
                    const isDark = document.documentElement.classList.contains('dark');
                    setTheme(isDark ? 'light' : 'dark');
                });
            }
        })();

        window.addEventListener('load', function () {
            document.getElementById('page-loader')?.classList.add('hidden');
        });
    </script>
</body>
</html>
