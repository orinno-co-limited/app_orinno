$('#add').on('click', function () {
    var selector = $('#addModal');
    selector.find('.is-invalid').removeClass('is-invalid');
    selector.find('.error-message').remove();
    selector.modal('show')
    selector.find('form').trigger("reset");
});

$('.edit').on('click', function (e) {
    var selector = $('#editModal');
    selector.find('.is-invalid').removeClass('is-invalid');
    selector.find('.error-message').remove();
    selector.find('input[name=name]').val($(this).data('item').name)
    selector.find('input[name=id]').val($(this).data('item').id)
    selector.find('select[name=status]').val($(this).data('item').status)
    selector.modal('show')
})
