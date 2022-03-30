<?php

namespace Digitalcake\Scheduling\Controllers;

use App\Http\Controllers\Administrator\BaseController;
use Illuminate\Http\Request;
use App\Extensions\Newsletter\Models\User as NewsletterUser;
use App\Extensions\Newsletter\Models\Group as NewsletterGroup;
use Digitalcake\Scheduling\Models\ScheduleMessage;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Validator;
use Intervention\Image\Facades\Image;

class ScheduleController extends BaseController
{
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
            'image' => ['required', 'image'],
            'content' => ['required_if:template,empty-template'],
            'image' => ['required_if:template,greetings-template'],
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

        if ($request->get('template') == 'empty-template') {
            $name = 'empty_newsletter';
        }
        if ($request->get('template') == 'greetings-template') {
            $name = 'greetings';
        }

        $schedule = new ScheduleMessage();
        $schedule->name = $name;
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

    public function edit()
    {
        # code...
    }

    public function update()
    {
        # code...
    }
}
