
$('#add').on('click', function () {
    var selector = $('#smsSendModal');
    selector.find('.is-invalid').removeClass('is-invalid');
    selector.find('.error-message').remove();
    selector.modal('show');
});

$('.property_id').on('change', function () {
    var property_ids = $(this).val();
    commonAjax('GET', $('#getPropertyUnitsRoute').val(), getUnitByPropertyIdsRes, getUnitByPropertyIdsRes, { 'property_ids': property_ids });
});

function getUnitByPropertyIdsRes(response) {
    $('.unit_id').selectpicker('destroy');
    $('.unit_id').html('');
    var html = '';
    if (response.data.units.length != 0) {
        html = '<option value="all">All</option>';
        response.data.units.forEach(function (opt) {
            html += '<option value="' + opt.id + '">' + opt.unit_name + '</option>';
        });
    }
    $('.unit_id').html(html);
    $('.unit_id').selectpicker('render');
}

$('.target_audience').on('change', function () {
    var value = $(this).val();
    $('.by-property').addClass('d-none')
    $('.by-user-type').addClass('d-none')
    $('.message').removeClass('d-none')
    if (value == 1) {
        $('.by-property').removeClass('d-none')
    } else if (value == 2) {
        $('.by-user-type').removeClass('d-none')
    } else if (value == 3) {
        $('.audience-custom ').removeClass('d-none')
    }
});
$('.user_type').on('change', function () {
    var value = $(this).val();
    $('.user-type-tenant').addClass('d-none')
    $('.user-type-maintainer').addClass('d-none')
    if (value == 1) {
        $('.user-type-tenant').removeClass('d-none')
    } else if (value == 2) {
        $('.user-type-maintainer').removeClass('d-none')
    }
});

(function ($) {
    $('#allDataTable').DataTable({
        processing: true,
        serverSide: true,
        pageLength: 25,
        responsive: true,
        ajax: $('#route').val(),
        autoWidth: false,
        language: {
            'paginate': {
                'previous': '<span class="iconify" data-icon="icons8:angle-left"></span>',
                'next': '<span class="iconify" data-icon="icons8:angle-right"></span>'
            }
        },
        dom: '<"row"<"col-sm-4"l><"col-sm-4"B><"col-sm-4"f>>tr<"bottom"<"row"<"col-sm-6"i><"col-sm-6"p>>><"clear">',
        buttons: [],
        columns: [{
            "data": 'DT_RowIndex',
            "name": 'DT_RowIndex',
            orderable: false,
            searchable: false,
        },
        {
            "data": "date",
        },
        {
            "data": "message",
            "name": "sms_histories.message"
        },
        {
            "data": "number",
            "name": "sms_histories.phone_number"
        },
        {
            "data": "status",
        }
        ]
    });
})(jQuery)

