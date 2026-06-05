@extends('layouts.app')

@section('content')
<div class="space-y-4">
    <div class="flex items-center justify-between">
        <h1 class="text-2xl font-bold">Манга</h1>
        <a href="{{ route('admin.dashboard') }}" class="text-sm text-blue-600 hover:underline">Назад в админ-панель</a>
    </div>

    @if(session('success'))
        <div class="rounded border border-emerald-300 bg-emerald-50 px-4 py-2 text-emerald-700">
            {{ session('success') }}
        </div>
    @endif

    @if($errors->any())
        <div class="rounded border border-rose-300 bg-rose-50 px-4 py-2 text-rose-700">
            {{ $errors->first() }}
        </div>
    @endif

    <div class="overflow-x-auto rounded bg-white shadow">
        <table class="min-w-full text-sm">
            <thead class="bg-slate-100 text-left">
                <tr>
                    <th class="px-3 py-2">ID</th>
                    <th class="px-3 py-2">Тайтл</th>
                    <th class="px-3 py-2">Описание</th>
                    <th class="px-3 py-2">Обновить</th>
                    <th class="px-3 py-2">Удалить</th>
                </tr>
            </thead>
            <tbody>
                @forelse($titles as $title)
                    <tr class="border-t">
                        <td class="px-3 py-2">{{ $title->id }}</td>
                        <td class="px-3 py-2">{{ $title->title}}</td>
                        <td class="px-3 py-2">{{ $title->description }}</td>
                        <td class="px-3 py-2">
                            <div class="flex flex-col gap-2">    
                                <button id="add-title-open-btn" class="w-full rounded bg-yellow-400 px-3 py-1 text-white hover:bg-rose-700">
                                    Обновить
                                </button>
                                <div id="add-title-modal" class="fixed inset-0 z-[10003] hidden items-center justify-center bg-slate-900/50 p-4">
                                    <div class="w-full max-w-lg rounded-lg bg-white p-4 shadow-xl dark:bg-slate-800">
                                        <div class="mb-3 flex items-center justify-between">
                                            <h2 class="text-lg font-semibold">Добавить мангу</h2>
                                            <button id="add-title-close-btn" type="button" class="text-slate-500 hover:text-slate-800 dark:hover:text-slate-200" aria-label="Закрыть">✕</button>
                                        </div>

                                        <form method="POST" action="{{ route('admin.titles.update') }}" enctype="multipart/form-data" class="space-y-3">
                                            @csrf
                                            @method('PATCH')
                                            <input type="hidden" name="id" value="{{ $title->id }}">
                                            <div>
                                                <label for="title-name" class="mb-1 block text-sm text-slate-600 dark:text-slate-300">Название</label>
                                                <input
                                                    id="title-name"
                                                    type="text"
                                                    name="title"
                                                    value="{{ $title->title }}"
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
                                                >{{ $title->description }}</textarea>
                                                @error('description') <p class="mt-1 text-sm text-rose-600">{{ $message }}</p> @enderror
                                            </div>

                                            <div class="flex justify-end gap-2">
                                                <button type="button" id="add-title-cancel-btn" class="rounded border px-4 py-2 text-sm">Отмена</button>
                                                <button type="submit" class="rounded bg-emerald-600 px-4 py-2 text-sm text-white hover:bg-emerald-700">Сохранить</button>
                                            </div>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        </td>
                        <td class="px-3 py-2">
                            <form method="POST" action="{{ route('admin.titles.delete', $title) }}" class="space-y-1">
                                @csrf
                                @method('DELETE')
                
                                <button type="submit" class="w-full rounded bg-rose-600 px-3 py-1 text-white hover:bg-rose-700">
                                    Удалить
                                </button>
                            </form>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="7" class="px-3 py-6 text-center text-slate-500">Манга не найдена.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    {{ $titles->links() }}
</div>

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

@endsection