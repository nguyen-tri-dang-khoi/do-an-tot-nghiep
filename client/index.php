<?php
    include_once("../lib/database.php");
    if(is_get_method()) {
        include_once("include/head.meta.php");
		include_once("include/menu.php");
        // code to be executed get method
		$where = "where 1=1 and pi.is_delete = 0";
		//$sql = "select * from product_info pi left join product_type pt on pi.product_info_id = pt. where pi.product_type_id = 130";
		//$result = db_query($sql);
		$sql = "select * from product_type where parent_id is null and is_delete = 0";
		$results = db_query($sql);
		//log_v(find_branch_by_root(128));

?>
<!--html & css section start-->
<div role="main" class="main shop pt-4">
	<section class="">
		<div class="container">
			
		</div>
	</section>
	<div class="container">
		<?php
			foreach($results as $result) {
				$type_id = find_branch_by_root($result['id']);
				$sql = "select * from product_info where product_type_id = '$type_id' and is_delete = 0";
				$result2 = db_query($sql);
		?>
		<div class="j-between row page-header page-header-modern page-header-md ptb-0 mb-10 kh-header-title">
			<div class="col-md-6 order-2 order-md-1 align-self-center p-static">
				<h2 class="mb-0 kh-h2-tag"><?=$result['name']?></h2>
			</div>
			<div class="d-flex col-md-6 order-1 order-md-2 align-self-center j-between">
				<div></div>
				<div class="j-end">
					<button type="button" class="kh-btn kh-btn-pt">Samsung</button>
					<button type="button" class="kh-btn kh-btn-pt">Nokia</button>
					<button type="button" class="kh-btn kh-btn-pt">Lenovo</button>
				</div>
			</div>
		</div>
		<div class="masonry-loader mb-40 kh-list-product">
			<div class="row products product-thumb-info-list" data-plugin-masonry data-plugin-options="{'layoutMode': 'fitRows'}">
				<?php 
					foreach($result2 as $res) {
				?>
					<div class="col-12 col-sm-6 col-lg-3">
						<div class="product mb-0">
							<div class="product-thumb-info border-0 mb-3">
								<div class="product-thumb-info-badges-wrapper">
									<span class="badge badge-ecommerce badge-success"></span>
								</div>
								<div class="addtocart-btn-wrapper">
									<a href="#" class="text-decoration-none addtocart-btn" title="Thêm vào giỏ hàng">
										<i class="icons icon-bag"></i>
									</a>
								</div>
								<!--<a href="ajax/shop-product-quick-view.html" class="quick-view text-uppercase font-weight-semibold text-2">
									QUICK VIEW
								</a>-->
								<a href="product_detail.php?id=<?=$res['id']?>">
									<div class="product-thumb-info-image">
										<img style="" alt="" class="img-fluid" src='<?="../admin/" . $res['img_name'];?>'>
									</div>
								</a>
							</div>
							<div class="d-flex justify-content-between">
								<div>
									<a href="#" class="d-block text-uppercase text-decoration-none text-color-default text-color-hover-primary line-height-1 text-0 mb-1">electronics</a>
									<h3 class="text-3-5 font-weight-medium font-alternative text-transform-none line-height-3 mb-0"><a href="product_detail.php?id=<?=$res['id']?>" class="text-color-dark text-color-hover-primary"><?=$res['name'];?></a></h3>
								</div>
								<a href="#" class="text-decoration-none text-color-default text-color-hover-dark text-4"><i class="far fa-heart"></i></a>
							</div>
							<div title="Rated 5 out of 5">
								<input type="text" class="d-none" value="5" title="" data-plugin-star-rating data-plugin-options="{'displayOnly': true, 'color': 'default', 'size':'xs'}">
							</div>
							<p class="price text-5 mb-3">
								<!--<span class="sale text-color-dark font-weight-semi-bold"></span>-->
								<span class="amount"><?=$res['price']?> VNĐ</span>
							</p>
						</div>
					</div>
				<?php 
					}
				?>
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
		<?php
			}
		?>
		<!--<div class="j-between row page-header page-header-modern page-header-md ptb-0 mb-10 kh-header-title">
			<div class="col-md-6 order-2 order-md-1 align-self-center p-static">
				<h2 class="mb-0 kh-h2-tag">Điện thoại</h2>
			</div>
			<div class="d-flex col-md-6 order-1 order-md-2 align-self-center j-between">
				<div></div>
				<div class="j-end">
					<button type="button" class="kh-btn kh-btn-pt">Samsung</button>
					<button type="button" class="kh-btn kh-btn-pt">Nokia</button>
					<button type="button" class="kh-btn kh-btn-pt">Lenovo</button>
				</div>
			</div>
		</div>
		<div class="masonry-loader masonry-loader-showing mb-40 kh-list-product">
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

<!--js section end-->
<?php
        include_once("include/foot.php"); 
?>
<?php
    } else if (is_post_method()) {
        
        // code to be executed post method
    }
?>