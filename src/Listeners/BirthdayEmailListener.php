<?php

namespace Digitalcake\Scheduling\Listeners;

use Digitalcake\Scheduling\Events\BirthdaySendEmailEvent;
use Digitalcake\Scheduling\Jobs\SendBirthdayEmail;
use Digitalcake\Scheduling\Models\EmailSendSettings;
use Digitalcake\Scheduling\Models\SendedBirthdayEmail;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;

class BirthdayEmailListener implements ShouldQueue
{
    public $connection = "database";

    public $queue = "default";

    /**
     * Create the event listener.
     *
     * @return void
     */
    public function __construct()
    {
    }

    /**
     * Handle the event.
     *
     * @param  BirthdaySendEmailEvent  $event
     * @return void
     */
    public function handle(BirthdaySendEmailEvent $event)
    {
        $sendedEmail = new SendedBirthdayEmail();

        if ($sendedEmail->where(
            'email',
            $event
                ->user
                ->getEmail()
        )->count() === 0) {
            $birtdaySettings = EmailSendSettings::first();
            $sendedEmail->email = $event->user->getEmail();
            $sendedEmail->save();

            Mail::to($event->user->getEmail())
                ->send(new \Digitalcake\Scheduling\Mail\BirthdayEmail(
                    $birtdaySettings->subject,
                    $birtdaySettings->message
                ));
        }
    }
}
