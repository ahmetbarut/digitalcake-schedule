<?php

namespace Digitalcake\Scheduling\Navigation;

use App\Contracts\Navigation as ContractsNavigation;
use Digitalcake\Scheduling\Models\ScheduleMessage;

class Navigation implements ContractsNavigation
{
    public static function getNavigation(): array
    {
        return [
            'title' => str_replace(
                ':count',
                ScheduleMessage::where('is_sent', false)->count(),
                'Scheduling (:count)'
            ),
            'url' => 'administrator/scheduling',
            'active' => request()->is('administrator/scheduling*'),
            'role' => 'super_user|manager',
            'icon' => 'bx bx-calendar-check',
            'items' => [
                [
                    'title' => 'Schedules',
                    'url' => route('schedule.index'),
                    'active' => request()->is(
                        'administrator/schedule',
                        'administrator/schedule/*/edit',
                        'administrator/schedule/create'
                    ),
                    'role' => 'super_user|manager'
                ],
                [
                    'title' => 'Emails',
                    'url' => 'administrator/schedule/emails',
                    'active' => \Request::is('administrator/newsletter/emails*'),
                    'role' => 'super_user|manager'
                ],
            ]
        ];
    }
}
