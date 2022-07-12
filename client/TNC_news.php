<?php 
    include_once 'db.php';
    //session_start();
?>

<!DOCTYPE html>
<html lang="en">
<?php include_once ('include/head.php'); ?>
<?php
    $conn = connect();
    $img = "select * from notification where is_delete like 0";
    $result = mysqli_query($conn, $img);
    while ($row = mysqli_fetch_array($result)){
        
    }
?>
<body>
    <?php include_once ('include/menu.php');?>
    <div class="news__content row col-10 m-auto mt-5 mb-5">
        <div class="container p-0 ">
            <div class="ok-content d-flex">
                <div class="wp-70 d-flex f-columns j-between mr-35">
                    <div class="d-flex">
                        <div class="st mr-35">
                            <div class="st-img">
                                <img class="img-1" src="<?php ?>" alt="">
                            </div>
                            <div class="st-text">
                                <h2 class="st-title">LỰA CHỌN LAPTOP CHO SINH VIÊN VỪA HỌC VỪA CHƠI 2022</h2>
                                <p class="st-text-note">
                                Laptop không chỉ dùng để học tập và làm việc mà còn là công cụ giải trí hữu hiệu của các bạn học sinh, sinh viên. Cùng TNC tìm kiếm chiếc laptop chân ái dành cho bạn trong bài viết này nhé!
                                </p>
                            </div>
                        </div>
                        <div class="st ">
                            <div class="st-img">
                                <img class="img-1" src="Img/khoi/anh_2.jpg" alt="">
                            </div>
                            <div class="st-text">
                                <h2 class="st-title">LỰA CHỌN LAPTOP CHO SINH VIÊN VỪA HỌC VỪA CHƠI 2022</h2>
                                <p class="st-text-note">
                                Laptop không chỉ dùng để học tập và làm việc mà còn là công cụ giải trí hữu hiệu của các bạn học sinh, sinh viên. Cùng TNC tìm kiếm chiếc laptop chân ái dành cho bạn trong bài viết này nhé!
                                </p>
                            </div>
                        </div>
                    </div>
                    <div class="d-flex">
                        <div class="st mr-35">
                            <div class="st-img">
                                <img class="img-1" src="Img/khoi/anh_3.png" alt="">
                            </div>
                            <div class="st-text">
                                <h2 class="st-title">LỰA CHỌN LAPTOP CHO SINH VIÊN VỪA HỌC VỪA CHƠI 2022</h2>
                                <p class="st-text-note">
                                Laptop không chỉ dùng để học tập và làm việc mà còn là công cụ giải trí hữu hiệu của các bạn học sinh, sinh viên. Cùng TNC tìm kiếm chiếc laptop chân ái dành cho bạn trong bài viết này nhé!
                                </p>
                            </div>
                        </div>
                        <div class="st">
                            <div class="st-img">
                                <img class="img-1" src="Img/khoi/anh_4.png" alt="">
                            </div>
                            <div class="st-text">
                                <h2 class="st-title">LỰA CHỌN LAPTOP CHO SINH VIÊN VỪA HỌC VỪA CHƠI 2022</h2>
                                <p class="st-text-note">
                                Laptop không chỉ dùng để học tập và làm việc mà còn là công cụ giải trí hữu hiệu của các bạn học sinh, sinh viên. Cùng TNC tìm kiếm chiếc laptop chân ái dành cho bạn trong bài viết này nhé!
                                </p>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="wp-30">
                    <div class="st-2 d-flex f-columns j-between">
                        <div class="st st-2 d-flex j-between a-start">
                            <div class="st-img" style="width:">
                                <img class="img-2" src="Img/khoi/anh_5.png" alt="">
                            </div>
                            <div class="st-text ml-20">
                                <p class="st-ct">MÀN HÌNH MSI MD241P ULTRAMARINE : THIẾT KẾ ĐỈNH CAO, LỰA CHỌN TINH TẾ!</p>
                            </div>
                        </div>
                        <div class="st st-2 d-flex j-between a-start">
                            <div class="st-img">
                                <img class="img-2" src="Img/khoi/anh_6.jpg" alt="">
                            </div>
                            <div class="st-text ml-20">
                                <p class="st-ct">Đập hộp dàn Mainboard Z490 siêu khủng của ASUS - Phổ cập Thunderbolt 3 đến mọi nhà</p>
                            </div>
                        </div>
                        <div class="st st-2 d-flex j-between a-start">
                            <div class="st-img">
                                <img class="img-2" src="Img/khoi/anh_7.png" alt="">
                            </div>
                            <div class="st-text ml-20">
                                <p class="st-ct">G.SKILL Trident Z Royal đạt kỷ lục thế giới DDR4-6666Mhz  với ASUS ROG</p>
                            </div>
                        </div>
                    </div>
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
