<?php
    include_once("../lib/database.php");
    if(is_get_method()) {
        include_once("include/head.meta.php");
        include_once("include/menu.php");
        // code to be executed get method
?>
<!--html & css section start-->
<div role="main" class="main shop pb-4">
	<div class="container">
		<div class="row justify-content-center">
			<div class="col-lg-8">
				<ul class="breadcrumb breadcrumb-dividers-no-opacity font-weight-bold text-6 justify-content-center my-5">
					<li class="text-transform-none me-2">
						<a href="shop-cart.html" class="text-decoration-none text-color-dark text-color-hover-primary">Shopping Cart</a>
					</li>
					<li class="text-transform-none text-color-dark me-2">
						<a href="shop-checkout.html" class="text-decoration-none text-color-dark text-color-hover-primary">Checkout</a>
					</li>
					<li class="text-transform-none text-color-dark">
						<a href="shop-order-complete.html" class="text-decoration-none text-color-primary">Order Complete</a>
					</li>
				</ul>
			</div>
		</div>

		<div class="row justify-content-center">
			<div class="col-lg-8">
				<div class="card border-width-3 border-radius-0 border-color-success">
					<div class="card-body text-center">
						<p class="text-color-dark font-weight-bold text-4-5 mb-0"><i class="fas fa-check text-color-success me-1"></i> Thank You. Your Order has been received.</p>
					</div>
				</div>
				<div class="d-flex flex-column flex-md-row justify-content-between py-3 px-4 my-4">
					<div class="text-center">
						<span>
							Order Number <br>
							<strong class="text-color-dark">31891</strong>
						</span>
					</div>
					<div class="text-center mt-4 mt-md-0">
						<span>
							Date <br>
							<strong class="text-color-dark">June 17, 2020</strong>
						</span>
					</div>
					<div class="text-center mt-4 mt-md-0">
						<span>
							Email <br>
							<strong class="text-color-dark">abc@abc.com</strong>
						</span>
					</div>
					<div class="text-center mt-4 mt-md-0">
						<span>
							Total <br>
							<strong class="text-color-dark">$30</strong>
						</span>
					</div>
					<div class="text-center mt-4 mt-md-0">
						<span>
							Payment Method <br>
							<strong class="text-color-dark">Cash on Delivery</strong>
						</span>
					</div>
				</div>
				<div class="card border-width-3 border-radius-0 border-color-hover-dark mb-4">
					<div class="card-body">
						<h4 class="font-weight-bold text-uppercase text-4 mb-3">Your Order</h4>
						<table class="shop_table cart-totals mb-0">
							<tbody>
								<tr>
									<td colspan="2" class="border-top-0">
										<strong class="text-color-dark">Product</strong>
									</td>
								</tr>
								<tr>
									<td>
										<strong class="d-block text-color-dark line-height-1 font-weight-semibold">Black Porto Smartwatch <span class="product-qty">x1</span></strong>
										<span class="text-1">COLOR BLACK</span>
									</td>
									<td class="text-end align-top">
										<span class="amount font-weight-medium text-color-grey">$15</span>
									</td>
								</tr>
								<tr>
									<td class="border-top-0 pt-0">
										<strong class="d-block text-color-dark line-height-1 font-weight-semibold">Black Porto Smartwatch <span class="product-qty">x1</span></strong>
										<span class="text-1">COLOR BLACK</span>
									</td>
									<td class="border-top-0 text-end align-top pt-0">
										<span class="amount font-weight-medium text-color-grey">$15</span>
									</td>
								</tr>
								<tr class="cart-subtotal">
									<td class="border-top-0">
										<strong class="text-color-dark">Subtotal</strong>
									</td>
									<td class="border-top-0 text-end">
										<strong><span class="amount font-weight-medium">$431</span></strong>
									</td>
								</tr>
								<tr class="shipping">
									<td class="border-top-0">
										<strong class="text-color-dark">Shipping</strong>
									</td>
									<td class="border-top-0 text-end">
										<strong><span class="amount font-weight-medium">Free Shipping</span></strong>
									</td>
								</tr>
								<tr class="total">
									<td>
										<strong class="text-color-dark text-3-5">Total</strong>
									</td>
									<td class="text-end">
										<strong class="text-color-dark"><span class="amount text-color-dark text-5">$431</span></strong>
									</td>
								</tr>
							</tbody>
						</table>
					</div>
				</div>
				<div class="row pt-3">
					<div class="col-lg-6 mb-4 mb-lg-0">
						<h2 class="text-color-dark font-weight-bold text-5-5 mb-1">Billing Address</h2>
						<ul class="list list-unstyled text-2 mb-0">
							<li class="mb-0">John Doe Junior</li>
							<li class="mb-0">Street Name, City</li>
							<li class="mb-0">State AL 85001</li>
							<li class="mb-0">123 456 7890</li>
							<li class="mt-3 mb-0">abc@abc.com</li>
						</ul>
					</div>
					<div class="col-lg-6">
						<h2 class="text-color-dark font-weight-bold text-5-5 mb-1">Shipping Address</h2>
						<ul class="list list-unstyled text-2 mb-0">
							<li class="mb-0">John Doe Junior</li>
							<li class="mb-0">Street Name, City</li>
							<li class="mb-0">State AL 85001</li>
							<li class="mb-0">123 456 7890</li>
							<li class="mt-3 mb-0">abc@abc.com</li>
						</ul>
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