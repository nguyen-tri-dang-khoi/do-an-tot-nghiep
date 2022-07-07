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
</head>
<body>
    
<div class="container" id="container">
    <div class="form-container sign-up-container">
        <form action="Login_signup_process.php" method="post">
            <div class="social-container">
                <h1>Đăng ký</h1>
                <!-- <a href="#" class="social"><i class="fab fa-facebook-f"></i></a>
                <a href="#" class="social"><i class="fab fa-google-plus-g"></i></a>
                <a href="#" class="social"><i class="fab fa-linkedin-in"></i></a> -->
            </div>
            <span>Sử dụng email của bạn để đăng ký </span>
            <input type="text" name="full_name" placeholder="Tên" />
            <input type="email" name="email" placeholder="Email" />
            <input type="password" name="password" placeholder="Mật Khẩu" />
            <input type="hidden" name="thao_tac" value="SignUp">
            <button>Đăng ký</button>
        </form>
    </div>
    <div class="form-container sign-in-container">
        <form action="Login_signup_process.php">
            <h1>Đăng nhập</h1>
            <div class="social-container">
                <!-- <a href="#" class="social"><i class="fab fa-facebook-f"></i></a>
                <a href="#" class="social"><i class="fab fa-google-plus-g"></i></a>
                <a href="#" class="social"><i class="fab fa-linkedin-in"></i></a> -->
            </div>
            <span>Sử dụng tài khoản của bạn</span>
            <input type="email" name="email" placeholder="Email" />
            <input type="password" name="password" placeholder="Mật Khẩu" />
            <input type="hidden" name="thao_tac" value="Login">
            <p><?php echo $_SESSION['login_error'];?></p>
            <?php
                $_SESSION['login_error'] = "";
            ?>
            <a href="#">Quên mật khẩu?</a>
            <button>Đăng nhập</button>
        </form>
    </div>
    <div class="overlay-container">
        <div class="overlay">
            <div class="overlay-panel overlay-left">
                <h1>Chào mừng quay trở lại!</h1>
                <p>Để giữ kết nối với chúng tôi, vui lòng đăng nhập bằng thông tin cá nhân của bạn</p>
                <button class="ghost" id="signIn">Đăng nhập</button>
            </div>
            <div class="overlay-panel overlay-right">
                <h1>Xin chào, bạn!</h1>
                <p>Nhập thông tin cá nhân của bạn và bắt đầu hành trình với chúng tôi</p>
                <button class="ghost" id="signUp">Đăng ký</button>
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
    </script>
</body>
</html>