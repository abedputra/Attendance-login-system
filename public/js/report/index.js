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
        "serverSide": false,
        "responsive": true,
        "ajax": {
            "url": baseUrl + "report/dataTableJson",
            "type": "POST",
            "data": {"role": role, "name": name},
        },
        oLanguage: {
            sProcessing: "loading..."
        },
        dom: 'lBfrtip',
        buttons: [
            'csv', 'excel', 'print'
        ],
        order: [[2, 'desc'], [3, 'desc']],
    });

    // No. data
    table.on('order.dt search.dt', function () {
        table.column(0, {search: 'applied', order: 'applied'}).nodes().each(function (cell, i) {
            cell.innerHTML = i + 1;
        });
    }).draw();

    // Add datepicker for min input
    $('#min').datepicker({
        format: 'yyyy-mm-dd',
        todayHighlight: 'TRUE',
        autoclose: true,
        changeMonth: true,
        changeYear: true
    });

    // Add datepicker for max input
    $("#max").datepicker({
        format: 'yyyy-mm-dd',
        todayHighlight: 'TRUE',
        autoclose: true,
        changeMonth: true,
        changeYear: true
    });

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

    // Datatables for search with date
    $.fn.dataTable.ext.search.push(
        function (settings, data, dataIndex) {
            var min = $('#min').datepicker("getDate");
            var max = $('#max').datepicker("getDate");
            var startDate = new Date(data[2]);
            if (changeFormat(min) == null && changeFormat(max) == null) {
                return true;
            }
            if (changeFormat(min) == null && changeFormat(startDate) <= changeFormat(max)) {
                return true;
            }
            if (changeFormat(max) == null && changeFormat(startDate) >= changeFormat(min)) {
                return true;
            }
            if (changeFormat(startDate) <= max && changeFormat(startDate) >= changeFormat(min)) {
                return true;
            }
            return false;
        }
    );

    // Function change the format date
    function changeFormat(orginaldate) {
        if (orginaldate == null) {
            date = null;
        } else {
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
            var date = new Date(month + "/" + day + "/" + year);
        }

        return date;
    }

    // Redraw the datatables
    $('#min, #max').change(function () {
        table.draw();
    });
});