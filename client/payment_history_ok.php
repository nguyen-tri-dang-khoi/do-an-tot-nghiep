<?php
    $arr_vnpay_payment_error = [
        "09" => "Thẻ/Tài khoản của khách hàng chưa đăng ký dịch vụ InternetBanking tại ngân hàng.",
        "10" => "Khách hàng xác thực thông tin thẻ/tài khoản không đúng quá 3 lần",
        "11" => "Đã hết hạn chờ thanh toán. Xin quý khách vui lòng thực hiện lại giao dịch.",
        "12" => "Thẻ/Tài khoản của khách hàng bị khóa.",
        "13" => "Giao dịch không thành công do Quý khách nhập sai mật khẩu xác thực giao dịch (OTP). Xin quý khách vui lòng thực hiện lại giao dịch.",
        "24" => "Khách hàng hủy giao dịch",
        "51" => "Tài khoản của quý khách không đủ số dư để thực hiện giao dịch.",
        "65" => "Tài khoản của Quý khách đã vượt quá hạn mức giao dịch trong ngày."
    ];
    $arr_momo_payment_error = [
        "1001" => "Giao dịch thanh toán thất bại do tài khoản người dùng không đủ tiền.",
        "1002" => "Giao dịch bị từ chối do nhà phát hành tài khoản thanh toán.",
        "1003" => "Giao dịch đã bị hủy.",
        "1004" => "Giao dịch thất bại do số tiền thanh toán vượt quá hạn mức thanh toán của người dùng.",
        "1005" => "Giao dịch thất bại do url hoặc QR code đã hết hạn.",
        "1006" => "Giao dịch thất bại do người dùng đã từ chối xác nhận thanh toán.",
        "1007" => "Giao dịch bị từ chối vì tài khoản người dùng đang ở trạng thái tạm khóa."
    ];
    $arr_zalopay_payment_error = [
        "2" => "Giao dịch thất bại, tài khoản chưa bị trừ tiền, vui lòng thực hiện lại.",
        "-62" => "Tài khoản không đủ tiền để thanh toán.",
        "-63" => "Tài khoản không đủ tiền để thanh toán.",
    ]
?>
<?php
    include_once("../lib/database.php");
    $status_type = isset($_REQUEST['status_type']) ? $_REQUEST['status_type'] : null;
    $order_code = isset($_REQUEST['order_code']) ? $_REQUEST['order_code'] : null;
    if($order_code) {
        $note_payment = null;
        $sql_get_id = "select id from orders where orders_code = '$order_code' limit 1";
        $row = fetch(sql_query($sql_get_id));
        $order_id = $row["id"];
        if($status_type == "vnpay") {
            $vnp_ResponseCode = isset($_REQUEST['vnp_ResponseCode']) ? $_REQUEST['vnp_ResponseCode'] : null;
            if($vnp_ResponseCode == "00") {
                $sql_ins_pay_history = "Insert into order_payment_history(order_id,payment_status_id) values('$order_id','1')";
                $sql_upt_latest_payment_status = "Update orders set payment_status = 1 where id = '$order_id'";
                sql_query($sql_upt_latest_payment_status);
                
                $_SESSION['is_create_order'] = 0;
                $_SESSION['client_order_code'] = "";
            } else {
                if(array_key_exists($vnp_ResponseCode,$arr_vnpay_payment_error)) {
                    $note_payment = $arr_vnpay_payment_error[$vnp_ResponseCode];
                } else {
                    $note_payment = "Đã có lỗi xảy ra bên hệ thống thanh toán - mã lỗi $vnp_ResponseCode";
                }
                $sql_ins_pay_history = "Insert into order_payment_history(order_id,payment_status_id,note_payment) values('$order_id','2','$note_payment')";
            }
            sql_query($sql_ins_pay_history);
            $_SESSION['cart'] = [];
            header("Location: http://localhost/project/client/order_complete.php?order_id=$order_id");
            exit();
        } else if($status_type=="momo") {
            $resultCode = isset($_REQUEST['resultCode']) ? $_REQUEST['resultCode'] : null;
            if($resultCode == "0") {
                $sql_ins_pay_history = "Insert into order_payment_history(order_id,payment_status_id) values('$order_id','1')";
                $sql_upt_latest_payment_status = "Update orders set payment_status = 1 where id = '$order_id'";
                sql_query($sql_upt_latest_payment_status);
                
                $_SESSION['is_create_order'] = 0;
                $_SESSION['client_order_code'] = "";
            } else {
                if(array_key_exists($resultCode,$arr_momo_payment_error)) {
                    $note_payment = $arr_momo_payment_error[$resultCode];
                } else {
                    $note_payment = "Đã có lỗi xảy ra bên hệ thống thanh toán - mã lỗi $resultCode";
                }
                $sql_ins_pay_history = "Insert into order_payment_history(order_id,payment_status_id,note_payment) values('$order_id','2','$note_payment')";
            }
            sql_query($sql_ins_pay_history);
            $_SESSION['cart'] = [];
            header("Location: http://localhost/project/client/order_complete.php?order_id=$order_id");
            exit();
        } else if($status_type=="zalopay") {
            $status = isset($_REQUEST['status']) ? $_REQUEST['status'] : null;
            $apptransid = isset($_REQUEST['apptransid']) ? $_REQUEST['apptransid'] : null;
            if($status == 1) {
                $sql_ins_pay_history = "Insert into order_payment_history(order_id,transaction_code,payment_method_id,payment_status_id,note_payment) values('$order_id','$apptransid','5','1','Thanh toán thành công')";
                $sql_upt_latest_payment_status = "Update orders set payment_status = 1 where id = '$order_id'";
                sql_query($sql_upt_latest_payment_status);
                $_SESSION['is_create_order'] = 0;
                $_SESSION['client_order_code'] = "";
            } else {
                if(array_key_exists($resultCode,$arr_zalopay_payment_error)) {
                    $note_payment = $arr_zalopay_payment_error[$status];
                } else {
                    $note_payment = "Đã có lỗi xảy ra bên hệ thống thanh toán - mã lỗi $status";
                }
                $sql_ins_pay_history = "Insert into order_payment_history(order_id,transaction_code,payment_method_id,payment_status_id,note_payment) values('$order_id','$apptransid','5','2','$note_payment')";
            }
            sql_query($sql_ins_pay_history);
            $_SESSION['cart'] = [];
            header("Location: http://localhost/project/client/order_complete.php?order_id=$order_id");
            exit();
        }
    }
    
?>