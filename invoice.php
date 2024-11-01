<?php 
	$functions = new Merch_Stock_Functions();
	$user_functions = new Merch_Stock_User_Functions();	
	$order_functions = new Merch_Stock_Order_Functions();	
	$order_id = intval($_GET['order_id']); 
	$order = get_post($order_id);
	$invoice_id = intval(get_post_meta( $order_id, 'invoice_id', true ));
	$invoice = get_post($invoice_id);
	$invoice_custom = get_post_custom($invoice_id);
	$invoice_status = $invoice_custom["status"];
	$office_id = intval(get_post_meta( $order_id, 'office_id', true ));
	$office = get_post($office_id);
	$office_custom = get_post_custom( $office->ID );
?>
<header class="page-header col-lg-12">
	<h2>
		<?php 	
			echo esc_html(get_the_title($order_id));
			echo esc_html(" #".$order_id);
		?>
	</h2>
	<div class="right-wrapper text-right">
		<ol class="breadcrumbs">
			<li>
				<a href="admin.php?page=Dashboard">
					<i class="fa fa-home"></i>
				</a>
			</li>
			<li><a href="admin.php?page=orders"><span>Packing lists</span></a></li>
			<li><a href="admin.php?page=order&order_id=<?php echo esc_attr($order_id) ?>"><span>Order #<?php echo intval($order_id) ?></span></a></li>
			<li><span>Packing list</span></li>
		</ol>		
	</div>
</header>

<section role="main" class="content-body">
	<form action="<?php echo esc_url( admin_url('admin-post.php') ); ?>" method="POST">	
		<input type="hidden" name="action" value="invoice_pdf">
		<input type="hidden" name="order_id" value="<?php echo esc_attr($order_id) ?>">		
		 <button class="mb-1 mt-1 mr-1 btn btn-primary" type="submit" name="generate_posts_pdf" value="generate">Generate PDF</button>
	</form>
	<div class="plugin-content col-lg-12 nopadding nomargin card">
		<header class="card-header">
			<div class="card-actions">
			</div>				
			<h2 class="card-title">Packing list</h2>
		</header>
		<div class="card-body">
			<div class="invoice">
				<header class="clearfix">
					<div class="row">
						<div class="col-sm-6 mt-3">
							<h2 class="h2 mt-0 mb-1 text-dark font-weight-bold">PACKING LIST</h2>
							<h4 class="h4 m-0 text-dark font-weight-bold">#<?php echo intval($order_id) ?></h4>
						</div>
						<div class="col-sm-6 text-right mt-3 mb-3">
							<address class="ib mr-5">
								<b>ACME Corporation</b>
								<br/>
								1640 Riverside Drive
								<br/>
								Hill Valley
								<br/>
								California
								<br/>
								<a href="mailto:info@acme-corp.com">info@acme-corp.com</a>							
								<br/>
								+33 (0)123 456 789
							</address>
							<div class="ib">
								<img src="<?php echo esc_url(plugin_dir_url( dirname( __FILE__ ) ) . '/wilson/public/images/Acme-corp.png') ?>" width="100" height="100" class="" alt="Merchandise.nl">
							</div>
						</div>
					</div>
					<div class="bill-info">
						<div class="row">
							<div class="col-md-6">
								<div class="bill-to">
									<p class="h5 mb-1 text-dark font-weight-semibold">To:</p>
									<?php 	
										echo "<img src='".esc_url(get_the_post_thumbnail_url($user_functions->getUserCustomer()))."' alt='' class='invoice-image' data-lock-picture='".esc_attr(get_the_post_thumbnail_url($user_functions->getUserCustomer()))."' />"	
									?>
									<address>
										<b><?php echo esc_html(get_the_title($office->ID)) ?></b>
										<?php echo (strlen($office_custom["addressline1"][0])>0 ? '<br/>'.esc_html($office_custom["addressline1"][0]):'') ?>	
										<?php echo (strlen($office_custom["addressline2"][0])>0 ? '<br/>'.esc_html($office_custom["addressline2"][0]):'') ?>	
										<?php echo (strlen($office_custom["addressline3"][0])>0 ? '<br/>'.esc_html($office_custom["addressline3"][0]):'') ?>	
										<?php echo (strlen($office_custom["postal_code"][0])>0 ? '<br/>'.esc_html($office_custom["postal_code"][0]):'') ?>						
										<?php echo (strlen($office_custom["city"][0])>0 ? '<br/>'.esc_html($office_custom["city"][0]):'') ?>									
										<?php echo (strlen($office_custom["region"][0])>0 ? '<br/>'.esc_html($office_custom["region"][0]):'') ?>									
										<?php echo (strlen($office_custom["county"][0])>0 ? '<br/>'.esc_html($office_custom["county"][0]):'') ?>									
										<?php echo (strlen($office_custom["country"][0])>0 ? '<br/>'.esc_html($office_custom["country"][0]):'') ?>	
									</address>
								</div>
							</div>
						</div>
					</div>				
				</header>
			</div>
			<?php 
				$order_output = "<div class='invoice-order row'>";	
				$order_output .= "<div class='col-lg-12 invoice-number'>Packing list #";	
				$order_output .= $order_id;
				$order_output .= "</div></div>";
				echo $order_output;
					?>
						<div class="total_carton">
							Total carton(s): <?php echo esc_html(json_decode($order_functions->getOrderShippingCostsV2($order_id, false, false, false, true),true)[1]) ?>
						</div>
					<?php				
			?>

			<ul >	
				<li>	
					<div class="row invoice-header">
						<div class="col-lg-2">ID</div>
						<div class="col-lg-3">Product</div>
						<div class="col-lg-7 text-right">Amount</div>
					</div>
				</li>
				<?php 	
					foreach ($functions->getOrderlines($order->ID) as $key1 => $orderline){	
						$tmpOrderline = json_decode($orderline[0],true);	
						$tmpOrderline_id = intval($tmpOrderline["ID"]);
						$tmpOrderlineCustom = get_post_custom($tmpOrderline_id);
						

						$amount = intval($tmpOrderlineCustom["amount"][0]);
						$product_price = floatval($tmpOrderlineCustom["product_price"][0]);
						$production_costs = floatval($tmpOrderlineCustom["production_costs"][0]);
						$stockline_id = intval($tmpOrderlineCustom["stockline_id"][0]);
						$stockline = get_post($stockline_id);
						$product = get_post(get_post_meta( $stockline_id, 'product_id', true ));
						$total = floatval($amount*$production_costs);
						$orderTotal += $total;
						$orderProductionCosts += floatval($amount*$production_costs);
						$totalProductCostMonth += floatval($total);
						$class = "uneven";
						if ($key1 % 2 == 0){
							$class = "even";
						}		
						?>
							<li>
								<?php 	
									$orderline_output = "<div class='invoice-orderline row ".$class."'>";
									$orderline_output .= "<div class='col-lg-2'>";	
									$orderline_output .= $tmpOrderline_id;
									$orderline_output .= "</div>";
									$orderline_output .= "<div class='col-lg-3'>";	
									$orderline_output .= esc_html(get_the_title( $product->ID ));									
									if ($stockline_id>0){
										$orderline_output .= " " . esc_html(get_post_meta( $stockline_id, 'description', true ));
									}
									$orderline_output .= "</div>";	
									$orderline_output .= "<div class='col-lg-7 text-right'>";	
									$orderline_output .= intval($tmpOrderlineCustom["amount"][0]);
									$orderline_output .= "</div>";											
									$orderline_output .= "</div>";
									echo $orderline_output;
								?>
							</li> 
						<?php
					} 
					$orderTotal += floatval($office_shipping_costs);
					$totalMonth +=  floatval($orderTotal);
					$productionCostMonth +=  floatval($orderProductionCosts);
					$shippingCostMonth +=  floatval($office_shipping_costs);
				?>
			</ul>
		</div>
	</div>
</section>