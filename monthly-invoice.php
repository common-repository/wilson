<?php 
	$office_id = intval($_GET["office_id"]);
	$office = get_post($office_id);
	$month = intval($_GET["month"]);
	$year = intval($_GET["year"]);
	$functions = new Merch_Stock_Functions();
	$customer = $functions->getUserCustomer();
	$current_role = $functions->getMerchStockRoleName();
	$offices = $functions->getUserOffices();
	$months = array(
		1=>"January",
		2=>"February",
		3=>"March",
		4=>"April",
		5=>"May",		
		6=>"June",
		7=>"July",
		8=>"August",
		9=>"September",
		10=>"October",
		11=>"November",
		12=>"December",
	);
	$orders = json_decode($functions->getMonthlyOrders($month, $year, $office_id),true);
?>

<header class="page-header col-lg-12">
	<h2>		
		Monthly Invoice
	</h2>		

</header>
<section role="main" class="content-body">	
<div class="row">
	<div class="col-lg-12">
		<header class="card-header">
			<div class="card-actions">
			</div>				
			<h2 class="card-title"> <?php echo esc_html($months[$month] . " " . $year . " - " . get_the_title( $office_id )) ?></h2>
		</header>		
		<div class="card-body">	
			<ul>
			<?php 	
				$totalMonth = 0.00;
				$totalProductCostMonth = 0.00;
				$productionCostMonth = 0.00;
				$shippingCostMonth = 0.00;
				foreach ($orders as $key => $order) {
					$invoice = get_post(get_post_meta( $order["ID"], 'invoice_id', true ));
					$invoice_status = get_post_meta($invoice->ID,"status",true);
					
					$office_shipping_costs = get_post_meta($order["ID"],'office_shipping_costs',true);
					$orderTotal = 0.00;
					$orderProductionCosts = 0.00;
					?>
						<li>
							<blockquote class='primary rounded b-thin order-title'>
							<?php 
								$order_output = "<div class='invoice-order row'>";	
								$order_output .= "<div class='col-lg-12 invoice-number'>Order #";	
								$order_output .= $order["ID"] . " (" . $invoice_status . ")";
								$order_output .= "</div></div>";
								echo esc_html($order_output);
							?>
							<ul style="display: none">	
								<li>	
									<div class="row invoice-header">
										<div class="col-lg-2">ID</div>
										<div class="col-lg-3">Product</div>
										<div class="col-lg-1">Aantal</div>
										<div class="col-lg-1">Prijs</div>
										<div class="col-lg-5 text-right">Totaal</div>
									</div>
								</li>
								<?php 	
									foreach ($functions->getOrderlines($order["ID"]) as $key1 => $orderline){	
										$tmpOrderline = json_decode($orderline[0],true);	
										$tmpOrderlineCustom = get_post_custom($tmpOrderline["ID"]);
										$product = get_post($tmpOrderlineCustom["product_id"][0]);
										$amount = $tmpOrderlineCustom["amount"][0];
										$product_price = $tmpOrderlineCustom["product_price"][0];
										$production_costs = $tmpOrderlineCustom["production_costs"][0];
										$total = $amount*$product_price;
										$orderTotal += $total;
										$orderProductionCosts += $amount*$production_costs;
										$totalProductCostMonth += $total;
										$class = "uneven";
										if ($key1 % 2 == 0){
											$class = "even";
										}		
										?>
											<li>
												<?php 	
													$orderline_output = "<div class='invoice-orderline row ".$class."'>";
													$orderline_output .= "<div class='col-lg-2'>";	
													$orderline_output .= $tmpOrderline["ID"];
													$orderline_output .= "</div>";
													$orderline_output .= "<div class='col-lg-3'>";	
													$orderline_output .= get_the_title( $product->ID );
													$orderline_output .= "</div>";	
													$orderline_output .= "<div class='col-lg-1'>";	
													$orderline_output .= $tmpOrderlineCustom["amount"][0];
													$orderline_output .= "</div>";	
													$orderline_output .= "<div class='col-lg-1'>€ ";	
													$orderline_output .= number_format($tmpOrderlineCustom["product_price"][0], 2,".",",");
													$orderline_output .= "</div>";																												
													$orderline_output .= "<div class='col-lg-5 text-right'>€ ";	
													$orderline_output .=  number_format($total, 2,".",",");
													$orderline_output .= "</div>";											
													$orderline_output .= "</div>";
													echo esc_html($orderline_output);
												?>
											</li>
										<?php
									} 
									$orderTotal += $office_shipping_costs;
									$orderTotal += $orderProductionCosts;
									$totalMonth += $orderTotal;
									$productionCostMonth += $orderProductionCosts;
									$shippingCostMonth += $office_shipping_costs;
									echo esc_html("<li><div class='row invoice-total-order'><div class='col-lg-10 text-right'>Shipping Costs</div><div class='col-lg-2 text-right '>€ " . number_format($office_shipping_costs, 2,".",","). "</div></div></li>");		
									echo esc_html("<li><div class='row invoice-total-order'><div class='col-lg-10 text-right'>Costs per unit</div><div class='col-lg-2 text-right '>€ " . number_format($orderProductionCosts, 2,".",","). "</div></div></li>");																	
									echo esc_html("<li><div class='row invoice-total-order'><div class='col-lg-10 text-right'>Order Total</div><div class='col-lg-2 text-right '>€ " . number_format($orderTotal, 2,".",","). "</div></div></li>");
								?>
							</ul>
						</blockquote>
						</li>
					<?php
				}
				echo esc_html("<li class='order-total'><blockquote class='primary rounded b-thin'><div class='row invoice-total-order'><div class='col-lg-10 text-right'>Total product costs this month</div><div class='col-lg-2 text-right '>€ " . number_format($totalProductCostMonth, 2,".",",") . "</div></div></blockquote></li>");					
				echo esc_html("<li class='order-total'><blockquote class='primary rounded b-thin'><div class='row invoice-total-order'><div class='col-lg-10 text-right'>Total shipping costs this month</div><div class='col-lg-2 text-right '>€ " . number_format($shippingCostMonth, 2,".",",") . "</div></div></blockquote></li>");						
				echo esc_html("<li class='order-total'><blockquote class='primary rounded b-thin'><div class='row invoice-total-order'><div class='col-lg-10 text-right'>Total costs per unit this month</div><div class='col-lg-2 text-right '>€ " . number_format($orderProductionCosts, 2,".",",") . "</div></div></blockquote></li>");								
				echo esc_html("<li class='order-total'><blockquote class='primary rounded b-thin'><div class='row invoice-total-order'><div class='col-lg-10 text-right'>Total van deze maand</div><div class='col-lg-2 text-right '>€ " . number_format($totalMonth, 2,".",",") . "</div></div></blockquote></li>");				
			?>
			</ul>
		</div>		
	</div>
</div>

	
</section>