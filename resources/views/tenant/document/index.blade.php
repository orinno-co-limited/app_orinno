@extends('tenant.layouts.app')

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
                        <div class="col-12">
                            <div class="page-title-box text-end flex-sm-row align-items-sm-center justify-content-between g-20">
                                <div class="page-title-right">
                                    <button type="button" class="theme-btn" title="{{ __('Upload files') }}"
                                            id="addFiles">{{ __('Upload files') }}</button>
                                </div>
                            </div>
                        </div>
                        <!-- Documents Area Start -->
                        <div class="documents-page-area">
                            @foreach ($kycConfigs->where('tenant_id', '!=', null) as $kycConfig)
                                <div class="upload-nid-wrap radius-4 p-20 mb-25 alert alert-dismissible fade show"
                                    role="alert">
                                    <div class="d-flex align-items-center me-3">
                                        <div class="flex-shrink-0">
                                            <div
                                                class="tenant-nid-icon text-white rounded-circle d-inline-flex align-items-center justify-content-center font-20">
                                                <span class="iconify" data-icon="clarity:notification-line"></span>
                                            </div>
                                        </div>
                                        <div class="flex-grow-1 ms-3">
                                            <h5>{{ $kycConfig->name }}</h5>
                                            <p class="font-13 mt-2">{{ $kycConfig->details }}
                                                <button type="button" data-id="{{ $kycConfig->id }}"
                                                    class="font-bold specialUploadFilesAdd"
                                                    title="{{ __('Upload Now') }}">{{ __('Upload Now') }}</button>
                                            </p>
                                        </div>
                                    </div>
                                    <button type="button" class="btn-close" data-bs-dismiss="alert"
                                        aria-label="Close"></button>
                                </div>
                            @endforeach

                            <div class="all-document-wrap bg-off-white theme-border radius-4 p-25 pb-0">
                                <div class="documents-page-document-wrap">
                                    <div class="row">
                                        <div class="information-table-area">
                                            <div class="pb-25">
                                                <table id="allDataTable"
                                                    class="table bg-off-white theme-border p-20 dt-responsive">
                                                    <thead>
                                                        <tr>
                                                            <th>{{ __('SL') }}</th>
                                                            <th class="all">{{ __('Kyc Config Name') }}</th>
                                                            <th class="desktop">{{ __('Front Side') }}</th>
                                                            <th class="desktop">{{ __('Back Side') }}</th>
                                                            <th class="desktop">{{ __('Status') }}</th>
                                                            <th class="desktop">{{ __('Action') }}</th>
                                                        </tr>
                                                    </thead>
                                                    <tbody>
                                                        @foreach ($kycVerifications as $kycVerification)
                                                            <tr class="kycVerificationRow">
                                                                <td>{{ $loop->iteration }}</td>
                                                                <td>{{ $kycVerification->config?->name }}</td>
                                                                <td>
                                                                    <div class="tenants-tbl-info-object d-flex align-items-center"
                                                                        title="Download">
                                                                        <div class="flex-shrink-0">
                                                                            <a href="{{ $kycVerification->front }}"
                                                                                download="">
                                                                                @if (pathinfo($kycVerification->front, PATHINFO_EXTENSION) == 'pdf')
                                                                                    <img
                                                                                        src="{{ asset('assets/images/pdf-file-img.png') }}">
                                                                                @else
                                                                                    <img class="rounded avatar-md tbl-user-image"
                                                                                        src="{{ $kycVerification->front }}">
                                                                                @endif
                                                                            </a>
                                                                        </div>
                                                                    </div>
                                                                </td>
                                                                <td>
                                                                    <div class="tenants-tbl-info-object d-flex align-items-center"
                                                                        title="Download">
                                                                        <div class="flex-shrink-0">
                                                                            <a href="{{ $kycVerification->back }}"
                                                                                download="">
                                                                                @if (pathinfo($kycVerification->back, PATHINFO_EXTENSION) == 'pdf')
                                                                                    <img
                                                                                        src="{{ asset('assets/images/pdf-file-img.png') }}">
                                                                                @else
                                                                                    <img class="rounded avatar-md tbl-user-image"
                                                                                        src="{{ $kycVerification->back }}">
                                                                                @endif
                                                                            </a>
                                                                        </div>
                                                                    </div>
                                                                </td>
                                                                <td>
                                                                    @if ($kycVerification->status == KYC_STATUS_ACCEPTED)
                                                                        <div
                                                                            class="status-btn status-btn-green font-13 radius-4">
                                                                            {{ __('Accepted') }}</div>
                                                                    @elseif ($kycVerification->status == KYC_STATUS_PENDING)
                                                                        <div
                                                                            class="status-btn status-btn-orange font-13 radius-4">
                                                                            {{ __('Pending') }}</div>
                                                                    @elseif ($kycVerification->status == KYC_STATUS_REJECTED)
                                                                        <div
                                                                            class="status-btn status-btn-red font-13 radius-4">
                                                                            {{ __('Rejected') }}
                                                                        </div>
                                                                        <button type="button" class="read-more-btn">
                                                                            <span class="iconify"
                                                                                data-icon="material-symbols:auto-read-pause-outline"></span>
                                                                        </button>
                                                                        <div class="reason-text mt-1 d-none-content">
                                                                            {{ $kycVerification->reason }}
                                                                        </div>
                                                                    @endif
                                                                </td>
                                                                <td>
                                                                    <div class="tbl-action-btns d-inline-flex">
                                                                        @if ($kycVerification->status != KYC_STATUS_ACCEPTED)
                                                                            <button type="button"
                                                                                class="p-1 tbl-action-btn edit"
                                                                                data-id="{{ $kycVerification->id }}"
                                                                                title="Edit"><span class="iconify"
                                                                                    data-icon="clarity:note-edit-solid"></span></button>
                                                                            <button type="button"
                                                                                class="p-1 tbl-action-btn deleteItem"
                                                                                data-formid="delete_row_form_{{ $kycVerification->id }}"
                                                                                title="{{ __('Delete') }}">
                                                                                <span class="iconify"
                                                                                    data-icon="ep:delete-filled"></span></button>
                                                                            <form
                                                                                action="{{ route('tenant.document.delete', [$kycVerification->id]) }}"
                                                                                method="post"
                                                                                id="delete_row_form_{{ $kycVerification->id }}">
                                                                                {{ method_field('DELETE') }}
                                                                                <input type="hidden" name="_token"
                                                                                    value="{{ csrf_token() }}">
                                                                            </form>
                                                                        @endif
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
                                <!-- Documents Page Document Wrap End -->
                            </div>
                        </div>
                        <!-- Documents Area End -->
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Tenant Documents Upload Files Modal Start -->
    <div class="modal fade" id="addFilesModal" tabindex="-1" aria-labelledby="addFilesModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title" id="addFilesModalLabel">{{ __('Upload Files') }}</h4>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close">
                        <span class="iconify" data-icon="akar-icons:cross"></span>
                    </button>
                </div>
                <form class="ajax" action="{{ route('tenant.document.store') }}" method="POST"
                    enctype="multipart/form-data" data-handler="getShowMessage">
                    <div class="modal-body">
                        <div class="modal-inner-form-box">
                            <div class="row">

                                <div class="col-12 mb-25">
                                    <div class="d-flex align-items-center">
                                        <select name="kyc_config_id" class="form-control kyc_config_id">
                                            <option value="">--{{ __('Select Config') }}--</option>
                                            @foreach ($kycConfigs as $kycConfig)
                                                <option value="{{ $kycConfig->id }}"
                                                    data-is_both="{{ $kycConfig->is_both }}">{{ $kycConfig->name }}</option>
                                            @endforeach
                                        </select>
                                        <div  class="d-none demo-file d-flex align-items-center ms-1 border form-control rounded-sm w-25">
                                            <svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" aria-hidden="true" role="img" class="iconify iconify--material-symbols" width="1em" height="1em" preserveAspectRatio="xMidYMid meet" viewBox="0 0 24 24" data-icon="material-symbols:download"><path fill="currentColor" d="m12 16l-5-5l1.4-1.45l2.6 2.6V4h2v8.15l2.6-2.6L17 11zm-6 4q-.825 0-1.412-.587T4 18v-3h2v3h12v-3h2v3q0 .825-.587 1.413T18 20z"></path></svg>
                                            <span class="ms-1"><a download>{{ __('Demo File') }}</a></span>
                                        </div>
                                    </div>
                                </div>

                                <div class="col-12 mb-25 pb-5">
                                    <label
                                        class="label-text-title color-heading font-medium mb-2">{{ __('Front Side') }}</label>
                                    <input type="file" name="front" class="dropify"
                                        data-allowed-file-extensions="jpeg jpg png pdf" data-max-file-size-preview="3M" />
                                </div>
                                <div class="col-12 mb-25 pb-3 d-none isBoth">
                                    <label
                                        class="label-text-title color-heading font-medium mb-2">{{ __('Back Side') }}</label>
                                    <input type="file" name="back" class="dropify"
                                        data-allowed-file-extensions="jpeg jpg png pdf" data-max-file-size-preview="3M"
                                        multiple />
                                    <input type="hidden" name="type_back" class="type_back">
                                </div>

                            </div>
                        </div>
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
    <div class="modal fade" id="editFilesModal" tabindex="-1" aria-labelledby="editFilesModalLabel"
        aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title" id="editFilesModalLabel">{{ __('Edit Files') }}</h4>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close">
                        <span class="iconify" data-icon="akar-icons:cross"></span>
                    </button>
                </div>
                <form class="ajax" action="{{ route('tenant.document.store') }}" method="POST"
                    enctype="multipart/form-data" data-handler="getShowMessage">
                    <input type="hidden" name="id" class="id">
                    <div class="modal-body">
                        <div class="modal-inner-form-box">
                            <div class="row">

                                <div class="col-12 mb-25">
                                    <input type="hidden" name="kyc_config_id" class="kyc_config_id">
                                    <input type="text" class="form-control kyc_config_name" disabled>
                                </div>

                                <div class="col-12 mb-25 pb-5">
                                    <label
                                        class="label-text-title color-heading font-medium mb-2">{{ __('Front Side') }}</label>
                                    <input type="file" name="front" class="front"
                                        data-allowed-file-extensions="jpeg jpg png pdf" data-max-file-size-preview="3M" />
                                </div>
                                <div class="col-12 mb-25 pb-3 d-none isBoth">
                                    <label
                                        class="label-text-title color-heading font-medium mb-2">{{ __('Back Side') }}</label>
                                    <input type="file" name="back" class="back"
                                        data-allowed-file-extensions="jpeg jpg png pdf" data-max-file-size-preview="3M"
                                        multiple />
                                    <input type="hidden" name="type_back" class="type_back">
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
    <input type="hidden" id="getInfoRoute" value="{{ route('tenant.document.get.info') }}">
    <input type="hidden" id="getConfigInfoRoute" value="{{ route('tenant.document.get.config.info') }}">
@endsection
@push('style')
    @include('common.layouts.datatable-style')
@endpush

@push('script')
    @include('common.layouts.datatable-script')
    <script src="{{ asset('/') }}assets/js/pages/alldatatables.init.js"></script>
    <script src="{{ asset('assets/js/custom/tenant-document.js') }}"></script>
@endpush
