@extends('owner.layouts.app')

@section('content')
    <div class="main-content">
        <div class="page-content">
            <div class="container-fluid">
                <div class="page-content-wrapper bg-white p-30 radius-20">
                    <div class="row">
                        <div class="col-12">
                            <div
                                class="page-title-box d-sm-flex align-items-center justify-content-between flex-wrap g-20 border-bottom mb-20">
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
                            <div class="bg-off-white theme-border radius-4 p-25 table-responsive">
                                <table class="table bg-off-white theme-border p-20">
                                    <thead>
                                        <tr>
                                            <th>{{ __('SL') }}</th>
                                            <th data-priority="1">{{ __('Month') }}</th>
                                            <th>{{ __('Income') }}</th>
                                            <th>{{ __('Expense') }}</th>
                                            <th>{{ __('Profit/Loss') }}</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($lossProfits as $lossProfit)
                                            <tr>
                                                <td>{{ $loop->iteration }}</td>
                                                <td>{{ $lossProfit['month'] }}</td>
                                                <td>{{ currencyPrice($lossProfit['income']) }}</td>
                                                <td>{{ currencyPrice($lossProfit['expense']) }}</td>
                                                <td>{{ $lossProfit['income'] - $lossProfit['expense'] }}</td>
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
