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

                    <div class="row">
                        <div class="property-top-search-bar">
                            <div class="row align-items-center">
                                <div class="col-md-12">
                                    <div class="property-top-search-bar-right text-end">
                                        <button type="button" class="theme-btn mb-25" id="add"
                                                title="{{ __('Add Owner') }}">{{ __('Add Owner') }}
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="billing-center-area bg-off-white theme-border radius-4 p-25">
                            <table id="allOwnerDataTable" class="table responsive theme-border p-20 ">
                                <thead>
                                    <th>{{ __('SL') }}</th>
                                    <th data-priority="1">{{ __('Name') }}</th>
                                    <th>{{ __('Email') }}</th>
                                    <th>{{ __('Contact Number') }}</th>
                                    <th>{{ __('Status') }}</th>
                                    <th>{{ __('Action') }}</th>
                                </thead>
                            </table>
                        </div>
                    </div>

                </div>
            </div>
        </div>
    </div>
    <!-- Add Owner Modal Start -->
    <div class="modal fade" id="addModal" tabindex="-1" aria-labelledby="addModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title" id="addModalLabel"><span class="modalTitle">{{ __('Add Owner') }}</span></h4>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"><span
                            class="iconify" data-icon="akar-icons:cross"></span></button>
                </div>
                <form class="ajax" action="{{ route('admin.owner.store') }}" method="post"
                      enctype="multipart/form-data" data-handler="getShowMessage">
                    <div class="modal-body">
                        <div class="">
                            <div class="row">
                                <div class="col-md-6 mb-25">
                                    <label class="label-text-title color-heading font-medium mb-2">{{ __('First Name') }}
                                        <span class="text-danger">*</span></label>
                                    <input type="text" name="first_name" class="form-control"
                                           placeholder="{{ __('First Name') }}">
                                </div>
                                <div class="col-md-6 mb-25">
                                    <label class="label-text-title color-heading font-medium mb-2">{{ __('Last Name') }}
                                        <span class="text-danger">*</span></label>
                                    <input type="text" name="last_name" class="form-control"
                                           placeholder="{{ __('Last Name') }}">
                                </div>
                                <div class="col-md-6 mb-25">
                                    <label
                                        class="label-text-title color-heading font-medium mb-2">{{ __('Contact Number') }}
                                        <span class="text-danger">*</span></label>
                                    <input type="text" name="contact_number" class="form-control"
                                           placeholder="{{ __('Contact Number') }}">
                                </div>
                                <div class="col-md-6 mb-25">
                                    <label class="label-text-title color-heading font-medium mb-2">{{ __('Email') }}
                                        <span class="text-danger">*</span></label>
                                    <input type="text" name="email" class="form-control"
                                           placeholder="{{ __('Email') }}">
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-6 mb-25">
                                    <label
                                        class="label-text-title color-heading font-medium mb-2">{{ __('Password') }}</label>
                                    <input type="password" name="password" class="form-control"
                                           placeholder="{{ __('Password') }}">
                                </div>
                                <div class="col-md-6 mb-25">
                                    <label
                                        class="label-text-title color-heading font-medium mb-2">{{ __('Confirm Password') }}</label>
                                    <input type="password" name="password_confirmation" class="form-control"
                                           placeholder="{{ __('Confirm Password') }}">
                                </div>
                            </div>
                        </div>
                        <div class="modal-inner-form-box">
                            <div class="row">
                                <div class="col-md-12">
                                    <label
                                        class="label-text-title color-heading font-medium mb-2">{{ __('Status') }}</label>
                                    <select name="status" id="status" class="form-control">
                                        <option value="{{USER_STATUS_ACTIVE}}">{{ __('Active') }}</option>
                                        <option value="{{USER_STATUS_INACTIVE}}">{{ __('Inactive') }}</option>
                                        <option value="{{USER_STATUS_DELETED}}">{{ __('Deleted') }}</option>
                                        <option value="{{USER_STATUS_UNVERIFIED}}">{{ __('Unverified') }}</option>
                                    </select>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer justify-content-start">
                        <a href="javascript:void(0)" class="theme-btn-back me-3" data-bs-dismiss="modal"
                           title="{{ __('Back') }}">{{ __('Back') }}</a>
                        <button type="submit" class="theme-btn me-3"
                                title="{{ __('Add Owner') }}">{{ __('Add Owner') }}</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <div class="modal fade" id="editModal" tabindex="-1" aria-labelledby="editModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title" id="editModalLabel"><span class="modalTitle">{{ __('Edit Owner Status') }}</span>
                    </h4>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"><span
                            class="iconify" data-icon="akar-icons:cross"></span></button>
                </div>
                <form class="ajax" action="{{ route('admin.owner.update') }}" method="post"
                    enctype="multipart/form-data" data-handler="getShowMessage">
                    <input type="hidden" name="id">
                    <div class="modal-body">
                        <div class="">
                            <div class="row">
                                <div class="col-md-6 mb-25">
                                    <label class="label-text-title color-heading font-medium mb-2">{{ __('First Name') }}
                                        <span class="text-danger">*</span></label>
                                    <input type="text" name="first_name" class="form-control"
                                        placeholder="{{ __('First Name') }}">
                                </div>
                                <div class="col-md-6 mb-25">
                                    <label class="label-text-title color-heading font-medium mb-2">{{ __('Last Name') }}
                                        <span class="text-danger">*</span></label>
                                    <input type="text" name="last_name" class="form-control"
                                        placeholder="{{ __('Last Name') }}">
                                </div>
                                <div class="col-md-6 mb-25">
                                    <label
                                        class="label-text-title color-heading font-medium mb-2">{{ __('Contact Number') }}
                                        <span class="text-danger">*</span></label>
                                    <input type="text" name="contact_number" class="form-control"
                                        placeholder="{{ __('Contact Number') }}">
                                </div>
                                <div class="col-md-6 mb-25">
                                    <label class="label-text-title color-heading font-medium mb-2">{{ __('Email') }}
                                        <span class="text-danger">*</span></label>
                                    <input type="text" name="email" class="form-control"
                                        placeholder="{{ __('Email') }}">
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-6 mb-25">
                                    <label
                                        class="label-text-title color-heading font-medium mb-2">{{ __('Password') }}</label>
                                    <input type="password" name="password" class="form-control"
                                        placeholder="{{ __('Password') }}">
                                </div>
                                <div class="col-md-6 mb-25">
                                    <label
                                        class="label-text-title color-heading font-medium mb-2">{{ __('Confirm Password') }}</label>
                                    <input type="password" name="password_confirmation" class="form-control"
                                        placeholder="{{ __('Confirm Password') }}">
                                </div>
                            </div>
                        </div>
                        <div class="modal-inner-form-box">
                            <div class="row">
                                <div class="col-md-12">
                                    <label
                                        class="label-text-title color-heading font-medium mb-2">{{ __('Status') }}</label>
                                    <select name="status" id="status" class="form-control">
                                        <option value="{{USER_STATUS_ACTIVE}}">{{ __('Active') }}</option>
                                        <option value="{{USER_STATUS_INACTIVE}}">{{ __('Inactive') }}</option>
                                        <option value="{{USER_STATUS_DELETED}}">{{ __('Deleted') }}</option>
                                        <option value="{{USER_STATUS_UNVERIFIED}}">{{ __('Unverified') }}</option>
                                    </select>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer justify-content-start">
                        <a href="" class="theme-btn-back me-3" data-bs-dismiss="modal"
                            title="{{ __('Back') }}">{{ __('Back') }}</a>
                        <button type="submit" class="theme-btn me-3"
                            title="{{ __('Update') }}">{{ __('Update') }}</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    <input type="hidden" id="ownerGetInfoRoute" value="{{ route('admin.owner.get.info') }}">
    <input type="hidden" id="adminOwnerRoute" value="{{ route('admin.owner.index') }}">
@endsection

@push('style')
    @include('common.layouts.datatable-style')
@endpush

@push('script')
    @include('common.layouts.datatable-script')
    <script src="{{ asset('assets/js/custom/owner.js') }}"></script>
@endpush
