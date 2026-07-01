$(document).ready(function () {
    $("#allDataTable").DataTable({
        language: {
            paginate: {
                previous: "<i class='mdi mdi-chevron-left'>",
                next: "<i class='mdi mdi-chevron-right'>",
            },
        },
        pageLength: 10,
        responsive: true,
        order: [1, 'desc'],
        ordering: false,
        autoWidth: false,
        drawCallback: function () {
            $(".dataTables_length select").addClass("form-select form-select-sm");
        },
        dom: '<"tableTop"<"row align-items-center"<"col-xl-6 col-lg-12 col-md-6"<"tableSearch float-start"f>><"col-xl-6 col-lg-12 col-md-6"<"tableLengthInput float-end"l>>>><"clear">',
    });
});
