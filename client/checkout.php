<?php
    include_once("../lib/database.php");
	redirect_if_customer_login_status_false();
	$url = get_url_current_page();
    if(is_get_method()) {
        include_once("include/head.meta.php");
        include_once("include/menu.php");
		$sql = "select * from customer where id = $_SESSION[customer_id] limit 1";
		$result = fetch(sql_query($sql));
		log_a($result);
?>
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

<div class="row">
	<!--<div class="col">
		<p class="mb-1">Returning customer? <a href="#" class="text-color-dark text-color-hover-primary text-uppercase text-decoration-none font-weight-bold" data-bs-toggle="collapse" data-bs-target=".login-form-wrapper">Login</a></p>
	</div>-->
</div>

<!--<div class="row login-form-wrapper collapse mb-5">
	<div class="col">
		<div class="card border-width-3 border-radius-0 border-color-hover-dark">
			<div class="card-body">
				<form action="/" id="frmSignIn" method="post">
					<div class="row">
						<div class="form-group col">
							<label class="form-label text-color-dark text-3">Email address <span class="text-color-danger">*</span></label>
							<input type="text" class="form-control form-control-lg text-4" >
						</div>
					</div>
					<div class="row">
						<div class="form-group col">
							<label class="form-label text-color-dark text-3">Password <span class="text-color-danger">*</span></label>
							<input type="password" value="" class="form-control form-control-lg text-4" >
						</div> 
					</div>
					<div class="row justify-content-between">
						<div class="form-group col-md-auto">
							<div class="custom-control custom-checkbox">
								<input type="checkbox" class="custom-control-input" id="rememberme">
								<label class="form-label custom-control-label cur-pointer text-2" for="rememberme">Remember Me</label>
							</div>
						</div>
						<div class="form-group col-md-auto">
							<a class="text-decoration-none text-color-dark text-color-hover-primary font-weight-semibold text-2" href="#">Forgot Password?</a>
						</div>
					</div>
					<div class="row">
						<div class="form-group col">
							<button type="submit" class="btn btn-dark btn-modern w-100 text-uppercase rounded-0 font-weight-bold text-3 py-3" data-loading-text="Loading...">Login</button>
							<div class="divider">
								<span class="bg-light px-4 position-absolute left-50pct top-50pct transform3dxy-n50">or</span>
							</div>
							<a href="#" class="btn btn-primary-scale-2 btn-modern w-100 text-transform-none rounded-0 font-weight-bold align-items-center d-inline-flex justify-content-center text-3 py-3" data-loading-text="Loading..."><i class="fab fa-facebook text-5 me-2"></i> Login With Facebook</a>
						</div>
					</div>
				</form>
			</div>
		</div>
	</div>
</div>-->

<div class="row">
	<!--<div class="col">
		<p>Have a coupon? <a href="#" class="text-color-dark text-color-hover-primary text-uppercase text-decoration-none font-weight-bold" data-bs-toggle="collapse" data-bs-target=".coupon-form-wrapper">Enter your code</a></p>
	</div>-->
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
<form role="form" class="needs-validation" method="post" action="">
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
					<label class="form-label">Phương thức thanh toán <span class="text-color-danger">*</span></label>
					<div class="custom-select-1">
						<select class="form-select form-control h-auto py-2" name="payment_method" >
							<option value="" selected>Chọn phương thức thanh toán</option>
							<option value="usa">Momo</option>
							<option value="spa">Paypal</option>
							<option value="fra">Thanh toán tại quầy</option>
						</select>
					</div>
				</div>
			</div>
			<!--<div class="row">
				<div class="form-group col">
					<label class="form-label">Street Address <span class="text-color-danger">*</span></label>
					<input type="text" class="form-control h-auto py-2" name="address1" value="" placeholder="House number and street name"  />
				</div>
			</div>
			<div class="row">
				<div class="form-group col">
					<input type="text" class="form-control h-auto py-2" name="address2" value="" placeholder="Apartment, suite, unit, etc..."  />
				</div>
			</div>
			<div class="row">
				<div class="form-group col">
					<label class="form-label">Town/City <span class="text-color-danger">*</span></label>
					<input type="text" class="form-control h-auto py-2" name="city" value=""  />
				</div>
			</div>
			<div class="row">
				<div class="form-group col">
					<label class="form-label">State <span class="text-color-danger">*</span></label>
					<div class="custom-select-1">
						<select class="form-select form-control h-auto py-2" name="state" >
							<option value="" selected></option>
							<option value="ny">Nova York</option>
							<option value="ca">California</option>
							<option value="tx">Texas</option>
							<option value="">Florida</option>
						</select>
					</div>
				</div>
			</div>
			<div class="row">
				<div class="form-group col">
					<label class="form-label">ZIP <span class="text-color-danger">*</span></label>
					<input type="text" class="form-control h-auto py-2" name="zip" value=""  />
				</div>
			</div>-->
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
			<div class="row">
				<div class="form-group col">
					<label class="form-label">Ghi chú</label>
					<textarea class="form-control h-auto py-2" name="notes" rows="5" placeholder="Ghi chú..." value=""></textarea>
				</div>
			</div>
			<button type="button" onclick="createOrder()" class="btn btn-secondary">Lưu thông tin</button>
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
							<span class="amount font-weight-medium text-color-grey"><?=$cart['pi_price']?> VNĐ</span>
						</td>
						<td class="product-quantity">
							<div class="quantity float-none m-0">
								<input style="cursor:default;" readonly type="text" class="input-text qty text" title="Qty" value="<?=$cart['pi_count']?>" name="pi_count" min="1" step="1">
							</div>
						</td>
						<td class="product-subtotal text-end">
							<span class="amount text-color-dark font-weight-bold text-4"><?=$cart['pi_price'] * $cart['pi_count'];?> VNĐ</span>
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
	</div>
</form>

</div>

</div>
<div class="row">
	<div class="form-group col" style="display: flex;justify-content: center;">
		<button onclick="createOrders(1)" class="btn btn-primary btn-rounded btn-px-4 btn-py-2 font-weight-bold" type="submit">Thanh toán ngay</button>
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
	function createOrder(index = 0){
		// save client_info
		let full_name = $("input[name='full_name']").val();
		let email = $("input[name='email']").val();
		let address = $("input[name='address']").val();
		let phone = $("input[name='phone']").val();
		let note = $("input[name='note']").val();
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
				},
				success: function(data){
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
					note: note,
					total: "<?=$sum;?>",
					address: address,
					id: "<?=$_SESSION['customer_id']?>",
				},success: function(data){
					data = JSON.parse(data);
					if(data.msg == "ok") {
						$.alert({
							title:"Thông báo",
							content: "Bạn đã đặt hàng thành công. Bạn sẽ nhận được hàng trong vòng 48h",
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