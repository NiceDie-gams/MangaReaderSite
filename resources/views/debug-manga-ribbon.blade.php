@extends('layouts.app')

@section('content')
<div class="space-y-4">
    <div class="rounded bg-white p-4 shadow">
        <h1 class="text-lg font-semibold">Debug ribbon: {{ $title->title }}</h1>
        <p class="text-sm text-slate-600">
            Глава #{{ $chapter->chapter_number }} | Страниц: {{ $pages->count() }}
        </p>
    </div>

    <div class="space-y-6">
        @forelse ($pages as $page)
            <div class="rounded bg-white p-2 shadow">
                <div class="mb-2 text-sm font-medium text-slate-700">
                    Страница {{ $page->page_number }}
                </div>
                <img
                    src="{{ $page->image_path }}"
                    alt="Page {{ $page->page_number }}"
                    class="mx-auto w-full max-w-4xl object-contain"
                    loading="lazy"
                >
            </div>
        @empty
            <div class="rounded bg-amber-50 p-4 text-amber-700">
                У этой главы нет страниц.
            </div>
        @endforelse
    </div>
</div>
@endsection
