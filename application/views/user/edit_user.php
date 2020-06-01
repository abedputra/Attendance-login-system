<div class="col-lg-4 col-lg-offset-4">
    <h2>Edit User</h2>
    <h5>Hi <span><?php echo $first_name; ?></span>, <br>Edit user.</h5>
    <hr>
    <?php $fattr = array('class' => 'form-signin');
    echo form_open(site_url() . 'user/edit/' . $id, $fattr); ?>

    <?php
    foreach ($groups as $row) {
        $id = $row->id;
        $first_name = $row->first_name;
        $last_name = $row->last_name;
        $email = $row->email;
        $role = $row->role;
    }
    ?>
    <div class="form-group">
        <?php echo form_input(array('name' => 'firstname', 'id' => 'firstname', 'placeholder' => 'First Name', 'class' => 'form-control', 'value' => $first_name)); ?>
        <?php echo form_error('firstname'); ?>
    </div>
    <div class="form-group">
        <?php echo form_input(array('name' => 'lastname', 'id' => 'lastname', 'placeholder' => 'Last Name', 'class' => 'form-control', 'value' => $last_name)); ?>
        <?php echo form_error('lastname'); ?>
    </div>
    <div class="form-group">
        <select class="form-control" name="email" id="email">
            <?php
            foreach ($groups as $row) {
                echo '<option value="' . $row->email . '">' . $row->email . '</option>';
            }
            ?>
        </select>
    </div>

    <div class="form-group">
        <?php

        $dd_list = array(
            '1' => 'Admin',
            '2' => 'User',
            '3' => 'Subscriber',
        );
        $dd_name = 'role';
        echo form_dropdown($dd_name, $dd_list, set_value($dd_name, $role), 'class = "form-control" id="role"');
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
    <?php echo form_submit(array('value' => 'Submit', 'class' => 'btn btn-lg btn-primary btn-block')); ?>
    <a href="<?php echo site_url() . 'user/'; ?>">
        <button type="button" class="btn btn-default btn-lg btn-block">Cancel</button>
    </a>
    <?php echo form_close(); ?>
</div>
