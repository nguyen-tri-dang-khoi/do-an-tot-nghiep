cam on ban da mua hang cua chung toi `

<?php 
    include_once '../db.php';
    $conn = connect();
    $order_id = $_SESSION['order_pm_history_id'];
    $transaction_id = $_GET['vnp_TransactionNo'];
    $transactionStatus_id = $_GET['vnp_TransactionStatus'];
    if($transactionStatus_id == 0)
    {
        $sql_insert_order_pm_history= "Insert into order_payment_history(order_id,transaction_code,payment_method_id,payment_status_id,note_payment) 
        value('$order_id','$transaction_id','4',1,'Thanh toán thành công')";
        $sql_upt_payment_status = "Update orders set payment_status_id = 1 where id = '$order_id'";
        mysqli_query($conn,$sql_upt_payment_status);
    }else{
        echo "â";
        $sql_insert_order_pm_history= "Insert into order_payment_history(order_id,transaction_code,payment_method_id,payment_status_id,note_payment) 
        value('$order_id','$transaction_id','4',2,'Thanh toán không thành công')";
        $sql_upt_payment_status = "Update orders set payment_status_id = 2 where id = '$order_id'";
        mysqli_query($conn,$sql_upt_payment_status);
    }
    
    print_r ($sql_upt_payment_status);
    header('Location:http://localhost:8080/project/client/form_info_customer.php');

?>