<div class="container">
    <h2>Report</h2>
    <h5>Hello <span><?php echo $first_name; ?></span>.</h5>
    <hr>

    <table id="data-table" class="display" cellspacing="0" width="100%">
        <thead>
        <tr>
            <th>
                ID
            </th>
            <th>
                Name
            </th>
            <th>
                Date
            </th>
            <th>
                In Time
            </th>
            <th>
                Out Time
            </th>
            <th>
                Work Hour
            </th>
            <th>
                Over Time
            </th>
            <th>
                Late Time
            </th>
            <th>
                Early Out Time
            </th>
            <th>
                In Location
            </th>
            <th>
                Out Location
            </th>
        </tr>
        </thead>
        <tbody>
        </tbody>
    </table>
</div>

</div><!--row-->

<footer>
    <div class="col-md-12" style="text-align:center;">
        <hr>
        Copyright&copy; - <?php echo date('Y'); ?> | Create by <a
                href="https://connectwithdev.com/">connectwithdev.com</a>
    </div>
</footer>
</div><!-- /container -->

<!-- Modal -->
<div class="modal fade" id="myModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span>
                </button>
                <h4 class="modal-title" id="myModalLabel">Attendance Login System</h4>
            </div>
            <div class="modal-body">
                <h2>Version</h2>
                <p>V3.0</p>
                <h2>About</h2>
                <p>Attendance login system is based on the <a
                            href="https://github.com/bcit-ci/CodeIgniter">codeigniter</a>.
                <p>If you have question, please email me : <a
                            href="mailto:abedputra@gmail.com">abedputra@gmail.com</a><br>
                    Visit: <a href="https://connectwithdev.com/page/contact" rel="nofollow">https://connectwithdev.com/</a></p>
                <h2>License</h2>
                <p>The MIT License (MIT).</p>
                <p>Copyright&copy; <?php echo date('Y'); ?>, Abed Putra.</p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>


<!-- /Load Js -->
<script src="https://code.jquery.com/jquery-1.12.4.js"></script>
<script src="https://cdn.jsdelivr.net/clipboard.js/1.5.12/clipboard.min.js"></script>
<script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js"></script>
<script src="<?php echo base_url() . 'public/js/main.js' ?>"></script>
<script type="text/javascript"
        src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.4.1/js/bootstrap-datepicker.min.js"></script>
<script type="text/javascript"
        src="https://cdn.datatables.net/r/dt/jq-2.1.4,jszip-2.5.0,pdfmake-0.1.18,dt-1.10.9,af-2.0.0,b-1.0.3,b-colvis-1.0.3,b-html5-1.0.3,b-print-1.0.3,se-1.0.1/datatables.min.js"></script>
<script src="https://cdn.datatables.net/1.10.13/js/dataTables.bootstrap.min.js"></script>
<script>

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
                "url": "<?php echo site_url('report/dataTableJson'); ?>",
                "type": "POST",
                "data": {"role": "<?php echo $role; ?>", "name": "<?php echo $name; ?>"}
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

        $(".form-control.input-sm").attr("placeholder", "Search multi columns");
        $(".dataTables_length").css("margin-right", "10px");

    });
</script>
</body>
</html>

