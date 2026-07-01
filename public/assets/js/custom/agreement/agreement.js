$('.sendAgreement').on('click', function () {
    var selector = $('#sendAgreementModal');
    selector.find('.is-invalid').removeClass('is-invalid');
    selector.find('.error-message').remove();
    selector.modal('show')
})

$(document).on('change', '.property_id', function () {
    commonAjax('GET', $('#getPropertyUnitsRoute').val(), getPropertyUnitsRes, getPropertyUnitsRes, { "property_id": $(this).val() });
});

function getPropertyUnitsRes(response) {
    var selector = $('#sendAgreementModal');
    var html = '<option value="">--Select Unit--</option>';
    response.data.forEach(function (unit) {
        if (unit.user_id != null) {
            html += '<option value="' + unit.user_id + '">' + unit.unit_name + ' (' + unit.first_name + unit.last_name + ') ' + unit.email + '</option>';
        } else {
            html += '<option value="' + unit.user_id + '">' + unit.unit_name + '</option>';
        }
    });
    selector.find('.propertyUnitSelectOption').html(html);
}

$('#agreementDataTable').DataTable({
    processing: true,
    serverSide: true,
    pageLength: 25,
    responsive: true,
    ajax: $('#agreementIndexRoute').val(),
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
        { "data": "name", },
        { "data": "date", },
        { "data": "status", },
        { "data": "action", },
    ]
});
