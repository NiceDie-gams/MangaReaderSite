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
                    <div class="p-2 text-sm font-medium">{{ $title->title }}</div>
                </a>
            @endforeach
        </div>
        <div class="mt-4">{{ $titles->links() }}</div>
    </section>
</div>
@endsection
