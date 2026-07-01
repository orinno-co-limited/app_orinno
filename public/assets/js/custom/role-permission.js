$('#add').on('click', function () {
    var selector = $('#addModal');
    selector.find('.is-invalid').removeClass('is-invalid');
    selector.find('.error-message').remove();
    selector.find('form').trigger('reset');
    selector.modal('show')
})


$(document).on('click', '.edit', function () {
    commonAjax('GET', $('#roleInfoRoute').val(), getDataEditRes, getDataEditRes, { 'id': $(this).data('id') });
});

function getDataEditRes(response) {
    var selector = $('#editModal');
    selector.find('.is-invalid').removeClass('is-invalid');
    selector.find('.error-message').remove();
    selector.find('input[name=id]').val(response.data.id);
    selector.find('input[name=name]').val(response.data.display_name);
    selector.find('select[name=status]').val(response.data.status);
    selector.modal('show');
}

$('#roleListDataTable').DataTable({
    processing: true,
    serverSide: true,
    pageLength: 25,
    responsive: true,
    ajax: $('#roleIndexRoute').val(),
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
        { "data": "display_name", "name": "display_name" },
        { "data": "status", "name": "status" ,"class": "text-end"},
        { "data": "action", "name": "action","searchable": false, "class": "text-end" }
    ]
});
