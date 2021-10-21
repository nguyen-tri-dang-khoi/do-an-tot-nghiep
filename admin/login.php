<?php
    include_once("../lib/database.php");
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
            <p class="login-box-msg">Đăng nhập</p>

            <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"])?>" method="post" onsubmit="return validate()">
                <div class="input-group mb-3">
                    <div class="input-group">
                        <input id="email" type="email" name="email" class="form-control" placeholder="Email" value="admin2@gmail.com">
                        <div class="input-group-text">
                        <span class="fas fa-envelope"></span>
                        </div>
                    </div>
                    <div class="text-danger"></div>
                </div>
                <div class="input-group mb-3">
                    <div class="input-group">
                        <input id="password" type="password" name="password" class="form-control" placeholder="Password" value="1234">
                        <div class="input-group-text">
                            <span class="fas fa-lock"></span>
                        </div>
                    </div>
                    <div class="text-danger"></div>
                </div>
                <input type="hidden" name="token" value="<?php echo_token();?>">
                <div class="row">
                    <div class="col-4">
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
            $.alert({
                title: "Thông báo",
                content: "Email không được để trống"
            });
            test = false;
          } else if(!password) {
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
        $email = isset($_REQUEST["email"]) ? $_REQUEST["email"] : null;
        $password = isset($_REQUEST["password"]) ? $_REQUEST["password"] : null;
        $sql = "select id,username,email,password,img_name,paging,count(*) as 'countt' from user where email = ? limit 1";
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
                redirect_if_login_success();
            } else {
                $_SESSION["error"] = "Tài khoản hoặc mật khẩu bạn đăng nhập không chính xác";
            }
        }
        header("location:login.php");
    }
    //}
?>