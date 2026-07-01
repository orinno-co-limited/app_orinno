var dt = $('#leaseReportDataTable').DataTable({
    processing: true,
    serverSide: true,
    responsive: true,
    ajax: $('#leaseReportRoute').val(),
    order: [1, 'desc'],
    ordering: false,
    autoWidth: false,
    lengthMenu: [
        [10, 25, 50, 100, -1],
        [10, 25, 50, 100, 'All'],
    ],
    drawCallback: function () {
        $(".dataTables_length select").addClass("form-select form-select-sm");
    },
    language: {
        'paginate': {
            'previous': '<span class="iconify" data-icon="icons8:angle-left"></span>',
            'next': '<span class="iconify" data-icon="icons8:angle-right"></span>'
        }
    },
    dom: '<"row"<"col-sm-4"l><"col-sm-4"B><"col-sm-4"f>>tr<"bottom"<"row"<"col-sm-6"i><"col-sm-6"p>>><"clear">',
    buttons: [{
        extend: 'excel',
        className: 'theme-btn theme-button1 default-hover-btn'
    },
    {
        extend: 'pdf',
        className: 'theme-btn theme-button1 default-hover-btn'
    },
    {
        extend: 'copy',
        className: 'theme-btn theme-button1 default-hover-btn'
    }
    ],
    columnDefs: [
        { className: "text-center", targets: [1, 2, 3, 4] },
        { className: "text-end", targets: [5] }
    ],
    columns: [{
        "data": 'DT_RowIndex',
        "name": 'DT_RowIndex',
        orderable: false,
        searchable: false,
    },
    {
        "data": "name",
        "name": "users.first_name"
    },
    {
        "data": "property",
        "name": "properties.name"
    },
    {
        "data": "unit",
        "name": "property_units.unit_name"
    },
    {
        "data": "start_date",
        "name": "tenants.lease_start_date"
    },
    {
        "data": "end_date",
        "name": "tenants.lease_end_date"
    }
    ]
});

