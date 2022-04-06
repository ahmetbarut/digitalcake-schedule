<?php

namespace Digitalcake\Scheduling\Listeners;

use Digitalcake\Scheduling\Models\SendedEmail;
use Illuminate\Contracts\Queue\ShouldQueue;
use Digitalcake\Scheduling\Events\ScheduleSendEmailEvent;
use Digitalcake\Scheduling\Jobs\SendEmail;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;

class SendEmailListener
{
    public function __construct()
    {
        //
    }

    public function handle(ScheduleSendEmailEvent $event)
    {
        SendEmail::dispatch([
            'email' => $event->user->getEmail(),
            'subject' => $event->data['subject'],
            'content' => $event->data['content'],
        ])->delay(now()->addMinute())->onQueue('emails');
    }
}
