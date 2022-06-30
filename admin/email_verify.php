<?php
    $html = '<div style="margin:0;padding:0" bgcolor="#FFFFFF"><table width="100%" height="100%" style="min-width:348px" border="0" cellspacing="0" cellpadding="0" lang="en"><tbody><tr height="32" style="height:32px"><td></td></tr><tr align="center"><td><div><div></div></div><table border="0" cellspacing="0" cellpadding="0" style="padding-bottom:20px;max-width:516px;min-width:220px"><tbody><tr><td width="8" style="width:8px"></td><td><div style="border-style:solid;border-width:thin;border-color:#dadce0;border-radius:8px;padding:40px 20px" align="center" class="m_-5645504512316095871mdv2rw"><div style="font-family:' . "'Google Sans'" . ',Roboto,RobotoDraft,Helvetica,Arial,sans-serif;border-bottom:thin solid #dadce0;color:rgba(0,0,0,0.87);line-height:32px;padding-bottom:24px;text-align:center;word-break:break-word"><div style="font-size:24px">Xác thực email</div><table align="center" style="margin-top:8px"><tbody><tr style="line-height:normal"><td align="right" style="padding-right:8px"></td><td><a style="font-family:' . "'Google Sans'" . ',Roboto,RobotoDraft,Helvetica,Arial,sans-serif;color:rgba(0,0,0,0.87);font-size:14px;line-height:20px"></a></td></tr></tbody></table> </div><div style="font-family:Roboto-Regular,Helvetica,Arial,sans-serif;font-size:14px;color:rgba(0,0,0,0.87);line-height:20px;padding-top:20px;text-align:center"><div style="padding-top:32px;text-align:center"><a href="" style="font-family:'. "'Google Sans'" . ',Roboto,RobotoDraft,Helvetica,Arial,sans-serif;line-height:16px;color:#ffffff;font-weight:400;text-decoration:none;font-size:14px;display:inline-block;padding:10px 24px;background-color:#4184f3;border-radius:5px;min-width:90px" target="_blank">Kiểm tra hoạt động</a></div></div><div style="padding-top:20px;font-size:12px;line-height:16px;color:#5f6368;letter-spacing:0.3px;text-align:center"><br><a style="color:rgba(0,0,0,0.87);text-decoration:inherit"></a></div></div><div style="text-align:left"><div style="font-family:Roboto-Regular,Helvetica,Arial,sans-serif;color:rgba(0,0,0,0.54);font-size:11px;line-height:18px;padding-top:12px;text-align:center"><div>Chúng tôi gửi email này để thông báo cho bạn biết về những thay đổi quan trọng đối với Tài khoản Google và dịch vụ của bạn.</div><div style="direction:ltr">© 2021 Google LLC, <a class="m_-5645504512316095871afal" style="font-family:Roboto-Regular,Helvetica,Arial,sans-serif;color:rgba(0,0,0,0.54);font-size:11px;line-height:18px;padding-top:12px;text-align:center">1600 Amphitheatre Parkway, Mountain View, CA 94043, USA</a></div></div></div></td><td width="8" style="width:8px"></td></tr></tbody></table></td></tr><tr height="32" style="height:32px"><td></td></tr></tbody></table></div>' ;
?>
<?php
    include_once("../lib/database.php");
    if(is_get_method()) {
        include_once("email.php");
        $token = isset($_REQUEST['token']) ? $_REQUEST['token'] : null;
        $sql_get_email = "select id from user where email_verify_token='$token' limit 1";
        $result = fetch(sql_query($sql_get_email));
        $test = false;
        if($result["id"] == $_SESSION["id"]) {
            $test = true;
            $alert = "<script>
                $.alert({
                    'title': 'Thông báo',
                    'content': 'Chúc mừng bạn đã xác thực email thành công.',
                    'buttons': {
                        'Ok': function(){
                            location.href='information.php';
                        }
                    }
                });
            </script>";
            $verify_at = Date("Y-m-d h-i-s");
            $session_id = $_SESSION["id"];
            $sql_update_email_verify_at = "Update user set email_verify_at = '$verify_at' where id = '$session_id'";
            sql_query($sql_update_email_verify_at);
        }
        include_once("include/head.meta.php");
?>
<!--html & css section start-->
<!--html & css section end-->
<?php
        include_once("include/footer.php");
?>
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