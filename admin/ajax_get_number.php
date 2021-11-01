<?php
    include_once("../lib/database.php");
    $status = isset($_REQUEST['status']) ? $_REQUEST['status'] : null;
    if($status) {
        if($status == "count_pi") {
            $sql = "select count(*) as 'countt' from product_info where is_delete = 0";
        } else if($status == "count_pt") {
            $parent_id = isset($_REQUEST['parent_id']) ? $_REQUEST['parent_id'] : null;
            if($parent_id){
                $sql = "select count(*) as 'countt' from product_type where is_delete = 0 and parent_id = '$parent_id'";
            } else {
                $sql = "select count(*) as 'countt' from product_type where is_delete = 0 and parent_id is null";
            }       
        } else if($status == "count_n") {
            $sql = "select count(*) as 'countt' from notification where is_delete = 0";
        } else if($status == "count_user") {
            $sql = "select count(*) as 'countt' from user where is_delete = 0";
        }
        $count = fetch_row($sql)['countt'];
        echo_json(['msg' => 'ok','count' => $count]);
    }
    
?>