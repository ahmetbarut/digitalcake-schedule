@extends('administrator.layouts.default')
@section('content')
    <div class="main-container" id="page-faq-add">
        @include('administrator.layouts.partial.heading', [
            'title' => trans('newsletter::admin.add_page_title'),
            'items' => collect([
                ['url' => route('faq.index'), 'title' => trans('newsletter::admin.index_page_title')],
                ['url' => '', 'title' => trans('newsletter::admin.add_page_title')],
            ]),
            'buttons' => collect(),
        ])

        {!! Form::open(['class' => 'form-floating', 'url' => 'administrator/newsletter/email-update']) !!}
        <div class="row">
            <div class="col-12">
                @include('administrator.layouts.alerts')
                <div class="card">
                    <div class="card-body">
                        <div class="well white">
                            <div class="languages-selector">
                                @if ($errors->count())
                                    <div class="alert alert-warning">{{ $errors->first() }}</div>
                                @endif

                                <div class="form-group">
                                    <label for="schedule"
                                        class="control-label">{{ trans('newsletter::admin.add_schedule') }}</label>
                                    <select class="form-control" name="email_send_day" id="schedule">
                                        <option {{ $settings->email_send_day == -2 ? 'selected' : null }} value="-2">
                                            {{ __('newsletter::admin.before_two_days') }}</option>
                                        <option {{ $settings->email_send_day == -1 ? 'selected' : null }} value="-1">
                                            {{ __('newsletter::admin.before_one_day') }}</option>
                                        <option {{ $settings->email_send_day == 0 ? 'selected' : null }} value="0">
                                            {{ __('newsletter::admin.same_day') }}</option>
                                        <option {{ $settings->email_send_day == 1 ? 'selected' : null }} value="1">
                                            {{ __('newsletter::admin.after_one_day') }}</option>
                                    </select>
                                </div>
                                <hr>
                                <div class="form-group mb-0">
                                    <button class="btn btn-primary"
                                        type="submit">{{ trans('newsletter::admin.save') }}</button>
                                    <button class="btn btn-secondary" type="button"
                                        onclick="location.href='{{ url('administrator/faq') }}'">{{ trans('admin.cancel') }}</button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div> <!-- end col -->
            </div>
        </div>
        {!! Form::close() !!}
        @if ($staffNotEmail->count() > 0)
            <div class="alert alert-danger">
                @lang('newsletter::admin.staff_not_email')
            </div>
            <div class="row">
                <div class="col-12">
                    <table class="table table-hover">
                        <thead>
                            <tr>
                                <th>
                                    @lang('newsletter::admin.staff_name')
                                </th>
                                <th>
                                    @lang('admin.actions')
                                </th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($staffNotEmail as $staff)
                                <tr>
                                    <td>
                                        {{ $staff->translations->first()->name }}
                                    </td>
                                    <td>
                                        <a href="{{ route('administrator.staff.edit', $staff->id) }}"
                                            class="btn btn-primary btn-sm">
                                            @lang('admin.edit')
                                        </a>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        @endif
    @endsection
