<?php
    include_once("../lib/database.php");
    //redirect_if_login_success();
    if(is_get_method()) {
        include_once("email.php");
        include_once("include/head.meta.php");
        $forgive = isset($_REQUEST['forgive']) ? $_REQUEST['forgive'] : null;
        $isSend = isset($_REQUEST['isSend']) ? $_REQUEST['isSend'] : null;
        $email = isset($_REQUEST['email']) ? $_REQUEST['email'] : null;
        $alert = "";
        $test = false;
        $login ="location.href='login.php';";
        $register ="location.href='register.php';";
        $link_forgive_pass = "http://localhost/project/admin/reset_password.php?forgive={$_COOKIE['forgive']}";;
        if($isSend && $email) {
            if(sendEmail($link_forgive_pass,$email)){
                $test = true;
                $alert = "<script>
                $.alert({
                'title': 'Thông báo',
                'content': 'Đã gửi link khôi phục mật khẩu vui lòng kiểm tra email của bạn',
                });
                </script>";
            }
        } else if($forgive) {
            if($forgive == $_COOKIE['forgive']) {
                $test = true;
                $alert = "<script>
                $.alert({
                    'title': 'Thông báo',
                    'content': 'Xác nhận email thành công, vui lòng khôi phục mật khẩu của bạn',
                });
                </script>";
                $_SESSION['f_email'] = $_COOKIE['forgive_email'];
               
                // code to be executed get method
?>
<!--html & css section start-->
<body class="hold-transition login-page">
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
    <div class="login-box">
        <div class="login-logo">
            <a href="index.php"><b>Website bán hàng</b></a>
        </div>
        <!-- /.login-logo -->
        <div class="card">
            <div class="card-body login-card-body">
                <p class="login-box-msg">Khôi phục mật khẩu của bạn</p>
                <form action="<?php echo get_url_current_page();?>" method="post" onsubmit="return validate()">
                    <div class="input-group mb-3">
                        <div class="input-group">
                            <input id="password" type="password" name="password" class="form-control" placeholder="Nhập mật khẩu mới..." value="">
                            <div class="input-group-text">
                                <span class="fas fa-lock"></span>
                            </div>
                        </div>
                        <div class="text-danger"></div>
                    </div>
                    <div class="input-group mb-3">
                        <div class="input-group">
                            <input id="confirm_password" type="password" name="confirm_password" class="form-control" placeholder="Xác nhận mật khẩu...">
                            <div class="input-group-text">
                                <span class="fas fa-lock"></span>
                            </div>
                        </div>
                        <div class="text-danger"></div>
                    </div>
                    <input type="hidden" name="token" value="<?php echo_token();?>">
                    <div class="row">
                        <div class="col-12">
                            <button type="submit" class="btn btn-primary btn-block">Khôi phục mật khẩu</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>

<!--html & css section end-->
<?php
    }
}
?>
<?php
    include_once("include/bottom.meta.php");
?>

<!--js section start-->

<script>
     const validate = () => {
          let test = true;
          let confirm_password = document.getElementById('confirm_password').value;
          let password = document.getElementById('password').value;
          if(!password){
            $.alert({
                title: "Thông báo",
                content: "Mật khẩu không được để trống"
            });
            test = false;
          } else if(!confirm_password) {
            $.alert({
                title: "Thông báo",
                content: "Xác nhận mật khẩu không được để trống"
            });
            test = false;
          } else if(password !== confirm_password) {
            $.alert({
                title: "Thông báo",
                content: "Bạn xác nhận mật khẩu không khớp với mật khẩu bạn nhập."
            });
            test = false;
          }
          return test;
     }
</script>
<!--js section end-->

<?=$test ? $alert : "";?>
<?php
    include_once("include/footer.php");
?>
<?php
    } else if (is_post_method()) {
        $email = $_SESSION['f_email'];
        $password = isset($_REQUEST["password"]) ? $_REQUEST["password"] : null;
        //$confirm_password = isset($_REQUEST["confirm_password"]) ? $_REQUEST["confirm_password"] : null;
        $password = password_hash($password,PASSWORD_DEFAULT);
        $sql = "update user set password='$password' where email='$email'";
        sql_query($sql);
        header("location:login.php");
        /*$sql = "select id,username,email,password,img_name,paging,count(*) as 'countt' from user where email = ? limit 1";
        $row = fetch_row($sql,[$email]);
        if($row['countt'] == 0) {
            $_SESSION["error"] = "Email bạn đăng nhập không tồn tại";
        }
        if($row['countt'] > 0){
            if(password_verify($password,$row["password"])){
                $_SESSION["isLoggedIn"] = true;
                $_SESSION["id"] = $row["id"];
                $_SESSION["username"] = $row["username"];
                $_SESSION["email"] = $row["email"];
                $_SESSION["img_name"] = $row["img_name"];
                $_SESSION["paging"] = $row["paging"];
                if($remember) {
                    $pass_encrypt = encrypt_decrypt($password,'encrypt');
                    setcookie("co_email",$row["email"],time() + 3600 * 24,"/");
                    setcookie("co_password",$pass_encrypt,time() + 3600 * 24,"/");
                }
                redirect_if_login_success();
            } else {
                $_SESSION["error"] = "Tài khoản hoặc mật khẩu bạn đăng nhập không chính xác";
            }
        }*/
        
    }
    //}
?>