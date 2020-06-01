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
        "scrollX": true,
        ajax: {
            "url": baseUrl + "user/dataTableJson",
            "type": "POST"
        },
        dom: 'lBfrtip',
        buttons: [
            'csv', 'excel', 'print'
        ],
        columns: [
            {"data": "id", width: 30},
            {"data": "first_name", width: 170},
            {"data": "last_name", width: 170},
            {"data": "email", width: 170},
            {"data": "last_login", width: 170},
            {
                "data": "role", width: 80, "render": function (data, type, row) {
                    var dataRole;

                    if (data == 1)
                        dataRole = "Admin";
                    else if (data == 2)
                        dataRole = "User";
                    else
                        dataRole = "Subscribe";

                    return dataRole;
                }
            },
            {"data": "status", width: 30},
            {"data": "banned_users", width: 50},
            {"data": "change_role", width: 100, "orderable": false},
            {"data": "ban_user", width: 80, "orderable": false},
            {"data": "delete", width: 80, "orderable": false}
        ],
        order: [[1, 'asc']],
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
    $(".dataTables_length").css("margin-right", "10px");

});