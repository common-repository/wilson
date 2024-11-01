<?php 
	$functions = new Merch_Stock_Functions();
	$user_functions = new Merch_Stock_User_Functions();
	$notification_functions = new Merch_Stock_Notifications();
	$order_functions = new Merch_Stock_Order_Functions();
	$order_id = intval($_GET['order_id']);
	$order_functions->touchOrder($order_id);
	$has_backorderlines = sanitize_text_field($_GET['has_backorderlines']);
	$office_order_id = intval(get_post_meta( $order_id, 'office_id', true ));
	$orderlines = $functions->getOrderLines($order_id);	
	$order_status = get_post_meta( $order_id, 'status', true );
	$status_changed = $order_functions->getOrderDate($order_id);
	$current_role = $user_functions->getMerchStockRoleName();
	$shipping_box_price = number_format(get_post_meta( $order_id,'shipping_box_price',true ), 2,".",",");
	$notification_functions->updateNotifications($order_id, 'order');	
	$status_changes = json_decode($order_functions->getStatusChanges($order_id),true);
	$errors = json_decode(sanitize_text_field($_GET["errors"]),true);
?>
<header class="page-header col-lg-12">
	<h2>
		<?php 	
			echo esc_html(get_the_title($order_id)) . " #" . $order_id . " - " . esc_html(get_the_title( $office_order_id ));
		?>
	</h2>
	<div class="right-wrapper text-right">
		<ol class="breadcrumbs">
			<li>
				<a href="admin.php?page=Dashboard">
					<i class="fa fa-home"></i>
				</a>
			</li>
			<li><a href="admin.php?page=orders"><span>Orders</span></a></li>
			<li><span>Order #<?php echo $order_id . " - " . esc_html(get_the_title( $office_order_id )); ?></span></li>
		</ol>		
	</div>
</header>
<section role="main" class="content-body">	
	<form action="<?php echo esc_url( admin_url('admin-post.php') ); ?>" id="order_form" method="POST"> 
		<input type="hidden" name="action" value="update_order">
		<input type="hidden" name="order_id" value="<?php echo esc_attr($order_id) ?>">
		<input type="hidden" name="order-status" id="hidden-order-status" value="<?php echo esc_html($order_status) ?>">	
		<div class="plugin-content col-lg-12 nopadding nomargin card">
			<header class="card-header">
				<div class="card-actions">
				</div>				
				<h2 class="card-title">Orderlines</h2>
			</header>	
			<div class="card-body">
				<div class="col-lg-12">
				<table class="table table-bordered table-striped mb-0 dataTable no-footer">
					<thead>
						<tr>
							<th>ID</th>
							<th>Product</th>					
							<th>Type/Maat</th>					
							<th>Amount</th>
							<th>Costs per unit</th>						
							<th>Total</th>
						</tr>
					</thead>
					<?php
						$output = "";				
						$total_order = 0.00;
						$production_costs_order = 0.00;
						$actions = "";
						$total_products = 0;
						foreach ($orderlines as $key => $orderline) {	
							$actions = "";			
							$orderline_id = intval(json_decode($orderline[0],true)["ID"]);
							$priceline_id = intval(get_post_meta( $orderline_id, 'priceline_id', true ));
							$stockline_id = intval(get_post_meta( $orderline_id, 'stockline_id', true ));
							$product_id = intval(get_post_meta( $stockline_id, 'product_id',true ));
							$stockline = get_post($stockline_id);		
							$pricelines = $functions->getPricelines($product_id, 'ASC');
							$product_stock = $functions->getProductStock($product_id, $stockline_id);
							$orderlineObject = get_post($orderline_id);
							$order = get_post(get_post_meta( $orderline_id, 'order_id', true ));
							$order_id = intval($order->ID);
							$amount = (int)$orderline[1]["amount"];
							$product = get_post($product_id);
							$product_price =  floatval(get_post_meta( $orderline_id, 'product_price', true ));				
							$production_costs =  floatval(get_post_meta( $orderline_id, 'production_costs', true ));				
							$product_weight = intval(get_post_meta( $product_id, 'product_weight', true ));
							$total_orderline = floatval($amount * $product_price);
							$total_order += $total_orderline;
							if ($amount>intval($product_stock["stock"])){
								$output .= "<tr class='orderline-line not-enough-stock'>";	
							}
							else{
								$output .= "<tr class='orderline-line'>";		
							}
							$output .= "<td>";
							$output .= $orderline_id;
							$output .= "</td>";				
							$output .= "<td>";
							$output .= get_the_title( $product_id  );
							$output .= "</td>";
							$output .= "<td>";
							$output .= get_post_meta( $stockline_id, 'description', true );
							$output .= "</td>";		
							$output .= "<td>";
							if (get_post_meta( $order_id, 'status', true ) == 'Init'){	
								$output .= "<select name='changed-amounts[".$orderline_id."]'  class='add-product-amount'>";	
								$output .= "<option value='0'>0</option>";
										$order_per = intval(get_post_meta( $product_id, 'order_per', true ));
										$order_minimal = intval(get_post_meta( $product_id, 'minimal_order_amount', true ));
										for ($i=0; $i < 100 ; $i++) { 
											if ($amount==$order_minimal){
												$output .= "<option selected value='".$order_minimal."'>".$order_minimal."</option>";	
											}
											else{
												$output .= "<option value='".$order_minimal."'>".$order_minimal."</option>";	
											}
											
											$order_minimal += $order_per;
										}							
								$output .= "</select>";													
							}
							else{						
								$output .= "<div class='visible-input'>".number_format($amount,0,'.',',')."</div>";
							}
							if (in_array($stockline_id, $errors)){
								$output .= "<br/><font style='color:red;font-weight:bold'>sorry, not enough in stock at the moment (probably someone else is ordering the same product at the same time). You can order maximimal " . intval(intval($product_stock['stock'])-intval($functions->getProductStock($product->ID, $stockline_id)["init"])+intval($functions->productsOnOrder($order_id, $stockline_id))) . " right now.</font>";
							}	
							$output .= "</td>"; 
							$output .= "<td><span>";
							$output .= $functions->formatMoney($production_costs);
							$output .= "</span></td>"; 										
										
							$output .= "<td><span>";
							$output .= $functions->formatMoney(($production_costs*$amount));
							$output .= "</span></td>"; 
								$output .= "</tr>";			
							$total_order += floatval(($production_costs*$amount));
							$total_products += intval($amount);
						}		
						$output .= "</tr>";
						echo $output;
					?>
					<tr class="products_total">
						<td></td>
						<td><b>Products total</b></td>
						 
						<td></td>
						<td><?php echo intval($total_products) ?></td>
						<td></td>
						<td><?php echo esc_html($functions->formatMoney($total_order)) ?></td>
						
					</tr>				
					<tr class="shipping_costs">
						<td></td>
						<td><b>Shipping costs</b></td>
						
						<td></td>
						<td><?php echo esc_html(json_decode($order_functions->getOrderShippingCostsV2($order_id, false, false, false, true),true)[1]) ?></td>
						<td><?php echo esc_html($functions->formatMoney(json_decode($order_functions->getOrderShippingCostsV2($order_id, false, false, false, true),true)[3])) ?></td>
						<td><?php echo esc_html(json_decode($order_functions->getOrderShippingCostsV2($order_id, false, false, false, true),true)[0]) ?></td>
						
					</tr>
					<tr class="total" >
						<td></td>
						<td><b>Total order</b></td>					
						<td></td>
						<td></td>
						<td></td>
						<td ><span><b><?php echo esc_html($functions->formatMoney($total_order+json_decode($order_functions->getOrderShippingCostsV2($order_id, false, false, false, true),true)[2])) ?></b></span></td>
					</tr>		
				</table>
					<div class="plugin-content col-lg-12 nopadding nomargin card">
				<div class="card-body row">
				<?php 
					if ($current_role=="manager" && $order_status=="Init"){
						?>
							<div class="col-lg-6">
								<b>Comments (not required)</b>
								<textarea  class="form-control" name="comments"><?php echo esc_html(get_post_meta( $order_id, 'comments', true )) ?></textarea>							
							</div>	
							<div class="col-lg-6">
								<b>References (not required)</b>
								<textarea  class="form-control" name="references"><?php echo esc_html(get_post_meta( $order_id, 'references', true )) ?></textarea>							
							</div>	
						<?php
					}
					else{
						?>
		
							<div class="col-lg-4">
								<b>Comments</b>
								<br>
								<label name="comments"><?php echo esc_html(get_post_meta( $order_id, 'comments', true )) ?></label>							
							</div>	
							<div class="col-lg-4">
								<b>References</b>
								<br>
								<label name="references"><?php echo esc_html(get_post_meta( $order_id, 'references', true )) ?></label>							
							</div>	
							<div class="col-lg-4">
								<b>Tracking number</b>
								<br>
								<label name="references"><?php echo esc_html(get_post_meta( $order_id, 'tracking_number', true )) ?></label>							
							</div>												
						<?php					
					}
				?>
					
				</div>
						<?php 	
							if ($user_functions->currentUserCanChangeOffice($order_id)){
								?>
									<div class="card-body">	
										<div class="col-lg-12">
											Office
											<select   data-plugin-selectTwo class="form-control populate" name="office_id"  tabindex="-1" aria-hidden="true">
												<?php 	
													foreach ($user_functions->getUserOffices() as $office){						
														if ($office->ID==$office_order_id){
															echo esc_html("<option selected='selected' value='".$office->ID."'  >".get_the_title( $office->ID )."</option>");
														}
														else{
															echo esc_html("<option value='".$office->ID."'  >".get_the_title( $office->ID )."</option>");
														}
														
													}
												?>
											</select>
										</div>
									</div>
								<?php
							}
							if ($order_status=="Init" && ($current_role=="manager" || $current_role=="headoffice") ){
								echo '<button data-order_id="'.$order_id.'" type="submit" name="order_status" value="Init" id="addToTable" class="btn btn-default">Update order</i></button><br/>';
							}						
						?>
				</div>		
			<br>
			</div>
			<div class="button-bar">
			<?php
				if ($current_role == "admin"){
					?>		
						<?php 	
							if ($order_status=="Submitted"){
								?>							
									<b><label>Tracking Number</label></b>
									<input class='form-control' type="text" id="tracking_number" name="tracking_number"/>
									<div>	
									<button class="change-order-status-button change-to-shipped mb-1 mt-1 mr-1 btn btn-primary" type="submit" name="order_status" value="Shipped">Ship order</button>			
											<a href='<?php echo esc_url(admin_url( 'admin.php?page=invoice&order_id='.$order_id )) ?>' class='on-default edit-row'><button type='button' class='mb-1 mt-1 mr-1 btn btn-default'>Packing list</button></a>
									</div>
								<?php
					
							}	
							else{
									echo "&nbsp;<a href='" . esc_url(admin_url( 'admin.php?page=invoice&order_id='.$order_id )) .  "' class='on-default edit-row'><button type='button' class='mb-1 mt-1 mr-1 btn btn-default'>Packing list</button></a>";
								}					
						?>
					<?php
				}
				else{
					if ($order_status!="Init"){
						echo "&nbsp;<a href='" . esc_url(admin_url( 'admin.php?page=invoice&order_id='.$order_id )) .  "' class='on-default edit-row'><button type='button' class='mb-1 mt-1 mr-1 btn btn-default'>Packing list</button></a> ";
					}
					else{
						echo '<button data-order_id="'.$order_id.'" type="submit" name="order_status" value="Submitted" id="addToTable" class="btn btn-primary">Submit order</i></button>';
					}					
				}
			?>
			</div>
		</div>
		</div>	
	</form>
	<section class="card">	
		<?php 
			echo $notification_functions->drawTimeLine($status_changes, $order_status);
		?>
	</section>
</section>
