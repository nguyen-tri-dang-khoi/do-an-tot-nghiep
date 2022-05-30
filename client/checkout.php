<?php
	define("USD",0.4);
    include_once("../lib/database.php");
	redirect_if_customer_login_status_false();
	$url = get_url_current_page();
    if(is_get_method()) {
        include_once("include/head.meta.php");
        include_once("include/menu.php");
		$sql = "select * from customer where id = $_SESSION[customer_id] limit 1";
		$result = fetch(sql_query($sql));
		log_a($_SESSION['cart']);
		//log_a($result);
?>
<style>
	.payment-hide {
		display:none !important;
	}
</style>
<!--html & css section start-->
<div role="main" class="main shop pb-4">
	<div class="container">
		<div class="row">
			<div class="col">
				<ul class="breadcrumb font-weight-bold text-6 justify-content-center my-5">
					<li class="text-transform-none text-color-grey-lighten me-2">
						<a href="cart.php" class="text-color-grey-lighten text-decoration-none">Giỏ hàng</a>
					</li>
					<li class="text-transform-none text-color-grey-lighten me-2">
						<a href="checkout.php" class="text-decoration-none text-color-hover-primary">Thanh toán</a>
					</li>
					<li class="text-transform-none text-color-grey-lighten">
						<a href="order_complete.php" class="text-decoration-none text-color-grey-lighten text-color-hover-primary">Đơn hàng hoàn tất</a>
					</li>
				</ul>
			</div>
		</div>
		<div class="row coupon-form-wrapper collapse mb-5">
			<div class="col">
				<div class="card border-width-3 border-radius-0 border-color-hover-dark">
					<div class="card-body">
						<form role="form" method="post" action="">
							<div class="d-flex align-items-center">
								<input type="text" class="form-control h-auto border-radius-0 line-height-1 py-3" name="couponCode" placeholder="Coupon Code"  />
								<button type="submit" class="btn btn-light btn-modern text-color-dark bg-color-light-scale-2 text-color-hover-light bg-color-hover-primary text-uppercase text-3 font-weight-bold border-0 border-radius-0 ws-nowrap btn-px-4 py-3 ms-2">Apply Coupon</button>
							</div>
						</form>
					</div>
				</div>
			</div>
		</div>
		<div class="row">
			<div class="col-lg-6 mb-4 mb-lg-0">
				<h2 class="text-color-dark font-weight-bold text-5-5 mb-3">Thông tin người mua</h2>
				<div class="row">
					<div class="form-group col-md-6">
						<label class="form-label">Họ tên của bạn <span class="text-color-danger">*</span></label>
						<input type="text" class="form-control h-auto py-2" name="full_name" value="<?=$result['full_name']?>"  />
					</div>
				</div>
				<div class="row">
					<div class="form-group col">
						<label class="form-label">Địa chỉ giao hàng</label>
						<input type="text" class="form-control h-auto py-2" name="address" value="<?=$result['address']?>" />
					</div>
				</div>
				<div class="row">
					<div class="form-group col">
						<label class="form-label">Số điện thoại <span class="text-color-danger">*</span></label>
						<input type="number" class="form-control h-auto py-2" name="phone" value="<?=$result['phone']?>"  />
					</div>
				</div>
				<div class="row">
					<div class="form-group col">
						<label class="form-label">Email liên hệ <span class="text-color-danger">*</span></label>
						<input type="email" class="form-control h-auto py-2" name="email" value="<?=$result['email']?>"  />
					</div>
				</div>
				<button type="button" onclick="createOrder2()" class="btn btn-secondary">Lưu thông tin</button>
			</div>
			<div class="col-lg-6 mb-4 mb-lg-0">
				<h2 class="text-color-dark font-weight-bold text-5-5 mb-3">Thông tin cửa hàng</h2>
				<table class="table table-striped">
					<tr>
						<th>Tên cửa hàng</th>
						<td>Tech-shop</td>
					</tr>
					<tr>
						<th>Địa chỉ</th>
						<td>Thành phố Hồ chí minh</td>
					</tr>
					<tr>
						<th>Số điện thoại</th>
						<td>0123456789</td>
					</tr>
					<tr>
						<th>Email</th>
						<td>example@gmail.com</td>
					</tr>
				</table>
			</div>
		</div>					
		<hr>
		<div class="row mb-5 mb-lg-0">
			<h2 class="text-color-dark font-weight-bold text-5-5 mb-3">Thông tin giỏ hàng</h2>
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
								<span class="amount font-weight-medium text-color-grey"><?=number_format($cart['pi_price'],0,"",".");?> VNĐ</span>
							</td>
							<td class="product-quantity">
								<div class="quantity float-none m-0">
									<input style="cursor:default;" readonly type="text" class="input-text qty text" title="Qty" value="<?=$cart['pi_count']?>" name="pi_count" min="1" step="1">
								</div>
							</td>
							<td class="product-subtotal text-end">
								<span class="amount text-color-dark font-weight-bold text-4"><?=number_format($cart['pi_price'] * $cart['pi_count'],0,"",".");?> VNĐ</span>
							</td>
						</tr>
						<?php
								$i++;
							}
							log_v($sum);
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
		</div>
		<div class="row mb-5 mb-lg-0">
			<div class="col-md-6">
				<p class="font-weight-bold text-color-dark" style="font-size:17px;">Chọn mã khuyến mãi</p>
				<select onchange="showCouponContent()" name="coupon" class="form-control">
					<option value="">Chọn mã khuyến mãi</option>
					<?php
						$sql_show_coupon = "select * from coupon where is_delete = 0";
						$results = fetch_all(sql_query($sql_show_coupon));
						foreach($results as $row){
					?>
						<option data-discount="<?=$row['coupon_discount_percent'];?>" value="<?=$row['id'];?>"><?=$row['coupon_code'];?></option>
					<?php } ?>
				</select>
				<p class="show-coupon-info text-danger"></p>
			</div>
			<div class="col-md-6 form-group col">
				<p class="font-weight-bold text-color-dark" style="font-size:17px;">Ghi chú đơn hàng</p>
				<textarea class="form-control h-auto py-2" name="notes" rows="5" placeholder="Ghi chú..." >Giao hàng nhớ gọi điện trước</textarea>
			</div>						
		</div>
		<!--payment online-->
		<div class="row">
			<h2 class="text-color-dark font-weight-bold text-5-5 mb-3">Chọn phương thức thanh toán</h2>
			<div class="col-12 row">
				<div id="cod-checkbox" class="col-5 mb-3">
					<input onclick="addPaymentOnline()" class="form-check-input" type="radio" name="cod_payment" style="font-size:15px;" checked>
					<label for="cod_payment" style="font-size:17px;">Thanh toán online</label>
				</div>
			</div>
			<div class="is-payment-online col-12 row">
				<div id="paypal-button-container2" class="col-3"></div>
				<div id="vnpay-button-container" class="col-3">
					<button data-toggle="modal" data-target="#exampleModal" type="button" style="border:1px solid red;font-size:17px;color:red;" class="btn font-weight-bold">Thanh toán qua  <img style="width:61%;" src="img/logo.0a91d69.svg" alt=""></button>
				</div>
				<div id="momo-button-container" class="col-3">
					<button data-toggle="modal" data-target="#exampleModal2" type="button" style="border:1px solid #b0006e;color:#b0006e;font-size:17px;width:100%;" class="btn font-weight-bold">Thanh toán qua <img style="width: 30%;" src="img/momo.png" alt=""></button>
				</div>
				<div id="zalopay-button-container" class="col-3">
					<button data-toggle="modal" data-target="#exampleModal3" type="button" style="border:1px solid #0068ff;color:#0068ff;font-size:17px;width:100%;" class="btn font-weight-bold">Thanh toán qua <img style="width: 67%;" src="img/zalopay.svg" alt=""></button>
				</div>				
			</div>
			
			<div id="cod-checkbox" class="col-5  mt-3" style="">
				<input onclick="removePaymentOnline()" class="form-check-input" type="radio" name="cod_payment" style="font-size:15px;">
				<label for="cod_payment" style="font-size:17px;">Thanh toán tiền mặt khi nhận hàng</label>
				<button onclick="paymentCod()" type="button" style="width:190px;" class="is-payment-cod payment-hide btn btn-primary btn-rounded btn-px-4 btn-py-2 font-weight-bold">Đồng ý</button>
			</div>
			<!--vnpay-->
			<div class="modal fade" id="exampleModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
				<div class="modal-dialog" role="document">
					<div class="modal-content">
						<div class="modal-header">
							<h5 class="modal-title" id="exampleModalLabel">Thanh toán bằng Vnpay</h5>
							<button type="button" class="close" data-dismiss="modal" aria-label="Close">
								<span aria-hidden="true">&times;</span>
							</button>
						</div>
						<div class="modal-body">
							<form action="vnpay_checkout.php" method="post">   
								<div class="form-group">
									<label for="language">Loại hàng hóa:</label>
									<select name="order_type" id="ordertype" class="form-control">
										<option value="billpayment" selected>Thanh toán hóa đơn</option>
									</select>
								</div>        
								<div class="form-group">
									<label for="amount">Số tiền</label>
									<input readonly class="form-control" name="amount" type="text" value="<?=$sum;?>">
								</div>
								<div class="form-group">
									<label>Nội dung thanh toán</label>
									<textarea class="form-control" cols="20" id="OrderDescription" name="order_desc" rows="2"><?=Date("d-m-y H:i:s",time());?></textarea>
								</div>
								<div class="form-group">
									<label for="bank_code">Ngân hàng</label>
									<select name="bank_code" id="bankcode" class="form-control">
										<option value="">Không chọn </option>    
										<option value="NCB">Ngan hang NCB</option>
									</select>
								</div>
								<div class="form-group">
									<label for="language">Ngôn ngữ</label>
									<select name="language" id="language" class="form-control">
										<option value="vn">Tiếng Việt</option>
										<option value="en">English</option>
									</select>
								</div>
								<input type="hidden" name="note" value="">
								<input type="hidden" name="address" value="">
								<input type="hidden" name="payment_method" value="vnpay">
								<input type="hidden" name="payment_method_id" value="4">
								<input type="hidden" name="status" value="checkout_ok">
								<button type="submit" class="btn btn-primary">Thanh toán</button>
							</form>
						</div>
					</div>
				</div>
			</div>
			<!--momo-->
			<div class="modal fade" id="exampleModal2" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel2" aria-hidden="true">
				<div class="modal-dialog" role="document">
					<div class="modal-content">
						<div class="modal-header">
							<h5 class="modal-title" id="exampleModalLabel">Thanh toán bằng Momo</h5>
							<button type="button" class="close" data-dismiss="modal" aria-label="Close">
								<span aria-hidden="true">&times;</span>
							</button>
						</div>
						<div class="modal-body">
							<form action="momo_checkout.php" method="post">   
								<div class="form-group">
									<label for="amount">Số tiền</label>
									<input readonly class="form-control" name="amount" type="text" value="<?=$sum;?>">
								</div>
								<div class="form-group">
									<label>Nội dung thanh toán</label>
									<textarea class="form-control" cols="20" id="OrderDescription" name="order_desc" rows="2"><?=Date("d-m-y H:i:s",time());?></textarea>
								</div>
								<input type="hidden" name="note" value="">
								<input type="hidden" name="address" value="">
								<input type="hidden" name="payment_method" value="momo">
								<input type="hidden" name="payment_method_id" value="1">
								<input type="hidden" name="status" value="checkout_ok">
								<button type="submit" class="btn" style="color:#fff;background-color:#b0006e;border:1px solid #b0006e;">Thanh toán</button>
							</form>
						</div>
					</div>
				</div>
			</div>
			<!--zalopay-->
			<div class="modal fade" id="exampleModal3" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel3" aria-hidden="true">
				<div class="modal-dialog" role="document">
					<div class="modal-content">
						<div class="modal-header">
							<h5 class="modal-title">Thanh toán bằng Zalopay</h5>
							<button type="button" class="close" data-dismiss="modal" aria-label="Close">
								<span aria-hidden="true">&times;</span>
							</button>
						</div>
						<div class="modal-body">
							<form action="zalopay_checkout.php" method="post">    
								<div class="form-group">
									<label for="amount">Số tiền</label>
									<input readonly class="form-control" name="amount" type="text" value="<?=$sum;?>">
								</div>
								<div class="form-group">
									<label>Nội dung thanh toán</label>
									<textarea class="form-control" cols="20" id="OrderDescription" name="order_desc" rows="2"><?=Date("d-m-y H:i:s",time());?></textarea>
								</div>
								<?php
									$cart2 = $_SESSION['cart'];
									for($i = 0 ; $i < count($cart2) ; $i++) {
										unset($cart2[$i]['pi_image']);
									}
									$cart2 = json_encode($cart2);
								?>
								<input type="hidden" name="items" value=<?="'".$cart2."'";?>>
								<input type="hidden" name="note" value="">
								<input type="hidden" name="address" value="">
								<input type="hidden" name="payment_method" value="zalopay">
								<input type="hidden" name="payment_method_id" value="5">
								<input type="hidden" name="status" value="checkout_ok">
								<button type="submit" class="btn" style="color:#fff;background-color:#0068ff;border:1px solid #0068ff;">Thanh toán</button>
							</form>
						</div>
					</div>
				</div>
			</div>
		</div>
		<div class="row mt-5" style="justify-content:center;align-items:center;">
			
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
<script src="https://www.paypal.com/sdk/js?client-id=AUgbz7axMhtzH7lqGj5C5K6iG2fWIHV7rvU-4BMiksQqHh4rnE9o9RS0qhXyfVd3TojEHbdlPCLEDXDm&components=buttons&locale=en_VN"></script>
<script>
	var list_cart = [];
	var cart = {};
	var coupon_discount_percent = 0;
	<?php
		$i2 = 0;
		foreach($_SESSION['cart'] as $cart) {
	?>
		cart['name'] = "<?=$cart['pi_name'];?>";
		cart['unit_amount'] = {
			"currency_code": "USD",
			"value": <?=$cart['pi_price'] * USD;?> 
		};
		cart['quantity'] = "<?=$cart['pi_count'];?>";
		list_cart[<?=$i2;?>] = cart;
		cart = {}
	<?php 
			$i2++;
		} 
	?>
	console.log(list_cart);
	paypal.Buttons({
		style: {
			layout: 'vertical',
			color:  'blue',
			shape:  'rect',
			label:  'paypal'
		},
		onClick: function() {
			coupon_discount_percent = $("select[name='coupon'] > option:selected").attr('data-discount');
		},
		createOrder: function(data, actions) {
			// Set up the transaction
			return actions.order.create({
				"purchase_units": [{
					"amount": {
						"currency_code": "USD",
						"value": <?=$sum * USD?> - (<?=$sum * USD?> * coupon_discount_percent / 100),
						"breakdown": {
							"item_total": {  
								"currency_code": "USD",
								"value": <?=$sum * USD?>
							},
							"shipping_discount":{
								"currency_code": "USD",
								"value": <?=$sum * USD?> * coupon_discount_percent / 100
							}
						}
					},
					"items": list_cart
				}]
			});
		},
		onApprove: function(data, actions) {
			return actions.order.capture().then(function(details) {
				
				createOrder2(1,"paypal");
			});
		}
	}).render('#paypal-button-container2');
</script>
<script>
	function addPaymentOnline(){
		$('.is-payment-online').removeClass('payment-hide');
		$('.is-payment-cod').addClass('payment-hide');
	}
	function removePaymentOnline(){
		$('.is-payment-online').addClass('payment-hide');
		$('.is-payment-cod').removeClass('payment-hide');
	}
	function showCouponContent(){
		let coupon_id = $("select[name='coupon'] > option:selected").val();
		if(coupon_id != "") {
			$.ajax({
				url:"checkout_ok.php",
				type:"POSt",
				data: {
					status:"get_coupon_content",
					coupon_id:coupon_id,
				},success:function(data) {
					console.log(data);
					data = JSON.parse(data);
					if(data.msg == "ok") {
						$('.show-coupon-info').text(data.coupon_content);
					}
				}
			})
		}
		
	}
	function paymentCod(){
		createOrder2(1,"cod");
	}
</script>
<script>
	$('#exampleModal').on('shown.bs.modal', function () {
		console.log($("textarea[name='notes']").val());
		$("input[name='note']").val($("textarea[name='notes']").val());
		//$("input[name='payment_method']").val("vnpay");
		//$("input[name='address']").val($("input[name='address']"));
	})
	$('#exampleModal2').on('shown.bs.modal', function () {
		console.log($("textarea[name='notes']").val());
		$("input[name='note']").val($("textarea[name='notes']").val());
		//$("input[name='payment_method']").val("momo");
		//$("input[name='address']").val($("input[name='address']"));
	})
	$('#exampleModal3').on('shown.bs.modal', function () {
		console.log(JSON.parse($("input[name='items']").val()));
		$("input[name='note']").val($("textarea[name='notes']").val());
		//$("input[name='payment_method']").val("momo");
		//$("input[name='address']").val($("input[name='address']"));
	})
	function createOrderMomo(){

	}
	function createOrder2(index = 0,payment_method){
		// save client_info
		let test = true;
		let full_name = $("input[name='full_name']").val();
		let email = $("input[name='email']").val();
		let address = $("input[name='address']").val();
		let phone = $("input[name='phone']").val();
		let notes = $("textarea[name='notes']").val();
		let coupon_id = $("select[name='coupon'] > option:selected").val();
		let payment_method_id = "";
		if(payment_method == "paypal") {
			payment_method_id = "2";
		} else if(payment_method == "vnpay") {
			payment_method_id = "4";
		} else if(payment_method == "momo") {
			payment_method_id = "1";
		} else if(payment_method == "cod") {
			payment_method_id = "3";
		}
		
		if(full_name == "") {
			$.alert({
				title:"Thông báo",
				content: "Tên đầy đủ không được để trống",
			});
			test = false;
		} else if(email == ""){
			$.alert({
				title:"Thông báo",
				content: "Email không được để trống",
			});
			test = false;
		} else if(address == ""){
			$.alert({
				title:"Thông báo",
				content: "Địa chỉ giao hàng không được để trống",
			});
			test = false;
		} else if(phone == ""){
			$.alert({
				title:"Thông báo",
				content: "Số điện thoại không được để trống",
			});
			test = false;
		} else if(payment_method_id == "") {
			$.alert({
				title:"Thông báo",
				content: "Vui lòng chọn phương thức thanh toán",
			});
			test = false;
		}
		if(test) {
			if(index == 0) {
				$.ajax({
					url: "checkout_ok.php",
					type: "POST",
					data: {
						status: "save_customer_info",
						full_name: full_name,
						email: email,
						address: address,
						phone: phone,
						notes: notes,
						id: "<?=$_SESSION['customer_id']?>"
					},
					success: function(data){
						console.log(data);
						data = JSON.parse(data);
						if(data.msg == "ok") {
							$.alert({
								title:"Thông báo",
								content: "Bạn đã lưu thông tin thành công.",
							});
						}
					},error: function(data){
						console.log("Error: " + data);
					}
				});
			} else if(index == 1){
				$.ajax({
					url: "checkout_ok.php",
					type: "POST",
					data: {
						status: "checkout_ok",
						total: "<?=$sum;?>",
						address: address,
						payment_method_id: payment_method_id,
						customer_id: "<?=$_SESSION['customer_id']?>",
						notes: notes,
						coupon_id: coupon_id,
						payment_method:payment_method
					},success: function(data){
						console.log(data);
						data = JSON.parse(data);
						if(data.msg == "ok") {
							$.alert({
								title:"Thông báo",
								content: "Bạn đã đặt hàng thành công. Chúng tôi sẽ tiến hành giao hàng cho bạn",
								buttons: {
									"Ok": function(){
										location.href=`order_complete.php?order_id=${data.order_id}`;
									}
								}
							});
						}
					},error: function(data){
						console.log("Error: " + data);
					}
				});
			}
		}
		
	}
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