<?php
    include_once("../lib/database.php");
    if(is_get_method()) {
        include_once("email.php");
        $email = isset($_REQUEST['email']) ? $_REQUEST['email'] : null;
        $verify = isset($_REQUEST['u_verify']) ? $_REQUEST['u_verify'] : null;
        $link_mail = "http://localhost/project/client/email_tmp_verify.php?u_verify={$_COOKIE['u_verify']}";
        $alert= "";
        $login ="location.href='login.php';";
        $register ="location.href='register.php';";
        $test = false;
        if($email) {
          if(sendEmail($link_mail,$email)) {
               $test = true;
               $alert = "<script>
               $.alert({
               'title': 'Thông báo',
               'content': 'Đã gửi link xác thực vui lòng kiểm tra email của bạn',
               });
               </script>";
          }
        }
        if($verify) {
           if(isset($_COOKIE['u_verify'])){
               if($verify == $_COOKIE['u_verify']) {
                    $test = true;
                    $alert = "<script>
                         $.alert({
                         'title': 'Thông báo',
                         'content': 'Chúc mừng bạn đã xác thực email thành công, vui lòng đăng nhập lại',
                         });{$login}
                         </script>";
                    if($_COOKIE['u_cookie_email'] && $_COOKIE['u_cookie_username'] && $_COOKIE['u_cookie_password']) {
                         $email = $_COOKIE['u_cookie_email'];
                         $username = $_COOKIE['u_cookie_username'];
                         $password = $_COOKIE['u_cookie_password'];
                         $sql = "insert into customer(email,username,password) values('$email','$username','$password')";                      
                         sql_query($sql);
                         //db_insert('user',['email' => $_COOKIE['cookie_email'],'username' => $_COOKIE['cookie_username'],'password' => $_COOKIE['cookie_password']]);
                    } else {
                         $test = true;
                         $alert = "<script>
                         $.alert({
                              'title': 'Thông báo',
                              'content': 'Đã hết phiên xác thực email bạn vui lòng đăng ký lại',
                         });{$register}
                         </script>";
                    }
               } else {
                    $test = true;
                    $alert = "<script>
                    $.alert({
                    'title': 'Thông báo',
                    'content': 'Đã hết phiên xác thực email bạn vui lòng đăng ký lại',
                    });{$register}
                    </script>";
               }
           } else {
               $test = true;
               $alert = "<script>
               $.alert({
               'title': 'Thông báo',
               'content': 'Đã hết phiên xác thực email bạn vui lòng đăng ký lại',
               });{$register}
               </script>";        
           }
        }
        include_once("include/head.meta.php");
?>
<!--html & css section start-->
<!--html & css section end-->
<?php
        include_once("include/bottom.meta.php");
?>
<!--js section start-->
<?=$test ? $alert : "";?>
<!--js section end-->
<?php
        include_once("include/footer.php"); 
?>
<?php
    } else if (is_post_method()) {
        // code to be executed post method
    }
?>