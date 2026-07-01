@extends('maintainer.layouts.app')

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

                        <!-- All Maintainer Table Area Start -->
                        <div class="maintaince-request-table-area">
                            <!-- datatable Start -->
                            <div class="bg-off-white theme-border radius-4 p-25">
                                <table id="allDataTable" class="table bg-off-white aaa theme-border p-20 dt-responsive">
                                    <thead>
                                        <tr>
                                            <th>{{ __('Request Id') }}</th>
                                            <th class="all">{{ __('Issue') }}</th>
                                            <th class="desktop">{{ __('Details') }}</th>
                                            <th class="desktop">{{ __('Status') }}</th>
                                            <th class="all">{{ __('Action') }}</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($requests as $request)
                                            <tr>
                                                <td>{{ $request->request_id }}</td>
                                                <td>{{ $request->issue_name }}</td>
                                                <td>{{ Str::limit($request->details, 50, '...') }}</td>
                                                <td>
                                                    @if ($request->status == MAINTENANCE_REQUEST_STATUS_COMPLETE)
                                                        <div class="status-btn status-btn-green font-13 radius-4">
                                                            {{ __('Completed') }}</div>
                                                    @elseif($request->status == MAINTENANCE_REQUEST_STATUS_INPROGRESS)
                                                        <div class="status-btn status-btn-orange font-13 radius-4">
                                                            {{ __('In Progress') }}</div>
                                                    @else
                                                        <div class="status-btn status-btn-red font-13 radius-4">
                                                            {{ __('Pending') }}</div>
                                                    @endif

                                                </td>
                                                <td>
                                                    <div class="tbl-action-btns d-inline-flex">
                                                        <button type="button"
                                                                onclick="getEditModal('{{ route('maintainer.maintenance-request.view', $request->id) }}', '#viewModal')"
                                                                class="p-1 tbl-action-btn reminder" title="{{ __('View') }}">
                                                            <span class="iconify" data-icon="carbon:view-filled"></span>
                                                        </button>
                                                    </div>
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
    <div class="modal fade" id="viewModal" tabindex="-1" aria-labelledby="addModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
            </div>
        </div>
    </div>
    <input type="hidden" id="getInfoRoute" value="{{ route('maintainer.maintenance-request.get.info') }}">
@endsection
@push('style')
    @include('common.layouts.datatable-style')
@endpush

@push('script')
    @include('common.layouts.datatable-script')
    <script src="{{ asset('/') }}assets/js/pages/alldatatables.init.js"></script>
    <script src="{{ asset('assets/js/custom/maintainer-maintenance-request.js') }}"></script>
@endpush
