$('#addInfo').on('click', function () {
    $('#infoItems').append(infoTemplate());
});

$(document).on('click', '.removeInfo', function () {
    $(this).parent().parent().remove()
});

function getShowListingMessage(response) {
    var output = '';
    var type = 'error';
    $('.error-message').remove();
    $('.is-invalid').removeClass('is-invalid');
    if (response['status'] == true) {
        output = output + response['message'];
        type = 'success';
        toastr.success(response.message)
        location.replace($('#listingRoute').val())
    } else {
        commonHandler(response)
    }
}

function infoTemplate() {
    return `<div class="row mb-3">
                <div class="col-lg-6">
                    <div class="single-upload-input-from">
                        <label>Name</label>
                        <input type="text" name="info[name][]"
                        class="info-name"
                        placeholder="Name">
                    </div>
                </div>
                <div class="col-lg-6">
                    <div class="single-upload-input-from">
                        <label>Distance</label>
                        <input type="text" name="info[distance][]"
                            placeholder="Distance">
                    </div>
                </div>
                <div class="col-lg-6">
                    <div class="single-upload-input-from">
                        <label>Contact Number</label>
                        <input type="text"
                            name="info[contact_number][]"
                            placeholder="Contact Number">
                    </div>
                </div>
                <div class="col-lg-6">
                    <div class="single-upload-input-from">
                        <label
                            for="image">Upload Image</label>
                        <input type="file" name="info[image][]">
                    </div>
                </div>
                <div class="col-lg-12">
                    <div class="single-upload-input-from">
                        <label>Details</label>
                        <textarea name="info[details][]" placeholder="Details" cols="30" rows="10"></textarea>
                    </div>
                </div>
                <div class="col-md-12 d-flex justify-content-center">
                    <button type="button"
                    class="upload-count-trash theme-btn-red ms-4 removeInfo"><i
                        class="far fa-trash-alt"></i></button>
                </div>
            </div>`;
}
