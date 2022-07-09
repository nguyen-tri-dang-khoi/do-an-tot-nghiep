<?php 
    include_once 'db.php';
    //session_start();
?>

<!DOCTYPE html>
<html lang="en">
<?php include_once ('include/head.php'); ?>
<?php
    $_SESSION['cart'] = isset($_SESSION['cart']) ? $_SESSION['cart'] : [];
?>
<body>
    <script src="slick-master/slickcustom.js"></script>
    <?php include_once ('include/menu.php');?>
    <div class="modal-content row col-10 m-auto mt-4 mb-2">
            
            <div class="modal-body row ">
                <div class="body_products col-7 ">
                    <div id="view_cart" class="content-products-cart cart" style="border:none;">
                    <div class="modal-target">      
                            <div><h5>1. Chọn sản phẩm</h5></div>
                            <div><h5>2. Xác nhận đơn hàng</h5></div>
                            <div><h5>3. Thanh toán</h5></div>
                        </div>
                        <div class="modal-header">
                            <h4  style="font-weight: 800"class="modal-title" id="exampleModalLabel">Thông tin khách hàng</h4>
                        </div>
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
            <form id="infoCustomer_Checkout" action="confirm_checkout_process.php" method="post" class="row col-12 m-auto p-0">
                <div class="col-12 m-auto mt-2 p-0 ">
                    <label for="inputAddress2" class="form-label">Họ và Tên</label>
                    <input name="full_name" type="text" value="<?php echo $row['full_name']; ?>" class="form-control" placeholder="Họ và tên ">
                </div>
                <div class="col-md-12 m-auto mt-2 p-0 ">
                    <label for="inputEmail4" class="form-label">Email</label>
                    <input name="email" type="email" value="<?php echo $row['email']; ?>" class="form-control"  placeholder="abc@email.com">
                </div>
                <div class="col-md-12 m-auto mt-2 p-0">
                    <label for="inputcontact" class="form-label">Số điện thoại</label>
                    <input name="phone" type="text" value="<?php echo ($row['phone'] ? $row['phone'] : "");?>" class="form-control"  placeholder="0123456xxx">
                </div>
                <div class="col-12 m-auto  mt-2 p-0 ">
                    <label for="inputAddress" class="form-label">Địa chỉ</label>
                    <input name="address" type="text" value="<?php echo ($row['address'] ? $row['address'] : ""); ?>" class="form-control" placeholder="xxx Trần Xuân Soạn - Tân Thuận Tây - Quận 7 - HCM">
                </div>
                <div class="col-12 m-auto mt-2  p-0">
                    <label for="inputAddress" class="form-label">Ghi chú</label>
                    <textarea name="address" value="<?php echo ($row['address'] ? $row['address'] : ""); ?>" class="form-control" placeholder="Bỏ tạm address đó sửa sau"></textarea>
                </div>
                <hr>
                <h4 class="p-0" style="font-weight: 700;">Hình thức thanh toán</h4>
                <div class="form-check">
                    <input class="form-check-input" type="radio" name="cash_checkout" id="flexRadioDefault1" value="cod">
                        <label class="form-check-label" for="flexRadioDefault1">
                            Thanh toán tại nơi giao hàng
                        </label>
                </div>
                    <div class="form-check">
                    <input class="form-check-input" type="radio" name="cash_checkout" id="flexRadioDefault2" value="vnpay" checked>
                    <label class="form-check-label" for="flexRadioDefault2">
                        Thanh toán bằng VNPay
                    </label>
                </div>
                 <div class="col-12 mt-2 p-0 m-auto">
                    <button type="submit" class="btn btn-primary">Cập nhật thông tin khác hàng</button>
                </div>
            </form>
    <?php
            }
        }
    ?>
                    </div>
                </div>
                <div class="body_Price col-5 pr-0">
                    <div class="allPrice">
                        <h5>Thông tin giỏ hàng</h5>
                        <div class="totalProduct">
                            <p>Số lượng sản phẩm</p>
                            <span>
                                <?php
                                    $sum = 0;
                                    $_SESSION['cart'] = isset($_SESSION['cart']) ? $_SESSION['cart'] :[];
                                        foreach($_SESSION['cart'] as $cart){
                                            $sum += $cart["count"];
                                        }
                                        echo $sum;
                                ?>
                            </span>
                        </div>
                        <div class="totalPrice">
                            <p>Tổng chi phí</p>
                            <span>
                                <?php
                                    $_SESSION['cart'] = isset($_SESSION['cart']) ? $_SESSION['cart'] :[];
                                    $totalPrice = 0;
                                    
                                        foreach($_SESSION['cart'] as $cart){
                                           $totalPrice += $cart['price'] * $cart['count'];
                                        }$totalPrice = number_format($totalPrice,"0",".",".");
                                        echo $totalPrice. " đ";
                                ?>
                            </span>
                        </div>
                        <p class="noteVAT">Đã bao gồm VAT (nếu có)</p>
                        <div>
                            <?php
                                foreach($_SESSION['cart'] as $key => $value) {
                            ?>
                            <hr>
                                <div class="info_cartCheckout">
                                    <div class="name_productCheckout"><?php echo $value['name'];?><span></span> </div>
                                    <div class="count_productCheckout"><span>Số lượng: </span>  <?php echo $value['count'];?></div>
                                    <div class="d-flex mt-3">
                                        <div class="Price_productCheckout mr-6"><?php echo "Giá SP: ". number_format($value['price'],"0",".",".");?><span>đ</span> </div>
                                        <div class="totalPrice_productCheckout  m-auto"> <?php echo "Thành tiền: " .number_format(($value['price'] * $value['count']),"0",".",".");?> <span>đ</span></div>
                                    </div>
                                </div>
                            <?php 
                                }
                            ?>
                        </div>
                        <button onclick="submitForm()" class="go-cart mt-4 disable">Thanh toán</button>
                    </div>
                </div>
            </div>
        </div>
    <?php include_once ('include/footer.php'); ?>
    <!-- <script src = '../js/toast.min.js' > </script> -->
    <script type = "text/javascript" src = "//cdn.jsdelivr.net/npm/slick-carousel@1.8.1/slick/slick.min.js"> </script>
    <?php include_once ('js/js_customIndex.php'); ?>
    <script>
        function submitForm(){
            $('#infoCustomer_Checkout').submit();
        }
    </script>
</body>
</html>
