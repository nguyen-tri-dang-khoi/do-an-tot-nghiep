<?php
    ini_set('session.cookie_httponly', 1);
    session_start();
    // database connect setting
    $link = db_connect();
    
    function db_connect() {         
        $host = 'localhost';
        $dbname = 'shop_cua_hang_may_tinh'; //id16910140_do_an_tot_nghiep
        $username = 'root';  //id16910140_khoi
        $password = ''; //>iSxpL-6>Q{GbTq<
        try {
            $conn = new PDO("mysql:host=$host;dbname=$dbname", $username, $password);
           /* array(
                PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
                PDO::ATTR_PERSISTENT => false,
                PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES utf8mb4 COLLATE utf8mb4_unicode_ci",
                PDO::ATTR_EMULATE_PREPARES => false
            );*/
            $conn->setAttribute(PDO::ATTR_EMULATE_PREPARES,false);
            $conn->setAttribute(PDO::MYSQL_ATTR_INIT_COMMAND,"SET NAMES utf8mb4 COLLATE utf8mb4_unicode_ci");
            $conn->setAttribute(PDO::ATTR_ERRMODE,PDO::ERRMODE_EXCEPTION); // fix error query LIMIT clause not binding value
        } catch (PDOException $pe) {
            die("Could not connect to the database $dbname :" . $pe->getMessage());
        }
        return $conn;
    }
    function log_v($var){
        echo "<script>". "console.log(`". $var ."`);</script>";
    }
    function log_a($array){
        echo "<script>". "console.log(JSON.parse(`". json_encode($array) ."`));</script>";
    }
    function echo_json($arr){
        echo json_encode($arr);
        exit();
    }
?>