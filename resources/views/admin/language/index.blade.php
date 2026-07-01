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
                                        <li class="breadcrumb-item active" aria-current="page">{{ __(@$pageTitle) }}</li>
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
                                    <div class="language-settings-page-area">
                                        <div class="account-settings-content-box">
                                            <div class="account-settings-title border-bottom mb-20 pb-20">
                                                <div class="row align-items-center">
                                                    <div class="col-md-6">
                                                        <h4>{{ __(@$pageTitle) }}</h4>
                                                    </div>
                                                    <div class="col-md-6">
                                                        <div class="property-details-right text-end">
                                                            <button type="button" class="theme-btn" id="add"
                                                                title="{{ __('Add Language') }}">
                                                                {{ __('Add Language') }}
                                                            </button>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="language-list-table-area">
                                                <div class="bg-off-white theme-border radius-4 p-25">
                                                    <table id="allDataTable"
                                                        class="table bg-off-white theme-border p-20 dt-responsive">
                                                        <thead>
                                                            <tr>
                                                                <th>{{ __('SL') }}</th>
                                                                <th>{{ __('Flag') }}</th>
                                                                <th data-priority="1">{{ __('Name') }}</th>
                                                                <th>{{ __('Code') }}</th>
                                                                <th>{{ __('RTL') }}</th>
                                                                <th>{{ __('Status') }}</th>
                                                                <th>{{ __('Action') }}</th>
                                                            </tr>
                                                        </thead>
                                                        <tbody>
                                                            @foreach ($languages as $key => $language)
                                                                <tr>
                                                                    <td>{{ $loop->iteration }}</td>
                                                                    <td>
                                                                        <div
                                                                            class="tenants-tbl-info-object tbl-info-property-img d-flex align-items-center">
                                                                            <div class="flex-shrink-0 ">
                                                                                <img src="{{ $language->icon }}"
                                                                                    class="rounded avatar-xs tbl-user-image">
                                                                            </div>
                                                                        </div>
                                                                    </td>
                                                                    <td>{{ $language->name }}</td>
                                                                    <td>{{ $language->code }}</td>
                                                                    <td>
                                                                        @if ($language->rtl == 1)
                                                                            <span
                                                                                class="status-btn status-btn-green">{{ __('Yes') }}</span>
                                                                        @else
                                                                            <span
                                                                                class="status-btn status-btn-red">{{ __('No') }}</span>
                                                                        @endif
                                                                    </td>
                                                                    <td>
                                                                        @if ($language->status == 1)
                                                                            <span
                                                                                class="status-btn status-btn-green">{{ __('Published') }}</span>
                                                                        @else
                                                                            <span
                                                                                class="status-btn status-btn-red">{{ __('Unpublished') }}</span>
                                                                        @endif
                                                                    </td>
                                                                    <td>
                                                                        <div class="tbl-action-btns d-inline-flex">
                                                                            <button
                                                                                class="p-1 tbl-action-btn editLanguageBtn"
                                                                                data-data="{{ $language }}"
                                                                                data-font="{{ $language->font }}"
                                                                                title="Edit" data-bs-toggle="modal"
                                                                                data-bs-target="#editLanguageModal">
                                                                                <span class="iconify"
                                                                                    data-icon="clarity:note-edit-solid"></span>
                                                                            </button>

                                                                            <a href="#"
                                                                                class="p-1 tbl-action-btn deleteItem"
                                                                                data-formid="delete_row_form_{{ $language->id }}"
                                                                                title="Delete"><span class="iconify"
                                                                                    data-icon="ep:delete-filled"></span></a>
                                                                            <form
                                                                                action="{{ route('admin.language.delete', [$language->id]) }}"
                                                                                method="post"
                                                                                id="delete_row_form_{{ $language->id }}">
                                                                                {{ method_field('DELETE') }}
                                                                                <input type="hidden" name="_token"
                                                                                    value="{{ csrf_token() }}">
                                                                            </form>
                                                                            <a href="{{ route('admin.language.translate', [$language->id]) }}"
                                                                                class="btn-action" title="Edit">
                                                                                <span class="status-btn status-btn-blue">
                                                                                    {{ __('Translator') }}</span>
                                                                            </a>
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

    {{-- modal  --}}
    <div class="modal fade" id="addLanguageModal" tabindex="-1" aria-labelledby="addLanguageModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <form action="{{ route('admin.language.store') }}" method="POST" enctype="multipart/form-data"
                    class="ajax" data-handler="getShowMessage">
                    @csrf
                    <div class="modal-header">
                        <h4 class="modal-title" id="addLanguageModalLabel">{{ __('Add Language') }}</h4>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"><span
                                class="iconify" data-icon="akar-icons:cross"></span></button>
                    </div>
                    <div class="modal-body">
                        <div class="modal-inner-form-box">
                            <div class="row">
                                <div class="col-md-6 mb-25">
                                    <label
                                        class="label-text-title color-heading font-medium mb-2">{{ __('Name') }}</label>
                                    <input type="text" name="name" class="form-control"
                                        placeholder="{{ __('Name') }}">
                                    <span class="text-danger"></span>
                                </div>
                                <div class="col-md-6 mb-25">
                                    <label
                                        class="label-text-title color-heading font-medium mb-2">{{ __('Language') }}</label>
                                    <select name="code" id="code" class="form-control">
                                        @foreach (languageIsoCode() as $key => $value)
                                            <option value="{{ $key }}">{{ $value }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-6 mb-25">
                                    <label
                                        class="label-text-title color-heading font-medium mb-2">{{ __('RTL') }}</label>
                                    <select class="form-select flex-shrink-0" name="rtl">
                                        <option value="0">{{ __('No') }}</option>
                                        <option value="1">{{ __('Yes') }}</option>
                                    </select>
                                </div>
                                <div class="col-md-6 mb-25">
                                    <label
                                        class="label-text-title color-heading font-medium mb-2">{{ __('Default') }}</label>
                                    <select class="form-select flex-shrink-0" name="default">
                                        <option value="0">{{ __('No') }}</option>
                                        <option value="1">{{ __('Yes') }}</option>
                                    </select>
                                </div>
                                <div class="col-md-6 mb-25">
                                    <label
                                        class="label-text-title color-heading font-medium mb-2">{{ __('Status') }}</label>
                                    <select class="form-select flex-shrink-0" name="status">
                                        <option value="1">{{ __('Active') }}</option>
                                        <option value="0">{{ __('Deactivate') }}</option>
                                    </select>
                                </div>
                                <div class="col-md-6 mb-25">
                                    <label class="label-text-title color-heading font-medium mb-2">{{ __('Font') }}
                                        ({{ __('Optional') }})</label>
                                    <input type="file" class="form-control" name="font" />
                                    <small>{{ __('Language font') }}</small>
                                </div>
                            </div>
                            <div class="row">
                                <label class="label-text-title color-heading font-medium mb-2">{{ __('Icon') }}</label>
                                <!-- Upload Profile Photo Box Start -->
                                <div class="upload-profile-photo-box">
                                    <div class="profile-user position-relative d-inline-block">
                                        <img src="{{ asset('assets/images/users/empty-user.jpg') }}"
                                            class="rounded-circle avatar-md language-icon-image" alt="">
                                        <div class="avatar-xs p-0 rounded-circle language-icon-edit">
                                            <input id="language-icon-img-file-input" type="file" name="icon"
                                                class="language-icon-img-file-input">
                                            <label for="language-icon-img-file-input"
                                                class="language-icon-edit avatar-xs">
                                                <span class="avatar-title rounded-circle" title="Upload Image">
                                                    <i class="ri-camera-fill"></i>
                                                </span>
                                            </label>
                                        </div>
                                    </div>
                                    <div class="language-icon-recommend-size font-13 mt-2">{{ __('Recommend Size') }}: 30
                                        x 30 (1MB)</div>
                                </div>
                                <!-- Upload Profile Photo Box End -->
                            </div>
                        </div>
                        <!-- Modal Inner Form Box End -->
                    </div>
                    <div class="modal-footer justify-content-start">
                        <button type="button" class="theme-btn-back me-3" data-bs-dismiss="modal"
                            title="Back">{{ __('Back') }}</button>
                        <button type="submit" class="theme-btn me-3" title="Submit">{{ __('Submit') }}</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    <div class="modal fade" id="editLanguageModal" tabindex="-1" aria-labelledby="editLanguageModalLabel"
        aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <form action="" method="POST" enctype="multipart/form-data" class="ajax"
                    data-handler="getShowMessage">
                    @csrf
                    <div class="modal-header">
                        <h4 class="modal-title" id="editLanguageModalLabel">{{ __('Edit Language') }}</h4>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"><span
                                class="iconify" data-icon="akar-icons:cross"></span></button>
                    </div>
                    <div class="modal-body">
                        <!-- Modal Inner Form Box Start -->
                        <div class="modal-inner-form-box">
                            <div class="row">
                                <div class="col-md-6 mb-25">
                                    <label
                                        class="label-text-title color-heading font-medium mb-2">{{ __('Name') }}</label>
                                    <input type="text" name="name" class="form-control"
                                        placeholder="{{ __('Name') }}">
                                    <span class="text-danger"></span>
                                </div>
                                <div class="col-md-6 mb-25">
                                    <label
                                        class="label-text-title color-heading font-medium mb-2">{{ __('Language') }}</label>
                                    <input type="text" name="code" class="form-control" disabled>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-6 mb-25">
                                    <label
                                        class="label-text-title color-heading font-medium mb-2">{{ __('RTL') }}</label>
                                    <select class="form-select flex-shrink-0" name="rtl">
                                        <option value="0">{{ __('No') }}</option>
                                        <option value="1">{{ __('Yes') }}</option>
                                    </select>
                                </div>
                                <div class="col-md-6 mb-25">
                                    <label
                                        class="label-text-title color-heading font-medium mb-2">{{ __('Default') }}</label>
                                    <select class="form-select flex-shrink-0" name="default">
                                        <option value="0">{{ __('No') }}</option>
                                        <option value="1">{{ __('Yes') }}</option>
                                    </select>
                                </div>
                                <div class="col-md-6 mb-25">
                                    <label
                                        class="label-text-title color-heading font-medium mb-2">{{ __('Status') }}</label>
                                    <select class="form-select flex-shrink-0" name="status">
                                        <option value="1">{{ __('Active') }}</option>
                                        <option value="0">{{ __('Deactivate') }}</option>
                                    </select>
                                </div>
                                <div class="col-md-6 mb-25">
                                    <label class="label-text-title color-heading font-medium mb-2">{{ __('Font') }}
                                        ({{ __('Optional') }})</label>
                                    <input type="file" class="form-control" name="font" />
                                    <small><a href="" class="font d-none">{{ __('view') }}</a></small>
                                </div>
                            </div>
                            <div class="row">
                                <label class="label-text-title color-heading font-medium mb-2">{{ __('Icon') }}</label>
                                <!-- Upload Profile Photo Box Start -->
                                <div class="upload-profile-photo-box">
                                    <div class="profile-user position-relative d-inline-block">
                                        <img src="{{ asset('assets/images/users/empty-user.jpg') }}"
                                            class="rounded-circle avatar-md language-icon-image-edit" alt=""
                                            id="editImageShow">
                                        <div class="avatar-xs p-0 rounded-circle language-icon-edit">
                                            <input id="language-icon-img-file-input-edit" type="file" name="icon"
                                                class="language-icon-img-file-input-edit">
                                            <label for="language-icon-img-file-input-edit"
                                                class="language-icon-edit avatar-xs">
                                                <span class="avatar-title rounded-circle" title="Upload Image">
                                                    <i class="ri-camera-fill"></i>
                                                </span>
                                            </label>
                                        </div>
                                    </div>
                                    <div class="language-icon-recommend-size font-13 mt-2">{{ __('Recommend Size') }}: 30
                                        x 30 (1MB)</div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer justify-content-start">
                        <button type="button" class="theme-btn-back me-3" data-bs-dismiss="modal"
                            title="Back">{{ __('Back') }}</button>
                        <button type="submit" class="theme-btn me-3" title="Submit">{{ __('Submit') }}</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    <div id="addLanguageRoute" data-route="{{ route('admin.language.store') }}"></div>
    <div id="editLanguageRoute" data-route="{{ route('admin.language.update', '@') }}"></div>
@endsection

@push('style')
    @include('common.layouts.datatable-style')
@endpush

@push('script')
    @include('common.layouts.datatable-script')
    <script src="{{ asset('assets/js/pages/alldatatables.init.js') }}"></script>
    <script src="{{ asset('assets/js/pages/add-language-icon.init.js') }}"></script>
    <script src="{{ asset('assets/js/custom/language.js') }}"></script>
@endpush
