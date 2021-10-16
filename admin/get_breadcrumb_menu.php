<?php
    include_once("../lib/database.php");
    $id = isset($_REQUEST["id"]) ? $_REQUEST["id"] : null;
    if($id) {
        print_r(generate_breadcrumb_menus($id));
    }
?>