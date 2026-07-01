// Get unit
$(document).on('change', '.property_id', function () {
    var property_id = $(this).val()
    var route = $('#route').val().split('?');
    var routePara = route[1].split('&');
    var newRoute = route[0] + '?property_id=' + property_id + '&' + routePara[1];
    $('#route').val(newRoute)
    var getPropertyUnitsRoute = $('#getPropertyUnitsRoute').val();
    dt.ajax.url(newRoute).load();
    commonAjax('GET', getPropertyUnitsRoute, getUnitsRes, getUnitsRes, { 'property_id': property_id });
});

function getUnitsRes(response) {
    if (response.data) {
        var unitOptionsHtml = response.data.map(function (opt) {
            return '<option value="' + opt.id + '">' + opt.unit_name + '</option>';
        }).join('');
        var unitsHtml = '<option value="0">--Select Unit--</option>' + unitOptionsHtml
        $('.unit_id').html(unitsHtml);
    } else {
        $('.unit_id').html('<option value="0">--Select Unit--</option>');
    }
}

$('.unit_id').on('change', function () {
    var unit_id = $(this).val();
    var route = $('#route').val().split('?');
    var routePara = route[1].split('&');
    var newRoute = route[0] + '?' + routePara[0] + "&unit_id=" + unit_id;
    $('#route').val(newRoute)
    dt.ajax.url(newRoute).load();
});

$(document).on('click', '.reject', function () {
    commonAjax('GET', $('#getInfoRoute').val(), getDataRejectRes, getDataRejectRes, { 'id': $(this).data('id') });
});

function getDataRejectRes(response) {
    var selected = $('#rejectModal');
    selected.modal('show')
    selected.find('.id').val(response.data.id)
    selected.find('.kyc_config_name').val(response.data.config_name)
    selected.find('.front-img').attr('src', response.data.front)
    selected.find('.back-img').attr('src', response.data.back)
    selected.find('.tenant_name').text(response.data.first_name + ' ' + response.data.last_name)
    selected.find('.property_name').text(response.data.property_name)
    selected.find('.unit_name').text(response.data.unit_name)
    selected.find('.reason').text(response.data.reason)

    if (response.data.is_both == 1) {
        selected.find('.isBoth').removeClass('d-none')
    } else {
        selected.find('.isBoth').addClass('d-none')
    }
}

$(document).on('click', '.view', function () {
    commonAjax('GET', $('#getInfoRoute').val(), getDataViewRes, getDataViewRes, { 'id': $(this).data('id') });
});

function getDataViewRes(response) {
    var selected = $('#viewModal');
    selected.modal('show')
    selected.find('.kyc_config_name').text(response.data.config_name)
    if (getExtension(response.data.front) == 'pdf') {
        selected.find('.front-img').attr('src', '/assets/images/pdf-file-img.png');
    } else {
        selected.find('.front-img').attr('src', response.data.front);
    }
    if (getExtension(response.data.back) == 'pdf') {
        selected.find('.back-img').attr('src', '/assets/images/pdf-file-img.png');
    } else {
        selected.find('.back-img').attr('src', response.data.back);
    }
    selected.find('.reason').text(response.data.reason)
    selected.find('.config_name').text(response.data.config_name)
    selected.find('.tenant_name').text(response.data.first_name + ' ' + response.data.last_name)
    selected.find('.property_name').text(response.data.property_name)
    selected.find('.unit_name').text(response.data.unit_name)

    if (response.data.is_both == 1) {
        selected.find('.isBoth').removeClass('d-none')
    } else {
        selected.find('.isBoth').addClass('d-none')
    }
    if (response.data.status == 3) {
        selected.find('.reasonDiv').removeClass('d-none')
    } else {
        selected.find('.reasonDiv').addClass('d-none')
    }
}

function getExtension(filename) {
    return filename.split('.').pop().toLowerCase();
}

$(document).on("click", ".accept", function () {
    let url = this.dataset.url;
    Swal.fire({
        title: 'Sure! You want to accept?',
        text: "You won't be able to revert this!",
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#3085d6',
        cancelButtonColor: '#d33',
        confirmButtonText: 'Yes, Accept It!'
    }).then((result) => {
        if (result.value) {
            commonAjax('GET', url, getShowMessage, getShowMessage);
        } else if (result.dismiss === "cancel") {
            Swal.fire(
                "Cancelled",
                "Your imaginary data is safe :)",
                "error"
            )
        }
    })
});
var dt;
(function ($) {
    "use strict";
    dt = $('#allDataTableDoc').DataTable({
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
        columns: [{
            "data": 'DT_RowIndex',
            "name": 'DT_RowIndex',
            orderable: false,
            searchable: false,
        },
        {
            "data": "config_name",
            "name": 'kyc_configs.name'
        },
        {
            "data": "tenant_name",
            "name": 'users.first_name'
        },
        {
            "data": "tenant_name",
            "visible": false,
            "name": 'users.last_name'
        },
        {
            "data": "property_name",
            "name": 'properties.name'
        },
        {
            "data": "unit_name",
            "name": 'property_units.unit_name'
        },
        {
            "data": "front",
            "name": 'front'
        },
        {
            "data": "back",
            "name": 'back'
        },
        {
            "data": "status",
        },
        {
            "data": "action",
        },
        ]
    });
})(jQuery)
