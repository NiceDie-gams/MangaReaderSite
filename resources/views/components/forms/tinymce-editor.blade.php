@php
    // Используем глобальную функцию env() или Vite
    $apiKey = env('VITE_TINYMCE_API_KEY');
@endphp

{{-- Подключаем скрипт TinyMCE с нашим API-ключом --}}
<script src="https://cdn.tiny.cloud/1/{{ $apiKey }}/tinymce/8/tinymce.min.js" referrerpolicy="origin" crossorigin="anonymous"></script>

<script>
    tinymce.init({
        selector: '#editor', // ID твоего текстового поля
        plugins: 'code table lists anchor autolink charmap codesample emoticons image link media searchreplace visualblocks wordcount checklist mediaembed casechange export formatpainter pageembed permanentpen footnotes advtemplate advtable advcode editimage tableofcontents mergetags powerpaste tinymcespellchecker autocorrect a11ychecker typography inlinecss',
        toolbar: 'undo redo | blocks | bold italic | alignleft aligncenter alignright | indent outdent | bullist numlist | code | table',
        height: 500,
    });
</script>