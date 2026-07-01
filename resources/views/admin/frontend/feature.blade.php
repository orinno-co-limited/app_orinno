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
                                                title="Settings">{{ __('Settings') }}</a></li>
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
                                                        <h4>{{ @$pageTitle }}</h4>
                                                    </div>
                                                    @if($features->count() < 4)
                                                        <div class="col-md-6">
                                                            <div class="property-details-right text-end">
                                                                <button type="button" class="theme-btn" id="add"
                                                                    title="{{ __('Add Amazing Feature') }}">{{ __('Add Amazing Feature') }}</button>
                                                            </div>
                                                        </div>
                                                    @endif
                                                </div>
                                            </div>
                                            <div class="currency-list-table-area">
                                                <div class="bg-off-white theme-border radius-4 p-25">
                                                    <table id="allDataTable"
                                                        class="table bg-off-white theme-border p-20 dt-responsive">
                                                        <thead>
                                                            <tr>
                                                                <th>{{ __('Icon') }}</th>
                                                                <th>{{ __('Title') }}</th>
                                                                <th>{{ __('Summary') }}</th>
                                                                <th>{{ __('Status') }}</th>
                                                                <th>{{ __('Action') }}</th>
                                                            </tr>
                                                        </thead>
                                                        <tbody>
                                                            @foreach ($features as $feature)
                                                                <tr>
                                                                    <td>
                                                                        <div class="upload-profile-photo-box mb-25">
                                                                            <div
                                                                                class="profile-user position-relative d-inline-block">
                                                                                <img src="{{ $feature->icon }}"
                                                                                     class="rounded-circle avatar-xl maintainer-user-profile-image image"
                                                                                     alt="">
                                                                            </div>
                                                                        </div>
                                                                    </td>
                                                                    <td>{{ $feature->title }}</td>
                                                                    <td>{{ Str::limit($feature->summary, 100, '...') }}
                                                                    </td>
                                                                    <td>
                                                                        @if ($feature->status == ACTIVE)
                                                                            <div
                                                                                class="status-btn status-btn-blue font-13 radius-4">
                                                                                {{ __('Active') }}</div>
                                                                        @else
                                                                            <div
                                                                                class="status-btn status-btn-orange font-13 radius-4">
                                                                                {{ __('Deactivate') }}</div>
                                                                        @endif
                                                                    </td>
                                                                    <td>
                                                                        <div class="tbl-action-btns d-inline-flex">
                                                                            <button class="p-1 tbl-action-btn edit"
                                                                                data-id="{{ $feature->id }}"
                                                                                title="{{ __('Edit') }}"><span
                                                                                    class="iconify"
                                                                                    data-icon="clarity:note-edit-solid"></span>
                                                                            </button>
                                                                            <button class="p-1 tbl-action-btn deleteItem"
                                                                                data-formid="delete_row_form_{{ $feature->id }}">
                                                                                <span class="iconify"
                                                                                    data-icon="ep:delete-filled"></span>
                                                                            </button>
                                                                            <form
                                                                                action="{{ route('admin.feature.destroy', [$feature->id]) }}"
                                                                                method="post"
                                                                                id="delete_row_form_{{ $feature->id }}">
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
                    <h4 class="modal-title" id="addModalLabel">{{ __('Add Amazing Feature') }}</h4>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"><span
                            class="iconify" data-icon="akar-icons:cross"></span>
                    </button>
                </div>
                <form class="ajax" action="{{ route('admin.feature.store') }}" method="post"
                    data-handler="getShowMessage">
                    <div class="modal-body">
                        <div class="modal-inner-form-box">
                            <div class="row">
                                <div class="col-md-12 mb-25">
                                    <label
                                        class="label-text-title color-heading font-medium mb-2">{{ __('Title') }}</label>
                                    <input type="text" name="title" class="form-control"
                                        placeholder="{{ __('Title') }}">
                                </div>
                                <div class="col-md-12 mb-25">
                                    <label
                                        class="label-text-title color-heading font-medium mb-2">{{ __('Summary') }}</label>
                                    <textarea name="summary" id="summary" class="form-control" placeholder="{{ __('Summery') }}"></textarea>
                                </div>
                            </div>
                            <div class="col-md-12 mb-25">
                                <label
                                    class="label-text-title color-heading font-medium mb-2">{{ __('Icon') }}</label>
                                <input type="file" class="form-control" name="icon">
                            </div>
                            <div class="row">
                                <div class="col-md-12 mb-25">
                                    <label
                                        class="label-text-title color-heading font-medium mb-2">{{ __('Status') }}</label>
                                    <select name="status" class="form-select flex-shrink-0">
                                        <option value="1">{{ __('Active') }}</option>
                                        <option value="0">{{ __('Deactivate') }}</option>
                                    </select>
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

    <div class="modal fade" id="editModal" tabindex="-1" aria-labelledby="editModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title" id="editModalLabel">{{ __('Edit Amazing Feature') }}</h4>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"><span
                            class="iconify" data-icon="akar-icons:cross"></span>
                    </button>
                </div>
                <form class="ajax" action="{{ route('admin.feature.store') }}" method="post"
                    data-handler="getShowMessage">
                    <input type="hidden" name="id">
                    <div class="modal-body">
                        <div class="modal-inner-form-box">
                            <div class="row">
                                <div class="col-md-12 mb-25">
                                    <label
                                        class="label-text-title color-heading font-medium mb-2">{{ __('Title') }}</label>
                                    <input type="text" name="title" class="form-control"
                                        placeholder="{{ __('Title') }}">
                                </div>
                                <div class="col-md-12 mb-25">
                                    <label
                                        class="label-text-title color-heading font-medium mb-2">{{ __('Summary') }}</label>
                                    <textarea name="summary" id="summary" class="form-control" placeholder="{{ __('Summery') }}"></textarea>
                                </div>
                            </div>
                            <div class="col-md-12 mb-25">
                                <label
                                    class="label-text-title color-heading font-medium mb-2">{{ __('Icon') }}</label>
                                <input type="file" class="form-control" name="icon">
                            </div>
                            <div class="row">
                                <div class="col-md-12 mb-25">
                                    <label
                                        class="label-text-title color-heading font-medium mb-2">{{ __('Status') }}</label>
                                    <select name="status" class="form-select flex-shrink-0">
                                        <option value="1">{{ __('Active') }}</option>
                                        <option value="0">{{ __('Deactivate') }}</option>
                                    </select>
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
    <input type="hidden" id="getInfoRoute" value="{{ route('admin.feature.get.info') }}">
@endsection

@push('style')
    @include('common.layouts.datatable-style')
@endpush

@push('script')
    @include('common.layouts.datatable-script')
    <script src="{{ asset('assets/js/pages/alldatatables.init.js') }}"></script>
    <script src="{{ asset('assets/js/custom/feature.js') }}"></script>
@endpush
