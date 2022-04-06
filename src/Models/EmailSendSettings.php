<?php

namespace Digitalcake\Scheduling\Models;

use Illuminate\Database\Eloquent\Model;

class EmailSendSettings extends Model
{
    protected $fillable = [
        'email_type',
        'email_send_day',
    ];
}
