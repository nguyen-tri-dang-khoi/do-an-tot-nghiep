<?php
    include_once("db.php");
    $conn = connect();
    $thao_tac = isset($_REQUEST['thao_tac']) ? $_REQUEST['thao_tac'] : null;
    $order_id = isset($_REQUEST['order_id']) ? $_REQUEST['order_id'] : null;
    if($_SERVER["REQUEST_METHOD"] == "POST") {
        if($thao_tac == "huy_don_hang") {
            $sql_cancel = "Update orders set is_cancel = 1 where id = $order_id";
            mysqli_query($conn,$sql_cancel);
            echo json_encode(["msg" => "ok"]);
            exit();
        }
    }
   
?>