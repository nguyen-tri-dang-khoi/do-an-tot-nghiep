<?php
    include_once("../lib/database.php");
    if(is_get_method()) {
        include_once("include/head.meta.php");
        include_once("include/menu.php");
        // code to be executed get method
		$id = isset($_REQUEST['id']) ? $_REQUEST['id'] : "";
		$sql = "select * from product_info where id = '$id' limit 1";
		$row = fetch_row($sql);
?>
<!--html & css section start-->
<div role="main" class="main shop py-4">
	<div class="container">
		<div class="row">
			<div class="col-lg-12">
				<div class="row">
					<div class="col-lg-6">
						<div class="thumb-gallery-wrapper">
							<div class="thumb-gallery-detail owl-carousel owl-theme manual nav-inside nav-style-1 nav-dark mb-3">
								<div>
									<img alt="" class="img-fluid" src="<?='../admin/' . $row['img_name']?>" data-zoom-image="<?='../admin/' . $row['img_name']?>">
								</div>
								<?php
									$sql_get_img_child = "select * from product_image pi where pi.product_info_id = '$id' order by img_order asc";
									$imgs = db_query($sql_get_img_child);
									foreach($imgs as $img) {
								?>
										<div class="cur-pointer">
											<img alt="" class="img-fluid" src="<?='../admin/' . $img['img_id'];?>">
										</div>
								<?php
									}
								?>
							</div>
							<div class="thumb-gallery-thumbs owl-carousel owl-theme manual thumb-gallery-thumbs">
								<div>
									<img alt="" class="img-fluid" src="<?='../admin/' . $row['img_name']?>" data-zoom-image="<?='../admin/' . $row['img_name']?>">
								</div>
								<?php
									$sql_get_img_child = "select * from product_image pi where pi.product_info_id = '$id' order by img_order asc";
									$imgs = db_query($sql_get_img_child);
									foreach($imgs as $img) {
								?>
										<div class="cur-pointer">
											<img alt="" class="img-fluid" src="<?='../admin/' . $img['img_id'];?>">
										</div>
								<?php
									}
								?>
							</div>
						</div>
					</div>
					<div class="col-lg-6">
						<div class="summary entry-summary position-relative">
							<div class="position-absolute top-0 right-0">
								<div class="products-navigation d-flex">
									<a href="#" class="prev text-decoration-none text-color-dark text-color-hover-primary border-color-hover-primary" data-bs-toggle="tooltip" data-bs-animation="false" data-bs-original-title="Red Ladies Handbag"><i class="fas fa-chevron-left"></i></a>
									<a href="#" class="next text-decoration-none text-color-dark text-color-hover-primary border-color-hover-primary" data-bs-toggle="tooltip" data-bs-animation="false" data-bs-original-title="Green Ladies Handbag"><i class="fas fa-chevron-right"></i></a>
								</div>
							</div>
							<h1 class="mb-0 font-weight-bold text-7"><?=$row['name'];?></h1>
							<div class="pb-0 clearfix d-flex align-items-center">
								<div title="Rated 3 out of 5" class="float-start">
									<input type="text" class="opacity-0" value="3" title="" data-plugin-star-rating data-plugin-options="{'displayOnly': true, 'color': 'primary', 'size':'xs'}">
								</div>
								<div class="review-num">
									<a href="#description" class="text-decoration-none text-color-default text-color-hover-primary" data-hash data-hash-offset="75" data-hash-trigger-click=".nav-link-reviews" data-hash-trigger-click-delay="1000">
										<span class="count text-color-inherit" itemprop="ratingCount">(2</span> reviews)
									</a>
								</div>
							</div>
							<div class="divider divider-small">
								<hr class="bg-color-grey-scale-4">
							</div>
							<p class="price mb-3">
								<span class="sale text-color-dark"><?=$row['price'];?></span>
								<!--<span class="amount">$22,00</span>-->
							</p>
							<p class="text-3-5 mb-3"><?=$row['description'];?></p>

							<!--<ul class="list list-unstyled text-2">
								<li class="mb-0">AVAILABILITY: <strong class="text-color-dark">AVAILABLE</strong></li>
								<li class="mb-0">SKU: <strong class="text-color-dark">1234567890</strong></li>
							</ul>-->

							<form enctype="multipart/form-data" method="post" class="cart" action="shop-cart.html">
								<!--<table class="table table-borderless" style="max-width: 300px;">
									<tbody>
										<tr>
											<td class="align-middle text-2 px-0 py-2">SIZE:</td>
											<td class="px-0 py-2">
												<div class="custom-select-1">
													<select name="size" class="form-control form-select text-1 h-auto py-2">
														<option value="">PLEASE CHOOSE</option>
														<option value="blue">Small</option>
														<option value="red">Normal</option>
														<option value="green">Big</option>
													</select>
												</div>
											</td>
										</tr>
										<tr>
											<td class="align-middle text-2 px-0 py-2">COLOR:</td>
											<td class="px-0 py-2">
												<div class="custom-select-1">
													<select name="color" class="form-control form-select text-1 h-auto py-2">
														<option value="">PLEASE CHOOSE</option>
														<option value="blue">Blue</option>
														<option value="red">Red</option>
														<option value="green">Green</option>
													</select>
												</div>
											</td>
										</tr>
									</tbody>
								</table>-->
								<hr>
								<div class="quantity quantity-lg">
									<input type="button" class="minus text-color-hover-light bg-color-hover-primary border-color-hover-primary" value="-">
									<input type="text" class="input-text qty text" title="Qty" value="1" name="quantity" min="1" step="1">
									<input type="button" class="plus text-color-hover-light bg-color-hover-primary border-color-hover-primary" value="+">
								</div>
								<button type="submit" class="btn btn-dark btn-modern text-uppercase bg-color-hover-primary border-color-hover-primary">Add to cart</button>
								<hr>
							</form>
							<!--<div class="d-flex align-items-center">
								<ul class="social-icons social-icons-medium social-icons-clean-with-border social-icons-clean-with-border-border-grey social-icons-clean-with-border-icon-dark me-3 mb-0">
									<li class="social-icons-facebook">
										<a href="http://www.facebook.com/sharer.php?u=https://www.okler.net" target="_blank" data-bs-toggle="tooltip" data-bs-animation="false" data-bs-placement="top" title="Share On Facebook">
											<i class="fab fa-facebook-f"></i>
										</a>
									</li>
									<li class="social-icons-googleplus">
										<a href="https://plus.google.com/share?url=https://www.okler.net" target="_blank" data-bs-toggle="tooltip" data-bs-animation="false" data-bs-placement="top" title="Share On Google+">
											<i class="fab fa-google-plus-g"></i>
										</a>
									</li>
									<li class="social-icons-twitter">
										<a href="https://twitter.com/share?url=https://www.okler.net&amp;text=Simple%20Share%20Buttons&amp;hashtags=simplesharebuttons" target="_blank" data-bs-toggle="tooltip" data-bs-animation="false" data-bs-placement="top" title="Share On Twitter">
											<i class="fab fa-twitter"></i>
										</a>
									</li>
									<li class="social-icons-email">
										<a href="mailto:?Subject=Share This Page&amp;Body=I%20saw%20this%20and%20thought%20of%20you!%20 https://www.okler.net" data-bs-toggle="tooltip" data-bs-animation="false" data-bs-placement="top" title="Share By Email">
											<i class="far fa-envelope"></i>
										</a>
									</li>
								</ul>
								<a href="#" class="d-flex align-items-center text-decoration-none text-color-dark text-color-hover-primary font-weight-semibold text-2">
									<i class="far fa-heart me-1"></i> SAVE TO WISHLIST
								</a>
							</div>-->
						</div>
					</div>
				</div>
				<div class="row">
					<div class="col">
						<div id="description" class="tabs tabs-simple tabs-simple-full-width-line tabs-product tabs-dark mb-2">
							<ul class="nav nav-tabs justify-content-start">
								<li class="nav-item"><a class="nav-link active font-weight-bold text-3 text-uppercase py-2 px-3" href="#productDescription" data-bs-toggle="tab">Mô tả sản phẩm</a></li>
								<!--<li class="nav-item"><a class="nav-link font-weight-bold text-3 text-uppercase py-2 px-3" href="#productInfo" data-bs-toggle="tab">Additional Information</a></li>-->
								<li class="nav-item"><a class="nav-link nav-link-reviews font-weight-bold text-3 text-uppercase py-2 px-3" href="#productReviews" data-bs-toggle="tab">Bình luận (2)</a></li>
							</ul>
							<div class="tab-content p-0">
								<div class="tab-pane px-0 py-3 active" id="productDescription">
									<p><?=$row['description'];?></p>
								</div>
								<div class="tab-pane px-0 py-3" id="productInfo">
									<table class="table table-striped m-0">
										<tbody>
											<tr>
												<th class="border-top-0">
													Size:
												</th>
												<td class="border-top-0">
													Unique
												</td>
											</tr>
											<tr>
												<th>
													Colors
												</th>
												<td>
													Red, Blue
												</td>
											</tr>
											<tr>
												<th>
													Material
												</th>
												<td>
													100% Leather
												</td>
											</tr>
										</tbody>
									</table>
								</div>
								<div class="tab-pane px-0 py-3" id="productReviews">
									<ul class="comments">
										<li>
											<div class="comment">
												<div class="img-thumbnail border-0 p-0 d-none d-md-block">
													<img class="avatar rounded-circle" alt="" src="img/avatars/avatar-2.jpg">
												</div>
												<div class="comment-block">
													<div class="comment-arrow"></div>
													<span class="comment-by">
														<strong>Jack Doe</strong>
														<span class="float-end">
															<div class="pb-0 clearfix">
																<div title="Rated 3 out of 5" class="float-start">
																	<input type="text" class="opacity-0" value="3" title="" data-plugin-star-rating data-plugin-options="{'displayOnly': true, 'color': 'primary', 'size':'xs'}">
																</div>

																<div class="review-num">
																	<span class="count" itemprop="ratingCount">2</span> reviews
																</div>
															</div>
														</span>
													</span>
													<p>Lorem ipsum dolor sit amet, consectetur adipiscing elit. Nam viverra euismod odio, gravida pellentesque urna varius vitae, gravida pellentesque urna varius vitae.</p>
												</div>
											</div>
										</li>
										<li>
											<div class="comment">
												<div class="img-thumbnail border-0 p-0 d-none d-md-block">
													<img class="avatar rounded-circle" alt="" src="img/avatars/avatar.jpg">
												</div>
												<div class="comment-block">
													<div class="comment-arrow"></div>
													<span class="comment-by">
														<strong>John Doe</strong>
														<span class="float-end">
															<div class="pb-0 clearfix">
																<div title="Rated 3 out of 5" class="float-start">
																	<input type="text" class="opacity-0" value="3" title="" data-plugin-star-rating data-plugin-options="{'displayOnly': true, 'color': 'primary', 'size':'xs'}">
																</div>

																<div class="review-num">
																	<span class="count" itemprop="ratingCount">2</span> reviews
																</div>
															</div>
														</span>
													</span>
													<p>Lorem ipsum dolor sit amet, consectetur adipiscing elit. Nam viverra odio, gravida urna varius vitae, gravida pellentesque urna varius vitae.</p>
												</div>
											</div>
										</li>
									</ul>
									<hr class="solid my-5">
									<h4>Add a review</h4>
									<div class="row">
										<div class="col">

											<form action="" id="submitReview" method="post">
												<div class="row">
													<div class="form-group col pb-2">
														<label class="form-label required font-weight-bold text-dark">Rating</label>
														<input type="text" class="rating-loading" value="0" title="" data-plugin-star-rating data-plugin-options="{'color': 'primary', 'size':'sm'}">
													</div>
												</div>
												<div class="row">
													<div class="form-group col-lg-6">
														<label class="form-label required font-weight-bold text-dark">Name</label>
														<input type="text" value="" data-msg-required="Please enter your name." maxlength="100" class="form-control" name="name" required>
													</div>
													<div class="form-group col-lg-6">
														<label class="form-label required font-weight-bold text-dark">Email Address</label>
														<input type="email" value="" data-msg-required="Please enter your email address." data-msg-email="Please enter a valid email address." maxlength="100" class="form-control" name="email" required>
													</div>
												</div>
												<div class="row">
													<div class="form-group col">
														<label class="form-label required font-weight-bold text-dark">Review</label>
														<textarea maxlength="5000" data-msg-required="Please enter your review." rows="8" class="form-control" name="review" id="review" required></textarea>
													</div>
												</div>
												<div class="row">
													<div class="form-group col mb-0">
														<input type="submit" value="Post Review" class="btn btn-primary btn-modern" data-loading-text="Loading...">
													</div>
												</div>
											</form>
										</div>

									</div>
								</div>
							</div>
						</div>
					</div>
				</div>
				<hr class="solid my-5">
				<h4 class="mb-3">Các sản phẩm liên quan</h4>
				<div class="products row">
					<div class="col">
						<div class="owl-carousel owl-theme show-nav-title nav-dark mb-0" data-plugin-options="{'loop': false, 'autoplay': false,'items': 4, 'nav': true, 'dots': false, 'margin': 20, 'autoplayHoverPause': true, 'autoHeight': true}">
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
											<!--<img alt="" class="img-fluid" src="img/products/product-grey-1.jpg">-->
											<img alt="" class="img-fluid" src="<?="../admin/" . $row['img_name'];?>">
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
											<img alt="" class="img-fluid" src="<?="../admin/" . $row['img_name'];?>">
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
											<img alt="" class="img-fluid" src="<?="../admin/" . $row['img_name'];?>">
											<!--<img alt="" class="img-fluid" src="img/products/product-grey-2.jpg">-->

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
									<a href="ajax/shop-product-quick-view.html" class="quick-view text-uppercase font-weight-semibold text-2">
										QUICK VIEW
									</a>
									<a href="shop-product-sidebar-left.html">
										<div class="product-thumb-info-image">
											<img alt="" class="img-fluid" src="<?="../admin/" . $row['img_name'];?>">
											<!--<img alt="" class="img-fluid" src="img/products/product-grey-3.jpg">-->
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
<script src="js/jquery.elevatezoom.min.js"></script>
<script src="js/examples.gallery.js"></script>

<!--js section end-->
<?php
        include_once("include/foot.php"); 
?>
<?php
    } else if (is_post_method()) {
        
        // code to be executed post method
    }
?>