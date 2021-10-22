<?php
    include_once("../lib/database.php");
    $status = isset($_REQUEST['status']) ? $_REQUEST['status'] : null;
    $rate = isset($_REQUEST['rate']) ? $_REQUEST['rate'] : null;
    $customer_id = isset($_REQUEST['customer_id']) ? $_REQUEST['customer_id'] : null;
    $product_info_id = isset($_REQUEST['product_info_id']) ? $_REQUEST['product_info_id'] : null;
    if($status == "Insert") {
        $sql_rate = "Insert into product_rate(customer_id, product_info_id,rate) values('$customer_id','$product_info_id','$rate')";
        db_query($sql_rate);
        $success = "success";
    }
    echo_json(['msg' => 'ok','success' => $success,'created_at' => Date('d-m-Y h:i:s',time())]);
?>