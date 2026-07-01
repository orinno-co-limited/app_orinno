@extends('admin.layouts.app')
@push('style')
    <style>
        /* The total progress gets shown by event listeners */
        #previews #total-progress {
            opacity: 0;
            height: 0;
            transition: opacity 0.3s linear;
        }

        #previews .upload-completed {
            height: 0;
            opacity: 0;
        }

        #previews .dz-processing #total-progress {
            opacity: 100;
            height: 15px;
            transition: opacity 0.3s linear;
        }

        #previews .file-upload.dz-success #total-progress {
            opacity: 0;
            height: 0;
            transition: opacity 0.3s linear;
        }

        #previews .file-upload.dz-success .upload-completed {
            opacity: 100;
            height: 20px;
        }

        #previews .progress {
            background-color: var(--bs-progress-bg) !important;
        }

        /* Hide the progress bar when finished */
        #previews .file-upload.dz-success .progress {
            opacity: 0;
            height: 0;
            transition: opacity 0.3s linear;
        }

        #previews .file-upload.dz-error .progress {
            display: none;
        }


        /* Hide the delete button initially */
        #previews .file-upload .delete-btn {
            display: none;
        }

        /* Hide the start and cancel buttons and show the delete button */

        #previews .file-upload.dz-success .start,
        #previews .file-upload.dz-success .cancel {
            display: none;
        }

        #previews .file-upload.dz-success .delete-btn {
            display: block;
        }

        #previews .file-upload.dz-error .start-btn {
            display: none;
        }

        .fs-7 {
            font-size: 0.8rem;
        }
    </style>
@endpush
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
                        <div class="billing-center-area bg-off-white theme-border radius-4 p-25">
                            @if (getCustomerCurrentBuildVersion() == $latestBuildVersion)
                                <div class="col-sm-12">
                                    <div class="alert alert-info" type="info" icon="info-circle">
                                        <i class="fa fa-info-circle"></i>
                                        {{ __('You have the latest version of this app.') }}
                                    </div>
                                </div>
                            @endif
                            <div class="col-sm-12">
                                <div class="table-responsive">
                                    <table class="table table-responsive table-style table-sm table-striped table-bordered">
                                        <thead>
                                            <tr>
                                                <th colspan="2">{{ __('System Details') }}</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <tr>
                                                <td>{{ __('Current Version') }}</td>
                                                <td>
                                                    @if (getCustomerCurrentBuildVersion() == $latestBuildVersion)
                                                        {{ getOption('current_version') }} <i
                                                            class="fa  fa-check-circle text-success"></i>
                                                    @else
                                                        {{ getOption('current_version') }} <i data-bs-toggle="tooltip"
                                                            data-bs-placement="top" title="download latest from codecanyon"
                                                            class="fa fa-warning text-danger"></i>
                                                    @endif
                                                </td>
                                            </tr>
                                            @if (getCustomerCurrentBuildVersion() < $latestBuildVersion)
                                                <tr>
                                                    <td>
                                                        {{ __('Latest Version') }}
                                                        <a class="text-link" target="_blank"
                                                            href="https://codecanyon.net/item/zaiproty-property-management-laravel-script/43413718">{{ __('Download Latest') }}</a>
                                                    </td>
                                                    <td>{{ $latestVersion }}</td>
                                                </tr>
                                            @endif
                                            <tr>
                                                <td>{{ __('Laravel Version') }}</td>
                                                <td>{{ app()->version() }}</td>
                                            </tr>
                                            <tr>
                                                <td>{{ __('PHP Version') }}</td>
                                                <td>{{ phpversion() }}</td>
                                            </tr>
                                            @if (!is_null($mysql_version))
                                                <tr>
                                                    <td>{{ $databaseType }}</td>
                                                    <td>{{ $mysql_version }}</td>
                                                </tr>
                                            @endif
                                        </tbody>
                                    </table>
                                </div>
                            </div>

                            @if (getCustomerCurrentBuildVersion() < $latestBuildVersion)
                                <div class="col-md-8 offset-md-2">
                                    <div class="alert alert-danger" type="danger">
                                        <ol class="mb-0">
                                            <li>{{ __('Do not click update button if the application is customised. Your changes will be lost') }}.
                                            </li>
                                            <li>{{ __('Take backup all the files and database before updating.') }}</li>
                                        </ol>
                                    </div>
                                    <div class="table-responsive">
                                        <table class="table table-bordered table-responsive table-style">
                                            <tbody class="align-baseline">
                                                <tr>
                                                    <td colspan="2">
                                                        <div class="d-flex justify-content-center">
                                                            <span class="btn btn-success mb-4 p-2" id="dz-clickable">
                                                                <i class="fa fa-upload"></i>
                                                                <span>{{ __('Upload File') }}</span>
                                                            </span>
                                                            <div class="files" id="previews">

                                                                <div id="template" class="file-upload row">
                                                                    <div class="col-md-12">
                                                                        <table class="table table-borderless mb-0">
                                                                            <tr>
                                                                                <td>
                                                                                    <span class="preview text-danger"><i
                                                                                            class="fa fa-file-archive h1"></i></span>
                                                                                </td>
                                                                                <td>
                                                                                    <p class="name" data-dz-name></p>
                                                                                    <strong
                                                                                        class="error text-danger error-message"
                                                                                        data-dz-errormessage></strong>
                                                                                </td>
                                                                                <td>
                                                                                    <p class="d-flex size" data-dz-size></p>
                                                                                </td>
                                                                                <td width="251px">
                                                                                    <div id="actions">
                                                                                        <button
                                                                                            class="btn btn-blue start start-btn p-2">
                                                                                            <i class="fa fa-upload"></i>
                                                                                            <span>{{ __('Start') }}</span>
                                                                                        </button>
                                                                                        <button id="cancel-btn"
                                                                                            class="btn btn-warning cancel p-2">
                                                                                            <i class="fa fa-cancel"></i>
                                                                                            <span>{{ __('Cancel') }}</span>
                                                                                        </button>
                                                                                        <a data-url="{{ route('admin.file-version-update-execute') }}"
                                                                                            class="update-execute-btn btn btn-outline-success p-2 rounded-3 delete-btn">
                                                                                            <i
                                                                                                class="fa fa-download mr-1"></i>
                                                                                            {{ __('Update') }}
                                                                                        </a>
                                                                                    </div>
                                                                                </td>
                                                                            </tr>
                                                                        </table>
                                                                    </div>
                                                                    <div class="progress progress-striped active col-md-12 p-0"
                                                                        id="total-progress" role="progressbar"
                                                                        aria-valuemin="0" aria-valuemax="100"
                                                                        aria-valuenow="0">
                                                                        <div class="progress-bar progress-bar-success"
                                                                            style="width:0%;" data-dz-uploadprogress></div>
                                                                    </div>
                                                                    <div
                                                                        class="bold fw-bold text-center text-success upload-completed">
                                                                        <span>{{ __('Upload Completed') }}</span>
                                                                    </div>
                                                                </div>

                                                            </div>
                                                            @if ($errors->has('update_file'))
                                                                <span class="text-danger"><i
                                                                        class="fas fa-exclamation-triangle"></i>
                                                                    {{ $errors->first('update_file') }}</span>
                                                            @endif
                                                        </div>
                                                    </td>
                                                </tr>
                                                @if ($uploadedFile != '')
                                                    <tr>
                                                        <td>
                                                            {{ $uploadedFile }}
                                                            <a data-url="{{ route('admin.file-version-delete') }}"
                                                                data-reload="true"
                                                                class="btn btn-outline-danger p-1 rounded-3 delete">
                                                                <i class="fa fa-trash mr-1"></i>
                                                                {{ __('Delete') }}
                                                            </a>
                                                        </td>
                                                        <td>
                                                            <a data-url="{{ route('admin.file-version-update-execute') }}"
                                                                class="update-execute-btn btn btn-outline-success p-2 rounded-3">
                                                                <i class="fa fa-download mr-1"></i>
                                                                {{ __('Update') }}
                                                            </a>
                                                        </td>
                                                    </tr>
                                                @endif
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            @endif
                        </div>

                        <div class="billing-center-area bg-off-white theme-border radius-4 p-25 mt-5">
                            <div class="tenants-top-bar border-bottom pb-25">
                                <div class="row align-items-center">
                                    <div class="col-xl-12 col-xxl-6 tenants-top-bar-left">
                                        <h4>{{ __('Zaiproty Official Addons') }}</h4>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                @foreach ($addons as $addon)
                                    <div class="col-sm-12 border rounded p-3 mt-5">
                                        <div class="row align-items-center">
                                            <div class="col-xs-2 col-lg-1">
                                                <a href="{{ $addon->details->codecanyon_url }}" target="_blank">
                                                    <img src="https://support.zainikthemes.com/uploaded_files/images/app_image/{{ $addon->logo }}"
                                                        class="img-responsive img-thumbnail" alt="">
                                                </a>
                                            </div>
                                            <div class="col-xs-8 col-lg-5">
                                                <a href="{{ $addon->details->codecanyon_url }}" target="_blank"
                                                    class="fw-bold text-darkest-grey">
                                                    {{ $addon->title }}
                                                </a>
                                                @if (isAddonInstalled($addon->code) > 0)
                                                    <span class="badge bg-success ms-1">{{ __('Installed') }}
                                                        {{ getOption($addon->code . '_current_version') }}</span>
                                                @endif
                                                <p class="text-muted">
                                                    {!! $addon->details?->description !!}
                                                </p>
                                            </div>
                                            <div class="col-xs-2 col-lg-6 text-end pt-2">
                                                <a href="{{ route('admin.addon.details', $addon->code) }}"
                                                    class="btn btn-primary rounded fw-bolder p-2">
                                                    <i class="ri-arrow-right-line"></i>
                                                </a>
                                            </div>
                                        </div>
                                    </div>
                                @endforeach

                            </div>
                        </div>

                    </div>
                </div>
            </div>
        </div>
    </div>


    <!-- Page content area end -->
@endsection
@push('script')
    <script src="https://unpkg.com/dropzone@5/dist/min/dropzone.min.js"></script>
    <link rel="stylesheet" href="https://unpkg.com/dropzone@5/dist/min/dropzone.min.css" type="text/css" />
    <script>
        var previewNode = document.querySelector("#template");
        previewNode.id = "";
        var previewTemplate = previewNode.parentNode.innerHTML;
        previewNode.parentNode.removeChild(previewNode);

        var myDropzone = new Dropzone(document.body, { // Make the whole body a dropzone
            url: "{{ route('admin.file-version-update-store') }}", // Set the url
            method: 'post',
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            paramName: 'update_file',
            thumbnailWidth: 80,
            thumbnailHeight: 80,
            parallelUploads: 1,
            acceptedFiles: '.zip',
            previewTemplate: previewTemplate,
            autoQueue: false, // Make sure the files aren't queued until manually added
            previewsContainer: "#previews", // Define the container to display the previews
            clickable: "#dz-clickable" // Define the element that should be used as click trigger to select files.
        });


        myDropzone.on("addedfile", function(file) {
            // Hookup the start button
            file.previewElement.querySelector(".start").onclick = function() {
                myDropzone.enqueueFile(file);
            };
            $('#dz-clickable').addClass('d-none');
        });

        myDropzone.on("totaluploadprogress", function(progress) {
            var progressbar = document.querySelector("#total-progress .progress-bar");
            if (typeof progressbar != 'undefined' && progressbar != null) {
                document.querySelector("#total-progress .progress-bar").style.width = progress + "%";
            }
        });

        myDropzone.on("error", function(file, response) {
            if (typeof response.errors != 'undefined') {
                $('#previews .error-message').text(response.errors?.update_file[0]);
            } else {
                $('#previews .error-message').text(response.message);
            }
        });

        $(document).on('click', '#cancel-btn', function() {
            myDropzone.removeAllFiles(true);
            $('#dz-clickable').removeClass('d-none');
        });

        $(document).on('click', '.update-execute-btn', function() {
            Swal.fire({
                title: "{{ __('Version Update Execute') }}",
                html: `<div class="alert alert-danger fs-7 px-0 text-start" type="danger">
                        <ol class="mb-0">
                            <li>Do not click update now button if the application is customised. Your changes will be lost.</li>
                            <li>Take backup all the files and database before updating.</li>
                        </ol>
                    </div>`,
                icon: 'info',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Update Now'
            }).then((result) => {
                if (result.value) {
                    location.replace($(this).data('url'));
                }
            })
        })
    </script>
@endpush
