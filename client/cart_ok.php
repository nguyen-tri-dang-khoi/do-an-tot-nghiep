<?php
    include_once("../lib/database.php");
    $status = isset($_REQUEST['status']) ? $_REQUEST['status'] : null;
    $pi_id = isset($_REQUEST['pi_id']) ? $_REQUEST['pi_id'] : null;
    $pi_name = isset($_REQUEST['pi_name']) ? $_REQUEST['pi_name'] : null;
    $pi_count = isset($_REQUEST['pi_count']) ? $_REQUEST['pi_count'] : null;
    $pi_price = isset($_REQUEST['pi_price']) ? $_REQUEST['pi_price'] : null;
    $success = "Success";
    if($status == "Insert") {
        $test = true;
        $row = ['pi_id' => $pi_id,'pi_name' => $pi_name,'pi_count' => $pi_count,'pi_price' => $pi_price];
        for($i = 0 ; $i < count($_SESSION['cart']) ; $i++) {
            if($pi_id == $_SESSION['cart'][$i]['pi_id']) {
                $_SESSION['cart'][$i]['pi_count'] += $pi_count;
                $test = false;
                break;
            }
        }
        if($test) {
            array_push($_SESSION['cart'],$row);
        }
    } else if($status == "Update") {
        $row = ['pi_id' => $pi_id,'pi_name' => $pi_name,'pi_count' => $pi_count,'pi_price' => $pi_price];
        $index = isset($_REQUEST['index']) ? $_REQUEST['index'] : null;
        $_SESSION['cart'][$index] = $row;
    } else if($status == "Delete") {
        $index = isset($_REQUEST['index']) ? $_REQUEST['index'] : null;
        unset($_SESSION['cart'][$index]);
    } else if($status == 'cart_cancel') {
        $_SESSION['cart'] = [];
    }
    echo_json(['msg' => 'ok','success' => $success]);
?>