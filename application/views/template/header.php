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
    <link rel="stylesheet" href="<?php echo base_url() . 'public/css/font-awesome.min.css'; ?>">
    <link rel="stylesheet" href="<?php echo base_url() . 'public/css/bootstrap.min.css'; ?>">
    <link rel="stylesheet" href="<?php echo base_url() . 'public/css/jquery.timepicker.min.css'; ?>">
    <link rel="stylesheet" href="<?php echo base_url() . 'public/css/main.css?version=' . (rand(500, 1000)); ?>">

    <link rel="stylesheet" type="text/css"
          href="https://cdn.datatables.net/r/dt/jq-2.1.4,jszip-2.5.0,pdfmake-0.1.18,dt-1.10.9,af-2.0.0,b-1.0.3,b-colvis-1.0.3,b-html5-1.0.3,b-print-1.0.3,se-1.0.1/datatables.min.css"/>

    <link rel="stylesheet" href="<?php echo base_url() . 'public/css/bootstrap-datepicker3.css'; ?>"/>

    <script src='https://www.google.com/recaptcha/api.js'></script>
</head>
<body>
