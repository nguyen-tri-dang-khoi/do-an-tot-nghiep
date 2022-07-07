<?php   
session_start(); 
function connect()
{
    $connection = mysqli_connect('localhost', 'root', '', 'test114');
    if(!$connection){
        die('dailed to connect DB');
    }
    return $connection;
}
                                    

function show_menu(){
    $connection = connect();
    
    $menus =  '';

    $menus = generate_multilevel_menus($connection);
    return $menus;
}


function generate_multilevel_menus($connection, $parent_id = NULL){
    $menu ='';
    $sql = '';
    if(is_null($parent_id)){
        $sql = "SELECT * FROM `product_type` WHERE `parent_id` IS NULL and is_delete = 0"; // this query will false
    }
    else {
        $sql = "SELECT * FROM `product_type` WHERE `parent_id` = $parent_id and is_delete = 0";
    }

    $result = mysqli_query($connection, $sql);

    while($row = mysqli_fetch_assoc($result)){
        // if($row['page']){
        //     $menu .='<li><a href="'.$row['page'].'">'.$row['name'].'</a>';
        // }
        // else{
        //    
        // }
        $menu .= '<li> <a href="javascript:void(0);">'.$row['name'].'</a>';     
        $menu .= '<ul class="dropdown-menu">'.generate_multilevel_menus($connection, $row['id']).'</ul>';
        $menu .= '</li>'.'<hr>';
    }
    return $menu;
}
?>
