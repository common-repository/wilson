<?php 
	$functions = new Merch_Stock_Functions();
	$user_functions = new Merch_Stock_User_Functions();
	$order_functions = new Merch_Stock_Order_Functions();
	$backorder_request_id = intval($_GET["backorder_request_id"]);
	$backorder_request = get_post($backorder_request_id);
	$notification_functions = new Merch_Stock_Notifications();
	$backorder_request_custom = get_post_custom( $backorder_request_id );		
	$stockline_id = intval($backorder_request_custom["stockline_id"][0]);
	$stockline = get_post($stockline_id);
	$product_id = intval(get_post_meta( $stockline->ID, 'product_id', true ));
	$product = get_post($product_id);
	$status = $backorder_request_custom["status"][0];	
	$user_id = intval(get_post_meta( $backorder_request->ID, 'user_id', true ));
	$customer_id = intval(get_user_meta( $user_id, 'customer_id', true ));
	$notification_functions->updateNotifications($backorder_request_id, 'backorder_request');	
	$custom = get_post_custom($product_i);
	$custom_stock = intval(get_post_custom( $custom["stockline_id"][0] ));
	$production_costs = floatval($custom["production_costs"][0]);	
	$current_role = $user_functions->getMerchStockRoleName();
	$status_changes = json_decode($order_functions->getStatusChanges($backorder_request_id),true);	
	$backorder_status = get_post_meta( $backorder_request_id, 'status', true );
?>
<header class="page-header col-lg-12">
	<h2>
		<?php 	
			echo esc_html(get_the_title($backorder_request_id) . " #" . $backorder_request_id);
		?>
	</h2>
	<div class="right-wrapper text-right">
		<ol class="breadcrumbs">
			<li>
				<a href="admin.php?page=Dashboard">
					<i class="fa fa-home"></i>
				</a>
			</li>
			<li><a href="admin.php?page=backorder-requests"><span>Backorder requests</span></a></li>
			<li><span>Backorder request #<?php echo $backorder_request_id ?></span></li>
		</ol>		
	</div>
</header>
<form action="<?php echo esc_url( admin_url('admin-post.php') ); ?>" method="POST">
<section role="main" class="content-body">	
	<section class="card">
		<header class="card-header">
			<h2 class="card-title"><?php 	
				echo esc_html(get_the_title($backorder_request_id) . " #" . $backorder_request_id);
			?></h2>	
		</header>
		<div class="card-body">
			<div class="form-group row">
				<label class="col-lg-3 control-label text-lg-right pt-2" for="inputReadOnly">ID</label>
				<div class="col-lg-6">
					<label class="col-lg-3 control-label text-lg-left pt-2"><?php echo $backorder_request_id ?></label>
				</div>
			</div>	
			<div class="form-group row">
				<label class="col-lg-3 control-label text-lg-right pt-2" for="inputReadOnly">Customer</label>
				<div class="col-lg-6">
					<label class="col-lg-3 control-label text-lg-left pt-2"><?php echo esc_attr(get_the_title( $customer_id ))  ?></label>
				</div>
			</div>			
			<div class="form-group row">
				<label class="col-lg-3 control-label text-lg-right pt-2" for="inputReadOnly">User</label> 
				<div class="col-lg-6">
					<label class="col-lg-3 control-label text-lg-left pt-2"><?php echo esc_attr($user_functions->getBackorderRequestUser($backorder_request_id)) ?></label>
				</div>
			</div>	
	
			<?php
				
			?>
			<div class="form-group row">
				<label class="col-lg-3 control-label text-lg-right pt-2" for="inputReadOnly">Amount wanted</label>
				<div class="col-lg-6">
					<?php 
						if ($current_role == "headoffice " && $backorder_status == "pending" && is_int(get_post_meta( $backorder_request_id, 'amount', true ))){
							?>
								<label class="col-lg-3 control-label text-lg-left pt-2"><?php echo esc_attr(get_post_meta( $backorder_request_id, 'amount', true )) ?></label>		
							<?php
						}
						else{
							?>
								<label class="col-lg-3 control-label text-lg-left pt-2">Not available</label>		
							<?php							
						}
					?>
					
				</div>
			</div>	
			<div class="form-group row">
				<label class="col-lg-3 control-label text-lg-right pt-2" for="inputReadOnly">Product</label>
				<div class="col-lg-6">
					<label class="col-lg-3 control-label text-lg-left pt-2"><?php echo esc_html(get_the_title( $product_id ) . " " . esc_html(get_post_meta( $stockline_id, 'description', true ))) ?></label>
				</div>
			</div>				
			<!-- PRODUCT INFO -->
			<div class="form-group row">
				<div class="col-lg-3"></div>
				<div class="col-lg-6">
					<section class="card card-featured card-featured-primary" id="product_section_<?php echo $product_id ?>">
						<header class="card-header">
							<h2 class="card-title">
								<?php echo esc_html(get_the_title($product_id)); ?>
							</h2>
							<div class="product-price product-price-<?php echo $product_id ?>">
								<?php 	
									echo esc_html($functions->formatMoney($production_costs));
								?>
							</div>			
				
						</header>						
						<div class="card-body product-container">
							<img class="product-image" src="<?php echo esc_url(get_the_post_thumbnail_url($product_id)) ?>" alt="">
						</div>
					</section>
				</div>
				<div class="col-lg-3"></div>
			</div>
			<hr>
			<div class="form-group row">
				<label class="col-lg-3 control-label text-lg-right pt-2" for="inputReadOnly">Comments (not required)</label>
				<div class="col-lg-6">
					<textarea  class="form-control" name="comments"><?php echo esc_html(get_post_meta( $backorder_request_id, 'comments', true )) ?></textarea>							
				</div>				
			</div>	
			<hr>
										
		</div>
	
			<div class="button-bar">
				<?php 	
					if ($status == "pending" && $current_role=="manager"){
						?>
							<button data-order_id="<?php echo esc_attr($order_id) ?>" name="backorder_status" value="updated" type="submit" id="addToTable" class="btn btn-default">Update backorder</i></button>		
						<?php
					}			
					else{
						if ($status == "Pending" || $status == "pending"){
							?>
								<button data-backorder_id="<?php echo esc_attr($backorder_request_id) ?>" type="submit" name="backorder_status" value="refused" class="btn btn-default">Refuse backorder</button>						<?php 
									echo "<select name='backorder-amount'>";
									foreach ($functions->getPricelines($product_id, "ASC") as $key => $priceline) {
										echo "<option value=".esc_attr(intval($priceline['custom']['amount'][0])).">".esc_attr(intval($priceline['custom']['amount'][0]))." (".esc_html($priceline['product_costs_formatted'])." per unit)</option>";
									}
									echo "</select>";
					 			?>	
								
								<button data-backorder_id="<?php echo esc_attr($backorder_request_id) ?>" type="submit" name="backorder_status" value="accepted" class="btn btn-success">Accept backorder</button>								
							<?php
						}
						else{
							?>
								<button data-order_id="<?php echo esc_attr($order_id) ?>" name="backorder_status" value="updated" type="submit" id="addToTable" class="btn btn-default">Update backorder</i></button>		
							<?php
						}					
					}
				?>	
			</div>	
	</section>
	<section class="card">	
	<?php 
		echo $notification_functions->drawTimeLine($status_changes, $status); 
	?>
	</section>
	
</section>
<input type="hidden" name="action" value="update_backorder_request">			
<input type="hidden" name="backorder_request_id" value="<?php echo esc_attr($backorder_request_id) ?>">		
					
</form> 
 