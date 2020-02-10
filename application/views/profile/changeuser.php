<div class="col-lg-4 col-lg-offset-4">
    <h2>Change Profile</h2>
    <h5>Hello <span><?php echo $first_name; ?></span>.</h5>
    <?php
    $fattr = array('class' => 'form-signin');
    echo form_open(site_url() . 'profileuser/edituser/', $fattr); ?>

    <div class="form-group">
        <?php echo form_input(array('name' => 'firstname', 'id' => 'firstname', 'placeholder' => 'First Name', 'class' => 'form-control', 'value' => set_value('firstname', $groups->first_name))); ?>
        <?php echo form_error('firstname'); ?>
    </div>
    <div class="form-group">
        <?php echo form_input(array('name' => 'lastname', 'id' => 'lastname', 'placeholder' => 'Last Name', 'class' => 'form-control', 'value' => set_value('lastname', $groups->last_name))); ?>
        <?php echo form_error('lastname'); ?>
    </div>
    <div class="form-group">
        <?php echo form_input(array('type' => 'email', 'name' => 'email', 'id' => 'email', 'placeholder' => 'Email', 'class' => 'form-control', 'value' => set_value('email', $groups->email))); ?>
    </div>
    <div class="checkbox">
        <label>
            <input id="checkbox-pass" type="checkbox"> Change Password?
        </label>
    </div>
    <div id="hide-pass" style="display: none;">
        <div class="form-group">
            <?php echo form_password(array('name' => 'password', 'id' => 'password', 'placeholder' => 'Password', 'class' => 'form-control', 'value' => set_value('password'))); ?>
            <?php echo form_error('password') ?>
        </div>
        <div class="form-group">
            <?php echo form_password(array('name' => 'passconf', 'id' => 'passconf', 'placeholder' => 'Confirm Password', 'class' => 'form-control', 'value' => set_value('passconf'))); ?>
            <?php echo form_error('passconf') ?>
        </div>
    </div>
    <?php echo form_submit(array('value' => 'Change', 'class' => 'btn btn-lg btn-primary btn-block')); ?>
    <?php echo form_close(); ?>
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
<link rel="stylesheet"
      href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.4.1/css/bootstrap-datepicker3.css"/>
<script>

    $('#checkbox-pass').change(function () {
        if ($(this).is(":checked")) {
            $('#hide-pass').css('display', 'block');
            $('#check-change-pass').val('yes');
        } else {
            $('#hide-pass').css('display', 'none');
            $('#check-change-pass').val('no');
        }
    });
</script>
</body>
</html>
