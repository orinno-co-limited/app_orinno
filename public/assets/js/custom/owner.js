$('#add').on('click', function () {
    var selector = $('#addModal');
    selector.find('.is-invalid').removeClass('is-invalid');
    selector.find('.error-message').remove();
    selector.find('form').trigger('reset');
    selector.modal('show')
    // showPriceInput(selector, 1)
})
$(document).on('click', '.edit', function () {
    commonAjax('GET', $('#ownerGetInfoRoute').val(), getDataEditRes, getDataEditRes, { 'id': $(this).data('id') });
});

function getDataEditRes(response) {
    var selector = $('#editModal');
    selector.find('.is-invalid').removeClass('is-invalid');
    selector.find('.error-message').remove();
    selector.find('input[name=id]').val(response.id);
    selector.find('input[name=first_name]').val(response.first_name);
    selector.find('input[name=last_name]').val(response.last_name);
    selector.find('input[name=contact_number]').val(response.contact_number);
    selector.find('input[name=email]').val(response.email);
    selector.find('select[name=status]').val(response.status);
    selector.modal('show');
}

let allOwnerDataTable;
allOwnerDataTable = $('#allOwnerDataTable').DataTable({
    processing: true,
    serverSide: true,
    pageLength: 25,
    responsive: true,
    ajax: $('#adminOwnerRoute').val(),
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
        { "data": "name", "name": "users.first_name" },
        { "data": "email", "name": "users.email" },
        { "data": "contact_number", "name": "users.contact_number" },
        { "data": "status", "name": "status" },
        { "data": "action", "name": "action" }
    ]
});
