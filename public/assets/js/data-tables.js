$(document).ready(function () {
    var table = $('#datatable_date').DataTable({
        order: [[0, "desc"]],
            responsive: true
    });  
    var table = $('#datatable').DataTable({
        buttons: [
            {
                extend: 'print',
                title: 'Test Data export',
                exportOptions: {
                    columns: "thead th:not(.noExport)"
                }
            }, {
                extend: 'pdf',
                title: 'Test Data export',
                exportOptions: {
                    columns: "thead th:not(.noExport)"
                }
            }, {
                extend: 'excel',
                title: 'Test Data export',
                exportOptions: {
                    columns: "thead th:not(.noExport)"
                }
            }, {
                extend: 'csv',
                title: 'Test Data export',
                exportOptions: {
                    columns: "thead th:not(.noExport)"
                }
            }, {
                extend: 'copy',
                title: 'Test Data export',
                exportOptions: {
                    columns: "thead th:not(.noExport)"
                }
            }
        ],
    });
    table.buttons().container().appendTo('#export_buttons');
    $("#export_buttons .btn").removeClass('btn-secondary').addClass('btn-light');
});