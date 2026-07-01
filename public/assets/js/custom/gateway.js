var getCurrencySymbol = $('#getCurrencySymbol').val()
var allCurrency = JSON.parse($('#allCurrency').val())
var supportedCurrency = JSON.parse($('#supportedCurrency').val());

$(document).on('click', '.edit', function (e) {
    commonAjax('GET', $('#getInfoRoute').val(), getDataEditRes, getDataEditRes, { 'id': $(this).data('id') });
});

function getDataEditRes(response) {
    const selector = $('#editModal');
    selector.find('.gateway-input').removeClass('d-none');
    selector.modal('show')
    selector.find('.is-invalid').removeClass('is-invalid');
    selector.find('.error-message').remove();
    $('#id').val(response.data.gateway.id)
    selector.find('.image').attr('src', response.data.image)
    selector.find('.title').val(response.data.gateway.title)
    selector.find('.slug').val(response.data.gateway.slug)
    selector.find('select[name=status]').val(response.data.gateway.status)
    selector.find('select[name=mode]').val(response.data.gateway.mode)
    selector.find('input[name=key]').val(response.data.gateway.key)
    var gatewaySettings = JSON.parse($('#gatewaySettings').val());
    let currentGateway = gatewaySettings[response.data.gateway.slug];

    if (typeof currentGateway == 'undefined') {
        currentGateway = [];
    }
    else {
        selector.find('.gateway-input').addClass('d-none');
    }

    currentGateway.forEach(option => {
        if (option.name == 'url' && option.is_show == 1) {
            selector.find('input[name=url]').parent().find('.label-text-title').text(option.label);
            $('#gateway-url').removeClass('d-none');
        }
        else if (option.name == 'key' && option.is_show == 1) {
            selector.find('input[name=key]').parent().find('.label-text-title').text(option.label);
            $('#gateway-key').removeClass('d-none');
        }
        else if (option.name == 'secret' && option.is_show == 1) {
            selector.find('input[name=secret]').parent().find('.label-text-title').text(option.label);
            $('#gateway-secret').removeClass('d-none');
        }
    });


    selector.find('input[name=secret]').val(response.data.gateway.secret)
    selector.find('input[name=url]').val(response.data.gateway.url)

    if (response.data.gateway.slug == 'bank') {
        selector.find('.mode-div').hide();
        selector.find('.url-div').hide();
        selector.find('.key-secret-div').hide();
        selector.find('.bank-div').show();
        var banks = response.data.banks;
        var bankHtml = '';
        if (banks.length > 0) {
            Object.entries(banks).map(function (bank) {
                var isSelected = '';
                if (bank[1].status == 1) { isSelected = 'selected'; } else { isSelected = ''; }

                bankHtml += `<div class="multi-bank bg-white radius-4 theme-border p-20 pb-0 mb-25">
                                <div class="row">
                                    <div class="col-6 mb-20">
                                        <input type="hidden" name="bank[id][]" value="${bank[1].id}">
                                        <label for="name" class="label-text-title color-heading font-medium mb-2">Bank Name</label>
                                        <input type="text" name="bank[name][]" class="form-control bank-name" id="name" placeholder="Bank Name" value="${bank[1].name}">
                                    </div>
                                    <div class="col-6 mb-20">
                                        <label for="name" class="label-text-title color-heading font-medium mb-2">Status</label>
                                        <select name="bank[status][]" class="form-control bank-status" id="status">
                                            <option value="1" ${bank[1].status == 1 ? 'selected' : ''}>Active</option>
                                            <option value="0" ${bank[1].status == 0 ? 'selected' : ''}>Deactive</option>
                                        </select>
                                    </div>
                                    <div class="col-12 mb-20">
                                        <label for="name" class="label-text-title color-heading font-medium mb-2">Bank Details</label>
                                        <textarea name="bank[details][]" id="bank_details" class="form-control">${bank[1].details}</textarea>
                                    </div>
                                    <div class="row mb-20">
                                        <div class="col-12 text-end"><button type="button" class="red-color remove-bank" title="Remove">Remove</button></div>
                                    </div>
                                </div>
                            </div>`

            });
        } else {
            bankHtml += `<div class="multi-bank bg-white radius-4 theme-border p-20 pb-0 mb-25">
            <div class="row">
                <div class="col-6 mb-20">
                    <input type="hidden" name="bank[id][]" value="">
                    <label for="name" class="label-text-title color-heading font-medium mb-2">Bank Name</label>
                    <input type="text" name="bank[name][]" class="form-control bank-name" id="name" placeholder="Bank Name" value="">
                </div>
                <div class="col-6 mb-20">
                    <label for="name" class="label-text-title color-heading font-medium mb-2">Status</label>
                    <select name="bank[status][]" class="form-control bank-status" id="status">
                        <option value="1">Active</option>
                        <option value="0">Deactive</option>
                    </select>
                </div>
                <div class="col-12 mb-20">
                    <label for="name" class="label-text-title color-heading font-medium mb-2">Bank Details</label>
                    <textarea name="bank[details][]" id="bank_details" class="form-control"></textarea>
                </div>
            </div>
        </div>`
        }

        $('.bank-div-append').html(bankHtml);
    } else {
        if (response.data.gateway.slug == 'cash') {
            selector.find('.mode-div').hide();
            selector.find('.url-div').hide();
            selector.find('.key-secret-div').hide();
            selector.find('.bank-div').hide();
        } else {
            selector.find('.mode-div').show();
            selector.find('.url-div').show();
            selector.find('.key-secret-div').show();
            selector.find('.bank-div').hide();
        }
    }
    var html = '';
    response.data.currencies.map(function (data) {
        html += '<div class="input-group mb-3 currency-conversation-rate">' +
            '<div class="d-flex"><select name="currency[]" class="form-control currency" required>';
        Object.entries(allCurrency).forEach((currency) => {
            if (currency[0] == data.currency) {
                html += '<option value="' + currency[0] + '" selected>' + currency[1] + '</option>';
            } else {
                html += '<option value="' + currency[0] + '">' + currency[1] + '</option>';
            }
        });
        html += '</select>' +
            '<span class="input-group-text">1  ' + getCurrencySymbol + ' = </span>' +
            '<input type="number" step="any" min="0" name="conversion_rate[]" value="' + data.conversion_rate + '" class="form-control" required>' +
            '<input type="hidden" step="any" min="0" name="currency_id[]" value="' + data.id + '" class="form-control" required>' +
            '<span class="input-group-text append_currency">' + data.currency + '</span>' +
            '<button type="button" class="removedItem font-24 ms-3 text-danger mr-5" title="Remove"><i class="ri-delete-bin-6-line"></i></button>' +
            '</div></div>';
    });
    $('#currencyConversionRateSection').html(html);
}

$('.add-currency').on('click', function (e) {
    var html = '';
    html += '<div class="input-group mb-3 currency-conversation-rate">' +
        '<div class="d-flex"><select name="currency[]" class="form-control currency" required>';
    Object.entries(allCurrency).forEach((currency) => {
        html += '<option value="' + currency[0] + '">' + currency[1] + '</option>';
    });
    html += '</select>' +
        '<span class="input-group-text">1  ' + getCurrencySymbol + ' = </span>' +
        '<input type="number" step="any" min="0" name="conversion_rate[]" value="" class="form-control" required>' +
        '<input type="hidden" step="any" min="0" name="currency_id[]" value="" class="form-control" required>' +
        '<span class="input-group-text append_currency"></span>' +
        '<button type="button" class="removedItem font-24 ms-3 text-danger mr-5" title="Remove"><i class="ri-delete-bin-6-line"></i></button>' +
        '</div></div>';
    $('#currencyConversionRateSection').append(html);
    $('.currency').trigger("change");
})

$(document).on('click', '.removedItem', function () {
    $(this).closest('.currency-conversation-rate').remove();
});

$(document).on('change', '.currency', function () {
    let selectedCurrency = $(this).val();

    $(this).closest('.currency-conversation-rate').find('.append_currency').text(selectedCurrency);

    let gatewaySlug = $('#editModal').find('.slug').val();

    if (gatewaySlug !== 'bank' && gatewaySlug !== 'cash') {
        if (supportedCurrency[gatewaySlug] && supportedCurrency[gatewaySlug].includes(selectedCurrency)) {
            $(this).closest('.currency-conversation-rate').find('.currency-warning').remove();
        } else {
            let warningNote = '<div class="fs-14 currency-warning text-danger pt-2">Currency not supported, please check and add carefully.</div>';
            if ($(this).closest('.currency-conversation-rate').find('.currency-warning').length === 0) {
                $(this).closest('.currency-conversation-rate').append(warningNote);
            }
        }
    } else {
        $(this).closest('.currency-conversation-rate').find('.currency-warning').remove();
    }
});

// Bank
$('.add-bank').on('click', function () {
    $('.bank-div-append').append(addBank());
});

$(document).on('click', '.remove-bank', function () {
    $(this).closest('.multi-bank').remove()
});

function addBank() {
    return `<div class="multi-bank bg-white radius-4 theme-border p-20 pb-0 mb-25">
                <div class="row">
                    <div class="col-6 mb-20">
                        <input type="hidden" name="bank[id][]" value="">
                        <label for="name" class="label-text-title color-heading font-medium mb-2">Bank Name</label>
                        <input type="text" name="bank[name][]" class="form-control bank-name" id="name" placeholder="Bank Name" value="">
                    </div>
                    <div class="col-6 mb-20">
                        <label for="name" class="label-text-title color-heading font-medium mb-2">Status</label>
                        <select name="bank[status][]" class="form-control bank-status" id="status">
                            <option value="1">Active</option>
                            <option value="0">Deactivate</option>
                        </select>
                    </div>
                    <div class="col-12 mb-20">
                        <label for="name" class="label-text-title color-heading font-medium mb-2">Bank Details</label>
                        <textarea name="bank[details][]" id="bank_details" class="form-control"></textarea>
                    </div>
                    <div class="row mb-20">
                        <div class="col-12 text-end"><button type="button" class="red-color remove-bank" title="Remove">Remove</button></div>
                    </div>
                </div>
            </div>`;
}

$(document).ready(function () {
    $("#gatewayDataTable").DataTable({
        language: {
            'paginate': {
                'previous': '<span class="iconify" data-icon="icons8:angle-left"></span>',
                'next': '<span class="iconify" data-icon="icons8:angle-right"></span>'
            }
        },
        pageLength: 10,
        responsive: true,
        order: [1, 'desc'],
        ordering: false,
        autoWidth: false,
        drawCallback: function () {
            $(".dataTables_length select").addClass("form-select form-select-sm");
        },
        // dom: '<"tableTop"<"row align-items-center"<"col-xl-6 col-lg-12 col-md-6"<"tableSearch float-start"f>><"col-xl-6 col-lg-12 col-md-6"<"tableLengthInput float-end"l>>>><"clear">',
    });
});
