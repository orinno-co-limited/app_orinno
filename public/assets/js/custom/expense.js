var thisStateSelector;
$(document).on('change', '.property_id', function () {
    thisStateSelector = $(this).closest('.modal');
    getUnitsByPropertyId();
});

function getUnitsByPropertyId() {
    var selected = thisStateSelector.find('.property_id :selected');
    var units = selected.data("units");
    var optionsHtml = units.map(function (opt) {
        return '<option value="' + opt.id + '">' + opt.unit_name + '</option>';
    }).join('');
    var html = '<option value="">--Select Option--</option>' + optionsHtml
    $(thisStateSelector).find('.unitOption').html(html);
}

function typeStoreDataRes(response) {
    var output = '';
    var type = 'error';
    $('.error-message').remove();
    $('.is-invalid').removeClass('is-invalid');
    if (response['status'] == true) {
        output = output + response['message'];
        type = 'success';
        toastr.success(response.message)
        var html = '<option value="">--Select Option--</option>';
        Object.entries(response.data.types).forEach((type) => {
            html += '<option value="' + type[1].id + '">' + type[1].name + '</option>';
        });
        $('#typesOption').html(html);
        $('#addTypeModal').modal('hide');
    } else {
        commonHandler(response)
    }
}

$('#addTypeModal').on('hidden.bs.modal', function () {
    $('#addExpensesModal').modal('show');
});

$('.addExpenses').on('click', function () {
    var selector = $('#addExpensesModal');
    selector.modal('show')
})

$(document).on('click', '.edit', function () {
    commonAjax('GET', $(this).data('detailsurl'), getDataEditRes, getDataEditRes, { 'id': $(this).data('id') });
});

function getDataEditRes(response) {
    var selector = $('#editExpensesModal');
    selector.find('.is-invalid').removeClass('is-invalid');
    selector.find('.error-message').remove();
    selector.find('.id').val(response.data.id)
    selector.find('.name').val(response.data.name)
    selector.find('.property_id').val(response.data.property_id)
    selector.find('.expense_type_id').val(response.data.expense_type_id)
    selector.find('.description').text(response.data.description)
    selector.find('.total_amount').val(response.data.total_amount)

    var html = '<option value="">--Select Option--</option>';
    Object.entries(response.data.units).forEach((unit) => {
        if (unit[1].id == response.data.property_unit_id) {
            html += '<option value="' + unit[1].id + '" selected>' + unit[1].unit_name + '</option>';
        } else {
            html += '<option value="' + unit[1].id + '">' + unit[1].unit_name + '</option>';
        }
    });
    selector.find('.unitOption').html(html);

    if (response.data.responsibilities.tenant == 1) {
        selector.find("#responseTenantEdit").attr("checked", true);
    } else {
        selector.find("#responseTenantEdit").attr("checked", false);
    }

    if (response.data.responsibilities.owner == 2) {
        selector.find("#responseOwnerEdit").attr("checked", true);
    } else {
        selector.find("#responseOwnerEdit").attr("checked", false);
    }

    selector.modal('show')
}

$('#expensesDatatable').DataTable({
    processing: true,
    serverSide: true,
    pageLength: 25,
    responsive: true,
    ajax: $('#expenseIndexRoute').val(),
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
        { "data": "name" },
        { "data": "property" },
        { "data": "expense_type_name" },
        { "data": "responsibility" },
        { "data": "total_amount" },
        { "data": "action" },
    ]
});
