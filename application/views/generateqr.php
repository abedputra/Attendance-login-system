<div class="container">
        <h2>Generate QR Code</h2>
        <hr style="margin-bottom:50px">
        
        <?php
        $fattr = array('class' => 'form-inline text-center');
        echo form_open(site_url().'main/generateqr/', $fattr); 
        ?>

        <div class="form-group">
            <lable>Name</lable>
          <?php echo form_input(array('name'=>'qr', 'id'=>'qr', 'placeholder'=>'Your Employee Full Name', 'class'=>'form-control', 'value' => set_value('qr'))); ?>
          <?php echo form_error('name');?>
        </div>
        <div class="form-group">
        <?php echo form_submit(array('value'=>'Get it', 'class'=>'btn btn-primary')); ?>
        </div>
        <?php echo form_close(); ?>
        
        <?php
            $getqr = trim($this->input->post('qr'));
            $qr = "{'name':'".$getqr."'}";
            
            if(!empty($getqr)){
                echo '<div style="margin-top:100px;">';
                echo '<img class="img-responsive img-thumbnail" src="https://chart.googleapis.com/chart?chs=200x200&cht=qr&chl='.$qr.'&choe=UTF-8" style="margin: 0 auto;display: block;">';
                echo '<br><a href="https://chart.googleapis.com/chart?chs=400x400&cht=qr&chl='.$qr.'&choe=UTF-8" target="_blank"><button class="btn btn-primary" style="margin: 0 auto;display: block;">Save</button></a>';
                echo '</div>';
            }
        ?>
</div>