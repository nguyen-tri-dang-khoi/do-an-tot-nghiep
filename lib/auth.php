<?php
    function check_access_token(){
        if(isset($_COOKIE['access_token'])) {
            $user_data_json = encrypt_decrypt($_COOKIE['access_token'],"decrypt");
            $user_data = (array)json_decode($user_data_json);
            if($user_data['type'] == 'admin' || $user_data['type'] == 'officer') {
                $_SESSION["isLoggedIn"] = true;
                $_SESSION["id"] = $user_data["id"];
                $_SESSION["email"] = $user_data["email"];
                $_SESSION["img_name"] = $user_data["img_name"];
                $_SESSION["paging"] = $user_data["paging"];
            } 
        }
    }
    function check_shipper_access_token(){
        if(isset($_COOKIE['shipper_access_token'])) {
            $user_data_json = encrypt_decrypt($_COOKIE['shipper_access_token'],"decrypt");
            $user_data = (array)json_decode($user_data_json);
            if($user_data_json['type'] == 'shipper'){
                $_SESSION["isShipperLoggedIn"] = true;
                $_SESSION["shipper_id"] = $user_data["id"];
                $_SESSION["shipper_email"] = $user_data["email"];
                $_SESSION["shipper_img_name"] = $user_data["img_name"];
                $_SESSION["shipper_paging"] = $user_data["paging"];
            } 
        }
    }
    function echo_token(){
        echo "123";
    }
    function is_post_method(){
        return $_SERVER["REQUEST_METHOD"] == "POST";
    }
    function logout_session_timeout($type = 'admin|officer'){
		$_SESSION['timestamp'] = isset($_SESSION['timestamp']) ? $_SESSION['timestamp'] : time();
		$result = (time() - $_SESSION['timestamp']) / 60;
        //log_v($result);
		if($result > 100){
            if($type == 'admin|officer') {
                $_SESSION["isLoggedIn"] = false;
                if(isset($_COOKIE['access_token'])){
                    setcookie('access_token','',time() - 3600,"/","",false,false);
                }
            } else if($type == 'shipper') {
                $_SESSION["isShipperLoggedIn"] = false;
                if(isset($_COOKIE['shipper_access_token'])){
                    setcookie('shipper_access_token','',time() - 3600,"/","",false,false);
                }
            }
			unset($_SESSION["timestamp"]);
            redirect_if_login_status_false();
			exit();
		}
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
    function redirect_if_login_success($uri_login_success_redirect = "information.php",$type = 'admin|officer') {
        if($type == 'admin|officer') {
            if(isset($_SESSION["isLoggedIn"]) && $_SESSION['isLoggedIn'] !== false) {
                if(isset($_SESSION["redirect"])) {
                    header("location: " . $_SESSION['redirect']);
                    unset($_SESSION["redirect"]);
                    exit();
                }
                header("location:$uri_login_success_redirect");
                exit();
            }
        } else if($type == 'shipper') {
            if(isset($_SESSION["isShipperLoggedIn"]) && $_SESSION['isShipperLoggedIn']) {
                if(isset($_SESSION["redirect"])) {
                    header("location: " . $_SESSION['redirect']);
                    unset($_SESSION["redirect"]);
                    exit();
                }
                header("location:$uri_login_success_redirect");
                exit();
            }
        }
        
    }
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
    function redirect_if_customer_login_status_false($uri_login_redirect = "login.php") {
        if(!isset($_SESSION["isUserLoggedIn"]) || $_SESSION["isUserLoggedIn"] !== true){
            $_SESSION["redirect"] = get_url_current_page();
            header("location:$uri_login_redirect");
            exit();
        }
    }
    function encrypt_decrypt($string, $action = 'encrypt')
    {
        $encrypt_method = "AES-256-CBC";
        $secret_key = 'AA@!74C@DCC2BBR!@B63$C27'; // user define private key
        $secret_iv = 'khoi_dep_trai24324@#2'; // user define secret key
        $key = hash('sha256', $secret_key);
        $iv = substr(hash('sha256', $secret_iv), 0, 16); // sha256 is hash_hmac_algo
        if ($action == 'encrypt') {
            $output = openssl_encrypt($string, $encrypt_method, $key, 0, $iv);
            $output = base64_encode($output);
        } else if ($action == 'decrypt') {
            $output = openssl_decrypt(base64_decode($string), $encrypt_method, $key, 0, $iv);
        }
        return $output;
    }
?>