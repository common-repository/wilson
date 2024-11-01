<?php 
	$functions = new Merch_Stock_Functions();
	$notification_functions = new Merch_Stock_Notifications();
	$user_functions = new Merch_Stock_User_Functions();
	$order_functions = new Merch_Stock_Order_Functions();
	$backorder_id = intval($_GET["backorder_id"]);
	$backorder = get_post($backorder_id);
	$custom = get_post_custom($backorder_id);
	$stockline_id =  intval(get_post_meta( $backorder_id, 'stockline_id', true));
	$stockline = get_post( $stockline_id );
	$product_id = intval(get_post_meta( $stockline_id, 'product_id', true));
	$product = get_post( $product_id );		
	$customer_id = intval(get_post_meta( $product->ID, 'customer_id', true ));
	$customer = get_post( $customer_id );
	$status = get_post_meta( $backorder_id, 'status', true );	
	$notification_functions->updateNotifications($backorder_id, 'backorder');	
	$custom = get_post_custom($product_id);
	$custom_stock = get_post_custom( $custom["stockline_id"][0] );
	$production_costs = floatval($custom["production_costs"][0]);		
	$current_role = $user_functions->getMerchStockRoleName();
	$status = get_post_meta( $backorder_id, 'status', true );
	$status_changes = json_decode($order_functions->getStatusChanges($backorder_id),true);	
?>
<header class="page-header col-lg-12">
	<h2>
		<?php 	
			echo esc_html(get_the_title($backorder_id) . " #" . $backorder_id);
		?>
	</h2>
	<div class="right-wrapper text-right">
		<ol class="breadcrumbs">
			<li>
				<a href="admin.php?page=Dashboard">
					<i class="fa fa-home"></i>
				</a>
			</li>
			<li><a href="admin.php?page=backorders"><span>Backorders</span></a></li>
			<li><span>Backorder #<?php echo esc_html($backorder_id) ?></span></li>
	</ol>		
	</div>
</header>
<section role="main" class="content-body">	
	<section class="card">
		<header class="card-header">
			<h2 class="card-title">
				<?php 	
					echo esc_html(get_the_title($backorder_id) . " #" . $backorder_id);
				?>		
			</h2>
		</header>
		<form action="<?php echo esc_url( admin_url('admin-post.php') ); ?>" method="POST">
		<div class="card-body">
			<div class="form-group row">
				<label class="col-lg-3 control-label text-lg-right pt-2" for="inputReadOnly">ID</label>
				<div class="col-lg-6">
					<label class="col-lg-3 control-label text-lg-left pt-2"><?php echo esc_html($backorder_id) ?></label>
				</div>
			</div>	
			<div class="form-group row">
				<label class="col-lg-3 control-label text-lg-right pt-2" for="inputReadOnly">Customer</label>
				<div class="col-lg-6">
					<label class="col-lg-3 control-label text-lg-left pt-2"><?php echo esc_html(get_the_title( $customer->ID )) ?></label>
				</div>
			</div>	
			<div class="form-group row">
				<label class="col-lg-3 control-label text-lg-right pt-2" for="inputReadOnly">User</label> 
				<div class="col-lg-6">
					<label class="col-lg-3 control-label text-lg-left pt-2"><?php echo esc_html($user_functions->getBackorderUser($backorder_id)) ?></label>					
				</div>
			</div>						
			<div class="form-group row">
				<label class="col-lg-3 control-label text-lg-right pt-2" for="inputReadOnly">Status</label>
				<div class="col-lg-6">
					<label class="col-lg-3 control-label text-lg-left pt-2"><?php echo $functions->getStatusBubble(get_post_meta( $backorder_id, 'status', true )) ?></label>
				</div>
			</div>	
			<div class="form-group row">
				<label class="col-lg-3 control-label text-lg-right pt-2" for="inputReadOnly">Amount</label>
				<div class="col-lg-6">
					<label class="col-lg-3 control-label text-lg-left pt-2"><?php echo esc_html(get_post_meta( $backorder->ID, 'amount', true )) ?></label>
				</div>
			</div>				
			<div class="form-group row">
				<label class="col-lg-3 control-label text-lg-right pt-2" for="inputReadOnly">Product</label>
				<div class="col-lg-6">
					<label class="col-lg-3 control-label text-lg-left pt-2"><?php echo esc_html(get_the_title( $product->ID ) . " " . get_post_meta( $stockline_id, 'description', true )) ?></label>
				</div>
			</div>				
			<!-- PRODUCT INFO -->
			<div class="form-group row">
				<div class="col-lg-3"></div>
				<div class="col-lg-6">
					<section class="card card-featured card-featured-primary" id="product_section_<?php echo esc_attr($product->ID) ?>">
						<header class="card-header">
							<h2 class="card-title">
								<?php echo esc_html(get_the_title($product->ID)); ?>
							</h2>
							<div class="product-price product-price-<?php echo esc_attr($product->ID) ?>">
								<?php 	
									echo esc_html($functions->formatMoney($production_costs));
								?>
							</div>			
				
						</header>						
						<div class="card-body product-container">
							<img class="product-image" src="<?php echo esc_url(get_the_post_thumbnail_url($product->ID)) ?>" alt="">
						</div>
					</section>
				</div>
				<div class="col-lg-3"></div>
			</div>		
			<div class="form-group row">
			
					
			<label class="col-lg-3 control-label text-lg-right pt-2" for="inputReadOnly">Comments (not required)</label>
			<div class="col-lg-6">
				<textarea  class="form-control" name="comments"><?php echo esc_html(get_post_meta( $backorder_id, 'comments', true )) ?></textarea>							
			</div>					
					
			</div>	
			<hr>	
		</div>
	<div class="button-bar">
			<?php 	
				if ( ($status=="Pending"||$status=="pending") && ($current_role=="headoffice") ){
					echo ("<button data-order_id='$backorder_id' name='backorder_status' value='refused' type='submit' class='mb-1 mt-1 mr-1 btn btn-warning'>Refuse backorder</i></button>");
	
					echo ("<select name='backorder-amount'>");
					foreach ($functions->getPricelines($product->ID, "ASC") as $key => $priceline) {
						echo ("<option value=".esc_attr($priceline['custom']['amount'][0]).">".esc_html($priceline['custom']['amount'][0])." (".esc_html($priceline['product_costs_formatted'])." per unit)</option>");
					}
					echo ("</select>");
					echo ("<button data-order_id='$backorder_id' name='backorder_status' value='accepted' type='submit' class='mb-1 mt-1 mr-1 btn btn-success'>Accept backorder</i></button>");
				}
				if ($status!=="Pending"&&$status!=="pending"){
					echo ("<button data-order_id='$backorder_id' type='submit' class='btn btn-primary'>Update backorder</i></button>");
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
 
 											<input type="hidden" name="action" value="update_backorder">			
					<input type="hidden" name="backorder_id" value="<?php echo esc_attr($backorder_id) ?>">		
					
				</form>