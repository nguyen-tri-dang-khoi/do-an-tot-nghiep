<?php
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;
use PHPMailer\PHPMailer\Exception;
require '../lib/vendor/autoload.php';
$mail = new PHPMailer(true);
//Server settings
$mail->SMTPDebug = SMTP::DEBUG_SERVER;
//SMTP Settings
$mail->isSMTP();
$mail->Host = "smtp.gmail.com";
$mail->SMTPAuth = true;
$mail->Username = "nguyentridangkhoi@gmail.com";
$mail->Password = 'khoi17042000@';
$mail->Port = 465; //587
$mail->SMTPSecure = "ssl"; //tls

//Email Settings
$mail->isHTML(true);
$mail->setFrom($email, $name);
$mail->addAddress("khoiabcdef@gmail.com");
$mail->Subject = $subject;
$mail->Body = $body;

if ($mail->send()) {
    $status = "success";
    $response = "Email is sent!";
} else {
    $status = "failed";
    $response = "Something is wrong: <br><br>" . $mail->ErrorInfo;
}

exit(json_encode(array("status" => $status, "response" => $response)));
