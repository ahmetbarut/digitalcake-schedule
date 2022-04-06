@extends('administrator.layouts.default')

@section('content')

    <div class="main-container" id="page-newsletter-send-greetings">

        @include('administrator.layouts.partial.heading', [
            'title' => trans('schedule::admin.send_greetings_page_title'),
            'items' => collect([
                [
                    'url' => url('administrator/newsletter/send'),
                    'title' => __('schedule::admin.send_index_page_title'),
                ],
                ['url' => '', 'title' => __('schedule::admin.send_greetings_page_title')],
            ]),
            'buttons' => collect(),
        ])

        <div class="row">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-body">
                        <section>
                            <div class="page-header">
                                <p class="lead"> {{ trans('schedule::admin.send_greetings_page_description') }}
                                </p>
                            </div>

                            @if ($errors->count())
                                <div class="alert alert-warning">{{ $errors->first() }}</div>
                            @endif
                            <div class="well">
                                {!! Form::open(['class' => 'form-floating', 'files' => true, 'url' => route('schedule.store.email')]) !!}
                                <div class="form-group{!! $errors->has('type') ? ' has-error' : '' !!}">
                                    {!! Form::label('type', trans('schedule::admin.send_greetings_type'), ['class' => 'control-label']) !!}
                                    {!! Form::select('type', ['users' => trans('schedule::admin.send_greetings_type_users'), 'groups' => trans('schedule::admin.send_greetings_type_groups')], old('type'), ['class' => 'form-control selectpicker']) !!}
                                </div>
                                <div class="users-list-container">
                                    @if ($users->count())
                                        <div class="users-list">
                                            @foreach ($users as $user)
                                                <div class="checkbox">
                                                    <label>
                                                        <input type="checkbox" name="users[]" value="{{ $user->id }}">
                                                        {{ $user->email }}
                                                    </label>
                                                </div>
                                            @endforeach
                                        </div>
                                    @else
                                        <div class="alert alert-warning">
                                            {{ trans('schedule::admin.send_greetings_users_empty') }}</div>
                                    @endif
                                </div>
                                <div class="groups-list-container">
                                    @if ($groups->count())
                                        <div class="groups-list">
                                            @foreach ($groups as $group)
                                                <div class="checkbox">
                                                    <label>
                                                        <input type="checkbox" name="groups[]" value="{{ $group->id }}">
                                                        {{ $group->name }} ({{ $group->users->count() }})
                                                    </label>
                                                </div>
                                            @endforeach
                                        </div>
                                    @else
                                        <div class="alert alert-warning">
                                            {{ trans('schedule::admin.send_greetings_groups_empty') }}</div>
                                    @endif
                                </div>
                                <div class="form-group{!! $errors->has('subject') ? ' has-error' : '' !!}">
                                    {!! Form::label('subject', trans('schedule::admin.send_greetings_subject'), ['class' => 'control-label']) !!}
                                    {!! Form::text('subject', old('subject'), ['class' => 'form-control']) !!}
                                </div>
                                <div class="type">
                                  
                                    <div id="empty-template" class="form-group tinymce{!! $errors->has('content') ? ' has-error' : '' !!}">
                                        {!! Form::label('content', trans('newsletter::admin.send_empty_message'), ['class' => 'control-label']) !!}
                                        {!! Form::textarea('content', old('content'), ['class' => 'form-control tinymce']) !!}
                                    </div>
                                </div>
                                <div class="form-group{!! $errors->has('mail_from') ? ' has-error' : '' !!}">
                                    <label for="mail_from">@lang('schedule::admin.mail_from')</label>
                                    <input type="text" class="form-control" id="mail_from" name="mail_from"
                                        value="{{ old('mail_from') }}" placeholder="info@fibif.be">
                                </div>
                                <div class="form-group{!! $errors->has('date') ? ' has-error' : '' !!}">
                                    <label for="send_at">@lang('schedule::admin.send_at')</label>
                                    <input type="text" class="form-control datetimepicker" id="send_at" name="send_at"
                                        value="{{ old('date') }}">
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
@section('scripts')
    <script>
        $(document).ready(function() {
            $('.datetimepicker').datetimepicker({
                format: 'd-m-Y H:i:s',
                minDate: new Date(),
            });
        });
    </script>
@endsection
