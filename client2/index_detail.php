<?php include_once 'db.php'; ?>

<!DOCTYPE html>
<html lang="en">
<?php include_once ('include/head.php'); ?>

<body>
    <script src="slick-master/slickcustom.js"></script>
    <?php include_once ('include/menu.php');?>

    <div class="Product__Detail container-fluid">
        <div class=" row p-0 block__home">
            <div class="col-10 m-auto row p-0">
                <div id="carouselExampleDark" class="col-6 p-0 carousel carousel-dark slide" data-bs-ride="carousel">
                    <div class="carousel-indicators">
                        <button type="button" data-bs-target="#carouselExampleDark" data-bs-slide-to="0" class="active" aria-current="true" aria-label="Slide 1"></button>
                        <button type="button" data-bs-target="#carouselExampleDark" data-bs-slide-to="1" class="active" aria-label="Slide 2"></button>
                        <button type="button" data-bs-target="#carouselExampleDark" data-bs-slide-to="2" aria-label="Slide 3"></button>
                    </div>  
                    <div class="carousel-inner">
                        <div  class="carousel-item active" data-bs-interval="10000">
                            <img src="Img/product/giado01a.jpg" class="d-block w-80 m-auto" alt="...">
                            <div class="carousel-caption d-none d-md-block">
                            </div>
                        </div>
                        <div class="carousel-item" data-bs-interval="2000">
                            <img src="Img/product/giado01b.jpg" class="d-block w-80 m-auto" alt="...">
                            <div class="carousel-caption d-none d-md-block">
                            </div>
                        </div>
                        <div class="carousel-item">
                            <img src="Img/product/giado01c.jpg" class="d-block w-80 m-auto" alt="...">
                            <div class="carousel-caption d-none d-md-block">
                            </div>
                        </div>
                        <div class="carousel-item">
                            <img src="Img/product/giado01d.jpg" class="d-block w-80 m-auto" alt="...">
                            <div class="carousel-caption d-none d-md-block">
                            </div>
                        </div>
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
                        <h3 style="color: #005ec4">Giá đỡ màn hình Human Motion T6 Black</h3>
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
                                echo $row["description"] 
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
                            <p style="font-weight:700">690.000 đ</p>
                        </div>
                        <div>

                        </div>
                        <div>

                        </div>
                    </div>  
                </div>
            </div>
            <div class="block--header col-10 m-auto ">
                <span class="block--header_title">SẢN PHẨM  MỚI VỀ</span>
            </div>
            <div class="block--carousel slick-carousel slider col-10 m-auto ">
                <?php get_product()?>
            </div>

        <div>
    </div>

    <?php include_once ('include/footer.php'); ?>
    <script type="text/javascript" src="//cdn.jsdelivr.net/npm/slick-carousel@1.8.1/slick/slick.min.js"></script>
</body>
</html>
