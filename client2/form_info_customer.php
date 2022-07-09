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
        $_SESSION['customer_id'] = isset($_SESSION['customer_id']) ? $_SESSION['customer_id'] : null;
        if($_SESSION['customer_id']){
            $customer_id = $_SESSION['customer_id'];
            $conn = connect();
            $sql_customer = "select * from user where type = 'customer' and id = '$customer_id' limit 1";
            $result = mysqli_query($conn, $sql_customer);
            if(mysqli_num_rows($result) > 0){
                $row = mysqli_fetch_assoc($result);
    ?>
            <div class="col-10 m-auto p-0 row">
                <form action="form_info_customer_process.php" method="post" class="row d-flex col-6 m-auto p-0">
                    <div class="col-12 m-auto p-0">
                        <label for="inputAddress2" class="form-label">Họ và Tên</label>
                        <input name="full_name" type="text" value="<?php echo $row['full_name']; ?>" class="form-control" placeholder="Họ và tên ">
                    </div>
                    <div class="col-md-12 m-auto p-0">
                        <label for="inputEmail4" class="form-label">Email</label>
                        <input name="email" type="email" value="<?php echo $row['email']; ?>" class="form-control"  placeholder="abc@email.com">
                    </div>
                    <div class="col-md-12 m-auto p-0">
                        <label for="inputcontact" class="form-label">Số điện thoại</label>
                        <input name="phone" type="text" value="<?php echo ($row['phone'] ? $row['phone'] : "");?>" class="form-control"  placeholder="0123456xxx">
                    </div>
                    <div class="col-12 m-auto mb-1 p-0">
                        <label for="inputAddress" class="form-label">Địa chỉ</label>
                        <input name="address" type="text" value="<?php echo ($row['address'] ? $row['address'] : ""); ?>" class="form-control" placeholder="xxx Trần Xuân Soạn - Tân Thuận Tây - Quận 7 - HCM">
                    </div>
                    <input type="hidden" name="thao_tac" value="updateInfo">
                    <div class="col-12 m-auto">
                        <button type="submit" class="btn btn-primary">Cập nhật</button>
                    </div>
                </form>
                <div class="col-6">
                    <form action="form_info_customer_process.php" method="post" class="row d-flex col-12 m-auto p-0">
                        <div class="col-12 m-auto p-0">
                            <label for="inputAddress2" class="form-label">Họ và Tên</label>
                            <input name="full_name" type="text" value="<?php echo $row['full_name']; ?>" class="form-control" placeholder="Họ và tên ">
                        </div>
                        <div class="col-md-12 m-auto p-0">
                            <label for="inputEmail4" class="form-label">Email</label>
                            <input name="email" type="email" value="<?php echo $row['email']; ?>" class="form-control"  placeholder="abc@email.com">
                        </div>
                        <div class="col-md-12 m-auto p-0">
                            <label for="inputcontact" class="form-label">Số điện thoại</label>
                            <input name="phone" type="text" value="<?php echo ($row['phone'] ? $row['phone'] : "");?>" class="form-control"  placeholder="0123456xxx">
                        </div>
                        <div class="col-12 m-auto mb-1 p-0">
                            <label for="inputAddress" class="form-label">Địa chỉ</label>
                            <input name="address" type="text" value="<?php echo ($row['address'] ? $row['address'] : ""); ?>" class="form-control" placeholder="xxx Trần Xuân Soạn - Tân Thuận Tây - Quận 7 - HCM">
                        </div>
                        <input type="hidden" name="thao_tac" value="updateInfo">
                        <div class="col-12 m-auto">
                            <button type="submit" class="btn btn-primary">Cập nhật</button>
                        </div>
                    </form>
                </div>
            </div>
    <?php
            }
        }
    ?>
    
    <?php include_once ('include/footer.php'); ?>
    <!-- <script src = '../js/toast.min.js' > </script> -->
    <script type = "text/javascript" src = "//cdn.jsdelivr.net/npm/slick-carousel@1.8.1/slick/slick.min.js"> </script>
    <?php include_once ('js/js_customIndex.php'); ?>
</body>
</html>
