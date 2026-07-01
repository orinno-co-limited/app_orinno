(function ($) {
    "use strict";

    console.log('lll');
    // $(".property-search").change(function () {
    //     console.log( this.value );
    // });

    $('#allOwnPropertiesDataTable').DataTable({
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
            { "data": "image" },
            { "data": "name", "name": 'name' },
            { "data": "address" },
            { "data": "rooms" },
            { "data": "unit" },
            { "data": "available" },
            { "data": "action" },
        ]
    });

    $('#allPropertiesDataTable').DataTable({
        processing: true,
        // searching: false,
        serverSide: true,
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
            { "data": "image" },
            { "data": "name", "name": 'name' },
            { "data": "address" },
            { "data": "rooms" },
            { "data": "unit" },
            { "data": "available" },
            { "data": "action" },
        ]
    });

    $('#allLeasePropertiesDataTable').DataTable({
        processing: true,
        serverSide: true,
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
            { "data": "image" },
            { "data": "name", "name": 'name' },
            { "data": "address" },
            { "data": "rooms" },
            { "data": "unit" },
            { "data": "available" },
            { "data": "action" },
        ]
    });

})(jQuery)
