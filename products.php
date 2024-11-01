<?php 
	$functions = new Merch_Stock_Functions();
	$user_functions = new Merch_Stock_User_Functions();
	$customer = $user_functions->getUserCustomer();
	$current_role = $user_functions->getMerchStockRoleName();
	$products = $functions->getProducts();
?>
<header class="page-header col-lg-12">
	<h2>Products</h2>
	<div class="right-wrapper text-right">
		<div class="right-wrapper text-right">
			<ol class="breadcrumbs">
				<li>
					<a href="admin.php?page=Dashboard">
						<i class="fa fa-home"></i>
					</a>
				</li>
				<li><span>Products</span></li>
			</ol>		
		</div>		
	</div>
</header>
<section role="main" class="content-body">	
	<div class="plugin-content col-lg-12 nopadding nomargin card">
		<div class="row body-background">
			<?php 	
				foreach ($products as $key => $product) {		
					$product_id = intval($product->ID);
					$custom = get_post_custom($product->ID);
					$custom_stock = get_post_custom( $custom["stockline_id"][0] );
					$production_costs = $custom["production_costs"][0];
					$product_price = $custom["product_price"][0];
					$product_weight = $custom["product_weight"][0];
					$order_per = $custom["order_per"][0];
					$order_term = $custom["order_term"][0];		
					$minimal_order_amount = $custom["minimal_order_amount"][0];				
					$stocklines = $functions->getStockLines($product->ID);
					$hiddenAvailableAmounts = "";
					?>
					<div class="col-lg-4 product-view-container">
						<section class="card card-featured card-featured-primary" id="product_section_<?php echo $product_id ?>">
							<header class="card-header">
								<h2 class="card-title">
									<a href="
									<?php 
										if ($current_role=="headoffice"){
											echo  esc_url(admin_url( 'admin.php?page=product&product_id='. $product_id ));	
										}										
									?>
									">
										<?php echo esc_html(get_the_title($product_id)); ?>
									</a>
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
							<div class="card-footer">	
									<?php 			
										if (count($stocklines)>1){
											echo "<div><select class='add-product-stockline'></div>";
										}
										else{
											echo "<select style='display:none' class='add-product-stockline'>";	
										}
										foreach ($stocklines as $key => $value) {									
											$custom = get_post_custom( $value->ID );
											$product_stock = $functions->getProductStock($product_id, $value->ID);				
											$free_items = intval($product_stock["stock"]) - intval($product_stock["init"]);
											$hiddenAvailableAmounts .= "<input type='hidden' id='available_".$product_id."_".$value->ID."' value='".$free_items."'/>";				
											echo "<option value='".intval($value->ID)."'>".esc_html($custom['description'][0])."</option>";
										}
									?>		
								</select>
								<select class="add-product-amount">	
									<?php
										$order_minimal = $minimal_order_amount;
										for ($i=0; $i < 100 ; $i++) { 
											echo "<option value='".intval($order_minimal)."'>".intval($order_minimal)."</option>";
											$order_minimal += $order_per;
										}
									?>
								</select>
								<?php 	
									echo $hiddenAvailableAmounts;
									echo "<input type='hidden' value='".intval($order_per)."' id='order_per_".intval($product_id)."'/>";
									echo "<input type='hidden' value='".intval($minimal_order_amount)."' id='order_minimal_".intval($product_id)."'/>";
								?>
								<div class='button-container'>	<button data-product_id = "<?php echo intval($product_id) ?>" data-product_name = "<?php echo esc_html(get_the_title($product_id)) ?>" type="button" class=" btn btn-success add-product-to-order-from-product-v2 add-product-to-order-from-product-<?php echo intval($product_id) ?> ">Add to order<i class="fa fa-plus"></i></button>		
								</div>
								<div class="product-stock-info-tmp">	
									<?php 	
										$cnt = 0;
										foreach ($stocklines as $key => $stockline){	
											$stockline_id = intval($stockline->ID);
											$product_stock = $functions->getProductStock($product_id, $stockline_id);
											if ($cnt==0){															
												$output = "<div class='product-stock-line product-stock-info-".$stockline_id."'>";				
												$output .= "<div data-available='".(intval($product_stock["stock"])-intval($product_stock["init"]))."' class='product-stockline-available product-stockline-available-".$stockline_id."'>Available: ";
												$output .= intval(intval($product_stock["stock"])-intval($product_stock["init"]));	
												$output .= "</div>";
											}
											else{
												$output = "<div style='display:none' class='product-stock-line product-stock-info-".$stockline_id."'>";											
												$output .= "<div data-available='".(intval($product_stock["stock"])-intval($product_stock["init"]))."' class='product-stockline-available product-stockline-available-".$stockline_id."'>Available: ";
												$output .= intval(intval($product_stock["stock"])-intval($product_stock["init"]));														
												$output .= "</div>";
											}								
											if ($current_role=="manager"){
												$output .= "<button data-inbackorder='".$product_stock["backorder"]."' data-product_name='".get_the_title(intval($product_id))."' type='button' class='mb-1 mt-1 mr-1 btn btn-xs btn-default request-backorder-from-product request-backorder-from-product-".intval($product_id)." request_more'  data-product_id='".intval($product_id)." '>Request more</button>";
											}
											elseif ($current_role=="headoffice"){
												foreach ($stocklines as $key => $stockline){
													$stockline_id2 = intval($stockline->ID);
													if ($cnt==0){
														$output .= "<button data-inbackorder='".$product_stock["backorder"]."'  data-pricelines='".wp_json_encode($functions->getPricelines($product_id, 'ASC'))."' class='mb-1 mt-1 mr-1 mb-1 mt-1 mr-1 btn btn-xs btn-default  create_backorder create_backorder_".$stockline_id2."request_more  ' data-stockline_id='".$stockline_id2."' data-product_id='".$product_id."' data-backorder_request_id='null' data-product_name='".get_the_title($product_id)."' data-stockline_name='".get_post_meta( $stockline_id2, 'description', true )."' type='button'>Request more</button>";
													}
													else{
														$output .= "<button data-inbackorder='".$product_stock["backorder"]."'  data-pricelines='".wp_json_encode($functions->getPricelines($product_id, 'ASC'))."' class='mb-1 mt-1 mr-1 mb-1 mt-1 mr-1 btn btn-xs btn-default  create_backorder create_backorder_".$stockline_id2." request_more' data-stockline_id='".$stockline_id2."' data-product_id='".$product_id."' data-backorder_request_id='null' style='display:none' data-product_name='".get_the_title($product_id)."' data-stockline_name='".get_post_meta( $stockline_id2, 'description', true )."' type='button'>Request more</button>";
													}
													$cnt+=1;
												}
											}
											$output .= "<div class='product-stock-line-info'>";
											$output .= "Available: ";
											$output .= intval($product_stock["stock"]);					
											$output .= " / Submitted: ";
											$output .= intval($product_stock["submitted"]);												
											$output .= " / Approved: ";
											$output .= intval($product_stock["approved"]);							
											$output .= " / Shipped: ";
											$output .= intval($product_stock["shipped"]);																																
											$output .= " / Declined: ";
											$output .= intval($product_stock["declined"]);												
											$output .= " / Cancelled: ";
											$output .= intval($product_stock["cancelled"]);																						
											if (intval($product_stock["backorder"]>0)){
												$output .= ", In Backorder: " . intval($product_stock["backorder"]);				
											}										
											$output .= "</div>";
											$output .= "</div>";
											$cnt += 1;
											echo $output;
										}
									?>
								</div>
							</div>
						</section>
					</div>
					<?php
				}
			?>
		</div>	
	</div>
	<div class="row">
		<div class="col-lg-12">
			<div class="card-body add-product-box">
				Need a different item for your campaign? <a href="#" class="request-new-product">Send in a product request.</a>
			</div>	
		</div>
	</div>
</section>