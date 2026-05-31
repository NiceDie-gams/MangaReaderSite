@extends('layouts.app')

@section('content')
<div class="rounded bg-white p-6 shadow">
    <h1 class="mb-6 text-2xl font-bold">Панель переводчика</h1>

    <div class="grid grid-cols-1 gap-4 md:grid-cols-4">
        <div class="rounded bg-blue-100 p-4 text-center">
            <div class="text-2xl font-bold">{{ $stats['total'] }}</div>
            <div class="text-sm">Всего глав</div>
        </div>
        <div class="rounded bg-yellow-100 p-4 text-center">
            <div class="text-2xl font-bold">{{ $stats['pending'] }}</div>
            <div class="text-sm">На модерации</div>
        </div>
        <div class="rounded bg-green-100 p-4 text-center">
            <div class="text-2xl font-bold">{{ $stats['approved'] }}</div>
            <div class="text-sm">Одобрено</div>
        </div>
        <div class="rounded bg-red-100 p-4 text-center">
            <div class="text-2xl font-bold">{{ $stats['rejected'] }}</div>
            <div class="text-sm">Отклонено</div>
        </div>
    </div>

    <div class="mt-6 text-center">
        <a href="{{ route('translator.chapters.create') }}" class="rounded bg-blue-600 px-4 py-2 text-white hover:bg-blue-700">
            + Добавить новую главу
        </a>
    </div>
</div>
@endsection
