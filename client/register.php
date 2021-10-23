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
                    <h1 class="font-weight-bold text-dark">Đăng ký tài khoản</h1>
                </div>
            </div>
        </div>
    </section>
    <div class="container py-4">
        <div class="row justify-content-center">
            <div class="col-md-6 col-lg-5">
                <h2 class="font-weight-bold text-5 mb-0">Đăng ký</h2>
                <form action="register.php" id="frmSignUp" method="post" onsubmit="return validate();">
                    <div class="row">
                        <div class="form-group col">
                            <label class="form-label text-color-dark text-3">Tên đăng nhập <span class="text-color-danger">*</span></label>
                            <input name="username" type="text" value="" class="form-control form-control-lg text-4">
                        </div>
                    </div>
                    <div class="row">
                        <div class="form-group col">
                            <label class="form-label text-color-dark text-3">Email <span class="text-color-danger">*</span></label>
                            <input name="email" type="text" value="" class="form-control form-control-lg text-4">
                        </div>
                    </div>
                    <div class="row">
                        <div class="form-group col">
                            <label class="form-label text-color-dark text-3">Mật khẩu <span class="text-color-danger">*</span></label>
                            <input name="password" type="password" value="" class="form-control form-control-lg text-4">
                        </div>
                    </div>
                    <div class="row">
                        <div class="form-group col">
                            <label class="form-label text-color-dark text-3">Xác nhân mật khẩu <span class="text-color-danger">*</span></label>
                            <input name="confirm_password" type="password" value="" class="form-control form-control-lg text-4">
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
<script>
    const validate = () => {
        let test = true;
        let username = $('input[name="username"]').val();
        let email = $('input[name="email"]').val();
        let password = $('input[name="password"]').val();
        let confirm_password = $('input[name="confirm_password"]').val();
        if(!username){
            $.alert({
                title: "Thông báo",
                content: "Tên đăng nhập không được để trống"
            });
            //alert("Tên đăng nhập không được để trống");
            test = false;
        } else if(!email) {
            $.alert({
                title: "Thông báo",
                content: "Email không được để trống"
            });
            //alert("Email không được để trống");
            test = false;
        } else if(!password) {
            $.alert({
                title: "Thông báo",
                content: "Mật khẩu không được để trống"
            });
            //alert("Mật khẩu không được để trống");
            test = false;
        } else if(!confirm_password) {
            $.alert({
                title: "Thông báo",
                content: "Xác nhận mật khẩu không được để trống."
            });
            //alert("Xác nhận mật khẩu không được để trống.");
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
            $password = password_hash($password,PASSWORD_DEFAULT);
            //$id = db_insert_id('user',['username'=>$username,'password'=>$password]);
            $time = Date("d-m-Y h:i:s",time());
            $hidden_key = "&!239yhf98@";
            $rand = rand(0,999999);
            $md5_str = md5($email.$time.$rand.$hidden_key);
            setcookie("u_verify",$md5_str,time() + 600,"/");
            setcookie("u_cookie_username",$username,time() + 600,"/");
            setcookie("u_cookie_password",$password,time() + 600,"/");
            setcookie("u_cookie_email",$email,time() + 600,"/");
            header("location:email_tmp_verify.php?email={$email}");
            exit();
        }
        header("location:register.php");
    }
?>