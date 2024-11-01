 <?php 	
	$admin_functions = new Merch_Stock_WP_Admin();
	$functions = new Merch_Stock_Functions();
	$order_functions = new Merch_Stock_Order_Functions();
	$customer_id = intval($_GET['customer_id']);
	$url = esc_url(get_the_post_thumbnail_url($customer_id));
	$meta = get_post_custom( $customer_id );
	$post = get_post($customer_id);
	wp_enqueue_media();		
?>
<div class="wrap order wilson">	
	<h1 class='wp-heading-inline'>Customer edit - <?php echo esc_html(get_the_title($customer_id)) ?></h1>
	<div class="button-bar">
	</div>
	<div class='filters'>
		<label>&nbsp;</label>
	</div>
		<form action="<?php echo esc_url( admin_url('admin-post.php') ); ?>" method="POST">
			<input type="hidden" name="action" value="update_wilson_customer">
			<input type="hidden" name="customer_id" value="<?php echo $customer_id ?>">

	<div class="content backorders">
		<h5>
			<span>
				General
			</span>
		</h5>
		<div>	
			<label>ID</label>
			<label><?php echo $customer_id ?></label>
		</div>
		<div>	
			<label>Title</label>
			<input type="text" name="title" value="<?php echo esc_html(get_the_title($customer_id)) ?>">
		</div>
		<div>	
			<label>Description</label>
			<textarea name="description"><?php echo esc_html($post->post_content) ?></textarea>
		</div>
			<div>
				<label>Customer image</label>
				<img class="edit-thumbnail" src="<?php echo $url ?>" /><br>	
			</div>
			<div>	
				<label>&nbsp;</label>
			    <input style="display: none!important" type="text" class="process_custom_images add_product_image" name="selected_logo" value="" placeholder="http://">
			    <button type="button" class="set_custom_logo_3 button" style="vertical-align: middle;"><i class="fa fa-file-image"></i>Select product image</button>	
			    
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
		<hr>	
		<div>	
			<label>Telephone</label>
			<input type="text" name="telephone" value="<?php echo esc_html($meta["telephone"][0]) ?>">
		</div>
		<div>	
			<label>E-mail</label>
			<input type="text" name="email" value="<?php echo esc_html($meta["email"][0]) ?>">
		</div>																
	</div>	

	<button id="" type="submit" class="button next large-button"><i class="fa fa-save"></i>Save</button>
	<button id="" class="button back large-button"><i class="fa fa-ban "></i>Cancel</button>
</form>

</div>