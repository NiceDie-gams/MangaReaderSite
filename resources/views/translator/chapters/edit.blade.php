@extends('layouts.app')

@section('content')
<div class="mx-auto max-w-3xl rounded bg-white p-6 shadow">
    <h1 class="mb-4 text-2xl font-bold">Редактирование главы #{{ $chapter->chapter_number }}</h1>

    @if($chapter->reject_reason)
        <div class="mb-4 rounded border border-red-300 bg-red-50 p-3 text-red-700">
            <strong>Причина отклонения:</strong> {{ $chapter->reject_reason }}
        </div>
    @endif

    <form method="POST" action="{{ route('translator.chapters.update', $chapter) }}" enctype="multipart/form-data">
        @csrf
        @method('PUT')

        <div class="mb-4">
            <label class="mb-1 block font-medium">Тайтл</label>
            <select name="title_id" required class="w-full rounded border px-3 py-2">
                @foreach($titles as $title)
                    <option value="{{ $title->id }}" {{ old('title_id', $chapter->title_id) == $title->id ? 'selected' : '' }}>{{ $title->title }}</option>
                @endforeach
            </select>
        </div>

        <div class="mb-4">
            <label class="mb-1 block font-medium">Номер главы</label>
            <input type="number" name="chapter_number" value="{{ old('chapter_number', $chapter->chapter_number) }}" required class="w-full rounded border px-3 py-2">
        </div>

        <div class="mb-4">
            <label class="mb-1 block font-medium">Название главы</label>
            <input type="text" name="chapter_title" value="{{ old('chapter_title', $chapter->title) }}" class="w-full rounded border px-3 py-2">
        </div>

        <div class="mb-4">
            <label class="mb-1 block font-medium">Способ загрузки</label>
            <div class="flex gap-4">
                <label><input type="radio" name="upload_method" value="zip"> ZIP-архив</label>
                <label><input type="radio" name="upload_method" value="files"> Отдельные файлы</label>
            </div>
        </div>

        <div id="zip-block" class="mb-4 hidden">
            <label>ZIP-архив (новые страницы заменят старые)</label>
            <input type="file" name="zip_file" accept=".zip">
        </div>

        <div id="files-block" class="mb-4 hidden">
            <label>Изображения (можно несколько)</label>
            <input type="file" name="images[]" accept="image/*" multiple>
        </div>

        <button type="submit" class="rounded bg-blue-600 px-4 py-2 text-white">Отправить исправленную главу</button>
    </form>
</div>

<script>
    const radios = document.querySelectorAll('input[name="upload_method"]');
    const zipBlock = document.getElementById('zip-block');
    const filesBlock = document.getElementById('files-block');
    function toggle() {
        const val = document.querySelector('input[name="upload_method"]:checked').value;
        zipBlock.classList.toggle('hidden', val !== 'zip');
        filesBlock.classList.toggle('hidden', val !== 'files');
    }
    radios.forEach(r => r.addEventListener('change', toggle));
    toggle();
</script>
@endsection
