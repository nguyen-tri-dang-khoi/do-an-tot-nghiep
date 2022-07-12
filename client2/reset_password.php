<?php
    include_once 'db.php';
    $_SESSION['forgive_password_error']= isset($_SESSION['forgive_password_error']) ? $_SESSION['forgive_password_error'] : "";
    $_SESSION['forgive_password_email']= isset($_SESSION['forgive_password_email']) ? $_SESSION['forgive_password_email'] : "";

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Reset Password</title>
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
        <form id="form-login" action="forgive_password.php" method="post" onsubmit="return validateLogin()">
            <h1>Quên mật khẩu</h1>
            <div class="social-container">
            </div>
            <span>Hãy điền email tài khoản bạn sủ dụng</span>
            <input id="login_email" type="email" value="<?php echo $_SESSION['forgive_password_email'];?>" name="email" placeholder="Email" />
            <span id="login_email_err" class="text-danger"><?php echo $_SESSION['forgive_password_error'];?></span>

            <button type="submit">Thiết lập lại mật khẩu</button>
        </form>
    </div>

    <div class="overlay-container">
        <div class="overlay">
            <div class="overlay-panel overlay-right">
                <h1>Cảm ơn vì tiếp tục sử dụng dịch vụ của chúng tôi!</h1>
                <p>Hãy kiểm tra email sau 1 vài phút! để nhận liên kết thay đổi mật khẩu</p>
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
            return test;
        }
    </script>
</body>
</html>

<?php
     $_SESSION['forgive_password_error'] = "";
     $_SESSION['forgive_password_email'] = "";
?>