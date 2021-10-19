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
                    <h1 class="font-weight-bold text-dark">Register</h1>
                </div>
            </div>
        </div>
    </section>
    <div class="container py-4">
        <div class="row justify-content-center">
            <div class="col-md-6 col-lg-5">
                <h2 class="font-weight-bold text-5 mb-0">Đăng ký</h2>
                <form action="register.php" id="frmSignUp" method="post">
                    <div class="row">
                        <div class="form-group col">
                            <label class="form-label text-color-dark text-3">Tên đăng nhập <span class="text-color-danger">*</span></label>
                            <input name="username" type="text" value="" class="form-control form-control-lg text-4" required>
                        </div>
                    </div>
                    <div class="row">
                        <div class="form-group col">
                            <label class="form-label text-color-dark text-3">Email <span class="text-color-danger">*</span></label>
                            <input name="email" type="text" value="" class="form-control form-control-lg text-4" required>
                        </div>
                    </div>
                    <div class="row">
                        <div class="form-group col">
                            <label class="form-label text-color-dark text-3">Mật khẩu <span class="text-color-danger">*</span></label>
                            <input name="password" type="password" value="" class="form-control form-control-lg text-4" required>
                        </div>
                    </div>
                    <!--<div class="row">
                        <div class="form-group col">
                            <p class="text-2 mb-2">Your personal data will be used to support your experience throughout this website, to manage access to your account, and for other purposes described in our <a href="#" class="text-decoration-none">privacy policy.</a></p>
                        </div>
                    </div>-->
                    <input type="hidden" name="token" value="<?php echo_token();?>">
                    <div class="row">
                        <div class="form-group col">
                            <button type="submit" class="btn btn-dark btn-modern w-100 text-uppercase rounded-0 font-weight-bold text-3 py-3">Đăng ký</button>
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
        $username = isset($_REQUEST["username"]) ? $_REQUEST["username"] : null;
        $email = isset($_REQUEST["email"]) ? $_REQUEST["email"] : null;
        $password = isset($_REQUEST["password"]) ? $_REQUEST["password"] : null;
        $sql = "select id,count(*) as 'countt' from customer where username = ? and email = ? limit 1";
        $row = fetch_row($sql,[$username,$email]);
        //log_v($sql);
        if($row['countt'] > 0){
            $msg_error = "Tên đăng nhập hoặc email này đã tồn tại.";
            $_SESSION["error"] = $msg_error;
        }
        if(!isset($_SESSION["error"]))
        {
            //log_v($password);
            $password = password_hash($password,PASSWORD_DEFAULT);
            if(db_insert('customer',['email'=>$email,'username'=>$username,'password'=>$password]))
            {
                header("location:login.php");
                exit();
            }
        }
        header("location:register.php");
    }
?>