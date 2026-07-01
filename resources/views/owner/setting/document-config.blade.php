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
                                    <h3 class="mb-sm-0">{{ __('Settings') }}</h3>
                                </div>
                                <div class="page-title-right">
                                    <ol class="breadcrumb mb-0">
                                        <li class="breadcrumb-item"><a href="{{ route('owner.dashboard') }}"
                                                title="{{ __('Dashboard') }}">{{ __('Dashboard') }}</a></li>
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
                            @include('owner.setting.sidebar')
                            <div class="col-md-12 col-lg-12 col-xl-8 col-xxl-9">
                                <div class="account-settings-rightside bg-off-white theme-border radius-4 p-25">
                                    <div class="invoice-type-settings-page-area">
                                        <div class="account-settings-content-box">
                                            <div class="account-settings-title border-bottom mb-20 pb-20">
                                                <div class="row align-items-center">
                                                    <div class="col-md-6">
                                                        <h4>{{ $pageTitle }}</h4>
                                                    </div>
                                                    <div class="col-md-6">
                                                        <div class="property-details-right text-end">
                                                            <button type="button" class="theme-btn" id="add"
                                                                title="{{ __('Add Document Config') }}">
                                                                {{ __('Add Document Config') }}
                                                            </button>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="invoice-type-table-area">
                                                <div class="bg-white theme-border radius-4 p-25">
                                                    <table id="allDataTable"
                                                        class="table bg-white theme-border p-20 dt-responsive">
                                                        <thead>
                                                            <tr>
                                                                <th>{{ __('SL') }}</th>
                                                                <th>{{ __('Demo') }}</th>
                                                                <th data-priority="1">{{ __('Doc Name') }}</th>
                                                                <th>{{ __('Tenant') }}</th>
                                                                <th>{{ __('Details') }}</th>
                                                                <th>{{ __('Both Side') }}</th>
                                                                <th>{{ __('Status') }}</th>
                                                                <th>{{ __('Action') }}</th>
                                                            </tr>
                                                        </thead>
                                                        <tbody>
                                                            @foreach ($kycConfigs as $kycConfig)
                                                                <tr>
                                                                    <td>{{ $loop->iteration }}</td>
                                                                    <td>
                                                                        <a href="{{ $kycConfig->image }}" download>
                                                                            <img class="img-thumbnail"
                                                                                src="{{ $kycConfig->image }}">
                                                                        </a>
                                                                    </td>
                                                                    <td>{{ $kycConfig->name }}</td>
                                                                    <td>
                                                                        @if ($kycConfig->first_name)
                                                                            {{ $kycConfig->first_name }}
                                                                            {{ $kycConfig->last_name }}
                                                                        @else
                                                                            {{ __('All') }}
                                                                        @endif

                                                                    </td>
                                                                    <td>{{ Str::limit($kycConfig->details, 25, '...') }}
                                                                    </td>
                                                                    <td>
                                                                        @if ($kycConfig->is_both == ACTIVE)
                                                                            <div
                                                                                class="status-btn status-btn-green font-13 radius-4">
                                                                                {{ __('Yes') }}</div>
                                                                        @else
                                                                            <div
                                                                                class="status-btn status-btn-red font-13 radius-4">
                                                                                {{ __('No') }}</div>
                                                                        @endif
                                                                    </td>
                                                                    <td>
                                                                        @if ($kycConfig->status == ACTIVE)
                                                                            <div
                                                                                class="status-btn status-btn-green font-13 radius-4">
                                                                                {{ __('Active') }}</div>
                                                                        @else
                                                                            <div
                                                                                class="status-btn status-btn-red font-13 radius-4">
                                                                                {{ __('Deactivate') }}</div>
                                                                        @endif
                                                                    </td>

                                                                    <td>
                                                                        <div class="tbl-action-btns d-inline-flex">
                                                                            <a class="p-1 tbl-action-btn edit"
                                                                                data-id="{{ $kycConfig->id }}"
                                                                                title="{{ __('Edit') }}">
                                                                                <span class="iconify"
                                                                                    data-icon="clarity:note-edit-solid"></span>
                                                                            </a>
                                                                            <a href="#"
                                                                                class="p-1 tbl-action-btn deleteItem"
                                                                                data-formid="delete_row_form_{{ $kycConfig->id }}"
                                                                                title="{{ __('Delete') }}"><span
                                                                                    class="iconify"
                                                                                    data-icon="ep:delete-filled"></span></a>
                                                                            <form
                                                                                action="{{ route('owner.setting.document-config.delete', [$kycConfig->id]) }}"
                                                                                method="post"
                                                                                id="delete_row_form_{{ $kycConfig->id }}">
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

    <div class="modal fade" id="addModal" tabindex="-1" aria-labelledby="addModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title" id="addModalLabel">{{ __('Add Document Config') }}</h4>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close">
                        <span class="iconify" data-icon="akar-icons:cross"></span>
                    </button>
                </div>
                <form class="ajax" action="{{ route('owner.setting.document-config.store') }}" method="POST"
                    data-handler="getShowMessage">
                    @csrf
                    <div class="modal-body">
                        <div class="modal-inner-form-box">
                            <div class="row">
                                <div class="col-md-12 mb-3">
                                    <label
                                        class="label-text-title color-heading font-medium mb-2">{{ __('Tenant') }}</label>
                                    <select name="tenant_id" id="" class="form-control">
                                        <option value="">{{ __('All') }}</option>
                                        @foreach ($tenants as $tenant)
                                            <option value="{{ $tenant->id }}">{{ $tenant->first_name }}
                                                {{ $tenant->last_name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="col-md-12 mb-3">
                                    <label
                                        class="label-text-title color-heading font-medium mb-2">{{ __('Name') }}</label>
                                    <input type="text" name="name" class="form-control"
                                        placeholder="{{ __('Name') }}">
                                </div>
                                <div class="col-md-12 mb-3">
                                    <label
                                        class="label-text-title color-heading font-medium mb-2">{{ __('Details') }}</label>
                                    <textarea name="details" id="details" class="form-control" placeholder="{{ __('Details') }}"></textarea>
                                </div>
                                <div class="col-md-12 mb-3">
                                    <label class="label-text-title color-heading font-medium mb-2">{{ __('Demo File') }}
                                        ({{ __('Optional') }})</label>
                                    <input type="file" name="demo" class="form-control">
                                </div>
                                <div class="col-md-12 mb-3">
                                    <label
                                        class="label-text-title color-heading font-medium mb-2">{{ __('Status') }}</label>
                                    <select name="status" class="form-control">
                                        <option value="1">{{ __('Active') }}</option>
                                        <option value="0">{{ __('Deactive') }}</option>
                                    </select>
                                </div>
                                <div class="col-md-12 mb-3">
                                    <div class="form-group custom-checkbox" title="{{ __('Both Side') }}">
                                        <input type="checkbox" id="isBoth" name="is_both">
                                        <label class="fw-normal" for="isBoth">{{ __('Both Side') }}</label>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <!-- Modal Inner Form Box End -->
                    </div>

                    <div class="modal-footer justify-content-start">
                        <button type="button" class="theme-btn-back me-3" data-bs-dismiss="modal"
                            title="{{ __('Back') }}">{{ __('Back') }}</button>
                        <button type="submit" class="theme-btn me-3"
                            title="{{ __('Submit') }}">{{ __('Submit') }}</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    <div class="modal fade" id="editModal" tabindex="-1" aria-labelledby="editModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title" id="editModalLabel">{{ __('Edit Document Config') }}</h4>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close">
                        <span class="iconify" data-icon="akar-icons:cross"></span>
                    </button>
                </div>
                <form class="ajax" action="{{ route('owner.setting.document-config.store') }}" method="POST"
                    data-handler="getShowMessage">
                    @csrf
                    <input type="hidden" name="id" id="id">
                    <div class="modal-body">
                        <div class="modal-inner-form-box">
                            <div class="row">
                                <div class="col-md-12 mb-3">
                                    <label
                                        class="label-text-title color-heading font-medium mb-2">{{ __('Tenant') }}</label>
                                    <select name="tenant_id" id="" class="form-control tenant_id">
                                        <option value="">{{ __('All') }}</option>
                                        @foreach ($tenants as $tenant)
                                            <option value="{{ $tenant->id }}">{{ $tenant->first_name }}
                                                {{ $tenant->last_name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="col-md-12 mb-3">
                                    <label
                                        class="label-text-title color-heading font-medium mb-2">{{ __('Name') }}</label>
                                    <input type="text" name="name" class="form-control name"
                                        placeholder="{{ __('Name') }}">
                                </div>
                                <div class="col-md-12 mb-3">
                                    <label
                                        class="label-text-title color-heading font-medium mb-2">{{ __('Details') }}</label>
                                    <textarea name="details" id="details" class="form-control details" placeholder="{{ __('Details') }}"></textarea>
                                </div>
                                <div class="col-md-12 mb-3">
                                    <label class="label-text-title color-heading font-medium mb-2">{{ __('Demo File') }}
                                        ({{ __('Optional') }})</label>
                                    <input type="file" name="demo" class="form-control">
                                </div>
                                <div class="col-md-12 mb-3">
                                    <label
                                        class="label-text-title color-heading font-medium mb-2">{{ __('Status') }}</label>
                                    <select name="status" class="form-control status">
                                        <option value="1">{{ __('Active') }}</option>
                                        <option value="0">{{ __('Deactive') }}</option>
                                    </select>
                                </div>
                                <div class="col-md-12 mb-3">
                                    <div class="form-group custom-checkbox" title="{{ __('Both Side') }}">
                                        <input type="checkbox" id="isBothEdit" name="is_both" class="is_both">
                                        <label class="fw-normal" for="isBothEdit">{{ __('Both Side') }}</label>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer justify-content-start">
                        <button type="button" class="theme-btn-back me-3" data-bs-dismiss="modal"
                            title="{{ __('Back') }}">{{ __('Back') }}</button>
                        <button type="submit" class="theme-btn me-3"
                            title="{{ __('Update') }}">{{ __('Update') }}</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    <input type="hidden" id="getInfoRoute" value="{{ route('owner.setting.document-config.get.info') }}">
@endsection

@push('style')
    @include('common.layouts.datatable-style')
@endpush

@push('script')
    @include('common.layouts.datatable-script')

    <script src="{{ asset('/') }}assets/js/pages/alldatatables.init.js"></script>
    <script src="{{ asset('assets/js/custom/document-config.js') }}"></script>
@endpush
