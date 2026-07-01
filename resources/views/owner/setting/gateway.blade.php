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
                                        <li class="breadcrumb-item active" aria-current="page">{{ $pageTitle }}</li>
                                    </ol>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="settings-page-layout-wrap position-relative">
                        <div class="row">
                            @include('owner.setting.sidebar')
                            <div class="col-md-12 col-lg-12 col-xl-8 col-xxl-9">
                                <div class="account-settings-rightside bg-off-white theme-border radius-4 p-25">
                                    <div class="color-settings-page-area">
                                        <div class="account-settings-content-box">
                                            <div class="account-settings-title border-bottom mb-20 pb-20">
                                                <div class="row align-items-center">
                                                    <div class="align-items-center d-flex justify-content-between">
                                                        <h4>{{ $pageTitle }}</h4>
                                                        <a title="{{__('Sync missing gateway')}}" href="{{ route('owner.setting.gateway.sync') }}" class="theme-btn me-3">
                                                            <i class="fa fa-sync-alt"></i>
                                                        </a>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="information-table-area">
                                                <div class="bg-off-white theme-border radius-4 p-25">
                                                    <table id="gatewayDataTable"
                                                        class="table bg-off-white theme-border dt-responsive">
                                                        <thead>
                                                            <tr>
                                                                <th>{{ __('SL') }}</th>
                                                                <th>{{ __('Image') }}</th>
                                                                <th>{{ __('Title') }}</th>
                                                                <th>{{ __('Slug') }}</th>
                                                                <th>{{ __('Status') }}</th>
                                                                <th>{{ __('Mode') }}</th>
                                                                <th>{{ __('Action') }}</th>
                                                            </tr>
                                                        </thead>
                                                        <tbody>
                                                            @foreach ($gateways as $gateway)
                                                                <tr>
                                                                    <td>{{ $loop->iteration }}</td>
                                                                    <td>
                                                                        <div class="upload-profile-photo-box mb-25">
                                                                            <div
                                                                                class="profile-user position-relative d-inline-block">
                                                                                <img src="{{ $gateway->icon }}"
                                                                                    class="rounded-circle avatar-xl maintainer-user-profile-image image"
                                                                                    alt="">
                                                                            </div>
                                                                        </div>
                                                                    </td>
                                                                    <td>{{ $gateway->title }}</td>
                                                                    <td>{{ $gateway->slug }}</td>
                                                                    <td>
                                                                        @if ($gateway->status == ACTIVE)
                                                                            <div
                                                                                class="status-btn status-btn-green font-13 radius-4">
                                                                                {{ __('Active') }}</div>
                                                                        @else
                                                                            <div
                                                                                class="status-btn status-btn-orange font-13 radius-4">
                                                                                {{ __('Deactivate') }}</div>
                                                                        @endif
                                                                    </td>
                                                                    <td>
                                                                        @if ($gateway->mode == GATEWAY_MODE_LIVE)
                                                                            <div
                                                                                class="status-btn status-btn-green font-13 radius-4">
                                                                                {{ __('Live') }}</div>
                                                                        @else
                                                                            <div
                                                                                class="status-btn status-btn-orange font-13 radius-4">
                                                                                {{ __('Sandbox') }}</div>
                                                                        @endif
                                                                    </td>
                                                                    <td>
                                                                        <button type="button"
                                                                            class="p-1 tbl-action-btn edit"
                                                                            data-id="{{ $gateway->id }}"
                                                                            title="{{ __('Edit') }}"><span
                                                                                class="iconify"
                                                                                data-icon="clarity:note-edit-solid"></span>
                                                                        </button>
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
    </div>

    {{-- Modal  --}}
    <div class="modal fade" id="editModal" tabindex="-1" aria-labelledby="editModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title" id="editModalLabel">{{ __('Edit Gateway') }}</h4>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"><span
                            class="iconify" data-icon="akar-icons:cross"></span></button>
                </div>
                <form class="ajax" action="{{ route('owner.setting.gateway.store') }}" method="POST"
                    data-handler="getShowMessage">
                    @csrf
                    <input type="hidden" name="id" id="id" required>
                    <div class="modal-body">
                        <h4 class="mb-15">{{ __('Gateway') }}</h4>
                        <div class="modal-inner-form-box bg-off-white theme-border radius-4 p-20">
                            <div class="row">
                                <div class="upload-profile-photo-box mb-25">
                                    <div class="profile-user position-relative d-inline-block">
                                        <img src=""
                                            class="rounded-circle avatar-xl maintainer-user-profile-image image"
                                            alt="">
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-6 mb-25">
                                    <label
                                        class="label-text-title color-heading font-medium mb-2">{{ __('Title') }}</label>
                                    <input type="text" class="form-control title" readonly>
                                </div>
                                <div class="col-md-6 mb-25">
                                    <label
                                        class="label-text-title color-heading font-medium mb-2">{{ __('Slug') }}</label>
                                    <input type="text" name="slug" class="form-control slug" readonly>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-6 mb-25">
                                    <label
                                        class="label-text-title color-heading font-medium mb-2">{{ __('Status') }}</label>
                                    <select name="status" id="status" class="form-control">
                                        <option value="0">{{ __('Deactive') }}</option>
                                        <option value="1">{{ __('Active') }}</option>
                                    </select>
                                </div>
                                <div class="col-md-6 mb-25 mode-div">
                                    <label
                                        class="label-text-title color-heading font-medium mb-2">{{ __('Mode') }}</label>
                                    <select name="mode" id="mode" class="form-control">
                                        <option value="1">{{ __('Live') }}</option>
                                        <option value="2">{{ __('Sandbox') }}</option>
                                    </select>
                                </div>
                            </div>
                            <div class="bank-div">
                                <div class="bank-div-append">

                                </div>
                                <div class="row mb-20">
                                    <div class="col-12 text-end"><button type="button" class="green-color add-bank"
                                            title="{{ __('Add Bank') }}"><span class="iconify"
                                                data-icon="material-symbols:add"></span> {{ __('Add Bank') }}</button>
                                    </div>
                                </div>
                            </div>

                            <div class="row url-div">
                                <div class="col-md-12 mb-25 gateway-input" id="gateway-url">
                                    <label
                                        class="label-text-title color-heading font-medium mb-2">{{ __('Url') }}/{{ __('Hash') }}</label>
                                    <input class="form-control" type="text" name="url">
                                </div>
                            </div>
                            <div class="row key-secret-div">
                                <div class="col-md-12 mb-25 gateway-input" id="gateway-key">
                                    <label
                                        class="label-text-title color-heading font-medium mb-2">{{ __('Key') }}</label>
                                    <input class="form-control" type="text" name="key">
                                    <small
                                        class="d-none small">{{ __('Client id, Public Key, Key, Store id, Api Key') }}</small>
                                </div>
                                <div class="col-md-12 mb-25 gateway-input" id="gateway-secret">
                                    <label
                                        class="label-text-title color-heading font-medium mb-2">{{ __('Secret') }}</label>
                                    <input class="form-control" type="text" name="secret">
                                    <small
                                        class="d-none small">{{ __('Client Secret, Secret, Store Password, Auth Token') }}</small>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-12">
                                    <label
                                        class="label-text-title color-heading font-medium mb-2">{{ __('Conversion Rate') }}
                                        <button type="button" class="add-currency edit-btn"><span class="iconify"
                                                data-icon="material-symbols:add-rounded"></span></button>
                                    </label>
                                    <div id="currencyConversionRateSection"></div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer justify-content-start">
                        <button type="button" class="theme-btn-back me-3" data-bs-dismiss="modal"
                            title="{{ __('Back') }}">{{ __('Back') }}</button>
                        <button type="submit" class="theme-btn me-3"
                            title="{{ __('Submit') }}">{{ __('Update') }}</button>
                    </div>
                </form>

            </div>
        </div>
    </div>
    <input type="hidden" id="getInfoRoute" value="{{ route('owner.setting.gateway.get.info') }}">
    <input type="hidden" id="getCurrencySymbol" value="{{ getCurrencySymbol() }}">
    <input type="hidden" id="allCurrency" value="{{ json_encode(getCurrency()) }}">
    <input type="hidden" id="gatewaySettings" value="{{ gatewaySettings() }}">
    <input type="hidden" id="supportedCurrency" value="{{json_encode(getGatewaySupportedCurrencies())}}">
@endsection
@push('style')
    @include('common.layouts.datatable-style')
@endpush

@push('script')
    @include('common.layouts.datatable-script')
    <script src="{{ asset('assets/js/custom/gateway.js') }}"></script>
@endpush
