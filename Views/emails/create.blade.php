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
                                {!! Form::open(['class' => 'form-floating', 'files' => true, 'url' => route('administrator.schedule.store.email')]) !!}
                                <label for="template">Type</label>
                                <select name="template" id="template" class="form-control">
                                    <option selected disabled>Select Template</option>
                                    @foreach ($templates as $template => $name)
                                        <option value="{{ $template }}">{{ $name }}</option>
                                    @endforeach
                                </select>
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
                                    <div id="greetings-template" class="form-group d-none {!! $errors->has('image') ? ' has-error' : '' !!}">
                                        {!! Form::file('image') !!}
                                        <span
                                            class="help-block">{{ trans('schedule::admin.send_greetings_image_info') }}</span>
                                    </div>
                                    <div id="empty-template" class="form-group d-none tinymce{!! $errors->has('content') ? ' has-error' : '' !!}">
                                        {!! Form::label('content', trans('newsletter::admin.send_empty_message'), ['class' => 'control-label']) !!}
                                        {!! Form::textarea('content', old('content'), ['class' => 'form-control tinymce']) !!}
                                    </div>
                                </div>
                                <div class="d-flex flex-column">
                                    <div class="col">
                                        <div class="form-group{!! $errors->has('date') ? ' has-error' : '' !!}">
                                            <label for="send_at">@lang('schedule::admin.send_at')</label>
                                            <input type="text" class="form-control datetimepicker" id="send_at"
                                                name="send_at" value="{{ old('date') }}">
                                        </div>
                                    </div>
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
            $('#template').on('change', function() {
                var template = $(this).val();
                if (template == 'greetings-template') {
                    $('#greetings-template').removeClass('d-none');
                    $('#empty-template').addClass('d-none');
                } else if (template == 'empty-template') {
                    $('#greetings-template').addClass('d-none');
                    $('#empty-template').removeClass('d-none');
                }
            });
        });
    </script>
@endsection
