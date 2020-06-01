<div class="row" style="margin-bottom: 20px;">
    <div class="col-md-8">
        <h2>Hello There!</h2>
        <h5>Please enter the required information below.</h5>
    </div>
    <div class="col-md-4" style=" text-align: center; line-height: 99px;">
        <img src="<?php echo base_url(); ?>public/image/ic_launcher.png" width="80">
    </div>
</div>
<hr>
<?php
$fattr = array('class' => 'form-signin');
echo form_open('/main/register', $fattr);
?>
<div class="form-group">
    <?php echo form_input(array('name' => 'firstname', 'id' => 'firstname', 'placeholder' => 'First Name', 'class' => 'form-control', 'value' => set_value('firstname'))); ?>
    <?php echo form_error('firstname'); ?>
</div>
<div class="form-group">
    <?php echo form_input(array('name' => 'lastname', 'id' => 'lastname', 'placeholder' => 'Last Name', 'class' => 'form-control', 'value' => set_value('lastname'))); ?>
    <?php echo form_error('lastname'); ?>
</div>
<div class="form-group">
    <?php echo form_input(array('name' => 'email', 'id' => 'email', 'placeholder' => 'Email', 'class' => 'form-control', 'value' => set_value('email'))); ?>
    <?php echo form_error('email'); ?>
</div>
<?php
if ($recaptcha == 1) {
    echo '
  <div style="text-align:center;" class="form-group">
      <div style="display: inline-block;">' . $this->recaptcha->render() . '</div>
  </div>
  ';
}
echo form_submit(array('value' => 'Sign up', 'class' => 'btn btn-lg btn-primary btn-block')); ?>
<?php echo form_close(); ?>
<br>
<p>Registered? <a href="<?php echo site_url(); ?>main/login">Login</a></p>
