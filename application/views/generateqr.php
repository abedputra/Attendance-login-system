<div class="col-lg-4 col-lg-offset-4">
      <?php
      $getqrFirstName = trim($this->input->post('firstname'));
      $getqrLastName = trim($this->input->post('lastname'));
      if(empty($getqr) && empty($getqrLastName)){
      ?>
        <h2>Generate QR Code</h2>
        <hr>
        <h5>Please enter the required information below, to generate QR for your employee.</h5>
        <?php
        $fattr = array('class' => 'form-signin');
        echo form_open(site_url().'main/generateqr/', $fattr);
        ?>
        <div class="form-group">
          <?php echo form_input(array('name'=>'firstname', 'id'=> 'firstname', 'placeholder'=>'First Name', 'class'=>'form-control', 'value' => set_value('firstname'))); ?>
          <?php echo form_error('firstname');?>
        </div>
        <div class="form-group">
          <?php echo form_input(array('name'=>'lastname', 'id'=> 'lastname', 'placeholder'=>'Last Name', 'class'=>'form-control', 'value'=> set_value('lastname'))); ?>
          <?php echo form_error('lastname');?>
        </div>
        <div class="form-group">
          <?php echo form_input(array('name'=>'email', 'id'=> 'email', 'placeholder'=>'Email', 'class'=>'form-control', 'value'=> set_value('email'))); ?>
          <?php echo form_error('email');?>
        </div>
        <div class="form-group">
        <?php
            $dd_list = array(
                      '1'   => 'Admin',
                      '2'   => 'Author',
                      '3'   => 'Employee',
                      '4'   => 'Subscriber',
                    );
            $dd_name = "role";
            echo form_dropdown($dd_name, $dd_list, set_value($dd_name),'class = "form-control" id="role"');
        ?>
        </div>
        <div class="form-group">
          <?php echo form_password(array('name'=>'password', 'id'=> 'password', 'placeholder'=>'Password', 'class'=>'form-control', 'value' => set_value('password'))); ?>
          <?php echo form_error('password') ?>
        </div>
        <div class="form-group">
          <?php echo form_password(array('name'=>'passconf', 'id'=> 'passconf', 'placeholder'=>'Confirm Password', 'class'=>'form-control', 'value'=> set_value('passconf'))); ?>
          <?php echo form_error('passconf') ?>
        </div>

        <div class="form-group">
        <?php echo form_submit(array('value'=>'Save', 'class'=>'btn btn-lg btn-primary btn-block')); ?>
        </div>
        <?php echo form_close();
      }
        ?>

        <?php
            $qr = "{'name':'".$getqrFirstName." ".$getqrLastName."'}";
            if(!empty($getqrFirstName) && !empty($getqrLastName)){
              ?>
              <h2>Generate QR Code</h2>
              <hr>
              <h5><b>Detail Your Employee:</b></h5>
              <p>Name : <?php echo $getqrFirstName." ".$getqrLastName;?> </p>
              <p>Email : <?php echo $this->input->post('email');?> </p>
              <p>Pass : ****** </p>
              <br>
              <br>
              <?php
                echo '<div>';
                echo '<img class="img-responsive img-thumbnail" src="https://chart.googleapis.com/chart?chs=200x200&cht=qr&chl='.$qr.'&choe=UTF-8" style="margin: 0 auto;display: block;">';
                echo '<br><a href="https://chart.googleapis.com/chart?chs=400x400&cht=qr&chl='.$qr.'&choe=UTF-8" target="_blank"><button class="btn btn-primary" style="margin: 0 auto;display: block;">Save</button></a>';
                echo '</div>';
            }
        ?>
</div>
