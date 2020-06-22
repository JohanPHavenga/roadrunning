$(document).ready(function () {
    $('#datatable_date').DataTable({
        order: [[0, "desc"]],
        responsive: true
    });
    $('#datatable').DataTable({
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
});