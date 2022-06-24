<?php
    session_start();
    $_SESSION = array();
    if(isset($_COOKIE['shipper_access_token'])){
        setcookie('shipper_access_token','',time() - 3600,"/","",false,false);
    }
    session_destroy();
    header("location: login.php");
    exit();
?>