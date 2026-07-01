$('.specialUploadFilesAdd').on('click', function () {
    var configId = $(this).data('id');
    var selected = $('#addFilesModal');
    selected.modal('show')
    selected.find('.kyc_config_id').val(configId)
    if (selected.find('.kyc_config_id').find(':selected').data('is_both') == 1) {
        selected.find('.isBoth').removeClass('d-none')
    } else {
        selected.find('.isBoth').addClass('d-none')
    }
});

$('#addFiles').on('click', function () {
    var selected = $('#addFilesModal');
    selected.modal('show')
    selected.find('.kyc_config_id').val('')
});

$('.kyc_config_id').on('change', function () {
    if ($(this).find(':selected').data('is_both') == 1) {
        $(this).closest('.modal').find('.isBoth').removeClass('d-none')
    } else {
        $(this).closest('.modal').find('.isBoth').addClass('d-none')
    }
    commonAjax('GET', $('#getConfigInfoRoute').val(), getKycConfigInfoRes, getKycConfigInfoRes, { 'id': $(this).val() });
});

function getKycConfigInfoRes(response) {
    $('.demo-file').removeClass('d-none')
    $('.demo-file').find('a').attr('href', response.data.image)
}

$(document).on('click', '.edit', function () {
    commonAjax('GET', $('#getInfoRoute').val(), getDataEditRes, getDataEditRes, { 'id': $(this).data('id') });
});

function getDataEditRes(response) {
    var selected = $('#editFilesModal');
    selected.modal('show')
    selected.find('.id').val(response.data.id)
    selected.find('.kyc_config_id').val(response.data.kyc_config_id)
    selected.find('.kyc_config_name').val(response.data.config_name)

    // Front
    var drEvent1 = selected.find('.front').dropify({
        defaultFile: response.data.front
    });
    drEvent1 = drEvent1.data('dropify');
    drEvent1.resetPreview();
    drEvent1.clearElement();
    drEvent1.settings.defaultFile = response.data.front;
    drEvent1.destroy();
    drEvent1.init();

    // Back
    var drEvent2 = selected.find('.back').dropify({
        defaultFile: response.data.back
    });
    drEvent2 = drEvent2.data('dropify');
    drEvent2.resetPreview();
    drEvent2.clearElement();
    drEvent2.settings.defaultFile = response.data.back;
    drEvent2.destroy();
    drEvent2.init();

    if (response.data.is_both == 1) {
        selected.find('.isBoth').removeClass('d-none')
    } else {
        selected.find('.isBoth').addClass('d-none')
    }
}

$('.read-more-btn').on("click", function () {
    $(this).closest('.kycVerificationRow').find('.reason-text').slideToggle(1000);
});

// dropify initial
$('.dropify').dropify({
    messages: {
        'default': 'Drag and drop a file here or click',
        'replace': 'Drag and drop or click to replace',
        'remove': 'Remove',
        'error': 'Oops, something wrong happened.'
    }
});
