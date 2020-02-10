<?php
defined('BASEPATH') OR exit('No direct script access allowed');
?>

<!doctype html>
<!--[if lt IE 7]>
<html class="no-js lt-ie9 lt-ie8 lt-ie7" lang=""> <![endif]-->
<!--[if IE 7]>
<html class="no-js lt-ie9 lt-ie8" lang=""> <![endif]-->
<!--[if IE 8]>
<html class="no-js lt-ie9" lang=""> <![endif]-->
<!--[if gt IE 8]><!-->
<html class="no-js" lang=""> <!--<![endif]-->
    <head>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
        <title><?php echo $title; ?></title>
        <meta name="description" content="">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <link rel="apple-touch-icon" href="<?php echo base_url(); ?>public/image/ic_launcher.png">
        <link href="<?php echo base_url(); ?>public/image/ic_launcher.png" rel="icon" type="image/png">

        <!--CSS-->
        <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css">
        <link rel="stylesheet" href="https://bootswatch.com/3/cosmo/bootstrap.min.css">
        <link rel="stylesheet" href="<?php echo base_url() . 'public/css/main.css?version='. (rand(500,1000)); ?>">

        <script src='https://www.google.com/recaptcha/api.js'></script>
    </head>
    <body class="background-login">

        <!--[if lt IE 8]>
        <p class="browserupgrade">You are using an <strong>outdated</strong> browser. Please <a href="http://browsehappy.com/">upgrade
            your browser</a> to improve your experience.</p>
        <![endif]-->

        <?php
        $arr = $this->session->flashdata();
        if (!empty($arr['flash_message']) && $arr['flash_message'] != '') {
            $html = '<div class="container hide-flashdata" style="margin-top: 10px;">';
            $html .= '<div class="alert alert-warning alert-dismissible" role="alert">';
            $html .= '<button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>';
            $html .= $arr['flash_message'];
            $html .= '</div>';
            $html .= '</div>';
            echo $html;
        }
        if (!empty($arr['success_message']) && $arr['success_message'] != '') {
            $html = '<div class="container hide-flashdata" style="margin-top: 10px;">';
            $html .= '<div class="alert alert-info alert-dismissible" role="alert">';
            $html .= '<button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>';
            $html .= $arr['success_message'];
            $html .= '</div>';
            $html .= '</div>';
            echo $html;
        }
        if (!empty($arr['danger_message']) && $arr['danger_message'] != '') {
            $html = '<div class="container hide-flashdata" style="margin-top: 10px;">';
            $html .= '<div class="alert alert-danger alert-dismissible" role="alert">';
            $html .= '<button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>';
            $html .= $arr['danger_message'];
            $html .= '</div>';
            $html .= '</div>';
            echo $html;
        }
        ?>
        <div class="container">
            <div class="row">
                <div class="center-vertical">
                    <div class="container col-lg-4">
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
                        <hr>
                        Copyright&copy; - <?php echo date('Y'); ?> | Create by <a href="https://connectwithdev.com/">connectwithdev.com</a>
                    </div>
                </div>
            </div><!--row-->
        </div><!-- /container -->


        <!-- /Load Js -->
        <script src="//ajax.googleapis.com/ajax/libs/jquery/1.11.1/jquery.min.js" type="text/javascript"></script>
        <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js"></script>
        <script src="<?php echo base_url() . 'public/js/main.js' ?>"></script>
    </body>
</html>
