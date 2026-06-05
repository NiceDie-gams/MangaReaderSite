@extends('layouts.app')

@section('content')
<div class="mx-auto max-w-4xl rounded bg-white p-6 shadow dark:bg-gray-800">
    <h1 class="mb-6 text-3xl font-bold dark:text-white">О нас</h1>

    <div class="mb-8">
        <h2 class="mb-3 text-xl font-semibold text-blue-600 dark:text-blue-400">О компании</h2>
        <div class="prose prose-slate dark:prose-invert max-w-none text-slate-700 dark:text-slate-300">
            <p>Здесь будет текст о вашей компании. Расскажите, кто вы, чем занимаетесь, какую цель преследуете.</p>
            <p>Пример: Мы — команда энтузиастов, создавшая этот сайт для удобного чтения манги. Наша цель — предоставить пользователям качественный контент и удобный интерфейс.</p>
        </div>
    </div>

    <div>
        <h2 class="mb-3 text-xl font-semibold text-blue-600 dark:text-blue-400">Контакты</h2>
        <div class="prose prose-slate dark:prose-invert max-w-none text-slate-700 dark:text-slate-300">
            <p>Вы можете связаться с нами следующими способами:</p>
            <ul>
                <li>Email: <a href="mailto:info@example.com" class="text-blue-600 hover:underline">info@example.com</a></li>
                <li>Telegram: <a href="https://t.me/your_channel" class="text-blue-600 hover:underline">@your_channel</a></li>
                <li>VK: <a href="https://vk.com/your_page" class="text-blue-600 hover:underline">vk.com/your_page</a></li>
            </ul>
            <p>Или оставьте сообщение через форму обратной связи (скоро появится).</p>
        </div>
    </div>
</div>
@endsection
