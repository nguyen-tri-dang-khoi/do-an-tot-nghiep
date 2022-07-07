<?php 
    include_once 'db.php';
    //session_start();
?>

<!DOCTYPE html>
<html lang="en">
<?php include_once ('include/head.php'); ?>
<?php
    $id_loai_san_pham = isset($_REQUEST['id_loai_san_pham']) ? $_REQUEST['id_loai_san_pham'] : null;
?>
<body>
    <script src="slick-master/slickcustom.js"></script>
    <?php include_once ('include/menu.php');?>


<div class="content__category col-10">
    <div>
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
    <div class="d-flex w-100">
        <div class="block--content col-10 m-auto">
                    <div class="content_collapse">
                        <?php
                        if($id_loai_san_pham) {
                            $conn = connect();
                            $sql_product_type = "Select * from product_type where is_active like 1 and is_delete like 0 and parent_id = $id_loai_san_pham";
                            //print_r($sql_product_type);
                            $result = mysqli_query($conn, $sql_product_type);
                            if(mysqli_num_rows($result) > 0){
                                while($row = mysqli_fetch_assoc($result)){
                                    $id2 = $row['id'];
                                    $link_href = "";
                                    $sql_check_parent_id = "select id from product_type where parent_id = '$id2'";
                                    //print_r($sql_check_parent_id);
                                    $result_id = mysqli_query($conn, $sql_check_parent_id);
                                    $result_id = mysqli_fetch_assoc($result_id);

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
