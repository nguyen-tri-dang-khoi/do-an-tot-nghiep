<?php 
    include_once 'db.php';
    //session_start();
?>

<!DOCTYPE html>
<html lang="en">
<?php include_once ('include/head.php'); ?>
<body>
    <?php include_once ('include/menu.php');?>
    <div class="news__content row col-10 m-auto mt-5 mb-5">
        <div class="container p-0">
            <div class="block--header col-10 m-auto ">
                <span class="block--header_title">Tin tức TNC</span>
            </div>
            <div class="ok-content d-flex">
            
                <div class="wp-70 d-flex f-columns j-between mr-35">
                <?php
                    $i_flex = 0;
                    $conn = connect();
                    $img = "select * from notification where is_delete like 0";
                    $result = mysqli_query($conn, $img);
                    while ($row = mysqli_fetch_array($result)){
                        if($i_flex % 2 == 0) {
                            echo '<div class="d-flex">';
                        }
                ?>
                        <div class="st mr-35">
                            <div onclick="location.href='TNC_news_detail.php?id=<?php echo $row['id'];?>'" style="cursor:pointer;" class="st-img">
                                <img class="img-1" src="<?php echo "../admin/" . $row['img_name'];?>" alt="">
                            </div>
                            <div class="st-text">
                                <h2 class="st-title"><?php echo $row['title'];?></h2>
                                <p class="st-text-note"><?php echo $row['content'];?></p>
                            </div>
                        </div>
                <?php
                        $i_flex++;
                        if($i_flex % 2 == 0) {
                            echo '</div>';
                        }
                    }
                ?>
                    <!-- <div class="d-flex">
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
                    </div> -->
                </div>
                <div class="wp-30">
                    <div class="block--header col-10 m-auto ">
                        <span class="block--header_title">Top 3 bài viết xem nhiều nhất</span>
                    </div>
                    <div class="st-2 d-flex f-columns j-between">
                        <?php
                            $img_top_3 = "select * from notification where is_delete like 0 order by views desc limit 3";
                            $result = mysqli_query($conn, $img_top_3);
                            while($row = mysqli_fetch_array($result)) {
                        ?>
                        <div class="st st-2 d-flex j-between a-start">
                            <div class="st-img" style="width:">
                                <img class="img-2" src="<?php echo "../admin/" . $row['img_name'];?>" alt="">
                            </div>
                            <div class="st-text ml-20">
                                <p class="st-ct"><?php echo $row['title'];?></p>
                            </div>
                        </div>
                        <?php
                            }
                        ?>
                        <!-- <div class="st st-2 d-flex j-between a-start">
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
                        </div> -->
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
