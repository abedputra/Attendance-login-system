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
                Qr Code
            </th>
            <th>
                Name
            </th>
            <th>
                Delete
            </th>
            <th>
                Save
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
            ajax: {
                "url": "<?php echo site_url('qr/dataTableJson'); ?>",
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
                        return '<a href="https://chart.googleapis.com/chart?chs=400x400&cht=qr&chl=' + dataQr + '&choe=UTF-8" target="_blank"><button class="btn btn-primary">Save</button></a>';
                    }
                },
            ],
            order: [[1, 'asc']],
            rowCallback: function (row, data, iDisplayIndex) {
                var info = this.fnPagingInfo();
                var page = info.iPage;
                var length = info.iLength;
                $('td:eq(0)', row).html();
            }
        });

        $(".form-control.input-sm").attr("placeholder", "Search multi columns");
    });
</script>
</body>
</html>


