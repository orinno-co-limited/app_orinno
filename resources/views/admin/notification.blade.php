@extends('admin.layouts.app')

@section('content')
    <div class="main-content">
        <div class="page-content">
            <div class="container-fluid">
                <div class="page-content-wrapper bg-white p-30 radius-20">
                    <div class="row">
                        <div class="col-12">
                            <div
                                class="page-title-box d-sm-flex align-items-center justify-content-between border-bottom mb-20">
                                <div class="page-title-left">
                                    <h3 class="mb-sm-0">{{ $pageTitle }}</h3>
                                </div>
                                <div class="page-title-right">
                                    <ol class="breadcrumb mb-0">
                                        <li class="breadcrumb-item"><a href="{{ route('tenant.dashboard') }}"
                                                title="{{ __('Dashboard') }}">{{ __('Dashboard') }}</a></li>
                                        <li class="breadcrumb-item active" aria-current="page">{{ $pageTitle }}</li>
                                    </ol>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="information-table-area">
                            <div class="table-responsive bg-off-white theme-border radius-4 p-25">
                                <table id="allDataTable" class="table bg-off-white theme-border p-20 dt-responsive">
                                    <thead>
                                        <tr>
                                            <th>{{ __('SL') }}</th>
                                            <th>{{ __('Image') }}</th>
                                            <th class="all">{{ __('Name') }}</th>
                                            <th class="desktop">{{ __('Title') }}</th>
                                            <th class="desktop">{{ __('Body') }}</th>
                                            <th class="desktop">{{ __('Time') }}</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach (getNotification(auth()->id()) as $notification)
                                            <tr>
                                                <td>{{ $loop->iteration }}</td>
                                                <td><img src="{{ getFileUrl($notification->folder_name, $notification->file_name) }}"
                                                        class="me-3 rounded-circle avatar-xs" alt="user-pic"></td>
                                                <td>{{ $notification->first_name }} {{ $notification->last_name }}</td>
                                                <td>{!! $notification->title !!}</td>
                                                <td>{!! $notification->body !!}</td>
                                                <td>{{ $notification->created_at->diffForHumans() }}</td>
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
@endsection
@push('style')
    @include('common.layouts.datatable-style')
@endpush

@push('script')
    @include('common.layouts.datatable-script')
    <script src="{{ asset('assets/js/pages/alldatatables.init.js') }}"></script>
@endpush
