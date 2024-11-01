<?php 	
	wp_enqueue_media();
?>

<div class="new-item wrap wilson" id="new-customer">
	<form action="<?php echo esc_url( admin_url('admin-post.php') ); ?>" method="POST">
		<input type="hidden" name="action" value="add_customer">

	
	<h1 class='wp-heading-inline'>New customer</h1>
	<div class="new-product-step" id="step1">	
		<div class="number">1.</div>
		<div class="content">
			<h5>
				<span>
					Customer
				</span>
			</h5>	
			<div class="customer-name-container">
				<label>Customer name</label>
				<input name="title" class="title" type="text">
				<label class="obligated">*</label>
			</div>
			<div class="customer-description-container">
				<label>Customer description</label>
				<input name="description" class="description" type="text">
			</div>			
			<div>	
				<label>	Customer image
				</label>
			    <input style="display: none!important" type="text" class="process_custom_images add_product_image" name="selected_logo" value="" placeholder="http://">
			    <button type="button" class="set_custom_logo_3 button" style="vertical-align: middle;"><i class="fa fa-file-image"></i>Select product image</button>					
			</div>
		</div>
	</div>
			<button  type="submit" class="button add-customer next"><i class="fa fa-forward"></i>Next</button>
			<button  class="button add-customer back"><i class="fa fa-backward"></i>Back</button>		
</form>
</div>