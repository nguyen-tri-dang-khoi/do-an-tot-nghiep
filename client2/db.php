<?php   

function connect()
{
    $connection = mysqli_connect('localhost', 'root', '', 'shop_cua_hang_may_tinh');
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
        if($row['page']){
            $menu .='<li><a href="'.$row['page'].'">'.$row['name'].'</a>';
        }
        else{
            $menu .= '<li> <a href="#">'.$row['name'].'</a>';
        }

        $menu .= '<ul class="dropdown-menu">'.generate_multilevel_menus($connection, $row['id']).'</ul>';

        $menu .= '</li>'.'<hr>';
    }
    return $menu;
}

// function get_product(){
//     $conn = connect();
//     $getDataProduct = "SELECT * FROM product_info WHERE is_delete like 0 and is_active like 1";
//     $result = mysqli_query($conn, $getDataProduct);

//     if(mysqli_num_rows($result) > 0){
//         //out put data in whike loop, or out "0 NO RESULT "
//         while($row = mysqli_fetch_assoc($result)){
//             echo $row["name"]. "   " .$row["price"];
//         }
//     }
//     else {
//         echo "0 NO RESULT";
//     }
// }

// 

// DINH DANG GIA TIEN TRONG PRODUCT

// $('#price').number( true, 0,',','.' );


?>

<?php 
    function get_product(){
        $conn = connect();
        $getDataProduct = "SELECT * FROM product_info WHERE (is_delete like 0 and is_active like 1)";
        $result = mysqli_query($conn, $getDataProduct);
        
        if(mysqli_num_rows($result) > 0){
            //out put data in whike loop, or out "0 NO RESULT "
            
            while($row = mysqli_fetch_assoc($result)){
?>
<div class="product">                    
    <div class="product__info">
        <div class="info--percent">
        <span>
            <?php echo "-".$row["discount"]."%"; ?>
        </span>
        </div>
        <div class="info--thumb">
            <a href="https://www.tncstore.vn/gaming-pc-sentinel-3090ti-i9-12900k.html" class="product__link">
                <img src="<?php echo $row["img_name"]; ?>" alt="Sentinel 3090Ti - i9 12900K/ Z690/ 32GB/ 2TB/ RTX 3090Ti/ 1200W">
            </a>
        </div> 
        <div class="info--bottom">
            <div class="bottom_title">
                    <a href="https://www.tncstore.vn/gaming-pc-sentinel-3090ti-i9-12900k.html" class="product__link"><?php echo $row["name"]; ?></a>
            </div> 
            <div class="bottom_rate">
                <div class="rate-star">
                    <?php echo $row["rate"]; ?>
                </div> 
                <div class="rate-text">0 đánh giá</div>
            </div> 
            <div class="bottom_price">
                <span class="price-selling"><?php echo $row["price"]. "đ";?></span> 
                <span class="price-root" name="price"><?php echo $row["price_root"]. "đ";?></span>
            </div> 
            <?php echo $row["description"] ;?> 
            <button type="button" class="add-to-cart">Mua ngay</button>
        </div>
    </div>
</div>
<?php
            }
        }
        else {
            echo "0 NO RESULT";
        }
    } 
?>