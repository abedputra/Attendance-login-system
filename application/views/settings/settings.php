<div class="container">
    <div class="row">
        <div class="col-md-4 col-lg-4 col-lg-offset-2">
            <h2>Settings</h2>
            <h5>Hello <span><?php echo $first_name; ?></span>.</h5>
            <hr>
            <?php
            $fattr = array('class' => 'form-signin');
            echo form_open(site_url() . 'settings/', $fattr);

            function tz_list()
            {
                $zones_array = array();
                $timestamp = time();
                foreach (timezone_identifiers_list() as $key => $zone) {
                    date_default_timezone_set($zone);
                    $zones_array[$key]['zone'] = $zone;
                }
                return $zones_array;
            }

            ?>

            <?php echo '<input type="hidden" name="id" value="' . $id . '">'; ?>
            <div class="form-group">
                <span>Start Time</span>
                <?php echo form_input(array('name' => 'start_time', 'id' => 'start_time', 'placeholder' => 'Start', 'class' => 'form-control', 'value' => set_value('start_time', $start))); ?>
                <?php echo form_error('start_time'); ?>
            </div>
            <div class="form-group">
                <span>Out Time</span>
                <?php echo form_input(array('name' => 'out_time', 'id' => 'out_time', 'placeholder' => 'Out', 'class' => 'form-control', 'value' => set_value('out_time', $out))); ?>
                <?php echo form_error('out_time'); ?>
            </div>
            <div class="form-group">
                <span>How Many Employee</span>
                <?php echo form_input(array('name' => 'many_employee', 'id' => 'many_employee', 'placeholder' => 'How many employee', 'class' => 'form-control', 'value' => set_value('many_employee', $many_employee))); ?>
                <?php echo form_error('many_employee'); ?>
            </div>
            <div class="form-group">
                <span>Key</span>
                <?php echo form_input(array('name' => 'key', 'id' => 'key', 'placeholder' => 'KEY', 'class' => 'form-control', 'value' => set_value('key', $key))); ?>
                <?php echo form_error('key') ?>
            </div>
            <span>Recaptcha</span>
            <select name="recaptcha" id="recaptcha" class="form-control">
                <?php
                if ($recaptcha == 0) {
                    echo '
            <option value="0" selected>No</option>
            <option value="1">Yes</option>
            ';
                } else {
                    echo '
            <option value="0">No</option>
            <option value="1" selected>Yes</option>
            ';
                }
                ?>
            </select>
            <span>Timezone</span>
            <select name="timezone" id="timezone" class="form-control">
                <option value="<?php echo $timezonevalue; ?>"><?php echo $timezone; ?></option>
                <?php foreach (tz_list() as $t) { ?>
                    <option value="<?php echo $t['zone']; ?>"> <?php echo $t['zone']; ?></option>
                <?php } ?>
            </select>
            <?php echo form_submit(array('value' => 'Save', 'name' => 'submit', 'class' => 'btn btn-primary btn-block', 'style' => 'margin-right:10px')); ?>
            <?php echo form_close(); ?>
            <button onclick="myFunction()" class="btn btn-default btn-block" style="margin-top: 10px">Get New Key
            </button>
        </div>
        <div class="col-md-4 col-lg-4">
            <h2>Share The Key</h2>
            <br>
            <hr>
            <?php
            function generateRandomString()
            {
                $characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
                $charactersLength = strlen($characters);
                $randomString = sha1($characters[rand(0, $charactersLength - 1)]);
                return $randomString;
            }

            // the QR
            $qr = "{'url':'" . base_url() . "', 'key':'" . $key . "'}";

            echo '<p>Share via: </p>';
            echo '<a href="whatsapp://send?text=' . $key . '" data-action="share/whatsapp/share"><i class="fa fa-whatsapp" aria-hidden="true"></i></a> ';
            echo '<a href="mailto:?subject=Share KEY&amp;body=The Key is: ' . $key . '" title="Share KEY"><i class="fa fa-envelope-o" aria-hidden="true"></i></a> ';
            echo '<a class="copy-text" data-clipboard-target="#key" href="#"><i class="fa fa-clipboard" aria-hidden="true"></i></a>';

            echo '<div style="margin-top:100px;">';
            echo '<img class="img-responsive img-thumbnail" src="https://chart.googleapis.com/chart?chs=200x200&cht=qr&chl=' . $qr . '&choe=UTF-8" style="margin: 0 auto;display: block;">';
            echo '<p style="text-align: center;margin-top: 10px;">The QR Code</p>';
            echo '</div>';
            ?>
            <script>
                function myFunction() {
                    document.getElementById("key").value = "<?php echo generateRandomString(); ?>";
                }
            </script>
        </div>
    </div>
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
<script src="https://cdn.jsdelivr.net/clipboard.js/1.5.12/clipboard.min.js"></script>
<script src="//ajax.googleapis.com/ajax/libs/jquery/1.11.1/jquery.min.js" type="text/javascript"></script>
<script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js"></script>
<script src="<?php echo base_url() . 'public/js/main.js' ?>"></script>
<script type="text/javascript"
        src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.4.1/js/bootstrap-datepicker.min.js"></script>
<script>
    $(function () {
        new Clipboard('.copy-text');
    });
</script>
</body>
</html>

