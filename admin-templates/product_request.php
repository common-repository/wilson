<?php 	
	$admin_functions = new Merch_Stock_WP_Admin();
	$functions = new Merch_Stock_Functions();
	$product_request_id = intval($_GET['product_request_id']);
	$user_id = intval(get_post_meta($product_request_id,'user_id',true));
	$user = get_user_by( 'ID', $user_id );
	$status = get_post_meta( $product_request_id, 'status', true );
?>
<div class="wrap order wilson">	
	<form action="<?php echo esc_url( admin_url('admin-post.php') ); ?>" method="POST">	
		<input type="hidden" name="action" value="update_product_request_admin">
		<input type="hidden" name="product_request_id" value="<?php echo $product_request_id ?>" >
		<h1 class='wp-heading-inline'>Product request #<?php echo $product_request_id ?></h1>

		<div class="button-bar">
		</div>	
		<div class='filters'>
			<label>&nbsp;</label>
		</div>
		<div class="content backorders">
			<h5>
				<span>
					Request
				</span>
			</h5>
			<div>	
				<label>ID</label>
				<label><?php echo $product_request_id ?></label>
			</div>
			<div>	
				<label>User</label>
				<label><?php echo esc_html($user->first_name) . ' ' . esc_html($user->last_name) ?></label>
			</div>	
			<div>	
				<label>Status</label>
				<label><?php echo $functions->getStatusBubble(get_post_meta( $product_request_id, 'status', true )) ?></label>
			</div>	
			<div>	
				<label>Message</label>
				<label><?php echo esc_html(get_post_meta( $product_request_id, 'message', true )) ?></label>
			</div>
			<div>	
				<label>Comments</label>
				<?php 	
					if ($status=="finished"){
						?>
							<label name="comments">
								<?php echo esc_html(get_post_meta( $product_request_id, 'comments', true )) ?>
							</label>				
						<?php
					}
					else{
						?>
							<textarea name="comments">
								<?php echo esc_html(get_post_meta( $product_request_id, 'comments', true )) ?>
							</textarea>				
						<?php
					}
				?>
			</div>												
		</div>
	<?php 	
		if ($status!=="finished"){
			?>
			<button type="submit" class="button backorder-button next larger-button" name="button" value="finished"><i class="fa fa-check "></i>Finish product request</button>
			<button type="submit" class="button backorder-button larger-button" name="button" value="declined"><i class="fa fa-times"></i>Decline product request</button>
			<?php
		}
	?>		
	</form>
</div>