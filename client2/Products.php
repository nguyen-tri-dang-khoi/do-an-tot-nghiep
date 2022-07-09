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
    <script>
        $('.title_producer').click(function() {
        let temp = $(this).attr('class');
        $(this).siblings().toggleClass('hidden_class');
        $(this).find('i').toggleClass('activeClassI');
    });
    $('.title_price').click(function() {
        let temp = $(this).attr('class');
        $(this).siblings().toggleClass('hidden_class');
        $(this).find('i').toggleClass('activeClassI');
    });
    </script>
<?php
    $hang_san_xuat = isset($_REQUEST['hang_san_xuat']) ? $_REQUEST['hang_san_xuat'] : null;
    $gia_1 = isset($_REQUEST['gia_1']) ? $_REQUEST['gia_1'] : null;
    $gia_2 = isset($_REQUEST['gia_2']) ? $_REQUEST['gia_2'] : null;
    $id_loai_san_pham = isset($_REQUEST['id_loai_san_pham']) ? $_REQUEST['id_loai_san_pham'] : null;
    $keyword = isset($_REQUEST['keyword']) ? $_REQUEST['keyword'] : null;
    $changeSortt = isset($_REQUEST['changeSortt']) ? $_REQUEST['changeSortt'] : null;
    $where = "is_delete like 0 and is_active like 1";
    $order_by = "";
    if($gia_1) {
        $where .= " and price >= $gia_1";
    }
    if($gia_2) {
        $where .= " and price <= $gia_2";
    }
    if($hang_san_xuat) {
        $where .= " and brand_id = '$hang_san_xuat'";
    }
    if($keyword) {
        $where .= " and name like '%$keyword%'";
    }
    if($id_loai_san_pham) {
        $where .= " and product_type_id = '$id_loai_san_pham'";
    }
    if($changeSortt) {
        $order_by = $changeSortt;
    }
    $conn = connect();

    $sql_get_count = "select count(*) as 'countt' from product_info where $where limit 1";
    $result = mysqli_query($conn, $sql_get_count);
    $row2 = mysqli_fetch_assoc($result);
    
    
?>
<script>
    $('.title_producer').click(function() {
        let temp = $(this).attr('class');
        $(this).siblings().toggleClass('hidden_class');
        $(this).find('i').toggleClass('activeClassI');
    });
    $('.title_price').click(function() {
        let temp = $(this).attr('class');
        $(this).siblings().toggleClass('hidden_class');
        $(this).find('i').toggleClass('activeClassI');
    });
</script>
<div class="block__home row">
        <div class="category__product col-10 m-auto">
            <div class="breadcrumb__list" style="margin-bottom: 1%">
                <a href="index.php"><i class="fa-solid fa-house-chimney"></i></a>
                <i class="fa-solid fa-angle-right"></i>
                <!-- <span>Trang chủ</span> -->
                <a style="color: black; text-decoration: none" href="index.php">Trang chủ</a>
                <i class="fa-solid fa-angle-right"></i>
                <!-- <span>Bàn di chuột</span> -->
                <?php
                $row_name2 = "";
                if($id_loai_san_pham){
                    
                    $conn = connect();
                    $sql_show_name = "select * from product_type where id = '$id_loai_san_pham' limit 1";
                    $result = mysqli_query($conn, $sql_show_name);
                    if(mysqli_num_rows($result) > 0){
                        $row = mysqli_fetch_assoc($result);
                    
                ?>
                        <span><?php echo $row['name'];?></span>
                <?php
                        $row_name2 = $row['name'];
                    }
                }
                ?>

            </div>
            <div class="d-flex">
                <div class="col-3 filterss">
                    <form id="change_sort" action="Products.php" method="get">
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
                                <a onclick="setInputPrice(0,500000)" href="javascript:void(0)">Dưới 500.000đ</a>
                                <a onclick="setInputPrice(500000,1000000)" href="javascript:void(0)">500.000 - 1Tr</a>
                                <a onclick="setInputPrice(2000000,4000000)" href="javascript:void(0)">2Tr - 4Tr</a>
                                <a onclick="setInputPrice(4000000,7000000)" href="javascript:void(0)">4Tr - 7Tr</a>
                            </div>
                        </div>
                        <div class="input_price">
                            <span>Hoặc nhập giá dưới đây</span>
                            <div>
                                <input type="number" name="gia_1" min="1" max="30000000" value="<?php echo $gia_1;?>">
                                <span> - </span>
                                <input type="number" name="gia_2" min="1" max="30000000" value="<?php echo $gia_2;?>">
                            </div>
                            <input type="hidden" name="id_loai_san_pham" value="<?php echo $id_loai_san_pham;?>">
                            <input type="hidden" name="changeSortt">
                            <button type="submit">Áp dụng</button>
                        </div>
                    </div>
                </div>
                <div class="col-9 sortss">
                    <div class="sortss_title">
                        <div class="sortss_title_L">
                            <h5 style="color:black "><?php echo $row_name2;?> </h5>
                            <span>(<?php echo $row2['countt'];?> sản phẩm)</span>
                        </div>
                        <div class="sortss_title_R">
                            <span>Sắp xếp theo:</span> 
                            <select onchange="sortt()" name="changeSortt" class="selectsort">
                                <option value="">Sắp xếp</option>
                                <option <?=$changeSortt == 'Order by price asc' ? 'selected' : "";?> value="Order by price asc">Giá (Thấp - Cao)</option>
                                <option <?=$changeSortt == 'Order by price desc' ? 'selected' : "";?> value="Order by price desc">Giá (Cao - Thấp)</option>
                                <option <?=$changeSortt == 'Order by name asc' ? 'selected' : "";?> value="Order by name asc">Tên (A - Z)</option>
                                <option <?=$changeSortt == 'Order by name desc' ? 'selected' : "";?> value="Order by name desc">Tên (Z - A)</option>
                            </select>
                        </div>
                    </div>
                    </form>
                    <div class="sortss_product">
                    <?php 
                    $conn = connect();
                    $get_data_product = "SELECT * FROM product_info WHERE $where $order_by";
                    $result = mysqli_query($conn, $get_data_product);
                    
                    if(mysqli_num_rows($result) > 0){
                        while($row = mysqli_fetch_assoc($result)){
                ?>
                <div class="product">                    
                    <div class="product__info">
                        <?php
                            $row_discount = "";
                            $sql_check_product_type = "";
                            if($row) {
                              $product_type_id = $row['product_type_id'];
                              $sql_check_product_type = "select * from product_type_discount where product_type_discount.product_type_id = '$product_type_id' and is_delete like 0 and is_active like 1 limit 1";
                              $result_discount = mysqli_query($conn, $sql_check_product_type);
                              $row_discount = mysqli_fetch_array($result_discount);
                            } else if($keyword) {

                            }
                        ?>
                        <?php
                            if($row_discount != ""){
                        ?>
                                <div class="info--percent">
                                    <span><?php echo "-".$row_discount['discount_percent']."%"; ?></span>
                                </div>
                        <?php
                            } else {
                                echo "";
                            }
                        ?>
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
                            <div class="bottom_price">
                                <?php
                                    if($row_discount != "") {
                                ?>
                                <span class="price-selling"><?php echo number_format($row["price"] * (100 - $row_discount['discount_percent']) / 100,0,'.','.'). "đ";?></span> 
                                <?php
                                    } else {
                                ?>
                                <span class="price-selling"><?php echo number_format($row["price"] * (100) / 100,0,'.','.'). "đ";?></span> 
                                <?php
                                    }
                                ?>
                                <?php
                                    if($row_discount != "") {
                                ?>
                                <span class="price-root" name="price"><?php echo number_format($row["price"],0,'.','.'). "đ";?></span>
                                <?php
                                    }
                                ?>
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

                ?>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <?php include_once 'include/footer.php'?>
    <script>
        function sortt(){
            event.preventDefault();
            $('#change_sort').submit();
        }
        function setInputPrice(gia_1,gia_2) {
            if(gia_1 > 0) {
                $('input[name="gia_1"]').val(gia_1);
            }
            
            $('input[name="gia_2"]').val(gia_2);
        }
    </script>
    <?php
        include_once 'js/js_customIndex.php';
    ?>
</body>

</html>