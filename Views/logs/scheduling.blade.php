@extends('administrator.layouts.default')

@section('content')

    <div class="main-container" id="page-scheduling">

        @include('administrator.layouts.partial.heading', [
            'title' => trans('newsletter::admin.scheduling_index_page_title'),
            'items' => collect([
                ['url' => '', 'title' => __('newsletter::admin.scheduling_index_page_title')],
            ]),
        ])
        <div class="row">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header">
                        <div class="row">
                            <div class="col-7">
                                <p class="mb-0">
                                    @lang('newsletter::admin.scheduling_index_page_description')
                                </p>
                            </div>
                            <div class="col-5 d-flex justify-content-end">
                                <div class="dropdown">
                                    <div class="dropdown-menu">
                                        <a class="dropdown-item"
                                            href="{{ url('administrator/scheduling/scheduling/create/content') }}">
                                            @lang('newsletter::admin.scheduling_index_add_content')
                                        </a>
                                        <a class="dropdown-item"
                                            href="{{ url('administrator/scheduling/scheduling/create/custom') }}">
                                            @lang('newsletter::admin.scheduling_index_add_custom')
                                        </a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="card-body p-0">
                        <section>
                            @if (Session::get('success'))
                                <div class="alert alert-success">{{ Session::get('success') }}</div>
                            @endif

                            @if ($schedules->count() > 0)
                                <table class="table table-bordered table-full table-hover table-full-small mb-0"
                                    id="dataTable">
                                    <thead>
                                        <th>{{ trans('newsletter::admin.scheduling_index_name') }}</th>
                                        <th>{{ trans('newsletter::admin.scheduling_index_type') }}</th>
                                        <th>{{ trans('newsletter::admin.scheduling_index_status') }}</th>
                                        <th>{{ trans('newsletter::admin.scheduling_index_send_at') }}</th>
                                        <th>{{ trans('newsletter::admin.scheduling_index_users') }}</th>
                                        <th>{{ trans('admin.actions') }}</th>
                                    </thead>
                                    <tbody>
                                        @foreach ($schedules as $schedule)
                                            <tr>
                                                <td>{{ $schedule->name }}</td>
                                                <td>{{ $schedule->type }}</td>
                                                <td>
                                                    <span
                                                        class="badge p-2 badge-{{ $schedule->is_sent ? 'success' : 'warning' }}">{{ $schedule->is_sent ? 'Worked' : 'Waiting' }}</span>
                                                </td>
                                                <td>
                                                    @if ($schedule->send_at)
                                                        {{ $schedule->send_at }}
                                                    @endif
                                                </td>
                                                <td>

                                                    <span
                                                        class="badge badge-primary">{{ $schedule->users->count() }}</span>
                                                </td>
                                                <td>
                                                    <button class="btn btn-sm btn-info" type="button"
                                                        onclick="location.href='{{ url('administrator/scheduling/scheduling/' . $schedule->id . '/show') }}';"><i
                                                            class="fa fa-eye"></i>
                                                        {{ trans('admin.preview') }}</button>
                                                    <button class="btn btn-sm btn-danger btn-destroy"
                                                        data-url="{{ url('administrator/scheduling/scheduling/' . $schedule->id . '/destroy') }}"><i
                                                            class="fa fa-trash-alt"></i>
                                                        {{ trans('admin.remove') }}</button>
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            @else
                                <div class="alert alert-info">{{ trans('newsletter::admin.scheduling_index_empty') }}
                                </div>
                            @endif
                        </section>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('scripts')
    <script>
        var schedulingTranslations = {
            'js_remove': '{{ __('newsletter::admin.js_remove') }}'
        };
    </script>
@endsection
