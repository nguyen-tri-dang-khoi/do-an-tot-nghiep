<?php
    include_once("../lib/database.php");
    //session_start();
    //log_v('khoi');
    //log_a($_SESSION['cart']);
    //unset($_SESSION);
    $_SESSION = [];
    //print_r($_SESSION);
   /* $_SESSION['cart'] = isset($_SESSION['cart']) ? $_SESSION['cart'] : [];
    // them gio hang
    if(isset($_SESSION['cart'][$id])){
        // neu them san pham trung 
        $_SESSION['cart'][$id]['count']++;
    } else {
        $_SESSION['cart'][$id] = ['count' => 3,'price' => 7];
    }
    // xoa gio hang
    unset($_SESSION['cart'][3]);
    // sua gio hang
    // $_SESSION['cart'][5]['count'] = 7;
    
    /*$id = 5;
    $_SESSION['cart'] = [];
    $_SESSION['cart'][$id] = ['count' => 3,'price' => 7];
    $_SESSION['cart'][3] = ['count' => 3,'price' => 7];*/
    print_r($_SESSION['cart']);
?>