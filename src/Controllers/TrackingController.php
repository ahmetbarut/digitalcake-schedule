<?php

namespace Digitalcake\Scheduling\Controllers;

use App\Extensions\Newsletter\Models\User;
use App\Http\Controllers\PackageBaseController;
use Digitalcake\Scheduling\Models\SendedEmail;
use Illuminate\Http\Request;

class TrackingController extends PackageBaseController
{

    protected $types = [
        'processed' => 'processed',
        'delivered' => 'delivered',
        'open' => 'opened',
        'click' => 'clicked',
        'bounce' => 'bounced',
        'spam' => 'spam',
        'unsubscribe' => 'unsubscribed',
        'resubscribe' => 'resubscribed',
        'reject' => 'rejected',

    ];

    public function __construct()
    {
        parent::__construct(app('request'));
        $this->rejectTypes = config('schedule.smtp2go.destroy_users');
    }

    public function index(Request $request)
    {
        if ($request->has('recipients')) {
            $emailLog = SendedEmail::where('email', $request->recipients)
                ->latest()
                ->first();

            $emailLog->status = $this->types[$request->get('event')];
            $emailLog->email_id = $request->get('email_id');
            $emailLog->save();
        }

        if (
            $request->has('rcpt') && $emailLog = SendedEmail::where('email_id', $request->email_id)->latest()
            ->first()
        ) {
            $emailLog->status = $this->types[$request->get('event')];
            $emailLog->save();
        }

        if (in_array($request->get('event'), $this->rejectTypes)) {
            User::where('email', $request->get('rcpt'))
                ->delete();
        }

        if ($request->get('event') == 'resubscribe') {
            $user = new User;
            $user->email = $request->get('rcpt');
            $user->save();
        }
    }

    public function emails()
    {
        return view('schedule::logs.emails')
            ->with('emails', SendedEmail::all());
    }

    // public function settings()
    // {
    //     if (EmailSendSettings::count() == 0) {
    //         (new GlobalEmailSendSettings())->run();
    //     }
    //     return view('Newsletter.Views.administrator.logs.settings')
    //         ->with('staffNotEmail', Staff::where('email', null)
    //             ->orWhere('email', '')
    //             ->get())
    //         ->with('settings', EmailSendSettings::first());
    // }

    public function showEmail(SendedEmail $email)
    {
        return view('schedule::logs.email-show')
            ->with('email', $email);
    }
}
