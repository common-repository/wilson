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
		</div>					
		<button id="add-customer-next-button" class="button next"><i class="fa fa-forward"></i>Next</button> 
		<button id="add-customer-back-button" class="button back"><i class="fa fa-backward"></i>Back</button>													
		
	</div>		
</div>
<div id="new_manager_container">
	<div class="content">
		<h5>
			<span>
				New Manager
			</span>
		</h5>			
		<div>
			<label>Manager first name</label>
			<input id="new_manager_first_name" type="text" name="">
			<label class="obligated">*</label>
		</div>		
		<div>
			<label>Manager last name</label>
			<input id="new_manager_last_name" type="text" name="">
			<label class="obligated">*</label>
		</div>		
		<div>	
			<label>Is headoffice user</label>
			<input id="is_headoffice" type="checkbox" name="">
		</div>	
		<div>
			<label>Manager e-mail</label>
			<input id="new_manager_email" type="text" name="">
			<label class="obligated">*</label>
		</div>						
		<div>
			<label>Manager password</label>
			<input id="new_manager_password" type="text" name="">
			<label class="obligated">*</label>
			<br>	
			<label></label><button id="generate_password" class="button">Generate safe password</button>			
		</div>								
		<button id="add-manager-next-button" class="button next"><i class="fa fa-forward"></i>Next</button> 
		<button id="add-manager-back-button" class="button back"><i class="fa fa-backward"></i>Back</button>													
		
	</div>		
</div>
<div class="new-item wrap wilson" id="new-office">
	<input type="hidden" id="new_office_id" value="0" name=""/>		
	<h1 class='wp-heading-inline'>New office</h1>
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
			<button id="new_customer" class="button large-button button-left"><i class="fa fa-plus"></i>New Customer</button>
			<button id="step1-next-button-office" class="button add-office next large-button"><i class="fa fa-forward"></i>Next</button>
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
					Select Manager
				</span>
			</h5>
			<div>	
				<label>Manager</label>
				<select id="select_manager">
					<?php 

					?>
				</select>
				<label class="obligated">*</label>				
			</div>
			<button id="new_manager" class="button large-button button-left"><i class="fa fa-plus"></i>New Manager</button>
			<button id="step2-next-button-office" class="button add-office next large-button"><i class="fa fa-forward"></i>Next</button>
			<button id="step2-back-button-office" class="button add-office back large-button"><i class="fa fa-backward"></i>Back</button>
		</div>
	</div>

	<div class="new-product-step original" id="step2-original">
		<div class="number">2.</div>
		<div class="content"></div>
	</div>	

	<div class="new-product-step" id="step3">		
		<div class="number">3.</div>
		<div class="content">
			<h5>
				<span>
					Office address
				</span>
			</h5>			
			<div class="product-name-container">
				<label>Office description</label>
				<input class="description" type="text">
				<label class="obligated">*</label>
			</div>			
			<div class="product-name-container">
				<label>Addressline #1</label>
				<input class="addressline1" type="text">
				<label class="obligated">*</label>
			</div>
			<div class="product-name-container">
				<label>Addressline #2</label>
				<input class="addressline2" type="text">
			</div>			
			<div class="product-name-container">
				<label>Addressline #3</label>
				<input class="addressline3" type="text">
			</div>	
			<div class="product-name-container">
				<label>Postal code</label>
				<input class="postal_code" type="text">
				<label class="obligated">*</label>
			</div>	
			<div class="product-name-container">
				<label>City</label>
				<input class="city" type="text">
				<label class="obligated">*</label>
			</div>	
			<div class="product-name-container">
				<label>Region</label>
				<input class="region" type="text">
			</div>	
			<div class="product-name-container">
				<label>County</label>
				<input class="county" type="text">
			</div>	
			<div class="product-name-container">
				<label>Country</label>
				<input class="country" type="text">
				<label class="obligated">*</label>
			</div>	
			<div class="product-name-container">
				<label>Telephone</label>
				<input class="telephone" type="text">
				<label class="obligated">*</label>
			</div>				
			<div class="product-name-container">
				<label>E-mail</label>
				<input class="email" type="text">
				<label class="obligated">*</label>
			</div>							
			<button id="step3-next-button-office" class="button add-office next large-button"><i class="fa fa-forward"></i>Next</button>
			<button id="step3-back-button-office" class="button add-office back large-button"><i class="fa fa-backward"></i>Back</button>																	
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
					Shipping costs
				</span>
			</h5>	
			<div class="product-name-container">
				<label>Shipping box price</label>
				<input class="shipping_box_price" type="text">
				<label class="obligated">*</label>
			</div>
			<div class="product-name-container">
				<label>Shipping box weight</label>
				<input class="shipping_box_weight" type="text">
				<label class="obligated">*</label>
			</div>						
			<button id="step4-next-button-office" class="button add-office next large-button"><i class="fa fa-forward"></i>Next</button>
			<button id="step4-back-button-office" class="button add-office back large-button"><i class="fa fa-backward"></i>Back</button>				
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
					Your office has been added to the system
				</span>
			</h5>
			<div class="result">	
			</div>
		</div>	
	</div>
</div>

<div id="new_product_overlay">

</div>