var TableDatatablesManaged = function () {

    var initEventsTable = function () {
        var table = $('#events_table');
        table.dataTable({
            order: [[3, "desc"]],
            responsive: true,
            bStateSave: true,
            columnDefs: [
                {orderable: false, targets: [6]},
                {searchable: false, targets: [6]},
                {responsivePriority: 1, targets: 0},
                {responsivePriority: 2, targets: -1},
                {responsivePriority: 3, targets: 1}

            ]
        });
        table.on('change', 'tbody tr .checkboxes', function () {
            $(this).parents('tr').toggleClass("active");
        });
    };

    var initEditionsTable = function () {
        var table = $('#editions_table');
        table.dataTable({
            order: [[4, "desc"]],
            responsive: true,
            bStateSave: true,
            columnDefs: [
                {orderable: false, targets: [6]},
                {searchable: false, targets: [6]},
                {responsivePriority: 1, targets: 0},
                {responsivePriority: 2, targets: -1},
                {responsivePriority: 3, targets: 1},
                {responsivePriority: 4, targets: 4}
            ]
        });
    };

    var initRacesTable = function () {
        var table = $('#races_table');
        table.dataTable({
            order: [[0, "desc"]],
            responsive: true,
            bStateSave: true,
            columnDefs: [
                {orderable: false, targets: [3, 4, -1]},
                {searchable: false, targets: [-1]},
                {responsivePriority: 1, targets: 0},
                {responsivePriority: 2, targets: -1},
                {responsivePriority: 3, targets: 1}
            ]
        });
    };

    var initUsersTable = function () {
        var table = $('#users_table');
        table.dataTable({
            order: [[0, "desc"]],
            responsive: true,
            columnDefs: [
                {orderable: false, targets: [5]},
                {searchable: false, targets: [5]},
                {responsivePriority: 1, targets: 0},
                {responsivePriority: 2, targets: -1},
                {responsivePriority: 3, targets: 3}

            ]
        });
    };

    var initClubsTable = function () {
        var table = $('#clubs_table');
        table.dataTable({
            order: [[1, "asc"]],
            responsive: true,
            columnDefs: [
                {orderable: false, targets: [6]},
                {searchable: false, targets: [6]},
                {responsivePriority: 1, targets: 0},
                {responsivePriority: 2, targets: -1},
                {responsivePriority: 3, targets: 1}

            ]
        });
    };

    var initSponsorsTable = function () {
        var table = $('#sponsors_table');
        table.dataTable({
            order: [[1, "asc"]],
            responsive: true,
            columnDefs: [
                {orderable: false, targets: [3]},
                {searchable: false, targets: [3]},
                {responsivePriority: 1, targets: 0},
                {responsivePriority: 2, targets: -1},
                {responsivePriority: 3, targets: 1}
            ]
        });
    };

    var initParkRunsTable = function () {
        var table = $('#parkruns_table');
        table.dataTable({
            order: [[1, "asc"]],
            responsive: true,
            columnDefs: [
                {orderable: false, targets: [6]},
                {searchable: false, targets: [6]},
                {responsivePriority: 1, targets: 0},
                {responsivePriority: 2, targets: -1},
                {responsivePriority: 3, targets: 1}
            ]
        });
    };

    // Search  
    var initSearchTable = function () {
        var table = $('#search_table');
        table.dataTable({
            order: [[3, "desc"]],
            responsive: true,
            columnDefs: [
                {orderable: false, targets: [5]},
                {searchable: false, targets: [5]},
                {responsivePriority: 1, targets: 0},
                {responsivePriority: 2, targets: -1},
                {responsivePriority: 3, targets: 1}

            ],
        });
    };

    // Email Que  
    var initEmailQueTable = function () {
        var table = $('#emailques_table');
        table.dataTable({
            order: [[4, "desc"]],
            responsive: true,
            columnDefs: [
                {orderable: false, targets: [-1]},
                {searchable: false, targets: [-1]},
                {responsivePriority: 1, targets: 0},
                {responsivePriority: 2, targets: -1},
                {responsivePriority: 3, targets: 1},
                {responsivePriority: 4, targets: 4},
                {responsivePriority: 5, targets: 2}

            ],
        });
    };

    // Email Templates  
    var initEmailTemplateTable = function () {
        var table = $('#emailtemplates_table');
        table.dataTable({
            order: [[1, "asc"]],
            responsive: true,
            columnDefs: [
                {orderable: false, targets: [-1]},
                {searchable: false, targets: [-1]},
                {responsivePriority: 1, targets: 0},
                {responsivePriority: 2, targets: -1}
            ]
        });
    };

    // Email Merges 
    var initEmailMergesTable = function () {
        var table = $('#emailmerges_table');
        table.dataTable({
            order: [[4, "desc"]],
            responsive: true,
            columnDefs: [
                {orderable: false, targets: [-1]},
                {searchable: false, targets: [0, 3, -1]},
                {responsivePriority: 1, targets: 0},
                {responsivePriority: 2, targets: -1},
                {responsivePriority: 3, targets: 3}
            ]
        });
    };

    // History Summary Table
    var initHistorySummaryTable = function () {
        var table = $('#history_summary_table');
        table.dataTable({
            order: [[3, "desc"]],
            responsive: true,
            lengthMenu: [[10, 25, 50, -1], [10, 25, 50, "All"]],
            columnDefs: [
                {responsivePriority: 1, targets: 1},
                {responsivePriority: 2, targets: 3},
                {responsivePriority: 3, targets: 4},
                {responsivePriority: 4, targets: 5}
            ]
        });
    };

    var initEditionRacesTable = function () {
        var table = $('#edition_races_table');
        table.dataTable({
            order: [[1, "asc"]],
            responsive: true,
            paging: false,
            searching: false,
            info: false,
            columnDefs: [
                {orderable: false, targets: [4, -1]},
                {searchable: false, targets: [-1]},
                {responsivePriority: 1, targets: 0},
                {responsivePriority: 2, targets: -1},
                {responsivePriority: 3, targets: 1}
            ]
        });
    };

    var initEditionDatesTable = function () {
        var table = $('#edition_dates_table');
        table.dataTable({
            order: [[1, "asc"]],
            responsive: true,
            paging: false,
            searching: false,
            info: false,
            columnDefs: [
                {orderable: false, targets: [3, -1]},
                {searchable: false, targets: [-1]},
                {responsivePriority: 1, targets: 0},
                {responsivePriority: 2, targets: -1},
            ]
        });
    };

    var initEditionUrlTable = function () {
        var table = $('#edition_url_table');
        table.dataTable({
            order: [[1, "asc"]],
            responsive: true,
            paging: false,
            searching: false,
            info: false,
            columnDefs: [
                {orderable: false, targets: [3, -1]},
                {searchable: false, targets: [-1]},
                {responsivePriority: 1, targets: 0},
                {responsivePriority: 2, targets: -1},
            ]
        });
    };

    var initEditionFileTable = function () {
        var table = $('#edition_file_table');
        table.dataTable({
            order: [[1, "asc"]],
            responsive: true,
            paging: false,
            searching: false,
            info: false,
            columnDefs: [
                {orderable: false, targets: [3, -1]},
                {searchable: false, targets: [-1]},
                {responsivePriority: 1, targets: 0},
                {responsivePriority: 2, targets: -1},
            ]
        });
    };

    // DateType Table
    var initDateTypeTable = function () {
        var table = $('#datetype_table');
        table.dataTable({
            order: [[0, "asc"]],
            responsive: true,
            lengthMenu: [[25, 50, -1], [25, 50, "All"]],
            columnDefs: [
                {responsivePriority: 1, targets: 1},
                {responsivePriority: 2, targets: -1},
            ]
        });
    };
    
    // Result Table
    var initResultTable = function () {
        var table = $('#result_table');
        table.dataTable({
            order: [[3, "desc"], [1, "desc"], [0, "asc"]],
            responsive: true,
            lengthMenu: [[25, 50, -1], [25, 50, "All"]],
            columnDefs: [
                {orderable: false, targets: [-1]},
                {searchable: false, targets: [-1]},
                {responsivePriority: 1, targets: 1},
                {responsivePriority: 2, targets: -1},
                {responsivePriority: 3, targets: -2},
            ]
        });
    };
    
    // Result Search Table
    var initResultSearchTable = function () {
        var table = $('#result_search_table');
        table.dataTable({
            order: [[0, "asc"]],
            responsive: true,
            lengthMenu: [[10, 25, 50, -1], [10, 25, 50, "All"]],
            columnDefs: [
                {orderable: false, targets: [-1]},
                {searchable: false, targets: [-1]},
                {responsivePriority: 1, targets: 1},
                {responsivePriority: 2, targets: -1},
                {responsivePriority: 3, targets: -2},
            ]
        });
    };
    
    // Audit Table
    var initAuditTable = function () {
        var table = $('#audit_table');
        table.dataTable({
            order: [[0, "asc"]],
            responsive: true,
            lengthMenu: [[10, 25, 50, -1], [10, 25, 50, "All"]],
            bStateSave: true,
            columnDefs: [
                {orderable: false, targets: [-1]},
                {searchable: false, targets: [-1]},
                {responsivePriority: 1, targets: 1},
                {responsivePriority: 2, targets: -1},
                {responsivePriority: 3, targets: -2}
            ]
        });
    };
    
    // Result Audit Table
    var initResultAuditTable = function () {
        var table = $('#result_audit_table');
        table.dataTable({
            order: [[0, "desc"]],
            responsive: true,
            lengthMenu: [[10, 25, 50, -1], [10, 25, 50, "All"]],
            bStateSave: true,
            columnDefs: [
                {orderable: false, targets: [-1]},
                {searchable: false, targets: [-1]},
                {responsivePriority: 1, targets: 1},
                {responsivePriority: 2, targets: -1},
                {responsivePriority: 3, targets: -2}
            ]
        });
    };
    
     // Unconfirmed Data Table on Dashboard
    var initUnconfirmedDataTable = function () {
        var table = $('#unconfirmed_date_table');
        table.dataTable({
//            order: [[0, "asc"]],
            responsive: true,
            lengthMenu: [[10, 25, 50, -1], [10, 25, 50, "All"]],
            bStateSave: true,
            columnDefs: [
                {searchable: false, targets: [-1]},
                {responsivePriority: 1, targets: 0},
                {responsivePriority: 2, targets: -1},
                {responsivePriority: 3, targets: 1},
                {responsivePriority: 4, targets: 2},
                { type: 'date', 'targets': [0] }
            ]
        });
    };
    
    
    // GENERIC list table
    var initListTable = function () {
        var table = $('#list_table');
        table.dataTable({
            order: [[1, "asc"]],
            responsive: true,
            columnDefs: [
                {responsivePriority: 1, targets: 0},
                {responsivePriority: 2, targets: -1},
                {responsivePriority: 3, targets: 1}
            ]
        });
    };


    return {
        //main function to initiate the module
        init: function () {
            if (!jQuery().dataTable) {
                return;
            }
            initEventsTable();
            initEditionsTable();
            initRacesTable();
            initUsersTable();
            initClubsTable();
            initSponsorsTable();
            initParkRunsTable();
            initSearchTable();
            initEmailQueTable();
            initEmailTemplateTable();
            initEmailMergesTable();
            initHistorySummaryTable();
            initEditionRacesTable();
            initEditionDatesTable();
            initEditionUrlTable();
            initEditionFileTable();
            initDateTypeTable();
            initResultTable();
            initResultSearchTable();
            initAuditTable();
            initResultAuditTable();
            initUnconfirmedDataTable();
            initListTable();
        }
    };

}();

if (App.isAngularJsApp() === false) {
    jQuery(document).ready(function () {
        TableDatatablesManaged.init();
    });
}