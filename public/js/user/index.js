var table;
$(document).ready(function () {

    table = $("#data-table").DataTable({
        initComplete: function () {
            var api = this.api();
            $('#data-table_filter input')
                .off('.DT')
                .on('input.DT', function () {
                    api.search(this.value).draw();
                });
        },
        oLanguage: {
            sProcessing: "loading..."
        },
        "serverSide": false,
        "responsive": true,
        ajax: {
            "url": baseUrl + "user/dataTableJson",
            "type": "POST"
        },
        dom: 'lBfrtip',
        buttons: [
            'csv', 'excel', 'print'
        ],
        order: [[1, 'asc']],
    });

    table.on('order.dt search.dt', function () {
        table.column(0, {search: 'applied', order: 'applied'}).nodes().each(function (cell, i) {
            cell.innerHTML = i + 1;
        });
    }).draw();

    $(".dataTables_filter .input-sm").attr("placeholder", "Search multi columns");
    $(".dataTables_length").css("margin-right", "10px");
    // Style for CSV button
    $(".dt-button.buttons-csv.buttons-html5").removeClass('dt-button');
    $(".buttons-csv.buttons-html5").addClass('btn btn-info');
    // Style for Excel button
    $(".dt-button.buttons-excel.buttons-html5").removeClass('dt-button');
    $(".buttons-excel.buttons-html5").addClass('btn btn-success');
    // Style for Print button
    $(".dt-button.buttons-print").removeClass('dt-button');
    $(".buttons-print").addClass('btn btn-danger');

});