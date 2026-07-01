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
    selector.find('input[name=name]').val(response.name)
    selector.find('input[name=designation]').val(response.designation)
    selector.find('textarea[name=comment]').text(response.comment)
    selector.find('input[name=star]').val(response.star)
    selector.find('select[name=status]').val(response.status)
    selector.modal('show')
}
