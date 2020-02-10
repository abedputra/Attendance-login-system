<div class="col-lg-4 col-lg-offset-4">
    <?php
    $getqrFirstName = trim($this->input->post('firstname'));
    $getqrLastName = trim($this->input->post('lastname'));
    $userDetails = trim($this->input->post('user-details'));
    $getqr = trim($this->input->post('qr'));

    if ($userDetails === 1) {
        $userDetailsStyle = 'style="display:block !important"';
        $userWithoutDetails = 'style="display:none !important"';
        $userDetailsCheck = 'checked';
        $userWithoutDetailsCheck = '';
    } else if ($userDetails === 0) {
        $userDetailsStyle = 'style="display:none !important"';
        $userWithoutDetails = 'style="display:block !important"';
        $userDetailsCheck = '';
        $userWithoutDetailsCheck = 'checked';
    } else {
        $userDetailsStyle = '';
        $userWithoutDetails = '';
        $userDetailsCheck = 'checked';
        $userWithoutDetailsCheck = '';
    }

    if (empty($getqr) && empty($getqrLastName)) {
        ?>
        <h2>Generate QR Code</h2>
        <hr>
        <p>Please select</p>
        <div class="radio">
            <label>
                <input type="radio" name="type" value="0" <?php echo $userDetailsCheck; ?>>
                Get QR without save the user details
            </label>
        </div>
        <div class="radio">
            <label>
                <input type="radio" name="type" value="1" <?php echo $userWithoutDetailsCheck; ?>>
                Get QR with save the user details
            </label>
        </div>
        <hr>
        <br>

        <div class="with-user" style="display: none">
            <h5>Please enter the required information below, to generate QR.</h5>
            <?php
            $fattr = array('class' => 'form-signin');
            echo form_open(site_url() . 'qr/generateqr/', $fattr);
            ?>
            <input type="hidden" name="user-details" value="1">
            <div class="form-group">
                <?php echo form_input(array('name' => 'firstname', 'id' => 'firstname', 'placeholder' => 'First Name', 'class' => 'form-control', 'value' => set_value('firstname'))); ?>
                <?php echo form_error('firstname'); ?>
            </div>
            <div class="form-group">
                <?php echo form_input(array('name' => 'lastname', 'id' => 'lastname', 'placeholder' => 'Last Name', 'class' => 'form-control', 'value' => set_value('lastname'))); ?>
                <?php echo form_error('lastname'); ?>
            </div>
            <div class="form-group">
                <?php echo form_input(array('type' => 'email', 'name' => 'email', 'id' => 'email', 'placeholder' => 'Email', 'class' => 'form-control', 'value' => set_value('email'))); ?>
                <?php echo form_error('email'); ?>
            </div>
            <div class="form-group">
                <?php
                $dd_list = array(
                    '1' => 'Admin',
                    '2' => 'User',
                    '3' => 'Subscriber',
                );
                $dd_name = "role";
                echo form_dropdown($dd_name, $dd_list, set_value($dd_name), 'class = "form-control" id="role"');
                ?>
            </div>
            <div class="form-group">
                <?php echo form_password(array('name' => 'password', 'id' => 'password', 'placeholder' => 'Password', 'class' => 'form-control', 'value' => set_value('password'))); ?>
                <?php echo form_error('password') ?>
            </div>
            <div class="form-group">
                <?php echo form_password(array('name' => 'passconf', 'id' => 'passconf', 'placeholder' => 'Confirm Password', 'class' => 'form-control', 'value' => set_value('passconf'))); ?>
                <?php echo form_error('passconf') ?>
            </div>

            <div class="form-group">
                <?php echo form_submit(array('value' => 'Save', 'class' => 'btn btn-lg btn-primary btn-block')); ?>
            </div>
            <?php echo form_close(); ?>
        </div>

        <div class="without-user">
            <?php
            $frattr = array('class' => 'form-signin');
            echo form_open(site_url() . 'qr/generateqr/', $frattr);
            ?>
            <div class="form-group">
                <?php echo form_input(array('name' => 'qr', 'id' => 'qr', 'placeholder' => 'Your Employee Full Name', 'class' => 'form-control', 'value' => set_value('qr'))); ?>
                <?php echo form_error('name'); ?>
            </div>
            <input type="hidden" name="user-details" value="0">
            <div class="form-group">
                <?php echo form_submit(array('value' => 'Get it', 'class' => 'btn btn-lg btn-primary btn-block')); ?>
            </div>
            <?php echo form_close(); ?>
        </div>

        <?php
    }
    ?>

    <div class="with-user" <?php echo $userDetailsStyle; ?>>

        <?php
        $qr = "{'name':'" . $getqrFirstName . " " . $getqrLastName . "'}";
        if (!empty($getqrFirstName) && !empty($getqrLastName) && $userDetails == 1) {
            ?>
            <h2>Generate QR Code</h2>
            <hr>
            <h5><b>Detail Your Employee:</b></h5>
            <p>Name : <?php echo $getqrFirstName . " " . $getqrLastName; ?> </p>
            <p>Email : <?php echo $this->input->post('email'); ?> </p>
            <p>Pass : ****** </p>
            <br>
            <br>
            <?php
            echo '<div>';
            echo '<img class="img-responsive img-thumbnail" src="https://chart.googleapis.com/chart?chs=200x200&cht=qr&chl=' . $qr . '&choe=UTF-8" style="margin: 0 auto;display: block;">';
            echo '<br><a href="https://chart.googleapis.com/chart?chs=400x400&cht=qr&chl=' . $qr . '&choe=UTF-8" target="_blank"><button class="btn btn-primary" style="margin: 0 auto;display: block;">Save</button></a>';
            echo '</div>';
        }
        ?>
    </div>

    <div class="without-user" <?php echo $userWithoutDetails; ?>>
        <?php
        $qr = "{'name':'" . $getqr . "'}";

        if (!empty($getqr) && $userDetails == 0) {
            echo '<div style="margin-top:100px;">';
            echo '<img class="img-responsive img-thumbnail" src="https://chart.googleapis.com/chart?chs=200x200&cht=qr&chl=' . $qr . '&choe=UTF-8" style="margin: 0 auto;display: block;">';
            echo '<br><a href="https://chart.googleapis.com/chart?chs=400x400&cht=qr&chl=' . $qr . '&choe=UTF-8" target="_blank"><button class="btn btn-primary" style="margin: 0 auto;display: block;">Save</button></a>';
            echo '</div>';
        }
        ?>
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

    $('input[type=radio][name=type]').change(function () {
        if (this.value == 0) {
            $('.with-user').hide();
            $('.without-user').show();
        } else if (this.value == 1) {
            $('.without-user').hide();
            $('.with-user').show();
        }
    });
</script>
</body>
</html>

