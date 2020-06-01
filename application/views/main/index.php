<?php
//check user level
$dataLevel = $this->userlevel->checkLevel($role);
?>
    <div class="jumbotron">
        <div class="container">
            <div class="row">
                <div class="col-md-6">
                    <h1>Hi, <?php echo $first_name; ?>!</h1>
                    <p>Welcome Back! How are you today?</p>
                </div>
                <div class="col-md-6">
                    <?php if ($dataLevel == 'is_admin') {?>
                        <canvas id="bar-chart"></canvas>
                    <?php } ?>
                </div>
            </div>
        </div>
    </div>
</div><!--row-->

