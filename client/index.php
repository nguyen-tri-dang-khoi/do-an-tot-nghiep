<?php
    include_once("../lib/database.php");
    if(is_get_method()) {
        include_once("include/head.meta.php");
		include_once("include/menu.php");
        // code to be executed get method
?>
<!--html & css section start-->
<div role="main" class="main shop pt-4">
	<div class="container">
		<div class="masonry-loader masonry-loader-showing">
			<div class="row products product-thumb-info-list" data-plugin-masonry data-plugin-options="{'layoutMode': 'fitRows'}">
				<div class="col-12 col-sm-6 col-lg-3">
					<div class="product mb-0">
						<div class="product-thumb-info border-0 mb-3">
							<div class="product-thumb-info-badges-wrapper">
								<span class="badge badge-ecommerce badge-success">fsfefwe</span>
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
				</div>
				<div class="col-12 col-sm-6 col-lg-3">
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
				</div>
				<div class="col-12 col-sm-6 col-lg-3">
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
				</div>
				<div class="col-12 col-sm-6 col-lg-3">
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
								<div class="text-color-light negative-ls-05 text-2" data-plugin-countdown data-plugin-options="{'date': '2022/01/01 12:00:00', 'numberClass': 'text-color-light', 'wrapperClass': 'text-color-light', 'insertHTMLbefore': '<span>OFFER ENDS IN </span>', 'textDay': 'DAYS', 'textHour': ':', 'textMin': ':', 'textSec': '', 'uppercase': true}"></div>
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
				</div>
				<div class="col-12 col-sm-6 col-lg-3">
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
				</div>
				<div class="col-12 col-sm-6 col-lg-3">
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
				</div>
				<div class="col-12 col-sm-6 col-lg-3">
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
				</div>
				<div class="col-12 col-sm-6 col-lg-3">
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
				</div>
			</div>
			<div class="row mt-4">
				<div class="col">
					<ul class="pagination float-end">
						<li class="page-item"><a class="page-link" href="#"><i class="fas fa-angle-left"></i></a></li>
						<li class="page-item active"><a class="page-link" href="#">1</a></li>
						<li class="page-item"><a class="page-link" href="#">2</a></li>
						<li class="page-item"><a class="page-link" href="#">3</a></li>
						<li class="page-item"><a class="page-link" href="#"><i class="fas fa-angle-right"></i></a></li>
					</ul>
				</div>
			</div>
		</div>
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

<!--js section end-->
<?php
        include_once("include/foot.php"); 
?>
<?php
    } else if (is_post_method()) {
        
        // code to be executed post method
    }
?>