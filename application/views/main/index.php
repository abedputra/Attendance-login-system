<?php
//check user level
$dataLevel = $this->userlevel->checkLevel($role);
?>
<div class="jumbotron">
    <div class="container">
        <div class="row">
            <div class="col-md-6" style="padding: 20px;">
                <h1>Hi, <?php echo $first_name; ?>!</h1>
                <p>Welcome Back! <i class="fa fa-smile-o" aria-hidden="true"></i><br>How are you today?</p>
            </div>
            <div class="col-md-6">
                <?php if ($dataLevel == 'is_admin') { ?>
                    <canvas id="bar-chart"></canvas>
                <?php } ?>
            </div>
        </div>
    </div>
</div>
</div><!--row-->

