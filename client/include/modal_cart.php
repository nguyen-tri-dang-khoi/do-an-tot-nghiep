<!-- Button trigger modal -->
<button type="button" class="btn " data-bs-toggle="modal" data-bs-target="#exampleModal">
    <i class="fa-solid fa-cart-shopping"></i>
</button>

<!-- Modal -->
<div class="modal fade" id="exampleModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Bạn đang có <span>0</span> sản phẩm khác nhau trong giỏ hàng !</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" ></button>
            </div>
            <div class="modal-body row ">
                <div class="body_products col-7">
                    <div id='view_cart' class="content-products-cart cart">
                    </div>
                </div>
                <div class="body_Price col-5">
                    <div class="allPrice">
                        <h5>Thông tin giỏ hàng</h5>
                        <div class="totalProduct">
                            <p >Số lượng sản phẩm</p>
                            <span >0</span>
                        </div>
                        <div class="totalPrice">
                            <p>Tổng chi phí</p>
                            <span>0 đ</span>
                        </div>
                        <p class="noteVAT">Đã bao gồm VAT (nếu có)</p>
                        <a href="cart.php" class="go-cart disable">ĐẾN GIỎ HÀNG</a>
                        <a onclick = "deleteAllCart()" href="javascript:void(0)" style="background: rgb(237, 27, 36); border-color: rgb(237, 27, 36); color: rgb(255, 255, 255);">XÓA GIỎ HÀNG</a>
                        <a href="http://localhost:8080/project/client2/Products.php?keyword=&id_loai_san_pham=" class="viewMores">XEM SẢN PHẨM KHÁC</a>
                    </div>
                    <div class="info-cart">
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
    </div>
</div>