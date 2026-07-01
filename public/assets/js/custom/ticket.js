
$('.edit').on('click', function () {
    commonAjax('GET', $('#getInfoRoute').val(), getDataEditRes, getDataEditRes, { 'id': $(this).data('id') });

});

function getDataEditRes(response) {
    var selector = $('#editTicketModal')
    selector.modal('show');
    selector.find('.id').val(response.data.id);
    selector.find('.title').val(response.data.title);
    selector.find('.details').text(response.data.details);
    selector.find('.topic').val(response.data.topic_id);
}

// Dropify Initial
$('.dropify').dropify({
    messages: {
        'default': 'Drag and drop a file here or click',
        'replace': 'Drag and drop or click to replace',
        'remove': 'Remove',
        'error': 'Oops, something wrong happened.'
    }
});
