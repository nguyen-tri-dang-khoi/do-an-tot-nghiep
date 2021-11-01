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
<?php
	$sql_comment_count = "select count(*) as 'cnt' from product_comment where product_info_id='$id'";
	$cnt = fetch_row($sql_comment_count)['cnt'];
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
									<img id="pi_image" alt="" class="img-fluid" src="<?='../admin/' . $row['img_name']?>" data-zoom-image="<?='../admin/' . $row['img_name']?>">
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
							<!--<div class="position-absolute top-0 right-0">
								<div class="products-navigation d-flex">
									<a href="#" class="prev text-decoration-none text-color-dark text-color-hover-primary border-color-hover-primary" data-bs-toggle="tooltip" data-bs-animation="false" data-bs-original-title="Red Ladies Handbag"><i class="fas fa-chevron-left"></i></a>
									<a href="#" class="next text-decoration-none text-color-dark text-color-hover-primary border-color-hover-primary" data-bs-toggle="tooltip" data-bs-animation="false" data-bs-original-title="Green Ladies Handbag"><i class="fas fa-chevron-right"></i></a>
								</div>
							</div>-->
							<h1 id="pi_name" class="mb-0 font-weight-bold text-7"><?=$row['name'];?></h1>
							<div class="pb-0 clearfix d-flex align-items-center">
								<div title="Rated 3 out of 5" class="float-start">
									<input type="text" class="opacity-0" value="3" title="" data-plugin-star-rating data-plugin-options="{'displayOnly': true, 'color': 'primary', 'size':'xs'}">
								</div>
								<div class="review-num">
									<a href="#description" class="text-decoration-none text-color-default text-color-hover-primary" data-hash data-hash-offset="75" data-hash-trigger-click=".nav-link-reviews" data-hash-trigger-click-delay="1000">
										<span class="count text-color-inherit" itemprop="ratingCount">(<?=$cnt;?></span> phản hồi)
									</a>
								</div>
							</div>
							<div class="divider divider-small">
								<hr class="bg-color-grey-scale-4">
							</div>
							<p class="price mb-3">
								<span id="pi_price" data-price="<?=$row['price'];?>" class="sale text-color-dark"><?=$row['price'];?> VNĐ</span>
								<!--<span class="amount">$22,00</span>-->
							</p>
							<p class="text-3-5 mb-3"><?=$row['description'];?></p>
							<form method="" class="cart" action="">
								<hr>
								<div class="quantity quantity-lg">
									<input type="button" class="minus text-color-hover-light bg-color-hover-primary border-color-hover-primary" value="-">
									<input type="text" class="input-text qty text" title="Qty" value="1" name="pi_count" min="1" step="1">
									<input type="button" class="plus text-color-hover-light bg-color-hover-primary border-color-hover-primary" value="+">
								</div>
								<button id="btn-add-cart" type="button" class="btn btn-dark btn-modern text-uppercase bg-color-hover-primary border-color-hover-primary">Thêm vào giỏ hàng</button>
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
								
								<li class="nav-item"><a class="nav-link nav-link-reviews font-weight-bold text-3 text-uppercase py-2 px-3" href="#productReviews" data-bs-toggle="tab">Đánh giá và nhận xét (<?=$cnt;?>)</a></li>
							</ul>
							<div class="tab-content p-0">
								<div class="tab-pane px-0 py-3 active" id="productDescription">
									<p><?=$row['description'];?></p>
								</div>
								<div class="tab-pane px-0 py-3" id="productReviews">
									<?php
										$sql_get_comment = "select * from product_comment where product_info_id='$id'";
										$result_comment = db_query($sql_get_comment);
									?>
									<ul id="list-comments" class="comments">
										<?php
											foreach($result_comment as $res) {
										?>
											<li>
												<div class="comment">
													<!--<div class="img-thumbnail border-0 p-0 d-none d-md-block">
														<img class="avatar rounded-circle" alt="" src="img/avatars/avatar-2.jpg">
													</div>-->
													<div class="comment-block">
														<div class="comment-arrow"></div>
														<span class="comment-by">
															<strong><?=$res['name'];?></strong>
															<span class="float-end">
																<div class="pb-0 clearfix">
																	<div title="Rated 3 out of 5" class="float-start">
																		<input type="text" class="opacity-0" value="<?=$res['rate'];?>" title="" data-plugin-star-rating data-plugin-options="{'displayOnly': true, 'color': 'primary', 'size':'xs'}">
																	</div>
																</div>
															</span>
														</span>
														<p><?=$res['comment']?></p>
													</div>
												</div>
											</li>
										<?php 
											} 
										?>
										<!--<li>
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
										</li>-->
										<!--<li>
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
																	<input type="text" class="opacity-0" value="3.5" title="" data-plugin-star-rating data-plugin-options="{'displayOnly': true, 'color': 'primary', 'size':'xs'}">
																</div>
															</div>
														</span>
													</span>
													<p>Lorem ipsum dolor sit amet, consectetur adipiscing elit. Nam viverra odio, gravida urna varius vitae, gravida pellentesque urna varius vitae.</p>
												</div>
											</div>
										</li>-->
									</ul>
									<hr class="solid my-5">
									<h4>Đánh giá và nhận xét</h4>
									<div class="row">
										<div class="col">
											<form action="<?=get_url_current_page();?>" id="submitReview" method="post">
												<div class="row">
													<div class="form-group col pb-2">
														<label class="form-label required font-weight-bold text-dark">Đánh giá</label>
														<input name="rate" type="text" class="rating-loading" value="" title="" data-plugin-star-rating data-plugin-options="{'color': 'primary', 'size':'sm'}">
													</div>
												</div>
												<div class="row">
													<div class="form-group col-lg-6">
														<label class="form-label required font-weight-bold text-dark">Họ và tên</label>
														<input type="text" value="" data-msg-required="Vui lòng nhập họ tên" maxlength="100" class="form-control" name="name" required>
													</div>
													<div class="form-group col-lg-6">
														<label class="form-label required font-weight-bold text-dark">Địa chỉ email</label>
														<input type="email" value="" data-msg-required="Vui lòng nhập địa chỉ email" data-msg-email="Please enter a valid email address." maxlength="100" class="form-control" name="email" required>
													</div>
												</div>
												<div class="row">
													<div class="form-group col">
														<label class="form-label required font-weight-bold text-dark">Nội dung</label>
														<textarea maxlength="5000" data-msg-required="Vui lòng nhập nội dung bình luận" rows="8" class="form-control" name="comment" id="review" required></textarea>
													</div>
												</div>
												<input type="hidden" name="token" value="<?php echo_token();?>">
												<input type="hidden" name="product_info_id" value="<?=$id;?>">
												<div class="row">
													<div class="form-group col mb-0">
														<button id="btn_comment" type="button" value="" class="btn btn-primary btn-modern">Gửi đánh giá</button>
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

<script>
	$('#btn_comment').on('click',function(e){
		let rate2 = $('#submitReview .filled-stars')[0].style.width;
		$('input[name="rate"]').val(parseFloat(rate2) / 100 * 5);
		e.preventDefault();
		let test = true;
		let name = $('input[name="name"]').val();
		let rate = $('input[name="rate"]').val();
		console.log(parseFloat(rate2) / 100 * 5);
		let comment = $('textarea[name="comment"]').val();
		let email = $('input[name="email"]').val();
		let product_info_id = $('input[name="product_info_id"]').val();
		let token = $('input[name="token"]').val();
		if(name.trim() == "") {
			$.alert({
				title: "Thông báo",
				content: "Vui lòng không để trống họ tên",
			});
			test = false;
		} else if(rate.trim() == "") {
			$.alert({
				title: "Thông báo",
				content: "Vui lòng không để trống sao đánh giá",
			});
			test = false;
		} else if(comment.trim() == "") {
			$.alert({
				title: "Thông báo",
				content: "Vui lòng không để trống nội dung đánh giá",
			});
			test = false;
		} else if(email.trim() == "") {
			$.alert({
				title: "Thông báo",
				content: "Vui lòng không để trống email",
			});
			test = false;
		}
		if(test) {
			$.ajax({
				url: window.location.href,
				type: "POST",
				data: {
					status: "comment_ok",
					product_info_id : product_info_id,
					name: name,
					rate: rate,
					comment: comment,
					email: email,
					token: token
				},
				success: function(data){
					data = JSON.parse(data);
					if(data.msg == "ok") {
						let start_rating_html = `
						<div class="rating-container theme-krajee-fas rating-xs rating-animate is-display-only">
							<div class="rating-stars" tabindex="0">
								<span class="empty-stars">
									<span class="star" title="One Star">
										<i class="far fa-star"></i>
									</span>
									<span class="star" title="Two Stars">
										<i class="far fa-star"></i>
									</span>
									<span class="star" title="Three Stars">
										<i class="far fa-star"></i>
									</span>
									<span class="star" title="Four Stars">
										<i class="far fa-star"></i>
									</span>
									<span class="star" title="Five Stars">
										<i class="far fa-star"></i>
									</span>
								</span>
								<span class="filled-stars" style="width: ${rate2};">
									<span class="star" title="One Star">
										<i class="fas fa-star"></i>
									</span>
									<span class="star" title="Two Stars">
										<i class="fas fa-star"></i>
									</span>
									<span class="star" title="Three Stars">
										<i class="fas fa-star"></i>
									</span>
									<span class="star" title="Four Stars">
										<i class="fas fa-star"></i>
									</span>
									<span class="star" title="Five Stars">
										<i class="fas fa-star"></i>
									</span>
								</span>
							</div>
						</div>
						`;
						let file_html = `
						<li>
							<div class="comment">
								<div class="comment-block">
									<div class="comment-arrow"></div>
									<span class="comment-by">
										<strong>${name}</strong>
										<span class="float-end">
											<div class="pb-0 clearfix">
												<div style='display:flex;justify-content:space-between;' title="" class="float-start">
													<input type="text" class="opacity-0" value="${rate}" title="" data-plugin-star-rating data-plugin-options="{'displayOnly': true, 'color': 'primary', 'size':'xs'}">
													${start_rating_html}
												</div>
											</div>
										</span>
									</span>
									<p>${comment}</p>
								</div>
							</div>
						</li>
						`;
						$(file_html).appendTo('#list-comments');
						$.alert({
							title: "Thông báo",
							content: "Cảm ơn bạn đã gửi phản hồi về sản phẩm cho chúng tôi",
						});
						
					}
					
				},error: function(data){
					console.log("Error" + data);
				}
			});
		}
	})
	$('#btn-add-cart').on('click',function(e){
		let pi_name = $('#pi_name').text();
		let pi_count = $('input[name="pi_count"]').val();
		let pi_price = $('#pi_price').attr('data-price');
		let pi_image = $('#pi_image').attr('src');

		$.ajax({
			url: "cart_ok.php",
			type: "POST",
			data: {
				status: "Insert",
				pi_name: pi_name,
				pi_count: pi_count,
				pi_price: pi_price,
				pi_image: pi_image,
				pi_id: '<?=$_REQUEST["id"];?>',
			},success:function(data){
				data = JSON.parse(data);
				if(data.msg == "ok") {
					$.alert({
						title: "Thông báo",
						content: "Bạn đã thêm sản phẩm vào giỏ hàng thành công",
					});
					setTimeout(() => {
						location.reload();
					},2000);
				}
			},error:function(data){
				console.log("Error:" + data);
			}
		});
	})

</script>
<!--js section end-->
<?php
        include_once("include/foot.php"); 
?>
<?php
    } else if (is_post_method()) {
        $status = isset($_REQUEST['status']) ? $_REQUEST['status'] : null;
		$comment = isset($_REQUEST['comment']) ? $_REQUEST['comment'] : null;
		$rate = isset($_REQUEST['rate']) ? $_REQUEST['rate'] : null;
		$customer_id = isset($_REQUEST['customer_id']) ? $_REQUEST['customer_id'] : null;
		$name = isset($_REQUEST['name']) ? $_REQUEST['name'] : null;
		$email = isset($_REQUEST['email']) ? $_REQUEST['email'] : null;
		$product_info_id = isset($_REQUEST['product_info_id']) ? $_REQUEST['product_info_id'] : null;
		if($status == "comment_ok") {
			$sql_comment = "Insert into product_comment(customer_id,name,email, product_info_id,comment,rate) values('$customer_id','$name','$email','$product_info_id','$comment','$rate')";
			db_query($sql_comment);
			$success = "success";
		}
		echo_json(['msg' => 'ok']);
    }
?>