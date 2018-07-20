        <?php
        //check user level
	    $dataLevel = $this->userlevel->checkLevel($role);
	    //check user level
        ?>
        <nav class="navbar navbar-inverse">
            <div class="container">
              <div class="container-fluid">
                <!-- Brand and toggle get grouped for better mobile display -->
                <div class="navbar-header">
                  <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#bs-example-navbar-collapse-1" aria-expanded="false">
                    <span class="sr-only">Toggle navigation</span>
                    <span class="icon-bar"></span>
                    <span class="icon-bar"></span>
                    <span class="icon-bar"></span>
                  </button>
                  <a class="navbar-brand" href="<?php echo site_url();?>main/"><img src="<?php echo base_url(); ?>public/image/ic_launcher.png" width="25" style="border: 1px solid #fff;"></a>
                </div>

                <!-- Collect the nav links, forms, and other content for toggling -->
                <div class="collapse navbar-collapse" id="bs-example-navbar-collapse-1">
                  <ul class="nav navbar-nav">
                    <li><a href="<?php echo site_url();?>main/"><i class="fa fa-tachometer" aria-hidden="true"></i> Dashboard</a></li>
                    <?php
                        if($dataLevel == 'is_admin'){
                            echo'<li><a href="'.site_url().'main/employees"><i class="fa fa-user" aria-hidden="true"></i> Employee</a></li>';
                            echo'<li><a href="'.site_url().'main/generateqr"><i class="glyphicon glyphicon-qrcode" aria-hidden="true"></i> Generate QR</a></li>';
                            echo'<li><a href="'.site_url().'main/settings"><i class="glyphicon glyphicon-cog" aria-hidden="true"></i> Settings</a></li>';
                        }
                        if($dataLevel == 'is_employee'){
                            echo'<li><a href="'.site_url().'main/employees"><i class="fa fa-user" aria-hidden="true"></i> Employee</a></li>';
                        }
                        if($dataLevel == 'is_admin'){
                            echo'
                            <li class="dropdown">
                              <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false"><i class="fa fa-users" aria-hidden="true"></i> Users <span class="caret"></span></a>
                              <ul class="dropdown-menu">
                                <li><a href="'.site_url().'main/users">Users List</a></li>
                                <li><a href="'.site_url().'main/adduser">Add User</a></li>
                                <li><a href="'.site_url().'main/banuser">Ban User</a></li>
                                <li><a href="'.site_url().'main/changelevel">Role</a></li>
                              </ul>
                            </li>';
                        }
                    ?>
                  </ul>

                  <ul class="nav navbar-nav navbar-right">
                    <li class="dropdown">
                      <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false"><i class="fa fa-user-circle" aria-hidden="true"></i> <?php echo $first_name; ?> <span class="caret"></span></a>
                      <ul class="dropdown-menu">
                        <li><a href="<?php echo site_url();?>main/profile"><?php echo $email; ?></a></li>
                        <li><a href="<?php echo site_url();?>main/changeuser">Edit Profile</a></li>
                        <li role="separator" class="divider"></li>
                        <li><a href="<?php echo base_url().'main/logout' ?>">Log Out</a></li>
                      </ul>
                    </li>
                  </ul>
                </div><!-- /.navbar-collapse -->
              </div><!-- /.container-fluid -->
            </div>
        </nav>
