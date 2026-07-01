$(document).on('input', '.quantity', function () {
    var quantity = $(this).val();
    if (parseInt(quantity) < 1) {
        quantity = 1;
    }
    var selector = $(this).closest('form');
    var per_monthly_price = selector.find('input[name=per_monthly_price]').val();
    var per_yearly_price = selector.find('input[name=per_yearly_price]').val();
    var totalPerMonthlyPrice = 0;
    var totalPerYearlyPrice = 0;
    if (parseInt(quantity) > 0) {
        totalPerMonthlyPrice = Number(per_monthly_price) * parseInt(quantity);
        totalPerYearlyPrice = Number(per_yearly_price) * parseInt(quantity);
    }
    var perMonthlyPriceDetails = currencyPrice(visualNumberFormat(per_monthly_price)) + '*' + parseInt(quantity) + '=' + currencyPrice(visualNumberFormat(totalPerMonthlyPrice));
    var perYearlyPriceDetails = currencyPrice(visualNumberFormat(per_monthly_price)) + '*' + parseInt(quantity) + '=' + currencyPrice(visualNumberFormat(totalPerYearlyPrice));
    selector.find('.per_monthly_price').text(perMonthlyPriceDetails);
    selector.find('.per_yearly_price').text(perYearlyPriceDetails);
});

$(document).on('change', '.quantity', function () {
    var quantity = $(this).val();
    if (parseInt(quantity) < 1) {
        $(this).val(1)
    } else {
        $(this).val(parseInt(quantity))
    }
});
