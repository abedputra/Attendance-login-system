    <?php
    //check user level
    $dataLevel = $this->userlevel->checkLevel($role);
    //check user level
    ?>
    <div class="alert alert-info alert-dismissible info-update" style="display: none;">
      <a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>
      <strong>Info!</strong> There is the latest update for the system, please download from here <a href="https://github.com/abedputra/Attendance-login-system/" target="_blank">Github</a>.
    </div>
    <div class="jumbotron">
        <div class="container">
          <h1>Hi, <?php echo $first_name; ?>!</h1>
          <p>Welcome Back! How are you today?</p>
        </div>
    </div>
    <?php
    if(!empty($count_absent_today) && !empty($many_employee)){
        $allabset = $count_absent_today / $many_employee * 100;
        $allabsetPerc = $allabset."%";
        
        $allLate = $count_late_today / $count_absent_today * 100;
        $allLatePerc = $allLate."%";
    }else{
        $allabsetPerc = "0%";
        $allLatePerc = "0%";
    }
    ?>
    <?php
        if($dataLevel == 'is_admin'){
    ?>
        <div class="panel panel-primary">
          <div class="panel-heading">
           <h3 class="panel-title">Your Employees Today 
                <div class="pull-right">
                    <?php echo $nowToday; ?>
                </div>
            </h3>
          </div>
          <div class="panel-body">
            <div class="panel-body">
               <div class="row">
                   <div class="col-md-6">
                        <div class="progress">
                          <div class="progress-bar progress-bar-success progress-bar-striped active" role="progressbar" 
                            aria-valuenow="40" aria-valuemin="0" aria-valuemax="100" style="width: <?php echo $allabsetPerc; ?>">
                            
                          </div>
                        </div>
                        <h4 class="progress-label"><?php echo $allabsetPerc; ?> or <?php echo $count_absent_today; ?> Person Absent Today.</h4>
                    </div>
                    <div class="col-md-6">
                        <div class="progress">
                          <div class="progress-bar progress-bar-danger progress-bar-striped active" role="progressbar" 
                            aria-valuenow="40" aria-valuemin="0" aria-valuemax="100" style="width: <?php echo $allLatePerc; ?>">
                            
                          </div>
                        </div>
                        <h4 class="progress-label"><?php echo $allLatePerc; ?> or <?php echo $count_late_today; ?> Employees are late Today.</h4>
                    </div>
                </div>
            </div>
        </div>
    
    <?php
    }
    ?>
