$(document).on('change', '#property_id', function () {
    var property_id = $(this).val()
    commonAjax('GET', $('#getPropertyUnitsRoute').val(), getUnitsRes, getUnitsRes, { 'property_id': property_id });
});

function getUnitsRes(response) {
    var html = '<option value="">--Select Option--</option>';
    Object.entries(response.data).forEach((unit) => {
        html += '<option value="' + unit[1].id + '">' + unit[1].unit_name + '</option>';
    });
    $('#unit_id').html(html);
}

$(document).on('click', '#searchBtn', function () {
    var url = $('#expenseReportRoute').val() + '?start_date=' + $('#start_date').val() + '&end_date=' + $('#end_date').val() + '&property_id=' + $('#property_id').val() + '&unit_id=' + $('#unit_id').val();
    dt.ajax.url(url).load();
});

var dt = $('#allExpenseDataTable').DataTable({
    processing: true,
    serverSide: true,
    responsive: true,
    ajax: $('#expenseReportRoute').val() + '?start_date=&end_date=&property_id=&unit_id=',
    order: [1, 'desc'],
    ordering: false,
    autoWidth: false,
    lengthMenu: [
        [10, 25, 50, 100, -1],
        [10, 25, 50, 100, 'All'],
    ],
    drawCallback: function () {
        $(".dataTables_length select").addClass("form-select form-select-sm");
    },
    language: {
        'paginate': {
            'previous': '<span class="iconify" data-icon="icons8:angle-left"></span>',
            'next': '<span class="iconify" data-icon="icons8:angle-right"></span>'
        }
    },
    dom: '<"row"<"col-sm-4"l><"col-sm-4"B><"col-sm-4"f>>tr<"bottom"<"row"<"col-sm-6"i><"col-sm-6"p>>><"clear">',
    buttons: [{
        extend: 'excel',
        className: 'theme-btn theme-button1 default-hover-btn'
    },
    {
        extend: 'pdf',
        className: 'theme-btn theme-button1 default-hover-btn'
    },
    {
        extend: 'copy',
        className: 'theme-btn theme-button1 default-hover-btn'
    }
    ],
    columnDefs: [
        { className: "text-center", targets: [1, 2, 3, 4] },
        { className: "text-end", targets: [5] }
    ],
    footerCallback: function (row, data, start, end, display) {
        var api = this.api();
        // Remove the formatting to get integer data for summation
        var intVal = function (num) {
            var val = 0;
            if (!!num[0].trim() && !isNaN(+num[0])) {
                val = num.split(' ')[0];
            } else {
                val = num.split(' ')[1];
            }
            return Number(val.replace(',', ''));
        };
        var totalAmount = 0;
        for (var i = 0; i < data.length; i++) {
            totalAmount += parseFloat(intVal(data[i]['amount']));
        }
        $(api.column(4).footer()).html('All Total : ' + currencyPrice(api.ajax.json().total));
        $(api.column(5).footer()).html('Page Total : ' + currencyPrice(totalAmount));
    },
    columns: [
        { "data": 'DT_RowIndex', "name": 'DT_RowIndex', orderable: false, searchable: false, },
        { "data": "name", "name": "expenses.name" },
        { "data": "property", "name": "properties.name" },
        { "data": "unit", "name": "property_units.unit_name" },
        { "data": "date", "name": "expenses.created_at" },
        { "data": "amount", "name": "expenses.total_amount" }
    ]
});

