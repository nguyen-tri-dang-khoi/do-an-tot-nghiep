<?php
    //=====================multiple_menu======================//
    function show_menu($connection = NULL){
        if(!$connection) {
            $connection = $GLOBALS['link'];
        }
        //$connection = db_connect();
        $menu = generate_multilevel_menus($connection,NULL);
        return $menu;
    }
    function show_menu_2($connection = NULL){
        if(!$connection) {
            $connection = $GLOBALS['link'];
        }
        //$connection = db_connect();
        $menu = generate_multilevel_menus_2($connection,NULL);
        return $menu;
    }
    function show_menu_3($connection = NULL){
        if(!$connection) {
            $connection = $GLOBALS['link'];
        }
        //$connection = db_connect();
        $menu = generate_multilevel_menus_3($connection,NULL);
        return $menu;
    }


    function generate_multilevel_menus($connection,$parent_id = NULL){
        $sql = "";
        $menu = "";
        if(is_null($parent_id)) {
            $sql = "select * from product_type where parent_id is NULL and is_delete = 0";
        } else {
            $sql = "select * from product_type where parent_id = $parent_id and is_delete = 0";
        }
        $stmt = $connection->prepare($sql);
        $stmt->execute();
        while($result = $stmt->fetch(PDO::FETCH_ASSOC)) {
            $menu .= "<li onclick='show_menu_root()' class='parent' data-id='" ."{$result["id"]}".  "'><a href='#'>" . $result["name"] . "<span class='expand'>»</span></a>";
            $menu .= "<ul class='child'>" . generate_multilevel_menus($connection,$result["id"]) . "</ul>";
            $menu .= "</li>";
        }
        return $menu;
    }
    function generate_multilevel_menus_3($connection,$parent_id = NULL){
        $sql = "";
        $menu = "";
        if(is_null($parent_id)) {
            $sql = "select * from product_type where parent_id is NULL and is_delete = 0";
        } else {
            $sql = "select * from product_type where parent_id = $parent_id and is_delete = 0";
        }
        $stmt = $connection->prepare($sql);
        $stmt->execute();
        while($result = $stmt->fetch(PDO::FETCH_ASSOC)) {
            $menu .= "<li onclick='show_menu_root()' class='parent' data-id='" ."{$result["id"]}".  "'><a href='#'>" . $result["name"] . "<span class='expand'>»</span></a>";
            $menu .= "<ul class='child'>" . generate_multilevel_menus($connection,$result["id"]) . "</ul>";
            $menu .= "</li>";
        }
        return $menu;
    }
    
    function generate_breadcrumb_menus($id = NULL){
        $sql = "";
        $__arr = [];
        $sql_get_product_type = "select *,count(*) as 'countt' from product_type where id = ? and is_delete = 0";
        $id = fetch_row($sql_get_product_type,[$id]);
        while($id["countt"] > 0) {
            array_unshift($__arr,"<li class='breadcrumb-item'>".$id["name"]."</li>");
            $sql_get_product_type = "select *,count(*) as 'countt' from product_type where id = ? and is_delete = 0";
            $id = fetch_row($sql_get_product_type,[$id['parent_id']]);
            
        }
        $menu = "<ol style='margin-bottom: 25px;padding: 9px 10px;' tabindex='1' class='breadcrumb'>" . implode("",$__arr) . "</ol>";
        return $menu ;
    }
    function generate_breadcrumb_menus_3($id = NULL){
        $sql = "";
        $__arr = [];
        $parent_id="";
        $sql_get_product_type = "select *,count(*) as 'countt' from product_type where id = ? and is_delete = 0";
        $id = fetch_row($sql_get_product_type,[$id]);
        $count=0;
        while($id["countt"] > 0) {
            $parent_id = $id['id'];
            array_unshift($__arr,"<li class='breadcrumb-item breadcrumb-item-aaa'><a style='cursor:pointer;color: #9c27b0;' href='category_manage.php?parent_id=$parent_id'>".$id["name"]."</a></li>");
            $sql_get_product_type = "select *,count(*) as 'countt' from product_type where id = ? and is_delete = 0";
            $id = fetch_row($sql_get_product_type,[$id['parent_id']]);
            $count++;
        }
        $menu = implode("",$__arr);
        return $menu ;
    }
    function generate_multilevel_menus_2($connection,$parent_id = NULL){
        $sql = "";
        $menu = "";
        if(is_null($parent_id)) {
            $sql = "select * from product_type where parent_id is NULL and is_delete = 0";
        } else {
            $sql = "select * from product_type where parent_id = $parent_id and is_delete = 0";
        }
        $stmt = $connection->prepare($sql);
        $stmt->execute();
        while($result = $stmt->fetch(PDO::FETCH_ASSOC)) {
            $menu .= "<li class='dropdown-submenu' data-id='" ."{$result["id"]}".  "'><a class='dropdown-item' href='#'>" . $result["name"] . "</a>";
            $menu .= "<ul class='dropdown-menu'>" . generate_multilevel_menus_2($connection,$result["id"]) . "</ul>";
            $menu .= "</li>";
        }
        return $menu;
    }
    function find_branch_by_root($id){
        $branch = "";
        $sql = "select *,count(*) as 'countt' from product_type where parent_id = ? and is_delete = 0";
        $id = fetch_row($sql,[$id]);
        while($id['countt'] > 0) {
            $branch = $id['id'];
            $sql = "select *,count(*) as 'countt' from product_type where parent_id = ? and is_delete = 0";
            $id = fetch_row($sql,[$id['id']]);
        }
        return $branch;
    }
    function find_root_menu($id = NULL){
        $root_id = "";
        $sql = "";
        $__arr = [];
        $sql_get_product_type = "select *,count(*) as 'countt' from product_type where id = ? and is_delete = 0";
        $id = fetch_row($sql_get_product_type,[$id]);
        while($id["countt"] > 0) {
            $root_id = $id['id'];
            $sql_get_product_type = "select *,count(*) as 'countt' from product_type where id = ? and is_delete = 0";
            $id = fetch_row($sql_get_product_type,[$id['parent_id']]);
        }
        return $root_id;
    }
    function check_permission_link($link){
        $test = false;
        $sql_get_role = "select m.name as 'm_name',m.link as 'm_link',u.permission as 'u_role' from user_role u inner join menus m on u.menu_id = m.id where user_id = '$_SESSION[id]'";
        $result_role = fetch_all(sql_query($sql_get_role));
        // if this is admin, we set admin full access role
        $sql_check_admin = "select type from user where id = '$_SESSION[id]'";
        $check_ad = fetch(sql_query($sql_check_admin));
        if($check_ad['type'] == "1") {
            return true;
        }
        //
        foreach($result_role as $role) {
            if($role["m_link"] == $link) {
                $test = true;
                return $test;
            }
        }
        return false;
    }
    function get_permission($link) {
        $test = false;
        // if this is admin, we set admin full access role
        $sql_check_admin = "select type from user where id = '$_SESSION[id]'";
        $check_ad = fetch(sql_query($sql_check_admin));
        if($check_ad['type'] == "1") {
            return [true,true];
        }
        //
        $sql_get_role = "select m.name as 'm_name',m.link as 'm_link',u.permission as 'u_role' from user_role u inner join menus m on u.menu_id = m.id where user_id = '$_SESSION[id]'";
        $result_role = fetch_all(sql_query($sql_get_role));
        foreach($result_role as $role) {
            if($role["m_link"] == $link) {
               return $role["u_role"];
            }
        }
        return false;
    }
    function check_permission_crud($link,$str){
        $permission = get_permission($link);
        //log_a($permission);
        if($permission) {

            // if this is admin, we set admin full access role
            if($permission == [true,true]) {
                return true;
            }
            return strpos($permission,$str) !== false;
        }
        return false;
    }
    function check_permission_redirect($link){
        $test = false;
        $sql_check_admin = "select type from user where id = '$_SESSION[id]'";
        $check_ad = fetch(sql_query($sql_check_admin));
        if($check_ad['type'] == "1") {
            $test = true;
        }
        if(in_array($link,['information.php','change_password.php','logout.php'])){
            $test = true;
        }
        if(!$test) {
            $sql_get_role = "select m.name as 'm_name',m.link as 'm_link',u.permission as 'u_role' from user_role u inner join menus m on u.menu_id = m.id where user_id = '$_SESSION[id]'";
            $result_role = fetch_all(sql_query($sql_get_role));
            foreach($result_role as $role) {
                if($role["m_link"] == $link) {
                    $test = true;
                }
            }
        }
        if(!$test) {
            echo "<script src='js/jquery.min.js'></script>";
            echo "<script src='js/jquery-confirm.min.js'></script>";
            echo "<script>
                $.alert({
                    title: 'Thông báo',
                    type: 'red',
                    typeAnimated: true,
                    content: 'Bạn không có quyền truy cập. Vui lòng liên hệ admin để được cấp quyền truy cập',
                    buttons: {
                        'Ok': function(){
                            location.href='information.php'
                        }
                    }
                });
            </script>";
            exit();
        }
    }
    function get_user_info(){
        if(isset($_SESSION["id"]) && (isset($_SESSION["isLoggedIn"]) && $_SESSION["isLoggedIn"] !== false)) {
            $sql = "select * from user where id = '$_SESSION[id]' and is_delete = 0 limit 1";
            $res = fetch(sql_query($sql));
            if($res) {
                $_SESSION["email"] = $res["email"];
                $_SESSION["img_name"] = $res["img_name"];
                $_SESSION["paging"] = $res["paging"];
            }
        }
    }
    
?>