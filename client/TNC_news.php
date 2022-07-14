<?php 
    include_once 'db.php';
    $conn = connect();
    $page = isset($_REQUEST['page']) && is_numeric($_REQUEST['page']) && $_REQUEST['page'] > 0 ? $_REQUEST['page'] : 1; 
    $limit = 8;
    $start_page = $limit * ($page - 1);
    $sql_get_count = "select count(*) as 'countt' from notification where is_delete like 0 limit 1";
    $result = mysqli_query($conn, $sql_get_count);
    $row2 = mysqli_fetch_assoc($result);
    $total = $row2['countt'];
    unset($_GET['page']);
    $str_get = http_build_query($_GET);
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
                    $img = "select * from notification where is_delete like 0 limit $start_page,$limit";
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
                            </div>
                        </div>
                <?php
                        $i_flex++;
                        if($i_flex % 2 == 0) {
                            echo '</div>';
                        }
                    }
                ?>
                    <nav aria-label="Page navigation example">
                        <ul class="pagination justify-content-center">
                            <?php
                                $pagination = ceil($total / $limit) + 1; 
                                for($i = 1 ; $i < $pagination ; $i++) {
                            ?>
                                    <li class="page-item <?=$i == $page ? 'active' : '';?>"><a class="page-link" href="TNC_news.php?page=<?php echo $i;?>&<?php echo $str_get;?>"><?php echo $i;?></a></li>
                            <?php
                                }
                            ?>
                        </ul>
                    </nav>
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
