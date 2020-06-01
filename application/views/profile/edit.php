<div class="col-lg-4 col-lg-offset-4">
    <h2>Edit Profile</h2>
    <h5>Edit your profile.</h5>
    <hr>
    <?php
    $fattr = array('class' => 'form-signin');
    echo form_open(site_url() . 'ProfileUser/edit/', $fattr); ?>

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
