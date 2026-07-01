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
                                        <li class="breadcrumb-item"><a href="{{ route('tenant.dashboard') }}"
                                                title="{{ __('Dashboard') }}">{{ __('Dashboard') }}</a></li>
                                        <li class="breadcrumb-item active" aria-current="page">{{ $pageTitle }}</li>
                                    </ol>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="tenants-top-bar">
                            <div class="property-search-inner-bg bg-off-white theme-border radius-4 p-25 pb-0 mb-25">
                                <div class="row">
                                    <div class="col-xl-12 col-xxl-6 tenants-top-bar-left">
                                        <div class="row">
                                            <div class="col-md-6 col-lg-6 col-xl-4 col-xxl-4 mb-25">
                                                <select class="form-select flex-shrink-0 property_id" id="search_property">
                                                    <option value="" selected>{{ __('Select Property') }}</option>
                                                    @foreach ($properties as $property)
                                                        <option value="{{ $property->id }}">{{ $property->name }}</option>
                                                    @endforeach
                                                </select>
                                            </div>
                                            <div class="col-md-6 col-lg-6 col-xl-4 col-xxl-4 mb-25">
                                                <select class="form-select flex-shrink-0 unit_id">
                                                    <option selected>--{{ __('Select Unit') }}--</option>
                                                </select>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="documents-page-area">
                            <div class="all-document-wrap bg-off-white theme-border radius-4 p-25 pb-0">
                                <div class="tenants-top-bar border-bottom pb-25 mb-25">
                                    <div class="row align-items-center">
                                        <div class="col-xl-12 col-xxl-6 tenants-top-bar-left">
                                            <h4>{{ __('All Document') }}</h4>
                                        </div>
                                    </div>
                                </div>
                                <div class="documents-page-document-wrap">
                                    <div class="row">
                                        <div class="information-table-area">
                                            <table id="allDataTableDoc" class="table responsive theme-border p-20">
                                                <thead>
                                                    <tr>
                                                        <th>{{ __('SL') }}</th>
                                                        <th>{{ __('Document Type') }}</th>
                                                        <th data-priority="1">{{ __('Tenant Name') }}</th>
                                                        <th>{{ __('Tenant Name') }}</th>
                                                        <th>{{ __('Property') }}</th>
                                                        <th>{{ __('Unit') }}</th>
                                                        <th>{{ __('Front Side') }}</th>
                                                        <th>{{ __('Back Side') }}</th>
                                                        <th>{{ __('Status') }}</th>
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
                </div>
            </div>
        </div>
    </div>

    {{-- Modal  --}}
    <div class="modal fade" id="rejectModal" tabindex="-1" aria-labelledby="editFilesModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title" id="editFilesModalLabel">{{ __('Reject Reason') }}</h4>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close">
                        <span class="iconify" data-icon="akar-icons:cross"></span>
                    </button>
                </div>
                <form class="ajax" action="{{ route('owner.documents.reject.reason.store') }}" method="POST"
                    enctype="multipart/form-data" data-handler="getShowMessage">
                    <input type="hidden" name="id" class="id">
                    <div class="modal-body">
                        <div class="modal-inner-form-box">
                            <div class="row">
                                <div class="col-12 mb-25">
                                    <label
                                        class="label-text-title color-heading font-medium mb-2">{{ __('Config Name') }}</label>
                                    <input type="text" class="form-control kyc_config_name" disabled>
                                </div>

                                <div class="col-12 mb-25">
                                    <label
                                        class="label-text-title color-heading font-medium mb-2">{{ __('Front Side') }}</label>
                                    <div class="information-details-img radius-4 mb-25">
                                        <img src="" alt="" class="fit-image radius-4 front-img">
                                    </div>
                                </div>
                                <div class="col-12 mb-25 pb-3 d-none isBoth">
                                    <label
                                        class="label-text-title color-heading font-medium mb-2">{{ __('Back Side') }}</label>
                                    <div class="information-details-img radius-4 mb-25">
                                        <img src="" alt="" class="fit-image radius-4 back-img">
                                    </div>
                                </div>
                                <div class="view-information-page-box mb-25">
                                    <label class="label-text-title color-heading font-medium mb-2">{{ __('Tenant Name') }}
                                        :</label>
                                    <span class="tenant_name"></span>
                                </div>
                                <div class="view-information-page-box mb-25">
                                    <label
                                        class="label-text-title color-heading font-medium mb-2">{{ __('Property Name') }}
                                        :</label>
                                    <span class="property_name"></span>
                                </div>
                                <div class="view-information-page-box mb-25">
                                    <label class="label-text-title color-heading font-medium mb-2">{{ __('Unit Name') }}
                                        :</label>
                                    <span class="unit_name"></span>
                                </div>
                                <div class="col-12 mb-25">
                                    <label
                                        class="label-text-title color-heading font-medium mb-2">{{ __('Reason') }}</label>
                                    <textarea name="reason" class="form-control reason" placeholder="{{ __('Reason') }}"></textarea>
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

    <div class="modal fade" id="viewModal" tabindex="-1" aria-labelledby="viewModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title" id="viewModalLabel">{{ __('Details') }}</h4>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"><span
                            class="iconify" data-icon="akar-icons:cross"></span></button>
                </div>
                <div class="modal-body">
                    <div class="view-information-page-modal-content">
                        <div class="view-information-page-box mb-25">
                            <label class="label-text-title color-heading font-medium mb-2">{{ __('Front Side') }}</label>
                            <div class="information-details-img radius-4 mb-25">
                                <img src="" alt="" class="fit-image radius-4 front-img">
                            </div>
                        </div>
                        <div class="view-information-page-box mb-25 d-none isBoth">
                            <label class="label-text-title color-heading font-medium mb-2">{{ __('Back Side') }}</label>
                            <div class="information-details-img radius-4 mb-25">
                                <img src="" alt="" class="fit-image radius-4 back-img">
                            </div>
                        </div>
                        <div class="view-information-page-box mb-25">
                            <label class="label-text-title color-heading font-medium mb-2">{{ __('Config Name') }}
                                :</label>
                            <span class="config_name"></span>
                        </div>
                        <div class="view-information-page-box mb-25">
                            <label class="label-text-title color-heading font-medium mb-2">{{ __('Tenant Name') }}
                                :</label>
                            <span class="tenant_name"></span>
                        </div>
                        <div class="view-information-page-box mb-25">
                            <label class="label-text-title color-heading font-medium mb-2">{{ __('Property Name') }}
                                :</label>
                            <span class="property_name"></span>
                        </div>
                        <div class="view-information-page-box mb-25">
                            <label class="label-text-title color-heading font-medium mb-2">{{ __('Unit Name') }}
                                :</label>
                            <span class="unit_name"></span>
                        </div>
                        <div class="view-information-page-box d-none reasonDiv">
                            <label class="label-text-title color-heading font-medium mb-2">{{ __('Reject Reason') }}
                                :</label>
                            <span class="reason"></span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="main-content">
        <input type="hidden" id="route" value="{{ route('owner.documents.index') }}?property_id=0&unit_id=0">
        <input type="hidden" id="getInfoRoute" value="{{ route('owner.documents.get.info') }}">
        <input type="hidden" id="getPropertyUnitsRoute" value="{{ route('owner.property.getPropertyUnits') }}">
    </div>
@endsection
@push('style')
    @include('common.layouts.datatable-style')
@endpush
@push('script')
    @include('common.layouts.datatable-script')
    <script src="{{ asset('assets/js/custom/documents.js') }}"></script>
@endpush
