var thisStateSelector;
// Get unit
$(document).on('change', '.property_id', function () {
    thisStateSelector = $(this);
    var route = $('#getPropertyUnitsRoute').val();
    commonAjax('GET', route, getUnitsRes, getUnitsRes, { 'property_id': $(thisStateSelector).val() });
});

function getUnitsRes(response) {
    if (response.data) {
        var unitOptionsHtml = response.data.map(function (opt) {
            return '<option value="' + opt.id + '">' + opt.unit_name + '</option>';
        }).join('');
        var unitsHtml = '<option value="0">--Select Unit--</option>' + unitOptionsHtml
        $('.unit_id').html(unitsHtml);
    } else {
        $('.unit_id').html('<option value="0">--Select Unit--</option>');
    }
}

tenantSearch(0, 0);
function tenantSearch(property_id, unit_id) {
    if (property_id == 0 && unit_id == 0) {
        $('.single-tenant').removeClass('d-none');
    } else if (property_id != 0 && unit_id == 0) {
        $('.single-tenant').addClass('d-none');
        $('.property-' + property_id).removeClass('d-none');
    } else if (property_id != 0 && unit_id != 0) {
        $('.single-tenant').addClass('d-none');
        $('.up-' + property_id + '-' + unit_id).removeClass('d-none');
    }
}
$(document).on('click', '#applySearch', function () {
    var property_id = $('.property_id').val();
    var unit_id = $('.unit_id').val();
    tenantSearch(property_id, unit_id);
})
