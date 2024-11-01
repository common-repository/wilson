<?php 
	$functions = new Merch_Stock_Functions();
	$user_functions = new Merch_Stock_User_Functions();
	$order_functions = new Merch_Stock_Order_Functions();
	$product_request_id = intval($_GET["product_request_id"]);
	$product_request = get_post($product_request_id);
	$notification_functions = new Merch_Stock_Notifications();		
	$status = get_post_meta( $product_request_id, 'status', true );
	$current_role = $user_functions->getMerchStockRoleName();
	$status_changes = json_decode($order_functions->getStatusChanges($product_request_id),true);	
?>
<header class="page-header col-lg-12">
	<h2>
		<?php 	
			echo esc_html(get_the_title($product_request_id) . " #" . $product_request_id);
		?>
	</h2>
	<div class="right-wrapper text-right">
		<ol class="breadcrumbs">
			<li>
				<a href="admin.php?page=Dashboard">
					<i class="fas fa-home"></i>
				</a>
			</li>
			<li><a href="admin.php?page=product-requests"><span>Product requests</span></a></li>
			<li><span>Product request #<?php echo $product_request_id ?></span></li>
		</ol>		
	</div>
</header>
<form action="<?php echo esc_url( admin_url('admin-post.php') ); ?>" method="POST">
	<input type="hidden" name="action" value="update_product_request">
	<input type="hidden" name="product_request_id" value="<?php echo esc_attr($product_request_id) ?>">
<section role="main" class="content-body">	
	<section class="card">
		<header class="card-header">
			<h2 class="card-title"><?php 	
				echo esc_html(get_the_title($product_request_id) . " #" . $product_request_id);
			?></h2>	
		</header>
		<div class="card-body">
			<div class="form-group row">
				<label class="col-lg-3 control-label text-lg-right pt-2" for="inputReadOnly">ID</label>
				<div class="col-lg-9">
					<label class="col-lg-12 control-label text-lg-left pt-2"><?php echo esc_html($product_request_id) ?></label>
				</div>
			</div>				
			<div class="form-group row">
				<label class="col-lg-3 control-label text-lg-right pt-2" for="inputReadOnly">User</label>
				<div class="col-lg-9">
					<label class="col-lg-12 control-label text-lg-left pt-2"><?php echo esc_html($user_functions->getUserNameByID(get_post_meta( $product_request_id, 'user_id', true ))) ?></label>
				</div>
			</div>							
			<div class="form-group row">
				<label class="col-lg-3 control-label text-lg-right pt-2" for="inputReadOnly">Status</label>
				<div class="col-lg-9">
					<label class="col-lg-12 control-label text-lg-left pt-2"><?php echo $functions->getStatusBubble(esc_html($status)) ?></label>
				</div>
			</div>										
			<div class="form-group row">
				<label class="col-lg-3 control-label text-lg-right pt-2" for="inputReadOnly">Message</label>
				<div class="col-lg-9">
					<label class="col-lg-12 control-label text-lg-left pt-2"><?php echo esc_html(get_post_meta( $product_request_id, 'message', true )) ?></label>
				</div>
			</div>	
			<hr>
				<div class="form-group row">
					<label class="col-lg-3 control-label text-lg-right pt-2" for="inputReadOnly">Comments</label>
					<div class="col-lg-6">
						<textarea  class="form-control" name="comments"><?php echo esc_html(get_post_meta( $product_request_id, 'comments', true )) ?></textarea>							
					</div>				
				</div>	
			<hr>
								
		</div>
			<div class="button-bar">
			<?php 	
				if ($status=="pending" && $current_role=="headoffice"){
					?>
						<button data-order_id="<?php echo esc_attr($order_id) ?>" type="submit" name="change_status" value="refused" class="btn btn-default">Refuse</button>										
							
						<button data-order_id="<?php echo esc_attr($order_id) ?>" type="submit" name="change_status" value="accepted" class="btn btn-success">Accept</button>
					<?php
				}
				else{
					?>
						<button data-order_id="<?php echo esc_attr($order_id) ?>" type="submit" class="btn btn-default">Update product request</i></button>
					<?php
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
</form>