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
    <x-head.tinymce-config />
</head>
<body class="min-h-screen bg-slate-100 text-slate-900 dark:bg-slate-900 dark:text-slate-100">
    <div id="page-loader" class="fixed inset-0 z-[10000] flex items-center justify-center bg-slate-900/50">
        <div class="loader-container">
            <svg class="gear" width="48" height="48" viewBox="0 0 16 16" fill="none" xmlns="http://www.w3.org/2000/svg">
                <path d="M9.1 4.4L8.6 2H7.4l-.5 2.4-.7.3-2-1.3-.9.8 1.3 2-.2.7-2.4.5v1.2l2.4.5.3.8-1.3 2 .8.8 2-1.3.8.3.4 2.3h1.2l.5-2.4.8-.3 2 1.3.8-.8-1.3-2 .3-.8 2.3-.4V7.4l-2.4-.5-.3-.8 1.3-2-.8-.8-2 1.3-.7-.2zM9.4 1l.5 2.4L12 2.1l2 2-1.4 2.1 2.4.4v2.8l-2.4.5L14 12l-2 2-2.1-1.4-.5 2.4H6.6l-.5-2.4L4 13.9l-2-2 1.4-2.1L1 9.4V6.6l2.4-.5L2.1 4l2-2 2.1 1.4.4-2.4h2.8zm.6 7c0 1.1-.9 2-2 2s-2-.9-2-2 .9-2 2-2 2 .9 2 2zM8 9c.6 0 1-.4 1-1s-.4-1-1-1-1 .4-1 1 .4 1 1 1z"
                    fill="currentColor" stroke="currentColor" stroke-width="0.5"/>
            </svg>

            <svg class="wrench" width="40" height="40" viewBox="0 0 488.535 488.535" xmlns="http://www.w3.org/2000/svg">
                <path d="M488.21,214.864L463.863,98.924l-84.165-84.156c-0.063-0.055-0.129-0.072-0.191-0.127l-0.352-0.351l-0.064,0.071
                    c-5.934-5.384-15.075-5.329-20.805,0.407l-49.492,49.478c-5.728,5.736-5.775,14.87-0.4,20.821l-51.727,51.719
                    c-5.934-5.378-15.078-5.321-20.788,0.414l-49.495,49.469c-5.729,5.736-5.774,14.863-0.414,20.814L17.531,375.949
                    c-23.374,23.382-23.374,61.284,0,84.667c11.217,11.224,26.437,17.527,42.313,17.527s31.097-6.303,42.313-17.527l168.377-168.369
                    v0.009l115.948,24.356c5.011,1.052,10.212-0.503,13.833-4.117l5.424-5.433c5.92-5.919,5.904-15.509,0-21.427l-64.3-64.3
                    l51.503-51.511l64.301,64.3c5.918,5.911,15.508,5.919,21.413,0.008l5.44-5.433C487.716,225.075,489.265,219.873,488.21,214.864z
                    M80.76,436.115c-10.706,10.714-28.082,10.707-38.786-0.008c-10.674-10.697-10.674-28.056,0-38.763
                    c10.704-10.706,28.08-10.706,38.786-0.008C91.483,408.051,91.483,425.409,80.76,436.115z"
                    fill="currentColor"/>
            </svg>

            <div class="spark spark"></div>
        </div>
    </div>

    <header class="border-b border-slate-200 bg-white dark:border-slate-700 dark:bg-slate-800">
        <div class="mx-auto flex max-w-7xl items-center justify-between px-4 py-3">

            <a href="{{ route('home') }}" class="logo-link flex items-center gap-0 text-xl font-bold transition-all duration-500 ease-out">
                <span class="text-slate-800 dark:text-white">Manga</span>
                <span class="ml-0.5 rounded-md bg-transparent px-1.5 py-0.5 text-slate-800 transition-all duration-300 dark:bg-[#ff9000] dark:text-black">Reader</span>
            </a>


            <button id="burger-btn" class="block rounded p-1 text-slate-800 hover:bg-slate-100 dark:text-white dark:hover:bg-slate-700 lg:hidden" aria-label="Меню">
                <svg class="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
                </svg>
            </button>


            <nav class="hidden items-center gap-3 text-sm lg:flex">
                <a href="{{ route('home') }}" class="hover:text-blue-600 dark:hover:text-blue-400">Главная</a>
                <a href="{{ route('about') }}" class="hover:text-blue-600 dark:hover:text-blue-400">О нас</a>
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


        <div id="mobile-menu" class="hidden border-t border-slate-200 bg-white px-4 py-3 dark:border-slate-700 dark:bg-slate-800 lg:hidden">
            <nav class="flex flex-col gap-3 text-sm">
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
            class="fixed bottom-4 left-1 ml-20 z-[10002] -translate-x-1/2 rounded-full bg-slate-800 px-5 py-2 text-sm font-medium text-white shadow-lg hover:bg-slate-700 dark:bg-slate-700 dark:hover:bg-slate-600"
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

    <div id="global-loader" class="fixed right-4 bottom-20 z-[10001] hidden rounded bg-slate-900 px-3 py-2 text-sm text-white dark:bg-slate-800">
    <div class="loader-animation">
        <svg class="gear" viewBox="0 0 100 100" width="24" height="24">
        <path fill="currentColor" d="M50,30a20,20 0 1,0 0,40 20,20 0 0,0 0-40zm0,10a10,10 0 1,1 0,20 10,10 0 0,1 0-20z"/>
        <path fill="currentColor" d="M50,15 L50,25 M50,75 L50,85 M15,50 L25,50 M75,50 L85,50 M27,27 L34,34 M66,66 L73,73 M27,73 L34,66 M66,34 L73,27"/>
        <circle cx="50" cy="50" r="6" fill="white"/>
        </svg>
        <svg class="wrench" viewBox="0 0 100 100" width="20" height="20">
        <path fill="currentColor" d="M70,25 L75,30 L55,50 L50,45 Z M45,55 L50,60 L30,80 L25,75 Z M80,20 L75,15 L55,35 L60,40 Z" />
        <rect x="40" y="58" width="12" height="8" transform="rotate(45 46 62)" fill="currentColor" />
        <circle cx="70" cy="30" r="5" fill="none" stroke="currentColor" stroke-width="3" />
        </svg>
    </div>
    </div>
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
        const burgerBtn = document.getElementById('burger-btn');
        const mobileMenu = document.getElementById('mobile-menu');

        if (burgerBtn && mobileMenu) {
            burgerBtn.addEventListener('click', () => {
                mobileMenu.classList.toggle('hidden');
                const svg = burgerBtn.querySelector('svg');
                if (svg) {
                    if (!mobileMenu.classList.contains('hidden')) {
                        svg.innerHTML = '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />';
                    } else {
                        svg.innerHTML = '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />';
                    }
                }
            });
        }
    </script>
</body>
</html>
