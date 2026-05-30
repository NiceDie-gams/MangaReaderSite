{{-- resources/views/chapter-show.blade.php --}}
@extends('layouts.app')

@section('content')
<div class="space-y-4">
    <div id="chapter-image-container" class="rounded bg-black cursor-pointer">
        <img
            id="chapter-image"
            data-chapter-id="{{ $chapter->id }}"
            data-title-slug="{{ $title->slug }}"
            data-page="{{ $firstPage?->page_number ?? 1 }}"
            src="{{ $firstPage?->image_path }}"
            class="mx-auto  object-contain"
            alt="chapter page"
        >
    </div>

    {{-- Навигация по главам --}}
    <div class="flex justify-between items-center px-4 py-3 bg-white rounded shadow">
        @if($prevChapter)
            <a href="{{ route('chapters.show', [$title, $prevChapter]) }}" class="inline-flex items-center gap-1 px-3 py-2 bg-slate-200 rounded hover:bg-slate-300 text-sm font-medium">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/></svg>
                Глава {{ $prevChapter->chapter_number }}
            </a>
        @else
            <a href="{{ route('titles.show', $title) }}" class="inline-flex items-center gap-1 px-3 py-2 bg-slate-200 rounded hover:bg-slate-300 text-sm font-medium">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/></svg>
                К описанию
            </a>
        @endif

        <span class="text-sm font-medium">Глава {{ $chapter->chapter_number }}</span>

        @if($nextChapter)
            <a href="{{ route('chapters.show', [$title, $nextChapter]) }}" class="inline-flex items-center gap-1 px-3 py-2 bg-slate-200 rounded hover:bg-slate-300 text-sm font-medium">
                Глава {{ $nextChapter->chapter_number }}
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
            </a>
        @else
            <a href="{{ route('titles.show', $title) }}" class="inline-flex items-center gap-1 px-3 py-2 bg-slate-200 rounded hover:bg-slate-300 text-sm font-medium">
                К описанию
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
            </a>
        @endif
    </div>
</div>
@endsection
