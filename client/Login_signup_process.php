<?php
    include_once 'db.php';
    $thao_tac = isset($_REQUEST['thao_tac']) ? $_REQUEST['thao_tac'] : null;
    $email = isset($_REQUEST['email']) ? $_REQUEST['email'] : null;
    $password = isset($_REQUEST['password']) ? $_REQUEST['password'] : null;
    $full_name = isset($_REQUEST['full_name']) ? $_REQUEST['full_name'] : null;
    $conn = connect();
    if($thao_tac ==  "Login") {
        $sql_get_email_password = "Select id,password from user where type = 'customer' and email = '$email'";
        $result = mysqli_query($conn, $sql_get_email_password);
        $row = mysqli_fetch_assoc($result);
        if(password_verify($password,$row['password'])){
            $_SESSION['customer_id'] = $row['id'];
            header("location: index.php");
            exit();
        } else {
            $_SESSION['login_error'] = "Email hoặc mật khẩu bạn đăng nhập bị sai!";
            header("location: Login_signup.php");
            exit();
        }
    } else if($thao_tac ==  "SignUp") {
        $password = password_hash($password,PASSWORD_DEFAULT);
        $sql_signup = "Insert into user(type,full_name,email,password) values('customer','$full_name','$email','$password')";
        $result = mysqli_query($conn, $sql_signup);
        header("location: Login_signup.php");
        exit();
    }
?>