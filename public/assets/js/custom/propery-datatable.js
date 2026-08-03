(function ($) {
    "use strict";

    var sharedConfig = {
        processing: true,
        searching: true,
        serverSide: false,
        pageLength: 25,
        responsive: true,
        ajax: $('#getAllPropertyRoute').val(),
        order: [1, 'desc'],
        ordering: false,
        autoWidth: false,
        drawCallback: function () {
            $(".dataTables_length select").addClass("form-select form-select-sm");
        },
        language: {
            'paginate': {
                'previous': '<span class="iconify" data-icon="icons8:angle-left"></span>',
                'next': '<span class="iconify" data-icon="icons8:angle-right"></span>'
            }
        },
        columns: [
            { "data": 'DT_RowIndex', "name": 'DT_RowIndex', orderable: false, searchable: false, },
            { "data": "property", "name": 'name' },
            { "data": "price" },
            { "data": "type_filter", visible: false },
            { "data": "status_filter", visible: false },
            { "data": "district_filter", visible: false },
            { "data": "action" },
        ]
    };

    // Only one of these three IDs exists on any given page. Whichever one
    // does gets exposed as window.__propertyDataTable so the view-toggle
    // and filter scripts can reference the live API directly instead of
    // re-querying/re-invoking .DataTable() on the DOM element (which throws
    // once a table is already initialized).
    ['#allOwnPropertiesDataTable', '#allPropertiesDataTable', '#allLeasePropertiesDataTable'].forEach(function (selector) {
        var $table = $(selector);
        if ($table.length) {
            window.__propertyDataTable = $table.DataTable(sharedConfig);
        }
    });

})(jQuery)
