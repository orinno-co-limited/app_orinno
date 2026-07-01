$(document).on('click', '.reply', function () {
    commonAjax('GET', $('#messageInfoRoute').val(), getDataViewRes, getDataViewRes, { 'id': $(this).data('id') });
});

function getDataViewRes(response) {
    var selector = $('#replyModal');
    selector.find('.id').val(response.id);
    selector.find('.name').text(response.first_name + ' ' + response.last_name);
    selector.find('.email').text(response.email);
    selector.find('.phone').text(response.phone);
    selector.find('.message').text(response.message);
    if (response.reply != null) {
        selector.find('.reply-sec').removeClass('d-none');
        selector.find('.reply').text(response.reply);
    } else {
        selector.find('.reply-sec').addClass('d-none');
    }
    selector.modal('show')
}

$('#messageDataTable').DataTable({
    processing: true,
    serverSide: true,
    pageLength: 25,
    responsive: true,
    ajax: $('#messageIndexRoute').val(),
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
        "name": "messages.first_name"
    },
    {
        "data": "email",
        "name": "email"
    },
    {
        "data": "phone",
        "name": "phone"
    },
    {
        "data": "status",
    },
    {
        "data": "action",
    },
    ]
});
