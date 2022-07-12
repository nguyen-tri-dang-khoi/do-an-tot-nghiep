<?php
    include_once 'db.php';
    $old_pass = isset($_REQUEST['old_pass']) ? $_REQUEST['old_pass'] : null;
    $new_pass = isset($_REQUEST['new_pass']) ? $_REQUEST['new_pass'] : null;
    $customer_id = isset($_SESSION['customer_id']) ? $_SESSION['customer_id'] : null;
    if($_SERVER['REQUEST_METHOD'] == "POST") {
        if($old_pass && $new_pass && $customer_id){
            $conn = connect();
            $sql_get_customer = "select * from customer where id = $customer_id and type = 'customer'";
            $result_customer = mysqli_query($conn,$sql_get_customer);
            $row = mysqli_fetch_array($result_customer);
            if(password_verify($old_pass,$row['password'])) {
                $password = password_hash($new_pass,PASSWORD_DEFAULT);
                $sql_change_pass = "Update customer set password = '$password' where id = '$customer_id'";
                mysqli_query($conn,$result_customer);
            }
        }
    }
    
?>