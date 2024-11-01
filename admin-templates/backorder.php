<?php 	
	$admin_functions = new Merch_Stock_WP_Admin();
	$functions = new Merch_Stock_Functions();
	$backorder_id = intval($_GET['backorder_id']);
	$stockline_id = intval(get_post_meta( $backorder_id, 'stockline_id', true ));
	$product_id = intval(get_post_meta( $stockline_id, 'product_id', true ));
	$user_id = intval(get_post_meta( $backorder_id, 'user_id', true ));
	$customer_id = intval(get_user_meta( $user_id, 'customer_id', true ));
	$user = get_user_by( 'ID', $user_id );
	$status = get_post_meta( $backorder_id, 'status', true );
?>
<div class="wrap order wilson">	
	<form action="<?php echo esc_url( admin_url('admin-post.php') ); ?>" method="POST">	
	<h1 class='wp-heading-inline'>Backorder #<?php echo $backorder_id ?></h1>

	<div class="button-bar">
	</div>	
	<div class='filters'>
		<label>&nbsp;</label> 
	</div>
	<div class="content backorders">
		<h5>
			<span>
				Backorder
			</span>
		</h5>
		<div>	
			<label>ID</label>
			<label><?php echo $backorder_id ?></label>
		</div>
		<div>	
			<label>Customer</label>
			<label>
				<?php 	
					echo '<a href="'.esc_url(admin_url( 'admin.php?page=admin_customer&customer_id='.$customer_id  )) .'">' . esc_html(get_the_title( $customer_id  )) . "</a>";					
				?>
			</label>		
		</div>
		<div>	
			<label>User</label>
			<label>	
				<?php 	
					echo esc_html($user->first_name) . " " . esc_html($user->last_name);
				?>
			</label>
		</div>
		<div>	
			<label>Status</label>
			<label>
				<?php
					echo $functions->getStatusBubble($status);
				?> 
			</label>
		</div>
		<div>	
			<label>Amount</label>
			<label>
				<?php 	
					if ($status=='finished'||$status=='Finished'){
						?>
							<label><?php echo intval(get_post_meta($backorder_id, 'amount',true)) ?></label>
							
						<?php		
					}
					else{
						?>
							<input type="" name="amount" value="<?php echo intval(get_post_meta($backorder_id, 'amount',true)) ?>">
						<?php		
					}
				?>
				
			</label>
		</div>	
		<hr>	
		<div>	
			<label>Product</label>
			<div class="backorder-product-container">	
				<h1>	
				<?php
					echo esc_html(get_the_title( $product_id ));
				?>
				</h1>
				<img class="edit-thumbnail" src="<?php echo esc_url(get_the_post_thumbnail_url( $product_id, 'post-thumbnail' )); ?>">
				
			</div>
		</div>		
		<hr>	
		<div>	
			<label>Comments</label>
			<label>
				<?php
					echo esc_html(get_post_meta($backorder_id, 'comments',true));
				?>
			</label>
		</div>		
		<hr>	

	</div>	
				<input type="hidden" name="action" value="accept_backorder">
			<input type="hidden" name="backorder_id" value="<?php echo $backorder_id ?>">
	<?php 	
		if ($status!=="finished"){
			?>
			<button type="submit" class="button backorder-button next" name="button" value="finished"><i class="fa fa-check "></i>Finish backorder</button>
			<button type="submit" class="button backorder-button" name="button" value="declined"><i class="fa fa-times"></i>Decline backorder</button>
			<?php
		}
	?>


			
		</form>