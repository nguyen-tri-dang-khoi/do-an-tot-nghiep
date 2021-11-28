<?php
    include_once("../lib/database.php");
    $id = isset($_REQUEST["id"]) ? $_REQUEST["id"] : null;
    $func = isset($_REQUEST["func"]) ? $_REQUEST["func"] : null;
    if($id && $func == 'ins_more') {
        print_r(generate_breadcrumb_menus_3($id));
    } else if($id) {
        print_r(generate_breadcrumb_menus($id));
    }
?>