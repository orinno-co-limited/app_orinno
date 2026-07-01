$('#add').on('click', function () {
    var selector = $('#addModal');
    selector.find('.is-invalid').removeClass('is-invalid');
    selector.find('.error-message').remove();
    selector.modal('show')
    selector.find('form').trigger('reset');
});
$(document).on('click', '.edit', function () {
    commonAjax('GET', $('#getInfoRoute').val(), getDataEditRes, getDataEditRes, { 'id': $(this).data('id') });
});

function getDataEditRes(response) {
    var selector = $('#editModal');
    selector.find('.is-invalid').removeClass('is-invalid');
    selector.find('.error-message').remove();
    selector.find('#id').val(response.data.id)
    selector.find('.tenant_id').val(response.data.tenant_id)
    selector.find('.name').val(response.data.name)
    selector.find('.details').text(response.data.details)
    selector.find('.status').val(response.data.status)
    selector.modal('show')
    if (response.data.is_both == 1) {
        selector.find('#isBothEdit').attr('checked', true)
    } else {
        selector.find('#isBothEdit').attr('checked', false)
    }
}
