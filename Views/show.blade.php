@extends('administrator.layouts.default')

@section('content')
    <div class="main-container" id="page-scheduling">

        @include('administrator.layouts.partial.heading', [
            'title' => trans('schedule::admin.scheduling_index_page_title'),
            'items' => collect([
                ['url' => '', 'title' => __('schedule::admin.scheduling_index_page_title')],
            ]),
        ])
        <div class="row">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header">
                        <div class="row">
                            <div class="col-7">
                                <p class="mb-0">
                                    {{ $schedule->name }}
                                </p>
                            </div>
                            <div class="col-5 d-flex justify-content-end">
                                <div class="dropdown">
                                    <div class="dropdown-menu">
                                        <a class="dropdown-item"
                                            href="{{ url('administrator/scheduling/scheduling/create/content') }}">
                                            @lang('schedule::admin.scheduling_index_add_content')
                                        </a>
                                        <a class="dropdown-item"
                                            href="{{ url('administrator/scheduling/scheduling/create/custom') }}">
                                            @lang('schedule::admin.scheduling_index_add_custom')
                                        </a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="card-body p-0">
                        <div class="card-header">
                            <div class="row">
                                <div class="col-md-8">
                                    <div class="form-group">
                                        <label for="name">@lang('schedule::admin.scheduling_index_name')</label>
                                        <input type="text" class="form-control" id="name" name="name"
                                            value="{{ $schedule->subject }}" disabled>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label for="name">@lang('schedule::admin.scheduling_index_type')</label>
                                        <input type="text" class="form-control" id="name" name="name"
                                            value="{{ $schedule->type }}" disabled>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-8">
                                    <div class="form-group">
                                        <label for="name">@lang('schedule::admin.scheduling_index_description')</label>
                                        <div class="p-3 border bg-white">
                                            {!! $schedule->message !!}
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="name">@lang('schedule::admin.scheduling_index_status')</label>
                                        <input type="text" class="form-control" id="name" name="name"
                                            value="{{ $schedule->status ? 'active' : 'passive' }}" disabled>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="name">@lang('schedule::admin.scheduling_index_send_at')</label>
                                        <input type="text" class="form-control" id="name" name="name"
                                            value="{{ $schedule->send_at }}" disabled>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-12">
                                    <table class="table table-hover bg-white">
                                        <thead>
                                            <tr>
                                                <th>
                                                    @lang('schedule::admin.scheduling_index_name')
                                                </th>
                                                <th>
                                                    @if ($schedule->type == 'email')
                                                        @lang('schedule::admin.scheduling_index_email')
                                                    @elseif ($schedule->type == 'sms')
                                                        @lang('schedule::admin.scheduling_index_sms')
                                                    @endif
                                                </th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach ($schedule->users as $user)
                                                <tr>
                                                    <td>
                                                        {{ implode(' ', [$user->name, $user->surname]) }}
                                                    </td>
                                                    <td>
                                                        @if ($schedule->type == 'email')
                                                            {{ $user->email }}
                                                        @elseif ($schedule->type == 'sms')
                                                            {{ $user->phone }}
                                                        @endif
                                                    </td>
                                                </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('scripts')
    <script>
        var schedulingTranslations = {
            'js_remove': '{{ __('schedule::admin.js_remove') }}'
        };
    </script>
@endsection
