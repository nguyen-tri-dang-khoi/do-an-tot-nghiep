<?php
    // logout_session_timeout();
    // check_access_token();
     if(strpos(get_url_current_page(),"login.php")){
        redirect_if_login_success();
     } else {
        redirect_if_login_status_false();
     }
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Technology shop</title>
    <link rel="stylesheet" href="css/select2.min.css">
    <!--AdminLTE-->
    <link rel="stylesheet" href="css/adminlte.min.css">
    <!--Jquery UI-->
    <link rel="stylesheet" href="css/jquery-ui.css">

    <!--Simple Pagination-->
    <link rel="stylesheet" href="css/simplePagination.css">
	<!--Simple Pagination-->
    <link rel="stylesheet" href="css/jquery-confirm.min.css">
    <!--Google Font-->
    <link href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700" rel="stylesheet">
    <!--fontawesome 5-->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css" integrity="sha512-1ycn6IcaQQ40/MKBW2W4Rhis/DbILU74C1vSrLJxCq57o941Ym01SwNsOMqvEBFlcgUa6xLiPY/NS5R+E6ztJQ==" crossorigin="anonymous" referrerpolicy="no-referrer" />
	
    <!--khoi.css-->
	<link rel="stylesheet" href="css/khoi.css">
    <style>
        .user-panel img {
            height: 30px;
            width: 30px;
        }
        .bg-color-selected {
            background-color: #fbebfaf7 !important;
        }
        *:focus {
            outline:none !important;
        }
        .center {
            display:flex;
            justify-content:center;
            align-items:center;
            flex-direction:column;
            min-height:100vh;
        }
        .kh-inp-ctrl {
            border-radius: 0.25rem;
            padding: 4px !important;
            padding-left: 10px !important;
            border: 1px solid rgb(158 158 158 / 74%);
            width:100%;
        }
        .kh-inp-ctrl:focus {
            background-color: #fff;
            border-color: #3594fb;
            
        }
        .form-control:focus {
            background-color: #fff;
            border-color: #3594fb !important;
        }
        .card-header::after{
            display:none;
        }
        .file {
            width: 64px;
            position: relative; 
            height: 64px;
        }
        .file input {
            width: 100%;
            cursor: pointer;
            height: 100%;
            opacity: 0;
            font-size: 0px;
            display: block;
            position: absolute;
        }
        .file-excel {
            background-image: url(img/excel.png);
            background-position: 50%;
            background-repeat: no-repeat;
        } 
        .file-csv {
            background-image: url(img/csv.png);
            background-position: 50%;
            background-repeat: no-repeat;
        } 
    </style>
    
</head>
<body class="">
<div style="min-width:fit-content;margin-right:0px;" class="wrapper <?php if(strpos(get_url_current_page(),"reset_password.php") || strpos(get_url_current_page(),"forgive_password.php") || strpos(get_url_current_page(),"login") || strpos(get_url_current_page(),"register")) { echo "center";}?>">

    
