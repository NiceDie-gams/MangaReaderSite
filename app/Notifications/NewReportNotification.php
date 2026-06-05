<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;

class NewReportNotification extends Notification
{
    use Queueable;

    public $report;

    public function __construct($report)
    {
        $this->report = $report;
    }

    public function via($notifiable): array
    {
        return ['mail'];
    }

    public function toMail($notifiable): MailMessage
    {
        return (new MailMessage)
                    ->subject('Новая жалоба на сайте')
                    ->greeting('Здравствуйте, Администратор!')
                    ->line('Была подана новая жалоба:')
                    ->line('* Текст: "' . $this->report->reportText . '"')
                    ->action('Перейти в админ-панель', url('/admin/reports'))
                    ->line('Пожалуйста, рассмотрите её.');
    }
}
