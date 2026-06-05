<?php

namespace App\Listeners;

use App\Events\NewReportSubmitted;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use App\Notifications\NewReportNotification;
use Illuminate\Support\Facades\Notification;

class SendNewReportEmail
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
    public function handle(NewReportSubmitted $event): void
    {
        $adminEmail = config('mail.to.address');

        Notification::route('mail', $adminEmail)
            ->notify(new NewReportNotification($event->report));
    }
}
