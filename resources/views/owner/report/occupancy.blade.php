@extends('owner.layouts.app')

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
                                        <li class="breadcrumb-item"><a href="{{ route('owner.dashboard') }}"
                                                title="Dashboard">{{ __('Dashboard') }}</a></li>
                                        <li class="breadcrumb-item" aria-current="page">{{ __('Report') }}</li>
                                        <li class="breadcrumb-item active" aria-current="page">{{ $pageTitle }}</li>
                                    </ol>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="notice-board-table-area">
                            <div class="bg-off-white theme-border radius-4 p-25">
                                <table id="occupancyReportDataTable"
                                    class="table bg-off-white aaa theme-border p-20 dt-responsive">
                                    <thead>
                                        <tr>
                                            <th>{{ __('SL') }}</th>
                                            <th class="text-center" data-priority="1">{{ __('Property Name') }}</th>
                                            <th class="text-center">{{ __('Address') }}</th>
                                            <th class="text-center">{{ __('Total Unit') }}</th>
                                            <th class="text-end">{{ __('Available Unit') }}</th>
                                            {{-- <th class="text-end">{{ __('Turnover Rate') }}</th> --}}
                                        </tr>
                                    </thead>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <input type="hidden" id="occupancyReportRoute" value="{{ route('owner.reports.occupancy') }}">
@endsection
@push('style')
    @include('common.layouts.datatable-style')
@endpush
@push('script')
    @include('common.layouts.datatable-script')
    <script src="{{ asset('assets/js/custom/report-occupancy.js') }}"></script>
@endpush
