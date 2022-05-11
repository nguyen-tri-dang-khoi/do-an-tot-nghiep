<?php
    include_once("../lib/database.php");
    $status = isset($_REQUEST['status']) ? $_REQUEST['status'] : null;
    $address = isset($_REQUEST['address']) ? $_REQUEST['address'] : null;
    $customer_id = isset($_SESSION['customer_id']) ? $_SESSION['customer_id'] : null;
    $payment_method_id = isset($_REQUEST['payment_method_id']) ? $_REQUEST['payment_method_id'] : null;
    $total = isset($_REQUEST['total']) ? $_REQUEST['total'] : null;
    $notes = isset($_REQUEST['notes']) ? $_REQUEST['notes'] : null;
    if($status == 'checkout_ok') {
        $note = isset($_REQUEST['note']) ? $_REQUEST['note'] : null;
        $order_code = "DH" . str_replace("-","",Date("d-m-YH-i-s",time()));
        $sql_orders = "Insert into orders(customer_id,total,payment_method_id,address,note) values('$customer_id','$total','$payment_method_id','$address','$notes')";
        sql_query($sql_orders);
        $order_id = ins_id();
        if($order_id > 0) {
            for($i = 0 ; $i < count($_SESSION['cart']) ; $i++) {
                $product_info_id = $_SESSION['cart'][$i]['pi_id'];
                $count = $_SESSION['cart'][$i]['pi_count'];
                $price = $_SESSION['cart'][$i]['pi_price'];
                $sql_order_detail = "Insert into order_detail(order_id,product_info_id,count,price) values('$order_id','$product_info_id','$count','$price')";
                sql_query($sql_order_detail);
                $count2 = fetch(sql_query("select count from product_info where id = '$product_info_id' limit 1"))['count'];
                $count2 = $count2 - $count;
                sql_query("Update product_info set count='$count2' where id = '$product_info_id'");
            }
        }
        $order_code = $order_code.$order_id;
        sql_query("Update orders set orders_code = '$order_code' where id = '$order_id'");
        $_SESSION['cart'] = [];
    } else if($status == 'save_customer_info') {
        $id = isset($_SESSION['customer_id']) ? $_SESSION['customer_id'] : null;
        $fullname = isset($_REQUEST['full_name']) ? $_REQUEST['full_name'] : null;
        $email = isset($_REQUEST['email']) ? $_REQUEST['email'] : null;
        $phone = isset($_REQUEST['phone']) ? $_REQUEST['phone'] : null;
        $address = isset($_REQUEST['address']) ? $_REQUEST['address'] : null;
        $sql_save_client = "Update customer set full_name='$fullname',email='$email', phone='$phone',address='$address' where id='$id'";
        sql_query($sql_save_client);
    } else if($status == 'checkout_cancel') {
        $_SESSION['cart'] = [];
    }
    echo_json(["msg" => "ok"]);
?>