<?php

namespace Digitalcake\Scheduling\Events;

use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;
use Digitalcake\Scheduling\Models\ScheduleMessage;
use Digitalcake\Scheduling\Contracts\UserContract;

class ScheduleSendEmailEvent
{
    use Dispatchable, InteractsWithSockets, SerializesModels;


    public $user;
    
    /**
     * @var array<int, string> $data
     */
    public $data;

    /**
     * Create a new event instance.
     * @param array <email, subject>$data
     * @return void
     */
    public function __construct(UserContract $user, array $data)
    {
        $this->user = $user;
        $this->data = $data;
    }

    /**
     * Get the channels the event should broadcast on.
     *
     * @return \Illuminate\Broadcasting\Channel|array
     */
    public function broadcastOn()
    {
        return new PrivateChannel('channel-name');
    }
}
