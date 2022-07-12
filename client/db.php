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

function encrypt_decrypt($string, $action = 'encrypt')
{
    $encrypt_method = "AES-256-CBC";
    $secret_key = 'AA@!74C@DCC2BBR!@B63$C27'; // user define private key
    $secret_iv = '24324@#2'; // user define secret key
    $key = hash('sha256', $secret_key);
    $iv = substr(hash('sha256', $secret_iv), 0, 16); // sha256 is hash_hmac_algo
    if ($action == 'encrypt') {
        $output = openssl_encrypt($string, $encrypt_method, $key, 0, $iv);
        $output = base64_encode($output);
    } else if ($action == 'decrypt') {
        $output = openssl_decrypt(base64_decode($string), $encrypt_method, $key, 0, $iv);
    }
    return $output;
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


// get product

                function get_product(){
                    $conn = connect();
                    $getDataProduct = "SELECT * FROM product_info WHERE (is_delete like 0 and is_active like 1)";
                    $result = mysqli_query($conn, $getDataProduct);
                    
                    if(mysqli_num_rows($result) > 0){
                        //out put data in whike loop, or out "Không có sản phẩm "
                        
                        while($row = mysqli_fetch_assoc($result)){
            ?>
            <div  class="product">                    
                <div class="product__info">
                    <div class="info--percent">
                    <span>
                        <?php //echo "-".$row["discount"]."%"; ?>0 %
                    </span>
                    </div>
                    <div class="info--thumb" onclick="location.href='index_detail.php?id=<?php echo $row['id']; ?>'">
                        <a href="javascript:void(0)" class="product__link">
                            <img src="<?php echo "../admin/". $row["img_name"]; ?>" alt="Sentinel 3090Ti - i9 12900K/ Z690/ 32GB/ 2TB/ RTX 3090Ti/ 1200W">
                        </a>
                    </div> 
                    <div class="info--bottom">
                        <div class="bottom_title" onclick="location.href='index_detail.php?id=<?php echo $row['id']; ?>'">
                                <a href="javascript:void(0)" class="product__link"><?php echo $row["name"]; ?></a>
                        </div> 
                        <div class="bottom_rate" onclick="location.href='index_detail.php?id=<?php echo $row['id']; ?>'">
                            <div class="rate-star">
                                <?php //echo $row["rate"]; ?>
                                <i class="fa-solid fa-star"></i>
                                <i class="fa-solid fa-star"></i>
                                <i class="fa-solid fa-star"></i>
                                <i class="fa-solid fa-star"></i>
                                <i class="fa-solid fa-star"></i>
                            </div>
                            <div class="rate-text">0 đánh giá</div> 
                        </div> 
                        <div class="bottom_price" onclick="location.href='index_detail.php?id=<?php echo $row['id']; ?>'">
                            <span class="price-selling"><?php echo number_format($row["price"],0,".","."). "đ";?></span>   
                            <span class="price-root" name="price"><?php echo number_format($row["price"],0,".","."). "đ";?></span>
                        </div> 
                        <?php //echo $row["description"] ;?>
                        <button onclick="addToCart()" type="button" data-img="<?php echo $row["img_name"];?>" class="add-to-cart" data-name="<?php echo $row["name"];?>" data-price="<?php echo $row["price"];?>" data-id="<?php echo $row['id'] ?>">Mua ngay</button>
                    </div>
                </div>
            </div>
            <?php
                        }
                    }
                    else {
                        echo "Không có sản phẩm";
                    }
                } 

?>
