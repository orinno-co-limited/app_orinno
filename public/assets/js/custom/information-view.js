$(document).on('click', '.view', function () {
    commonAjax('GET', $('#getInfoRoute').val(), getDataViewRes, getDataViewRes, { 'id': $(this).data('id') });
});

function getDataViewRes(response) {
    $('.image').attr('src', response.data.image)
    $('.name').html(response.data.name)
    $('.property').html(response.data.property_name)
    $('.distance').html(response.data.distance)
    $('.contact_number').html(response.data.contact_number)
    $('.additional_information').html(response.data.additional_information)
}
