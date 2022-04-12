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
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="name">@lang('schedule::admin.scheduling_index_name')</label>
                                        <input type="text" class="form-control" id="name" name="name"
                                            value="{{ $email->subject }}" disabled>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="name">@lang('schedule::admin.scheduling_index_email')</label>
                                        <input type="text" class="form-control" id="name" name="name"
                                            value="{{ $email->email }}" disabled>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-8">
                                    <div class="form-group">
                                        <label for="name">@lang('schedule::admin.scheduling_index_description')</label>
                                        <div class="p-3 border bg-white">
                                            {!! $email->message !!}
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="name">@lang('schedule::admin.scheduling_index_status')</label>
                                        <input type="text" class="form-control" id="name" name="name"
                                            value="{{ $email->status }}" disabled>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="name">@lang('schedule::admin.scheduling_index_send_at')</label>
                                        <input type="text" class="form-control" id="name" name="name"
                                            value="{{ $email->created_at }}" disabled>
                                    </div>
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
