<?php

namespace Digitalcake\Scheduling\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class SendSmsJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public $phone;
    public $message;
    
    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct($phone, string $message)
    {
        $this->phone = $phone;
        $this->message = $message;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        if (!is_array($this->phone)) {
            $this->phone = [$this->phone];
        }

        $client = new \CMText\TextClient(config('services.cmtext.api_key'));

        $client->SendMessage($this->message, 'BIF', $this->phone);
    }
}
