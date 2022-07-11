<?php
    include_once 'db.php';
    $_SESSION['login_error']= isset($_SESSION['login_error']) ? $_SESSION['login_error'] : "";
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>login_signin</title>
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
    <div class="form-container sign-up-container">
        <form id="form-register" action="Login_signup_process.php" method="post" onsubmit="return validateRegister()">
            <div class="social-container">
                <h1>Đăng ký</h1>
            </div>
            <span>Sử dụng email của bạn để đăng ký </span>
            <input type="text" id="register_full_name" name="full_name" placeholder="Tên" />
            <span id="register_full_name_err" class="text-danger"></span>
            <input type="email" id="register_email" name="email" placeholder="Email" />
            <span id="register_email_err" class="text-danger"></span>
            <input type="password" id="register_password" name="password" placeholder="Mật Khẩu" />
            <span id="register_password_err" class="text-danger"></span>
            <input type="hidden" name="thao_tac" value="SignUp">
            <button type="submit">Đăng ký</button>
        </form>
    </div>
    <div class="form-container sign-in-container">
        <form id="form-login" action="Login_signup_process.php" method="post" onsubmit="return validateLogin()">
            <h1>Đăng nhập</h1>
            <div class="social-container">
            </div>
            <span>Sử dụng tài khoản của bạn</span>
            <input id="login_email" type="email" name="email" placeholder="Email" />
            <span id="login_email_err" class="text-danger"></span>
            <input id="login_password" type="password" name="password" placeholder="Mật Khẩu" />
            <span id="login_password_err" class="text-danger"></span>
            <input type="hidden" name="thao_tac" value="Login">
            <p><?php echo $_SESSION['login_error'];?></p>
            <?php
                $_SESSION['login_error'] = "";
            ?>
            <a href="forgive_password.php">Quên mật khẩu?</a>
            <button type="submit">Đăng nhập</button>
        </form>
    </div>
    <div class="overlay-container">
        <div class="overlay">
            <div class="overlay-panel overlay-left">
                <h1>Chào mừng quay trở lại!</h1>
                <p>Để giữ kết nối với chúng tôi, vui lòng đăng nhập bằng thông tin cá nhân của bạn</p>
                <button type="button" class="ghost" id="signIn">Đăng nhập</button>
            </div>
            <div class="overlay-panel overlay-right">
                <h1>Xin chào, bạn!</h1>
                <p>Nhập thông tin cá nhân của bạn và bắt đầu hành trình với chúng tôi</p>
                <button type="button" class="ghost" id="signUp">Đăng ký</button>
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