

$(document).on('submit', "form.ajax", function (event) {
    event.preventDefault();
    var enctype = $(this).prop("enctype");
    if (!enctype) {
        enctype = "application/x-www-form-urlencoded";
    }
    commonAjax($(this).prop('method'), $(this).prop('action'), window[$(this).data("handler")], window[$(this).data("handler")], new FormData($(this)[0]));
});

function commonAjax(type, url, successHandler, errorHandler, data) {
    var ajaxData = {
        type: type,
        url: url,
        dataType: 'json',
        success: successHandler,
        error: errorHandler
    }
    if (typeof (data) != 'undefined') {
        ajaxData.data = data;
    }
    if (type == 'POST' || type == 'post') {
        ajaxData.encType = 'enctype';
        ajaxData.contentType = false;
        ajaxData.processData = false;
    }
    $.ajax(ajaxData);
}

function getShowMessage(response) {
    var output = '';
    var type = 'error';
    $('.error-message').remove();
    $('.is-invalid').removeClass('is-invalid');
    if (response['status'] == true) {
        output = output + response['message'];
        type = 'success';
        toastr.success(response.message)
        location.reload()
    } else {
        commonHandler(response)
    }
}

function commonHandler(data) {
    var output = '';
    var type = 'error';
    $('.error-message').remove();
    $('.is-invalid').removeClass('is-invalid');
    if (data['status'] == false) {
        output = output + data['message'];
    } else if (data['status'] === 422) {
        var errors = data['responseJSON']['errors'];
        output = getValidationError(errors);
    } else if (typeof data['responseJSON']['error'] !== 'undefined') {
        output = data['responseJSON']['error'];
    } else {
        output = data['responseJSON']['message'];
    }
    alertAjaxMessage(type, output);
}

function alertAjaxMessage(type, message) {
    if (type === 'success') {
        toastr.success(message);
    } else if (type === 'error') {
        toastr.error(message);
    } else if (type === 'warning') {
        toastr.error(message);
    } else {
        return false;
    }
}

function getValidationError(errors) {
    var output = 'Validation Errors';
    $.each(errors, function (index, items) {
        if (index.indexOf('.') != -1) {
            var name = index.split('.');
            var getName = name.slice(0, -1).join('-');
            var i = name.slice(-1);
            var itemSelect = $(document).find('.' + getName + ':eq(' + i + ')')
            itemSelect.addClass('is-invalid');
            itemSelect.closest('div').append('<span class="text-danger p-2 error-message">' + items[0] + '</span>')
        } else {
            var itemSelect = $("[name='" + index + "']");
            itemSelect.addClass('is-invalid');
            itemSelect.closest('div').append('<span class="text-danger p-2 error-message">' + items[0] + '</span>')
        }
    });
    return output;
}

$(document).on("submit", "form", function (e) {
    var form = $(this);
    form.find('button[type=submit]').attr('disabled', true);
    setTimeout(function () {
        form.find('button[type=submit]').attr('disabled', false);
    }, 5000)
})

$(document).on('keyup change paste', 'input, select, textarea', function () {
    var form = $(this).closest('form')
    form.find('button[type=submit]').attr('disabled', false);
});

function currencyPrice($price) {
    if (currencyPlacement == 'after')
        return $price.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ",") + ' ' + currencySymbol;
    else {
        return currencySymbol + $price.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ",");
    }
}

function gatewayCurrencyPrice($price, $currency = 'USD') {
    if (currencyPlacement == 'after')
        return $price + ' ' + $currency;
    else {
        return $currency + ' ' + $price;
    }
}

function dateFormat(date, format = 'MM-DD-YYYY') {
    return moment(date).format(format);
}

function deleteItem(url, id) {
    let deleteTitle = $('#deleteTitle').val();
    let deleteText = $('#deleteText').val();

    Swal.fire({
        title: deleteTitle,
        text: deleteText,
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#3085d6',
        cancelButtonColor: '#d33',
        confirmButtonText: $('#deleteConfirmButtonText').val(),
        cancelButtonText: $('#cancelButtonText').val()
    }).then((result) => {
        if (result.value) {
            $.ajax({
                type: 'GET',
                url: url,
                success: function (data) {
                    Swal.fire({
                        title: 'Deleted',
                        html: ' <span style="color:red">Item has been deleted</span> ',
                        timer: 2000,
                        icon: 'success'
                    })
                    toastr.success(data.message);
                    if (id == 'allDataTableDoc') {
                        location.reload();
                    } else {
                        $('#' + id).DataTable().ajax.reload();
                    }
                },
                error: function (error) {
                    toastr.error(error.responseJSON.message)
                }
            })
        }
    })
}

$(document).on("click", ".deleteItem", function () {
    let form_id = this.dataset.formid;

    let deleteTitle = $('#deleteTitle').val();
    var deleteText = $('#deleteText').val();

    Swal.fire({
        title: deleteTitle,
        text: deleteText,
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#3085d6',
        cancelButtonColor: '#d33',
        confirmButtonText: $('#deleteConfirmButtonText').val(),
        cancelButtonText: $('#cancelButtonText').val()
    }).then((result) => {
        if (result.value) {
            $("#" + form_id).submit();
        }
    })
});

$(document).on("click", ".subscriptionCancel", function () {

    let subscriptionCancelTitle =$("subscriptionCancelTitle").val();
    let subscriptionCancelText = $("subscriptionCancelText").val();
    let stateSelect = $(this);
    Swal.fire({
        title: subscriptionCancelTitle,
        text: subscriptionCancelText,
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#3085d6',
        cancelButtonColor: '#d33',
        confirmButtonText: $('cancelConfirmButtonText').val(),
        cancelButtonText: $('#cancelButtonText').val()
    }).then((result) => {
        if (result.value) {
            stateSelect.closest('form').submit();
        }
    })
});

$(document).on("click", "a.delete", function () {
    const selector = $(this);
    const isReload = $(this).data("reload");
    Swal.fire({
        title: 'Sure! You want to delete?',
        text: "You won't be able to revert this!",
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#3085d6',
        cancelButtonColor: '#d33',
        confirmButtonText: $('#deleteConfirmButtonText').val(),
        cancelButtonText: $('#cancelButtonText').val()
    }).then((result) => {
        if (result.value) {
            $.ajax({
                type: 'GET',
                url: $(this).data("url"),
                success: function (data) {
                    selector.closest('.removable-item').fadeOut('fast');
                    Swal.fire({
                        title: 'Deleted',
                        html: ' <span style="color:red">Deleted Successfully</span> ',
                        timer: 2000,
                        icon: 'success'
                    })

                    if (typeof isReload != 'undefined') {
                        location.reload();
                    }
                }
            })
        }
    })
});

$(document).on("click", ".statusChange", function () {
    let url = this.dataset.url;
    let id = this.dataset.id;
    let status = this.dataset.status;
    Swal.fire({
        title: 'Sure! You want to change status?',
        text: "You won't be able to revert this!",
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#3085d6',
        cancelButtonColor: '#d33',
        confirmButtonText: $('#cancelConfirmButtonText').val(),
        cancelButtonText: $('#cancelButtonText').val()
    }).then((result) => {
        if (result.value) {
            $.ajax({
                type: 'GET',
                url: url,
                data: { 'id': id, 'status': status },
                success: function (data) {
                    Swal.fire({
                        title: 'Changed',
                        html: ' <span style="color:red">Status has been Changed</span> ',
                        timer: 2000,
                        icon: 'success'
                    })
                    toastr.success(data.message);
                    location.reload()
                },
                error: function (error) {
                    toastr.error(error.responseJSON.message)
                }
            })
        } else if (result.dismiss === "cancel") {
            Swal.fire(
                "Cancelled",
                "Your imaginary file is safe :)",
                "error"
            )
        }
    })
});

$(document).on("input", "#topSearch", function (e) {
    commonAjax('GET', $('#topSearchRoute').val(), topSearchRes, topSearchRes, { 'keyword': $(this).val() });
    function topSearchRes(response) {
        if (response.status == true) {
            $('#topSearchContent').html(response.data)
        } else {
            $('#topSearchContent').html('')
        }
    }
})

window.getEditModal = function (url, modalId, callbackFunc) {
    $.ajax({
        type: 'GET',
        url: url,
        success: function (data) {
            $(modalId).find('.modal-content').html(data);

            $(modalId).modal('toggle');

            if ($(modalId).find('.selectpicker').length) {
                $(modalId).find('.selectpicker').selectpicker();
            }

            if (typeof callbackFunc !== 'undefined' && typeof window[callbackFunc] === 'function') {
                window[callbackFunc]();
            }
        },
        error: function (error) {
            toastr.error(error.responseJSON.message);
        }
    });
}

function visualNumberFormat(value) {
    try {
        if (value == null || value == undefined || isNaN(value) || value == '') {
            return '0.00';
        }
        value = parseFloat(value);
        if (Number.isInteger(value)) {
            return value.toFixed(2);
        }
        const temp = value.toFixed(8);
        const number = temp.split('.');
        let floatValue = number[1];
        floatValue = floatValue.toString();
        const result = floatValue.replace(/[0]+$/, '');
        if (result.length < 2) {
            return value.toFixed(2);
        }

        return `${number[0]}.${result}`;
    } catch (e) {
        return '';
    }
}
window.visualNumberFormat = visualNumberFormat;
