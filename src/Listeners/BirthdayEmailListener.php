<?php

namespace Digitalcake\Scheduling\Listeners;

use Digitalcake\Scheduling\Events\BirthdaySendEmailEvent;
use Digitalcake\Scheduling\Jobs\SendBirthdayEmail;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;

class BirthdayEmailListener
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
     * @param  BirthdaySendEmailEvent  $event
     * @return void
     */
    public function handle(BirthdaySendEmailEvent $event)
    {
        SendBirthdayEmail::dispatch($event->user)
            ->onQueue('birthday')
            ->delay(now()->addSeconds(5));
    }
}
