$(document).on('click', '.orderPayStatus', function () {
    commonAjax('GET', $('#ordersGetInfoRoute').val(), getInfoRes, getInfoRes, { 'id': $(this).data('id') });
});

function getInfoRes(response) {
    const selector = $('#payStatusChangeModal');
    selector.find('input[name=id]').val(response.data.id)
    selector.find('select[name=status]').val(response.data.payment_status)
    selector.modal('show')
}

$(document).on('click', '.view', function () {
    commonAjax('GET', $('#ordersGetInfoRoute').val(), getInfoViewRes, getInfoViewRes, { 'id': $(this).data('id') });
});

function getInfoViewRes(response) {
    const selector = $('#previewModal');
    selector.find('.invoiceNo').text(response.data.invoice_no)
    var status = 'Pending';
    if (response.data.payment_status == '1') {
        status = "Paid"
    }
    selector.find('.invoiceStatus').html(status)

    selector.find('.total').html(currencyPrice(response.data.total));
    if (response.data != null) {
        selector.find('.orderDate').html(dateFormat(response.data.created_at, 'YYYY-MM-DD'))
        selector.find('.orderPaymentTitle').html(response.data.gatewayTitle)
        selector.find('.orderPaymentId').html(response.data.transaction_id)
        selector.find('.orderTotal').html(currencyPrice(response.data.total))
    } else {
        selector.find('.orderDate').html()
        selector.find('.orderPaymentTitle').html()
        selector.find('.orderPaymentId').html()
        selector.find('.orderTotal').html()
    }
    selector.modal('show')
}

(function ($) {
    "use strict";
    var allOrderDataTable;
    var allPaidDataTable;
    var allPendingDataTable;
    var bankPendingInvoiceDataTable;
    var allCancelledDataTable;

    $(document).on('shown.bs.tab', 'button[data-bs-toggle="tab"]', function (event) {
        allOrderDataTable.ajax.reload();
        allPaidDataTable.ajax.reload();
        allPendingDataTable.ajax.reload();
        bankPendingInvoiceDataTable.ajax.reload();
        allCancelledDataTable.ajax.reload();
    });

    allOrderDataTable = $('#allOrderDataTable').DataTable({
        processing: true,
        serverSide: true,
        pageLength: 25,
        responsive: true,
        ajax: $('#ordersRoute').val(),
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
            { "data": "name", "name": 'users.first_name' },
            { "data": "package", "name": 'packages.name' },
            { "data": "amount", "name": 'users.last_name' },
            { "data": "gateway", },
            { "data": "date", "name": "subscription_orders.created_at" },
            { "data": "status", },
            { "data": "action", },
        ]
    });

    allPaidDataTable = $('#allPaidDataTable').DataTable({
        processing: true,
        serverSide: true,
        pageLength: 25,
        responsive: true,
        ajax: $('#ordersPaidRoute').val(),
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
            { "data": "name", "name": 'users.first_name' },
            { "data": "package", "name": 'packages.name' },
            { "data": "amount", "name": 'users.last_name' },
            { "data": "gateway", },
            { "data": "date", "name": "subscription_orders.created_at" },
            { "data": "status", },
            { "data": "action", },
        ]
    });

    allPendingDataTable = $('#allPendingDataTable').DataTable({
        processing: true,
        serverSide: true,
        pageLength: 25,
        responsive: true,
        ajax: $('#ordersPendingRoute').val(),
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
            { "data": "name", "name": 'users.first_name' },
            { "data": "package", "name": 'packages.name' },
            { "data": "amount", "name": 'users.last_name' },
            { "data": "gateway", },
            { "data": "date", "name": "subscription_orders.created_at" },
            { "data": "status", },
            { "data": "action", },
        ]
    });

    bankPendingInvoiceDataTable = $('#bankPendingInvoiceDataTable').DataTable({
        processing: true,
        serverSide: true,
        pageLength: 25,
        responsive: true,
        ajax: $('#ordersBankRoute').val(),
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
            { "data": "name", "name": 'users.first_name' },
            { "data": "package", "name": 'packages.name' },
            { "data": "amount", "name": 'users.last_name' },
            { "data": "gateway", },
            { "data": "date", "name": "subscription_orders.created_at" },
            { "data": "status", },
            { "data": "action", },
        ]
    });

    allCancelledDataTable = $('#allCancelledDataTable').DataTable({
        processing: true,
        serverSide: true,
        pageLength: 25,
        responsive: true,
        ajax: $('#ordersCancelledRoute').val(),
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
            { "data": 'DT_RowIndex', "name": 'DT_RowIndex', orderable: false, searchable: false, 'title': 'SL' },
            { "data": "name", "name": 'users.first_name' },
            { "data": "package", "name": 'packages.name' },
            { "data": "amount", "name": 'users.last_name' },
            { "data": "gateway", },
            { "data": "date", "name": "subscription_orders.created_at" },
            { "data": "status", },
            { "data": "action", },
        ]
    });

})(jQuery)
