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
                                        <li class="breadcrumb-item"><a href="#"
                                                title="{{ __('Settings') }}">{{ __('Settings') }}</a></li>
                                        <li class="breadcrumb-item active" aria-current="page">{{ $pageTitle }}
                                        </li>
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
                                            <div class="account-settings-title">
                                                <div class="align-items-center row rg-24">
                                                    <div class="col-xl-6">
                                                        <h4>{{ $pageTitle }}</h4>
                                                    </div>
                                                    <div class="col-xl-6">
                                                        <div class="input-group">
                                                            <input type="text" value="{{ route('recurring.generate.invoice') }}" class="form-control">
                                                            <button type="button"
                                                                data-url="{{ route('recurring.generate.invoice') }}"
                                                                class="input-group-text theme-btn-green"
                                                                id="cronUrlCopyBtn">{{ __('Copy') }}</button>
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
@endsection

@push('script')
    <script>
        $('#cronUrlCopyBtn').on('click', function() {
            var $temp = $("<input>");
            $("body").append($temp);
            $temp.val($(this).data('url')).select();
            document.execCommand("copy");
            $temp.remove();
            toastr.success("{{ __('Url Copy to Clipboard') }}")
        });
    </script>
@endpush
