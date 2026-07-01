$(document).on('click', '.view', function () {
    commonAjax('GET', $('#getInfoRoute').val(), getDataRes, getDataRes, { 'id': $(this).data('id') });
});

function getDataRes(response) {
    $('#id').val(response.data.id)
    $('.status').val(response.data.status)
    $('.amount').val(response.data.amount)
    if (response.data.file_attach_file) {
        $('.attach').attr('href', response.data.attach);
        $('.attach').text(response.data.file_attach_file.file_name);
    } else {
        $('.attach').attr('href', '');
        $('.attach').text('');
    }
    if (response.data.file_attach_invoice) {
        $('.invoice').attr('href', response.data.invoice);
        $('.invoice').text(response.data.file_attach_invoice.file_name);
    } else {
        $('.invoice').attr('href', '');
        $('.invoice').text('');
    }
    $('.issue_name').text(response.data.issue_name)
    $('.view_details').text(response.data.details)
}
