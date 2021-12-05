<div class="body">
<header id="header" class="header-effect-shrink" data-plugin-options="{'stickyEnabled': true, 'stickyEffect': 'shrink', 'stickyEnableOnBoxed': false, 'stickyEnableOnMobile': false, 'stickyStartAt': 70, 'stickyChangeLogo': false, 'stickyHeaderContainerHeight': 70}">
    <div class="header-body border-top-1 box-shadow-lighted" style="box-shadow: 2px 3px 4px #21252924;">
        <div class="header-container header-container-md container" style="height: 75px !important;">
            <div class="header-row">
                <div class="header-column">
                    <div class="header-row">
                        <div class="header-logo">
                            <a href="index.php"><img alt="Porto" width="100" height="48" data-sticky-width="82" data-sticky-height="40" data-sticky-top="0" src="img/image.png"></a>
                        </div>
                        <div class="header-nav header-nav-line header-nav-bottom-line header-nav-bottom-line-no-transform header-nav-bottom-line-active-text-dark header-nav-bottom-line-effect-1 order-2 order-lg-1">
                            <div class="header-nav-main header-nav-main-square header-nav-main-dropdown-no-borders header-nav-main-effect-2 header-nav-main-sub-effect-1">
                                <nav class="collapse">
                                    <ul class="nav nav-pills" id="mainNav">
                                        <!--<li class="dropdown">
                                            <a class="dropdown-item dropdown-toggle" href="#">
                                                Danh mục
                                            </a>
                                            <ul class="dropdown-menu">
                                                <li class="dropdown-submenu">
                                                    <a class="dropdown-item" href="#">Single Product</a>
                                                    <ul class="dropdown-menu">
                                                        <li><a class="dropdown-item" href="shop-product-full-width.html">Full Width</a></li>
                                                        <li><a class="dropdown-item" href="shop-product-sidebar-left.html">Left Sidebar</a></li>
                                                        <li><a class="dropdown-item" href="shop-product-sidebar-right.html">Right Sidebar</a></li>
                                                        <li><a class="dropdown-item" href="shop-product-sidebar-left-and-right.html">Left and Right Sidebar</a></li>
                                                    </ul>
                                                </li>
                                                <li><a class="dropdown-item" href="shop-4-columns.html">4 Columns</a></li>
                                                <li class="dropdown-submenu">
                                                    <a class="dropdown-item" href="#">3 Columns</a>
                                                    <ul class="dropdown-menu">
                                                        <li><a class="dropdown-item" href="shop-3-columns-full-width.html">Full Width</a></li>
                                                        <li><a class="dropdown-item" href="shop-3-columns-sidebar-left.html">Left Sidebar</a></li>
                                                        <li><a class="dropdown-item" href="shop-3-columns-sidebar-right.html">Right Sidebar </a></li>
                                                    </ul>
                                                </li>
                                                <li class="dropdown-submenu">
                                                    <a class="dropdown-item" href="#">2 Columns</a>
                                                    <ul class="dropdown-menu">
                                                        <li><a class="dropdown-item" href="shop-2-columns-full-width.html">Full Width</a></li>
                                                        <li><a class="dropdown-item" href="shop-2-columns-sidebar-left.html">Left Sidebar</a></li>
                                                        <li><a class="dropdown-item" href="shop-2-columns-sidebar-right.html">Right Sidebar </a></li>
                                                        <li><a class="dropdown-item" href="shop-2-columns-sidebar-left-and-right.html">Left and Right Sidebar</a></li>
                                                    </ul>
                                                </li>
                                                <li class="dropdown-submenu">
                                                    <a class="dropdown-item" href="#">1 Column</a>
                                                    <ul class="dropdown-menu">
                                                        <li><a class="dropdown-item" href="shop-1-column-full-width.html">Full Width</a></li>
                                                        <li><a class="dropdown-item" href="shop-1-column-sidebar-left.html">Left Sidebar</a></li>
                                                        <li><a class="dropdown-item" href="shop-1-column-sidebar-right.html">Right Sidebar </a></li>
                                                        <li><a class="dropdown-item" href="shop-1-column-sidebar-left-and-right.html">Left and Right Sidebar</a></li>
                                                    </ul>
                                                </li>
                                                <li><a class="dropdown-item" href="shop-cart.html">Cart</a></li>
                                                <li><a class="dropdown-item" href="shop-login.html">Login</a></li>
                                                <li><a class="dropdown-item" href="shop-checkout.html">Checkout</a></li>
                                                <li><a class="dropdown-item" href="shop-order-complete.html">Order Complete</a></li>
                                            </ul>
                                        </li>-->
                                        <li class="dropdown">
                                            <a class="dropdown-item dropdown-toggle" href="#">
                                                Danh mục
                                            </a>
                                            <ul class="dropdown-menu">
                                                <?php echo show_menu_2();?>
                                            </ul>
                                        </li>
                                        
                                    </ul>
                                </nav>
                            </div>
                            <!--<button class="btn header-btn-collapse-nav" data-bs-toggle="collapse" data-bs-target=".header-nav-main nav">
                                <i class="fas fa-bars"></i>
                            </button>-->
                        </div>
                    </div>
                </div>
                <!--search-->
                <?php
                    $keyword2 = isset($_REQUEST['keyword2']) ? $_REQUEST['keyword2'] : null;
                ?>
                <div class="header-column justify-content-end">
                    <div class="header-row">
                        <form class="w-100" role="search" action="products.php" method="get">
                            <div class="simple-search input-group">
                                <input class="form-control text-1" id="headerSearch" name="keyword2" type="search" value="<?=$keyword2;?>" placeholder="Nhập tên sản phẩm tìm kiếm...">
                                <button class="btn" type="submit">
                                    <i class="fas fa-search header-nav-top-icon text-color-dark"></i>
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
                <div class="header-column ">
                    <div class="header-row">
                        
                        <div class="header-nav-features header-nav-features-no-border header-nav-features-lg-show-border order-1 order-lg-2">
                        <div style="justify-content:space-between;" class="header-nav-feature header-nav-features-cart d-inline-flex ms-2">
                            <?php
                                if(!isset($_SESSION['isUserLoggedIn']) || $_SESSION['isUserLoggedIn'] == false) {
                            ?>
                                    <a href="login.php">Đăng nhập</a>
                                    <a class="ml-10 mr-10" href="register.php">Đăng ký</a>
                            <?php
                                } else {
                            ?>
                                    <p style="margin-bottom:0px;margin-right:7px;">Xin chào, <?=$_SESSION['username']?>
                                        
                                    </p>
                                    <div class="sample-icon ml-5">
                                        <a href="logout.php"><i class="icon-logout icons"></i><span class="name"></span></a>
                                    </div>
                            <?php
                                }
                            ?>
                        </div>
                            <div class="header-nav-feature header-nav-features-cart d-inline-flex ms-2">
                                <a href="#" class="header-nav-features-toggle">
                                    <img src="img/icons/icon-cart.svg" width="14" alt="" class="header-nav-top-icon-img">
                                    <span class="cart-info">
                                        <?php
                                            log_a($_SESSION);
                                            if(!isset($_SESSION['cart'])) {
                                                //$ccc = $_SESSION['cart'];
                                        ?>
                                            <span class="cart-qty"></span>
                                        <?php } ?>
                                    </span>
                                </a>
                                <div class="header-nav-features-dropdown" id="headerTopCartDropdown">
                                    <ol class="mini-products-list">
                                        <?php
                                           // print_r($_SESSION['cart']);
                                            $_total = 0;
                                            if(!isset($_SESSION['cart'])) {
                                                $_SESSION['cart'] = [];
                                            }
                                            foreach($_SESSION['cart'] as $cart) {
                                                $_total += $cart['pi_price'] * $cart['pi_count'];
                                        ?>
                                        <li class="item">
                                            <a href="#" title="Camera X1000" class="product-image"><img src="<?=$cart['pi_image'];?>" alt="Camera X1000"></a>
                                            <div class="product-details">
                                                <p class="product-name">
                                                    <a href="product_detail?id=<?=$cart['pi_id']?>"><?=$cart['pi_name'];?></a>
                                                </p>
                                                <p class="qty-price">
                                                    <?=$cart['pi_count'];?> X <span class="price"><?=$cart['pi_price'];?> VNĐ</span>
                                                </p>
                                                <a href="#" title="Remove This Item" class="btn-remove"><i class="fas fa-times"></i></a>
                                            </div>
                                        </li>
                                        <?php
                                            }
                                        ?>
                                    </ol>
                                    <div class="totals">
                                        <span class="label">Tổng tiền:</span>
                                        <span class="price-total"><span class="price"><?=$_total;?></span></span>
                                    </div>
                                    <div class="actions">
                                        <a class="btn btn-dark" href="cart.php">Xem giỏ hàng</a>
                                        <a class="btn btn-primary" href="checkout.php">Thanh toán</a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</header>