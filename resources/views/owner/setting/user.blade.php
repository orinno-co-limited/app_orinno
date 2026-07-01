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
                                        <li class="breadcrumb-item active" aria-current="page">{{ __('Role Based') }}</li>
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
                                    <div class="language-settings-page-area">
                                        <div class="account-settings-content-box">
                                            <div class="account-settings-title border-bottom mb-20 pb-20">
                                                <div class="row align-items-center">
                                                    <div class="col-md-6">
                                                        <h4>{{ __('All Users') }}</h4>
                                                    </div>
                                                    <div class="col-md-6">
                                                        <div class="property-details-right text-end">
                                                            <button type="button" class="theme-btn" data-bs-toggle="modal"
                                                                data-bs-target="#addUserModal" title="{{ __('Add User') }}">
                                                                {{ __('Add User') }}
                                                            </button>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>

                                            <div class="language-list-table-area">
                                                <div class="bg-off-white theme-border radius-4 p-25">
                                                    <table class="table bg-off-white theme-border p-20 dt-responsive">
                                                        <thead>
                                                            <tr>
                                                                <th>{{ __('Name') }}</th>
                                                                <th>{{ __('Email') }}</th>
                                                                <th>{{ __('Role') }}</th>
                                                                <th>{{ __('Action') }}</th>
                                                            </tr>
                                                        </thead>
                                                        <tbody>
                                                            @foreach ($users as $user)
                                                                <tr>
                                                                    <td>{{ $user->name }}</td>
                                                                    <td>{{ $user->email }} </td>
                                                                    <td>{{ getRoleName($user->role) }}</td>
                                                                    <td>
                                                                        <div class="tbl-action-btns d-inline-flex">
                                                                            <a class="p-1 tbl-action-btn edit"
                                                                                data-item="{{ $user }}"
                                                                                data-updateurl="{{ route('owner.setting.user.update', $user->id) }}"
                                                                                data-bs-toggle="modal"
                                                                                data-bs-target="#editUserModal"
                                                                                title="Edit"><span class="iconify"
                                                                                    data-icon="clarity:note-edit-solid"></span>
                                                                            </a>
                                                                            <button class="p-1 tbl-action-btn deleteItem"
                                                                                data-formid="delete_row_form_{{ $user->id }}">
                                                                                <span class="iconify"
                                                                                    data-icon="ep:delete-filled"></span>
                                                                            </button>
                                                                            <form
                                                                                action="{{ route('owner.setting.user.destroy', [$user->id]) }}"
                                                                                method="post"
                                                                                id="delete_row_form_{{ $user->id }}">
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

    <div class="modal fade" id="addUserModal" tabindex="-1" aria-labelledby="addUserModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title" id="addUserModalLabel">{{ __('Add User') }}</h4>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"><span
                            class="iconify" data-icon="akar-icons:cross"></span>
                    </button>
                </div>
                <form action="{{ route('owner.setting.user.store') }}" method="post">
                    @csrf
                    <div class="modal-body">
                        <div class="modal-inner-form-box">

                            <div class="row">
                                <div class="col-md-6 mb-25">
                                    <label
                                        class="label-text-title color-heading font-medium mb-2">{{ __('First Name') }}</label>
                                    <input type="text" name="first_name" class="form-control"
                                        placeholder="{{ __('Editor') }}">
                                </div>
                                <div class="col-md-6 mb-25">
                                    <label
                                        class="label-text-title color-heading font-medium mb-2">{{ __('Last Name') }}</label>
                                    <input type="text" name="last_name" class="form-control"
                                        placeholder="{{ __('Editor') }}">
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-6 mb-25">
                                    <label
                                        class="label-text-title color-heading font-medium mb-2">{{ __('Email') }}</label>
                                    <input type="email" name="email" class="form-control"
                                        placeholder="editor@email.com">
                                </div>
                                <div class="col-md-6 mb-25">
                                    <label
                                        class="label-text-title color-heading font-medium mb-2">{{ __('Password') }}</label>
                                    <input type="password" name="password" class="form-control" placeholder="******">
                                </div>
                                <div class="col-md-6 mb-25">
                                    <label
                                        class="label-text-title color-heading font-medium mb-2">{{ __('Role') }}</label>
                                    <select name="role" class="form-select flex-shrink-0">
                                        <option selected>{{ __('Select Option') }}</option>
                                        <option value="1">{{ __('Owner') }}</option>
                                        <option value="2">{{ __('Tenant') }}</option>
                                        <option value="3">{{ __('Maintainer') }}</option>
                                    </select>
                                </div>
                                <div class="col-md-6 mb-25">
                                    <label
                                        class="label-text-title color-heading font-medium mb-2">{{ __('Contact Number') }}</label>
                                    <input type="text" name="contact_number" class="form-control"
                                        placeholder="{{ __('Type contact number') }}">
                                </div>
                                <div class="col-md-6 mb-25">
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
                            title="Back">{{ __('Back') }}</button>
                        <button type="submit" class="theme-btn me-3" title="Save">{{ __('Save') }}</button>
                    </div>
                </form>

            </div>
        </div>
    </div>


    <div class="modal fade edit_modal" id="editUserModal" tabindex="-1" aria-labelledby="editUserModalLabel"
        aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title" id="editUserModalLabel">{{ __('Edit User') }}</h4>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"><span
                            class="iconify" data-icon="akar-icons:cross"></span>
                    </button>
                </div>
                <form action="" id="updateEditModal" method="post">
                    @csrf
                    {{ method_field('PUT') }}
                    <div class="modal-body">
                        <div class="modal-inner-form-box">

                            <div class="row">
                                <div class="col-md-6 mb-25">
                                    <label
                                        class="label-text-title color-heading font-medium mb-2">{{ __('First Name') }}</label>
                                    <input type="text" name="first_name" class="form-control"
                                        placeholder="{{ __('Editor') }}">
                                </div>
                                <div class="col-md-6 mb-25">
                                    <label
                                        class="label-text-title color-heading font-medium mb-2">{{ __('Last Name') }}</label>
                                    <input type="text" name="last_name" class="form-control"
                                        placeholder="{{ __('Editor') }}">
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-6 mb-25">
                                    <label
                                        class="label-text-title color-heading font-medium mb-2">{{ __('Email') }}</label>
                                    <input type="email" name="email" class="form-control"
                                        placeholder="editor@email.com">
                                </div>
                                <div class="col-md-6 mb-25">
                                    <label
                                        class="label-text-title color-heading font-medium mb-2">{{ __('Password') }}</label>
                                    <input type="password" name="password" class="form-control" placeholder="******">
                                </div>
                                <div class="col-md-6 mb-25">
                                    <label
                                        class="label-text-title color-heading font-medium mb-2">{{ __('Role') }}</label>
                                    <select name="role" class="form-select flex-shrink-0">
                                        <option selected>{{ __('Select Option') }}</option>
                                        <option value="1">{{ __('Owner') }}</option>
                                        <option value="2">{{ __('Tenant') }}</option>
                                        <option value="3">{{ __('Maintainer') }}</option>
                                    </select>
                                </div>
                                <div class="col-md-6 mb-25">
                                    <label
                                        class="label-text-title color-heading font-medium mb-2">{{ __('Contact Number') }}</label>
                                    <input type="text" name="contact_number" class="form-control"
                                        placeholder="{{ __('Type contact number') }}">
                                </div>
                                <div class="col-md-6 mb-25">
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
@endsection

@push('style')
    @include('common.layouts.datatable-style')
@endpush

@push('script')
    @include('common.layouts.datatable-script')
    <script src="{{ asset('/') }}assets/js/pages/settings-invoice-type-datatables.init.js"></script>
    <script>
        $(function() {
            'use strict'
            $('.edit').on('click', function(e) {
                e.preventDefault();
                const modal = $('.edit_modal');
                modal.find('input[name=first_name]').val($(this).data('item').first_name)
                modal.find('input[name=last_name]').val($(this).data('item').last_name)
                modal.find('input[name=email]').val($(this).data('item').email)
                modal.find('input[name=contact_number]').val($(this).data('item').contact_number)
                modal.find('select[name=role]').val($(this).data('item').role)
                modal.find('select[name=status]').val($(this).data('item').status)
                let route = $(this).data('updateurl');
                $('#updateEditModal').attr("action", route)
                modal.modal('show')
            })
        })
    </script>
@endpush
