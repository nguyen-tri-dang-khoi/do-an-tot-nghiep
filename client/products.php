<?php
    include_once("../lib/database.php");
    if(is_get_method()) {
        include_once("include/head.meta.php");
        include_once("include/menu.php");
        // code to be executed get method
		
		$where = "where 1=1 and is_delete = 0";
		$search_option = isset($_REQUEST['search_option']) ? $_REQUEST['search_option'] : null;
		$keyword = isset($_REQUEST['keyword']) ? $_REQUEST['keyword'] : null;
		if($keyword2 && $keyword2 != "") {
			$where .= " and lower(name) like '%$keyword2%'";
		} else if($search_option) {
			if($keyword || $keyword == 0) {
				if($search_option == "name") {
					$where .= " and lower(name) like '%$keyword%'";
				} else if($search_option == "description") {
					$where .= " and lower(description) like '%$keyword%'";
				} else if($search_option == "price") {
					$where .= " and lower(price) like '%$keyword%'";
				} else if($search_option == "all") {
					$where .= " and lower(name) like '%$keyword%'";
					$where .= " or lower(price) like '%$keyword%'";
					$where .= " or lower(description) like '%$keyword%'";
				}
			}
		}
		$sql = "select * from product_info $where ";
		//print_r($sql);
		$results = db_query($sql);
?>
<!--html & css section start-->
<div role="main" class="main shop pt-4">
	<div class="container">
		<div class="row">
			<div class="col-lg-12 order-2 order-lg-1">
				<aside class="sidebar">
					<form action="products.php" method="get" style="display:flex;">
						<div class="col-3" >
							<div class="input-group mb-3 pb-1">
								<select name="search_option" class="form-control text-1" id="">
									<option value="">Côt tìm kiếm</option>
									<option value="name" <?=$search_option == "name" ? "selected" : "";?>>Tên sản phẩm</option>
									<option value="description" <?=$search_option == "description" ? "selected" : "";?>>Mô tả sản phẩm</option>
									<option value="price" <?=$search_option == "price" ? "selected" : "";?>>Đơn giá</option>
									<option value="all" <?=$search_option == "all" ? "selected" : "";?>>Tất cả</option>
								</select>
							</div>
						</div>
						<div class="col-3">
							<div class="input-group ml-10 mb-3 pb-1">
								<input class="form-control text-1" placeholder="Tìm kiếm" value="<?=$keyword?>" name="keyword" id="s" type="text">
								<button type="submit" class="btn btn-dark text-1 p-2"><i class="fas fa-search m-2"></i></button>
							</div>
						</div>
					</form>
				</aside>
			</div>
			<div class="col-lg-12 order-1 order-lg-2">
				<div class="masonry-loader masonry-loader-showing">
					<div class="row products product-thumb-info-list" data-plugin-masonry data-plugin-options="{'layoutMode': 'fitRows'}">
						<?php
							foreach($results as $res) {
						?>
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
									<!--<a href="ajax/shop-product-quick-view.html" class="quick-view text-uppercase font-weight-semibold text-2">
										QUICK VIEW
									</a>-->
									<a href="product_detail.php?id=<?=$res['id']?>">
										<div class="product-thumb-info-image">
											<img alt="" class="img-fluid" src='<?="../admin/" . $res['img_name'];?>'>
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
									<span class="amount"><?=number_format($res['price'],0,'','.')?> VNĐ</span>
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