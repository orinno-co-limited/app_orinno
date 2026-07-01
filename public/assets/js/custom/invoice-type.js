$(function () {
    'use strict'
    $('#add').on('click', function () {
        var selector = $('#addInvoiceTypeModal');
        selector.find('.is-invalid').removeClass('is-invalid');
        selector.find('.error-message').remove();
        selector.modal('show')
        selector.find('form').trigger('reset');
    });

    $(document).on('click', '.edit', function (e) {
        var selector = $('#editInvoiceTypeModal');
        selector.find('.is-invalid').removeClass('is-invalid');
        selector.find('.error-message').remove();
        selector.modal('show')
        selector.find('input[name=id]').val($(this).data('item').id)
        selector.find('input[name=name]').val($(this).data('item').name)
        selector.find('input[name=tax]').val(parseFloat($(this).data('item').tax).toFixed(2))
    })
})
