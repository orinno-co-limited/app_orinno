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
                                                title="{{ __('Dashboard') }}">{{ __('Dashboard') }}</a></li>
                                        <li class="breadcrumb-item">
                                            <a href="{{ route('owner.property.allUnit') }}"
                                                title="{{ __('Properties') }}">{{ __('Properties') }}</a>
                                        </li>
                                        <li class="breadcrumb-item active" aria-current="page">{{ $pageTitle }}</li>
                                    </ol>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="tenants-details-layout-wrap position-relative">
                        <div class="row">
                            <div class="col-md-12 col-lg-12 col-xl-12 col-xxl-12">
                                <div class="account-settings-rightside bg-off-white theme-border radius-4 p-25">
                                    <div class="tenants-details-payment-history">
                                        <div class="account-settings-content-box">
                                            <div class="tenants-details-payment-history-table">
                                                <table id="allDataTable" class="table responsive theme-border p-20">
                                                    <thead>
                                                        <tr>
                                                            <th>{{ __('SL') }}</th>
                                                            <th data-priority="1">{{ __('Name') }}</th>
                                                            <th>{{ __('Image') }}</th>
                                                            <th>{{ __('Property') }}</th>
                                                            <th>{{ __('Tenant') }}</th>
                                                            <th class="text-center">{{ __('Action') }}</th>
                                                        </tr>
                                                    </thead>
                                                    <tbody>
                                                        @foreach ($units as $unit)
                                                            <tr>
                                                                <td>{{ $loop->iteration }}</td>
                                                                <td>{{ $unit->unit_name }}</td>
                                                                <td>
                                                                    <img class="rounded-circle avatar-md tbl-user-image"
                                                                        src="{{ assetUrl($unit->folder_name . '/' . $unit->file_name) }}">
                                                                </td>
                                                                <td>{{ $unit->property_name }}</td>
                                                                <td>
                                                                    @if ($unit->first_name)
                                                                        <span class="text-success">{{ $unit->first_name }}
                                                                            {{ $unit->last_name }}</span>
                                                                    @else
                                                                        <span
                                                                            class="text-danger">{{ __('Not Available') }}</span>
                                                                    @endif
                                                                </td>
                                                                <td class="text-center">
                                                                    @if (is_null($unit->first_name))
                                                                        <button class="p-1 tbl-action-btn deleteItem"
                                                                            data-formid="delete_row_form_{{ $unit->id }}">
                                                                            <span class="iconify"
                                                                                data-icon="ep:delete-filled"></span>
                                                                        </button>
                                                                        <form
                                                                            action="{{ route('owner.property.unit.delete', [$unit->id]) }}"
                                                                            method="post"
                                                                            id="delete_row_form_{{ $unit->id }}">
                                                                            {{ method_field('DELETE') }}
                                                                            <input type="hidden" name="_token"
                                                                                value="{{ csrf_token() }}">
                                                                        </form>
                                                                    @else
                                                                        <a href="{{route('owner.tenant.details', [$unit->tenantId, 'tab' => 'profile'])}}">
                                                                            <span class="iconify" data-icon="carbon:view-filled"></span>
                                                                        </a>
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
