<?php

namespace Digitalcake\Scheduling\Listeners;

use Digitalcake\Scheduling\Models\SendedEmail;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Events\MessageSent;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Support\Facades\Log;

class LogSentMessage
{
    /**
     * Create the event listener.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * Handle the event.
     *
     * @param  MessageSent  $event
     * @return void
     */
    public function handle(MessageSent $event)
    {

        $emails = collect($event->message->getTo())->map(function ($item, $key) {
            return $key;
        })->each(function ($item) use ($event) {
            $email = new SendedEmail();
            $email->email = $item;
            $email->message = $event->data['content'];
            $email->subject = $event->message->getSubject();
            $email->email_type = $event->data['type'] ?? null;
            $email->status = 'sended';
            $email->save();
        });
    }
}
