$(document).ready(function () {
  $("#datatableMaintenance1").DataTable({
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
    autoWidth:false,
    drawCallback: function () {
      $(".dataTables_length select").addClass("form-select form-select-sm");
    },
  });
  $("#datatableMaintenance2").DataTable({
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
    autoWidth:false,
    drawCallback: function () {
      $(".dataTables_length select").addClass("form-select form-select-sm");
    },
  });

});