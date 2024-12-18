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
    <div class="modal-content row col-10 m-auto mt-5 mb-5">
            
            <div class="modal-body row ">
                <div class="body_products col-7">
                    <div id="view_cart" class=" content-products-carts cart">
                        <div class="modal-target3">      
                            <div><h5>1. Chọn sản phẩm</h5></div>
                            <div><h5>2. Xác nhận đơn hàng</h5></div>
                            <div style="background-color: #f0f3fc;"><h5 style="color: #29324e;">3. Thanh toán</h5></div>
                        </div>
                        <div class="modal-headerss">
                            <h3  style="font-weight: 800"class="modal-title" id="exampleModalLabel">Thông tin sản phẩm</h3>
                        </div>
                        <?php
                            //print_r($_SESSION['cart']);
                            foreach($_SESSION['cart'] as $key => $value) {
                        ?>
                        <div data-id="<?php echo $key;?>" class="items_carts item_cart">

                                <div class="img_products"><img src="<?php echo "../admin/". $value['img'];?>" alt="..."></div>
                                <div class="info_products">
                                    <div class="name_products"><p><?php echo "Tên Sp: ". $value['name'];?></p></div>
                                    <div class="Price_products"><p><?php echo "Giá: ". number_format($value['price'],0,".",".");?>đ</p></div>
                                </div>

                            <div class="change_product">
                                <div class="input-product">
                                    <!-- <span onclick="updateInfoCart('-')">-</span> -->
                                    <span>Số lượng: </span>
                                    <input name="count" readonly="" type="text" value="<?php echo  $value['count'];?>"> 
                                    <!-- <span onclick="updateInfoCart('+')">+</span> -->
                                </div>
                                <div class="i-product">
                                    <i onclick="deleteCart2()" class="fa-solid fa-trash-can"></i>
                                </div>
                            </div>
                        </div>
                        <?php
                            }
                        ?>
                    </div>
                </div>
                <div class="body_Price col-5">
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
                        <a href="confirm_checkout.php" class="go-cart disable">Xác nhận đơn hàng</a>
                        <a onclick="deleteAllCart()" href="javascript:void(0)" style="background: rgb(237, 27, 36); border-color: rgb(237, 27, 36); color: rgb(255, 255, 255);">XÓA GIỎ HÀNG</a>
                        <a href="http://localhost:8080/project/client2/Products.php?keyword=&id_loai_san_pham=#" class="viewMores">XEM SẢN PHẨM KHÁC</a>
                    </div>
                    <div class="info-carts">
                        <div class="d-flex"><i class="fa-solid fa-circle-check"></i><p>Hỗ trợ trả góp 0%, trả trước 0đ</p></div>
                        <div class="d-flex"><i class="fa-solid fa-circle-check"></i><p>Hoàn tiền 200% khi phát hiện hàng giả</p></div>
                        <div class="d-flex"><i class="fa-solid fa-circle-check"></i><p>Giao hàng từ 5 - 7 ngày toàn quốc</p></div>
                        <div class="d-flex"><i class="fa-solid fa-circle-check"></i><p>Đội ngũ kĩ thuật hỗ trợ online 7/7  </p> </div>
                        <div class="logo-footer-cart">
                            <div class="logo">
                                <img src="img/logo-footer/tien-mat.png" alt="tienmat">
                                <img src="img/logo-footer/internet-bank.png" alt="internet-banking">
                                <img src="img/logo-footer/mastercard.png" alt="mastercard">
                                <img src="img/logo-footer/visa.png" alt="visa">
                                <img src="img/logo-footer/bo-cong-thuong.png" alt="bocongthuong">
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
