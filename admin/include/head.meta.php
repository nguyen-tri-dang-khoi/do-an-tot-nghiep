<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
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
        .center {
            display:flex;
            justify-content:center;
            align-items:center;
            flex-direction:column;
            min-height:100vh;
        }
    </style>
</head>
<body class="">
<div class="wrapper <?php if(strpos(get_url_current_page(),"login") || strpos(get_url_current_page(),"register")) { echo "center";}?>">

    
