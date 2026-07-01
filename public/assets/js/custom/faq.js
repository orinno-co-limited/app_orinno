$('#add').on('click', function () {
    var selector = $('#addModal');
    selector.find('.is-invalid').removeClass('is-invalid');
    selector.find('.error-message').remove();
    selector.modal('show')
    selector.find('form').trigger("reset");
});

$(document).on('click', '.edit', function () {
    commonAjax('GET', $('#getInfoRoute').val(), getDataEditRes, getDataEditRes, { 'id': $(this).data('id') });
});

function getDataEditRes(response) {
    var selector = $('#editModal');
    selector.find('.is-invalid').removeClass('is-invalid');
    selector.find('.error-message').remove();
    selector.find('input[name=id]').val(response.id)
    selector.find('input[name=question]').val(response.question)
    selector.find('textarea[name=answer]').text(response.answer)
    selector.find('select[name=status]').val(response.status)
    selector.modal('show')
}
