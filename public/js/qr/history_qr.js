var table;
$(document).ready(function () {

    $.fn.dataTableExt.oApi.fnPagingInfo = function (oSettings) {
        return {
            "iStart": oSettings._iDisplayStart,
            "iEnd": oSettings.fnDisplayEnd(),
            "iLength": oSettings._iDisplayLength,
            "iTotal": oSettings.fnRecordsTotal(),
            "iFilteredTotal": oSettings.fnRecordsDisplay(),
            "iPage": Math.ceil(oSettings._iDisplayStart / oSettings._iDisplayLength),
            "iTotalPages": Math.ceil(oSettings.fnRecordsDisplay() / oSettings._iDisplayLength)
        };
    };

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
        columns: [
            {"data": "id", width: 20},
            {
                "data": "name", width: 80, "render": function (data, type, row) {
                    var dataQr = "{'name':'" + data + "'}";
                    return '<img class="img-thumbnail clickSave" src="https://chart.googleapis.com/chart?chs=100x100&cht=qr&chl='+ dataQr +'&choe=UTF-8" style="margin: 0 auto;display: block;widht:200px !important;">';
                }
            },
            {"data": "name", width: 200},
            {"data": "action", "orderable": false, width: 80},
            {
                "data": "name", width: 80, "orderable": false, "render": function (data, type, row) {
                    var dataQr = "{'name':'" + data + "'}";
                    return '<a href="https://chart.googleapis.com/chart?chs=400x400&cht=qr&chl=' + dataQr + '&choe=UTF-8" target="_blank"><button class="btn btn-primary btn-sm">Save</button></a>';
                }
            },
        ],
        order: [[2, 'asc']],
        rowCallback: function (row, data, iDisplayIndex) {
            var info = this.fnPagingInfo();
            var page = info.iPage;
            var length = info.iLength;
            $('td:eq(0)', row).html();
        }
    });

    table.on('order.dt search.dt', function () {
        table.column(0, {search: 'applied', order: 'applied'}).nodes().each(function (cell, i) {
            cell.innerHTML = i + 1;
        });
    }).draw();

    $(".form-control.input-sm").attr("placeholder", "Search multi columns");
});