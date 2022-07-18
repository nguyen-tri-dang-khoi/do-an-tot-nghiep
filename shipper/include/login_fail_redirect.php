<?php
    if(!isset($_SESSION['isShipperLoggedIn']) || $_SESSION["isShipperLoggedIn"] == false) {
        header("Location:login.php");
        exit();
    }
?>