<?php
    include_once 'db.php';
    $password = isset($_REQUEST['password']) ? $_REQUEST['password'] : null;
    if(isset($_SESSION['forgive_password'])) {
        $email = encrypt_decrypt($_SESSION['forgive_password']['forgive_password_token'],'decrypt');
        if($_SERVER['REQUEST_METHOD'] == "POST") {
            if($password){
                $conn = connect();
                $password = password_hash($password,PASSWORD_DEFAULT);
                $sql_change_pass = "Update user set password = '$password' where email = '$email'";
                mysqli_query($conn,$sql_change_pass);
            }
        }
    }
    
    
?>