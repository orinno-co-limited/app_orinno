$('#add').on('click', function () {
    var selector = $('#addModal');
    selector.find('.is-invalid').removeClass('is-invalid');
    selector.find('.error-message').remove();
    selector.modal('show');
});

$(document).on('click', '.edit', function () {
    commonAjax('GET', $('#getInfoRoute').val(), getDataEditRes, getDataEditRes, { 'id': $(this).data('id') });
});

function getDataEditRes(response) {
    var selector = $('#editModal');
    selector.find('.is-invalid').removeClass('is-invalid');
    selector.find('.error-message').remove();
    selector.find('input[name=id]').val(response.data[0].id)
    selector.find('input[name=subject]').val(response.data[0].subject)
    selector.find(".summernote").summernote('code', response.data[0].body);
    selector.find('select[name=status]').val(response.data[0].status)
    selector.find('select[name=category]').val(response.data[0].category)
    selector.modal('show')
}
