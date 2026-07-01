$(function () {
    'use strict'
    $(document).on('click', '.edit', function (e) {
        e.preventDefault();
        const modal = $('.edit_modal');
        modal.find('input[name=currency_code]').val($(this).data('item').currency_code)
        modal.find('input[name=symbol]').val($(this).data('item').symbol)
        modal.find('select[name=currency_placement]').val($(this).data('item').currency_placement)
        var current_currency = $(this).data('item').current_currency
        if (current_currency == 1) {
            modal.find('input[name=current_currency]').attr('checked', true)
        } else {
            modal.find('input[name=current_currency]').attr('checked', false)
        }
        let route = $(this).data('updateurl');
        $('#updateEditModal').attr("action", route)
        modal.modal('show')
    })
})
