(function ($) {
    "use strict";
    $('#allDataTable').DataTable({
        processing: true,
        serverSide: true,
        pageLength: 25,
        responsive: true,
        ajax: $('#getAllTicketRoute').val(),
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
            "data": "ticket",
            "name": 'tickets.ticket_no'
        },
        {
            "data": "title",
            "name": 'tickets.title'
        },
        {
            "data": "details",
            "name": 'tickets.details'
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
