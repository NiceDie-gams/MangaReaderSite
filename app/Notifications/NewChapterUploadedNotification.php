<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class NewChapterUploadedNotification extends Notification
{
    use Queueable;

    public $chapter;

    /**
     * Create a new notification instance.
     */
    public function __construct($chapter)
    {
        $this->chapter = $chapter;
    }

    /**
     * Get the notification's delivery channels.
     *
     * @return array<int, string>
     */
    public function via(object $notifiable): array
    {
        return ['mail'];
    }

    /**
     * Get the mail representation of the notification.
     */
    public function toMail(object $notifiable): MailMessage
    {
        return (new MailMessage)
            ->subject('Новая глава на модерацию')
            ->greeting('Здравствуйте, Администратор!')
            ->line("Переводчик {$this->chapter->uploadedBy->name} загрузил новую главу:")
            ->line("**Тайтл:** {$this->chapter->titleBelong->title}")
            ->line("**Номер главы:** {$this->chapter->chapter_number}")
            ->action('Перейти к модерации', url('/admin/chapters'))
            ->line('Пожалуйста, проверьте её.');
    }

    /**
     * Get the array representation of the notification.
     *
     * @return array<string, mixed>
     */
    public function toArray(object $notifiable): array
    {
        return [
            //
        ];
    }
}
