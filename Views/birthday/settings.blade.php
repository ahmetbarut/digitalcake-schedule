@extends('administrator.layouts.default')

@section('content')
    <div class="main-container" id="page-newsletter-send-greetings">

        @include('administrator.layouts.partial.heading', [
            'title' => trans('schedule::admin.birthday_settings'),
            'items' => collect([
                [
                    'url' => url('administrator/schedule'),
                    'title' => __('schedule::admin.schedule'),
                ],
                ['url' => '', 'title' => __('schedule::admin.birthday_settings')],
            ]),
            'buttons' => collect(),
        ])

        <div class="row">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-body">
                        <section>
                            <div class="page-header">
                                <p class="lead"> {{ trans('schedule::admin.birthday_settings') }}
                                </p>
                            </div>

                            @if ($errors->count())
                                <div class="alert alert-warning">{{ $errors->first() }}</div>
                            @endif
                            <div class="well">
                                {!! Form::open(['class' => 'form-floating', 'files' => true, 'url' => route('schedule.birthday.settings.update')]) !!}

                                <div class="form-group{!! $errors->has('email_setting') ? ' has-error' : '' !!}">
                                    {!! Form::label('email_setting', trans('schedule::admin.birthday_email_settings'), ['class' => 'control-label']) !!}
                                    <select name="email_setting" id="" class="form-control">
                                        <option {{ $setting->email_send_day == '-2' ? 'selected' : ''}} value="-2">2 days ago</option>
                                        <option {{ $setting->email_send_day == '-1' ? 'selected' : ''}} value="-1">1 day ago</option>
                                        <option {{ $setting->email_send_day == '0' ? 'selected' : ''}} value="0">on the day</option>
                                        <option {{ $setting->email_send_day == '1' ? 'selected' : ''}} value="1">1 day after</option>
                                    </select>
                                </div>

                                <div class="form-group">
                                    <button class="btn btn-primary"
                                        type="submit">{{ trans('schedule::admin.send_greetings_submit') }}</button>
                                    <button class="btn btn-default" type="button"
                                        onclick="location.href='{{ url('administrator/newsletter/send') }}'">{{ trans('admin.cancel') }}</button>
                                </div>
                                {!! Form::close() !!}
                            </div>
                        </section>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
