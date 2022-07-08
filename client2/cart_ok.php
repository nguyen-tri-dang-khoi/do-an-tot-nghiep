<?php
    include_once("../lib/database.php");
    $id = isset($_REQUEST['id']) ? $_REQUEST['id'] : null; 
    $count = isset($_REQUEST['count']) ? $_REQUEST['count'] : null;
    $img = isset($_REQUEST['img']) ? $_REQUEST['img'] : null;
    $price = isset($_REQUEST['price']) ? $_REQUEST['price'] : null;
    $name = isset($_REQUEST['name']) ? $_REQUEST['name'] : null;
    $thao_tac = isset($_REQUEST['thao_tac']) ? $_REQUEST['thao_tac'] : null;
    $_SESSION['cart'] = isset($_SESSION['cart']) ? $_SESSION['cart'] : [];

    if($thao_tac == 'addCart') {
        if(isset($_SESSION['cart']))
        {
            if(isset($_SESSION['cart'][$id])){
                $_SESSION['cart'][$id]['count']++;
            }
            else
            {
                $_SESSION['cart'][$id] = ['count' => $count,'price' => $price,'name' => $name,'img' => $img];
            }
        }
    } else if($thao_tac == 'updateInfoCart') {
        $_SESSION['cart'][$id]['count'] = $count;
    } else if($thao_tac == 'deleteCart') {
        unset($_SESSION['cart'][$id]);
    } else if($thao_tac == 'loadCart') {
        echo_json(['msg' => 'ok','cart' => $_SESSION['cart']]);
    } else if($thao_tac == 'deleteAllCart') {
        unset($_SESSION['cart']);
    }
    echo_json(['msg' => 'ok','cart' => $_SESSION['cart']]);
?>