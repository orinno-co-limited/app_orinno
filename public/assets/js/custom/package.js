$('#add').on('click', function () {
    var selector = $('#addModal');
    selector.find('.is-invalid').removeClass('is-invalid');
    selector.find('.error-message').remove();
    selector.find('form').trigger('reset');
    selector.modal('show')
    // showPriceInput(selector, 1)
})

$('.addOtherField').on('click', function () {
    var selector = $(this).closest('.modal')
    selector.find('.otherFields').append(otherFiledTemplate());
});

$(document).on('click', '.removeOtherField', function () {
    $(this).parent().remove();
});

function otherFiledTemplate(val = null) {
    return `<div class="input-group flex-nowrap mt-3">
                <input type="text" name="others[]" class="form-control" value="${val ?? ''}">
                <button type="button" class="bg-danger input-group-text text-white removeOtherField">Remove</button>
            </div>`;
}

$(document).on('click', '.edit', function () {
    commonAjax('GET', $('#packageInfoRoute').val(), getDataEditRes, getDataEditRes, { 'id': $(this).data('id') });
});

function getDataEditRes(response) {
    var selector = $('#editModal');
    selector.find('.is-invalid').removeClass('is-invalid');
    selector.find('.error-message').remove();
    selector.find('input[name=id]').val(response.data.id);
    selector.find('input[name=name]').val(response.data.name);
    selector.find('input[name=description]').val(response.data.description);
    selector.find('select[name=pricing_type]').val(response.data.type);
    selector.find('input[name=per_monthly_price]').val(response.data.per_monthly_price);
    selector.find('input[name=per_yearly_price]').val(response.data.per_yearly_price);
    showPriceInput(selector, response.data.type)
    selector.find('input[name=max_property]').val(response.data.max_property);
    selector.find('input[name=max_unit]').val(response.data.max_unit);
    selector.find('input[name=max_tenant]').val(response.data.max_tenant);
    // others
    var otherHtmlFields = '';
    var otherFields = JSON.parse(response.data.others);
    if (otherFields) {
        otherFields.forEach((val) => {
            otherHtmlFields += otherFiledTemplate(val)
        });
    }
    selector.find('.otherFields').html(otherHtmlFields);
    if (response.data.customer_limit == -1) {
        selector.find('input[name=customer_limit]').val(0);
        selector.find('select[name=customer_limit_type]').val(2);
    } else {
        selector.find('input[name=customer_limit]').val(response.data.customer_limit);
    }

    if (response.data.max_maintainer == -1) {
        selector.find('select[name=maintainer_limit_type]').val(2);
        selector.find('input[name=max_maintainer]').prop('disabled', true);
        selector.find('input[name=max_maintainer]').val(0);
    } else {
        selector.find('select[name=maintainer_limit_type]').val(1);
        selector.find('input[name=max_maintainer]').prop('disabled', false);
        selector.find('input[name=max_maintainer]').val(response.data.max_maintainer);
    }

    if (response.data.max_invoice == -1) {
        selector.find('select[name=invoice_limit_type]').val(2);
        selector.find('input[name=max_invoice]').prop('disabled', true);
        selector.find('input[name=max_invoice]').val(0);
    } else {
        selector.find('select[name=invoice_limit_type]').val(1);
        selector.find('input[name=max_invoice]').prop('disabled', false);
        selector.find('input[name=max_invoice]').val(response.data.max_invoice);
    }

    if (response.data.max_auto_invoice == -1) {
        selector.find('select[name=auto_invoice_limit_type]').val(2);
        selector.find('input[name=max_auto_invoice]').prop('disabled', true);
        selector.find('input[name=max_auto_invoice]').val(0);
    } else {
        selector.find('select[name=auto_invoice_limit_type]').val(1);
        selector.find('input[name=max_auto_invoice]').prop('disabled', false);
        selector.find('input[name=max_auto_invoice]').val(response.data.max_auto_invoice);
    }

    selector.find('select[name=ticket_support]').val(response.data.ticket_support);
    selector.find('select[name=notice_support]').val(response.data.notice_support);
    selector.find('select[name=status]').val(response.data.status);
    selector.find('select[name=is_trail]').val(response.data.is_trail);
    selector.find('select[name=is_default]').val(response.data.is_default);
    selector.find('input[name=monthly_price]').val(response.data.monthly_price);
    selector.find('input[name=yearly_price]').val(response.data.yearly_price);

    if (response.data.subscription_price.find(item => item.gateway === 'stripe')) {
        selector.find('select[name=stripe_subscription]').val(1);
    } else {
        selector.find('select[name=stripe_subscription]').val(0);
    }

    if (response.data.subscription_price.find(item => item.gateway === 'paypal')) {
        selector.find('select[name=paypal_subscription]').val(1);
    } else {
        selector.find('select[name=paypal_subscription]').val(0);
    }

    selector.modal('show');
}

$(document).on('change', '.pricing_type', function () {
    selector = $(this).closest('form');
    pricing_type = selector.find('select[name=pricing_type]').val();
    showPriceInput(selector, pricing_type)
});

function showPriceInput(selector, pricing_type) {
    if (pricing_type == 1) {
        selector.find('.property_type_price').removeClass('d-none');
        selector.find('.unit_type_price').addClass('d-none');
        selector.find('.tenant_type_price').addClass('d-none');
    } else if (pricing_type == 2) {
        selector.find('.property_type_price').addClass('d-none');
        selector.find('.unit_type_price').removeClass('d-none');
        selector.find('.tenant_type_price').addClass('d-none');
    } else if (pricing_type == 3) {
        selector.find('.property_type_price').addClass('d-none');
        selector.find('.unit_type_price').addClass('d-none');
        selector.find('.tenant_type_price').removeClass('d-none');
    }
}

$(document).on('change', 'select[name=maintainer_limit_type],select[name=invoice_limit_type],select[name=auto_invoice_limit_type]', function () {
    var selector = $(this).closest('div');
    if ($(this).val() == 1) {
        selector.find('input').prop('disabled', false);
    } else {
        selector.find('input').val(0);
        selector.find('input').prop('disabled', true);
    }
});

$('#allDataTable').DataTable({
    processing: true,
    serverSide: true,
    pageLength: 25,
    responsive: true,
    ajax: $('#packageIndexRoute').val(),
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
    dom: '<"row"<"col-sm-4"l><"col-sm-4"B><"col-sm-4"f>>tr<"bottom"<"row"<"col-sm-6"i><"col-sm-6"p>>><"clear">',
    buttons: [{
        extend: 'excel',
        className: 'theme-btn theme-button1 default-hover-btn'
    }],
    columns: [
        { "data": "name", "name": "name" },
        { "data": "monthly_price", "name": "monthly_price" },
        { "data": "yearly_price", "name": "yearly_price" },
        { "data": "max_property", "name": "max_property" },
        { "data": "max_unit", "name": "max_unit" },
        { "data": "status", },
        { "data": "trail", },
        { "data": "action", },
    ]
});
