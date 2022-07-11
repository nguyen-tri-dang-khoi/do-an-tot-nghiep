<?php
    include_once 'db.php';
    $comment = isset($_REQUEST['comment']) ?  $_REQUEST['comment'] : null;
    $rate = isset($_REQUEST['rate']) ?  $_REQUEST['rate'] : null;
    $product_info_id = isset($_REQUEST['product_info_id']) ?  $_REQUEST['product_info_id'] : null;
    $customer_id = isset($_SESSION['customer_id']) ?  $_SESSION['customer_id'] : null;
    $thao_tac = isset($_REQUEST['thao_tac']) ? $_REQUEST['thao_tac'] : null;
    $conn = connect();
    $sql_show_customer = "select full_name from user where type = 'customer' and id = '$customer_id' limit 1";
    $customer_result = mysqli_query($conn,$sql_show_customer);
    $customer_name = mysqli_fetch_array($customer_result);
    if($thao_tac == "send") {
        $sql_insert_comment_rate = "Insert into product_comment(user_id,product_info_id,comment,rate) values('$customer_id','$product_info_id','$comment','$rate')";
        mysqli_query($conn,$sql_insert_comment_rate);
    }
    echo json_encode(['msg' => 'ok','customer_name' => $customer_name['full_name'],'date' => Date('d-m-Y',time())]);
    exit();
?>
