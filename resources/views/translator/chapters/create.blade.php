@extends('layouts.app')

@section('content')
<div class="mx-auto max-w-3xl rounded bg-white p-6 shadow">
    <h1 class="mb-4 text-2xl font-bold">Добавить новую главу</h1>

    <form method="POST" action="{{ route('translator.chapters.store') }}" enctype="multipart/form-data">
        @csrf

        {{-- Поиск тайтла с автокомплитом --}}
        <div class="mb-4">
            <label class="mb-1 block font-medium">Тайтл <span class="text-red-600">*</span></label>
            <input type="text" id="title-search" class="w-full rounded border px-3 py-2" placeholder="Начните вводить название..." autocomplete="off">
            <input type="hidden" name="title_id" id="title-id" required>
            <div id="title-suggestions" class="absolute z-10 mt-1 hidden max-h-60 w-relative overflow-auto rounded border bg-white shadow-lg"></div>
            @error('title_id') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
        </div>

        {{-- Номер главы + подсказка последних глав --}}
        <div class="mb-4">
            <label class="mb-1 block font-medium">Номер главы <span class="text-red-600">*</span></label>
            <input type="number" name="chapter_number" id="chapter-number" value="{{ old('chapter_number') }}" required step="1" min="1" class="w-full rounded border px-3 py-2" oninput="this.value = Math.abs(parseInt(this.value)) || ''">
            <div id="recent-chapters" class="mt-2 text-sm text-gray-500"></div>
            @error('chapter_number') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
        </div>

        {{-- Способ загрузки (без изменений) --}}
        <div class="mb-4">
            <label class="mb-1 block font-medium">Способ загрузки</label>
            <div class="flex gap-4">
                <label><input type="radio" name="upload_method" value="zip" checked> ZIP-архив</label>
                <label><input type="radio" name="upload_method" value="files"> Отдельные файлы</label>
            </div>
        </div>

        <div id="zip-block" class="mb-4">
            <label class="mb-1 block font-medium">ZIP-архив (jpg, png, gif, webp)</label>
            <input type="file" name="zip_file" accept=".zip">
        </div>

        <div id="files-block" class="mb-4 hidden">
            <label class="mb-1 block font-medium">Изображения (можно несколько)</label>
            <input type="file" name="images[]" accept="image/*" multiple>
        </div>

        <button type="submit" class="rounded bg-blue-600 px-4 py-2 text-white hover:bg-blue-700">Отправить на модерацию</button>
    </form>
</div>

<script>

    const radioZip = document.querySelector('input[value="zip"]');
    const radioFiles = document.querySelector('input[value="files"]');
    const zipBlock = document.getElementById('zip-block');
    const filesBlock = document.getElementById('files-block');

    function toggleMethod() {
        if (radioZip.checked) {
            zipBlock.classList.remove('hidden');
            filesBlock.classList.add('hidden');
        } else {
            zipBlock.classList.add('hidden');
            filesBlock.classList.remove('hidden');
        }
    }
    radioZip.addEventListener('change', toggleMethod);
    radioFiles.addEventListener('change', toggleMethod);
    toggleMethod();

    const searchInput = document.getElementById('title-search');
    const titleIdInput = document.getElementById('title-id');
    const suggestionsDiv = document.getElementById('title-suggestions');
    let currentTimeout;

    searchInput.addEventListener('input', function() {
        const query = this.value.trim();
        if (query.length < 2) {
            suggestionsDiv.classList.add('hidden');
            return;
        }
        clearTimeout(currentTimeout);
        currentTimeout = setTimeout(() => {
            fetch(`/api/titles/search?q=${encodeURIComponent(query)}`)
                .then(res => res.json())
                .then(data => {
                    if (!data.length) {
                        suggestionsDiv.classList.add('hidden');
                        return;
                    }
                    suggestionsDiv.innerHTML = data.map(title => `
                        <div class="cursor-pointer px-3 py-2 hover:bg-slate-100" data-id="${title.id}" data-title="${title.title}">
                            ${title.title}
                        </div>
                    `).join('');
                    suggestionsDiv.classList.remove('hidden');

                    document.querySelectorAll('#title-suggestions div').forEach(el => {
                        el.addEventListener('click', () => {
                            searchInput.value = el.dataset.title;
                            titleIdInput.value = el.dataset.id;
                            suggestionsDiv.classList.add('hidden');
                            loadRecentChapters(el.dataset.id);
                        });
                    });
                });
        }, 300);
    });

    document.addEventListener('click', function(e) {
        if (!searchInput.contains(e.target) && !suggestionsDiv.contains(e.target)) {
            suggestionsDiv.classList.add('hidden');
        }
    });

    function loadRecentChapters(titleId) {
        const container = document.getElementById('recent-chapters');
        if (!titleId) {
            container.innerHTML = '';
            return;
        }
        fetch(`/api/titles/${titleId}/recent-chapters`)
            .then(res => res.json())
            .then(data => {
                if (data.length === 0) {
                    container.innerHTML = '<span class="text-gray-400">Нет загруженных глав</span>';
                } else {
                    container.innerHTML = `<span class="font-medium">Последние главы:</span> ${data.map(ch => ch.chapter_number).join(', ')}`;
                }
            })
            .catch(() => {
                container.innerHTML = '<span class="text-red-500">Не удалось загрузить последние главы</span>';
            });
    }
</script>
@endsection
