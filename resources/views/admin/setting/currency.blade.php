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
                                    <h3 class="mb-sm-0">{{ __('Settings') }}</h3>
                                </div>

                                <div class="page-title-right">
                                    <ol class="breadcrumb mb-0">
                                        <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}"
                                                title="Dashboard">{{ __('Dashboard') }}</a></li>
                                        <li class="breadcrumb-item"><a href="#"
                                                title="{{ __('Settings') }}">{{ __('Settings') }}</a></li>
                                        <li class="breadcrumb-item active" aria-current="page">{{ $pageTitle }}</li>
                                    </ol>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="settings-page-layout-wrap position-relative">
                        <div class="row">
                            @include('admin.setting.sidebar')
                            <div class="col-md-12 col-lg-8 col-xxl-9">
                                <div class="account-settings-rightside bg-off-white theme-border radius-4 p-25">
                                    <div class="currency-settings-page-area">
                                        <div class="account-settings-content-box">
                                            <div class="account-settings-title border-bottom mb-20 pb-20">
                                                <div class="row align-items-center">
                                                    <div class="col-md-6">
                                                        <h4>{{ $pageTitle }}</h4>
                                                    </div>
                                                    <div class="col-md-6">
                                                        <div class="property-details-right text-end">
                                                            <button type="button" class="theme-btn" data-bs-toggle="modal"
                                                                data-bs-target="#addCurrencyModal"
                                                                title="{{ __('Add Currency') }}">
                                                                {{ __('Add Currency') }}
                                                            </button>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="currency-list-table-area">
                                                <div class="bg-off-white theme-border radius-4 p-25">
                                                    <table id="datatableCurrencySettings" class="table bg-off-white theme-border p-20 dt-responsive">
                                                        <thead>
                                                            <tr>
                                                                <th>{{ __('Currency Code') }}</th>
                                                                <th>{{ __('Symbol') }}</th>
                                                                <th>{{ __('Currency Placement') }}</th>
                                                                <th>{{ __('Action') }}</th>
                                                            </tr>
                                                        </thead>
                                                        <tbody>
                                                            @foreach ($currencies as $currency)
                                                                <tr>
                                                                    <td>{{ $currency->currency_code }}
                                                                        {{ $currency->current_currency == ACTIVE ? '(Current Currency)' : '' }}
                                                                    </td>
                                                                    <td>{{ $currency->symbol }}</td>
                                                                    <td>{{ ucwords($currency->currency_placement) }}</td>
                                                                    <td>
                                                                        <div class="tbl-action-btns d-inline-flex">
                                                                            <a class="p-1 tbl-action-btn edit"
                                                                                data-item="{{ $currency }}"
                                                                                data-updateurl="{{ route('admin.setting.currency.update', $currency->id) }}"
                                                                                data-bs-toggle="modal"
                                                                                data-bs-target="#editCurrencyModal"
                                                                                title="{{ __('Edit') }}"><span
                                                                                    class="iconify"
                                                                                    data-icon="clarity:note-edit-solid"></span>
                                                                            </a>
                                                                            <button class="p-1 tbl-action-btn deleteItem"
                                                                                data-formid="delete_row_form_{{ $currency->id }}">
                                                                                <span class="iconify"
                                                                                    data-icon="ep:delete-filled"></span>
                                                                            </button>
                                                                            <form
                                                                                action="{{ route('admin.setting.currency.destroy', [$currency->id]) }}"
                                                                                method="post"
                                                                                id="delete_row_form_{{ $currency->id }}">
                                                                                {{ method_field('DELETE') }}
                                                                                <input type="hidden" name="_token"
                                                                                    value="{{ csrf_token() }}">
                                                                            </form>
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
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade" id="addCurrencyModal" tabindex="-1" aria-labelledby="addCurrencyModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title" id="addCurrencyModalLabel">{{ __('Add Currency') }}</h4>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"><span
                            class="iconify" data-icon="akar-icons:cross"></span>
                    </button>
                </div>
                <form action="{{ route('admin.setting.currency.store') }}" method="POST" enctype="multipart/form-data"
                      class="ajax" data-handler="getShowMessage">
                    @csrf
                    <div class="modal-body">
                        <div class="modal-inner-form-box">
                            <div class="row">
                                <div class="col-md-12 mb-25">
                                    <label
                                        class="label-text-title color-heading font-medium mb-2">{{ __('Currency ISO Code') }}</label>
                                    <input type="text" name="currency_code" class="form-control"
                                        placeholder="{{ __('Currency ISO Code') }}">
                                </div>
                                <div class="col-md-12 mb-25">
                                    <label
                                        class="label-text-title color-heading font-medium mb-2">{{ __('Symbol') }}</label>
                                    <input type="text" name="symbol" class="form-control"
                                        placeholder="{{ __('Symbol') }}">
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-12 mb-25">
                                    <label
                                        class="label-text-title color-heading font-medium mb-2">{{ __('Currency Placement') }}</label>
                                    <select name="currency_placement" class="form-select flex-shrink-0">
                                        <option value="before">{{ __('Before') }}</option>
                                        <option value="after">{{ __('After') }}</option>
                                    </select>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group custom-checkbox" title="{{ __('Make Current Currency') }}">
                                        <input type="checkbox" value="1" id="makeCurrentCurrency" name="current_currency">
                                        <label class="fw-normal"
                                            for="makeCurrentCurrency">{{ __('Make Current Currency') }}</label>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="modal-footer justify-content-start">
                        <button type="button" class="theme-btn-back me-3" data-bs-dismiss="modal"
                            title="{{ __('Back') }}">{{ __('Back') }}</button>
                        <button type="submit" class="theme-btn me-3"
                            title="{{ __('Save') }}">{{ __('Save') }}</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    <div class="modal fade edit_modal" id="editCurrencyModal" tabindex="-1" aria-labelledby="editCurrencyModalLabel"
        aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title" id="editCurrencyModalLabel">{{ __('Edit Currency') }}</h4>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"><span
                            class="iconify" data-icon="akar-icons:cross"></span>
                    </button>
                </div>
                <form action="" id="updateEditModal" method="POST" enctype="multipart/form-data"
                      class="ajax" data-handler="getShowMessage">
                    @csrf
                    {{ method_field('PUT') }}
                    <div class="modal-body">
                        <div class="modal-inner-form-box">
                            <div class="row">
                                <div class="col-md-12 mb-25">
                                    <label
                                        class="label-text-title color-heading font-medium mb-2">{{ __('Currency ISO Code') }}</label>
                                    <input type="text" name="currency_code" class="form-control"
                                        placeholder="{{ __('Currency ISO Code') }}">
                                </div>
                                <div class="col-md-12 mb-25">
                                    <label
                                        class="label-text-title color-heading font-medium mb-2">{{ __('Symbol') }}</label>
                                    <input type="text" name="symbol" class="form-control"
                                        placeholder="{{ __('Symbol') }}">
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-12 mb-25">
                                    <label
                                        class="label-text-title color-heading font-medium mb-2">{{ __('Currency Placement') }}</label>
                                    <select name="currency_placement" class="form-select flex-shrink-0">
                                        <option value="before">{{ __('Before') }}</option>
                                        <option value="after">{{ __('After') }}</option>
                                    </select>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group custom-checkbox" title="{{ __('Make Current Currency') }}">
                                        <input type="checkbox" value="1" id="updateMakeCurrentCurrency" name="current_currency">
                                        <label class="fw-normal"
                                            for="updateMakeCurrentCurrency">{{ __('Make Current Currency') }}</label>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer justify-content-start">
                        <button type="button" class="theme-btn-back me-3" data-bs-dismiss="modal"
                            title="{{ __('Back') }}">{{ __('Back') }}</button>
                        <button type="submit" class="theme-btn me-3"
                            title="{{ __('Save') }}">{{ __('Save') }}</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection

@push('style')
    @include('common.layouts.datatable-style')
@endpush

@push('script')
    @include('common.layouts.datatable-script')
    <script src="{{ asset('/') }}assets/js/pages/currency-datatables.init.js"></script>
    <script src="{{ asset('assets/js/custom/currency.js') }}"></script>
@endpush
