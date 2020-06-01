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
