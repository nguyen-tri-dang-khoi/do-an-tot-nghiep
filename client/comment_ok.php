<?php
    include_once("../lib/database.php");
    $status = isset($_REQUEST['status']) ? $_REQUEST['status'] : null;
    $comment = isset($_REQUEST['comment']) ? $_REQUEST['comment'] : null;
    $customer_id = isset($_REQUEST['customer_id']) ? $_REQUEST['customer_id'] : null;
    $product_info_id = isset($_REQUEST['product_info_id']) ? $_REQUEST['product_info_id'] : null;
    if($status == "Insert") {
        $sql_comment = "Insert into product_comment(customer_id, product_info_id,comment) values('$customer_id','$product_info_id','$comment')";
        db_query($sql_comment);
        $success = "success";
    }
    echo_json(['msg' => 'ok','success' => $success,'created_at' => Date('d-m-Y h:i:s',time())]);
?>