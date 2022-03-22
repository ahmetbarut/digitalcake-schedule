<?php

namespace Digitalcake\Scheduling\Commands;

use App\Extensions\Newsletter\Models\Message\Message;
use Brick\PhoneNumber\PhoneNumber;
use Brick\PhoneNumber\PhoneNumberFormat;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use App\Extensions\Newsletter\Models\Message\Receiver;
use App\Jobs\SendSmsJob;
use Digitalcake\Scheduling\Models\ScheduleMessage;
use Illuminate\Support\Facades\Schema;

class SendNewsletter extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'newsletter:send';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Zamanlanmış mesajları gönderir';

    /**
     * Create a new command instance.
     *
     * @var ScheduleMessage | null
     */
    protected $scheduleMessage;

    /**
     * Create a new command instance.
     *
     * @var \Illuminate\Support\Collection
     */
    protected $newsletterUsers;

    /**
     * Create a new command instance.
     *
     * @var bool
     */
    protected $hasSent = false;

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
        if (Schema::hasTable('schedule_messages')) {
            $this->scheduleMessage = ScheduleMessage::where('is_sent', '=', false)
                ->where(DB::raw('DATE(send_at)'), '<=', DB::raw('CURDATE()'))
                ->where(DB::raw('TIME(send_at)'), '<=', DB::raw('CURTIME()'))
                ->first();

            if ($this->scheduleMessage) {
                $this->hasSent = true;
                $this->newsletterUsers = $this->scheduleMessage->users;
            }
        }
    }

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        if ($this->hasSent) {
            $subject = $this->scheduleMessage->subject;
            try {
                if ($this->scheduleMessage->type == 'sms') {
                    $this->sendSms($subject, $this->scheduleMessage->message);
                }

                if ($this->scheduleMessage->type == 'email') {
                    if ($this->scheduleMessage->name = 'empty_newsletter') {
                        foreach ($this->newsletterUsers as $user) {
                            Mail::send(
                                'Newsletter.Views.email.empty-template',
                                [
                                    'content' => $this->scheduleMessage->message,
                                    'user' => $user,
                                    'type' => 'empty'
                                ],
                                function ($mail) use ($user, $subject) {
                                    $mail
                                        ->to($user->email)
                                        ->subject($subject);
                                }
                            );
                        }
                        $this->scheduleMessage->is_sent = true;
                    } elseif ($this->scheduleMessage->name = 'greetings') {
                        foreach ($this->newsletterUsers as $user) {
                            Mail::send('Newsletter.Views.email.greetings-template', [
                                'image' => $this->scheduleMessage->attachment,
                                'user' => $user,
                                'type' => 'newsletter'
                            ], function ($mail) use ($user, $subject) {
                                $mail->to($user->email)->subject($subject);
                            });
                        }
                    }
                }
                $this->scheduleMessage->is_sent = true;
                $this->scheduleMessage->save();
            } catch (\Exception $e) {
                Log::error($e->getMessage());
            }
        }
        return 0;
    }

    /**
     * Sms gönderme işlemini yapar.
     * @param $phone
     * @return void
     */
    protected function sendSms(string $subject, string $content): void
    {
        $newsletterMessage = new Message();
        $newsletterMessage->subject = $subject;
        $newsletterMessage->content = $content;
        $newsletterMessage->save();

        $this->newsletterUsers
            ->each(function ($user) use ($newsletterMessage) {
                $newsletterReceiver = new Receiver();
                $newsletterReceiver->newsletter_message_id = $newsletterMessage->id;
                $newsletterReceiver->newsletter_user_id = $user->id;
                $newsletterReceiver->status = '200';
                $newsletterReceiver->save();
            })
            ->pluck('phone')
            ->map(function ($item) {
                return \App\Helpers\Phone::addZeroForNumber(
                    \App\Helpers\General::trimAll(
                        PhoneNumber::parse($item)
                            ->format(PhoneNumberFormat::INTERNATIONAL)
                    )
                );
            })
            ->each(function ($item) use ($subject, $content) {
                SendSmsJob::dispatch($item, $subject, $content)->onQueue('sms');
            });
    }
}
