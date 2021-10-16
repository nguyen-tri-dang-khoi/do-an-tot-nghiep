<?php
    include_once("../lib/database.php");
    if(is_get_method()) {
        include_once("include/head.meta.php");
        // code to be executed get method
?>
<!--html & css section start-->
<body class="hold-transition register-page">
    <?php
        if(isset($_SESSION["error"])){
    ?>
            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                <?php echo $_SESSION["error"]; ?>
                <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
    <?php
            unset($_SESSION["error"]);
        }
    ?>
    <div class="register-box">
        <div class="register-logo">
            <b>Website bán hàng</b> 
        </div>
        <div class="card">
            <div class="card-body register-card-body">
            <p class="login-box-msg">Hãy đăng ký ngay để trở thành admin</p>
            <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"])?>" method="post" onsubmit="return validate()">
                <div class="input-group mb-3">
                    <div class="input-group">
                        <input id="username" type="text" name="username" class="form-control" placeholder="Username...">
                        <div class="input-group-text">
                            <span class="fas fa-user"></span>
                        </div>
                    </div>
                    <div class="text-danger"></div>
                </div>
                <div class="input-group mb-3">
                    <div class="input-group">
                        <input id="email" type="email" name="email" class="form-control" placeholder="Email...">
                        <div class="input-group-text">
                            <span class="fas fa-envelope"></span>
                        </div>
                    </div>
                    <div class="text-danger"></div>
                </div>
                <div class="input-group mb-3">
                    <div class="input-group">
                        <input id="password" type="password" name="password" class="form-control" placeholder="Password...">
                        <div class="input-group-text">
                            <span class="fas fa-lock"></span>
                        </div>
                    </div>
                    <div class="text-danger"></div>
                </div>
                <div class="input-group mb-3">
                    <div class="input-group">
                        <input id="confirm_password" type="password" name="confirm_password" class="form-control <?php empty($confirm_pass_err) ? 'has-error' : '' ;?>" placeholder="Confirm password...">
                        <div class="input-group-text">
                            <span class="fas fa-lock"></span>
                        </div>
                    </div>
                    <div class="text-danger"></div>
                </div>
                <input type="hidden" name="token" value="<?php echo_token();?>">
                <div class="row">
                    <div class="col-4">
                        <button type="submit" class="btn btn-primary btn-block">Register</button>
                    </div>
                </div>
            </form>

            <a href="login.php" class="text-center">Tôi là admin</a>
            </div>
        </div>
    </div>
<!--html & css section end-->
<?php
        include_once("include/bottom.meta.php");
?>
<!--js section start-->
<script>
    const validate = () => {
        let test = true;
        let username = document.getElementById('username').value;
        let email = document.getElementById('email').value;
        let password = document.getElementById('password').value;
        let confirm_password = document.getElementById('confirm_password').value;
        if(!username){
            alert("Tên đăng nhập không được để trống");
            test = false;
        } else if(!email) {
            alert("Email không được để trống");
            test = false;
        } else if(!password) {
            alert("Mật khẩu không được để trống");
            test = false;
        } else if(!confirm_password) {
            alert("Xác nhận mật khẩu không được để trống.");
            test = false;
        } else if(password !== confirm_password) {
            alert("Bạn xác nhận mật khẩu không khớp với mật khẩu bạn nhập.");
            test = false;
        }
        return test;
     }
</script>
<!--js section end-->
<?php
        include_once("include/footer.php");
?>
<?php
    } else if (is_post_method()) {
        // code to be executed post method
        /*$result = php_validate([
            $_POST["username"]."a" => ['required' => "Username",'min' => 1,'max' => 200],
            $_POST["email"]."b" => ['required' => "Email",'max' => 200],
            $_POST["password"]."c" => ['required' => "Mật khẩu",'min' => 5,'max' => 20],
            $_POST["confirm_password"]."d" => ['required' => "Xác nhận mật khẩu",'equal' => $_POST['password']],
        ]);*/
        //if(!array_key_exists("error",$result)){
        /*$row = db_query([
            's'=>['id'],
            'f'=>['user'],
            'w_o'=>[['username','='],['email','=']],
            'lim' => ['?']
        ],[$_POST["username"],$_POST["email"],1]);*/
        $username = isset($_REQUEST["username"]) ? $_REQUEST["username"] : null;
        $email = isset($_REQUEST["email"]) ? $_REQUEST["email"] : null;
        $password = isset($_REQUEST["password"]) ? $_REQUEST["password"] : null;
        $sql = "select id,count(*) as 'countt' from user where username = ? and email = ? limit 1";
        $row = fetch_row($sql,[$username,$email]);
        if($row['countt'] > 0){
            $msg_error = "Tên đăng nhập hoặc email này đã tồn tại.";
            $_SESSION["error"] = $msg_error;
        }
        if(!isset($_SESSION["error"]))
        {
            $password = password_hash($password,PASSWORD_DEFAULT);
            if(db_insert('user',['email'=>$email,'username'=>$username,'password'=>$password]))
            {
                header("location:login.php");
                exit();
            }
        }
        //} else {
            // Báo lỗi dữ liệu đầu vào không hợp lệ
         //   $_SESSION['error'] = $result["error"];
       // }
        header("location:register.php");
    }
?>