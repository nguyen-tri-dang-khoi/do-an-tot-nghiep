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
    //
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
            $menu .= "<ul class='child'>" . generate_multilevel_menus_3($connection,$result["id"]) . "</ul>";
            $menu .= "</li>";
        }
        return $menu;
    }
    function generate_breadcrumb_menus($id = NULL){
        $sql = "";
        $__arr = [];
        $sql_get_product_type = "select *,count(*) as 'countt' from product_type where id = ? and is_delete = 0";
        $id = fetch(sql_query($sql_get_product_type,[$id]));
        while($id["countt"] > 0) {
            array_unshift($__arr,"<li class='breadcrumb-item'>".$id["name"]."</li>");
            $sql_get_product_type = "select *,count(*) as 'countt' from product_type where id = ? and is_delete = 0";
            $id = fetch(sql_query($sql_get_product_type,[$id['parent_id']]));
            
        }
        $menu = "<ol style='margin-bottom: 25px;padding: 9px 10px;' tabindex='1' class='breadcrumb'>" . implode("",$__arr) . "</ol>";
        return $menu ;
    }
    function generate_breadcrumb_menus_3($id = NULL){
        $sql = "";
        $__arr = [];
        $parent_id="";
        $sql_get_product_type = "select *,count(*) as 'countt' from product_type where id = ? and is_delete = 0";
        $id = fetch(sql_query($sql_get_product_type,[$id]));
        $count=0;
        while($id["countt"] > 0) {
            $parent_id = $id['id'];
            $tab_unique = isset($_REQUEST['tab_unique']) ? $_REQUEST['tab_unique'] : null;
            array_unshift($__arr,"<li class='breadcrumb-item breadcrumb-item-aaa'><a style='cursor:pointer;color: #9c27b0;' onclick=" . '"' ."loadDataInTab('category_manage.php?parent_id=$parent_id&tab_unique=$tab_unique')" . '">'.$id["name"]."</a></li>");   
            $sql_get_product_type = "select *,count(*) as 'countt' from product_type where id = ? and is_delete = 0";
            $id = fetch(sql_query($sql_get_product_type,[$id['parent_id']]));
            $count++;
        }
        $menu = implode("",$__arr);
        return $menu ;
    }
    function generate_breadcrumb_menus_4($id = NULL){
        $sql = "";
        $__arr = [];
        $parent_id="";
        $sql_get_product_type = "select *,count(*) as 'countt' from product_type where id = ? and is_delete = 0";
        $id = fetch(sql_query($sql_get_product_type,[$id]));
        $count=0;
        while($id["countt"] > 0) {
            $parent_id = $id['id'];
            if($count == 0) {
                array_unshift($__arr,$id["name"]);
            } else {
                array_unshift($__arr,$id["name"] . " / ");
            }
            $sql_get_product_type = "select *,count(*) as 'countt' from product_type where id = ? and is_delete = 0";
            $id = fetch(sql_query($sql_get_product_type,[$id['parent_id']]));
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
        $id = fetch(sql_query($sql,[$id]));
        while($id['countt'] > 0) {
            $branch = $id['id'];
            $sql = "select *,count(*) as 'countt' from product_type where parent_id = ? and is_delete = 0";
            $id = fetch(sql_query($sql,[$id['id']]));
        }
        return $branch;
    }
    function find_root_menu($id = NULL){
        $root_id = "";
        $sql = "";
        $__arr = [];
        $sql_get_product_type = "select *,count(*) as 'countt' from product_type where id = ? and is_delete = 0";
        $id = fetch(sql_query($sql_get_product_type,[$id]));
        while($id["countt"] > 0) {
            $root_id = $id['id'];
            $sql_get_product_type = "select *,count(*) as 'countt' from product_type where id = ? and is_delete = 0";
            $id = fetch(sql_query($sql_get_product_type,[$id['parent_id']]));
        }
        return $root_id;
    }
    function confirm_when_del_pt($connection,$parent_id = NULL) {
        $sql = "";
        $sum = 0;
        if(is_null($parent_id)) {
            $sql = "select * from product_type where parent_id is NULL and is_delete = 0";
        } else {
            $sql = "select * from product_type where parent_id = $parent_id and is_delete = 0";
        }
        $stmt = $connection->prepare($sql);
        $stmt->execute();
        $sql_upt_pi = "Select count(*) as 'countt' from product_info where is_delete = 0 and product_type_id = " . $parent_id;
        $res = fetch(sql_query($sql_upt_pi));
        $sum += $res['countt'];
        while($result = $stmt->fetch(PDO::FETCH_ASSOC)){
            //print_r($sql_upt_pi . " ");   
            $sum += confirm_when_del_pt($connection,$result['id']);
            //print_r($sum . " ");
            //confirm_when_del_pt($connection,$result['id']);
        }
        return $sum;
    }
    function menu22($connection = NULL,$parent_id = NULL){
        if(!$connection) {
            $connection = $GLOBALS['link'];
        }
        $res = iterateMenu($connection,$parent_id);
        return $res;
    }
    function iterateMenu($connection,$parent_id) {
        $sql = "select * from product_type where parent_id = $parent_id and is_delete = 0 limit 1";
        $stmt = $connection->prepare($sql);
        $stmt->execute();
        $result = $stmt->fetch(PDO::FETCH_ASSOC);
        if($result) return $parent_id . " - " . iterateMenu($connection,$result['id']);
        return $parent_id . " - NULL";
    }
    //
    function show_confirm_when_del_pt($connection = NULL,$parent_id = NULL) {
        if(!$connection) {
            $connection = $GLOBALS['link'];
        }
        $res = confirm_when_del_pt($connection,$parent_id);
        return $res;
    }
    function exec_del_pi_when_del_pt($connection = NULL,$parent_id = NULL) {
        if(!$connection) {
            $connection = $GLOBALS['link'];
        }
        $res = del_pi_when_del_pt($connection,$parent_id);
        return $res;   
    }
    //
    function exec_delete_comment($connection = NULL,$id = NULL){
        if(!$connection) {
            $connection = $GLOBALS['link'];
        }
        $res = delete_comment($connection,$id);   
        return $res; 
    }
    function delete_comment($connection = NULL,$id){
        $sql = "";
        if(is_null($id)) {
            $sql = "select * from product_comment where reply_id is NULL and is_delete = 0";
        } else {
            $sql = "select * from product_comment where reply_id = $id and is_delete = 0";
        }
        $stmt = $connection->prepare($sql);
        $stmt->execute();
        $sql_upt_pt = "Update product_comment set is_delete = 1 where id = " . $id;
        sql_query($sql_upt_pt);
        while($result = $stmt->fetch(PDO::FETCH_ASSOC)) {
            delete_comment($connection,$result["id"]);
        }       
        return true;
    }
    //
    function exec_deactive_all($connection = NULL,$parent_id = NULL) {
        if(!$connection) {
            $connection = $GLOBALS['link'];
        }
        $res = deactive_all($connection,$parent_id);   
        return $res;  
    }
    function show_confirm_when_deactive($connection = NULL,$parent_id = NULL){
        if(!$connection) {
            $connection = $GLOBALS['link'];
        }
        $res = confirm_when_deactive($connection,$parent_id);
        return $res;
    }
    function confirm_when_deactive($connection = NULL,$parent_id = NULL) {
        $sql = "";
        $sum = 0;
        if(is_null($parent_id)) {
            $sql = "select * from product_type where parent_id is NULL and is_active = 1";
        } else {
            $sql = "select * from product_type where parent_id = $parent_id and is_active = 1";
        }
        $stmt = $connection->prepare($sql);
        $stmt->execute();
        $sql_upt_pi = "Select count(*) as 'countt' from product_info where is_active = 1 and product_type_id = " . $parent_id;
        $res = fetch(sql_query($sql_upt_pi));
        $sum += $res['countt'];
        while($result = $stmt->fetch(PDO::FETCH_ASSOC)){
            //print_r($sql_upt_pi . " ");   
            $sum += confirm_when_deactive($connection,$result['id']);
            //print_r($sum . " ");
            //confirm_when_del_pt($connection,$result['id']);
        }
        return $sum;
    }
    function exec_active_all($connection = NULL,$parent_id = NULL) {
        if(!$connection) {
            $connection = $GLOBALS['link'];
        }
        if(check_is_active($parent_id)) { // kiểm tra danh mục cha có kích hoạt chưa, nếu chưa thì danh mục con ko được phép kích hoạt
            $res = active_all($connection,$parent_id);    
        } else {
            $res = false;
            echo_json(["msg" => "not_ok","error"=>"Danh mục cha chưa được kích hoạt"]);
        }
        return $res;  
    }
    function deactive_all($connection = NULL,$parent_id = NULL){
        $sql = "";
        if(is_null($parent_id)) {
            $sql = "select * from product_type where parent_id is NULL and is_active = 1";
        } else {
            $sql = "select * from product_type where parent_id = $parent_id and is_active = 1";
        }
        $stmt = $connection->prepare($sql);
        $stmt->execute();
        $sql_upt_pt = "Update product_type set is_active = 0 where id = " . $parent_id;
        sql_query($sql_upt_pt);
        $sql_upt_pi = "Update product_info set is_active = 0 where product_type_id = " . $parent_id;
        sql_query($sql_upt_pi);
        while($result = $stmt->fetch(PDO::FETCH_ASSOC)) {
            deactive_all($connection,$result["id"]);
        }       
        return true;
    }
    //
    
    function exec_toggle_comment($connection = NULL,$id = NULL,$status) {
        if(!$connection) {
            $connection = $GLOBALS['link'];
        }
        $res = toggle_comment($connection,$id,$status);   
        return $res;  
    }
    function toggle_comment($connection = NULL,$id = NULL,$status){
        $sql = "";
        if(is_null($id)) {
            $sql = "select * from product_comment where reply_id is NULL";
        } else {
            $sql = "select * from product_comment where reply_id = $id";
        }
        $stmt = $connection->prepare($sql);
        $stmt->execute();
        if($status == "Active") {
            $sql_upt_pt = "Update product_comment set is_active = 1 where id = " . $id;
            sql_query($sql_upt_pt);
        } else if($status == "Deactive") {
            $sql_upt_pt = "Update product_comment set is_active = 0 where id = " . $id;
            sql_query($sql_upt_pt);
        }
        while($result = $stmt->fetch(PDO::FETCH_ASSOC)) {
            toggle_comment($connection,$result["id"],$status);
        }       
        return true;
    }
    
    //
    // kiểm tra xem cha của danh mục tình trạng đang active hay deactive
    /**
     * check_is_active()
     */
    function check_is_active($id = NULL){
        //print_r($id);
        $test = false;
        if($id) {
            $sql = "select parent_id from product_type where id = $id limit 1"; 
            $res = fetch(sql_query($sql));
            $parent_id_2 = $res['parent_id'];
            //print_r($sql);
            if($parent_id_2){
                //print_r($is_active);
                $sql = "select is_active from product_type where id = '$parent_id_2' limit 1"; 
                $res = fetch(sql_query($sql));
                $is_active = $res['is_active'];
                if($is_active == 1) {  // active
                    $test = true;
                }
            } else {
                $test = true;
            }
        }
        return $test;
    }
    function active_all($connection = NULL,$parent_id = NULL){
        $sql = "";
        if(is_null($parent_id)) {
            $sql = "select * from product_type where parent_id is NULL and is_active = 0";
        } else {
            $sql = "select * from product_type where parent_id = $parent_id and is_active = 0";
        }
        $stmt = $connection->prepare($sql);
        $stmt->execute();
        $sql_upt_pt = "Update product_type set is_active = 1 where id = " . $parent_id;
        sql_query($sql_upt_pt);
        $sql_upt_pi = "Update product_info set is_active = 1 where product_type_id = " . $parent_id;
        sql_query($sql_upt_pi);
        while($result = $stmt->fetch(PDO::FETCH_ASSOC)) {
            active_all($connection,$result["id"]);
        }       
        return true;
    }
    
    function del_pi_when_del_pt($connection,$parent_id = NULL) {
        $sql = "";
        if(is_null($parent_id)) {
            $sql = "select * from product_type where parent_id is NULL and is_delete = 0";
        } else {
            $sql = "select * from product_type where parent_id = $parent_id and is_delete = 0";
        }
        $stmt = $connection->prepare($sql);
        $stmt->execute();
        $sql_upt_pt = "Update product_type set is_delete = 1 where id = " . $parent_id;
        sql_query($sql_upt_pt);
        $sql_upt_pi = "Update product_info set is_delete = 1 where product_type_id = " . $parent_id;
        sql_query($sql_upt_pi);
        while($result = $stmt->fetch(PDO::FETCH_ASSOC)) {
            del_pi_when_del_pt($connection,$result["id"]);
        }       
        return true;
    }
    
    function check_permission_link($link){
        $test = false;
        $sql_get_role = "select m.name as 'm_name',m.link as 'm_link',u.permission as 'u_role' from user_role u inner join menus m on u.menu_id = m.id where user_id = '$_SESSION[id]'";
        $result_role = fetch_all(sql_query($sql_get_role));
        // if this is admin, we set admin full access role
        $sql_check_admin = "select type from user where id = '$_SESSION[id]'";
        $check_ad = fetch(sql_query($sql_check_admin));
        if($check_ad['type'] == "admin") {
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
        if($check_ad['type'] == "admin") {
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
        if($check_ad['type'] == "admin") {
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