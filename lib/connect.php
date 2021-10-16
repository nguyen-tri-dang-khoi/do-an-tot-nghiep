<?php
    session_start();
    // database connect setting
    $link = db_connect();
    function db_connect() {         
        $host = 'localhost';
        $dbname = 'uguflupr_techshop'; //id16910140_do_an_tot_nghiep
        $username = 'uguflupr_techshop';  //id16910140_khoi
        $password = 'Khoi17042000@'; //>iSxpL-6>Q{GbTq<
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
?>