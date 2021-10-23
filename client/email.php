<?php
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;
use PHPMailer\PHPMailer\Exception;
require '../lib/vendor/autoload.php';
function sendEmail($html,$email,$subject = "tech-shop-user@x10host.com"){
    $mail = new PHPMailer(true);
    //Server settings
    //$mail->SMTPDebug = SMTP::DEBUG_SERVER;
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
    $mail->setFrom("nguyentridangkhoi@gmail.com", "tech-shop");
    $mail->addAddress($email);
    $mail->Subject = $subject;
    $mail->Body = $html;
    return $mail->send();
}

