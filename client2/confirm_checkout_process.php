<?php
    include_once 'db.php';
?>
<?php
/** payment_status
 * 1: Đã thanh toán
 * 2: Thanh toán thành công
 * 3: Chưa thanh toán
 * 4: Đang chờ xử lý
 */
/** payment_method
 * 1: Momo
 * 2: Paypal
 * 3: tiền mặt (cod)
 * 4: vnpay
 * 5: zalopay
 */
    
    $thao_tac = isset($_REQUEST['thao_tac']) ? $_REQUEST['thao_tac'] : null;
    $payment_method_id = isset($_REQUEST['payment_method_id']) ? $_REQUEST['payment_method_id'] : null;
    $payment_status_id = isset($_REQUEST['payment_status_id']) ? $_REQUEST['payment_status_id'] : 3; // chua thanh toan
    $address = isset($_REQUEST['address']) ? $_REQUEST['address'] : null;
    $note = isset($_REQUEST['note']) ? $_REQUEST['note'] : null;
    $orders_id = isset($_REQUEST['order_id']) ? $_REQUEST['order_id'] : null;
    $customer_id = isset($_SESSION['customer_id']) ? $_SESSION['customer_id'] : null;
    $cart = isset($_SESSION['cart']) ? $_SESSION['cart'] : [];
    $conn = connect();
    //print_r($_REQUEST);
    if($thao_tac == "tao_don_hang") {
        if($payment_method_id == 'cod') {
            $payment_method_id = 3;
        } else if($payment_method_id == 'vnpay'){
            $payment_method_id = 4;
        }
        $orders_code = uniqid();
        //
        $sql_ins = "Insert into orders(orders_code,customer_id,total,payment_status_id,payment_method_id,address,note) value('$orders_code','$customer_id','1','$payment_status_id','$payment_method_id','$address','$note')";
        $result = mysqli_query($conn,$sql_ins);
        $id = mysqli_insert_id($conn);
        //
        $orders_code = $orders_code . $id;
        $sql_update_order_code = "Update orders set orders_code = $orders_code where id = $id";
        //
        mysqli_query($conn,$sql_update_order_code);
        $order_id_insert = $id;
        if($_SESSION['cart'] != []) {
            $total = 0;
            foreach($_SESSION['cart'] as $key => $value) {
                $sql_get_cost = "select cost,price from product_info where id = '$key' and is_active like 1 and is_delete like 0 limit 1";
                $result = mysqli_query($conn,$sql_get_cost);
                $row = mysqli_fetch_array($result);
                $cost = $row['cost'];
                $price = $row['price'];
                $count = $value['count'];
                $total = $total + ($price * $count);
                $sql_order_detail = "Insert into order_detail(order_id,product_info_id,count,price,cost) values('$order_id_insert','$key','$count','$price','$cost')";
                mysqli_query($conn,$sql_order_detail);
                //
            }    
        }
        $sql_update_total = "Update orders set total = '$total' where id = $id";
        mysqli_query($conn,$sql_update_total);
        echo json_encode(["msg" => "ok"]);
        exit();
    } else if($thao_tac == "cap_nhat_thanh_toan_thanh_cong") {
        $sql_upt_payment_status = "Update orders set payment_status_id = 1 where id = '$order_id'";
        mysqli_query($conn,$sql_upt_payment_status);
        echo json_encode(["msg" => "ok"]);
        exit();
    }
    
?>