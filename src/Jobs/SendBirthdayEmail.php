<?php

namespace Digitalcake\Scheduling\Jobs;

use Digitalcake\Scheduling\Models\EmailSendSettings;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Mail;

class SendBirthdayEmail implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $user;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct($user)
    {
        $this->user = $user;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $birtdaySettings = EmailSendSettings::first();
        Mail::to($this->user->getEmail())
            ->send(new \Digitalcake\Scheduling\Mail\BirthdayEmail(
                $birtdaySettings->subject,
                $birtdaySettings->message
            ));

        Cache::put(explode('@', $this->user->getEmail())[0], true, now()->addDay());
    }
}
