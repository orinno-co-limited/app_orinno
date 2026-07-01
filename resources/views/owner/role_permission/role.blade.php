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
                                        <button type="button" class="theme-btn mb-25" id="add"
                                                title="{{ __('Add Role') }}">{{ __('Add Role') }}
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="billing-center-area bg-off-white theme-border radius-4 p-25">
                            <table id="roleListDataTable" class="table responsive theme-border p-20 ">
                                <thead>
                                <th>{{ __('Id') }}</th>
                                <th>{{ __('Name') }}</th>
                                <th class="text-end">{{ __('Status') }}</th>
                                <th class="text-end">{{ __('Action') }}</th>
                                </thead>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Add Role Modal Start -->
    <div class="modal fade" id="addModal" tabindex="-1" aria-labelledby="addModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title" id="addModalLabel"><span class="modalTitle">{{ __('Add Role') }}</span></h4>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"><span
                            class="iconify" data-icon="akar-icons:cross"></span></button>
                </div>
                <form class="ajax" action="{{ route('owner.role-permission.store') }}" method="post"
                      enctype="multipart/form-data" data-handler="getShowMessage">
                    <div class="modal-body">
                        <div class="modal-inner-form-box">
                            <div class="row">
                                <div class="col-md-12 mb-25">
                                    <label class="label-text-title color-heading font-medium mb-2">{{ __('Name') }}
                                        <span class="text-danger">*</span></label>
                                    <input type="text" name="name" class="form-control"
                                           placeholder="{{ __('Name') }}">
                                </div>
                            </div>
                            <div class="col-md-12 mb-25">
                                <label
                                    class="label-text-title color-heading font-medium mb-2">{{ __('Status') }} <span class="text-danger">*</span></label>
                                <select name="status" id="status" class="form-control">
                                    <option value="1">{{ __('Active') }}</option>
                                    <option value="0">{{ __('Deactivate') }}</option>
                                </select>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer justify-content-start">
                        <a href="javascript:void(0)" class="theme-btn-back me-3" data-bs-dismiss="modal"
                           title="{{ __('Back') }}">{{ __('Back') }}</a>
                        <button type="submit" class="theme-btn me-3"
                                title="{{ __('Add Role') }}">{{ __('Add Role') }}</button>
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
                <form class="ajax" action="{{ route('owner.role-permission.store') }}" method="post"
                      enctype="multipart/form-data" data-handler="getShowMessage">
                    <input type="hidden" name="id">
                    <div class="modal-body">
                        <div class="modal-inner-form-box">
                            <div class="row">
                                <div class="col-md-12 mb-25">
                                    <label class="label-text-title color-heading font-medium mb-2">{{ __('Name') }}
                                        <span class="text-danger">*</span></label>
                                    <input type="text" name="name" class="form-control"
                                           placeholder="{{ __('Name') }}">
                                </div>
                            </div>
                            <div class="col-md-12 mb-25">
                                <label
                                    class="label-text-title color-heading font-medium mb-2">{{ __('Status') }} <span class="text-danger">*</span></label>
                                <select name="status" id="status" class="form-control">
                                    <option value="1">{{ __('Active') }}</option>
                                    <option value="0">{{ __('Deactivate') }}</option>
                                </select>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer justify-content-start">
                        <a href="javascript:void(0)" class="theme-btn-back me-3" data-bs-dismiss="modal"
                           title="{{ __('Back') }}">{{ __('Back') }}</a>
                        <button type="submit" class="theme-btn me-3"
                                title="{{ __('Add Role') }}">{{ __('Update Role') }}</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <div class="modal fade" id="permissionModal" tabindex="-1" aria-labelledby="editModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">

            </div>
        </div>
    </div>

    <input type="hidden" id="roleIndexRoute" value="{{ route('owner.role-permission.role-list') }}">
    <input type="hidden" id="roleInfoRoute" value="{{ route('owner.role-permission.get-info') }}">

@endsection

@push('style')
    @include('common.layouts.datatable-style')
@endpush

@push('script')
    @include('common.layouts.datatable-script')
    <script src="{{ asset('assets/js/custom/role-permission.js') }}"></script>
@endpush
