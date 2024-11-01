 <?php 	
	$admin_functions = new Merch_Stock_WP_Admin();
	$functions = new Merch_Stock_Functions();
	$order_functions = new Merch_Stock_Order_Functions();
	$office_id = intval($_GET['office_id']);
	$customer_id = intval(get_post_meta( $office_id, 'customer_id', true ));
	$orders = json_decode($functions->getOrders(-1, null, $office_id));
	$meta = get_post_custom( $office_id );
	$customers = json_decode($admin_functions->getCustomers(),1);
	$post = get_post($office_id);

?>
<div class="wrap order wilson">	
	<h1 class='wp-heading-inline'>Office edit - <?php echo esc_html(get_the_title($office_id)) ?></h1>
	<div class="button-bar">
	</div>
	<div class='filters'>
		<label>&nbsp;</label>
	</div>
		<form action="<?php echo esc_url( admin_url('admin-post.php') ); ?>" method="POST">
			<input type="hidden" name="action" value="update_wilson_office">
			<input type="hidden" name="office_id" value="<?php echo $office_id ?>">

	<div class="content backorders">
		<h5>
			<span>
				General
			</span>
		</h5>
		<div>	
			<label>ID</label>
			<label><?php echo $office_id ?></label>
		</div>
		<div>	
			<label>Title</label>
			<input type="text" name="title" value="<?php echo esc_html(get_the_title($office_id)) ?>">
		</div>
		<div>	
			<label>Description</label>
			<textarea name="description"><?php echo esc_html($post->post_content) ?></textarea>
		</div>		
		<div>	
			<label>Customer</label>
			<label>
				<select name="select_customer" id="select_customer">
					<?php 
						foreach ($customers as $customer){
							$id = $customer['ID'];
							$title = get_the_title( $id );
							if ($id==$customer_id){
								echo "<option selected='selected' value='$id'>$title</option>";	
							}
							else{
								echo "<option value='$id'>$title</option>";	
							}
							
						}
					?>
				</select>
			</label>		
		</div>
	</div>	
	<div class="content backorders">

		<h5>
			<span>
				Shipping
			</span>
		</h5>
		<div>	
			<label>Shipping costs per box</label>			
			<input type="text" name="shipping_box_price" value="<?php echo esc_html($meta["shipping_box_price"][0]) ?>">
		</div>
		<div>	
			<label>Shipping box weight</label>			
			<input type="text" name="shipping_box_weight" value="<?php echo esc_html($meta["shipping_box_weight"][0]) ?>">
		</div>		
	</div>
	<div class="content backorders">

		<h5>
			<span>
				Address
			</span>
		</h5>
		<div>	
			<label>Addressline #1</label>
			<input type="text" name="addressline1" value="<?php echo esc_html($meta["addressline1"][0]) ?>">
		</div>
		<div>	
			<label>Addressline #2</label>
			<input type="text" name="addressline2" value="<?php echo esc_html($meta["addressline2"][0]) ?>">
		</div>
		<div>	
			<label>Addressline #3</label>
			<input type="text" name="addressline3" value="<?php echo esc_html($meta["addressline3"][0]) ?>">
		</div>
		<div>	
			<label>Postal code</label>
			<input type="text" name="postal_code" value="<?php echo esc_html($meta["postal_code"][0]) ?>">
		</div>
		<div>	
			<label>City</label>
			<input type="text" name="city" value="<?php echo esc_html($meta["city"][0]) ?>">
		</div>
		<div>	
			<label>Region</label>
			<input type="text" name="region" value="<?php echo esc_html($meta["region"][0]) ?>">
		</div>
		<div>	
			<label>County</label>
			<input type="text" name="county" value="<?php echo esc_html($meta["county"][0]) ?>">
		</div>
		<div>	
			<label>Country</label>
			<input type="text" name="country" value="<?php echo esc_html($meta["country"][0]) ?>">
		</div>														
	</div>	
			<button id="step2-next-button-office" type="submit" class="button add-office next"><i class="fa fa-forward"></i>Next</button>
			<button id="step2-back-button-office" class="button add-office back"><i class="fa fa-backward"></i>Back</button>	
</form>