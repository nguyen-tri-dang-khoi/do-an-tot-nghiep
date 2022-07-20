<?php
    include_once 'db.php';
    include_once 'include/config.php';
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
    $payment_status_id = isset($_REQUEST['payment_status_id']) ? $_REQUEST['payment_status_id'] : 2; // thanh toan ko thanh cong
    $address = isset($_REQUEST['address']) ? $_REQUEST['address'] : null;
    $note = isset($_REQUEST['note']) ? $_REQUEST['note'] : null;
    $orders_id = isset($_REQUEST['order_id']) ? $_REQUEST['order_id'] : null;
    $customer_id = isset($_SESSION['customer_id']) ? $_SESSION['customer_id'] : null;
    $cart = isset($_SESSION['cart']) ? $_SESSION['cart'] : [];

    $orders_code = uniqid();
    $total = 0;

    $conn = connect();
    //print_r($_REQUEST);
    if($thao_tac == "tao_don_hang") {
        
        
        if($payment_method_id == 'cod') {
            $payment_method_id = 3;
            $payment_status_id = 3;

            $sql_insert_order = "Insert into orders(orders_code,customer_id,total,payment_status_id,payment_method_id,address,note) 
            value('$orders_code','$customer_id','$total','$payment_status_id','$payment_method_id','$address','$note')";

            $result = mysqli_query($conn,$sql_insert_order);
            $id = mysqli_insert_id($conn);

            $orders_code = $orders_code . $id;
            // print_r ($orders_code);
            $sql_update_order_code = "Update orders set orders_code = '$orders_code' where id = $id";

            mysqli_query($conn,$sql_update_order_code);
            $order_id_insert = $id;
            
            if($_SESSION['cart'] != []) {
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
            // echo json_encode(["msg" => "ok"]);
            // exit();
            $_SESSION['cart'] = [];
            header("Location:form_info_customer.php");
        } else if($payment_method_id == 'vnpay'){ 
            $payment_method_id = 4;
                $sql_insert_order = "Insert into orders(orders_code,customer_id,total,payment_status_id,payment_method_id,address,note) 
                value('$orders_code','$customer_id','$total','$payment_status_id','$payment_method_id','$address','$note')";

                $result = mysqli_query($conn,$sql_insert_order);
                $id = mysqli_insert_id($conn);

                $orders_code = $orders_code . $id;
                // print_r ($orders_code);
                $sql_update_order_code = "Update orders set orders_code = '$orders_code' where id = $id";

                mysqli_query($conn,$sql_update_order_code);
                $order_id_insert = $id;
                
                if($_SESSION['cart'] != []) {
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

                
            

            //vnpay start
            $_SESSION['order_pm_history_id'] = $order_id_insert;

            $vnp_Returnurl = "http://localhost:8080/project/client/include/camon.php?order_id=$order_id_insert";
            $vnp_TxnRef = $orders_code; //Mã đơn hàng. Trong thực tế Merchant cần insert đơn hàng vào DB và gửi mã này sang VNPAY
            $vnp_OrderInfo = 'Thanh toán đơn hàng đặt tại web TNC';
            $vnp_OrderType = 'billpayment';
            $vnp_Amount = $total * 100;
            $vnp_Locale = 'vn';
            $vnp_BankCode = 'NCB';
            $vnp_IpAddr = $_SERVER['REMOTE_ADDR'];
            //Add Params of 2.0.1 Version
            $vnp_ExpireDate = $expire;
            $inputData = array(
                "vnp_Version" => "2.1.0",
                "vnp_TmnCode" => $vnp_TmnCode,
                "vnp_Amount" => $vnp_Amount,
                "vnp_Command" => "pay",
                "vnp_CreateDate" => date('YmdHis'),
                "vnp_CurrCode" => "VND",
                "vnp_IpAddr" => $vnp_IpAddr,
                "vnp_Locale" => $vnp_Locale,
                "vnp_OrderInfo" => $vnp_OrderInfo,
                "vnp_OrderType" => $vnp_OrderType,
                "vnp_ReturnUrl" => $vnp_Returnurl,
                "vnp_TxnRef" => $vnp_TxnRef,
                "vnp_ExpireDate"=>$vnp_ExpireDate
            );

            if (isset($vnp_BankCode) && $vnp_BankCode != "") {
                $inputData['vnp_BankCode'] = $vnp_BankCode;
            }

            //var_dump($inputData);
            ksort($inputData);
            $query = "";
            $i = 0;
            $hashdata = "";
            foreach ($inputData as $key => $value) {
                if ($i == 1) {
                    $hashdata .= '&' . urlencode($key) . "=" . urlencode($value);
                } else {
                    $hashdata .= urlencode($key) . "=" . urlencode($value);
                    $i = 1;
                }
                $query .= urlencode($key) . "=" . urlencode($value) . '&';
            }

            $vnp_Url = $vnp_Url . "?" . $query;
            if (isset($vnp_HashSecret)) {
                $vnpSecureHash =   hash_hmac('sha512', $hashdata, $vnp_HashSecret);//  
                $vnp_Url .= 'vnp_SecureHash=' . $vnpSecureHash;
            }
            // $returnData = array('code' => '00'
            //     , 'message' => 'success'
            //     , 'data' => $vnp_Url);
            if (isset($_POST['redirect'])) {
                header('Location: ' . $vnp_Url);
                die();
            } else {
                header('Location: ' . $vnp_Url);
            }
            // vui lòng tham khảo thêm tại code demo
        }
        
    } else{
        // $sql_upt_payment_status = "Update orders set payment_status_id = 1 where id = '$order_id'";
        // mysqli_query($conn,$sql_upt_payment_status);
        // echo json_encode(["msg" => "ok"]);
        exit();
    }
    
?>