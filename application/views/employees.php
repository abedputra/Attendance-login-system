<div class="container">
        <h2>Employee</h2>
        <h5>Hello <span><?php echo $first_name; ?></span>.</h5>
        <hr>

        <?php
            if(!empty($this->input->get('datefrom')) && !empty($this->input->get('dateto')) && empty($this->input->get('name'))){
                echo '<h3 class="alert alert-info">Result Date <b>'.$this->input->get('datefrom').'</b> to <b>'.$this->input->get('dateto').'</b></h3>';
            }else if(!empty($this->input->get('datefrom')) && !empty($this->input->get('dateto')) && !empty($this->input->get('name'))){
                echo '<h3 class="alert alert-info">Result Name <b>'.$this->input->get('name').'</b> and Date <b>'.$this->input->get('datefrom').'</b> to <b>'.$this->input->get('dateto').'</b></h3>';
            }else if(!empty($this->input->get('datefrom'))){
                echo '<h3 class="alert alert-info">Result Date <b>'.$this->input->get('datefrom').'</b></h3>';
            }else if(!empty($this->input->get('name'))){
                echo '<h3 class="alert alert-info">Result Name <b>'.$this->input->get('name').'</b></h3>';
            }
        ?>

        <div class="panel panel-primary">
          <div class="panel-heading">Search</div>
            <div class="panel-body">
                <?php
                $fattr = array('class' => 'form-inline text-center', 'method' => 'GET');
                echo form_open(site_url().'main/employees/', $fattr);
                ?>

                <div class="form-group">
                    <lable>Name</lable>
                  <?php echo form_input(array('name'=>'name', 'id'=>'name', 'placeholder'=>'Name', 'class'=>'form-control', 'value' => set_value('name'))); ?>
                  <span class="help-block">Employee Name</span>
                  <?php echo form_error('name');?>
                </div>
                <div class="form-group">
                    <lable>From</lable>
                  <?php echo form_input(array('data-format' => 'YY-MM-DD', 'data-custom-class' => 'form-control', 'data-template' => 'YY / MM / DD', 'name'=> 'datefrom', 'placeholder'=>'Date From', 'class'=>'form-control', 'id'=>'datefrom', 'value'=> set_value('datefrom'))); ?>
                  <span class="help-block">Format Y/M/D</span>
                  <?php echo form_error('datefrom');?>
                </div>
                <div class="form-group">
                    <lable>To</lable>
                  <?php echo form_input(array('data-format' => 'YY-MM-DD', 'data-custom-class' => 'form-control', 'data-template' => 'YY / MM / DD', 'name'=> 'dateto', 'placeholder'=>'Date To', 'class'=>'form-control', 'id'=>'dateto', 'value'=> set_value('dateto'))); ?>
                  <span class="help-block">Format Y/M/D</span>
                  <?php echo form_error('dateto');?>
                </div>
                <div class="form-group">
                <?php echo form_submit(array('value'=>'Search', 'class'=>'btn btn-primary')); ?>
                <span class="help-block">Search</span>
                </div>
                <?php echo form_close(); ?>
            </div>
        </div>
        <?php if (!empty($groups)){ ?>
        <div class="row">
        <div class="col-md-12">
        <?php
        $fattr = array('class' => 'form-inline pull-left', 'method' => 'GET');
        echo form_open(site_url().'main/employees/', $fattr);
        ?>

        <input type="hidden" name="name" value="<?php echo $this->input->get('name'); ?>">
        <input type="hidden" name="datefrom" value="<?php echo $this->input->get('datefrom'); ?>">
        <input type="hidden" name="dateto" value="<?php echo $this->input->get('dateto'); ?>">
        <div class="form-group">
            <lable>Order By </lable>
        <?php
            $dd_list = array(
                      'id ASC'   => 'ID ASC',
                      'id DESC'   => 'ID DESC',
                      'name ASC'   => 'Name ASC',
                      'name DESC'   => 'Name DESC',
                      'work_hour ASC'   => 'Work Hour ASC',
                      'work_hour DESC'   => 'Work Hour DESC',
                      'late_time ASC'   => 'Late Time ASC',
                      'late_time DESC'   => 'Late Time DESC',
                    );
            $dd_name = "order";
            echo form_dropdown($dd_name, $dd_list, set_value($dd_name),'class = "form-control" id="order"');
        ?>
        </div>

        <div class="form-group">
        <?php echo form_submit(array('value'=>'Go!', 'class'=>'btn btn-primary')); ?>
        </div>
        <?php echo form_close(); ?>

        <?php
        $fattr = array('class' => 'form-inline pull-right', 'method' => 'GET');
        echo form_open(site_url().'main/employees/', $fattr);
        ?>

        <input type="hidden" name="name" value="<?php echo $this->input->get('name'); ?>">
        <input type="hidden" name="datefrom" value="<?php echo $this->input->get('datefrom'); ?>">
        <input type="hidden" name="dateto" value="<?php echo $this->input->get('dateto'); ?>">
        <input type="hidden" name="order" value="<?php echo $this->input->get('order'); ?>">

        <div class="form-group">
        <button type="submit" name="download" value="xls" class="btn btn-default">*Export to Exel</button>
        <button type="submit" name="download" value="csv" class="btn btn-default">Export to CSV</button>
        <span id="helpBlock" class="help-block">*Just Compatible with Microsoft Excel 2007.</span>
        </div>
        <?php echo form_close(); ?>
        </div>

        <div class="table-responsive col-md-12" style="margin-top:50px">
            <table class="table table-hover table-bordered table-striped">
              <tr>
                  <th>
                      Name
                  </th>
                  <th>
                      Date
                  </th>
                  <th>
                      In Time
                  </th>
                  <th>
                      Out Time
                  </th>
                  <th>
                      Work Hour
                  </th>
                  <th>
                      Over Time
                  </th>
                  <th>
                      Late Time
                  </th>
                  <th>
                      Early Out Time
                  </th>
                  <th width="150px">
                      In Location
                  </th>
                  <th width="150px">
                      Out Location
                  </th>
              </tr>
                    <?php
                        foreach($groups as $row)
                        {
                        echo '<tr>';
                        echo '<td>'.$row->name.'</td>';
                        echo '<td>'.$row->date.'</td>';
                        echo '<td>'.$row->in_time.'</td>';
                        echo '<td>'.$row->out_time.'</td>';
                        echo '<td>'.$row->work_hour.'</td>';
                        echo '<td>'.$row->over_time.'</td>';
                        echo '<td>'.$row->late_time.'</td>';
                        echo '<td>'.$row->early_out_time.'</td>';
                        echo '<td>'.$row->in_location.'</td>';
                        echo '<td>'.$row->out_location.'</td>';
                        echo '</tr>';
                        }
                    ?>
            </table>
        </div>
        </div>
        <p><?php echo $links; ?></p>
        <?php
        }else{
            echo '<div class="alert alert-warning" role="alert"  style="margin-top:50px">'.$info.'</div>';
        }
        ?>
    </div>
