<div class="row">
    <div class="col-lg-4">
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
                    Generate QR without save the user & can't login
                </label>
            </div>
            <div class="radio">
                <label>
                    <input type="radio" name="type" value="1" <?php echo $userWithoutDetailsCheck; ?>>
                    Generate QR save the user & user can login
                </label>
            </div>
            <hr>
            <br>

            <div class="with-user" style="display: none">
                <h5>Please enter the required information below, to generate QR.</h5>
                <?php
                $fattr = array('class' => 'form-signin');
                echo form_open(site_url() . 'qr/generateQr/', $fattr);
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
                    <?php echo form_submit(array('value' => 'Save & Generate', 'class' => 'btn btn-lg btn-primary btn-block')); ?>
                </div>
                <?php echo form_close(); ?>
            </div>

            <div class="without-user">
                <?php
                $frattr = array('class' => 'form-signin');
                echo form_open(site_url() . 'qr/generateQr/', $frattr);
                ?>
                <div class="form-group">
                    <?php echo form_input(array('name' => 'qr', 'id' => 'qr', 'placeholder' => 'User Full Name', 'class' => 'form-control', 'value' => set_value('qr'))); ?>
                    <?php echo form_error('name'); ?>
                </div>
                <input type="hidden" name="user-details" value="0">
                <div class="form-group">
                    <?php echo form_submit(array('value' => 'Generate', 'class' => 'btn btn-lg btn-primary btn-block')); ?>
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
                echo '<div style="text-align: center;margin-top: 20px;"><a href="https://chart.googleapis.com/chart?chs=400x400&cht=qr&chl=' . $qr . '&choe=UTF-8" target="_blank"><button class="btn btn-primary">Save</button></a> <a href="' . site_url() . 'qr/generateQr/"><button class="btn btn-success"> Generate Again</button></a></div>';
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
                echo '<br><div style="text-align: center;margin-top: 20px;"><a href="https://chart.googleapis.com/chart?chs=400x400&cht=qr&chl=' . $qr . '&choe=UTF-8" target="_blank"><button class="btn btn-primary">Save</button></a> <a href="' . site_url() . 'qr/generateQr/"><button class="btn btn-success"> Generate Again</button></a></div>';
                echo '</div>';
            }
            ?>
        </div>
    </div>
    <div class="col-lg-8">
        <h2>Import Data From CSV</h2>
        <hr>
        <p>Please select your CSV file</p>
        <form action="<?php echo site_url();?>qr/importData" method="post" enctype="multipart/form-data" style="margin-bottom: 20px;">
            <table>
                <tr>
                    <td>
                        <input type="file" class="form-control" name="import" id="import" align="center"/>
                    </td>
                    <td>
                        <button type="submit" name="submit" class="btn btn-primary">Import</button>
                    </td>
                </tr>
            </table>
        </form>

        <hr>
        <a href="<?php echo base_url() . 'public/uploads/template.csv' ?>" download><button class="btn btn-success"> Download Template CSV</button></a>
        <button type="button" class="btn btn-info" data-toggle="collapse" data-target="#instructions">Show Instructions </button>
        <div id="instructions" class="collapse">
            <img src="<?php echo base_url() . 'public/uploads/import_csv.png' ?>" class="img-responsive" style="margin-top: 20px;border: 1px solid #2c3e50;padding: 10px;">
        </div>
    </div>
</div>

