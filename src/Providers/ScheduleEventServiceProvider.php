<?php

namespace Digitalcake\Scheduling\Providers;

use Illuminate\Foundation\Support\Providers\EventServiceProvider as ServiceProvider;

class ScheduleEventServiceProvider extends ServiceProvider
{

    protected $listen = [
        'Illuminate\Mail\Events\MessageSending' => [
            'Digitalcake\Scheduling\Listeners\LogSendingMessage',
        ],
        'Illuminate\Mail\Events\MessageSent' => [
            'Digitalcake\Scheduling\Listeners\LogSentMessage',
        ],
        'Digitalcake\Scheduling\Events\ScheduleSendEmailEvent' => [
            'Digitalcake\Scheduling\Listeners\SendEmailListener',
        ],
        'Digitalcake\Scheduling\Events\BirthdaySendEmailEvent' => [
            'Digitalcake\Scheduling\Listeners\BirthdayEmailListener',
        ],
    ];

    public function boot()
    {
        parent::boot();
    }
}
