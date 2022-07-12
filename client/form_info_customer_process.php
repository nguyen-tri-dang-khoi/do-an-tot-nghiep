<?php
    include_once 'db.php';
    $thao_tac = isset($_REQUEST['thao_tac']) ? $_REQUEST['thao_tac'] : null;
    $full_name = isset($_REQUEST['full_name']) ? $_REQUEST['full_name'] : null;
    $email = isset($_REQUEST['email']) ? $_REQUEST['email'] : null;
    $phone = isset($_REQUEST['phone']) ? $_REQUEST['phone'] : null;
    $address = isset($_REQUEST['address']) ? $_REQUEST['address'] : null;
    $_SESSION['customer_id'] = isset($_SESSION['customer_id']) ? $_SESSION['customer_id'] : null;
    if($thao_tac == "updateInfo"){
        $customer_id = $_SESSION['customer_id'];
        $conn = connect();
        $sql_update_info = "Update user set full_name = '$full_name',email = '$email',phone = '$phone',address = '$address' where id = '$customer_id' and type = 'customer'";
        $result = mysqli_query($conn, $sql_update_info);
        header("location: form_info_customer.php");
        exit();
    } else if($thao_tac == "updateInfoConfirmCheckout"){
        $customer_id = $_SESSION['customer_id'];
        $conn = connect();
        $sql_update_info = "Update user set full_name = '$full_name',email = '$email',phone = '$phone',address = '$address' where id = '$customer_id' and type = 'customer'";
        $result = mysqli_query($conn, $sql_update_info);
        header("location: confirm_checkout.php");
        exit();
    }
?>