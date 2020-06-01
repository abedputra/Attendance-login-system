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
            "url": baseUrl + "report/dataTableJson",
            "type": "POST",
            "data": {"role": role, "name": name}
        },
        dom: 'lBfrtip',
        buttons: [
            'csv', 'excel', 'print'
        ],
        columns: [
            {"data": "id", width: 20},
            {"data": "name", width: 170},
            {"data": "date", width: 70},
            {"data": "in_time", width: 70},
            {"data": "out_time", width: 70},
            {"data": "work_hour", width: 70},
            {"data": "over_time", width: 70},
            {"data": "late_time", width: 70},
            {"data": "early_out_time", width: 70},
            {"data": "in_location", width: 170},
            {"data": "out_location", width: 170}
        ],
        order: [[2, 'desc'],[3, 'desc']],
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

    $(".dataTables_filter .input-sm").attr("placeholder", "Search multi columns");
    $(".dataTables_length").css("margin-right", "10px");

    // Datatables for search with date
    $.fn.dataTable.ext.search.push(
        function (settings, data, dataIndex) {
            var min = $('#min').datepicker("getDate");
            var max = $('#max').datepicker("getDate");
            var startDate = new Date(data[2]);
            if (changeFormat(min) == null && changeFormat(max) == null) { return true; }
            if (changeFormat(min) == null && changeFormat(startDate) <= changeFormat(max)) { return true;}
            if(changeFormat(max) == null && changeFormat(startDate) >= changeFormat(min)) {return true;}
            if (changeFormat(startDate) <= max && changeFormat(startDate) >= changeFormat(min)) { return true; }
            return false;
        }
    );

    // Function change the format date
    function changeFormat (orginaldate) {
        if(orginaldate == null){
            date = null;
        }else{
            var date = new Date(orginaldate);
            var day = date.getDate();
            var month = date.getMonth() + 1;
            var year = date.getFullYear();
            if (day < 10) {
                day = "0" + day;
            }
            if (month < 10) {
                month = "0" + month;
            }
            var date =  new Date(month + "/" + day + "/" + year);
        }

        return date;
    }

    // Add datepicker for min input
    $('#min').datepicker({
        format: 'yyyy-mm-dd',
        todayHighlight:'TRUE',
        autoclose: true,
        changeMonth: true,
        changeYear: true,
        onSelect: function () {
            table.draw();
        },
    });

    // Add datepicker for max input
    $("#max").datepicker({
        format: 'yyyy-mm-dd',
        todayHighlight:'TRUE',
        autoclose: true,
        changeMonth: true,
        changeYear: true,
        onSelect: function () {
            table.draw();
        },
    });

    // Redraw the datatables
    $('#min, #max').change(function () {
        table.draw();
    });

});