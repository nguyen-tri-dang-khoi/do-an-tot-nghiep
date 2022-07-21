<?php
    session_start();
    $_SESSION = array();
    // if(isset($_COOKIE['access_token'])){
    //     setcookie('access_token','',time() - 3600,"/","",false,false);
    // }
    session_destroy();
    header("location: login.php");
    exit();
?>