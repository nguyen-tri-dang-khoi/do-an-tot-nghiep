<?php
    include_once("../lib/database.php");
	redirect_if_customer_login_status_false();
    if(is_get_method()) {
        include_once("include/head.meta.php");
        include_once("include/menu.php");
		$order_id = isset($_REQUEST['order_id']) ? $_REQUEST['order_id'] : "";
		if($order_id) {
			$sql_get_order_info = "select ord.created_at as 'ord_created_at',ord.*,cus.* from orders ord inner join customer cus on ord.customer_id = cus.id where ord.id = '$order_id' limit 1";
			$sql_get_order_detail = "select ord.count as 'ord_count',ord.price as 'ord_price',pi.id as 'pi_id', ord.*,pi.* from order_detail ord inner join product_info pi on ord.product_info_id = pi.id where ord.order_id = $order_id";
			$result = fetch(sql_query($sql_get_order_info));
			if($result == false)  {
				echo "<script>location.href='products.php'</script>";
				exit();
			}
			$list_order_detail = fetch_all(sql_query($sql_get_order_detail));
		} else {
			echo "<script>location.href='products.php'</script>";
			exit();
		}
		
        // code to be executed get method
?>
<div role="main" class="main shop pb-4">
	<div class="container">
		<div class="row justify-content-center">
			<div class="col-lg-8">
				<ul class="breadcrumb breadcrumb-dividers-no-opacity font-weight-bold text-6 justify-content-center my-5">
					<li class="text-transform-none me-2">
						<a href="cart.php" class="text-decoration-none text-color-grey-lighten">Giỏ hàng</a>
					</li>
					<li class="text-transform-none text-color-grey-lighten me-2">
						<a href="checkout.php" class="text-decoration-none text-color-grey-lighten text-color-hover-primary">Thanh toán</a>
					</li>
					<li class="text-transform-none text-color-grey-lighten">
						<a href="order_complete.php" class="text-decoration-none text-color-grey-lighten  text-color-primary text-color-hover-primary">Đơn hàng hoàn tất</a>
					</li>
				</ul>
			</div>
		</div>

		<div class="row justify-content-center">
			<div class="col-lg-12">
				<div class="card border-width-3 border-radius-0 border-color-success">
					<div class="card-body text-center">
						<p class="text-color-dark font-weight-bold text-4-5 mb-0"><i class="fas fa-check text-color-success me-1"></i> Chúc mừng bạn đã đặt hàng thành công, cảm ơn bạn đã ủng hộ chúng tôi rất nhiều</p>
					</div>
				</div>
				<div class="d-flex flex-column flex-md-row justify-content-between py-3 px-4 my-4">
					<div class="text-center">
						<span>
							Mã đơn hàng: <br>
							<strong class="text-color-dark"><?=$result['orders_code'] ? $result['orders_code'] : "";?></strong>
						</span>
					</div>
					<div class="text-center mt-4 mt-md-0">
						<span>
							Ngày đặt hàng: <br>
							<strong class="text-color-dark"><?=$result['ord_created_at'] ? Date("d-m-Y",strtotime($result['ord_created_at'])) : "";?></strong>
						</span>
					</div>
					<div class="text-center mt-4 mt-md-0">
						<span>
							Email <br>
							<strong class="text-color-dark"><?=$result['email'] ? $result['email'] : "";?></strong>
						</span>
					</div>
					<div class="text-center mt-4 mt-md-0">
						<span>
							Số điện thoại <br>
							<strong class="text-color-dark"><?=$result['phone'] ? $result['phone'] : "";?></strong>
						</span>
					</div>
					<div class="text-center mt-4 mt-md-0">
						<span>
							Tổng tiền <br>
							<strong class="text-color-dark"><?=$result['total'] ? number_format($result['total'],0,".",",")."đ" : "";?></strong>
						</span>
					</div>
					
				</div>
				<div class="d-flex flex-column flex-md-row justify-content-between py-3 px-4 my-4">
					<?php
						$sql_get_payment_method = "select payment_name from payment_method where id = ";
						$sql_get_payment_method .= $result['payment_method_id'] ? $result['payment_method_id'] : "";
						if($result['payment_method_id']) {
							$payment_name = fetch(sql_query($sql_get_payment_method));
					?>
					<div class="text-center mt-4 mt-md-0">
						<span>
							Phương thức thanh toán<br>
							<strong class="text-color-dark"><?=$payment_name['payment_name'] ? $payment_name['payment_name'] : "";?></strong>
						</span>
					</div>
					<div class="text-center mt-4 mt-md-0">
						<span>
							Tình trạng thanh toán<br>
							<strong class="text-color-dark"><?=$result['payment_status'] == 1 ? "Đã thanh toán" : "Chưa thanh toán";?></strong>
						</span>
					</div>
					<?php } ?>
					<div class="text-center">
						<span>
							Tình trạng giao hàng: <br>
							<?php
								$sql_get_delivery_status = "select delivery_status_name from delivery_status where id = " . $result['delivery_status_id'];
								//log_v($sql_get_delivery_status);
								$row22 = fetch(sql_query($sql_get_delivery_status));
							?>
							<strong class="text-color-dark"><?=$row22['delivery_status_name'] ? $row22['delivery_status_name'] : "";?></strong>
						</span>
					</div>
					<div class="text-center mt-4 mt-md-0">
						<span>
							Ngày giao hàng: <br>
							<strong class="text-color-dark"><?=$result['delivery_date'] ? Date("d-m-Y",strtotime($result['delivery_date'])) : "Đang cập nhật";?></strong>
						</span>
					</div>
					<div class="text-center mt-4 mt-md-0">
						<span>
							Địa chỉ giao hàng: <br>
							<strong class="text-color-dark"><?=$result['address'] ? $result['address'] : "Đang cập nhật";?></strong>
						</span>
					</div>
					<div class="text-center mt-4 mt-md-0">
						<span>
							Ghi chú đơn hàng<br>
							<strong class="text-color-dark"><?=$result['note'] ? $result['note'] : "Không có";?></strong>
						</span>
					</div>
					<!--<div class="text-center mt-4 mt-md-0">
						<span>
							Tình trạng thanh toán<br>
							<strong class="text-color-dark"><?=$result['payment_status'] == 1 ? "Đã thanh toán" : "Chưa thanh toán";?></strong>
						</span>
					</div>-->
				</div>
				<div class="card border-width-3 border-radius-0 border-color-hover-dark mb-4">
					<div class="card-body">
						<h4 class="font-weight-bold text-uppercase text-4 mb-3">Chi tiết đơn hàng: </h4>
						<table class="shop_table cart-totals mb-0">
							<tbody>
								<tr>
									<td colspan="2" class="border-top-0">
										<strong class="text-color-dark">Sản phẩm</strong>
									</td>
								</tr>
								<?php foreach($list_order_detail as $row) {?>
								<?php
									$sql_get_img = "select img_name from product_info where id=" . $row['pi_id'] . " limit 1";
									log_a($sql_get_img);
									$row2 = fetch(sql_query($sql_get_img));
								?>
									<tr>
										<td style="display:flex;align-items:center;">
											<img width="75" src="../admin/<?=$row2['img_name'] ? $row2['img_name'] : "";?>" alt="">
											<div style="margin-left:10px;">
												<strong class="d-block text-color-dark line-height-1 font-weight-semibold"><?=$row['name'];?></strong><span style="margin-right:10px;" class="product-qty"> x <?=$row['ord_count'];?></span>
											</div>
											
										</td>
										<td class="text-end align-top">
											<span class="amount font-weight-medium text-color-grey"><?=number_format($row['ord_price'],0,".",".")."đ";?></span>
										</td>
									</tr>
								<?php } ?>
								<tr class="cart-subtotal">
									<td class="border-top-0">
										<strong class="text-color-dark">Mã khuyến mãi áp dụng</strong>
									</td>
									<?php
									$row44['coupon_discount_percent'] = 0;
									if($result['coupon_id']){
										$sql_get_coupon = "select * from coupon where id = ". $result['coupon_id'];
										$row44 = fetch(sql_query($sql_get_coupon));
									
									?>
									<td class="border-top-0 text-end">
										<strong><span class="amount font-weight-medium"><?=$row44['coupon_code'] ? $row44['coupon_code'] : "Không có";?></span></strong>
										<p class="text-danger">-<?=number_format(($result['total'] * $row44['coupon_discount_percent'] / 100),0,".",".") . "đ";?></p>
									</td>
									<?php } else {?>
									<td class="border-top-0 text-end">
										<strong><span class="amount font-weight-medium">Không có</span></strong>
										<p class="text-danger">-0đ</p>
									</td>	
									<?php } ?>
								</tr>
								<tr class="shipping">
									<td class="border-top-0">
										<strong class="text-color-dark">Phí giao hàng</strong>
									</td>
									<td class="border-top-0 text-end">
										<strong><span class="amount font-weight-medium"><?=$result['delivery_fee'] ? number_format($result['delivery_fee'],0,".",".") : "Miễn phí vận chuyển";?></span></strong>
									</td>
								</tr>
								<tr class="total">
									<td>
										<strong class="text-color-dark text-3-5">Tổng tiền thanh toán</strong>
									</td>
									<td class="text-end">
										<strong class="text-color-dark"><span class="amount text-color-dark text-5">
											<?=$row44 ? number_format(($result['total'] - ($result['total'] * $row44['coupon_discount_percent'] / 100 + $result['delivery_fee'])),0,".",".") . "đ" : "";?></span>
										</strong>
									</td>
								</tr>
							</tbody>
						</table>
					</div>
				</div>
				<!--<div style="width:100%;justify-content:space-between;" class="row pt-3">
					<div class="col-lg-6 mb-4 mb-lg-0">
						<h2 class="text-color-dark font-weight-bold text-5-5 mb-1">Địa chỉ giao hàng</h2>
						<ul class="list list-unstyled text-2 mb-0">
							<li class="mb-0"><?=$result['address'] ? $result['address'] : "";?></li>
							<li class="mb-0">Street Name, City</li>
							<li class="mb-0">State AL 85001</li>
							<li class="mb-0">123 456 7890</li>
							<li class="mt-3 mb-0">abc@abc.com</li>-
						</ul>
					</div>
					<div class="col-lg-6">
						<h2 class="text-color-dark font-weight-bold text-5-5 mb-1">Ghi chú</h2>
						<ul class="list list-unstyled text-2 mb-0">
							<li class="mb-0"><?=$result['note'] ? $result['note'] : "Không có";?></li>
						</ul>
					</div>
				</div>-->
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