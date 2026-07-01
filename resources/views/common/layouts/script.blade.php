@if(session('payment_form_html'))
    <div id="virtualPaymentForm" style="display: none;">
        {!! session('payment_form_html') !!}
    </div>
    <script>
        document.addEventListener("DOMContentLoaded", function() {
            // Find the form inside the hidden div and submit it automatically
            var virtualForm = document.getElementById('virtualPaymentForm').querySelector('form');
            if (virtualForm) {
                virtualForm.submit();
            }
        });
    </script>
@endif
<!-- Bootstrap core JavaScript -->
<script src="{{ asset('assets/libs/jquery/jquery-3.6.0.min.js') }}"></script>
<script src="{{ asset('assets/libs/bootstrap/js/bootstrap.bundle.min.js') }}"></script>

<!-- JQuery UI -->
<script src="{{ asset('assets/libs/jquery-easing/jquery.easing.min.js') }}"></script>
<script src="{{ asset('assets/libs/jquery-ui/jquery-ui.min.js') }}"></script>

<!-- Owl Carousel JavaScript -->
<script src="{{ asset('assets/libs/owl-carousel/owl.carousel.min.js') }}"></script>

<!--Venobox-->
<script src="{{ asset('assets/libs/venobox/venobox.min.js') }}"></script>

<!--Iconify Icon-->
<script src="{{ asset('assets/js/iconify.min.js') }}"></script>

<!-- Plugin JavaScript -->
<script src="{{ asset('assets/libs/metismenu/metisMenu.min.js') }}"></script>
<script src="{{ asset('assets/libs/simplebar/simplebar.min.js') }}"></script>

<!-- Dropzone js -->
<script src="{{ asset('/') }}assets/libs/dropzone/dropzone.js"></script>
<script src="{{ asset('/') }}assets/js/pages/form-file-upload.init.js"></script>

<script src="{{ asset('assets/sweetalert2/sweetalert2.all.js') }}"></script>
<script src="{{ asset('assets/js/toastr.min.js') }}"></script>
<script src="{{ asset('assets/js/custom/common.js') }}"></script>
<script src="{{ asset('assets/js/moment.js') }}"></script>
<script src="{{ asset('assets/js/dropify.js') }}"></script>
<input type="hidden" id="deleteTitle" value="{{__("Sure! You want to delete?")}}">
<input type="hidden" id="deleteText" value="{{__("You won't be able to revert this!")}}">
<input type="hidden" id="subscriptionCancelTitle" value="{{__("Sure! You want to cancel?")}}">
<input type="hidden" id="subscriptionCancelText" value="{{__("You won't be able to revert this!")}}">
<!-- Select2 -->
<script src="{{ asset('assets/js/select2.min.js') }}"></script>

<script>
    "use strict";

    // select2 initial
    $('.select2').select2({
        dropdownParent: $("#addPackageModal")
    });

    var currencySymbol = "{{ getCurrencySymbol() }}";
    var currencyPlacement = "{{ getCurrencyPlacement() }}";

    toastr.options = {
        "closeButton": false,
        "debug": false,
        "newestOnTop": false,
        "progressBar": false,
        "positionClass": "toast-bottom-right",
        "preventDuplicates": false,
        "onclick": null,
        "showDuration": "300",
        "hideDuration": "1000",
        "timeOut": "5000",
        "extendedTimeOut": "1000",
        "showEasing": "swing",
        "hideEasing": "linear",
        "showMethod": "fadeIn",
        "hideMethod": "fadeOut"
    };
    @if (Session::has('success'))
        toastr.success("{{ session('success') }}");
    @endif
    @if (Session::has('error'))
        toastr.error("{{ session('error') }}");
    @endif
    @if (Session::has('info'))
        toastr.info("{{ session('info') }}");
    @endif
    @if (Session::has('warning'))
        toastr.warning("{{ session('warning') }}");
    @endif

    @if (@$errors->any())
        @foreach ($errors->all() as $error)
            toastr.error("{{ $error }}");
        @endforeach
    @endif

    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    });

    $(".topBannerClose").on('click', function() {
        $(this).parent().remove();
    });
</script>
