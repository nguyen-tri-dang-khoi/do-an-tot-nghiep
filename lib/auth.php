<?php
    function echo_token($url = ""){
        if($url == "") {
            $url = get_url_current_page();
        }
        if(empty($_SESSION['key'])){
            $_SESSION['key'] = bin2hex(random_bytes(32));
        }
        echo hash_hmac('sha256', $url, $_SESSION["key"]);
    }
    function is_post_method($token = "",$url = ""){
        if($url == ""){
            $url = get_url_current_page();
        }
        if(isset($_POST["token"])) {
            $token = $_POST["token"];
        }
        if(empty($_SESSION['key'])){
            $_SESSION['key'] = bin2hex(random_bytes(32));
        }
        return hash_equals($token, hash_hmac('sha256', $url, $_SESSION["key"])) && $_SERVER["REQUEST_METHOD"] == "POST";
    }
    function is_get_method_csrf($token = "",$url = ""){
        if($url == ""){
            $url = get_url_current_page();
        }
        if(isset($_POST["token"])) {
            $token = $_POST["token"];
        }
        if(empty($_SESSION['key'])){
            $_SESSION['key'] = bin2hex(random_bytes(32));
        }
        return hash_equals($token, hash_hmac('sha256', $url, $_SESSION["key"])) && ($_SERVER["REQUEST_METHOD"] == "GET");
    }
    function is_get_method(){
        return ($_SERVER["REQUEST_METHOD"] == "GET");
    }
    //================redirect===================//
    //f_t
    function get_url_current_page() {
        return (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on' ? "https" : "http") . "://$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]";
    }
    //f_u
    function redirect_if_login_status_false($uri_login_redirect = "login.php") {
        if(!isset($_SESSION["isLoggedIn"]) || $_SESSION["isLoggedIn"] !== true){
            $_SESSION["redirect"] = get_url_current_page();
            header("location:$uri_login_redirect");
            exit();
        }
    }
    //f_w
    function redirect_if_login_success($uri_login_success_redirect = "index.php") {
        if(isset($_SESSION["isLoggedIn"]) && $_SESSION['isLoggedIn']) {
            if(isset($_SESSION["redirect"])) {
                header("location: " . $_SESSION['redirect']);
                unset($_SESSION["redirect"]);
                exit();
            }
            header("location:$uri_login_success_redirect");
            exit();
        }
    }
    //f_x
    function redirect_if_customer_login_success($uri_login_success_redirect = "index.php") {
        if(isset($_SESSION["isUserLoggedIn"]) && $_SESSION['isUserLoggedIn']) {
            if(isset($_SESSION["redirect"])) {
                header("location: " . $_SESSION['redirect']);
                unset($_SESSION["redirect"]);
                exit();
            }
            header("location:$uri_login_success_redirect");
            exit();
        }
    }
    //f_y
    function redirect_if_customer_login_status_false($uri_login_redirect = "login.php") {
        if(!isset($_SESSION["isUserLoggedIn"]) || $_SESSION["isUserLoggedIn"] !== true){
            $_SESSION["redirect"] = get_url_current_page();
            header("location:$uri_login_redirect");
            exit();
        }
    }
    //f_z
?>