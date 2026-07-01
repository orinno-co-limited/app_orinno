$('#add').on('click', function () {
    var selector = $('#addModal');
    selector.find('.is-invalid').removeClass('is-invalid');
    selector.find('.error-message').remove();
    selector.find('form').trigger('reset');
    selector.modal('show')
})

$('#teamMemberDataTable').DataTable({
    processing: true,
    serverSide: true,
    pageLength: 25,
    responsive: true,
    ajax: $('#teamMemberIndexRoute').val(),
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
        { "data": 'DT_RowIndex', "name": 'DT_RowIndex', orderable: false, searchable: false },
        { "data": "name", "name": "name" },
        { "data": "email", "name": "email" },
        { "data": "contact_number", "name": "contact_number" },
        { "data": "status", "name": "status","searchable": false},
        { "data": "action", "name": "action","searchable": false},
    ]
});
