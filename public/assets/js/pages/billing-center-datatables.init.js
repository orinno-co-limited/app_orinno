$(document).ready(function () {
  $("#datatableBilling1").DataTable({
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

  $("#datatableBilling2").DataTable({
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

  $("#datatableBilling3").DataTable({
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

  $("#datatableBilling4").DataTable({
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