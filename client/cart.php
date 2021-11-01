<?php
    include_once("../lib/database.php");
    if(is_get_method()) {
        include_once("include/head.meta.php");
        include_once("include/menu.php");
        // code to be executed get method
?>
<!--html & css section start-->
<div role="main" class="main shop pb-4">
<div class="container">
    <div class="row">
        <div class="col">
            <ul class="breadcrumb font-weight-bold text-6 justify-content-center my-5">
                <li class="text-transform-none me-2">
                    <a href="cart.php" class="text-decoration-none text-color-primary">Giỏ hàng</a>
                </li>
                <li class="text-transform-none text-color-grey-lighten me-2">
                    <a href="checkout.php" class="text-decoration-none text-color-grey-lighten text-color-hover-primary">Thanh toán</a>
                </li>
                <li class="text-transform-none text-color-grey-lighten">
                    <a href="order_complete.php" class="text-decoration-none text-color-grey-lighten text-color-hover-primary">Đơn hàng hoàn tất</a>
                </li>
            </ul>
        </div>
    </div>
    <div class="row pb-4 mb-5">
        <div class="col-lg-9 mb-5 mb-lg-0">
            <form method="post" action="">
                <div class="table-responsive">
                    <table class="shop_table cart">
                        <thead>
                            <tr class="text-color-dark">
                                <th class="product-thumbnail" width="15%">
                                    &nbsp;
                                </th>
                                <th class="product-name text-uppercase" width="30%">
                                    Tên sản phẩm
                                </th>
                                <th class="product-price text-uppercase" width="10%">
                                    Đơn giá
                                </th>
                                <th class="product-quantity text-uppercase" width="5%">
                                    Số lượng
                                </th>
                                <th class="product-subtotal text-uppercase text-end" width="12%">
                                    Số tiền
                                </th>
                                <th class="product-subtotal text-uppercase text-end" width="28%">
                                    Thao tác
                                </th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php   
                                $i = 0;
                                if(!isset($_SESSION['cart'])) {
                                    $_SESSION['cart'] = [];
                                }
                                $sum = 0;
                                foreach($_SESSION['cart'] as $cart) {
                                    $sum += $cart['pi_count'] * $cart['pi_price'];
                            ?>
                            <tr id="cart<?=$i;?>" class="cart_table_item">
                                <td class="product-thumbnail">
                                    <div class="product-thumbnail-wrapper">
                                        <a href="#" class="product-thumbnail-image" title="Photo Camera">
                                            <img width="90" height="90" alt="" class="img-fluid" src="<?=$cart['pi_image']?>">
                                        </a>
                                    </div>
                                </td>
                                <td class="product-name">
                                    <a href="product_detail?id=<?=$cart['pi_id']?>" class="font-weight-semi-bold text-color-dark text-color-hover-primary text-decoration-none"><?=$cart['pi_name']?></a>
                                </td>
                                <td class="product-price">
                                    <span class="amount font-weight-medium text-color-grey"><?=$cart['pi_price']?> VNĐ</span>
                                </td>
                                <td class="product-quantity">
                                    <div class="quantity float-none m-0">
                                        <input type="button" class="minus text-color-hover-light bg-color-hover-primary border-color-hover-primary" value="-">
                                        <input type="text" class="input-text qty text" title="Qty" value="<?=$cart['pi_count']?>" name="pi_count" min="1" step="1">
                                        <input type="button" class="plus text-color-hover-light bg-color-hover-primary border-color-hover-primary" value="+">
                                    </div>
                                </td>
                                <td class="product-subtotal text-end">
                                    <span class="amount text-color-dark font-weight-bold text-4"><?=$cart['pi_price'] * $cart['pi_count'];?> VNĐ</span>
                                </td>
                                <td class="product-subtotal text-end">
                                    <button type="button" data-index="<?=$i;?>" class="btn-update btn btn-primary">Sửa</button>
                                    <button type="button" data-index="<?=$i;?>" class="btn-delete btn btn-danger">Xoá</button>
                                </td>
                            </tr>
                            <?php
                                    $i++;
                                }
                            ?>
                            <tr>
                                <td colspan="5">
                                    <div class="row justify-content-between mx-0">
                                        <div class="col-md-auto px-0 mb-3 mb-md-0">
                                            <div class="d-flex align-items-center">
                                                <!--<input type="text" class="form-control h-auto border-radius-0 line-height-1 py-3" name="couponCode" placeholder="Coupon Code" />-->
                                                <!--<button type="submit" class="btn btn-light btn-modern text-color-dark bg-color-light-scale-2 text-color-hover-light bg-color-hover-primary text-uppercase text-3 font-weight-bold border-0 border-radius-0 ws-nowrap btn-px-4 py-3 ms-2">Apply Coupon</button>-->
                                            </div>
                                        </div>
                                        <div class="col-md-auto px-0">
                                            <!--<button type="submit" class="btn btn-light btn-modern text-color-dark bg-color-light-scale-2 text-color-hover-light bg-color-hover-primary text-uppercase text-3 font-weight-bold border-0 border-radius-0 btn-px-4 py-3">Update Cart</button>-->
                                        </div>
                                    </div>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </form>
        </div>
        <div class="col-lg-3 position-relative">
            <div class="card border-width-3 border-radius-0 border-color-hover-dark" data-plugin-sticky data-plugin-options="{'minWidth': 991, 'containerSelector': '.row', 'padding': {'top': 85}}">
                <div class="card-body">
                    <h4 class="font-weight-bold text-uppercase text-4 mb-3">Tổng tiền giỏ hàng</h4>
                    <table class="shop_table cart-totals mb-4">
                        <tbody>
                            <tr class="total">
                                <td>
                                    <strong class="text-color-dark text-3-5">Tổng tiền</strong>
                                </td>
                                <td class="text-end">
                                    <strong class="text-color-dark"><span class="amount text-color-dark text-5"><?=$sum;?> VNĐ</span></strong>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                    <a href="checkout.php" class="btn btn-dark btn-modern w-100 text-uppercase bg-color-hover-primary border-color-hover-primary border-radius-0 text-3 py-3">Thanh toán <i class="fas fa-arrow-right ms-2"></i></a>
                </div>
            </div>
        </div>
    </div>
    <!--<div class="row">
        <div class="col">
            <h4 class="font-weight-semibold text-4 mb-3">PEOPLE ALSO BOUGHT</h4>
            <hr class="mt-0">
            <div class="products row">
                <div class="col">
                    <div class="owl-carousel owl-theme nav-style-1 nav-outside nav-outside nav-dark mb-0" data-plugin-options="{'loop': false, 'autoplay': false, 'items': 4, 'nav': true, 'dots': false, 'margin': 20, 'autoplayHoverPause': true, 'autoHeight': true, 'stagePadding': '75', 'navVerticalOffset': '50px'}">
                        <div class="product mb-0">
                            <div class="product-thumb-info border-0 mb-3">
                                <div class="product-thumb-info-badges-wrapper">
                                    <span class="badge badge-ecommerce badge-success">NEW</span>
                                </div>
                                <div class="addtocart-btn-wrapper">
                                    <a href="shop-cart.html" class="text-decoration-none addtocart-btn" title="Add to Cart">
                                        <i class="icons icon-bag"></i>
                                    </a>
                                </div>
                                <a href="ajax/shop-product-quick-view.html" class="quick-view text-uppercase font-weight-semibold text-2">
                                    QUICK VIEW
                                </a>
                                <a href="shop-product-sidebar-left.html">
                                    <div class="product-thumb-info-image">
                                        <img alt="" class="img-fluid" src="img/products/product-grey-1.jpg">
                                    </div>
                                </a>
                            </div>
                            <div class="d-flex justify-content-between">
                                <div>
                                    <a href="#" class="d-block text-uppercase text-decoration-none text-color-default text-color-hover-primary line-height-1 text-0 mb-1">electronics</a>
                                    <h3 class="text-3-5 font-weight-medium font-alternative text-transform-none line-height-3 mb-0"><a href="shop-product-sidebar-right.html" class="text-color-dark text-color-hover-primary">Photo Camera</a></h3>
                                </div>
                                <a href="#" class="text-decoration-none text-color-default text-color-hover-dark text-4"><i class="far fa-heart"></i></a>
                            </div>
                            <div title="Rated 5 out of 5">
                                <input type="text" class="d-none" value="5" title="" data-plugin-star-rating data-plugin-options="{'displayOnly': true, 'color': 'default', 'size':'xs'}">
                            </div>
                            <p class="price text-5 mb-3">
                                <span class="sale text-color-dark font-weight-semi-bold">$69,00</span>
                                <span class="amount">$59,00</span>
                            </p>
                        </div>
                        <div class="product mb-0">
                            <div class="product-thumb-info border-0 mb-3">
                                <div class="product-thumb-info-badges-wrapper">
                                    <span class="badge badge-ecommerce badge-success">NEW</span>
                                    <span class="badge badge-ecommerce badge-danger">27% OFF</span>
                                </div>
                                <div class="addtocart-btn-wrapper">
                                    <a href="shop-cart.html" class="text-decoration-none addtocart-btn" title="Add to Cart">
                                        <i class="icons icon-bag"></i>
                                    </a>
                                </div>
                                <a href="ajax/shop-product-quick-view.html" class="quick-view text-uppercase font-weight-semibold text-2">
                                    QUICK VIEW
                                </a>
                                <a href="shop-product-sidebar-left.html">
                                    <div class="product-thumb-info-image product-thumb-info-image-effect">
                                        <img alt="" class="img-fluid" src="img/products/product-grey-7.jpg">
                                        <img alt="" class="img-fluid" src="img/products/product-grey-7-2.jpg">
                                    </div>
                                </a>
                            </div>
                            <div class="d-flex justify-content-between">
                                <div>
                                    <a href="#" class="d-block text-uppercase text-decoration-none text-color-default text-color-hover-primary line-height-1 text-0 mb-1">accessories</a>
                                    <h3 class="text-3-5 font-weight-medium font-alternative text-transform-none line-height-3 mb-0"><a href="shop-product-sidebar-right.html" class="text-color-dark text-color-hover-primary">Porto Headphone</a></h3>
                                </div>
                                <a href="#" class="text-decoration-none text-color-default text-color-hover-dark text-4"><i class="far fa-heart"></i></a>
                            </div>
                            <div title="Rated 5 out of 5">
                                <input type="text" class="d-none" value="5" title="" data-plugin-star-rating data-plugin-options="{'displayOnly': true, 'color': 'default', 'size':'xs'}">
                            </div>
                            <p class="price text-5 mb-3">
                                <span class="sale text-color-dark font-weight-semi-bold">$199,00</span>
                                <span class="amount">$99,00</span>
                            </p>
                        </div>
                        <div class="product mb-0">
                            <div class="product-thumb-info border-0 mb-3">
                                <div class="addtocart-btn-wrapper">
                                    <a href="shop-cart.html" class="text-decoration-none addtocart-btn" title="Add to Cart">
                                        <i class="icons icon-bag"></i>
                                    </a>
                                </div>
                                <a href="ajax/shop-product-quick-view.html" class="quick-view text-uppercase font-weight-semibold text-2">
                                    QUICK VIEW
                                </a>
                                <a href="shop-product-sidebar-left.html">
                                    <div class="product-thumb-info-image">
                                        <img alt="" class="img-fluid" src="img/products/product-grey-2.jpg">

                                    </div>
                                </a>
                            </div>
                            <div class="d-flex justify-content-between">
                                <div>
                                    <a href="#" class="d-block text-uppercase text-decoration-none text-color-default text-color-hover-primary line-height-1 text-0 mb-1">sports</a>
                                    <h3 class="text-3-5 font-weight-medium font-alternative text-transform-none line-height-3 mb-0"><a href="shop-product-sidebar-right.html" class="text-color-dark text-color-hover-primary">Golf Bag</a></h3>
                                </div>
                                <a href="#" class="text-decoration-none text-color-default text-color-hover-dark text-4"><i class="far fa-heart"></i></a>
                            </div>
                            <div title="Rated 5 out of 5">
                                <input type="text" class="d-none" value="5" title="" data-plugin-star-rating data-plugin-options="{'displayOnly': true, 'color': 'default', 'size':'xs'}">
                            </div>
                            <p class="price text-5 mb-3">
                                <span class="sale text-color-dark font-weight-semi-bold">$29,00</span>
                                <span class="amount">$19,00</span>
                            </p>
                        </div>
                        <div class="product mb-0">
                            <div class="product-thumb-info border-0 mb-3">
                                <div class="product-thumb-info-badges-wrapper">
                                    <span class="badge badge-ecommerce badge-danger">27% OFF</span>
                                </div>
                                <div class="addtocart-btn-wrapper">
                                    <a href="shop-cart.html" class="text-decoration-none addtocart-btn" title="Add to Cart">
                                        <i class="icons icon-bag"></i>
                                    </a>
                                </div>
                                <div class="countdown-offer-wrapper">
                                    <div class="text-color-light text-2" data-plugin-countdown data-plugin-options="{'date': '2022/01/01 12:00:00', 'numberClass': 'text-color-light', 'wrapperClass': 'text-color-light', 'insertHTMLbefore': '<span>OFFER ENDS IN </span>', 'textDay': 'DAYS', 'textHour': ':', 'textMin': ':', 'textSec': '', 'uppercase': true}"></div>
                                </div>
                                <a href="ajax/shop-product-quick-view.html" class="quick-view text-uppercase font-weight-semibold text-2">
                                    QUICK VIEW
                                </a>
                                <a href="shop-product-sidebar-left.html">
                                    <div class="product-thumb-info-image">
                                        <img alt="" class="img-fluid" src="img/products/product-grey-3.jpg">

                                    </div>
                                </a>
                            </div>
                            <div class="d-flex justify-content-between">
                                <div>
                                    <a href="#" class="d-block text-uppercase text-decoration-none text-color-default text-color-hover-primary line-height-1 text-0 mb-1">sports</a>
                                    <h3 class="text-3-5 font-weight-medium font-alternative text-transform-none line-height-3 mb-0"><a href="shop-product-sidebar-right.html" class="text-color-dark text-color-hover-primary">Workout</a></h3>
                                </div>
                                <a href="#" class="text-decoration-none text-color-default text-color-hover-dark text-4"><i class="far fa-heart"></i></a>
                            </div>
                            <div title="Rated 5 out of 5">
                                <input type="text" class="d-none" value="5" title="" data-plugin-star-rating data-plugin-options="{'displayOnly': true, 'color': 'default', 'size':'xs'}">
                            </div>
                            <p class="price text-5 mb-3">
                                <span class="sale text-color-dark font-weight-semi-bold">$40,00</span>
                                <span class="amount">$30,00</span>
                            </p>
                        </div>
                        <div class="product mb-0">
                            <div class="product-thumb-info border-0 mb-3">
                                <div class="addtocart-btn-wrapper">
                                    <a href="shop-cart.html" class="text-decoration-none addtocart-btn" title="Add to Cart">
                                        <i class="icons icon-bag"></i>
                                    </a>
                                </div>
                                <a href="ajax/shop-product-quick-view.html" class="quick-view text-uppercase font-weight-semibold text-2">
                                    QUICK VIEW
                                </a>
                                <a href="shop-product-sidebar-left.html">
                                    <div class="product-thumb-info-image">
                                        <img alt="" class="img-fluid" src="img/products/product-grey-4.jpg">
                                    </div>
                                </a>
                            </div>
                            <div class="d-flex justify-content-between">
                                <div>
                                    <a href="#" class="d-block text-uppercase text-decoration-none text-color-default text-color-hover-primary line-height-1 text-0 mb-1">accessories</a>
                                    <h3 class="text-3-5 font-weight-medium font-alternative text-transform-none line-height-3 mb-0"><a href="shop-product-sidebar-right.html" class="text-color-dark text-color-hover-primary">Luxury Bag</a></h3>
                                </div>
                                <a href="#" class="text-decoration-none text-color-default text-color-hover-dark text-4"><i class="far fa-heart"></i></a>
                            </div>
                            <div title="Rated 5 out of 5">
                                <input type="text" class="d-none" value="5" title="" data-plugin-star-rating data-plugin-options="{'displayOnly': true, 'color': 'default', 'size':'xs'}">
                            </div>
                            <p class="price text-5 mb-3">
                                <span class="sale text-color-dark font-weight-semi-bold">$99,00</span>
                                <span class="amount">$79,00</span>
                            </p>
                        </div>
                        <div class="product mb-0">
                            <div class="product-thumb-info border-0 mb-3">
                                <div class="addtocart-btn-wrapper">
                                    <a href="shop-cart.html" class="text-decoration-none addtocart-btn" title="Add to Cart">
                                        <i class="icons icon-bag"></i>
                                    </a>
                                </div>
                                <a href="ajax/shop-product-quick-view.html" class="quick-view text-uppercase font-weight-semibold text-2">
                                    QUICK VIEW
                                </a>
                                <a href="shop-product-sidebar-left.html">
                                    <div class="product-thumb-info-image">
                                        <img alt="" class="img-fluid" src="img/products/product-grey-5.jpg">
                                    </div>
                                </a>
                            </div>
                            <div class="d-flex justify-content-between">
                                <div>
                                    <a href="#" class="d-block text-uppercase text-decoration-none text-color-default text-color-hover-primary line-height-1 text-0 mb-1">accessories</a>
                                    <h3 class="text-3-5 font-weight-medium font-alternative text-transform-none line-height-3 mb-0"><a href="shop-product-sidebar-right.html" class="text-color-dark text-color-hover-primary">Styled Bag</a></h3>
                                </div>
                                <a href="#" class="text-decoration-none text-color-default text-color-hover-dark text-4"><i class="far fa-heart"></i></a>
                            </div>
                            <div title="Rated 5 out of 5">
                                <input type="text" class="d-none" value="5" title="" data-plugin-star-rating data-plugin-options="{'displayOnly': true, 'color': 'default', 'size':'xs'}">
                            </div>
                            <p class="price text-5 mb-3">
                                <span class="sale text-color-dark font-weight-semi-bold">$199,00</span>
                                <span class="amount">$119,00</span>
                            </p>
                        </div>
                        <div class="product mb-0">
                            <div class="product-thumb-info border-0 mb-3">
                                <div class="addtocart-btn-wrapper">
                                    <a href="shop-cart.html" class="text-decoration-none addtocart-btn" title="Add to Cart">
                                        <i class="icons icon-bag"></i>
                                    </a>
                                </div>
                                <a href="ajax/shop-product-quick-view.html" class="quick-view text-uppercase font-weight-semibold text-2">
                                    QUICK VIEW
                                </a>
                                <a href="shop-product-sidebar-left.html">
                                    <div class="product-thumb-info-image">
                                        <img alt="" class="img-fluid" src="img/products/product-grey-6.jpg">
                                    </div>
                                </a>
                            </div>
                            <div class="d-flex justify-content-between">
                                <div>
                                    <a href="#" class="d-block text-uppercase text-decoration-none text-color-default text-color-hover-primary line-height-1 text-0 mb-1">hat</a>
                                    <h3 class="text-3-5 font-weight-medium font-alternative text-transform-none line-height-3 mb-0"><a href="shop-product-sidebar-right.html" class="text-color-dark text-color-hover-primary">Blue Hat</a></h3>
                                </div>
                                <a href="#" class="text-decoration-none text-color-default text-color-hover-dark text-4"><i class="far fa-heart"></i></a>
                            </div>
                            <div title="Rated 5 out of 5">
                                <input type="text" class="d-none" value="5" title="" data-plugin-star-rating data-plugin-options="{'displayOnly': true, 'color': 'default', 'size':'xs'}">
                            </div>
                            <p class="price text-5 mb-3">
                                <span class="sale text-color-dark font-weight-semi-bold">$299,00</span>
                                <span class="amount">$289,00</span>
                            </p>
                        </div>
                        <div class="product mb-0">
                            <div class="product-thumb-info border-0 mb-3">
                                <div class="addtocart-btn-wrapper">
                                    <a href="shop-cart.html" class="text-decoration-none addtocart-btn" title="Add to Cart">
                                        <i class="icons icon-bag"></i>
                                    </a>
                                </div>
                                <a href="ajax/shop-product-quick-view.html" class="quick-view text-uppercase font-weight-semibold text-2">
                                    QUICK VIEW
                                </a>
                                <a href="shop-product-sidebar-left.html">
                                    <div class="product-thumb-info-image">
                                        <img alt="" class="img-fluid" src="img/products/product-grey-8.jpg">
                                    </div>
                                </a>
                            </div>
                            <div class="d-flex justify-content-between">
                                <div>
                                    <a href="#" class="d-block text-uppercase text-decoration-none text-color-default text-color-hover-primary line-height-1 text-0 mb-1">accessories</a>
                                    <h3 class="text-3-5 font-weight-medium font-alternative text-transform-none line-height-3 mb-0"><a href="shop-product-sidebar-right.html" class="text-color-dark text-color-hover-primary">Adventurer Bag</a></h3>
                                </div>
                                <a href="#" class="text-decoration-none text-color-default text-color-hover-dark text-4"><i class="far fa-heart"></i></a>
                            </div>
                            <div title="Rated 5 out of 5">
                                <input type="text" class="d-none" value="5" title="" data-plugin-star-rating data-plugin-options="{'displayOnly': true, 'color': 'default', 'size':'xs'}">
                            </div>
                            <p class="price text-5 mb-3">
                                <span class="sale text-color-dark font-weight-semi-bold">$99,00</span>
                                <span class="amount">$79,00</span>
                            </p>
                        </div>
                        <div class="product mb-0">
                            <div class="product-thumb-info border-0 mb-3">
                                <div class="addtocart-btn-wrapper">
                                    <a href="shop-cart.html" class="text-decoration-none addtocart-btn" title="Add to Cart">
                                        <i class="icons icon-bag"></i>
                                    </a>
                                </div>
                                <a href="ajax/shop-product-quick-view.html" class="quick-view text-uppercase font-weight-semibold text-2">
                                    QUICK VIEW
                                </a>
                                <a href="shop-product-sidebar-left.html">
                                    <div class="product-thumb-info-image">
                                        <img alt="" class="img-fluid" src="img/products/product-grey-9.jpg">
                                    </div>
                                </a>
                            </div>
                            <div class="d-flex justify-content-between">
                                <div>
                                    <a href="#" class="d-block text-uppercase text-decoration-none text-color-default text-color-hover-primary line-height-1 text-0 mb-1">sports</a>
                                    <h3 class="text-3-5 font-weight-medium font-alternative text-transform-none line-height-3 mb-0"><a href="shop-product-sidebar-right.html" class="text-color-dark text-color-hover-primary">Baseball Ball</a></h3>
                                </div>
                                <a href="#" class="text-decoration-none text-color-default text-color-hover-dark text-4"><i class="far fa-heart"></i></a>
                            </div>
                            <div title="Rated 5 out of 5">
                                <input type="text" class="d-none" value="5" title="" data-plugin-star-rating data-plugin-options="{'displayOnly': true, 'color': 'default', 'size':'xs'}">
                            </div>
                            <p class="price text-5 mb-3">
                                <span class="sale text-color-dark font-weight-semi-bold">$399,00</span>
                                <span class="amount">$299,00</span>
                            </p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>-->
</div>
</div>
<!--html & css section end-->

<?php
        include_once("include/footer.php");
?>
<?php
        include_once("include/bottom.meta.php");
?>
<!--js section start-->
<script>
    $('.btn-update').on('click',function(e){
        let index = $(e.currentTarget).attr('data-index');
        let pi_count = $('#cart' + index + ' input[name="pi_count"]').val();
        e.preventDefault();
        $.ajax({
            url: "cart_ok.php",
            type: "POST",
            data: {
                status: "Update",
                index: index,
                pi_count: pi_count,
            },
            success: function(data){
                data = JSON.parse(data);
                if(data.msg == 'ok') {
                    $.alert({
						title: "Thông báo",
						content: "Bạn đã sửa sản phẩm vào giỏ hàng thành công",
					});
                    setTimeout(() => {
						location.reload();
					},2000);
                }
            },error: function(data){
                console.log("Error" + data);
            }
        })
    })
    $('.btn-delete').on('click',function(e){
        let index = $(e.currentTarget).attr('data-index');
        e.preventDefault();
        $.confirm({
            title: "Thông báo",
            content: "Bạn có chắc chắn muốn xoá sản phẩm này",
            buttons: {
                Có : function(){
                    $.ajax({
                        url: "cart_ok.php",
                        type: "POST",
                        data: {
                            status: "Delete",
                            index: index,
                        },
                        success: function(data){
                            data = JSON.parse(data);
                            if(data.msg == 'ok') {
                                $.alert({
                                    title: "Thông báo",
                                    content: "Bạn đã xoá sản phẩm vào giỏ hàng thành công",
                                });
                                setTimeout(() => {
                                    location.reload();
                                },2000);
                            }
                        },error: function(data){
                            console.log("Error" + data);
                        }
                    })
                },Không: function(){

                }
            }
        })
        
    })
</script>                               
<!--js section end-->
<?php
        include_once("include/foot.php"); 
?>
<?php
    } else if (is_post_method()) {
        // code to be executed post method
    }
?>