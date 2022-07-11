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
<style>
    .rate-yellow,.rate-yellow2 {
        color:#ffc107;
    }
</style>
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
                        </div>
                    </div>
                    <div class="right_button">
                        <div class="d-flex justify-content-between">
                            <?php 
                                if($row['count'] > 0 ) {
                                ?>
                                    <span style="color: green">
                                        <i class="fa-solid fa-circle"></i> Còn hàng
                                    </span>
                                <?php }
                                else{
                                    ?>
                                        <span style="color: red">
                                            <i class="fa-solid fa-circle"></i> Hết hàng
                                        </span>
                                    <?php        
                                }  
                            ?>
                            <p style="font-weight:700">
                                <?php 
                                    echo number_format($row["price"],0,".","."); 
                                ?>đ
                            </p>
                        </div>
                        <div>
                            <div>
                                <span>Số lượng </span>
                                <!-- <div>
                                    <span>+</span>
                                    <input onreads type="text" value="1">
                                    <span>-</span>
                                </div> -->
                                <div class="change_product">
                                    <div><span onclick="updateInfoCart('-','<?php echo $id;?>')">-</span><input name="count" readonly="" type="text" value="1"> <span onclick="updateInfoCart('+','<?php echo $id;?>')">+</span></div>
                                </div>
                            </div>
                        </div>
                        <div>
                            <a href="cart.php" class="go-carts disable">Mua Hàng</a>
                        </div>
                    </div>  
                </div>
            </div>
            <div class="content__category col-10 m-auto mt-4 p-0">
                <div class="category_block_top">
                    <div class="category--item">
                        <i class="fa-solid fa-sack-dollar"></i>
                        <p>Hỗ trợ trả góp 0%, trả trước 0đ</p>
                    </div>
                    <div class="category--item">
                    <i class="fa-solid fa-sack-dollar"></i>
                        <p>Hoàn tiền 200% nếu có hàng giả</p>
                    </div>
                    <div class="category--item">
                        <i class="fa-solid fa-truck-fast"></i>
                        <p>Giao hàng nhanh trên toàn quốc</p>
                    </div>
                    <div class="category--item">
                        <i class="fa-brands fa-rocketchat"></i>
                        <p>Hỗ trợ kĩ thuật online 7/7</p>
                    </div>
                </div>
            </div>
            <div class="block--header col-10 m-auto ">
                
                <span class="block--header_title">SẢN PHẨM LIÊN QUAN</span>
            </div>
            <div class="block--carousel slick-carousel slider col-10 m-auto ">
                <?php
                    $product_type_id = $row['product_type_id'];
                    $price_1 = $row['price'];
                    $price_2 = $row['price'] + 1000000;
                    $conn = connect();
                    $getDataProduct = "SELECT * FROM product_info WHERE (is_delete like 0 and is_active like 1) and product_type_id like $product_type_id and price >= $price_1 and price <= $price_2";
                    $result = mysqli_query($conn, $getDataProduct);
                    
                    if(mysqli_num_rows($result) > 0){               
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
            <div class="block--comment col-10 m-auto p-0">
                <div id="comment--product" class="col-7 review_comment">
                    <h5>Bình luận</h5>
                    <?php
                        $sql_product_comment = "select user_id,comment,rate,created_at from product_comment where reply_id is null and product_info_id = $id and is_active like 1 and is_delete like 0";
                        $result_comment = mysqli_query($conn,$sql_product_comment);
                        while($row_comment = mysqli_fetch_array($result_comment)) {
                    ?>
                    <div class="content mt-3 ">
                        <div class="avatar_user">
                            <img src="img/avatar/img_placeholder_avatar.jpg" alt="avatar ngừời dùng">
                        </div>
                        <div class="rateOf_user w-40">
                            <div class="d-flex  justify-content-between">
                                <div>
                                    <?php
                                        $rate = $row_comment['rate'];
                                        $i = 0;
                                        for($i = 0 ; $i < $rate ; $i++) {
                                    ?>
                                        <i class="fas fa-star rate-yellow2" ></i> 
                                    <?php
                                        }
                                        for($i = 0 ; $i < 5 - $rate ; $i++) {
                                    ?>
                                            <i class="fas fa-star"></i> 
                                    <?php
                                        }
                                    ?>
                                </div>
                                <div class="content_cmt"><?php echo $row_comment['comment'];?></div>
                                
                            </div>
                            <div class="nameUser_cmt" style="font-weight: bold;">
                                <?php
                                    $all_customer_id = $row_comment['user_id'];
                                    $sql_get_customer2 = "select full_name from user where type = 'customer' and id = $all_customer_id and is_delete like 0 limit 1";
                                    $result_customer = mysqli_query($conn,$sql_get_customer2);
                                    $customer_name = mysqli_fetch_array($result_customer);
                                    echo $customer_name['full_name'];
                                ?>
                                </div>
                            <div class="d-flex justify-content-between">
                                <a href="javascript:void(0)">Trả lời</a> <span><?php echo Date("d-m-Y",strtotime($row_comment['created_at']));?></span>
                            </div>
                        </div>
                    </div>
                    <?php
                        }
                    ?>
                </div>
                <?php
                    if(isset($_SESSION['customer_id'])) {
                ?>
                <div class="input_comment">
                    <div class="input-group mb-3">
                        <div class=" mb-3 vote_rate" style="cursor:pointer;">
                            <span>Đánh giá: </span>
                            <i data-rate="1" class="fas fa-star rate-comment" onclick="rateEffect()" onmouseover="rateEffect()"></i> 
                            <i data-rate="2" class="fas fa-star rate-comment" onclick="rateEffect()" onmouseover="rateEffect()"></i> 
                            <i data-rate="3" class="fas fa-star rate-comment" onclick="rateEffect()" onmouseover="rateEffect()"></i> 
                            <i data-rate="4" class="fas fa-star rate-comment" onclick="rateEffect()" onmouseover="rateEffect()"></i> 
                            <i data-rate="5" class="fas fa-star rate-comment" onclick="rateEffect()" onmouseover="rateEffect()"></i>
                        </div>
                        <div class="content_rate">
                            <span>Nội dung bình luận: </span>
                            <textarea name="comment" class="form-control" aria-label="With textarea"></textarea>
                            <span class="text-danger" id="comment_err"></span>
                            <button class="mt-3" type="button" onclick="send()">Bình luận</button>
                        </div>
                    </div>
                </div>
                <?php
                    } else {
                ?>
                    <div class="input_comment">
                        <p style="font-weight:bold;">Vui lòng đăng nhập để được đánh giá bình luận</p> 
                    </div>
                <?php
                    }
                ?>
            </div>
        <div>
    </div>
    <?php include_once ('js/js_customIndex.php'); ?>                       
    <?php include_once ('include/footer.php'); ?>
    <script type="text/javascript" src="//cdn.jsdelivr.net/npm/slick-carousel@1.8.1/slick/slick.min.js"></script>
    <script>
        function rateEffect(){
            $('.rate-comment').removeClass('rate-yellow');
            let rate = $(event.currentTarget).attr('data-rate');
            console.log(rate);
            for(i = 1; i <= rate ; i++) {
                $(`[data-rate='${i}']`).addClass('rate-yellow');
            }
        }
        function send(){
            $('span.text-danger').text("");
            let test = true;
            let comment = $('textarea[name="comment"]').val();
            let rate = $('.rate-yellow').length;
            let product_info_id = '<?php echo $id;?>';
            if(comment == "") {
                $('#comment_err').text('Vui lòng không để trống nội dung bình luận');
                test = false;
            } else if(comment.length > 1500) {
                $('#comment_err').text('Nội dung bình luận không được nhiều hơn 1500 ký tự');
            }
            if(test) {
                $.ajax({
                    url:"comment_process.php",
                    type:"POST",
                    data: {
                        product_info_id: product_info_id,
                        rate: rate,
                        comment: comment,
                        thao_tac:'send',
                    },success:function(data){
                        console.log(data);
                        data = JSON.parse(data);
                        if(data.msg == "ok"){
                            $.alert({
                                "title":"Thông báo",
                                "content":"Bạn đã gửi phản hồi sản phẩm cho chúng tôi thành công",
                            });
                            // let html_rate = '';
                            // for(let i = 0 ; i < rate ; i++) {
                            //     html_rate += `<i class="fas fa-star rate-yellow"></i>`;
                            // }
                            // for(let i = 0 ; i < 5 - rate ; i++) {
                            //     html_rate += `<i class="fas fa-star"></i>`;
                            // }
                            // let html_baby = `
                            // <div class="content mt-3">
                            //     <div class="avatar_user">
                            //         <img src="img/avatar/img_placeholder_avatar.jpg" alt="avatar ngừời dùng">
                            //     </div>
                            //     <div class="rateOf_user">
                            //         <div>
                            //             ${html_rate}
                            //         </div>
                            //         <div class="nameUser_cmt">${data.customer_name}</div>
                            //         <div class="content_cmt">${comment}</div>
                            //         <div class="d-flex" style="justify-content:space-between;">
                            //             <a href="javascript:void(0)">Trả lời</a> <span>${data.date}</span>
                            //         </div>
                            //     </div>
                            // </div>`;
                            // $(html_baby).appendTo('#comment--product');
                        }
                    }
                })
            }
        }
    </script>
</body>
</html>
