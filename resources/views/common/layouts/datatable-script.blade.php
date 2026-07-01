<script>
    function getLanguage() {
        return {
            "sEmptyTable": "{{ __('No data available in table') }}",
            "sInfo": "{{ __('Showing _START_ To _END_ Of _TOTAL_ Entries') }}",
            "sInfoEmpty": "{{ __('Showing 0 to 0 of 0 entries') }}",
            "sInfoFiltered": "{{ __('(filtered from _MAX_ total entries)') }}",
            "sInfoPostFix": "",
            "sInfoThousands": ",",
            "sLengthMenu": "{{ __('Show _MENU_ entries') }}",
            "sLoadingRecords": "{{ __('Loading...') }}",
            "sProcessing": "{{ __('Processing...') }}",
            "sSearch": "{{ __('Search:') }}",
            "sZeroRecords": "{{ __('No matching records found') }}",
            "oPaginate": {
                "sFirst": "{{ __('First') }}",
                "sLast": "{{ __('Last') }}",
                "sNext": "{{ __('Next') }}",
                "sPrevious": "{{ __('Previous') }}"
            },
            "oAria": {
                "sSortAscending": ": {{ __('activate to sort column ascending') }}",
                "sSortDescending": ": {{ __('activate to sort column descending') }}"
            }
        };

    }
</script>
<!-- Required datatable js -->
<script src="{{ asset('/') }}assets/libs/datatables.net/js/jquery.dataTables.min.js"></script>
<script src="{{ asset('/') }}assets/libs/datatables.net-bs4/js/dataTables.bootstrap4.min.js"></script>
<!-- Buttons examples -->
<script src="{{ asset('/') }}assets/libs/datatables.net-buttons/js/dataTables.buttons.min.js"></script>
<script src="{{ asset('/') }}assets/libs/datatables.net-buttons-bs4/js/buttons.bootstrap4.min.js"></script>

<script src="{{ asset('/') }}assets/libs/datatables.net-buttons/js/buttons.html5.min.js"></script>
<script src="{{ asset('/') }}assets/libs/datatables.net-buttons/js/buttons.print.min.js"></script>
<script src="{{ asset('/') }}assets/libs/datatables.net-buttons/js/buttons.colVis.min.js"></script>

<script src="{{ asset('/') }}assets/libs/datatables.net-keytable/js/dataTables.keyTable.min.js"></script>
<script src="{{ asset('/') }}assets/libs/datatables.net-select/js/dataTables.select.min.js"></script>

<!-- Responsive examples -->
<script src="{{ asset('/') }}assets/libs/datatables.net-responsive/js/dataTables.responsive.min.js"></script>
<script src="{{ asset('/') }}assets/libs/datatables.net-responsive-bs4/js/responsive.bootstrap4.min.js"></script>

<script src="{{ asset('/') }}assets/libs/datatable/jszip/jszip.min.js"></script>
<script src="{{ asset('/') }}assets/libs/datatable/pdfmake/pdfmake.min.js"></script>
<script src="{{ asset('/') }}assets/libs/datatable/pdfmake/vfs_fonts.js"></script>
<script>
    const linkFont = "{{ selectedLanguage()->font }}";
    if (linkFont) {
        pdfMake.fonts = {
            Roboto: {
                normal: linkFont,
                bold: linkFont,
                italics: linkFont,
                bolditalics: linkFont
            }
        };
    }
</script>
