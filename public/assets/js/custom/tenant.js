// Response handler
function closeStatusChange(data) {
    var output = '';
    var type = 'error';
    $('.error-message').remove();
    $('.is-invalid').removeClass('is-invalid');
    if (data['status'] == true) {
        output = output + data['message'];
        type = 'success';
        $('#tenantCloseModal').modal('hide');
        $('.remove-tenants-item').addClass('d-none');
        alertAjaxMessage(type, output);
    } else {
        commonHandler(data)
    }
}

function deleteShowResponse(data) {
    var output = '';
    var type = 'error';
    if (data['status'] == true) {
        output = output + data['message'];
        type = 'success';
        window.location.href = $('#tenantListRoute').val();
        alertAjaxMessage(type, output);
    } else {
        commonHandler(data)
    }
}

function stepChange(data) {
    var output = '';
    var type = 'error';
    $('.error-message').remove();
    $('.is-invalid').removeClass('is-invalid');
    if (data['status'] == true) {
        output = output + data['message'];
        type = 'success';
        $("input[name='id']").val(data.data.id)
        nextStep(data.data.step)
        alertAjaxMessage(type, output);
    } else {
        commonHandler(data)
    }
}

// State selector
var thisStateSelector;
// Get unit
$(document).on('change', '.property_id', function () {
    thisStateSelector = $(this);
    var route = $('#getPropertyWithUnitsByIdRoute').val();
    commonAjax('GET', route, getPropertyWithUnitsRes, getPropertyWithUnitsRes, { 'property_id': $(thisStateSelector).val() });
});

function getPropertyWithUnitsRes(response) {
    if (response.data.units) {
        $('#propertyInformation').removeClass('d-none')
        unitsCollection = response.data.units;
        var unitOptionsHtml = response.data.units.map(function (opt) {
            return '<option value="' + opt.id + '">' + opt.name + '</option>';
        }).join('');
        var unitsHtml = '<option value="">--Select Unit--</option>' + unitOptionsHtml
        $('.unit_id').html(unitsHtml);
        $(".propertyImg").attr("src", response.data.image);
        $('.property-item-title').find('a').html(response.data.name)
        var propertyShowRoute = $('#propertyShowRoute').val();
        $('.property-item-title').find('a').attr('href', propertyShowRoute.replace("0", response.data.id))
        $('.property-item-address').find('p').html(response.data.address)

        $('#general_rent').val(0);
        $('#incident_receipt').val(0);
        $('#late_fee').val(0);
        $('#security_deposit').val(0);
    } else {
        unitsCollection = [];
        $('#propertyInformation').addClass('d-none')
        $('.unit_id').html('<option value="">--Select Unit--</option>');
    }
}

// Show data by unit
$(document).on('change', '#unitId', function () {
    var id = $(this).val();
    var unit = unitsCollection.find(x => x.id == id);
    $('#unit_name').html(unit.name);
    $('#general_rent').val(unit.general_rent);
    $('#incident_receipt').val(unit.incident_receipt);
    if (unit.rent_type == 1) {
        $('#payment_due_on_date').val(unit.monthly_due_day);
    }
    else {
        $('#payment_due_on_date').val(unit.yearly_due_day);
    }
    $('#late_fee_type').val(unit.late_fee_type);
    $('#late_fee').val(unit.late_fee);
    $('#security_deposit_type').val(unit.security_deposit_type);
    $('#security_deposit').val(unit.security_deposit);
});

// Document remove
$(document).on("click", ".removeDocument", function () {
    thisStateSelector = $(this);
    var route = $(thisStateSelector).data('route');
    Swal.fire({
        title: 'Sure! You want to delete?',
        text: "You won't be able to revert this!",
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#3085d6',
        cancelButtonColor: '#d33',
        confirmButtonText: $('cancelConfirmButtonText').val(),
        cancelButtonText: $('#cancelButtonText').val()
    }).then((result) => {
        if (result.value) {
            commonAjax('GET', route, documentRemovedRes, documentRemovedRes);
        }
    })
});

function documentRemovedRes(response) {
    toastr.success(response.message)
    $(thisStateSelector).parent().parent().parent().remove();
    Swal.fire({
        title: 'Deleted',
        html: ' <span style="color:red">Item has been deleted</span> ',
        timer: 2000,
        icon: 'success'
    })
}

// Get state
$(document).on("change", ".country_id", function () {
    thisStateSelector = $(this);
    getStateByCountryId($(thisStateSelector).val());
})

function getStateByCountryId(country_id) {
    var getStateListRoute = $('#getStateListRoute').val();
    commonAjax('GET', getStateListRoute, getStateByCountryRes, getStateByCountryRes, { 'country_id': country_id });
}

function getStateByCountryRes(response) {
    var states = response.data.states;
    var optionsHtml = states.map(function (opt) {
        return '<option value="' + opt.id + '">' + opt.name + '</option>';
    }).join('');
    var html = '<option value="">--Select State--</option>' + optionsHtml
    var stateId = $(thisStateSelector).closest('.location').find('.state_id');
    var cityId = $(thisStateSelector).closest('.location').find('.city_id');
    $(stateId).html(html);
    $(cityId).html('<option value="">--Select City--</option>');
}

// Get city
$(document).on("change", ".state_id", function () {
    thisStateSelector = $(this);
    getCitiesByState($(thisStateSelector).val())
})

function getCitiesByState(state_id) {
    var getCityListRoute = $('#getCityListRoute').val();
    commonAjax('GET', getCityListRoute, getCitiesByStateRes, getCitiesByStateRes, { 'state_id': state_id });
}

function getCitiesByStateRes(response) {
    var cities = response.data.cities
    var optionsHtml = cities.map(function (opt) {
        return '<option value="' + opt.id + '">' + opt.name + '</option>';
    }).join('');
    var html = '<option value="">--Select City--</option>' + optionsHtml
    var cityId = $(thisStateSelector).closest('.location').find('.city_id');
    $(cityId).html(html);
}

/*---------------------------------
Add Property Stepper Form JS Start
-----------------------------------*/
var current_fs, next_fs, previous_fs;
var opacity;
var current = 1;
var steps = $("fieldset").length;

setProgressBar(current);
function nextStep(stepClassName) {
    current_fs = $('.' + stepClassName).parent().parent();
    next_fs = $('.' + stepClassName).parent().parent().next();
    //Add Class Active
    $("#progressbar li").eq($("fieldset").index(next_fs)).addClass("active");
    //show the next fieldset
    next_fs.show();
    //hide the current fieldset with style
    current_fs.animate(
        { opacity: 0 },
        {
            step: function (now) {
                // for making fielset appear animation
                opacity = 1 - now;

                current_fs.css({
                    display: "none",
                    position: "relative"
                });
                next_fs.css({ opacity: opacity });
            },
            duration: 500
        }
    );
    setProgressBar(++current);
    goToList(stepClassName)
}

function goToList(stepClassName) {
    if (stepClassName === 'lastStep') {
        window.location.href = $('#tenantListRoute').val();
    }
}

$(".previousStep").on("click", function () {
    current_fs = $(this).parent().parent();
    previous_fs = $(this).parent().parent().prev();

    //Remove class active
    $("#progressbar li")
        .eq($("fieldset").index(current_fs))
        .removeClass("active");
    //show the previous fieldset
    previous_fs.show();
    //hide the current fieldset with style
    current_fs.animate(
        { opacity: 0 },
        {
            step: function (now) {
                // for making fielset appear animation
                opacity = 1 - now;

                current_fs.css({
                    display: "none",
                    position: "relative"
                });
                previous_fs.css({ opacity: opacity });
            },
            duration: 500
        }
    );
    setProgressBar(--current);
});

function setProgressBar(curStep) {
    var percent = parseFloat(100 / steps) * curStep;
    percent = percent.toFixed();
    $(".progress-bar").css("width", percent + "%");
}

// dropify initial
$('.dropify').dropify({
    messages: {
        'default': 'Drag and drop a file here or click',
        'replace': 'Drag and drop or click to replace',
        'remove': 'Remove',
        'error': 'Oops, something wrong happened.'
    }
});
// dropzone initial
var dropzonePreviewNode = document.querySelector("#dropzone-preview-list2");
if (dropzonePreviewNode) {
    dropzonePreviewNode.id = "";
    var previewTemplate = dropzonePreviewNode.parentNode.innerHTML;
    dropzonePreviewNode.parentNode.removeChild(dropzonePreviewNode);
    var dropzone = new Dropzone(".dropzone", {
        url: $('#tenantStoreRoute').val(),
        method: "post",
        previewTemplate: previewTemplate,
        previewsContainer: "#dropzone-preview2",
    });
    var CSRF_TOKEN = document.querySelector('meta[name="csrf-token"]').getAttribute("content");
    dropzone.on("sending", function (file, xhr, formData) {
        formData.append("_token", CSRF_TOKEN);
        formData.append("id", $("input[name='id']").val());
        formData.append("step", 3);
    });
    dropzone.on("complete", function (file) {
        toastr.success('File Uploaded Successfully');
    });
}

