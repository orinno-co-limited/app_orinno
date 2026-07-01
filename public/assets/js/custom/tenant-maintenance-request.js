$(document).on('click', '#add', function () {
    var selector = $('#addModal');
    selector.find('.is-invalid').removeClass('is-invalid');
    selector.find('.error-message').remove();
    selector.modal('show');
    selector.find('form').trigger("reset");
});

$(document).on('click', '.edit', function () {
    commonAjax('GET', $('#getInfoRoute').val(), getDataEditRes, getDataEditRes, { 'id': $(this).data('id') });
});

function getDataEditRes(response) {
    var selector = $('#editModal');
    selector.find('.is-invalid').removeClass('is-invalid');
    selector.find('.error-message').remove();
    selector.find('.id').val(response.data.id);

    selector.find('.issue_id').val(response.data.issue_id);
    selector.find('.details').text(response.data.details);
    selector.modal('show');
}

(function ($) {
    "use strict";
    var oTable;
    $('#search_property').on('change', function () {
        oTable.search($(this).val()).draw();
    })

    oTable = $('#allMaintenanceRequestDataTable').DataTable({
        processing: true,
        serverSide: true,
        pageLength: 25,
        responsive: true,
        ajax: $('#maintenanceIndexRoute').val(),
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
            { "data": "request_id", 'name' : 'request_id'},
            { "data": "issue_name", "name": "maintenance_issues.name" },
            { "data": "details", },
            { "data": "status", },
            { "data": "action", "class": "text-end", },
        ]
    });
})(jQuery)
