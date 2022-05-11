<?php
    include_once("../lib/database.php");
    if(is_get_method()) {
        include_once("include/head.meta.php");
        include_once("include/menu.php");
        // code to be executed get method
?>
<!--html & css section start-->
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
<div role="main" class="main">
    <section class="page-header page-header-modern bg-color-light-scale-1 page-header-lg">
        <div class="container">
            <div class="row">
                <div class="col-md-12 align-self-center p-static order-2 text-center">
                    <h1 class="font-weight-bold text-dark">Đăng nhập tài khoản</h1>
                </div>
                <!--<div class="col-md-12 align-self-center order-1">
                    <ul class="breadcrumb d-block text-center">
                        <li><a href="#">Home</a></li>
                        <li class="active">Pages</li>
                    </ul>
                </div>-->
            </div>
        </div>
    </section>
    <div class="container py-4">
        <div class="row justify-content-center">
            <div class="col-md-6 col-lg-5 mb-5 mb-lg-0">
                <h2 class="font-weight-bold text-5 mb-0">Đăng nhập</h2>
                <form action="login.php" id="frmSignIn" method="post" class="needs-validation">
                    <div class="row">
                        <div class="form-group col">
                            <label class="form-label text-color-dark text-3">Email <span class="text-color-danger">*</span></label>
                            <input name="email" type="text" value="<?=isset($_COOKIE['u_co_email']) ? $_COOKIE['u_co_email'] : "";?>" class="form-control form-control-lg text-4" required>
                        </div>
                    </div>
                    <div class="row">
                        <div class="form-group col">
                            <label class="form-label text-color-dark text-3">Mật khẩu <span class="text-color-danger">*</span></label>
                            <input name="password" type="password" value="<?=isset($_COOKIE['u_co_password']) ? encrypt_decrypt($_COOKIE['u_co_password'],'decrypt') : "";?>" class="form-control form-control-lg text-4" required>
                        </div>
                    </div>
                    <div class="row justify-content-between">
                        <div class="form-group col-md-auto">
                            <div class="custom-control custom-checkbox">
                                <input type="checkbox" name="remember" class="custom-control-input" value="y" <?=(isset($_COOKIE['u_co_remember']) && $_COOKIE['u_co_remember']=='y') ? "checked" : "";?> id="remember">
                                <label class="form-label custom-control-label cur-pointer text-2" for="remember">Nhớ tôi</label>
                            </div>
                        </div>
                        <div class="form-group col-md-auto">
                            <a class="text-decoration-none text-color-dark text-color-hover-primary font-weight-semibold text-2" href="forgive_password.php">Quên mật khẩu ?</a>
                        </div>
                    </div>
                    <input type="hidden" name="token" value="<?php echo_token();?>">
                    <div class="row">
                        <div class="form-group col">
                            <button type="submit" class="btn btn-dark btn-modern w-100 text-uppercase rounded-0 font-weight-bold text-3 py-3">Đăng nhập</button>
                            <div class="divider">
                                <span class="bg-light px-4 position-absolute left-50pct top-50pct transform3dxy-n50">Hoặc</span>
                            </div>
                            <a href="#" class="btn btn-primary-scale-2 btn-modern w-100 text-transform-none rounded-0 font-weight-bold align-items-center d-inline-flex justify-content-center text-3 py-3"><i class="fab fa-facebook text-5 me-2"></i> Đăng nhập bằng Facebook cá nhân</a>
                        </div>
                    </div>
                </form>
            </div>
        </div>

    </div>
</div>
<!--html & css section end-->
<?php
        include_once("include/footer.php");
?>
<?php
        include_once("include/bottom.meta.php");
?>
<!--js section start-->

<!--js section end-->
<?php
        include_once("include/footer.php"); 
?>
<?php
    } else if (is_post_method()) {
        $email = isset($_REQUEST["email"]) ? $_REQUEST["email"] : null;
        $password = isset($_REQUEST["password"]) ? $_REQUEST["password"] : null;
        $remember = isset($_REQUEST["remember"]) ? $_REQUEST["remember"] : null;
        $sql = "select id,email,password,img_name,count(*) as 'countt' from customer where email = ? limit 1";
        $row = fetch_row($sql,[$email]);
        if($row['countt'] == 0) {
            $_SESSION["error"] = "Email bạn đăng nhập không tồn tại";
        }
        if($row['countt'] > 0){
            if(password_verify($password,$row["password"])){
                $_SESSION["isUserLoggedIn"] = true;
                $_SESSION["customer_id"] = $row["id"];
                $_SESSION["email"] = $row["email"];
                $_SESSION["img_name"] = $row["img_name"];
                $_SESSION["cart"] = [];
                if($remember) {
                    $pass_encrypt = encrypt_decrypt($password,'encrypt');
                    setcookie("u_co_remember","y",time() + 3600 * 24,"/");
                    setcookie("u_co_email",$row["email"],time() + 3600 * 24,"/");
                    setcookie("u_co_password",$pass_encrypt,time() + 3600 * 24,"/");
                } else {
                    if(isset($_COOKIE['u_co_email']) ) {
                        setcookie("u_co_email","",time() - 3600,"/");
                    }
                    if(isset($_COOKIE['u_co_password']) ) {
                        setcookie("u_co_password","",time() - 3600,"/");
                    }
                    if(isset($_COOKIE['u_co_remember']) ) {
                        setcookie("u_co_remember","",time() - 3600,"/");
                    }
                }
                header("location:index.php");
                exit();
            } else {
                $_SESSION["error"] = "Tài khoản hoặc mật khẩu bạn đăng nhập không chính xác";
            }
        }
        header("location:login.php");
        // code to be executed post method
    }
?>