<?php

namespace Digitalcake\Scheduling\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class BirthdayEmail extends Mailable
{
    use Queueable, SerializesModels;

    public $message;
    
    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct(string $mailSubject, string $message)
    {
        $this->message = $message;
        $this->subject = $mailSubject;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->view('schedule::emails.empty-template')
            ->with([
                'content' => $this->message,
                'type' => $this->message['type'] ?? null
            ]);
    }
}
