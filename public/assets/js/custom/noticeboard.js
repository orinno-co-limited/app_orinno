var stateSelector;

$(document).on('click', '#add', function () {
    var selector = $('#addNoticeModal');
    stateSelector = selector
    selector.find('.is-invalid').removeClass('is-invalid');
    selector.find('.error-message').remove();
    selector.modal('show')
    selector.find('form').trigger("reset");
    selector.find('.property_id').attr("disabled", false);
    selector.find('.unit_id').attr("disabled", false);
});

$(document).on('click', '.edit', function () {
    commonAjax('GET', $('#getInfoRoute').val(), getDataEditRes, getDataEditRes, { 'id': $(this).data('id') });
});

function getDataEditRes(response) {
    var selector = $('#editNoticeModal');
    stateSelector = selector
    selector.find('.is-invalid').removeClass('is-invalid');
    selector.find('.error-message').remove();
    selector.find('#id').val(response.data.id)
    selector.find('.title').val(response.data.title)
    selector.find('.details').text(response.data.details)
    selector.find('.start_date').val(response.data.start_date)
    selector.find('.end_date').val(response.data.end_date)
    selector.modal('show')
    if (response.data.property_all == 1) {
        selector.find('#checkNoticeBoardAllProperty').attr('checked', true);
        selector.find('.unit_id').attr("disabled", true);
        selector.find('.property_id').attr("disabled", true);
        selector.find('.unit_id').val("");
        selector.find('.property_id').val("");
    } else {
        selector.find('#checkNoticeBoardAllProperty').attr('checked', false);
        selector.find('.property_id').attr("disabled", false);
        selector.find('.property_id').val(response.data.property_id);

        if (response.data.unit_all == 1) {
            selector.find('#checkNoticeBoardAllUnit').attr('checked', true);
            selector.find('.unit_id').attr("disabled", true);
            selector.find('.unit_id').val("");
        } else {
            selector.find('#checkNoticeBoardAllUnit').attr('checked', false);
            selector.find('.unit_id').attr("disabled", false);

            var selected = selector.find('.property_id :selected');
            var units = selected.data("units");
            var html = '<option value="">--Select Unit--</option>';
            Object.entries(units).forEach((unit) => {
                if (unit[1].id == response.data.unit_id) {
                    html += '<option value="' + unit[1].id + '" selected>' + unit[1].unit_name + '</option>';
                } else {
                    html += '<option value="' + unit[1].id + '">' + unit[1].unit_name + '</option>';
                }
            });
            selector.find('#unitOption').html(html);
        }
    }
}

$(document).on('click', '.view', function () {
    commonAjax('GET', $('#getInfoRoute').val(), getDataViewRes, getDataViewRes, { 'id': $(this).data('id') });
});

function getDataViewRes(response) {

    if(response.data.image.includes('no-image.jpg')){
        $('.uploaded-documents-wrapper').hide();
    }else {
        $('.image').attr('href',response.data.image)
        $('.uploaded-documents-wrapper').show();
    }

    $('.viewtitle').html(response.data.title)
    $('.property').html(response.data.property_name)
    $('.unit').html(response.data.unit_name)
    $('.details').html(response.data.details)
    $('.start_date').html(response.data.start_date)
    $('.end_date').html(response.data.end_date)
}

$(document).on('change', '#checkNoticeBoardAllProperty', function () {
    if ($(this).is(':checked')) {
        $('#checkNoticeBoardAllUnit').attr("disabled", true);
        $('.unit_id').attr("disabled", true);
        $('.property_id').attr("disabled", true);
        $('.unit_id').val("");
        $('.property_id').val("");
    } else {
        $('.property_id').attr("disabled", false);
        if ($('#checkNoticeBoardAllUnit').is(':checked')) {
            $('#checkNoticeBoardAllUnit').attr("disabled", false);
            $('.unit_id').attr("disabled", true);
        } else {
            $('#checkNoticeBoardAllUnit').attr("disabled", false);
            $('.unit_id').attr("disabled", false);
        }
    }
});

$(document).on('change', '#checkNoticeBoardAllUnit', function () {
    if ($(this).is(':checked')) {
        $('.unit_id').attr("disabled", true);
        $('.unit_id').val("");
    } else {
        $('.unit_id').attr("disabled", false);
    }
});

$(document).on('change', '.property_id', function () {
    getUnitsByPropertyId();
});

function getUnitsByPropertyId() {
    var selected = stateSelector.find('.property_id :selected');
    var units = selected.data("units");
    var html = '<option value="">--Select Unit--</option>';
    Object.entries(units).forEach((unit) => {
        html += '<option value="' + unit[1].id + '">' + unit[1].unit_name + '</option>';
    });
    stateSelector.find('#unitOption').html(html);
}

(function ($) {
    "use strict";
    $('#allNoticeDataTable').DataTable({
        processing: true,
        serverSide: true,
        pageLength: 25,
        responsive: true,
        ajax: $('#route').val(),
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
            { "data": "title", },
            { "data": "property", "name": "properties.name" },
            { "data": "details", "name": "notice_boards.details" },
            { "data": "start_date", },
            { "data": "end_date", },
            { "data": "action", },
        ]
    });
})(jQuery)
