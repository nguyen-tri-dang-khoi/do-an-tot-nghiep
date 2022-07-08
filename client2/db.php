<!-- 
<script>
               $ temp2= $("ul").empty();
</script> -->

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
    // print_r($result);
        while($row = mysqli_fetch_assoc($result)){
            // $link_href= "";
            $id2 = $row['id'];
            // print_r($id2);
            // print_r($parent_id); ở đây thì parent_id ban đầu sẽ luôn là null (Dòng 31)

            if($parent_id == null) {
                $link_href = "categoryProducts.php?id_loai_san_pham=" . $row['id'];
            } else {
                // neu no ko co con
                $link_href = "Products.php?id_loai_san_pham=" . $row['id'];
            }

            $menu .= '<li> <a href='. $link_href.'>'.$row['name'].'</a>';
            $menu .= '<ul  class="dropdown-menu">'.generate_multilevel_menus($connection, $row['id']);
            $menu .= '<div><img src='."../admin/".$row['img_name'].' alt='.$row['name'].'></div>'.'</ul>';
            $menu .= '</li>'.'<hr>';

            // $menu .= '<li> <a href="javascript:void(0);">'.$row['name'].'</a>';  
            // $menu .= '<ul class="dropdown-menu">'.generate_multilevel_menus($connection, $row['id']).'</ul>';
            // $menu .= '</li>'.'<hr>';
        }
    return $menu;
}
?>
