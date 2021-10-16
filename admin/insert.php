<?php
include_once("../../lib/database.php");
include_once("../../config.php");
function generateRandomString($length = 10) {
    $characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
    $charactersLength = strlen($characters);
    $randomString = '';
    for ($i = 0; $i < $length; $i++) {
        $randomString .= $characters[rand(0, $charactersLength - 1)];
    }
    return $randomString;
}
function generateRandomNumber($length = 10) {
    $characters = '0123456789';
    $charactersLength = strlen($characters);
    $randomString = '';
    for ($i = 0; $i < $length; $i++) {
        $randomString .= $characters[rand(0, $charactersLength - 1)];
    }
    return $randomString;
}
for($i = 0 ; $i < 50 ; $i++) {
    db_insert('customer',[
        'full_name' => generateRandomString(),
        'email' => generateRandomString()."@gmail.com",
        'phone' => generateRandomNumber(),
        'address' => generateRandomNumber(11),
        'birthday' => generateRandomNumber(4)."-".generateRandomNumber(1)."-".generateRandomNumber(2),
        'username' => generateRandomNumber(5),
        'password' => password_hash('1234567',PASSWORD_DEFAULT),
    ]);
}
?>