$(function () {
    'use strict'

    $('#add').on('click', function () {
        var selector = $('#addModal');
        selector.find('.is-invalid').removeClass('is-invalid');
        selector.find('.error-message').remove();
        selector.modal('show')
        selector.find('form').trigger("reset");
    });

    $(document).on('click', '.edit', function () {
        var selector = $('#editModal');
        selector.find('.is-invalid').removeClass('is-invalid');
        selector.find('.error-message').remove();
        selector.modal('show')
        selector.find('input[name=id]').val($(this).data('item').id)
        selector.find('input[name=type_name]').val($(this).data('item').name)
        selector.find('input[name=tax]').val(parseFloat($(this).data('item').tax).toFixed(2))
        selector.find('form').attr("action", $(this).data('updateurl'))
    });
})
