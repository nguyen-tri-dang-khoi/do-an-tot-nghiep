

<?php
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;
use PHPMailer\PHPMailer\Exception;
date_default_timezone_set('Asia/Ho_Chi_Minh');
require '../lib/vendor/autoload.php';
include_once "include/head.php";
include_once "db.php";
function sendEmail($html,$email,$subject = "TNC"){
    $mail = new PHPMailer(true);
    $mail->isSMTP();
    $mail->Host = "smtp.gmail.com";
    $mail->SMTPAuth = true;
    $mail->Username = "huykhoitnc@gmail.com";
    $mail->Password = 'pelomiosydpvrwpd';
    $mail->Port = 465; 
    $mail->SMTPSecure = "ssl"; 
    $mail->isHTML(true);
    $mail->setFrom("huykhoitnc@gmail.com", "TNC Store");
    $mail->addAddress($email);
    $mail->Subject = $subject;
    $mail->Body = $html;
    return $mail->send();
}

if($_SERVER['REQUEST_METHOD'] == "POST") {
    $conn = connect();
    $email = isset($_REQUEST['email']) ? $_REQUEST['email'] : null;
    $sql_check_email_exist_customer = "select count(*) as 'cnt' from user where email = '$email' and type = 'customer' and is_delete = 0";
    // kiem tra email ton tai
    $result_count = mysqli_query($conn,$sql_check_email_exist_customer);
    $row_count = mysqli_fetch_array($result_count);
    $row_count = $row_count['cnt'];
    if($row_count == 0) {
        $_SESSION['forgive_password_error'] = "Email này không tồn tại";
        $_SESSION['forgive_password_email'] = $email;
        header("Location:reset_password.php");
        exit();
    }

    // xu ly gui mail
    $token_auth = encrypt_decrypt($email,'encrypt');
    $_SESSION['forgive_password'] = 
    [
        'forgive_password_token' => $token_auth,
        'time_start' => Date("Y-m-d h:i:s",time()),
        'time_end' => Date("Y-m-d h:i:s",strtotime("+15 minutes",strtotime(Date("Y-m-d h:i:s",time()))))
    ];
    if($_SESSION['forgive_password'] && $email) {
        $forgive_password_token = $_SESSION['forgive_password']['forgive_password_token'];
        $link_ = "localhost/project/client2/forgive_password2.php?forgive_password_token=" . $forgive_password_token;
        $html = '
        <div style="margin:0;padding:0" bgcolor="#FFFFFF">
            <table width="100%" height="100%" style="min-width:348px" border="0" cellspacing="0" cellpadding="0" lang="en">
                <tbody>
                    <tr height="32" style="height:32px">
                        <td></td>
                    </tr>
                    <tr align="center">
                        <td>
                            <div>
                                <div></div>
                            </div>
                            <table border="0" cellspacing="0" cellpadding="0" style="padding-bottom:20px;max-width:516px;min-width:220px">
                                <tbody>
                                    <tr>
                                        <td width="8" style="width:8px"></td>
                                        <td>
                                            <div style="border-style:solid;border-width:thin;border-color:#dadce0;border-radius:8px;padding:40px 20px" align="center" class="m_-5645504512316095871mdv2rw"><div style="font-family:' . "'Google Sans'" . ',Roboto,RobotoDraft,Helvetica,Arial,sans-serif;border-bottom:thin solid #dadce0;color:rgba(0,0,0,0.87);line-height:32px;padding-bottom:24px;text-align:center;word-break:break-word">
                                                <div style="font-size:24px">Xác thực email</div>
                                                    <table align="center" style="margin-top:8px">
                                                        <tbody>
                                                            <tr style="line-height:normal">
                                                                <td align="right" style="padding-right:8px"></td>
                                                                <td>
                                                                    <a style="font-family:' . "'Google Sans'" . ',Roboto,RobotoDraft,Helvetica,Arial,sans-serif;color:rgba(0,0,0,0.87);font-size:14px;line-height:20px"></a>
                                                                </td>
                                                            </tr>
                                                        </tbody>
                                                    </table> 
                                                </div>
                                                <div style="font-family:Roboto-Regular,Helvetica,Arial,sans-serif;font-size:14px;color:rgba(0,0,0,0.87);line-height:20px;padding-top:20px;text-align:center">
                                                    Chúng tôi đã gửi email xác thực, bạn hãy click vào link dưới đây: 
                                                    <div style="padding-top:32px;text-align:center">
                                                        <a href="' . $link_ . '" style="font-family:'. "'Google Sans'" . ',Roboto,RobotoDraft,Helvetica,Arial,sans-serif;line-height:16px;color:#ffffff;font-weight:400;text-decoration:none;font-size:14px;display:inline-block;padding:10px 24px;background-color:#4184f3;border-radius:5px;min-width:90px" target="_blank">Link xác thực</a>
                                                    </div>
                                                </div>
                                                <div style="padding-top:20px;font-size:12px;line-height:16px;color:#5f6368;letter-spacing:0.3px;text-align:center">Bạn nhận được email này bởi bạn đang tiến hành xác thực email cá nhân trên trang web chúng tôi, Nếu không phải là bạn vui lòng báo cáo cho chúng tôi biết <br>
                                                    <a style="color:rgba(0,0,0,0.87);text-decoration:inherit"></a>
                                                </div>
                                            </div>
                                            <div style="text-align:left">
                                                <div style="font-family:Roboto-Regular,Helvetica,Arial,sans-serif;color:rgba(0,0,0,0.54);font-size:11px;line-height:18px;padding-top:12px;text-align:center">
                                                    <div>Trân trọng, Đội ngũ TNC.</div>
                                                    <div style="direction:ltr">©2022 TNC Store 
                                                        <a class="m_-5645504512316095871afal" style="font-family:Roboto-Regular,Helvetica,Arial,sans-serif;color:rgba(0,0,0,0.54);font-size:11px;line-height:18px;padding-top:12px;text-align:center">38 đường 101 Phường Thạnh Mỹ Lợi Quận 2 TPHCM.</a>
                                                    </div>
                                                </div>
                                            </div>
                                        </td>
                                        <td width="8" style="width:8px">
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                        </td>
                    </tr>
                    <tr height="32" style="height:32px">
                        <td></td>
                    </tr>
                </tbody>
            </table>
        </div>
        ' ;
        if(sendEmail($html,$email)){
            echo "<script>
                $.alert({
                    title: 'Thông báo',
                    content: 'Chúng tôi đã gửi link xác thực vào email của bạn, vui lòng kiểm tra',
                    buttons:{
                        'Ok':function(){
                            location.reload();
                        }
                    }
                })
            </script>";
        }
    }
}
?>
