<?php 
	$functions = new Merch_Stock_Functions();
	$product_id = intval($_GET["product_id"]);
	$product = get_post($product_id);
	$productTitle = esc_html(get_the_title( $product_id ));
	$meta = get_post_custom($product_id);
	$post = get_post($product_id);
	$content = esc_html($post->post_content);
	$url = esc_url(get_the_post_thumbnail_url($product_id));
	$output .= '<img class="list-thumbnail" src='.$url.'>';		
	wp_enqueue_media(); 
?>

<div class="wrap wilson">	
	<h1>Edit <?php echo $productTitle ?></h1>
	<div class="button-bar">		
		<form action="<?php echo esc_url( admin_url('admin-post.php') ); ?>" method="POST">
			<input type="hidden" name="action" value="delete_wilson_products">
			<input type="hidden" class="ids" name="ids">
			<button class="button"><i class="fa fa-trash"></i>Delete product</button>		
		</form>		
	</div>	
	<div class='filters'>
		<label>&nbsp;</label>
	</div>	
	<div class="product-properties" >	
		<form action="<?php echo esc_url( admin_url('admin-post.php') ); ?>" method="POST">
			<input type="hidden" name="action" value="update_wilson_product">
			<input type="hidden" name="product_id" value="<?php echo $product_id ?>">

		<div class="content">
			<h5>
				<span>
					Product properties
				</span>
			</h5>
			<div class="card-body">
			<div class="product-name-container">
				<label>Product name</label>
				<input class="product_name" name="product_name" type="text" value="<?php echo $productTitle ?>">
				<label class="obligated">*</label>
			</div>
			<div class="article-number-container">
				<label>Article number</label>
				<input class="article_number" name="article_number" type="text" value="<?php echo esc_html($meta["article_number"][0]) ?>">
			</div>	
			<div class="product-description-container">
				<label>Product description</label>
				<textarea class="product_description" name="product_description" rows="4" cols="50"><?php echo $content ?></textarea>
			</div>		
			<div>
				<label>Product image</label>
				<img class="edit-thumbnail" src="<?php echo $url ?>" /><br>	
				<label>&nbsp;</label>
			    <input style="display: none" type="text" class="process_custom_images add_product_image" name="selected_logo" value="" placeholder="http://">
			    <button type="button" class="set_custom_logo_3 button" style="vertical-align: middle;"><i class="fa fa-file-image"></i>Select product image</button>			
			</div>					
			</div>
		</div>
		<div class="content">
			<h5>
				<span>
					Product variables
				</span>
			</h5>		
			<div>
				<label>Product weight</label>
				<input id="product_weight" type="text" name="product_weight" value="<?php echo intval($meta["product_weight"][0]) ?>">
				<label class="obligated">*</label>
			</div>		
			<div>
				<label>Minimal order amount</label>
				<input id="minimal_order_amount" type="text" name="minimal_order_amount" value="<?php echo intval($meta["minimal_order_amount"][0]) ?>">
				<label class="obligated">*</label>
			</div>			
			<div>
				<label>Order per</label>
				<input id="order_per" type="text" name="order_per" value="<?php echo intval($meta["order_per"][0]) ?>">
				<label class="obligated">*</label>
			</div>			
			<div>
				<label>Order term</label>
				<input id="order_term" type="text" name="order_term" value="<?php echo esc_html($meta["order_term"][0]) ?>">
				<label class="obligated">*</label>
			</div>			
			<div>
				<label>Warning amount</label>
				<input id="warn_amount" type="text" name="warn_amount" value="<?php echo esc_html($meta["warn_amount"][0]) ?>">
				<label class="obligated">*</label>
			</div>				
		</div>
		<div class="content">
			<h5>
				<span>
					Product variations
				</span>
			</h5>	
			<div class="product-variations">	
				<?php 
					foreach ($functions->getStockLines($product_id) as $key => $stockline){
						$stockline_id = intval($stockline->ID);
						$description = esc_html(get_post_meta( $stockline_id, 'description', true ));
						$stock = intval(get_post_meta( $stockline_id, 'product_stock', true ));
						?>
								<div>	
									<label>Product variation #<?php echo $key+1 ?></label>
									<div class="description-container">
										<label>Description</label>
										<input class="description" type="text" name="description[<?php echo intval($stockline_id) ?>]" value="<?php echo $description ?>">
										</div>
									<div class="stock-container">
										<label>Stock</label>
										<input class="stock" type="integer" name="stock[<?php echo intval($stockline_id) ?>]" value="<?php echo $stock ?>">
									</div>		
									<div class="delete-container">	
										<button type="button" class="button delete-product-variation"><i class="fa fa-trash"></i>Delete variation</button>
									</div>																
								</div>
						<?php
					}
				?>
			</div>		
			<div class="add-variation">	
				<button data-is_edit_screen="true" type="button" class="button add-variation-button"><i class="fa fa-plus"></i>Add variation</button>
			</div>
		</div>	
		<div class="content">
			<h5>
				<span>
					Production costs
				</span>
			</h5>	
			<div class="production-costs">
				<?php 	
					foreach ($functions->getPricelines($product_id, 'DESC') as $key => $priceline){
						$priceline_id = intval($priceline["priceline"]->ID);
						
						$production_costs = floatval(get_post_meta( $priceline_id, 'production_costs', true ));
						$amount = intval(get_post_meta( $priceline_id, 'amount', true ));
						?>
						<div>	
							<label>Priceline #<?php echo $key+1 ?></label>
							<div class="amount-container">
								<label>Amount</label>
								<?php
									if ($amount==1){
										echo '<input disabled="disabled" class="amount" type="text" value="'.intval($amount).'" name="amount['.$priceline_id.']">';
									}
									else{
										echo '<input class="amount" type="text" value="'.intval($amount).'" name="amount['.$priceline_id.']">';
									}
								?>
							</div>
							<div class="production-cost-container">
									<label>Production costs</label>
									<input class="production-cost" type="integer" name="cost[<?php echo $priceline_id ?>]" value="<?php echo $production_costs ?>">								
							</div>
							<div class="delete-container">
										<?php 	
											if ($amount>1){
												echo '<button type="button" class="button delete-production-cost"><i class="fa fa-trash"></i>Delete priceline</button>';
											}
										?>										
							</div>
						</div>
<!-- 							<div class="production-cost">	
								<h4 class="title">	
									Priceline #<?php echo $key+1 ?>
								</h4>					
								<div class="amount-container">
									<label>Amount</label>
									<?php
										if ($amount==1){
											echo '<input disabled="disabled" class="amount" type="text" value="'.intval($amount).'" name="amount['.$priceline_id.']">';
										}
										else{
											echo '<input class="amount" type="text" value="'.intval($amount).'" name="amount['.$priceline_id.']">';
										}
									?>
									
								</div>
								<div class="production-cost-container">
									<label>Production costs</label>
									<input class="production-cost" type="integer" name="cost[<?php echo $priceline_id ?>]" value="<?php echo $production_costs ?>">
								</div>
								<div class="delete-container">			
										<?php 	
											if ($amount>1){
												echo '<button type="button" class="button delete-production-cost"><i class="fa fa-trash"></i>Delete priceline</button>';
											}
										?>				
									
								</div>
							</div> -->
						<?php
					}
				?>
			</div>	
			<div class="add-variation">					
				<button data-is_edit_screen="true" type="button" class="button add-product-cost-button"><i class="fa fa-plus"></i>Add priceline</button>
			</div>
		</div>	
			<button id="step2-next-button-product" type="submit" class="button add-product next"><i class="fa fa-forward"></i>Next</button>
			<button id="step2-back-button-product" class="button add-product back"><i class="fa fa-backward"></i>Back</button>
	</form>
	</div>			
</div>


