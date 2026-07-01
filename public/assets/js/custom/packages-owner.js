$('#addPackage').on('click', function () {
    var selector = $('#addPackageModal');
    selector.find('.is-invalid').removeClass('is-invalid');
    selector.find('.error-message').remove();
    selector.find('form').trigger('reset');
    selector.modal('show')
})

$('#allOwnerPackageDataTable').DataTable({
    processing: true,
    serverSide: true,
    pageLength: 25,
    responsive: true,
    ajax: $('#packagesOwnerRoute').val(),
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
        { "data": "user_name", "name": "users.first_name" },
        { "data": "email", "name": "users.email" },
        { "data": "package_name", "name": "packages.name" },
        { "data": "gatewaysName", "name": "gateways.title" },
        { "data": "start_date", "name": "owner_packages.start_date" },
        { "data": "end_date", "name": "owner_packages.end_date" },
        { "data": "payment_status", "name": "subscription_orders.payment_status" },
        { "data": "status", "name": "owner_packages.status" },
        { "data": "action", "name": "action" }
    ]
});

$(document).on('click', '.edit', function () {
    commonAjax('GET', $('#ownerPackagesRoute').val(), getDataEditRes, getDataEditRes, { 'id': $(this).data('id') });
});

function getDataEditRes(response) {

    var selector = $('#editModal');
    selector.find('.is-invalid').removeClass('is-invalid');
    selector.find('.error-message').remove();
    selector.find('input[name=id]').val(response.id);
    selector.find('select[name=status]').val(response.status);
    selector.modal('show');
}

