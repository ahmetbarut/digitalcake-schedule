<?php

namespace Digitalcake\Scheduling\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Prunable;

class SendedBirthdayEmail extends Model
{
    use HasFactory, Prunable;

    public function prunable()
    {
        static::where('created_at', '<', now()->subMonth())
            ->delete();
    }
}
