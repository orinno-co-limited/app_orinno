let landlordTenantDataTable;
landlordTenantDataTable = $('#landlordTenantDataTable').DataTable({
    processing: true,
    serverSide: true,
    pageLength: 25,
    responsive: true,
    ajax: $('#landlordTenantRoute').val(),
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
        { "data": "name", "name": "users.first_name" },
        { "data": "email", "name": "users.email" },
        { "data": "contact_number", "name": "users.contact_number" },
        { "data": "property_count", searchable: false },
        { "data": "tenant_count", searchable: false },
        { "data": "status", searchable: false }
    ]
});

let allPlatformTenantDataTable;
allPlatformTenantDataTable = $('#allPlatformTenantDataTable').DataTable({
    processing: true,
    serverSide: true,
    pageLength: 25,
    responsive: true,
    ajax: $('#allPlatformTenantRoute').val(),
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
        { "data": "name", "name": "users.first_name" },
        { "data": "email", "name": "users.email" },
        { "data": "contact_number", "name": "users.contact_number" },
        { "data": "landlord", searchable: false },
        { "data": "property", searchable: false },
        { "data": "unit", searchable: false },
        { "data": "status", searchable: false }
    ]
});
