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
            $menu .= "<li class='parent' data-id='" ."{$result["id"]}".  "><a href='#'>" . $result["name"] . "<span class='expand'>»</span></a>";
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
        $menu = "<ol class='breadcrumb'>" . implode("",$__arr) . "</ol>";
        return $menu ;
    }
    
?>