<?php
    include_once("../lib/database.php");
    $status = isset($_REQUEST['status']) ? $_REQUEST['status'] : null;
    $address = isset($_REQUEST['address']) ? $_REQUEST['address'] : null;
    $customer_id = isset($_SESSION['customer_id']) ? $_SESSION['customer_id'] : null;
    $payment_method_id = isset($_REQUEST['payment_method_id']) ? $_REQUEST['payment_method_id'] : null;
    $coupon_id = isset($_REQUEST['coupon_id']) ? $_REQUEST['coupon_id'] : null;
    $total = 0;
    foreach($_SESSION['cart'] as $car) {
        $total += $car['pi_price'] * $car['pi_count'];
    }
    $notes = isset($_REQUEST['notes']) ? $_REQUEST['notes'] : null;
    $order_code = null;
    $payment_method = isset($_REQUEST['payment_method']) ? $_REQUEST['payment_method'] : null;
    if($status == 'checkout_ok') {
        $note = isset($_REQUEST['note']) ? $_REQUEST['note'] : null;
        if($coupon_id) {
            $sql_orders = "Insert into orders(customer_id,total,delivery_status_id,payment_method_id,coupon_id,address,note) values('$customer_id','$total','10','$payment_method_id','$coupon_id','$address','$notes')";
        } else {
            $sql_orders = "Insert into orders(customer_id,total,delivery_status_id,payment_method_id,address,note) values('$customer_id','$total','10','$payment_method_id','$address','$notes')";
        }
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
        $order_code = uniqid("DH").$order_id;
        sql_query("Update orders set orders_code = '$order_code' where id = '$order_id'");
        if($payment_method_id == 3) {
            $sql_payment_history = "Insert into order_payment_history(order_id,payment_method_id,payment_status_id,note_payment) values('$order_id','$payment_method_id','3','Người dùng thanh toán sau khi nhận hàng')";
            sql_query($sql_payment_history);
        }   
        /*$_SESSION['cart'] = [];*/
        $_SESSION['client_order_code'] = $order_code;
        // nếu thanh toán bằng tiền mặt hoac bang paypal thì dẫn đến trang order_complete.php
        if($payment_method_id == 3 || $payment_method_id == 2) {
            $_SESSION['cart'] = [];
            echo_json(["msg" => "ok","order_id" => $order_id]);
        }
        if($payment_method_id == 2) {
            
        }
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
    } else if($status == "get_coupon_content") {
        $sql_get_coupon = "select * from coupon where id = '$coupon_id' and is_active = 1 and is_delete = 0 limit 1";
        $res33 = fetch(sql_query($sql_get_coupon));
        echo_json(["msg" => "ok","coupon_content" => $res33['coupon_content'],"coupon_discount_percent" => $res33['coupon_discount_percent']]);
    }
    if($payment_method == "vnpay" || $payment_method == "momo" || $payment_method == "zalopay") {
        //echo json_encode(["msg" => "ok"]);
    } else {
        echo_json(["msg" => "ok","order_id" => $order_id]);
    }
    
?>