@extends('layouts.app')

@section('content')
<div class="space-y-4">
    <div class="flex items-center justify-between">
        <h1 class="text-2xl font-bold">Манга</h1>
        <a href="{{ route('admin.dashboard') }}" class="text-sm text-blue-600 hover:underline dark:text-blue-400">Назад в админ-панель</a>
    </div>

    @if(session('success'))
        <div class="rounded border border-emerald-300 bg-emerald-50 px-4 py-2 text-emerald-700 dark:border-emerald-800 dark:bg-emerald-950 dark:text-emerald-300">
            {{ session('success') }}
        </div>
    @endif

    @if($errors->any())
        <div class="rounded border border-rose-300 bg-rose-50 px-4 py-2 text-rose-700 dark:border-rose-800 dark:bg-rose-950 dark:text-rose-300">
            {{ $errors->first() }}
        </div>
    @endif

    <div class="overflow-x-auto rounded bg-white shadow dark:bg-gray-800">
        <table class="min-w-full text-sm">
            <thead class="bg-slate-100 text-left dark:bg-gray-700">
                <tr>
                    <th class="px-3 py-2 dark:text-white">ID</th>
                    <th class="px-3 py-2 dark:text-white">Тайтл</th>
                    <th class="px-3 py-2 dark:text-white">Описание</th>
                    <th class="px-3 py-2 dark:text-white">Теги</th>
                    <th class="px-3 py-2 dark:text-white">Обновить</th>
                    <th class="px-3 py-2 dark:text-white">Удалить</th>
                </tr>
            </thead>
            <tbody>
                @forelse($titles as $title)
                    <tr class="border-t dark:border-gray-700">
                        <td class="px-3 py-2 dark:text-gray-300">{{ $title->id }}</td>
                        <td class="px-3 py-2 dark:text-gray-300">{{ $title->title}}</td>
                        <td class="px-3 py-2 dark:text-gray-300">{{ Str::limit($title->description, 50) }}</td>
                        <td class="px-3 py-2 dark:text-gray-300">
                            @foreach($title->tags as $tag)
                                <span class="inline-block rounded-full bg-slate-200 px-2 py-0.5 text-xs dark:bg-slate-600 dark:text-white">{{ $tag->name }}</span>
                            @endforeach
                        </td>
                        <td class="px-3 py-2">
                            <button data-title-id="{{ $title->id }}" class="open-edit-modal w-full rounded bg-yellow-400 px-3 py-1 text-white hover:bg-yellow-500 dark:bg-yellow-600 dark:hover:bg-yellow-700">
                                Обновить
                            </button>
                        </td>
                        <td class="px-3 py-2">
                            <form method="POST" action="{{ route('admin.titles.delete', $title) }}" class="space-y-1">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="w-full rounded bg-rose-600 px-3 py-1 text-white hover:bg-rose-700 dark:bg-rose-700 dark:hover:bg-rose-800">
                                    Удалить
                                </button>
                            </form>
                        </td>
                    </tr>
                @empty
                <tr>
                    <td colspan="6" class="px-3 py-6 text-center text-slate-500 dark:text-gray-400">Манга не найдена.</td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    {{ $titles->links() }}
</div>

{{-- Модальное окно для редактирования (общее) --}}
<div id="edit-title-modal" class="fixed inset-0 z-[10003] hidden items-center justify-center bg-slate-900/50 p-4">
    <div class="w-full max-w-lg rounded-lg bg-white p-4 shadow-xl dark:bg-slate-800">
        <div class="mb-3 flex items-center justify-between">
            <h2 class="text-lg font-semibold dark:text-white">Редактировать мангу</h2>
            <button id="edit-title-close-btn" type="button" class="text-slate-500 hover:text-slate-800 dark:text-slate-400 dark:hover:text-slate-200" aria-label="Закрыть">✕</button>
        </div>

        <form method="POST" action="{{ route('admin.titles.update') }}" enctype="multipart/form-data" class="space-y-3">
            @csrf
            @method('PATCH')
            <input type="hidden" name="id" id="edit-title-id">

            <div>
                <label for="edit-title-name" class="mb-1 block text-sm text-slate-600 dark:text-slate-300">Название</label>
                <input id="edit-title-name" type="text" name="title" required maxlength="255" class="w-full rounded border px-3 py-2 dark:border-slate-600 dark:bg-slate-700">
            </div>

            <div>
                <label for="edit-title-description" class="mb-1 block text-sm text-slate-600 dark:text-slate-300">Описание</label>
                <textarea id="edit-title-description" name="description" rows="4" maxlength="5000" class="w-full rounded border px-3 py-2 dark:border-slate-600 dark:bg-slate-700"></textarea>
            </div>

            {{-- Поле выбора тегов (чипсы) --}}
            <div>
                <label class="mb-1 block text-sm text-slate-600 dark:text-slate-300">Теги</label>
                <input type="text" id="tag-search" placeholder="Поиск тега..." class="mb-2 w-full rounded border px-3 py-1 text-sm dark:border-slate-600 dark:bg-slate-700 dark:text-white">
                <div id="tags-container" class="flex max-h-40 flex-wrap gap-2 overflow-y-auto rounded border p-2 dark:border-slate-600">
                    @foreach($allTags as $tag)
                        <label class="tag-chip inline-flex cursor-pointer items-center rounded-full border px-3 py-1 text-sm transition-colors dark:border-slate-600 dark:text-slate-300">
                            <input type="checkbox" name="tags[]" value="{{ $tag->id }}" class="hidden">
                            <span>{{ $tag->name }}</span>
                        </label>
                    @endforeach
                </div>
                <p class="mt-1 text-xs text-slate-500 dark:text-slate-400">Нажмите на тег, чтобы добавить/удалить</p>
            </div>

            <div class="flex justify-end gap-2">
                <button type="button" id="edit-title-cancel-btn" class="rounded border px-4 py-2 text-sm dark:border-slate-600 dark:text-slate-300">Отмена</button>
                <button type="submit" class="rounded bg-emerald-600 px-4 py-2 text-sm text-white hover:bg-emerald-700">Сохранить</button>
            </div>
        </form>
    </div>
</div>

<style>
.tag-chip {
    background-color: #f1f5f9;
    color: #1e293b;
    transition: all 0.2s;
}
.tag-chip:has(input:checked) {
    background-color: #ff9000;
    color: white;
    border-color: #ff9000;
}
.dark .tag-chip {
    background-color: #334155;
    color: #e2e8f0;
}
.dark .tag-chip:has(input:checked) {
    background-color: #ff9000;
    color: #0f172a;
}
</style>


<script>
    (function() {
        const modal = document.getElementById('edit-title-modal');
        const closeBtn = document.getElementById('edit-title-close-btn');
        const cancelBtn = document.getElementById('edit-title-cancel-btn');
        const form = modal.querySelector('form');
        const titleIdInput = document.getElementById('edit-title-id');
        const titleNameInput = document.getElementById('edit-title-name');
        const titleDescInput = document.getElementById('edit-title-description');

        const tagsContainer = document.getElementById('tags-container');
        const tagSearch = document.getElementById('tag-search');
        let allCheckboxes = [];

        function refreshTagFilter() {
            const query = tagSearch.value.trim().toLowerCase();
            const labels = tagsContainer.querySelectorAll('.tag-chip');
            labels.forEach(label => {
                const text = label.innerText.trim().toLowerCase();
                if (query === '' || text.includes(query)) {
                    label.style.display = 'inline-flex';
                } else {
                    label.style.display = 'none';
                }
            });
        }

        tagSearch.addEventListener('input', refreshTagFilter);

        function setSelectedTags(selectedTagIds) {
            const checkboxes = tagsContainer.querySelectorAll('input[type="checkbox"]');
            checkboxes.forEach(cb => {
                cb.checked = selectedTagIds.includes(parseInt(cb.value));
            });
        }

        const titlesTags = @json($titles->mapWithKeys(fn($t) => [$t->id => $t->tags->pluck('id')->toArray()]));

        function openModal(titleId) {
            const row = document.querySelector(`button[data-title-id="${titleId}"]`).closest('tr');
            const titleName = row.cells[1].innerText.trim();
            const titleDesc = row.cells[2].innerText.trim();
            titleIdInput.value = titleId;
            titleNameInput.value = titleName;
            titleDescInput.value = titleDesc;

            const selectedTagIds = titlesTags[titleId] || [];
            setSelectedTags(selectedTagIds);

            modal.classList.remove('hidden');
            modal.classList.add('flex');
            tagSearch.value = '';
            refreshTagFilter();
        }

        function closeModal() {
            modal.classList.add('hidden');
            modal.classList.remove('flex');
        }

        document.querySelectorAll('.open-edit-modal').forEach(btn => {
            btn.addEventListener('click', () => {
                const titleId = btn.getAttribute('data-title-id');
                openModal(titleId);
            });
        });

        closeBtn?.addEventListener('click', closeModal);
        cancelBtn?.addEventListener('click', closeModal);
        modal.addEventListener('click', (event) => {
            if (event.target === modal) closeModal();
        });

        @if($errors->has('title') || $errors->has('description') || $errors->has('tags'))
            const lastTitleId = {{ old('id') ?? 'null' }};
            if (lastTitleId) {
                openModal(lastTitleId);
            }
        @endif
    })();
</script>

@endsection
