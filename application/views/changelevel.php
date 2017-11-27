<div class="col-lg-4 col-lg-offset-4">
    <h2>Change Level</h2>
    <h5>Hi <span><?php echo $first_name; ?></span>, <br>Please choose the level user.</h5>     
    <?php $fattr = array('class' => 'form-signin');
         echo form_open(site_url().'main/changelevel/', $fattr); ?>
    
    <div class="form-group">
        <select class="form-control" name="email" id="email">
            <?php
            foreach($groups as $row)
            { 
              echo '<option value="'.$row->email.'">'.$row->email.'</option>';
            }
            ?>
            </select>
    </div>

    <div class="form-group">
    <?php
        $dd_list = array(
                  '1'   => 'Admin',
                  '2'   => 'Author',
                  '3'   => 'Editor',
                  '4'   => 'Subscriber',
                );
        $dd_name = "level";
        echo form_dropdown($dd_name, $dd_list, set_value($dd_name),'class = "form-control" id="level"');
    ?>
    </div>
    <?php echo form_submit(array('value'=>'Submit', 'class'=>'btn btn-lg btn-primary btn-block')); ?>
    <a href="<?php echo site_url().'main/users/';?>"><button type="button" class="btn btn-default btn-lg btn-block">Cancel</button></a>
    <?php echo form_close(); ?>
</div>