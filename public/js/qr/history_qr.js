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
        processing: true,
        serverSide: false,
        ajax: {
            "url": baseUrl + "qr/dataTableJson",
            "type": "POST"
        },
        order: [[1, 'asc']],
    });

    table.on('order.dt search.dt', function () {
        table.column(0, {search: 'applied', order: 'applied'}).nodes().each(function (cell, i) {
            cell.innerHTML = i + 1;
        });
    }).draw();

    $(".form-control.input-sm").attr("placeholder", "Search multi columns");
});