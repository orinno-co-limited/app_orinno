(function ($) {
    "use strict"
    var stateSelector;
    var invoiceTypes = JSON.parse($('.invoiceTypes').val());
    var typesHtml = '';
    Object.entries(invoiceTypes).forEach((type) => {
        typesHtml += '<option value="' + type[1].id + '">' + type[1].name + '</option>';
    });



    $('#add').on('click', function () {
        var selector = $('#addModal');
        selector.find('.is-invalid').removeClass('is-invalid');
        selector.find('.error-message').remove();
        selector.modal('show')
        selector.find('form').trigger('reset');
    });

    $(document).on("click", ".add-field", function () {
        $(this).closest('form').find('.multi-fields').append(
            `<div class="multi-field border-bottom pb-25 mb-25">
                <input type="hidden" name="invoiceItem[id][]" class="" value="">
                <!-- Modal Inner Form Box Start -->
                <div class="modal-inner-form-box bg-off-white theme-border radius-4 p-20 mb-20">
                    <div class="row">
                        <div class="col-md-6 mb-25">
                            <label class="label-text-title color-heading font-medium mb-2">Invoice Type</label>
                            <select class="form-select flex-shrink-0 invoiceItem-invoice_type_id" name="invoiceItem[invoice_type_id][]">
                                <option value="">--Select Type--</option>
                               ${typesHtml}
                            </select>
                        </div>
                        <div class="col-md-6 mb-25">
                            <label class="label-text-title color-heading font-medium mb-2">Amount</label>
                            <input type="text" name="invoiceItem[amount][]" class="form-control invoiceItem-amount" placeholder="Amount">
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-12">
                            <label class="label-text-title color-heading font-medium mb-2">Description</label>
                            <textarea class="form-control invoiceItem-description" name="invoiceItem[description][]" placeholder="Description"></textarea>
                        </div>
                    </div>
                </div>
                <!-- Modal Inner Form Box End -->
                <button type="button" class="remove-field red-color">Remove</button>
            </div>`
        )
    });

    $(document).on("click", ".remove-field", function () {
        $(this).parent(".multi-field").remove();
    });

    $('.recurring_type').on('change', function () {
        var selector = $(this);
        if (selector.val() == 3) {
            selector.closest('.modal').find('.recurring_day').removeClass('d-none');
        } else {
            selector.closest('.modal').find('.recurring_day').addClass('d-none');
        }
    });

    $(document).on("click", ".edit", function () {
        stateSelector = $('#editInvoiceModal');
        let detailsUrl = $(this).data('detailsurl');
        commonAjax('GET', detailsUrl, getDataEditRes, getDataEditRes);
    })

    function getDataEditRes(response) {
        var selector = $('#editInvoiceModal');
        selector.find('.is-invalid').removeClass('is-invalid');
        selector.find('.error-message').remove();
        selector.modal('show')
        selector.find('input[name=id]').val(response.data.invoice.id)
        selector.find('input[name=invoice_prefix]').val(response.data.invoice.invoice_prefix)
        selector.find('select[name=property_id]').val(response.data.invoice.property_id)
        getPropertyUnits(response.data.invoice.property_id)
        setTimeout(() => {
            selector.find('select[name=property_unit_id]').val(response.data.invoice.property_unit_id)
        }, 2000);
        selector.find('select[name=recurring_type]').val(response.data.invoice.recurring_type)
        selector.find('input[name=cycle_day]').val(response.data.invoice.cycle_day)
        selector.find('input[name=due_day_after]').val(response.data.invoice.due_day_after)
        if (response.data.invoice.recurring_type == 3) {
            selector.closest('.modal').find('.recurring_day').removeClass('d-none');
        } else {
            selector.closest('.modal').find('.recurring_day').addClass('d-none');
        }
        selector.find('select[name=status]').val(response.data.invoice.status)

        var html = '';
        Object.entries(response.data.items).forEach((item) => {
            var typesHtml = '';
            Object.entries(invoiceTypes).forEach((type) => {
                if (type[1].id == item[1].invoice_type_id) {
                    typesHtml += '<option value="' + type[1].id + '" selected>' + type[1].name + '</option>';
                } else {
                    typesHtml += '<option value="' + type[1].id + '">' + type[1].name + '</option>';
                }
            });
            html += `<div class="multi-field border-bottom pb-25 mb-25">
                        <input type="hidden" name="invoiceItem[id][]" class="" value="${item[1].id}">
                        <!-- Modal Inner Form Box Start -->
                        <div class="modal-inner-form-box bg-off-white theme-border radius-4 p-20 mb-20">
                            <div class="row">
                                <div class="col-md-6 mb-25">
                                    <label class="label-text-title color-heading font-medium mb-2">Invoice Type</label>
                                    <select class="form-select flex-shrink-0 invoiceItem-invoice_type_id" name="invoiceItem[invoice_type_id][]">
                                        <option value="">--Select Type--</option>
                                    ${typesHtml}
                                    </select>
                                </div>
                                <div class="col-md-6 mb-25">
                                    <label class="label-text-title color-heading font-medium mb-2">Amount</label>
                                    <input type="text" name="invoiceItem[amount][]" class="form-control invoiceItem-amount" placeholder="Amount" value="${item[1].amount}">
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-12">
                                    <label class="label-text-title color-heading font-medium mb-2">Description</label>
                                    <textarea class="form-control invoiceItem-description" name="invoiceItem[description][]" placeholder="Description">${item[1].description}</textarea>
                                </div>
                            </div>
                        </div>
                        <!-- Modal Inner Form Box End -->
                        <button type="button" class="remove-field red-color">Remove</button>
                    </div>`
        });
        selector.find('.multi-fields').html(html);
    }

    $(document).on('click', '.view', function () {
        let detailsUrl = $(this).data('detailsurl');
        commonAjax('GET', detailsUrl, getDetailsViewRes, getDetailsViewRes);
    });

    function getDetailsViewRes(response) {
        var selector = $('#invoicePreviewModal');
        selector.modal('show')
        selector.find('.invoiceNo').text(response.data.invoice.invoice_no)
        var status = 'Deactivate';
        if (response.data.invoice.status == '1') {
            status = "Active"
        }
        selector.find('.invoiceStatus').html(status)

        selector.find('.tenantName').html(response.data.tenant.first_name + ' ' + response.data.tenant.last_name)
        selector.find('.tenantEmail').html(response.data.tenant.email)
        selector.find('.propertyName').html(response.data.tenant.property_name)
        selector.find('.unitName').html(response.data.tenant.unit_name)
        var recurring = '';
        if (response.data.invoice.recurring_type == 1) {
            recurring = 'Monthly';
        } else if (response.data.invoice.recurring_type == 2) {
            recurring = 'Yearly';
        } else if (response.data.invoice.recurring_type == 3) {
            recurring = 'After ' + response.data.invoice.cycle_day + ' Days';
        }
        selector.find('.recurring').html(recurring)

        var html = '';
        Object.entries(response.data.items).forEach((item) => {
            var typeName;
            Object.entries(invoiceTypes).forEach((type) => {
                if (type[1].id == item[1].invoice_type_id) {
                    typeName = type[1].name;
                }
            });
            html += `<tr>
                        <td>${typeName}</td>
                        <td>${item[1].description}</td>
                        <td class="invoice-tbl-last-field">${currencyPrice(item[1].amount)}</td>
                    </tr>`;
        });
        selector.find('#invoiceItems').html(html);
        selector.find('.total').html(currencyPrice(response.data.invoice.amount));
    }

    $(document).on('change', '.property_id', function () {
        stateSelector = $(this);
        getPropertyUnits(stateSelector.val());
    });

    function getPropertyUnits(property_id) {
        var getPropertyUnitsRoute = $('#getPropertyUnitsRoute').val();
        commonAjax('GET', getPropertyUnitsRoute, getPropertyUnitsRes, getPropertyUnitsRes, { "property_id": property_id });
    }

    function getPropertyUnitsRes(response) {
        var html = '<option value="">--Select Unit--</option>';
        response.data.forEach(function (opt) {
            html += '<option value="' + opt.id + '">' + opt.unit_name + '</option>';
        });
        stateSelector.closest('.modal').find('.propertyUnitSelectOption').html(html);
    }
    
    // Auto-fill rent amount when a unit is selected
    $(document).on('change', '.propertyUnitSelectOption', function () {
        const unitId = $(this).val();
        const $modal = $(this).closest('.modal');
        const $amountFields = $modal.find('.invoiceItem-amount');
    
        if (!unitId) {
            $amountFields.val('');
            return;
        }
    
        const url = $('#getTenantRentRoute').val().replace(':unitId', unitId);
    
        $.ajax({
            url: url,
            type: 'GET',
            success: function (response) {
                if (response.result === true && response.data.rent) {
                    // Fill only the first amount field (the rent line item)
                    $amountFields.first().val(response.data.rent);
                } else {
                    $amountFields.first().val('');
                }
            },
            error: function () {
                $amountFields.first().val('');
            }
        });
    });

    // datatable
    $('#search_property').on('change', function () {
        var oTable = $('#allInvoiceDataTable').DataTable();
        oTable.search($(this).val()).draw();
    })
    $('#allInvoiceDataTable').DataTable({
        processing: true,
        serverSide: true,
        pageLength: 25,
        responsive: true,
        ajax: $('#invoiceRecurring').val(),
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
            "data": "prefix",
        },
        {
            "data": "property",
            "name": 'properties.name'
        },
        {
            "data": "type"
        },
        {
            "data": "amount"
        },
        {
            "data": "status"
        },
        {
            "data": "action"
        },
        ]
    });

})(jQuery)

