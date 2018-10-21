<div class="col-lg-4 col-lg-offset-4">
      <?php
      $getqrFirstName = trim($this->input->post('firstname'));
      $getqrLastName = trim($this->input->post('lastname'));
      $userDetails = trim($this->input->post('user-details'));
      $getqr = trim($this->input->post('qr'));

      if($userDetails === 1){
        $userDetailsStyle = 'style="display:block !important"';
        $userWithoutDetails = 'style="display:none !important"';
        $userDetailsCheck = 'checked';
        $userWithoutDetailsCheck = '';
      }else if($userDetails === 0){
        $userDetailsStyle = 'style="display:none !important"';
        $userWithoutDetails = 'style="display:block !important"';
        $userDetailsCheck = '';
        $userWithoutDetailsCheck = 'checked';
      }else{
        $userDetailsStyle = '';
        $userWithoutDetails = '';
        $userDetailsCheck = 'checked';
        $userWithoutDetailsCheck = '';
      }

      if(empty($getqr) && empty($getqrLastName)){
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
          <h5>Please enter the required information below, to generate QR for your employee.</h5>
          <?php
          $fattr = array('class' => 'form-signin');
          echo form_open(site_url().'main/generateqr/', $fattr);
          ?>
          <input type="hidden" name="user-details" value="1">
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
          <?php echo form_close();?>
        </div>

        <div class="without-user">
          <?php
          $frattr = array('class' => 'form-signin');
          echo form_open(site_url().'main/generateqr/', $frattr);
          ?>
          <div class="form-group">
            <?php echo form_input(array('name'=>'qr', 'id'=>'qr', 'placeholder'=>'Your Employee Full Name', 'class'=>'form-control', 'value' => set_value('qr'))); ?>
            <?php echo form_error('name');?>
          </div>
          <input type="hidden" name="user-details" value="0">
          <div class="form-group">
            <?php echo form_submit(array('value'=>'Get it', 'class'=>'btn btn-lg btn-primary btn-block')); ?>
          </div>
          <?php echo form_close(); ?>
        </div>

        <?php
        }
        ?>

        <div class="with-user" <?php echo $userDetailsStyle; ?>>

        <?php
            $qr = "{'name':'".$getqrFirstName." ".$getqrLastName."'}";
            if(!empty($getqrFirstName) && !empty($getqrLastName) && $userDetails == 1){
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

      <div class="without-user" <?php echo $userWithoutDetails; ?>>
        <?php
            $qr = "{'name':'".$getqr."'}";
            
            if(!empty($getqr)  && $userDetails == 0){
                echo '<div style="margin-top:100px;">';
                echo '<img class="img-responsive img-thumbnail" src="https://chart.googleapis.com/chart?chs=200x200&cht=qr&chl='.$qr.'&choe=UTF-8" style="margin: 0 auto;display: block;">';
                echo '<br><a href="https://chart.googleapis.com/chart?chs=400x400&cht=qr&chl='.$qr.'&choe=UTF-8" target="_blank"><button class="btn btn-primary" style="margin: 0 auto;display: block;">Save</button></a>';
                echo '</div>';
            }
        ?>
      </div>

</div>
