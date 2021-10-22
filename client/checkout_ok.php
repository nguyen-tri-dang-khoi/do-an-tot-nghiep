<?php
    include_once("../lib/database.php");
    $status = isset($_REQUEST['status']) ? $_REQUEST['status'] : null;
    $address = isset($_REQUEST['address']) ? $_REQUEST['address'] : null;
    $customer_id = isset($_REQUEST['customer_id']) ? $_REQUEST['customer_id'] : null;
    $payment_method_id = isset($_REQUEST['payment_method_id']) ? $_REQUEST['payment_method_id'] : null;
    $total = isset($_REQUEST['total']) ? $_REQUEST['total'] : null;
    if($status == 'checkout_ok') {
        $sql_orders = "Insert into orders('customer_id','address','total','payment_method_id') values('$customer_id','$address','$total','$payment_method_id')";
        sql_query($sql_orders);
        $order_id = ins_id();
        if($order_id > 0) {
            for($i = 0 ; $i < count($_SESSION['cart']) ; $i++) {
                $product_info_id = $_SESSION['cart'][$i]['pi_id'];
                $count = $_SESSION['cart'][$i]['count'];
                $price = $_SESSION['cart'][$i]['price'];
                $sql_order_detail = "Insert into order_detail(order_id,product_info_id,count,price) values('$order_id','$product_info_id','$count','$price')";
                sql_query($sql_order_detail);
            }
            /*$__arr = [];
            foreach($_SESSION['cart'] as $cart) {
                array_push($__arr,"(" . implode(",",array_values($cart)) . ")");   
            }
            if(count($_arr) > 0) {
                $sql_order_detail .= implode(",",$__arr);
                sql_query($sql_order_detail);
            }*/
        }
    } else if($status == 'save_customer_info') {
        $fullname = isset($_REQUEST['fullname']) ? $_REQUEST['fullname'] : null;
        $email = isset($_REQUEST['email']) ? $_REQUEST['email'] : null;
        $phone = isset($_REQUEST['phone']) ? $_REQUEST['phone'] : null;
        $address = isset($_REQUEST['address']) ? $_REQUEST['address'] : null;
        $sql_orders = "Insert into customer(fullname,email,phone,address) values('$fullname','$email','$phone','$address')";
    } else if($status == 'checkout_cancel') {
        $_SESSION['cart'] = [];
    }
?>