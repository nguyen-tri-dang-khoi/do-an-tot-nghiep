<?php
    include_once 'db.php';
    $_SESSION['login_error']= isset($_SESSION['login_error']) ? $_SESSION['login_error'] : "";
    if(isset($_SESSION['customer_id'])) {
        header("Location:index.php");
    }
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Change Password</title>
    <link rel="stylesheet" href="Style_signin_login.css">
    <script src="js/jquery.min.js"></script>
    <style>
        .text-danger {
            color:red;
        }
    </style>
</head>
<body>
    
<div class="container" id="container">
    <div class="form-container sign-in-container">
        <form id="form-login" action="Login_signup_process.php" method="post" onsubmit="return validateLogin()">
            <h1>Thay đổi mật khẩu</h1>
            <div class="social-container">
            </div>
            <span>Nhập mật khẩu</span>
            <input id="login_email" type="password" name="pass word" placeholder="password" />
            <span id="login_email_err" class="text-danger"></span>
            <input id="login_email" type="password" name="pass word" placeholder="confirm password" />
            <span id="login_email_err" class="text-danger"></span>
            <span id="login_password_err" class="text-danger"></span>
            <input type="hidden" name="thao_tac" value="Login">
            <button type="submit">Change Password</button>
        </form>
    </div>

    <div class="overlay-container">
        <div class="overlay">
            <div class="overlay-panel overlay-right">
                <h1>Cảm ơn vì tiếp tục sử dụng dịch vụ của chúng tôi!</h1>
                <p>Quý khác vui lòng nhập mật khẩu cho tài khoản của mình</p>
                <!-- <button type="button" class="ghost" id="signIn">Đăng nhập</button> -->
            </div>
        </div>
    </div>
</div>

    <script>
        const signUpButton = document.getElementById('signUp');
        const signInButton = document.getElementById('signIn');
        const container = document.getElementById('container');

        signUpButton.addEventListener('click', () => {
            container.classList.add('right-panel-active');
        });

        signInButton.addEventListener('click', () => {
            container.classList.remove('right-panel-active');
        });
        function validateLogin(){
            $('span.text-danger').text("");
            let test = true;
            let phone_reg = /^\d{10}$/;
            let email_reg = /^[A-Za-z0-9+_.-]+@(.+)/;
            let email = $('#login_email').val();
            let password = $('#login_password').val();
            if(email == "") {
                $('#login_email_err').text("Vui lòng không để trống email");
                test = false;
            } else if(!email.match(email_reg)) {
                $('#login_email_err').text("Email không đúng đinh dạng");
                test = false;
            } 
            if(password == ""){
                $('#login_password_err').text("Vui lòng không để trống mật khẩu");
                test = false;
            }
            return test;
        }

        function validateRegister(){
            $('span.text-danger').text("");
            let test = true;
            let phone_reg = /^\d{10}$/;
            let email_reg = /^[A-Za-z0-9+_.-]+@(.+)/;
            let full_name = $('#register_full_name').val();
            let email = $('#register_email').val();
            let password = $('#register_password').val();
            if(full_name == "") {
                $('#register_full_name_err').text("Email không đúng đinh dạng");
                test = false;
            } else if(full_name.length > 200) {
                $('#register_full_name_err').text("Email có độ dài 200 ký tự trở lên");
                test = false;
            }

            if(email == "") {
                $('#register_email_err').text("Email không được để trống");
                test = false;
            } else if(!email.match(email_reg)) {
                $('#register_email_err').text("Email không đúng đinh dạng");
                test = false;
            }

            if(password == "") {
                $('#register_password_err').text("Vui lòng không để trống mật khẩu");
                test = false;
            }
            return test;
        }
    </script>
</body>
</html>