<?php
    include_once 'db.php';
    $thao_tac = isset($_REQUEST['thao_tac']) ? $_REQUEST['thao_tac'] : null;
    $email = isset($_REQUEST['email']) ? $_REQUEST['email'] : null;
    $phone = isset($_REQUEST['phone']) ? $_REQUEST['phone'] : null;
    $password = isset($_REQUEST['password']) ? $_REQUEST['password'] : null;
    $address = isset($_REQUEST['address']) ? $_REQUEST['address'] : null;
    $birthday = isset($_REQUEST['birthday']) ? Date("Y-m-d",strtotime($_REQUEST['birthday'])) : null;
    // print_r($birthday);
    // exit();
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
        $sql_check_email = "select email from user where type = 'customer' and email = '$email' limit 1";
        $sql_check_phone = "select phone from user where type = 'customer' and phone = '$phone' limit 1";
        $check_email = mysqli_query($conn, $sql_check_email);
        $row = mysqli_fetch_array($check_email);
        if($row['email'] && $row['email'] == $email) {
            $_SESSION['register_error'] = "Email này đã tồn tại";
            header("location: Login_signup.php");
            exit();
        }
        $check_phone = mysqli_query($conn, $sql_check_phone);
        $row = mysqli_fetch_array($check_phone);
        if($row['phone'] && $row['phone'] == $phone) {
            $_SESSION['register_error'] = "Số điện thoại này đã tồn tại";
            header("location: Login_signup.php");
            exit();
        }
        $sql_signup = "Insert into user(type,full_name,email,phone,address,birthday,password) values('customer','$full_name','$email','$phone','$address','$birthday','$password')";
        $result = mysqli_query($conn, $sql_signup);
        header("location: Login_signup.php");
        exit();
    }
?>