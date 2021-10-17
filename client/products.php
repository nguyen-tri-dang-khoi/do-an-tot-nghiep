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
		<div class="row">
			<div class="col-lg-3 order-2 order-lg-1">
				<aside class="sidebar">
					<form action="page-search-results.html" method="get">
						<div class="input-group mb-3 pb-1">
							<input class="form-control text-1" placeholder="Search..." name="s" id="s" type="text">
							<button type="submit" class="btn btn-dark text-1 p-2"><i class="fas fa-search m-2"></i></button>
						</div>
					</form>
					<h5 class="font-weight-semi-bold pt-3">Categories</h5>
					<ul class="nav nav-list flex-column">
						<li class="nav-item"><a class="nav-link" href="#">Arts & Crafts</a></li>
						<li class="nav-item"><a class="nav-link" href="#">Automotive</a></li>
						<li class="nav-item"><a class="nav-link" href="#">Baby</a></li>
						<li class="nav-item"><a class="nav-link" href="#">Books</a></li>
						<li class="nav-item"><a class="nav-link" href="#">Eletronics</a></li>
						<li class="nav-item"><a class="nav-link" href="#">Women's Fashion</a></li>
						<li class="nav-item"><a class="nav-link" href="#">Men's Fashion</a></li>
						<li class="nav-item"><a class="nav-link" href="#">Health & Household</a></li>
						<li class="nav-item"><a class="nav-link" href="#">Home & Kitchen</a></li>
						<li class="nav-item"><a class="nav-link" href="#">Military Accessories</a></li>
						<li class="nav-item"><a class="nav-link" href="#">Movies & Television</a></li>
						<li class="nav-item"><a class="nav-link" href="#">Sports & Outdoors</a></li>
						<li class="nav-item"><a class="nav-link" href="#">Tools & Home Improvement</a></li>
						<li class="nav-item"><a class="nav-link" href="#">Toys & Games</a></li>
					</ul>
					<h5 class="font-weight-semi-bold pt-5">Tags</h5>
					<div class="mb-3 pb-1">
						<a href="#"><span class="badge badge-dark badge-sm rounded-pill text-uppercase px-2 py-1 me-1">Nike</span></a>
						<a href="#"><span class="badge badge-dark badge-sm rounded-pill text-uppercase px-2 py-1 me-1">Travel</span></a>
						<a href="#"><span class="badge badge-dark badge-sm rounded-pill text-uppercase px-2 py-1 me-1">Sport</span></a>
						<a href="#"><span class="badge badge-dark badge-sm rounded-pill text-uppercase px-2 py-1 me-1">TV</span></a>
						<a href="#"><span class="badge badge-dark badge-sm rounded-pill text-uppercase px-2 py-1 me-1">Books</span></a>
						<a href="#"><span class="badge badge-dark badge-sm rounded-pill text-uppercase px-2 py-1 me-1">Tech</span></a>
						<a href="#"><span class="badge badge-dark badge-sm rounded-pill text-uppercase px-2 py-1 me-1">Adidas</span></a>
						<a href="#"><span class="badge badge-dark badge-sm rounded-pill text-uppercase px-2 py-1 me-1">Promo</span></a>
						<a href="#"><span class="badge badge-dark badge-sm rounded-pill text-uppercase px-2 py-1 me-1">Reading</span></a>
						<a href="#"><span class="badge badge-dark badge-sm rounded-pill text-uppercase px-2 py-1 me-1">Social</span></a>
						<a href="#"><span class="badge badge-dark badge-sm rounded-pill text-uppercase px-2 py-1 me-1">Books</span></a>
						<a href="#"><span class="badge badge-dark badge-sm rounded-pill text-uppercase px-2 py-1 me-1">Tech</span></a>
						<a href="#"><span class="badge badge-dark badge-sm rounded-pill text-uppercase px-2 py-1 me-1">New</span></a>
					</div>
					<div class="row mb-5">
						<div class="col">
							<h5 class="font-weight-semi-bold pt-5">Top Rated Products</h5>
							<div class="product row row-gutter-sm align-items-center mb-4">
								<div class="col-5 col-lg-5">
									<div class="product-thumb-info border-0">
										<a href="shop-product-sidebar-left.html">
											<div class="product-thumb-info-image">
												<img alt="" class="img-fluid" src="img/products/product-grey-6.jpg">
											</div>
										</a>
									</div>
								</div>
								<div class="col-7 col-lg-7 ms-md-0 ms-lg-0 ps-lg-1 pt-1">
									<a href="#" class="d-block text-uppercase text-decoration-none text-color-default text-color-hover-primary line-height-1 text-0 mb-2">hat</a>
									<h3 class="text-3-5 font-weight-medium font-alternative text-transform-none line-height-3 mb-0"><a href="shop-product-sidebar-right.html" class="text-color-dark text-color-hover-primary text-decoration-none">Blue Hat</a></h3>
									<div title="Rated 5 out of 5">
										<input type="text" class="d-none" value="5" title="" data-plugin-star-rating data-plugin-options="{'displayOnly': true, 'color': 'dark', 'size':'xs'}">
									</div>
									<p class="price text-4 mb-0">
										<span class="sale text-color-dark font-weight-semi-bold">$299,00</span>
										<span class="amount">$289,00</span>
									</p>
								</div>
							</div>
							<div class="product row row-gutter-sm align-items-center mb-4">
								<div class="col-5 col-lg-5">
									<div class="product-thumb-info border-0">
										<a href="shop-product-sidebar-left.html">
											<div class="product-thumb-info-image">
												<img alt="" class="img-fluid" src="img/products/product-grey-8.jpg">
											</div>
										</a>
									</div>
								</div>
								<div class="col-7 col-lg-7 ms-md-0 ms-lg-0 ps-lg-1 pt-1">
									<a href="#" class="d-block text-uppercase text-decoration-none text-color-default text-color-hover-primary line-height-1 text-0 mb-2">accessories</a>
									<h3 class="text-3-5 font-weight-medium font-alternative text-transform-none line-height-3 mb-0"><a href="shop-product-sidebar-right.html" class="text-color-dark text-color-hover-primary text-decoration-none">Adventurer Bag</a></h3>
									<div title="Rated 5 out of 5">
										<input type="text" class="d-none" value="5" title="" data-plugin-star-rating data-plugin-options="{'displayOnly': true, 'color': 'dark', 'size':'xs'}">
									</div>
									<p class="price text-4 mb-0">
										<span class="sale text-color-dark font-weight-semi-bold">$99,00</span>
										<span class="amount">$79,00</span>
									</p>
								</div>
							</div>
							<div class="product row row-gutter-sm align-items-center mb-4">
								<div class="col-5 col-lg-5">
									<div class="product-thumb-info border-0">
										<a href="shop-product-sidebar-left.html">
											<div class="product-thumb-info-image">
												<img alt="" class="img-fluid" src="img/products/product-grey-9.jpg">
											</div>
										</a>
									</div>
								</div>
								<div class="col-7 col-lg-7 ms-md-0 ms-lg-0 ps-lg-1 pt-1">
									<a href="#" class="d-block text-uppercase text-decoration-none text-color-default text-color-hover-primary line-height-1 text-0 mb-2">sports</a>
									<h3 class="text-3-5 font-weight-medium font-alternative text-transform-none line-height-3 mb-0"><a href="shop-product-sidebar-right.html" class="text-color-dark text-color-hover-primary text-decoration-none">Baseball Ball</a></h3>
									<div title="Rated 5 out of 5">
										<input type="text" class="d-none" value="5" title="" data-plugin-star-rating data-plugin-options="{'displayOnly': true, 'color': 'dark', 'size':'xs'}">
									</div>
									<p class="price text-4 mb-0">
										<span class="sale text-color-dark font-weight-semi-bold">$399,00</span>
										<span class="amount">$299,00</span>
									</p>
								</div>
							</div>
						</div>
					</div>
				</aside>
			</div>
			<div class="col-lg-9 order-1 order-lg-2">

				<div class="masonry-loader masonry-loader-showing">
					<div class="row products product-thumb-info-list" data-plugin-masonry data-plugin-options="{'layoutMode': 'fitRows'}">

						<div class="col-sm-6 col-lg-4">
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
						</div>
						<div class="col-sm-6 col-lg-4">
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
						<div class="col-sm-6 col-lg-4">
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
						<div class="col-sm-6 col-lg-4">
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
						<div class="col-sm-6 col-lg-4">
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
						<div class="col-sm-6 col-lg-4">
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
						<div class="col-sm-6 col-lg-4">
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
						<div class="col-sm-6 col-lg-4">
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
						<div class="col-sm-6 col-lg-4">
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