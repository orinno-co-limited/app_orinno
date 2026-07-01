(function ($) {
    "use strict";
    $('#allClosingTenantDataTable').DataTable({
        processing: true,
        serverSide: true,
        pageLength: 25,
        responsive: true,
        ajax: $('#route').val(),
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
        columns: [{
            "data": 'DT_RowIndex',
            "name": 'DT_RowIndex',
            orderable: false,
            searchable: false,
        },
        {
            "data": "name",
            "name": 'users.first_name'
        },
        {
            "data": "name",
            "visible": false,
            "name": 'users.last_name'
        },
        {
            "data": "property",
            "name": 'properties.name'
        },
        {
            "data": "unit",
            "name": "property_units.unit_name"
        },
        {
            "data": "general_rent"
        },
        {
            "data": "status"
        },
        {
            "data": "action"
        },
        ]
    });
})(jQuery)
