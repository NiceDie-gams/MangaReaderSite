{{-- resources/views/home.blade.php --}}
@extends('layouts.app')

@section('content')
<div class="grid gap-6 md:grid-cols-[250px_1fr]">
    <aside class="rounded bg-white p-4 shadow">
        <button id="filters-toggle" class="mb-3 w-full rounded bg-slate-200 px-3 py-2 md:hidden flex items-center justify-between">
            <span>Фильтры</span>
            <svg id="filters-chevron" class="w-4 h-4 transition-transform duration-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
            </svg>
        </button>
        <form id="filter-form" class="space-y-3">
            <input id="search-input" name="search" value="{{ $search }}" placeholder="Поиск..." class="w-full rounded border px-3 py-2">
            <div id="filters-panel"
                 class="space-y-3 overflow-y-auto transition-all duration-300 ease-in-out max-h-0 md:!max-h-full md:!overflow-visible"
                 style="scrollbar-width: thin; scrollbar-color: #94a3b8 #e2e8f0;">
                <div>
                    <p class="text-sm font-semibold">Теги</p>
                    @foreach($tags as $tag)
                        @php
                            $currentState = $selectedTags[$tag->id] ?? '';
                            $iconText = '';
                            if ($currentState === 'include') $iconText = '✓';
                            elseif ($currentState === 'exclude') $iconText = '−';
                        @endphp
                        <div class="tag-filter-item flex items-center gap-2 cursor-pointer py-0.5 select-none"
                             data-tag-id="{{ $tag->id }}"
                             data-state="{{ $currentState }}">
                            <span class="tag-state-icon inline-flex items-center justify-center w-5 h-5 border border-slate-400 rounded text-xs font-bold leading-none
                                {{ $currentState === 'include' ? 'bg-blue-600 text-white border-blue-600' : '' }}
                                {{ $currentState === 'exclude' ? 'bg-rose-600 text-white border-rose-600' : '' }}
                                {{ $currentState === '' ? 'bg-white text-transparent' : '' }}">
                                {{ $iconText }}
                            </span>
                            <span class="text-sm">{{ $tag->name }}</span>
                            <input type="hidden" name="tags[{{ $tag->id }}]" value="{{ $currentState }}">
                        </div>
                    @endforeach
                </div>
                <div class="flex gap-2">
                    <button class="rounded bg-blue-600 px-3 py-2 text-sm text-white" id="apply-filters">Применить</button>
                    <button class="rounded bg-slate-300 px-3 py-2 text-sm" id="clear-filters" type="button">Очистить</button>
                </div>
            </div>
        </form>
    </aside>

    <section>
        @auth
            @if(auth()->user()->isAdmin())
                <div class="mb-4 flex items-center justify-between">
                    <button
                        id="add-title-open-btn"
                        type="button"
                        class="rounded bg-emerald-600 px-4 py-2 text-sm font-medium text-white hover:bg-emerald-700"
                    >
                        Добавить мангу
                    </button>
                </div>

                <div id="add-title-modal" class="fixed inset-0 z-[10003] hidden items-center justify-center bg-slate-900/50 p-4">
                    <div class="w-full max-w-lg rounded-lg bg-white p-4 shadow-xl dark:bg-slate-800">
                        <div class="mb-3 flex items-center justify-between">
                            <h2 class="text-lg font-semibold">Добавить мангу</h2>
                            <button id="add-title-close-btn" type="button" class="text-slate-500 hover:text-slate-800 dark:hover:text-slate-200" aria-label="Закрыть">✕</button>
                        </div>

                        <form method="POST" action="{{ route('admin.titles.store') }}" enctype="multipart/form-data" class="space-y-3">
                            @csrf

                            <div>
                                <label for="title-name" class="mb-1 block text-sm text-slate-600 dark:text-slate-300">Название</label>
                                <input
                                    id="title-name"
                                    type="text"
                                    name="title"
                                    value="{{ old('title') }}"
                                    required
                                    maxlength="255"
                                    class="w-full rounded border px-3 py-2 dark:border-slate-600 dark:bg-slate-700"
                                >
                                @error('title') <p class="mt-1 text-sm text-rose-600">{{ $message }}</p> @enderror
                            </div>

                            <div>
                                <label for="title-description" class="mb-1 block text-sm text-slate-600 dark:text-slate-300">Описание</label>
                                <textarea
                                    id="title-description"
                                    name="description"
                                    rows="4"
                                    maxlength="5000"
                                    class="w-full rounded border px-3 py-2 dark:border-slate-600 dark:bg-slate-700"
                                >{{ old('description') }}</textarea>
                                @error('description') <p class="mt-1 text-sm text-rose-600">{{ $message }}</p> @enderror
                            </div>

                            <div>
                                <label for="title-cover" class="mb-1 block text-sm text-slate-600 dark:text-slate-300">Обложка</label>
                                <input
                                    id="title-cover"
                                    type="file"
                                    name="cover_image"
                                    accept="image/jpeg,image/png,image/jpg,image/gif,image/webp"
                                    required
                                    class="w-full rounded border px-3 py-2 dark:border-slate-600 dark:bg-slate-700"
                                >
                                @error('cover_image') <p class="mt-1 text-sm text-rose-600">{{ $message }}</p> @enderror
                            </div>

                            <div class="flex justify-end gap-2">
                                <button type="button" id="add-title-cancel-btn" class="rounded border px-4 py-2 text-sm">Отмена</button>
                                <button type="submit" class="rounded bg-emerald-600 px-4 py-2 text-sm text-white hover:bg-emerald-700">Сохранить</button>
                            </div>
                        </form>
                    </div>
                </div>
            @endif
        @endauth

        @if(session('success'))
            <div class="mb-4 rounded border border-emerald-300 bg-emerald-50 px-4 py-2 text-emerald-700">
                {{ session('success') }}
            </div>
        @endif

        <style>
            @keyframes fadeInUp {
                from {
                    opacity: 0;
                    transform: translateY(20px);
                }
                to {
                    opacity: 1;
                    transform: translateY(0);
                }
            }
        </style>
        <div class="titles-grid grid grid-cols-2 gap-4 md:grid-cols-3 lg:grid-cols-4">
            @foreach($titles as $title)
                <a href="{{ route('titles.show', $title) }}"
                class="group overflow-hidden rounded bg-white shadow transition-all duration-300 hover:scale-105 hover:shadow-lg"
                style="animation: fadeInUp 0.5s ease-out forwards; opacity: 0; animation-delay: {{ $loop->index * 0.1 }}s;">
                    <img src="{{ $title->cover_image }}" class="h-56 w-full object-cover transition-transform duration-300 group-hover:scale-105" alt="{{ $title->title }}">

                    <div class="p-2 text-sm font-medium flex items-center justify-between">
                        <span>{{ $title->title }}</span>
                        @if(auth()->check() && in_array($title->id, $favoriteIds))
                            <svg class="w-4 h-4 text-yellow-500 inline-block" fill="currentColor" viewBox="0 0 20 20">
                                <path d="M10 15l-5.878 3.09 1.123-6.545L.489 6.91l6.572-.955L10 0l2.939 5.955 6.572.955-4.756 4.635 1.123 6.545z"/>
                            </svg>
                        @endif
                    </div>
                </a>
            @endforeach
        </div>
        <div class="mt-4">{{ $titles->links() }}</div>
    </section>
</div>

@auth
    @if(auth()->user()->isAdmin())
        <script>
            (function () {
                const modal = document.getElementById('add-title-modal');
                const openBtn = document.getElementById('add-title-open-btn');
                const closeBtn = document.getElementById('add-title-close-btn');
                const cancelBtn = document.getElementById('add-title-cancel-btn');

                if (!modal || !openBtn) return;

                const openModal = () => {
                    modal.classList.remove('hidden');
                    modal.classList.add('flex');
                };

                const closeModal = () => {
                    modal.classList.add('hidden');
                    modal.classList.remove('flex');
                };

                openBtn.addEventListener('click', openModal);
                closeBtn?.addEventListener('click', closeModal);
                cancelBtn?.addEventListener('click', closeModal);

                modal.addEventListener('click', (event) => {
                    if (event.target === modal) closeModal();
                });

                @if($errors->has('title') || $errors->has('description') || $errors->has('cover_image'))
                    openModal();
                @endif
            })();
        </script>
    @endif
@endauth
@endsection
