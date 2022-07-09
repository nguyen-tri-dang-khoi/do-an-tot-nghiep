<?php 
    include_once 'db.php';
    include_once 'js/js_customIndex.php';
    //session_start();
?>

<!DOCTYPE html>
<html lang="en">
<?php include_once ('include/head.php'); ?>
<body>
    <script src="slick-master/slickcustom.js"></script>
    <?php include_once ('include/menu.php');?>


    <div class="content container-fluid">
        <div class="content__carousel row">
            <div id="carouselExampleDark" class="col-10 m-auto carousel carousel-dark slide" data-bs-ride="carousel">
                <div class="carousel-indicators">
                    <button type="button" data-bs-target="#carouselExampleDark" data-bs-slide-to="0" class="active" aria-current="true" aria-label="Slide 1"></button>
                    <button type="button" data-bs-target="#carouselExampleDark" data-bs-slide-to="1" class="active" aria-label="Slide 2"></button>
                    <button type="button" data-bs-target="#carouselExampleDark" data-bs-slide-to="2" aria-label="Slide 3"></button>
                </div>  
                <div class="carousel-inner">
                    <a href="#" class="carousel-item active" data-bs-interval="10000">
                        <img src="Img/slide1.jpg" class="d-block w-100" alt="#">
                        <div class="carousel-caption d-none d-md-block">
                        </div>
                    </a>
                    <a href="#" class="carousel-item" data-bs-interval="2000">
                        <img src="Img/slide3.png" class="d-block w-100" alt="#">
                        <div class="carousel-caption d-none d-md-block">
                        </div>
                    </a>
                    <a href="#" class="carousel-item">
                        <img src="Img/slide2.png" class="d-block w-100" alt="#">
                        <div class="carousel-caption d-none d-md-block">
                        </div>
                    </a>
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
        </div>
        <div class="content__carousel2 row">
            <div class="slick-carousel2 col-10 m-auto">
                <div class="item">
                   <a href="#"> <img src="img/slide1.jpg" alt="#"></a>
                </div>
                <div class="item">
                   <a href="#"><img src="img/slide2.png" alt="#"></a> 
                </div>
                <div class="item">
                   <a href="#"> <img src="img/slide3.png" alt="#"></a>
                </div>
                <div class="item">
                   <a href="#"> <img src="img/slide4.jpg" alt="#"></a>
                </div>
                <div class="item">
                   <a href="#"><img src="img/slide5.jpg" alt="#"></a> 
                </div>
                <div class="item">
                   <a href="#"> <img src="img/slide6.png" alt="#"></a>
                </div>
            </div>
        </div>
        <div class="block__home flex-column row">
            <div class="block--header col-10 m-auto ">
                <span class="block--header_title">DANH MỤC SẢN PHẨM</span>
            </div>
            <div class="block--content col-10 m-auto">
                <div class="content_collapse">
                    <?php
                        $conn = connect();
                        $sql_product_type = "Select * from product_type where is_active like 1 and is_delete like 0 and parent_id is null";
                        $result = mysqli_query($conn, $sql_product_type);
                        if(mysqli_num_rows($result) > 0){
                            while($row = mysqli_fetch_assoc($result)){
                                $id2 = $row['id'];
                                $link_href = "";
                                $sql_check_parent_id = "select id from product_type where parent_id = '$id2'";
                                // print_r($sql_check_parent_id);
                                $result_id = mysqli_query($conn, $sql_check_parent_id);
                                // neu no co con
                                if($result_id) {
                                    $link_href = "categoryProducts.php?id_loai_san_pham=" . $row['id'];
                                } else {
                                    // neu no ko co con
                                    $link_href = "Products.php?id_loai_san_pham=" . $row['id'];
                                }
                    ?>
                                <div class="collapse-items">
                                    <a href="<?php echo $link_href; ?>">
                                        <span><?php echo $row['name'];?></span>
                                        <div class="shape" ></div>
                                        <img src="<?php echo "../admin/" . $row['img_name']; ?>" alt="collapse1">
                                    </a>
                                </div>
                    <?php
                            }
                        }
                    ?>
                    
                </div>
            </div>
        </div>
        <div class="block__home flex-column row ">
            <div class="block--header col-10 m-auto ">
                <span class="block--header_title">SẢN PHẨM GIẢM GIÁ</span>
            </div>
            <!-- define get_product 🐛 ⬇⬇⬇⬇ -->
            <div class="block--carousel slick-carousel slider col-10 m-auto ">
                <?php get_product() ?>
            </div>
            <div class="block--button col-10 m-auto"> 
                <button type="button" class="view-more">XEM THÊM</button>   
            </div>
            <div class="block--header col-10 m-auto ">
                <span class="block--header_title">SẢN PHẨM NỔI BẬT</span>
            </div>
            <div class="block--featured col-10 m-auto">
                <div class="featured_content">
                    <div class="content-left">
                        <div class="item"><a href="#"><img src="img/featured/featured1.jpg" alt="#"></a></div>
                        <div class="item"><a href="#"><img src="img/featured/featured2.jpg" alt="#"></a></div>
                        <div class="item"><a href="#"><img src="img/featured/featured3.jpg" alt="#"></a></div>
                        <div class="item"><a href="#"><img src="img/featured/featured4.jpg" alt="#"></a></div>
                    </div>
                    <div class="content-right">
                        <div class="item">
                        <a href="#"><img src="img/featured/featured5.jpg" alt="#"></a>
                        </div>
                    </div>
                </div>
            </div>
            <div class="block--button col-10 m-auto"> 
                <button type="button" class="view-more">XEM THÊM</button>   
            </div>
            <div class="block--header col-10 m-auto ">
                <span class="block--header_title">SẢN PHẨM  MỚI VỀ</span>
            </div>
            <div class="block--carousel slick-carousel slider col-10 m-auto ">
                <?php get_product()?>
            </div>  
            <div class="block--button col-10 m-auto"> 
                <button type="button" class="view-more">XEM THÊM</button>   
            </div>    
            <div class="block--header col-10 m-auto ">
                <span class="block--header_title">SẢN PHẨM BÁN CHẠY</span>
            </div>
            <div class="block--carousel slick-carousel slider col-10 m-auto ">
                <?php get_product()?>
            </div>  
            <div class="block--button col-10 m-auto"> 
                <button type="button" class="view-more">XEM THÊM</button>   
            </div>   
            <div class="block--header col-10 m-auto ">
                <span class="block--header_title">TNC CHANNEL</span>
            </div>
            <div class="block--channel col-10 m-auto">
                <div class="channel_content ">
                    <div class="content-left">
                        <div class="item">
                            <iframe src="https://www.youtube.com/embed/W6fdNkwRuLk" title="PEWPEW MUA PC 27 TRIỆU Và Pha Chốt Đơn Nhanh Như HACK SPEED Tại TNC Store! - Mua PC Như Mua Rau 😱" frameborder="0" allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture" allowfullscreen>
                            </iframe>
                            <a href="https://www.tncstore.vn/pewpew-mua-pc-tai-TNC-STORE.html" class="tnc__title">PEWPEW MUA PC 27 TRIỆU Và Pha Chốt Đơn Nhanh Như HACK SPEED Tại TNC Store! - Mua PC Như Mua Rau </a>
                        </div>
                    </div>
                    <div class="content-right">
                        <div class="item">
                        <iframe width="980" height="550" src="https://www.youtube.com/embed/c0wTzjjklMs" title="Cấu Hình PC Chơi Liên Minh Huyền Thoại 10 NĂM Của Nữ MC Minh Nghi" frameborder="0" allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture" allowfullscreen></iframe>
                            <a href="https://www.tncstore.vn/pewpew-mua-pc-tai-TNC-STORE.html" class="tnc__title">Cấu Hình PC Chơi Liên Minh Huyền Thoại "10 NĂM KHÔNG HỎNG" Của Nữ MC-Streamer Minh Nghi</a>
                        </div>
                        <div class="item">
                        <iframe width="980" height="550" src="https://www.youtube.com/embed/Gd98kaAeXzA" title="[TNC Reaction] KHÁNH VY ĐI MUA PC BỊ PEWPEW "THUỐC ĐỒ NGON" TẠI TNC STORE NHƯ THẾ NÀO?" frameborder="0" allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture" allowfullscreen></iframe>
                            <a href="https://www.tncstore.vn/pewpew-mua-pc-tai-TNC-STORE.html" class="tnc__title">KHÁNH VY ĐI MUA PC BỊ PEWPEW "THUỐC ĐỒ NGON" TẠI TNC STORE NHƯ THẾ NÀO? [REACTION]</a>
                        </div>
                        <div class="item">
                        <iframe width="980" height="550" src="https://www.youtube.com/embed/CeKZBe-kFfY" title="Khi Độ Mixi Đi Lượn Phố Vớ Được Hàng Khủng... Màn Hình Cực Nét Thì Stream Đến Bao Giờ??" frameborder="0" allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture" allowfullscreen></iframe>
                            <a href="https://www.tncstore.vn/pewpew-mua-pc-tai-TNC-STORE.html" class="tnc__title">Khi Độ Mixi Đi Lượn Phố Vớ Được Hàng Khủng... Màn Hình Cực Nét Thì Stream Đến Bao Giờ??</a>
                        </div>
                    </div>
                </div>    
            </div>
            <div class="block--button col-10 m-auto"> 
                <button type="button" class="view-more">Xem thêm video</button>   
            </div>  
            <div class="block--header col-10 m-auto ">
                <span class="block--header_title">GIỚI THIỆU VỀ TNC</span>
            </div>
            <div class="block--infoCompany col-10 m-auto">
                 <div class="infoCompany_content">
                    <div class="content-left">
                        <h3>Nhà cung cấp linh kiện & dịch vụ máy tính, gaming số 1 miền Bắc.</h3>
                        <p>
                        Với đội ngũ nhân viên là những người trẻ đam mê và yêu thích về công nghệ, cùng kiến thức về IT tốt kèm với sự dày dặn kinh nghiệm, chúng tôi luôn sẵn sàng giải đáp bất cứ thắc mắc của các khách hàng một cách nhanh nhất có thể. Dù bạn không mua, chúng tôi vẫn tư vấn cho bạn.        
                        </p>
                    </div>
                    <div class="content-right">
                    <iframe width="980" height="524" src="https://www.youtube.com/embed/RQIz50OJvHU" title="SHOWREEL 2018-2021 | TNC Channel - Sẵn Sàng Chuyển Mình!" frameborder="0" allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture" allowfullscreen></iframe>  
                    </div>
                 </div>                   
            </div>
            <div class="block--header col-10 m-auto ">
                <span class="block--header_title">CÁC THƯƠNG HIỆU</span>
            </div>
            <div class="slick-carousel3 col-10 m-auto">
                <div class="item">
                    <a href="#"> <img src="img/local/acer.png" alt="#"></a>
                </div>
                <div class="item">
                    <a href="#"><img src="img/local/adata.png" alt="#"></a> 
                </div>
                <div class="item">
                    <a href="#"> <img src="img/local/aerocool.png" alt="#"></a>
                </div>
                <div class="item">
                    <a href="#"> <img src="img/local/afox.jpg" alt="#"></a>
                </div>
                <div class="item">
                    <a href="#"><img src="img/local/akko.jpg" alt="#"></a> 
                </div>
                <div class="item">
                    <a href="#"> <img src="img/local/amd.jpg" alt="#"></a>
                </div>
                <div class="item">
                    <a href="#"> <img src="img/local/antec.jpg" alt="#"></a>
                </div>
                <div class="item">
                    <a href="#"> <img src="img/local/AOC.png" alt="#"></a>
                </div>
                <div class="item">
                    <a href="#"> <img src="img/local/apple.jpg" alt="#"></a>
                </div>
            </div>
            <div class="block--header col-10 m-auto ">
                <span class="block--header_title">ĐÁNH GIÁ TỪ KHÁCH HÀNG</span>
            </div>
            <div class="slick-carousel4 col-10 m-auto">
                    <div class=item>
                        <img src="img/rate/1.jpg" alt="#">
                    </div>
                    <div class=item>
                        <img src="img/rate/2.jpg" alt="#">
                    </div>
                    <div class=item>
                        <img src="img/rate/3.jpg" alt="#">
                    </div>
                    <div class=item>
                        <img src="img/rate/4.jpg" alt="#">
                    </div>
                    <div class=item>
                        <img src="img/rate/5.jpg" alt="#">
                    </div>
            </div>
        </div>
        
    </div>

    <?php include_once ('include/footer.php'); ?>
    <!-- <script src = '../js/toast.min.js' > </script> -->
    <script type = "text/javascript" src = "//cdn.jsdelivr.net/npm/slick-carousel@1.8.1/slick/slick.min.js"> </script>
    <?php include_once ('js/js_customIndex.php'); ?>
</body>
</html>
