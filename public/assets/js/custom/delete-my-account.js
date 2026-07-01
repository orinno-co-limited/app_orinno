$('#deleteMyAccountBtn').on('click', function () {
    var selector = $('#deleteModal');
    selector.find('.is-invalid').removeClass('is-invalid');
    selector.find('.error-message').remove();
    selector.find('form').trigger('reset');
    selector.modal('show')
})
