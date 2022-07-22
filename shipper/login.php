<?php
    include_once("../lib/database.php");
    if(is_get_method()) {
        include_once("include/login_redirect.php"); 
        include_once("include/head.meta.php");
?>
<!--html & css section start-->
<style>
    .shipper-page {
        background-image:url("img/shipper.jpg");
        height: 100%;
        background-position: center;
        background-repeat: no-repeat;
        background-size: cover;
    }
</style>
<body class="shipper-page center">
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
    <div class="login-box" style="background-color:#ab3030;width:500px;">
        <div class="login-logo">
            <a href="index.php"><b></b></a>
        </div>
        
        <!-- /.login-logo -->
        <div class="card">
            <div class="card-body login-card-body" style="box-shadow:-1px 2px 17px 5px #66666663;width:500px;">
            <h4 class="login-box-msg" style="padding:0px;font-weight:bold;color:#d9585c;">Website phân hệ giao hàng</h4>
            <hr>
            <form action="login.php" method="post" onsubmit="return validate()">
                <div class="mb-3">
                    <label for="" style="color:#d9585c;">Email</label>
                    <div class="">
                        <input id="email" type="email" name="email" class="form-control" placeholder="Email" value="<?=isset($_COOKIE['shipper_co_email']) ? $_COOKIE['shipper_co_email'] : "";?>">
                        <!--<div class="-text">
                        <span class="fas fa-envelope"></span>
                        </div>-->
                    </div>
                    <div class="text-danger"></div>
                </div>
                <div class="mb-3">
                    <label for="" style="color:#d9585c;">Mật khẩu</label>
                    <div class="">
                        <input id="password"  type="password" name="password" class="form-control" placeholder="Password" value="<?=isset($_COOKIE['shipper_co_password']) ? encrypt_decrypt($_COOKIE['shipper_co_password'],'decrypt') : "";?>">
                        <!--<div class="input-group-text">
                            <span class="fas fa-lock"></span>
                        </div>-->
                    </div>
                    <div class="text-danger"></div>
                </div>
                <div style="display:flex;justify-content:space-between;">
                    <div class="input-group mb-3">
                        <!-- <a href="forgive_password.php" style="color:#d9585c;font-weight:bold;">Quên mật khẩu</a> -->
                    </div>
                    <div style="justify-content:end;" class="input-group mb-3">
                        <div class="icheck-primary">
                            <label for="remember" style="color:#d9585c;">
                                Nhớ tôi
                            </label>
                            <input style="accent-color:#d9585cc4;" name="remember" value="y"  type="checkbox" <?=(isset($_COOKIE['shipper_co_remember']) && $_COOKIE['shipper_co_remember']=='y') ? "checked" : "";?> id="remember">
                        </div>
                    </div>
                </div>
                <hr>
                <input type="hidden" name="token" value="<?php echo_token();?>">
                <div class="row a-center j-center">
                    <div class="col-5">
                        <button type="submit" class="btn btn-block" style="background-color:#d9585c;color:#fff;font-weight:bold;border-radius:15px;">Đăng nhập</button>
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
        $sql = "select id,type,email,password,img_name,paging,is_lock,count(*) as 'countt' from user where email = ? and type = 'shipper' limit 1";
        $row = fetch(sql_query($sql,[$email]));
        if($row['countt'] == 0) {
            $_SESSION["error"] = "Email bạn đăng nhập không tồn tại";
        }
        if($row['is_lock'] == 1) {
            $_SESSION["error"] = "Tài khoản của bạn đã bị khoá, vui lòng liên hệ admin để mở khoá tài khoản";
            $test_lock = false;
        }
        if($row['countt'] > 0 && $test_lock){
            if(password_verify($password,$row["password"])){
                $_SESSION["isShipperLoggedIn"] = true;
                $_SESSION["shipper_id"] = $row["id"];
                $_SESSION["shipper_email"] = $row["email"];
                $_SESSION["shipper_img_name"] = $row["img_name"];
                $_SESSION["shipper_paging"] = $row["paging"];
                // $user_data_json = json_encode([
                //     "id" => $row["id"],
                //     "type" => 'shipper',
                //     "email" => $row["email"],
                //     "img_name" => $row["img_name"],
                //     "paging" => $row["paging"],
                //     "rand" => rand(1,1000000),
                //     "expire_at" => time()
                // ]);
                
                // $access_token = encrypt_decrypt($user_data_json,"encrypt");
                // setcookie("shipper_access_token",$access_token,time() + 60 * 60 * 24,"/","",false,true);
                if($remember) {
                    $pass_encrypt = encrypt_decrypt($password,'encrypt');
                    setcookie("shipper_co_remember","y",time() + 3600 * 24 * 30,"/","",false,true);
                    setcookie("shipper_co_email",$row["email"],time() + 3600 * 24 * 30,"/","",false,true);
                    setcookie("shipper_co_password",$pass_encrypt,time() + 3600 * 24 * 30,"/","",false,true);
                } else {
                    if(isset($_COOKIE['shipper_co_email']) ) {
                        setcookie("shipper_co_email","",time() - 3600,"/");
                    }
                    if(isset($_COOKIE['shipper_co_password']) ) {
                        setcookie("shipper_co_password","",time() - 3600,"/");
                    }
                    if(isset($_COOKIE['shipper_co_remember']) ) {
                        setcookie("shipper_co_remember","",time() - 3600,"/");
                    }
                }
                header("Location:shipper_order.php");
            } else {
                $_SESSION["error"] = "Tài khoản hoặc mật khẩu bạn đăng nhập không chính xác";
            }
        }
        header("location:login.php");
    }
    //}
?>