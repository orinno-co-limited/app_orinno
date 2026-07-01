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
                    <div class="row">
                        <div class="property-top-search-bar">
                            <div class="row align-items-center">

                                <div class="col-md-12">
                                    <div class="property-top-search-bar-right text-end">
                                        <button type="button" class="theme-btn mb-25 addExpenses"
                                            title="{{ __('Add New Expenses') }}">{{ __('Add New Expenses') }}
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="billing-center-area bg-off-white theme-border radius-4 p-25">
                            <table id="expensesDatatable" class="table responsive theme-border p-20 ">
                                <thead>
                                    <tr>
                                        <th>{{ __('Name') }}</th>
                                        <th>{{ __('Property') }}</th>
                                        <th>{{ __('Expenses Type') }}</th>
                                        <th>{{ __('Responsibility') }}</th>
                                        <th>{{ __('Amount') }}</th>
                                        <th>{{ __('Action') }}</th>
                                    </tr>
                                </thead>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Add Expenses Modal Start -->
    <div class="modal fade" id="addExpensesModal" tabindex="-1" aria-labelledby="addExpensesModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title" id="addExpensesModalLabel"><span
                            class="modalTitle">{{ __('Add Expenses') }}</span></h4>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"><span
                            class="iconify" data-icon="akar-icons:cross"></span></button>
                </div>
                <form class="ajax" action="{{ route('owner.expense.store') }}" method="post"
                    enctype="multipart/form-data" data-handler="getShowMessage">
                    <div class="modal-body">
                        <div class="modal-inner-form-box border-bottom mb-25">
                            <div class="row">
                                <div class="col-md-12 mb-25">
                                    <label
                                        class="label-text-title color-heading font-medium mb-2">{{ __('Name') }}</label>
                                    <input type="text" name="name" class="form-control name"
                                        placeholder="{{ __('Name') }}">
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-6 mb-25">
                                    <label
                                        class="label-text-title color-heading font-medium mb-2">{{ __('Property') }}</label>
                                    <select class="form-select flex-shrink-0 property_id" name="property_id">
                                        <option value="">--{{ __('Select Option') }}--</option>
                                        @foreach ($properties as $property)
                                            <option value="{{ $property->id }}"
                                                data-units="{{ $property->propertyUnits }}">{{ $property->name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="col-md-6 mb-25">
                                    <label
                                        class="label-text-title color-heading font-medium mb-2">{{ __('Unit') }}</label>
                                    <select class="form-select flex-shrink-0 property_unit_id unitOption"
                                        name="property_unit_id">
                                        <option value="">--{{ __('Select Option') }}--</option>
                                    </select>
                                </div>
                            </div>
                        </div>
                        <div class="modal-inner-form-box">
                            <div class="row">
                                <div class="col-md-12 mb-25">
                                    <div class="row justify-content-between expence-type-add-new-box">
                                        <div class="col">
                                            <label
                                                class="label-text-title color-heading font-medium mb-2">{{ __('Expense Type') }}</label>
                                        </div>
                                        <div class="col text-end">
                                            <button type="button" class="expence-type-add-new-box-btn theme-secondary-link"
                                                data-bs-toggle="modal" data-bs-target="#addTypeModal">
                                                + {{ __('Add New Types') }}
                                            </button>
                                        </div>
                                    </div>
                                    <select class="form-select flex-shrink-0 expense_type_id" id="typesOption"
                                        name="expense_type_id">
                                        <option value="">--{{ __('Select Option') }}--</option>
                                        @foreach ($expenseTypes as $expenseType)
                                            <option value="{{ $expenseType->id }}">{{ $expenseType->name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="col-md-12 mb-25">
                                    <label
                                        class="label-text-title color-heading font-medium mb-2">{{ __('Description') }}</label>
                                    <textarea class="form-control description" name="description" placeholder="{{ __('Description') }}"></textarea>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-6 mb-25">
                                    <label
                                        class="label-text-title color-heading font-medium mb-2">{{ __('Total Amount') }}</label>
                                    <input type="number" step="any" name="total_amount"
                                        class="form-control total_amount" placeholder="{{ __('Total Amount') }}">
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-auto mb-25">
                                    <label
                                        class="label-text-title color-heading font-medium mb-2">{{ __('Responsibilities') }}</label>
                                    <div>
                                        <div class="form-group custom-checkbox d-inline me-3">
                                            <input type="checkbox" value="1" name="responsibilities[0]"
                                                id="responseTenant">
                                            <label class="fw-normal" for="responseTenant">{{ __('Tenant') }}</label>
                                        </div>
                                        <div class="form-group custom-checkbox d-inline me-3">
                                            <input type="checkbox" value="2" name="responsibilities[1]"
                                                id="responseOwner">
                                            <label class="fw-normal"
                                                for="responseOwner">{{ __('Property Owner') }}</label>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-12">
                                    <label
                                        class="label-text-title color-heading font-medium mb-2">{{ __('Upload Documents') }}</label>
                                    <input type="file" name="file" class="form-control">
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer justify-content-start">
                        <a href="javascript:void(0)" class="theme-btn-back me-3" data-bs-dismiss="modal"
                            title="{{ __('Back') }}">{{ __('Back') }}</a>
                        <button type="submit" class="theme-btn me-3"
                            title="{{ __('Save Expenses') }}">{{ __('Save Expenses') }}</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <div class="modal fade" id="editExpensesModal" tabindex="-1" aria-labelledby="editExpensesModalLabel"
        aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title" id="editExpensesModalLabel"><span
                            class="modalTitle">{{ __('Edit Expenses') }}</span></h4>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"><span
                            class="iconify" data-icon="akar-icons:cross"></span></button>
                </div>
                <form class="ajax" action="{{ route('owner.expense.store') }}" method="post"
                    enctype="multipart/form-data" data-handler="getShowMessage">
                    <input type="hidden" class="id" name="id">
                    <div class="modal-body">
                        <div class="modal-inner-form-box border-bottom mb-25">
                            <div class="row">
                                <div class="col-md-12 mb-25">
                                    <label
                                        class="label-text-title color-heading font-medium mb-2">{{ __('Name') }}</label>
                                    <input type="text" name="name" class="form-control name"
                                        placeholder="{{ __('Name') }}">
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-6 mb-25">
                                    <label
                                        class="label-text-title color-heading font-medium mb-2">{{ __('Property') }}</label>
                                    <select class="form-select flex-shrink-0 property_id" name="property_id">
                                        <option value="">--{{ __('Select Option') }}--</option>
                                        @foreach ($properties as $property)
                                            <option value="{{ $property->id }}"
                                                data-units="{{ $property->propertyUnits }}">{{ $property->name }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="col-md-6 mb-25">
                                    <label
                                        class="label-text-title color-heading font-medium mb-2">{{ __('Unit') }}</label>
                                    <select class="form-select flex-shrink-0 property_unit_id unitOption"
                                        name="property_unit_id">
                                        <option value="">--{{ __('Select Option') }}--</option>
                                    </select>
                                </div>
                            </div>
                        </div>
                        <div class="modal-inner-form-box">
                            <div class="row">
                                <div class="col-md-12 mb-25">
                                    <div class="row justify-content-between expence-type-add-new-box">
                                        <div class="col">
                                            <label
                                                class="label-text-title color-heading font-medium mb-2">{{ __('Expense Type') }}</label>
                                        </div>
                                    </div>
                                    <select class="form-select flex-shrink-0 expense_type_id" id="typesOption"
                                        name="expense_type_id">
                                        <option value="">--{{ __('Select Option') }}--</option>
                                        @foreach ($expenseTypes as $expenseType)
                                            <option value="{{ $expenseType->id }}">{{ $expenseType->name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="col-md-12 mb-25">
                                    <label
                                        class="label-text-title color-heading font-medium mb-2">{{ __('Description') }}</label>
                                    <textarea class="form-control description" name="description" placeholder="{{ __('Description') }}"></textarea>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-6 mb-25">
                                    <label
                                        class="label-text-title color-heading font-medium mb-2">{{ __('Total Amount') }}</label>
                                    <input type="number" step="any" name="total_amount"
                                        class="form-control total_amount" placeholder="{{ __('Total Amount') }}">
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-auto mb-25">
                                    <label
                                        class="label-text-title color-heading font-medium mb-2">{{ __('Responsibilities') }}</label>
                                    <div>
                                        <div class="form-group custom-checkbox d-inline me-3">
                                            <input type="checkbox" value="1" name="responsibilities[0]"
                                                id="responseTenantEdit">
                                            <label class="fw-normal" for="responseTenantEdit">{{ __('Tenant') }}</label>
                                        </div>
                                        <div class="form-group custom-checkbox d-inline me-3">
                                            <input type="checkbox" value="2" name="responsibilities[1]"
                                                id="responseOwnerEdit">
                                            <label class="fw-normal"
                                                for="responseOwnerEdit">{{ __('Property Owner') }}</label>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-12">
                                    <label
                                        class="label-text-title color-heading font-medium mb-2">{{ __('Upload Documents') }}</label>
                                    <input type="file" name="file" class="form-control">
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer justify-content-start">
                        <a href="javascript:void(0)" class="theme-btn-back me-3" data-bs-dismiss="modal"
                            title="{{ __('Back') }}">{{ __('Back') }}</a>
                        <button type="submit" class="theme-btn me-3"
                            title="{{ __('Save Expenses') }}">{{ __('Save Expenses') }}</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <div class="modal fade" id="addTypeModal" tabindex="-1" aria-labelledby="addTypeModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title" id="addTypeModalLabel">{{ __('Add New Expense') }}</h4>
                    <a type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"><span
                            class="iconify" data-icon="akar-icons:cross"></span></a>
                </div>
                <form class="ajax" action="{{ route('owner.expense.expenseType.store') }}" method="POST"
                    data-handler="typeStoreDataRes">
                    @csrf
                    <div class="modal-body">
                        <div class="modal-inner-form-box">
                            <div class="row">
                                <div class="col-md-12 mb-25">
                                    <label
                                        class="label-text-title color-heading font-medium mb-2">{{ __('Expense Type Name') }}</label>
                                    <input type="text" name="type_name" class="form-control"
                                        placeholder="{{ __('Name') }}">
                                </div>
                            </div>
                        </div>
                    </div>
                    <input type="hidden" class="modalType" value="created">
                    <div class="modal-footer justify-content-start">
                        <a href="javascript:void(0)" class="theme-btn-back me-3" data-bs-dismiss="modal"
                            title="{{ __('Back') }}">{{ __('Back') }}</a>
                        <button type="submit" class="theme-btn me-3"
                            title="{{ __('Save') }}">{{ __('Save') }}</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <input type="hidden" id="expenseIndexRoute" value="{{ route('owner.expense.index') }}">
@endsection

@push('style')
    @include('common.layouts.datatable-style')
@endpush

@push('script')
    @include('common.layouts.datatable-script')

    <script src="{{ asset('assets/js/custom/expense.js') }}"></script>
@endpush
