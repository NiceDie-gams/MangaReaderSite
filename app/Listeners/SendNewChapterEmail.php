<?php

namespace App\Listeners;

use App\Events\NewChapterUploaded;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use App\Notifications\NewChapterUploadedNotification;
use Illuminate\Support\Facades\Notification;

class SendNewChapterEmail
{
    /**
     * Create the event listener.
     */
    public function __construct()
    {
        //
    }

    /**
     * Handle the event.
     */
    public function handle(NewChapterUploaded $event): void
    {
        $adminEmail = config('mail.to.address');

        Notification::route('mail', $adminEmail)
            ->notify(new NewChapterUploadedNotification($event->chapter));
    }
}
