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
                                                <div class="row align-items-center rg-24">
                                                    <div class="col-xl-6">
                                                        <h4>{{ $pageTitle }}</h4>
                                                    </div>
                                                    <div class="col-xl-6">
                                                        <div class="property-details-right text-xl-end">
                                                            <button type="button" class="theme-btn" id="add"
                                                                    title="{{ __('Add Blog') }}">{{ __('Add Blog') }}</button>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="tickets-topic-table-area">
                                                <div class="bg-white theme-border radius-4 p-25">
                                                    <table id="allDataTable"
                                                           class="table bg-white theme-border p-20 dt-responsive">
                                                        <thead>
                                                        <tr>
                                                            <th>{{ __('SL') }}</th>
                                                            <th>{{ __('Banner Image') }}</th>
                                                            <th>{{ __('Category Name') }}</th>
                                                            <th>{{ __('Title') }}</th>
                                                            <th>{{ __('Status') }}</th>
                                                            <th>{{ __('Action') }}</th>
                                                        </tr>
                                                        </thead>
                                                        <tbody>
                                                        @foreach ($blog as $data)
                                                            <tr>
                                                                <td>{{ $loop->iteration }}</td>
                                                                <td>
                                                                    <div class="upload-profile-photo-box mb-25">
                                                                        <div
                                                                            class="profile-user position-relative d-inline-block">
                                                                            <img src="{{ $data->image }}"
                                                                                 class="rounded-circle avatar-xl maintainer-user-profile-image image"
                                                                                 alt="">
                                                                        </div>
                                                                    </div>
                                                                </td>
                                                                <td>{{ $data->blogCategory?->name }}</td>
                                                                <td>{{ $data->title }}</td>
                                                                <td>
                                                                    @if ($data->status == ACTIVE)
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
                                                                           data-id="{{ $data->id }}"
                                                                           title="{{ __('Edit') }}">
                                                                                <span class="iconify"
                                                                                      data-icon="clarity:note-edit-solid"></span>
                                                                        </a>
                                                                        <a href="#"
                                                                           class="p-1 tbl-action-btn deleteItem"
                                                                           data-formid="delete_row_form_{{ $data->id }}"
                                                                           title="Delete"><span class="iconify"
                                                                                                data-icon="ep:delete-filled"></span></a>
                                                                        <form
                                                                            action="{{ route('admin.blogs.destroy', [$data->id]) }}"
                                                                            method="post"
                                                                            id="delete_row_form_{{ $data->id }}">
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
                    <h4 class="modal-title" id="addModalLabel">{{ __('Add Blog') }}</h4>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"><span
                            class="iconify" data-icon="akar-icons:cross"></span></button>
                </div>
                <form class="ajax" action="{{ route('admin.blogs.store') }}" method="post"
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
                                <div class="col-md-6 mb-25">
                                    <label
                                        class="label-text-title color-heading font-medium mb-2">{{ __('Blog Category Name') }}</label>
                                    <select name="blog_category_id" class="form-select flex-shrink-0">
                                        @foreach($blogCategory as $data)
                                            <option value="{{ $data->id }}">{{ $data->name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="col-md-6 mb-25">
                                    <label
                                        class="label-text-title color-heading font-medium mb-2">{{ __('Publish Date') }}</label>
                                    <input type="date" name="publish_date" class="form-control">
                                </div>
                                <div class="col-md-12 mb-25">
                                    <label
                                        class="label-text-title color-heading font-medium mb-2">{{ __('Details') }}</label>
                                    <textarea name="details" id="details" class="form-control summernoteOne" placeholder="{{ __('details') }}"></textarea>
                                </div>
                                <div class="col-md-12 mb-25">
                                    <label
                                        class="label-text-title color-heading font-medium mb-2">{{ __('Banner Image') }}</label>
                                    <input type="file" class="form-control" name="image">
                                </div>
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
                    <h4 class="modal-title" id="editModalLabel">{{ __('Edit Blog') }}</h4>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"><span
                            class="iconify" data-icon="akar-icons:cross"></span></button>
                </div>
                <form class="ajax" action="{{ route('admin.blogs.store') }}" method="post"
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
                                <div class="col-md-6 mb-25">
                                    <label
                                        class="label-text-title color-heading font-medium mb-2">{{ __('Blog Category Name') }}</label>
                                    <select name="blog_category_id" class="form-select flex-shrink-0">
                                        @foreach($blogCategory as $data)
                                            <option value="{{ $data->id }}">{{ $data->name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="col-md-6 mb-25">
                                    <label
                                        class="label-text-title color-heading font-medium mb-2">{{ __('Publish Date') }}</label>
                                    <input type="date" name="publish_date" class="form-control">
                                </div>
                                <div class="col-md-12 mb-25">
                                    <label
                                        class="label-text-title color-heading font-medium mb-2">{{ __('Details') }}</label>
                                    <textarea name="details" id="details" class="form-control summernoteOne" placeholder="{{ __('details') }}"></textarea>
                                </div>
                                <div class="col-md-12 mb-25">
                                    <label
                                        class="label-text-title color-heading font-medium mb-2">{{ __('Banner Image') }}</label>
                                    <input type="file" class="form-control" name="image">
                                </div>
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
    <input type="hidden" id="getInfoRoute" value="{{ route('admin.blogs.get.info') }}">
@endsection

@push('style')
    @include('common.layouts.datatable-style')
    <link rel="stylesheet" href="{{ asset('assets/libs/summernote/summernote.min.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/libs/summernote/summernote-lite.min.css') }}">
@endpush

@push('script')
    @include('common.layouts.datatable-script')
    <script src="{{ asset('assets/js/pages/alldatatables.init.js') }}"></script>
    <script src="{{ asset('assets/libs/summernote/summernote-lite.min.js') }}"></script>
    <script src="{{ asset('assets/js/custom/blog.js') }}?v=1"></script>
    <script>
        $(document).ready(function() {
            $(".summernoteOne").summernote({
                placeholder: "{{ __('Description') }}",
                minHeight: 300,
                focus: true,
                toolbar: [
                    ['style', ['style']],
                    ['font', ['bold', 'underline', 'clear']],
                    ['color', ['color']],
                    ['para', ['ul', 'ol', 'paragraph']],
                    ['table', ['table']],
                    ['insert', ['link']],
                    ['view', ['fullscreen', 'help']]
                ]
            });
        });
    </script>
@endpush
