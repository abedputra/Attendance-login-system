<div class="row" style="margin-bottom: 20px;">
    <div class="col-md-8">
        <h2>Almost There!</h2>
        <h5>Hello <span><?php echo $firstName; ?></span>. <br>Your username is <span><?php echo $email; ?></span>.</h5>
        <small>Please enter a password to begin using the site.</small>
    </div>
    <div class="col-md-4" style=" text-align: center; line-height: 99px;">
        <img src="<?php echo base_url(); ?>public/image/ic_launcher.png" width="80">
    </div>
</div>
<hr>
<?php
$fattr = array('class' => 'form-signin');
echo form_open(site_url() . 'main/complete/token/' . $token, $fattr); ?>
<div class="form-group">
    <?php echo form_password(array('name' => 'password', 'id' => 'password', 'placeholder' => 'Password', 'class' => 'form-control', 'value' => set_value('password'))); ?>
    <?php echo form_error('password') ?>
</div>
<div class="form-group">
    <?php echo form_password(array('name' => 'passconf', 'id' => 'passconf', 'placeholder' => 'Confirm Password', 'class' => 'form-control', 'value' => set_value('passconf'))); ?>
    <?php echo form_error('passconf') ?>
</div>
<?php echo form_hidden('user_id', $user_id); ?>
<?php echo form_submit(array('value' => 'Complete', 'class' => 'btn btn-lg btn-primary btn-block')); ?>
<?php echo form_close(); ?>