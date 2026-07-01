
$('#add').on('click', function () {
    var selector = $('#informationModal');
    selector.find('.is-invalid').removeClass('is-invalid');
    selector.find('.error-message').remove();
    selector.modal('show');
});

$(document).on('click', '.edit', function () {
    commonAjax('GET', $('#getInfoRoute').val(), getDataEditRes, getDataEditRes, { 'id': $(this).data('id') });
});

function getDataEditRes(response) {
    var selector = $('#editInformationModal');
    selector.find('.is-invalid').removeClass('is-invalid');
    selector.find('.error-message').remove();
    selector.modal('show')
    selector.find('#id').val(response.data.id)
    selector.find('.property_id').val(response.data.property_id)
    selector.find('.name').val(response.data.name)
    selector.find('.distance').val(response.data.distance)
    selector.find('.contact_number').val(response.data.contact_number)
    selector.find('.additional_information').val(response.data.additional_information)
}

$(document).on('click', '.view', function () {
    commonAjax('GET', $('#getInfoRoute').val(), getDataViewRes, getDataViewRes, { 'id': $(this).data('id') });
});

function getDataViewRes(response) {
    var selector = $('#viewInformationModal');
    selector.modal('show')
    selector.find('.image').attr('src', response.data.image)
    selector.find('.name').html(response.data.name)
    selector.find('.property').html(response.data.property_name)
    selector.find('.distance').html(response.data.distance)
    selector.find('.contact_number').html(response.data.contact_number)
    selector.find('.additional_information').html(response.data.additional_information)
}

(function ($) {
    "use strict";
    var oTable
    $('#search_property').on('change', function () {
        oTable.search($(this).val()).draw();
    })

    oTable = $('#allInfoDataTable').DataTable({
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
        buttons: [{
            extend: 'excel',
            className: 'theme-btn theme-button1 default-hover-btn'
        },
        {
            extend: 'pdf',
            className: 'theme-btn theme-button1 default-hover-btn'
        }
        ],
        columns: [
            { "data": 'DT_RowIndex', "name": 'DT_RowIndex', orderable: false, searchable: false, },
            { "data": "image", },
            { "data": "name", },
            { "data": "property", "name": "properties.name" },
            { "data": "distance", },
            { "data": "contact_number", },
            { "data": "action", },
        ]
    });
})(jQuery)
