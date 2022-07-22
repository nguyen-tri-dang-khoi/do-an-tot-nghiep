<?php
if(isset($_SESSION['isShipperLoggedIn']) && $_SESSION["isShipperLoggedIn"] == true) {
    header("Location:shipper_order.php");
    exit();
} ?>