$('#add').on('click', function () {
    var selector = $('#addLanguageModal');
    selector.find('.is-invalid').removeClass('is-invalid');
    selector.find('.error-message').remove();
    selector.modal('show')
    selector.find('form').trigger("reset");
});

$(document).on('click', '.editLanguageBtn', function () {
    var selector = $('#editLanguageModal');
    selector.find('.is-invalid').removeClass('is-invalid');
    selector.find('.error-message').remove();
    selector.modal('show');
    var url = $('#editLanguageRoute').data('route')
    selector.find('form').attr('action', url.replace("@", $(this).data('data')['id']));
    selector.find('form input[name=name]').val($(this).data('data')['name']);
    selector.find('form input[name=code]').val($(this).data('data')['code']);
    selector.find('form select[name=rtl]').val($(this).data('data')['rtl']);
    selector.find('form select[name=default]').val($(this).data('data')['default']);
    selector.find('form select[name=status]').val($(this).data('data')['status']);
    document.getElementById("editImageShow").src = $(this).data('data')['icon'];
    if ($(this).data('font')) {
        selector.find('.font').attr('href', $(this).data('font'));
        selector.find('.font').removeClass('d-none');
    } else {
        selector.find('.font').addClass('d-none');

    }
});

// Translate
$('.addmore').on('click', function (e) {
    e.preventDefault()
    let html = `
                    <tr>
                        <td>
                            <textarea type="text" name="key" class="key form-control" required></textarea>
                        </td>
                        <td>
                            <input type="hidden" value="1" class="is_new">
                            <textarea type="text" name="value" class="val form-control" required></textarea>
                        </td>
                        <td class="text-end col-1">
                            <button type="button" class="updateLangItem theme-btn">Update</button>
                        </td>
                    </tr>
                `;
    $('#append').prepend(html);
})

$(document).on('input', '.val', function () {
    $(this).closest('tr').find('button').attr('disabled', false);
})

$(document).on('click', '.updateLangItem', function () {
    var keyStr = $(this).closest('tr').find('.key').val();
    var valStr = $(this).closest('tr').find('.val').val();
    var is_new = $(this).closest('tr').find('.is_new').val();
    commonAjax('GET', $('#updateLangItemRoute').val(), getDataShowRes, getDataShowRes, { 'key': keyStr, 'val': valStr, 'is_new': is_new });
});

function getDataShowRes(response) {
    var output = '';
    var type = 'error';
    $('.error-message').remove();
    $('.is-invalid').removeClass('is-invalid');
    if (response['status'] == true) {
        output = output + response['message'];
        type = 'success';
        toastr.success(response.message)
    } else {
        toastr.error(response['responseJSON'].message)
    }
}

