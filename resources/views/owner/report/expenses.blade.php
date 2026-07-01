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
                        <div class="tenants-top-bar">
                            <div class="property-search-inner-bg bg-off-white theme-border radius-4 p-25 pb-0 mb-25">
                                <form action="">
                                    <div class="row">
                                        <div class="col-md-3 mb-25">
                                            <select class="form-select flex-shrink-0" name="property_id" id="property_id">
                                                <option value="" selected>--{{ __('Select Property') }}--</option>
                                                @foreach ($properties as $property)
                                                    <option value="{{ $property->id }}">{{ $property->name }}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                        <div class="col-md-3 mb-25">
                                            <select class="form-select flex-shrink-0" name="unit_id" id="unit_id">
                                                <option value="" selected>--{{ __('Select Option') }}--</option>
                                            </select>
                                        </div>
                                        <div class="col-md-5 mb-25">
                                            <div class="input-group">
                                                <span class="input-group-text">{{ __('From') }}</span>
                                                <input type="date" class="form-control" placeholder="Start Date"
                                                    id="start_date" name="start_date" aria-label="Start Date">
                                                <span class="input-group-text">{{ __('to') }}</span>
                                                <input type="date" class="form-control" placeholder="End Date"
                                                    id="end_date" name="end_date" aria-label="End Date">
                                            </div>
                                        </div>
                                        <div class="col-auto mb-25">
                                            <button type="button" class="default-btn theme-btn-purple w-auto"
                                                id="searchBtn" title="{{ __('Search') }}">{{ __('Search') }}</button>
                                        </div>
                                    </div>
                                </form>
                            </div>
                        </div>
                        <div class="notice-board-table-area">
                            <div class="bg-off-white theme-border radius-4 p-25">
                                <table id="allExpenseDataTable"
                                    class="table bg-off-white aaa theme-border p-20 dt-responsive">
                                    <thead>
                                        <tr>
                                            <th>{{ __('SL') }}</th>
                                            <th class="text-center">{{ __('Name') }}</th>
                                            <th class="text-center">{{ __('Property') }}</th>
                                            <th class="text-center">{{ __('Unit') }}</th>
                                            <th class="text-center">{{ __('Date') }}</th>
                                            <th class="text-end">{{ __('Amount') }}</th>
                                        </tr>
                                    </thead>
                                    <tfoot>
                                        <tr>
                                            <th colspan="4"></th>
                                            <th></th>
                                            <th></th>
                                        </tr>
                                    </tfoot>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <input type="hidden" id="expenseReportRoute" value="{{ route('owner.reports.expenses') }}">
    <input type="hidden" id="getPropertyUnitsRoute" value="{{ route('owner.property.getPropertyUnits') }}">
@endsection
@push('style')
    @include('common.layouts.datatable-style')
@endpush
@push('script')
    @include('common.layouts.datatable-script')
    <script src="{{ asset('assets/js/custom/report-expense.js') }}"></script>
@endpush
