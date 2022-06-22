<?php
    include_once("../lib/database.php");
    logout_session_timeout();
    check_access_token();
    redirect_if_login_success();
    if(is_get_method()) {
        include_once("include/head.meta.php");
        // code to be executed get method
?>
<!--html & css section start-->
<body class="hold-transition login-page">
    <?php
        if(isset($_SESSION["error"])){
    ?>
            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                <?=$_SESSION["error"];?>
                <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
    <?php
            unset($_SESSION["error"]);
        }
    ?>
    <div class="login-box">
        <div class="login-logo">
            <a href="index.php"><b>Website bán hàng</b></a>
        </div>
        <!-- /.login-logo -->
        <div class="card">
            <div class="card-body login-card-body">
            <p class="login-box-msg">Đăng nhập</p>

            <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"])?>" method="post" onsubmit="return validate()">
                <div class="input-group mb-3">
                    <div class="input-group">
                        <input id="email" type="email" name="email" class="form-control" placeholder="Email" value="<?=isset($_COOKIE['co_email']) ? $_COOKIE['co_email'] : "";?>">
                        <div class="input-group-text">
                        <span class="fas fa-envelope"></span>
                        </div>
                    </div>
                    <div class="text-danger"></div>
                </div>
                <div class="input-group mb-3">
                    <div class="input-group">
                        <input id="password" type="password" name="password" class="form-control" placeholder="Password" value="<?=isset($_COOKIE['co_password']) ? encrypt_decrypt($_COOKIE['co_password'],'decrypt') : "";?>">
                        <div class="input-group-text">
                            <span class="fas fa-lock"></span>
                        </div>
                    </div>
                    <div class="text-danger"></div>
                </div>
                <div style="display:flex;justify-content:space-between;">
                    <div class="input-group mb-3">
                        <a href="forgive_password.php">Quên mật khẩu</a>
                    </div>
                    <div style="justify-content:end;" class="input-group mb-3">
                        <div class="icheck-primary">
                            <label for="remember">
                                Nhớ tôi
                            </label>
                            <input name="remember" value="y"  type="checkbox" <?=(isset($_COOKIE['co_remember']) && $_COOKIE['co_remember']=='y') ? "checked" : "";?> id="remember">
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-5">
                        <button type="submit" class="btn btn-primary btn-block">Đăng nhập</button>
                    </div>
                </div>
            </form>
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
          let email = document.getElementById('email').value;
          let password = document.getElementById('password').value;
          if(!email){
            document.getElementById('email').focus();
            $.alert({
                title: "Thông báo",
                content: "Email không được để trống"
            });
            test = false;
          } else if(!password) {
            document.getElementById('password').focus();
            $.alert({
                title: "Thông báo",
                content: "Mật khẩu không được để trống"
            });
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
        $test_lock = true;
        $email = isset($_REQUEST["email"]) ? $_REQUEST["email"] : null;
        $password = isset($_REQUEST["password"]) ? $_REQUEST["password"] : null;
        $remember = isset($_REQUEST["remember"]) ? $_REQUEST["remember"] : null;
        $sql = "select id,email,password,img_name,paging,is_lock,count(*) as 'countt' from user where email = ? and type = 'officer'  or type = 'admin' limit 1";
        $row = fetch_row($sql,[$email]);
        if($row['countt'] == 0) {
            $_SESSION["error"] = "Email bạn đăng nhập không tồn tại";
        }
        if($row['is_lock'] == 1) {
            $_SESSION["error"] = "Tài khoản của bạn đã bị khoá, vui lòng liên hệ admin để mở khoá tài khoản";
            $test_lock = false;
        }
        if($row['countt'] > 0 && $test_lock){
            if(password_verify($password,$row["password"])){
                $_SESSION["isLoggedIn"] = true;
                $_SESSION["id"] = $row["id"];
                $_SESSION["email"] = $row["email"];
                $_SESSION["img_name"] = $row["img_name"];
                $_SESSION["paging"] = $row["paging"];
                $user_data_json = json_encode([
                    "id" => $row["id"],
                    "type" => $row['type'],
                    "email" => $row["email"],
                    "img_name" => $row["img_name"],
                    "paging" => $row["paging"],
                    "rand" => rand(1,1000000),
                    "expire_at" => time()
                ]);
                
                $access_token = encrypt_decrypt($user_data_json,"encrypt");
                setcookie("access_token",$access_token,time() + 60 * 60 * 24,"/","",false,true);
                if($remember) {
                    $pass_encrypt = encrypt_decrypt($password,'encrypt');
                    setcookie("co_remember","y",time() + 3600 * 24 * 30,"/","",false,true);
                    setcookie("co_email",$row["email"],time() + 3600 * 24 * 30,"/","",false,true);
                    setcookie("co_password",$pass_encrypt,time() + 3600 * 24 * 30,"/","",false,true);
                } else {
                    if(isset($_COOKIE['co_email']) ) {
                        setcookie("co_email","",time() - 3600,"/");
                    }
                    if(isset($_COOKIE['co_password']) ) {
                        setcookie("co_password","",time() - 3600,"/");
                    }
                    if(isset($_COOKIE['co_remember']) ) {
                        setcookie("co_remember","",time() - 3600,"/");
                    }
                }
                redirect_if_login_success("information.php");
            } else {
                $_SESSION["error"] = "Tài khoản hoặc mật khẩu bạn đăng nhập không chính xác";
            }
        }
        header("location:login.php");
    }
    //}
?>