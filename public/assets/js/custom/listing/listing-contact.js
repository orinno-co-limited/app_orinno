
var dt;
$(document).on('click', '.reply', function () {
    commonAjax('GET', $('#listingContactInfoInfoRoute').val(), getDataViewRes, getDataViewRes, { 'id': $(this).data('id') });
});

function getDataViewRes(response) {
    var selector = $('#replyModal');
    selector.find('.id').val(response.id);
    selector.find('.name').text(response.name);
    selector.find('.email').text(response.email);
    selector.find('.phone').text(response.phone);
    selector.find('.profession').text(response.profession);
    selector.find('.listing-name').text(response.listing_name);
    selector.find('.details').text(response.details);
    if (response.reply != null) {
        selector.find('.reply-sec').removeClass('d-none');
        selector.find('.reply').text(response.reply);
    } else {
        selector.find('.reply-sec').addClass('d-none');
    }
    selector.modal('show')
}

dt = $('#contactDataTable').DataTable({
    processing: true,
    serverSide: true,
    pageLength: 25,
    responsive: true,
    ajax: $('#listingContactRoute').val(),
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
        "name": 'listing_contacts.name'
    },
    {
        "data": "email",
        "name": 'listing_contacts.email'
    },
    {
        "data": "phone",
        "name": 'listing_contacts.phone'
    },
    {
        "data": "listing_name",
        "name": 'listings.name'
    },
    {
        "data": "details",
        "name": 'listing_contacts.details'
    },
    {
        "data": "status",
    },
    {
        "data": "action",
    },
    ]
});
