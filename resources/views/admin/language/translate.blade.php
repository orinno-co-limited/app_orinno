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
                                                title="{{ __('Dashboard') }}">{{ __('Dashboard') }}</a></li>
                                        <li class="breadcrumb-item active" aria-current="page">{{ $pageTitle }}</li>
                                    </ol>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="settings-page-layout-wrap position-relative">
                        <div class="row">
                            @include('admin.setting.sidebar')
                            <div class="col-md-12 col-lg-12 col-xl-8 col-xxl-9">
                                <div class="account-settings-rightside bg-off-white theme-border radius-4 p-25">
                                    <div class="language-settings-page-area">
                                        <div class="account-settings-content-box">
                                            <div class="account-settings-title border-bottom mb-20 pb-20">
                                                <div class="row align-items-center">
                                                    <div class="col-md-6">
                                                        <h4> {{ __('Translate Your Language') }} (English =>
                                                            {{ $language->name }} )</h4>
                                                    </div>
                                                    <div class="col-md-6">
                                                        <div class="property-details-right text-end">
                                                            <button type="button" class="theme-btn" data-bs-toggle="modal"
                                                                data-bs-target="#importModal"
                                                                title="{{ __('Import Keywords') }}">
                                                                {{ __('Import Keywords') }}
                                                            </button>
                                                            <button type="button" class="theme-btn-green addmore"> <i
                                                                    class="fa fa-plus"></i>
                                                                {{ __('Add More') }}</button>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="language-list-table-area">
                                                <div class="table-responsive bg-off-white theme-border radius-4 p-25">
                                                    <div class="card-body">
                                                        <div class="row">
                                                            <table
                                                                class="table bg-off-white theme-border p-20 dt-responsive">
                                                                <thead>
                                                                    <tr>
                                                                        <th>{{ __('Key') }}</th>
                                                                        <th>{{ __('Value') }}</th>
                                                                        <th class="text-end">{{ __('Action') }}</th>
                                                                    </tr>
                                                                </thead>
                                                                <tbody id="append">
                                                                    @foreach ($translators as $key => $value)
                                                                        <tr>
                                                                            <td>
                                                                                <textarea type="text" class="key form-control" readonly required>{!! $key !!}</textarea>
                                                                            </td>
                                                                            <td>
                                                                                <input type="hidden" value="0"
                                                                                    class="is_new">
                                                                                <textarea type="text" class="val form-control" required>{!! $value !!}</textarea>
                                                                            </td>
                                                                            <td class="text-end">
                                                                                <button type="button"
                                                                                    class="updateLangItem theme-btn"
                                                                                    disabled>{{ __('Update') }}</button>
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
        </div>
    </div>

    <!-- Add Modal section start -->
    <div class="modal fade" id="importModal" aria-hidden="true" tabindex="-1">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <form class="ajax" action="{{ route('admin.language.import') }}" method="POST"
                    data-handler="getShowMessage">
                    <input type="hidden" name="current" value="{{ $language->code }}">
                    <div class="modal-header">
                        <h5 class="modal-title">{{ __('Import Language') }}</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"><span
                                class="iconify" data-icon="akar-icons:cross"></span></button>
                    </div>
                    <div class="modal-body">
                        <div class="modal-inner-form-box">
                            <div class="row">
                                <div class="mb-30">
                                    <span
                                        class="text-danger text-center">{{ __('Note: If you import keywords, your current keywords will be deleted and replaced by the imported keywords.') }}</span>
                                </div>
                                <div class="col-md-6 mb-25">
                                    <label for="status" class="label-text-title color-heading font-medium mb-2">
                                        {{ __('Language') }} </label>
                                    <select name="import" class="form-select flex-shrink-0 export" id="inputGroupSelect02">
                                        <option value=""> {{ __('Select Option') }} </option>
                                        @foreach ($languages as $lang)
                                            <option value="{{ $lang->code }}">{{ __($lang->name) }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer justify-content-start">
                        <button type="button" class="theme-btn-back me-3" data-bs-dismiss="modal"
                            title="Back">{{ __('Back') }}</button>
                        <button type="submit" class="theme-btn me-3" title="Submit">{{ __('Import') }}</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    <input type="hidden" id="updateLangItemRoute" value="{{ route('admin.language.update.translate', [$language->id]) }}">
@endsection

@push('script')
    <script src="{{ asset('assets/js/custom/language.js') }}"></script>
@endpush
