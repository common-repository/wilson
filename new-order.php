<?php 	
	global $post;
	$functions = new Merch_Stock_Functions();
	$order_id = $functions->createNewOrder();
	$newOrder = get_post($order_id);
	$customer = $functions->getUserCustomer();
	$offices = $functions->getUserOffices();
	$products = get_posts( array(
		'post_type' => 'product'
	) );
?>

<header class="page-header col-lg-12">
	<h2>Products</h2>

	<div class="right-wrapper text-right">
	</div>
</header>
<section role="main" class="content-body">	
<div class="plugin-content col-lg-12 nopadding nomargin card">
	<header class="card-header">
		<div class="card-actions">
		</div>				
		<h2 class="card-title">New Order: #
			<?php 	
				echo esc_html($order_id);
			?>
		</h2>
	</header>
	<form action="<?php echo esc_url( admin_url('admin-post.php') ); ?>" method="POST">
		<!-- <select id="selected-office"> -->
		<select  class="form-control populate select2-hidden-accessible selected-office" name="selected-office"  tabindex="-1" aria-hidden="true">		
			<?php
				foreach ($offices as $key => $office) {		
					echo esc_html("<option value='".$office->ID."'>" . get_the_title( $office ) . "</option>");		
				}		
			?>
		</select>
		<hr>	
		<select  class="form-control populate select2-hidden-accessible selected-product" name="selected-product"  tabindex="-1" aria-hidden="true">
			<?php
				foreach ($products as $key => $product) {		
					if (get_post_meta( $product->ID, 'customer_id', true )==$customer){
						echo esc_html("<option value='".$product->ID."'>" . get_the_title( $product ) . "</option>");	
					}				
				}		
			?>
		</select>
		<input type="integer" name="amount" id="add-product-amount"/>
		<button type="button" id="add-product-to-order">Add</button>
		<input type="hidden" id="order_id" name="order_id" value="<?php echo esc_attr($order_id);?>">
		<div id="orderlines">
			<div class="row">
				<div class="col">
					<section class="card">
						<header class="card-header">
							<div class="card-actions">
								<a href="#" class="card-action card-action-toggle" data-card-toggle=""></a>
								<a href="#" class="card-action card-action-dismiss" data-card-dismiss=""></a>
							</div>
							<h2 class="card-title">Orderlines</h2>
						</header>
						<div class="card-body">
							<table class="table table-no-more table-bordered table-striped mb-0" id="datatable-default">
								<thead>
									<tr>
										<th>ID</th>
										<th>Product</th>
										<th>Price</th>
										<th>Amount</th>								
										<th>Actions</th>
									</tr>
								</thead>
								<tbody>
								</tbody>
							</table>
						</div>
					</section>
				</div>
			</div>		
			<div class="row">
				<div id="number-of-lines">
				</div>				
				<div id="total-order-price">
				</div>
			</div>
		</div>
		<button data-order_id="<?php echo esc_attr($order_id) ?>" type="submit" id="addToTable" class="btn btn-primary">Save order +</i></button>
		<input type="hidden" name="hidden-order-office" id="hidden-order-office" value="<?php echo esc_attr($offices[0]->ID) ?>">			
		<input type="hidden" name="order_id" value="<?php echo esc_attr($order_id) ?>">
		<input type="hidden" name="action" value="save_order">
	</form>
</div>
</section>