<?php 
    include_once 'db.php';
    //session_start();
?>
<!DOCTYPE html>
<html lang="en">
<?php include_once ('include/head.php'); ?>
<body>
    <script src="slick-master/slickcustom.js"></script>
    <?php include_once ('include/menu.php');?>
<?php
    $hang_san_xuat = isset($_REQUEST['hang_san_xuat']) ? $_REQUEST['hang_san_xuat'] : null;
    $gia_1 = isset($_REQUEST['gia_1']) ? $_REQUEST['gia_1'] : null;
    $gia_2 = isset($_REQUEST['gia_2']) ? $_REQUEST['gia_2'] : null;
    $id_loai_san_pham = isset($_REQUEST['id_loai_san_pham']) ? $_REQUEST['id_loai_san_pham'] : null;
    $where = "is_delete like 0 and is_active like 1";
    if($gia_1) {
        $where .= " and price >= $gia_1";
    }
    if($gia_2) {
        $where .= " and price <= $gia_2";
    }
    if($hang_san_xuat) {
        $where .= " and brand_id = '$hang_san_xuat'";
    }
    if($id_loai_san_pham) {
        $where .= " and product_type_id = '$id_loai_san_pham'";
    }
?>
<div class="block__home row">
        <div class="category__product col-10 m-auto">
            <div class="breadcrumb__list" style="margin-bottom: 1%">
                <i class="fa-solid fa-house-chimney"></i>
                <i class="fa-solid fa-angle-right"></i>
                <span>Trang chủ</span>
                <i class="fa-solid fa-angle-right"></i>
                <!-- <span>Bàn di chuột</span> -->
                <?php
                if($id_loai_san_pham){
                    $conn = connect();
                    $sql_show_name = "select * from product_type where id = '$id_loai_san_pham' limit 1";
                    $result = mysqli_query($conn, $sql_show_name);
                    if(mysqli_num_rows($result) > 0){
                        $row = mysqli_fetch_assoc($result);
                    
                ?>
                        <span><?php echo $row['name'];?></span>
                <?php
                    }
                }
                ?>

            </div>
            <div class="d-flex">
                <div class="col-3 filterss">
                    <h5 style="color:black ">Bộ lọc sản phẩm</h5>
                    <div class="filterss_producer">
                        <div type="button" class="title_producer">
                            <p>Hãng sản xuất</p> 
                            <i class="fa-solid fa-angle-right"></i>
                        </div>
                        <div class="research_producer">
                            <hr>
                            <div class="inputResearch">
                                <input placeholder="Tìm hãng sản xuất" type="text" value="">
                                <i class="fa-solid fa-magnifying-glass"></i>
                            </div>
                            <div class="checkboxResearch">
                                <div>
                                    <input type="checkbox" name="acer" id="acer1"> <span>ACER</span>
                                </div>
                                <div>
                                    <input type="checkbox" name="asus" id="asus1"><span>ASUS</span>     
                                </div>
                                <div>
                                    <input type="checkbox" name="msi" id="msi1"><span>MSI</span>
                                </div>
                                <div>
                                    <input type="checkbox" name="dell" id="dell1"><span>DELL</span>
                                </div>
                                <div>
                                    <input type="checkbox" name="gigabyte" id="gigabyte1"><span>GIGABYTE</span>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="filterss_price">
                        <div type="button" class="title_price">
                            <p>Mức giá</p> 
                            <i class="fa-solid fa-angle-right"></i>
                        </div>
                        <div class="research_price">
                            <hr>
                            <div class="scrollprice">
                                <a href="">Dưới 500.000đ</a>
                                <a href="">500.000 - 1Tr</a>
                                <a href="">2Tr - 4Tr</a>
                                <a href="">4Tr - 7Tr</a>
                            </div>
                        </div>
                        <div class="input_price">
                            <span>Hoặc nhập giá dưới đây</span>
                            <div>
                                <input type="number" id="#" name="quantitymin" min="1" max="30000000">
                                <span> - </span>
                                <input type="number" id="@" name="quantitymax" min="1" max="30000000">
                            </div>
                            <button>Áp dụng</button>
                        </div>
                    </div>
                </div>
                <div class="col-9 sortss">
                    <div class="sortss_title">
                        <div class="sortss_title_L">
                            <h5 style="color:black ">laptop Gaming </h5>
                            <span>(80 sản phẩm)</span>
                        </div>
                        <div class="sortss_title_R">
                            <span>Sắp xếp theo:</span> 
                            <select name="#" id="#" class="selectsort">
                                <option value="#">Mới nhất</option>
                                <option value="#">Giá (Thấp - Cao)</option>
                                <option value="#">Giá (Cao - Thấp)</option>
                                <option value="#">Tên (A - Z)</option>
                                <option value="#">Tên (Z - A)</option>
                            </select>
                        </div>
                    </div>
                    <div class="sortss_product">
                    <?php 
                    function get_product($where_clause){
                        $conn = connect();
                        $get_data_product = "SELECT * FROM product_info WHERE $where_clause";
                        //print_r($get_data_product);
                        $result = mysqli_query($conn, $get_data_product);
                        
                        if(mysqli_num_rows($result) > 0){
                            //out put data in whike loop, or out "0 NO RESULT "
                            
                            while($row = mysqli_fetch_assoc($result)){
                ?>
                <div class="product">                    
                    <div class="product__info">
                        <div class="info--percent">
                        <span>
                            <?php //echo "-".$row["discount"]."%"; ?>
                        </span>
                        </div>
                        <div class="info--thumb">
                            <a href="index_detail.php?id=<?php echo $row['id'];?>" class="product__link">
                                <img src="<?php echo "../admin/".$row["img_name"]; ?>" alt="Sentinel 3090Ti - i9 12900K/ Z690/ 32GB/ 2TB/ RTX 3090Ti/ 1200W">
                            </a>
                        </div> 
                        <div class="info--bottom">
                            <div class="bottom_title">
                                    <a href="index_detail.php?id=<?php echo $row['id'];?>" class="product__link"><?php echo $row["name"]; ?></a>
                            </div> 
                            <div class="bottom_rate">
                                <!-- <div class="rate-star">
                                    <?php //echo $row["rate"]; ?>
                                </div> 
                                <div class="rate-text">0 đánh giá</div> -->
                            </div> 
                            <div class="bottom_price">
                                <span class="price-selling"><?php echo $row["price"]. "đ";?></span> 
                                <span class="price-root" name="price"><?php echo $row["cost"]. "đ";?></span>
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
                <?php get_product($where) ?>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <?php include_once 'include/footer.php'?>
</body>
</html>