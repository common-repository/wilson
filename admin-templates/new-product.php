<?php  
	$functions = new Merch_Stock_Functions();
	$admin_functions = new Merch_Stock_WP_Admin();
	$user_functions = new Merch_Stock_User_Functions();
	$order_functions = new Merch_Stock_Order_Functions();
	$customers = json_decode($admin_functions->getCustomers(),1);
	wp_enqueue_media(); 
?>
<div id="new_customer_container">
	<div class="content">
		<h5>
			<span>
				New Customer
			</span>
		</h5>			
		<div>
			<label>Customer name</label>
			<input id="new_customer_name" type="text" name="">
			<label class="obligated">*</label>
		</div>		
		<div>
			<label>Customer description</label>
			<input id="new_customer_description" type="text" name="">
			<label class="obligated">*</label>
		</div>			
		<div>
			<label>Customer image</label>
		    <input type="text" class="process_custom_images add_customer_image"  name="selected_logo_2" value="" placeholder="http://">
		    <button class="set_custom_logo_2 button" style="vertical-align: middle;"><i class="fa fa-file-image"></i>Select product image</button>
		</div>					
		<button id="add-customer-next-button" class="button next"><i class="fa fa-forward "></i>Next</button> 
		<button id="add-customer-back-button" class="button back"><i class="fa fa-backward"></i>Back</button>	
	</div>		
</div>
<div class="new-item wrap wilson"  id="new-product">
	<h1 class='wp-heading-inline'>New product</h1>
	<!-- <div class="progress-bar"></div> -->
	<input type="hidden" id="new_product_id" value="0" name=""/>
	

	<div class="new-product-step" id="step1">		
		<div class="number">1.</div>
		<div class="content">
			<h5>
				<span>
					Select Customer
				</span>
			</h5>
			<div>	
				<label>Customer</label>
				<select id="select_customer">
					<?php 
						foreach ($customers as $customer){
							$id = intval($customer['ID']);
							$title = esc_html(get_the_title( $id ));
							echo "<option value='$id'>$title</option>";
						}
					?>
				</select>
				<label class="obligated">*</label>				
			</div>
			<button id="new_customer" class="button"><i class="fa fa-plus"></i>New Customer</button>
			<button id="step1-next-button-product" class="button add-product next large-button"><i class="fa fa-forward"></i>Next</button>
		</div>
	</div>

	<div class="new-product-step original" id="step1-original">
		<div class="number">1.</div>
		<div class="content"></div>
	</div>

	<div class="new-product-step" id="step2">		
		<div class="number">2.</div>
		<div class="content">
			<h5>
				<span>
					Product properties
				</span>
			</h5>
			<div class="product-name-container">
				<label>Product name</label>
				<input class="product_name" type="text">
				<label class="obligated">*</label>
			</div>
			<div class="article-number-container">
				<label>Article number</label>
				<input class="article_number" type="text">
			</div>	
			<div class="product-description-container">
				<label>Product description</label>
				<textarea class="product_description" rows="4" cols="50"></textarea>
			</div>		
			<div>
			    <input type="text" class="process_custom_images add_product_image" name="selected_logo" value="" placeholder="http://">
			    <button class="set_custom_logo button" style="vertical-align: middle;"><i class="fa fa-file-image"></i>Select product image</button>			
			</div>							
			<button id="step2-next-button-product" class="button add-product next large-button"><i class="fa fa-forward"></i>Next</button>
			<button id="step2-back-button-product" class="button add-product back large-button"><i class="fa fa-backward"></i>Back</button>
		</div>
	</div>	
	<div class="new-product-step original" id="step2-original">
		<div class="number">2.</div>
		<div class="content">
			
		</div>
	</div>	


	<div class="new-product-step" id="step3">		
		<div class="number">3.</div>
		<div class="content">
			<h5>
				<span>
					Product variables
				</span>
			</h5>			
			<div>
				<label>Product weight</label>
				<input id="product_weight" type="text" name="">
				<label class="obligated">*</label>
			</div>		
			<div>
				<label>Minimal order amount</label>
				<input id="minimal_order_amount" type="text" name="">
				<label class="obligated">*</label>
			</div>			
			<div>
				<label>Order per</label>
				<input id="order_per" type="text" name="">
				<label class="obligated">*</label>
			</div>			
			<div>
				<label>Order term</label>
				<input id="order_term" type="text" name="">
				<label class="obligated">*</label>
			</div>			
			<div>
				<label>Warning amount</label>
				<input id="warning_amount" type="text" name="">
				<label class="obligated">*</label>
			</div>																
			<button id="step3-next-button-product" class="button next large-button"><i class="fa fa-forward"></i>Next</button> 
			<button id="step3-back-button-product" class="button back large-button"><i class="fa fa-backward"></i>Back</button>
		</div>			
	</div>	
	<div class="new-product-step original" id="step3-original">
		<div class="number">3.</div>
		<div class="content"></div>
	</div>	

	<div class="new-product-step" id="step4">		
		<div class="number">4.</div>
		<div class="content">
			<h5>
				<span>
					Product variations
				</span>
			</h5>	
			<div class="product-variations">	
				<div class="product-variation">	
					<h4 class="title">	
						Product variation #1
					</h4>					
					<div class="description-container">
						<label>Description</label>
						<input class="description" type="text" name="">
					</div>
					<div class="stock-container">
						<label>Stock</label>
						<input class="stock" type="integer" name="">
					</div>
					<div class="delete-container">	
						<!-- <input class="delete-product-variation" type="button" value="Delete" name=""> -->
						<button class="button delete-product-variation"><i class="fa fa-trash"></i>Delete variation</button>
					</div>
				</div>
			</div>
			<div class="add-variation">	
				<!-- <input class="add-variation-button" type="button" value="Add variation" name=""> -->
				<button class="button add-variation-button"><i class="fa fa-plus"></i>Add variation</button>
			</div>
			<button id="step4-next-button-product" class="button next large-button"><i class="fa fa-forward"></i>Next</button>
			<button id="step4-back-button-product" class="button back large-button"><i class="fa fa-backward"></i>Back</button>			
		</div>
	</div>
	<div class="new-product-step original" id="step4-original">
		<div class="number">4.</div>
		<div class="content"></div>
	</div>		

	<div class="new-product-step" id="step5">		
		<div class="number">5.</div>
		<div class="content">
			<h5>
				<span>
					Pricelines
				</span>
			</h5>

			<div class="production-costs">	
				<div class="production-cost">	
					<h4 class="title">	
						Priceline #1
					</h4>					
					<div class="amount-container">
						<label>Amount</label>
						<input disabled="disabled" class="amount" type="text" value="1" name="first_amount">
					</div>
					<div class="production-cost-container">
						<label>Production costs</label>
						<input class="production-cost first_production_cost" type="integer" name="first_production_cost">
					</div>
					<div class="delete-container">							
						<button class="button delete-production-cost"><i class="fa fa-trash"></i>Delete priceline</button>
					</div>
				</div>
			</div>
			<div class="add-variation">					
				<button class="button add-product-cost-button"><i class="fa fa-plus"></i>Add priceline</button>
			</div>
			<button id="step5-next-button-product" class="button add-product next large-button"><i class="fa fa-forward"></i>Finish</button>
			<button id="step5-back-button-product" class="button add-product back large-button"><i class="fa fa-backward"></i>Back</button>
		</div>
	</div>	
	<div class="new-product-step original" id="step5-original">
		<div class="number">5.</div>
		<div class="content"></div>
	</div>	

	<div class="new-product-step" id="step6">
		<div class="number">6.</div>
		<div class="content">
			<h5>
				<span>
					Your product has been added to the system
				</span>
			</h5>
			<div class="result">	
			</div>
		</div>	
	</div>
<div id="new_product_overlay">

</div>

