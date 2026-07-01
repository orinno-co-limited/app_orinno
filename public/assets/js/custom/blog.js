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
    selector.find('input[name=title]').val(response.title)
    selector.find('textarea[name=details]').summernote('code', response.details);
    selector.find('input[name=publish_date]').val(response.publish_date.split(' ')[0]);
    selector.find('select[name=blog_category_id]').val(response.blog_category_id)
    selector.find('select[name=status]').val(response.status)
    selector.modal('show')
}
