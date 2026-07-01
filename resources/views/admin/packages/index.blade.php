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
                                        <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}"
                                                title="{{ __('Dashboard') }}">{{ __('Dashboard') }}</a></li>
                                        <li class="breadcrumb-item active" aria-current="page">{{ $pageTitle }}</li>
                                    </ol>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="alert alert-warning" role="alert">
                        {{__('You can create a package, but make sure the Stripe/PayPal credentials are correct. Otherwise, keep the package status deactivated; otherwise, you won’t be able to create the package.')}}
                    </div>
                    <div class="row">
                        <div class="property-top-search-bar">
                            <div class="row align-items-center">
                                <div class="col-md-12">
                                    <div class="property-top-search-bar-right text-end">
                                        <button type="button" class="theme-btn mb-25" id="add"
                                            title="{{ __('Add Package') }}">{{ __('Add Package') }}
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="billing-center-area bg-off-white theme-border radius-4 p-25">
                            <table id="allDataTable" class="table responsive theme-border p-20 ">
                                <thead>
                                    <th>{{ __('Name') }}</th>
                                    <th>{{ __('Monthly Price') }}</th>
                                    <th>{{ __('Yearly Price') }}</th>
                                    <th>{{ __('Max Property') }}</th>
                                    <th>{{ __('Max Unit') }}</th>
                                    <th>{{ __('Status') }}</th>
                                    <th>{{ __('Is Trial') }}</th>
                                    <th>{{ __('Action') }}</th>
                                </thead>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Add Package Modal Start -->
    <div class="modal fade" id="addModal" tabindex="-1" aria-labelledby="addModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title" id="addModalLabel"><span class="modalTitle">{{ __('Add Package') }}</span></h4>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"><span
                            class="iconify" data-icon="akar-icons:cross"></span></button>
                </div>
                <form class="ajax" action="{{ route('admin.packages.store') }}" method="post"
                    enctype="multipart/form-data" data-handler="getShowMessage">
                    <div class="modal-body">
                        <div class="modal-inner-form-box border-bottom mb-25">
                            <div class="row">
                                <div class="col-md-12 mb-25">
                                    <label class="label-text-title color-heading font-medium mb-2">{{ __('Name') }}
                                        <span class="text-danger">*</span></label>
                                    <input type="text" name="name" class="form-control"
                                        placeholder="{{ __('Name') }}">
                                </div>
                                <div class="col-md-12 mb-25">
                                    <label class="label-text-title color-heading font-medium mb-2">{{ __('Description') }}
                                        <span class="text-danger">*</span></label>
                                    <input type="text" name="description" class="form-control"
                                        placeholder="{{ __('Description') }}">
                                </div>
                                {{--                                <div class="col-md-6 mb-25"> --}}
                                {{--                                    <label --}}
                                {{--                                        class="label-text-title color-heading font-medium mb-2">{{ __('Pricing Type') }}<span --}}
                                {{--                                            class="text-danger">*</span></label> --}}
                                {{--                                    <select name="pricing_type" class="form-control pricing_type"> --}}
                                {{--                                        <option value="1">{{ __('Properties') }}</option> --}}
                                {{--                                        <option value="2">{{ __('Units') }}</option> --}}
                                {{--                                        <option value="3">{{ __('Tenants') }}</option> --}}
                                {{--                                    </select> --}}
                                {{--                                </div> --}}
                                <div class="col-md-6 mb-25">
                                    <label class="label-text-title color-heading font-medium mb-2">
                                        {{--                                        <span --}}
                                        {{--                                            class="property_type_price d-none">{{ __('Per Property Monthly Price') }}</span> --}}
                                        {{--                                        <span class="unit_type_price d-none">{{ __('Per Unit Monthly Price') }}</span> --}}
                                        {{--                                        <span class="tenant_type_price d-none">{{ __('Per Tenant Monthly Price') }}</span> --}}
                                        <span class="">{{ __('Max Property') }}</span>
                                        <span class="text-danger">*</span>
                                    </label>
                                    <input type="number" min="0" step="any" name="max_property"
                                        class="form-control" placeholder="10">
                                </div>
                                <div class="col-md-6 mb-25">
                                    <label class="label-text-title color-heading font-medium mb-2">
                                        <span class="">{{ __('Max Unit') }}</span>
                                        {{--                                        <span --}}
                                        {{--                                            class="property_type_price d-none">{{ __('Per Property Yearly Price') }}</span> --}}
                                        {{--                                        <span class="unit_type_price d-none">{{ __('Per Unit Yearly Price') }}</span> --}}
                                        {{--                                        <span class="tenant_type_price d-none">{{ __('Per Tenant Yearly Price') }}</span> --}}
                                        <span class="text-danger">*</span>
                                    </label>
                                    <input type="number" min="0" step="any" name="max_unit" class="form-control"
                                        placeholder="10">
                                </div>
                            </div>
                        </div>
                        <div class="modal-inner-form-box border-bottom mb-25">
                            <div class="row">
                                <div class="col-md-6 mb-25">
                                    <label
                                        class="label-text-title color-heading font-medium mb-2">{{ __('Max Maintainer') }}</label>
                                    <div class="input-group">
                                        <input type="number" min="0" step="any" name="max_maintainer"
                                            class="form-control" placeholder="5">
                                        <select name="maintainer_limit_type" class="form-control" fdprocessedid="bgzg6e">
                                            <option value="1">{{ __('Limited') }}</option>
                                            <option value="2">{{ __('Unlimited') }}</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="col-md-6 mb-25">
                                    <label
                                        class="label-text-title color-heading font-medium mb-2">{{ __('Max Invoice') }}</label>
                                    <div class="input-group">
                                        <input type="number" min="0" step="any" name="max_invoice"
                                            class="form-control" placeholder="5000">
                                        <select name="invoice_limit_type" class="form-control" fdprocessedid="bgzg6e">
                                            <option value="1">{{ __('Limited') }}</option>
                                            <option value="2">{{ __('Unlimited') }}</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="col-md-6 mb-25">
                                    <label
                                        class="label-text-title color-heading font-medium mb-2">{{ __('Max Auto Invoice') }}</label>
                                    <div class="input-group">
                                        <input type="number" min="0" step="any" name="max_auto_invoice"
                                            class="form-control" placeholder="5000">
                                        <select name="auto_invoice_limit_type" class="form-control"
                                            fdprocessedid="bgzg6e">
                                            <option value="1">{{ __('Limited') }}</option>
                                            <option value="2">{{ __('Unlimited') }}</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="col-md-6 mb-25">
                                    <label
                                        class="label-text-title color-heading font-medium mb-2">{{ __('Ticket Support') }}</label>
                                    <select name="ticket_support" id="ticket_support" class="form-control">
                                        <option value="0">{{ __('No') }}</option>
                                        <option value="1">{{ __('Yes') }}</option>
                                    </select>
                                </div>
                                <div class="col-md-6 mb-25">
                                    <label
                                        class="label-text-title color-heading font-medium mb-2">{{ __('Notice Support') }}</label>
                                    <select name="notice_support" id="notice_support" class="form-control">
                                        <option value="0">{{ __('No') }}</option>
                                        <option value="1">{{ __('Yes') }}</option>
                                    </select>
                                </div>
                                <div class="col-md-6 mb-25">
                                    <label
                                        class="label-text-title color-heading font-medium mb-2">{{ __('Is Default') }}</label>
                                    <select name="is_default" id="is_default" class="form-control">
                                        <option value="0">{{ __('No') }}</option>
                                        <option value="1">{{ __('Yes') }}</option>
                                    </select>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-12 mb-25">
                                    <label
                                        class="label-text-title color-heading font-medium mb-2">{{ __('Other Fields') }}</label>
                                    <button type="button" class="btn btn-info px-1 addOtherField"><i
                                            class="fa fa-plus"></i></button>
                                    <hr class="my-2">
                                    <div class="otherFields">
                                        <div class="input-group">
                                            <input type="text" name="others[]" class="form-control">
                                            <button type="button"
                                                class="bg-danger input-group-text text-white removeOtherField">Remove</button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="modal-inner-form-box">
                            <div class="row">
                                <div class="col-md-6 mb-25">
                                    <label
                                        class="label-text-title color-heading font-medium mb-2">{{ __('Status') }}</label>
                                    <select name="status" id="status" class="form-control">
                                        <option value="1">{{ __('Active') }}</option>
                                        <option value="0">{{ __('Deactivate') }}</option>
                                    </select>
                                </div>
                                <div class="col-md-6 mb-25">
                                    <label
                                        class="label-text-title color-heading font-medium mb-2">{{ __('Is Trial') }}</label>
                                    <select name="is_trail" id="is_trail" class="form-control">
                                        <option value="0">{{ __('No') }}</option>
                                        <option value="1">{{ __('Yes') }}</option>
                                    </select>
                                </div>
                                <div class="col-md-6 mb-25">
                                    <label
                                        class="label-text-title color-heading font-medium mb-2">{{ __('Monthly Price') }}
                                        <span class="text-danger">*</span></label>
                                    <input type="number" min="0" step="any" name="monthly_price"
                                        class="form-control" placeholder="10">
                                </div>
                                <div class="col-md-6 mb-25">
                                    <label
                                        class="label-text-title color-heading font-medium mb-2">{{ __('Yearly Price') }}
                                        <span class="text-danger">*</span></label>
                                    <input type="number" min="0" step="any" name="yearly_price"
                                        class="form-control" placeholder="10">
                                </div>
                                <div class="col-md-6 mb-25">
                                    <label
                                        class="label-text-title color-heading font-medium mb-2">{{ __('Stripe Subscription') }}</label>
                                    <select name="stripe_subscription" id="stripe_subscription" class="form-control">
                                        <option value="0">{{ __('No') }}</option>
                                        <option value="1">{{ __('Yes') }}</option>
                                    </select>
                                </div>
                                <div class="col-md-6 mb-25">
                                    <label
                                        class="label-text-title color-heading font-medium mb-2">{{ __('Paypal Subscription') }}</label>
                                    <select name="paypal_subscription" id="paypal_subscription" class="form-control">
                                        <option value="0">{{ __('No') }}</option>
                                        <option value="1">{{ __('Yes') }}</option>
                                    </select>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer justify-content-start">
                        <a href="javascript:void(0)" class="theme-btn-back me-3" data-bs-dismiss="modal"
                            title="{{ __('Back') }}">{{ __('Back') }}</a>
                        <button type="submit" class="theme-btn me-3"
                            title="{{ __('Add Package') }}">{{ __('Add Package') }}</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <div class="modal fade" id="editModal" tabindex="-1" aria-labelledby="editModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title" id="editModalLabel"><span class="modalTitle">{{ __('Edit Package') }}</span>
                    </h4>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"><span
                            class="iconify" data-icon="akar-icons:cross"></span></button>
                </div>
                <form class="ajax" action="{{ route('admin.packages.store') }}" method="post"
                    enctype="multipart/form-data" data-handler="getShowMessage">
                    <input type="hidden" name="id">
                    <div class="modal-body">
                        <div class="modal-inner-form-box border-bottom mb-25">
                            <div class="row">
                                <div class="col-md-12 mb-25">
                                    <label class="label-text-title color-heading font-medium mb-2">{{ __('Name') }}
                                        <span class="text-danger">*</span></label>
                                    <input type="text" name="name" class="form-control"
                                        placeholder="{{ __('Name') }}">
                                </div>
                                <div class="col-md-12 mb-25">
                                    <label class="label-text-title color-heading font-medium mb-2">{{ __('Description') }}
                                        <span class="text-danger">*</span></label>
                                    <input type="text" name="description" class="form-control"
                                        placeholder="{{ __('Description') }}">
                                </div>
                                {{--                                <div class="col-md-6 mb-25"> --}}
                                {{--                                    <label --}}
                                {{--                                        class="label-text-title color-heading font-medium mb-2">{{ __('Pricing Type') }}<span --}}
                                {{--                                            class="text-danger">*</span></label> --}}
                                {{--                                    <select name="pricing_type" class="form-control pricing_type"> --}}
                                {{--                                        <option value="1">{{ __('Properties') }}</option> --}}
                                {{--                                        <option value="2">{{ __('Units') }}</option> --}}
                                {{--                                        <option value="3">{{ __('Tenants') }}</option> --}}
                                {{--                                    </select> --}}
                                {{--                                </div> --}}
                                <div class="col-md-6 mb-25">
                                    <label class="label-text-title color-heading font-medium mb-2">
                                        {{--                                        <span --}}
                                        {{--                                            class="property_type_price d-none">{{ __('Per Property Monthly Price') }}</span> --}}
                                        {{--                                        <span class="unit_type_price d-none">{{ __('Per Unit Monthly Price') }}</span> --}}
                                        {{--                                        <span class="tenant_type_price d-none">{{ __('Per Tenant Monthly Price') }}</span> --}}
                                        <span class="">{{ __('Max Property') }}</span>
                                        <span class="text-danger">*</span>
                                    </label>
                                    <input type="number" min="0" step="any" name="max_property"
                                        class="form-control" placeholder="10">
                                </div>
                                <div class="col-md-6 mb-25">
                                    <label class="label-text-title color-heading font-medium mb-2">
                                        <span class="">{{ __('Max Unit') }}</span>
                                        {{--                                        <span --}}
                                        {{--                                            class="property_type_price d-none">{{ __('Per Property Yearly Price') }}</span> --}}
                                        {{--                                        <span class="unit_type_price d-none">{{ __('Per Unit Yearly Price') }}</span> --}}
                                        {{--                                        <span class="tenant_type_price d-none">{{ __('Per Tenant Yearly Price') }}</span> --}}
                                        <span class="text-danger">*</span>
                                    </label>
                                    <input type="number" min="0" step="any" name="max_unit"
                                        class="form-control" placeholder="10">
                                </div>
                            </div>
                        </div>
                        <div class="modal-inner-form-box border-bottom mb-25">
                            <div class="row">
                                <div class="col-md-6 mb-25">
                                    <label
                                        class="label-text-title color-heading font-medium mb-2">{{ __('Max Maintainer') }}</label>
                                    <div class="input-group">
                                        <input type="number" min="0" step="any" name="max_maintainer"
                                            class="form-control" placeholder="5">
                                        <select name="maintainer_limit_type" class="form-control" fdprocessedid="bgzg6e">
                                            <option value="1">{{ __('Limited') }}</option>
                                            <option value="2">{{ __('Unlimited') }}</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="col-md-6 mb-25">
                                    <label
                                        class="label-text-title color-heading font-medium mb-2">{{ __('Max Invoice') }}</label>
                                    <div class="input-group">
                                        <input type="number" min="0" step="any" name="max_invoice"
                                            class="form-control" placeholder="5000">
                                        <select name="invoice_limit_type" class="form-control" fdprocessedid="bgzg6e">
                                            <option value="1">{{ __('Limited') }}</option>
                                            <option value="2">{{ __('Unlimited') }}</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="col-md-6 mb-25">
                                    <label
                                        class="label-text-title color-heading font-medium mb-2">{{ __('Max Auto Invoice') }}</label>
                                    <div class="input-group">
                                        <input type="number" min="0" step="any" name="max_auto_invoice"
                                            class="form-control" placeholder="5000">
                                        <select name="auto_invoice_limit_type" class="form-control"
                                            fdprocessedid="bgzg6e">
                                            <option value="1">{{ __('Limited') }}</option>
                                            <option value="2">{{ __('Unlimited') }}</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="col-md-6 mb-25">
                                    <label
                                        class="label-text-title color-heading font-medium mb-2">{{ __('Ticket Support') }}</label>
                                    <select name="ticket_support" id="ticket_support" class="form-control">
                                        <option value="0">{{ __('No') }}</option>
                                        <option value="1">{{ __('Yes') }}</option>
                                    </select>
                                </div>
                                <div class="col-md-6 mb-25">
                                    <label
                                        class="label-text-title color-heading font-medium mb-2">{{ __('Notice Support') }}</label>
                                    <select name="notice_support" id="notice_support" class="form-control">
                                        <option value="0">{{ __('No') }}</option>
                                        <option value="1">{{ __('Yes') }}</option>
                                    </select>
                                </div>
                                <div class="col-md-6 mb-25">
                                    <label
                                        class="label-text-title color-heading font-medium mb-2">{{ __('Is Default') }}</label>
                                    <select name="is_default" id="is_default" class="form-control">
                                        <option value="0">{{ __('No') }}</option>
                                        <option value="1">{{ __('Yes') }}</option>
                                    </select>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-12 mb-25">
                                    <label
                                        class="label-text-title color-heading font-medium mb-2">{{ __('Other Fields') }}</label>
                                    <button type="button" class="btn btn-info px-1 addOtherField"><i
                                            class="fa fa-plus"></i></button>
                                    <hr class="my-2">
                                    <div class="otherFields">
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="modal-inner-form-box">
                            <div class="row">
                                <div class="col-md-6 mb-25">
                                    <label
                                        class="label-text-title color-heading font-medium mb-2">{{ __('Status') }}</label>
                                    <select name="status" id="status" class="form-control">
                                        <option value="1">{{ __('Active') }}</option>
                                        <option value="0">{{ __('Deactivate') }}</option>
                                    </select>
                                </div>
                                <div class="col-md-6 mb-25">
                                    <label
                                        class="label-text-title color-heading font-medium mb-2">{{ __('Is Trial') }}</label>
                                    <select name="is_trail" id="is_trail" class="form-control">
                                        <option value="0">{{ __('No') }}</option>
                                        <option value="1">{{ __('Yes') }}</option>
                                    </select>
                                </div>
                                <div class="col-md-6 mb-25">
                                    <label
                                        class="label-text-title color-heading font-medium mb-2">{{ __('Monthly Price') }}
                                        <span class="text-danger">*</span></label>
                                    <input type="number" min="0" step="any" name="monthly_price"
                                        class="form-control" placeholder="10">
                                </div>
                                <div class="col-md-6 mb-25">
                                    <label
                                        class="label-text-title color-heading font-medium mb-2">{{ __('Yearly Price') }}
                                        <span class="text-danger">*</span></label>
                                    <input type="number" min="0" step="any" name="yearly_price"
                                        class="form-control" placeholder="10">
                                </div>
                                <div class="col-md-6 mb-25">
                                    <label
                                        class="label-text-title color-heading font-medium mb-2">{{ __('Stripe Subscription') }}</label>
                                    <select name="stripe_subscription" id="stripe_subscription" class="form-control">
                                        <option value="0">{{ __('No') }}</option>
                                        <option value="1">{{ __('Yes') }}</option>
                                    </select>
                                </div>
                                <div class="col-md-6 mb-25">
                                    <label
                                        class="label-text-title color-heading font-medium mb-2">{{ __('Paypal Subscription') }}</label>
                                    <select name="paypal_subscription" id="paypal_subscription" class="form-control">
                                        <option value="0">{{ __('No') }}</option>
                                        <option value="1">{{ __('Yes') }}</option>
                                    </select>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer justify-content-start">
                        <a href="javascript:void(0)" class="theme-btn-back me-3" data-bs-dismiss="modal"
                            title="{{ __('Back') }}">{{ __('Back') }}</a>
                        <button type="submit" class="theme-btn me-3"
                            title="{{ __('Update') }}">{{ __('Update') }}</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <input type="hidden" id="packageIndexRoute" value="{{ route('admin.packages.index') }}">
    <input type="hidden" id="packageInfoRoute" value="{{ route('admin.packages.get.info') }}">
@endsection

@push('style')
    @include('common.layouts.datatable-style')
@endpush

@push('script')
    @include('common.layouts.datatable-script')
    <script src="{{ asset('assets/js/custom/package.js') }}"></script>
@endpush
