<?php
    include_once 'db.php';
    $_SESSION['login_error']= isset($_SESSION['login_error']) ? $_SESSION['login_error'] : "";
    $_SESSION['register_error'] = isset($_SESSION['register_error']) ? $_SESSION['register_error'] : "";
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
    
<div class="container <?=$_SESSION['register_error'] ? "right-panel-active" : "";?>" id="container">
    <div class="form-container sign-up-container ">
        <form id="form-register" action="Login_signup_process.php" method="post" onsubmit="return validateRegister()">
            <div class="social-container">
                <h1>Đăng ký</h1>
            </div>
            <span>Sử dụng email của bạn để đăng ký </span>
        
            <input type="text" id="register_full_name" name="full_name" placeholder="Tên đầy đủ" />
            <span id="register_full_name_err" class="text-danger"></span>
           
            <input type="email" id="register_email" name="email" placeholder="Email" />
            <span id="register_email_err" class="text-danger"></span>
            
            <input name="phone" id="register_phone" type="text" value="" class="form-control"  placeholder="Số điện thoại">
            <span id="register_phone_err" class="text-danger"></span> 
            
            <input type="date" id="register_birthday" name="birthday" placeholder="Ngày sinh" />
            <span id="register_birthday_err" class="text-danger"></span> 

            <input type="text" id="register_address" name="address" placeholder="Địa chỉ" />
            <span id="register_address_err" class="text-danger"></span>
            
            <input type="password" id="register_password" name="password" placeholder="Mật Khẩu" />

            <span id="register_password_err" class="text-danger"></span>
            <input type="hidden" name="thao_tac" value="SignUp">
            <?php echo $_SESSION['register_error'];?>
            
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
            <a href="reset_password.php">Quên mật khẩu?</a>
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
            let phone = $('#register_phone').val();
            let birthday = $('#register_birthday').val();
            let password = $('#register_password').val();
            let address = $('#register_address').val();
            console.log(full_name);
            console.log(email);
            console.log(phone);
            console.log(birthday);
            console.log(password);
            console.log(address);
            if(full_name == "") {
                $('#register_full_name_err').text("Tên đầy đủ không được để trống");
                test = false;
            } else if(full_name.length > 200) {
                $('#register_full_name_err').text("Tên đầy đủ phải có độ dài bé hơn 200");
                test = false;
            } 
            
            if(phone == "") {
                $('#register_phone_err').text("Số điện thoại không được để trống");
                test = false;
            } else if(!phone.match(phone_reg)) {
                $('#register_phone_err').text("Số điện thoại phải có 10 số");
                test = false;
            }

            if(birthday == "") {
                $('#register_birthday_err').text("Ngày sinh không được để trống");
                test = false;
            } else {
                let year_18_to_milisecond = 568024668000; //18year = 568024668000 milisecond
                birthday = birthday.split('/');
                birthday = `${birthday[2]}-${birthday[1]}-${birthday[0]}`;
                if(Date.parse(new Date().toISOString().slice(0,10)) - Date.parse(birthday) < year_18_to_milisecond){
                    $('#register_birthday_err').text("Khách hàng phải có độ tuổi từ 18 trở lên");
                    test = false;
                }
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
            } else if(password < 4){
                $('#register_password_err').text("Mật khẩu từ 4 ký tự trở lên");
                test = false;
            }

            if(address == "") {
                $('#register_address_err').text("Địa chỉ không được để trống");
                test = false;
            }
            return test;
        }
    </script>
</body>
</html>
<?php
    $_SESSION['register_error'] = "";
    $_SESSION['login_error'] = "";
?>