<?php 
    include_once 'db.php';
    $id = isset($_REQUEST['id'])?$_REQUEST['id']:null;
    $sql = "SELECT * FROM product_info WHERE id like $id and is_active like 1 and is_delete like 0";
   // print_r($sql);
    $conn = connect();
    $result = mysqli_query($conn, $sql);
    $row = mysqli_fetch_assoc($result);
  //  print_r($row);
?>
<!DOCTYPE html>
<html lang="en">
<?php include_once ('include/head.php'); ?>

<body>
    <script src="slick-master/slickcustom.js"></script>
    <?php include_once ('include/menu.php');?>

    <div class="Product__Detail container-fluidp-0">
        <div class=" row p-0 block__home">
            <div class="col-10 m-auto row p-0">
                <div id="carouselExampleDark" class="col-6 p-0 carousel carousel-dark slide" data-bs-ride="carousel">
                    <?php
                        $sql_load_img = "select * from product_image where product_info_id = '$id' order by img_order asc";
                        $image_result = mysqli_query($conn, $sql_load_img);
                        //$image_result2 = $image_result;
                        $number_slide = 0;
                    ?>
                    
                    <div class="carousel-indicators">
                        <button type="button" data-bs-target="#carouselExampleDark" data-bs-slide-to="<?php echo $number_slide;?>" class="active" aria-current="true" aria-label="Slide <?php echo ($number_slide + 1);?>"></button>
                        <?php
                            $number_slide++;
                            while($row_image = mysqli_fetch_assoc($image_result)) {
                        ?>
                            <button type="button" data-bs-target="#carouselExampleDark" data-bs-slide-to="<?php echo $number_slide;?>" aria-label="Slide <?php echo ($number_slide + 1);?>"></button>
                            <!-- <button type="button" data-bs-target="#carouselExampleDark" data-bs-slide-to="1" class="active" aria-label="Slide 2"></button>
                            <button type="button" data-bs-target="#carouselExampleDark" data-bs-slide-to="2" aria-label="Slide 3"></button>
                            <button type="button" data-bs-target="#carouselExampleDark" data-bs-slide-to="3" aria-label="Slide 4"></button> -->
                        <?php
                                $number_slide++;
                            }
                        ?>
                    </div>  
                    
                    <div class="carousel-inner">
                        <div class="carousel-item active" data-bs-interval="10000">
                            <img src="<?php echo "../admin/". $row['img_name'];?>" class="d-block w-80 m-auto" alt="...">
                            <div class="carousel-caption d-none d-md-block">
                            </div>
                        </div>
                        <?php
                         $sql_load_img = "select * from product_image where product_info_id = '$id' order by img_order asc";
                         $image_result = mysqli_query($conn, $sql_load_img);
                            while($row_image = mysqli_fetch_assoc($image_result)) {
                        ?>
                                <div class="carousel-item d-flex" data-bs-interval="10000">
                                    <img src="<?php echo "../admin/" . $row_image['img_id'];?>" class="d-block w-80 m-auto" alt="...">
                                    <div class="carousel-caption d-none d-md-block">
                                    </div>
                                </div>
                        <?php
                            }
                        ?>
                    </div>
                    <button class="carousel-control-prev" type="button" data-bs-target="#carouselExampleDark" data-bs-slide="prev">
                        <span class="carousel-control-prev-icon" aria-hidden="true"></span>
                        <span class="visually-hidden">Previous</span>
                    </button>
                    <button class="carousel-control-next" type="button" data-bs-target="#carouselExampleDark" data-bs-slide="next">
                        <span class="carousel-control-next-icon" aria-hidden="true"></span>
                        <span class="visually-hidden">Next</span>
                    </button>
                </div> 
                <div class="Detail--right col-6 m-auto p-0">
                    <div class="right_info">
                       
                        <h3 style="color: #005ec4;"><?php echo $row["name"]; ?></h3>
                        <div class="d-flex justify-content-between mb-4">
                            <div>
                                <i class="fas fa-star "></i> 
                                <i class="fas fa-star "></i> 
                                <i class="fas fa-star "></i> 
                                <i class="fas fa-star "></i> 
                                <i class="fas fa-star "></i>
                                <span> 0 đánh giá</span>
                            </div>
                            <span>Bảo Hành 24 Tháng</span>
                        </div>
                        <div>
                            <?php 
                                echo $row["description"]; 
                            ?>
                            <!-- <p>- Chuẩn VESA tương thích: 100x100mm và 75x75m.</p>
                            <p>- Có thể gắn 2 màn hình hiển thị từ 17”-27″ inch</p>
                            <p>- Xoay 180 độ</p>
                            <p>- Trọng lượng màn hình 2-7kg</p>
                            <p>- Trọng lượng: 2.6kg</p>
                            <p>- Kích thước: 117x506x570mm</p>
                            <p>- Kéo giãn tối đa: 463mm</p>
                            <p>- Màu sắc: Đen</p>
                            <p>- Chất liệu: Sắt</p> -->
                        </div>
                    </div>
                    <div class="right_button">
                        <div class="d-flex justify-content-between">
                            <span style="color: green"><i class="fa-solid fa-circle"></i> Còn hàng</span>
                            <p style="font-weight:700">
                                <?php 
                                    echo number_format($row["price"],0,".","."); 
                                ?>đ
                            </p>
                        </div>
                        <div>

                        </div>
                        <div>

                        </div>
                    </div>  
                </div>
            </div>
            <div class="block--header col-10 m-auto ">
                <span class="block--header_title">SẢN PHẨM LIÊN QUAN</span>
            </div>
            <div class="block--carousel slick-carousel slider col-10 m-auto ">
                <!-- <?php //get_product()?> -->
                <?php
                    $product_type_id = $row['product_type_id'];
                    $price_1 = $row['price'];
                    $price_2 = $row['price'] + 1000000;
                    $conn = connect();
                    $getDataProduct = "SELECT * FROM product_info WHERE (is_delete like 0 and is_active like 1) and product_type_id like $product_type_id and price >= $price_1 and price <= $price_2";
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
                ?>
            </div>
            <div class="block--comment col-10 m-auto">
                    <div class="review_comment">
                        <h5>Bình luận</h5>
                        <div>
                            <img src="#" alt="avatar ngừời dùng">
                            <div class="#">
                                <i class="fas fa-star "></i> 
                                <i class="fas fa-star "></i> 
                                <i class="fas fa-star "></i> 
                                <i class="fas fa-star "></i> 
                                <i class="fas fa-star "></i>
                            </div>
                            <div class="nameUser_cmt">khoi</div>
                            <div class="content_cmt">aaaaaaaaaaaaaaaaaaaaaaaas</div>
                            <div>
                                <a href="javascript:void(0)">Trả lời</a> <span>Ngày tháng cmt</span>
                            </div>
                        </div>
                    </div>
                    <div class="input_comment">
                        <div class="input-group mb-3">
                            <div class="input-group mb-3 vote_rate">
                                <span class="input-group-text">Đánh giá: </span>
                                <i class="fas fa-star "></i> 
                                <i class="fas fa-star "></i> 
                                <i class="fas fa-star "></i> 
                                <i class="fas fa-star "></i> 
                                <i class="fas fa-star "></i>
                            </div>

                            <div class="input-group">
                                <span class="input-group-text">Nội dung bình luận: </span>
                                <textarea class="form-control" aria-label="With textarea"></textarea>
                            </div>
                        </div>
                </div>
            </div>

        <div>
    </div>
    <?php include_once ('js/js_customIndex.php'); ?>                       
    <?php include_once ('include/footer.php'); ?>
    <script type="text/javascript" src="//cdn.jsdelivr.net/npm/slick-carousel@1.8.1/slick/slick.min.js"></script>
</body>
</html>
