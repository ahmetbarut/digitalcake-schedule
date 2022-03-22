<?php

namespace Digitalcake\Scheduling\Models;

use App\Extensions\Newsletter\Models\User;
use Illuminate\Database\Eloquent\Model;

class ScheduleMessage extends Model
{
    public function users()
    {
        return $this->belongsToMany(
            User::class,
            'schedule_messages_users',
            'schedule_message_id',
            'newsletter_users'
        );
    }

    public function setSendAtAttribute($value)
    {
        return $this->attributes['send_at'] = now()->parse($value)->format('Y-m-d H:i:s');
    }
}
