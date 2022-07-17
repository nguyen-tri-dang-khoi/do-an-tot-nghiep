<?php
//
// print_r($_SESSION['isShipperLoggedIn']);
if(isset($_SESSION['isShipperLoggedIn']) && $_SESSION["isShipperLoggedIn"] == true) {
    header("Location:index.php");
    exit();
} ?>