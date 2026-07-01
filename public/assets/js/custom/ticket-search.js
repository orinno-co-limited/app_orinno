
$('.statusSearch').on('change', function () {
    var statusVal = $('.statusSearch').val();
    tenantSearch(statusVal);
})

tenantSearch(0);
function tenantSearch(statusVal) {
    if (statusVal == 0) {
        $('.ticket-column').removeClass('d-none');
    } else {
        $('.ticket-column').addClass('d-none');
        $('.status' + statusVal).removeClass('d-none');
    }
}

$('.textSearch').on('input', function () {
    let search = $(this).val();
    let status = $('.statusSearch').find(':selected').val()
    commonAjax('GET', $('#searchRoute').val(), getSearchDataRes, getSearchDataRes, { 'search': search, 'status': status });
});

function getSearchDataRes(response) {
    $('#ticketAppend').html(response.data);
}
