@extends('administrator.layouts.default')

@section('content')

    <div class="main-container" id="page-emails">

        @include('administrator.layouts.partial.heading', [
            'title' => trans('newsletter::admin.emails_index_page_title'),
            'items' => collect([
                ['url' => '', 'title' => __('newsletter::admin.emails_index_page_title')],
            ]),
        ])
        <div class="row">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header">
                        <div class="row">
                            <div class="col-7">
                                <p class="mb-0">
                                    @lang('newsletter::admin.emails_index_page_description')
                                </p>
                            </div>
                            <div class="col-5 d-flex justify-content-end">
                                <div class="dropdown">
                                    <div class="dropdown-menu">
                                        <a class="dropdown-item"
                                            href="{{ url('administrator/emails/emails/create/content') }}">
                                            @lang('newsletter::admin.emails_index_add_content')
                                        </a>
                                        <a class="dropdown-item"
                                            href="{{ url('administrator/emails/emails/create/custom') }}">
                                            @lang('newsletter::admin.emails_index_add_custom')
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

                            @if ($emails->count() > 0)
                                <table class="table table-bordered table-full table-hover table-full-small mb-0"
                                    id="dataTable">
                                    <thead>
                                        <th>{{ trans('newsletter::admin.emails_index_email') }}</th>
                                        <th>{{ trans('newsletter::admin.emails_index_email_type') }}</th>
                                        <th>{{ trans('newsletter::admin.emails_index_status') }}</th>
                                        <th>{{ trans('admin.actions') }}</th>
                                    </thead>
                                    <tbody>
                                        @foreach ($emails as $message)
                                            <tr>
                                                <td>{{ $message->email }}</td>
                                                <td>{{ $message->email_type }}</td>
                                                <td>
                                                    {{ $message->status }}
                                                </td>
                                                <td>
                                                    <button class="btn btn-sm btn-info" type="button"
                                                        onclick="location.href='{{ route('schedule.logs.email.show', $message->id) }}';"><i
                                                            class="fa fa-eye"></i>
                                                        {{ trans('admin.preview') }}</button>
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            @else
                                <div class="alert alert-info">{{ trans('newsletter::admin.emails_index_empty') }}</div>
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
        var emailsTranslations = {
            'js_remove': '{{ __('newsletter::admin.js_remove') }}'
        };
    </script>
@endsection
