<?php

namespace Digitalcake\Scheduling\Controllers;

use Illuminate\Http\Request;
use App\Extensions\Newsletter\Models\User as NewsletterUser;
use App\Extensions\Newsletter\Models\Group as NewsletterGroup;
use App\Http\Controllers\PackageBaseController;
use Digitalcake\Scheduling\Contracts\UserContract;
use Digitalcake\Scheduling\Events\ScheduleSendEmailEvent;
use Digitalcake\Scheduling\Models\EmailSendSettings;
use Digitalcake\Scheduling\Models\ScheduleMessage;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Validator;
use Intervention\Image\Facades\Image;

class ScheduleController extends PackageBaseController
{
    public function __construct()
    {
        parent::__construct(app('request'));
    }
    
    public function index()
    {
        return view('schedule::index')
            ->with('schedules', ScheduleMessage::all());
    }

    public function createEmail()
    {
        // Get the newsletter users
        $users = NewsletterUser::all();

        $users = $users->filter(function ($user) {
            return filter_var($user->email, FILTER_VALIDATE_EMAIL);
        });

        // Get newsletter groups
        $groups = NewsletterGroup::all();

        return view('schedule::emails.create')->with([
            'users' => $users,
            'groups' => $groups,
            'templates' => [
                'greetings-template' => 'Greetings',
                // 'content' => 'Content',
                'empty-template' => 'Empty',
            ],
        ]);
    }


    public function storeEmail(Request $request)
    {
        $rules = [
            'type' => ['required', 'in:users,groups'],
            'subject' => ['required'],
            'send_at' => ['required'],
        ];

        if ($request->get('type') == 'users') {
            $rules['users'] = ['required', 'array'];
        } else {
            $rules['groups'] = ['required', 'array'];
        }

        $validation = Validator::make($request->all(), $rules);

        if ($validation->fails()) {
            return redirect()->back()->withErrors($validation->errors())->withInput();
        }

        $subject = $request->get('subject');

        if ($request->has('image')) {
            $image = $request->file('image');

            $filename = uniqid("", true) . '.jpg';

            $paths = ['uploads/newsletter'];
            foreach ($paths as $path) {
                if (!file_exists($path)) {
                    mkdir($path, 0755, true);
                }
            }

            Image::make($image->getRealPath())->resize(900, null, function ($constraint) {
                $constraint->aspectRatio();
            })->save('uploads/newsletter/' . $filename);
        }

        if ($request->get('type') == 'users') {
            $users_id = $request->get('users');
            $users = NewsletterUser::find($users_id);
        } else {
            $users = collect();
            NewsletterGroup::find($request->get('groups'))->each(
                function ($item) use ($users) {
                    $item->users->pluck('id')->each(
                        function ($item) use ($users) {
                            $users->push($item);
                        }
                    );
                }
            );
        }


        $schedule = new ScheduleMessage();
        $schedule->name = 'content';
        $schedule->subject = $subject;
        $schedule->message = preg_replace_callback('/src="(.*?)"/', function ($match) {
            if (!parse_url($match[1])) {
                return 'src="' . asset($match[1]) . '"';
            }
            return 'src="' . asset($match[1]) . '"';
        }, $request->get('content'));
        $schedule->attachment = $filename ?? null;
        $schedule->template_name = $request->get('template');
        $schedule->send_at = $request->get('send_at');
        $schedule->mail_from = $request->get('mail_from');
        $schedule->save();

        $schedule->users()->attach($users);

        return back()->with('success', 'Email scheduled successfully');
    }

    public function show(ScheduleMessage $schedule)
    {
        return view('schedule::show')
            ->with('schedule', $schedule);
    }

    public function createSms()
    {
        return view('schedule::sms.create')->with([
            'groups' => NewsletterGroup::all(),
            'users' => NewsletterUser::whereNotNull('phone')->get(),
            'templates' => [
                'greetings-template' => 'Greetings',
                // 'content' => 'Content',
                'empty-template' => 'Empty',
            ],
        ]);
    }

    public function storeSms(Request $request)
    {
        $request->validate([
            'type' => ['required', 'in:users,groups'],
            'subject' => ['required'],
            'content' => ['required', 'string', 'max:960'],
            'send_at' => ['required', 'date'],
        ]);

        $users = collect();

        if ($request->get('type') == 'users') {
            $users_id = $request->get('users');
            $users = NewsletterUser::find($users_id);
        } else {
            $users = collect();
            NewsletterGroup::find($request->get('groups'))->each(
                function ($item) use ($users) {
                    $item->users->pluck('id')->each(
                        function ($item) use ($users) {
                            $users->push($item);
                        }
                    );
                }
            );
        }

        if ($users->count() == 0) {
            return back()->withErrors(['No users found']);
        }

        $schedule = new ScheduleMessage();
        $schedule->name = "greetings";
        $schedule->subject = $request->get('subject');
        $schedule->message = $request->get('content');
        $schedule->send_at = $request->get('send_at');
        $schedule->type = 'sms';
        $schedule->save();

        $schedule->users()->attach($users);

        return back()->with('success', 'SMS scheduled successfully');
    }

    public function destroy(ScheduleMessage $schedule)
    {
        $schedule->delete();

        return back()->with('success', 'Schedule deleted successfully');
    }

    public function editSms(ScheduleMessage $schedule)
    {
        return view('schedule::sms.edit')->with([
            'groups' => NewsletterGroup::all(),
            'users' => NewsletterUser::whereNotNull('phone')->get(),
            'schedule' => $schedule
        ]);
    }

    public function birthdaySettings()
    {
        return view('schedule::birthday.settings')->with([
            'setting' => EmailSendSettings::first(),
        ]);
    }

    public function birthdaySettingsUpdate(Request $request)
    {
        $request->validate([
            'email_setting' => 'required|in:-2,-1,0,1',
        ]);

        $settings = EmailSendSettings::first();
        $settings->email_send_day = $request->input('email_setting');
        $settings->save();

        return redirect()->back()->with('success', 'Settings updated successfully');
    }
}
