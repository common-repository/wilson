<?php 
	$functions = new Merch_Stock_Functions();
	$product_id = intval($_GET["product_id"]);
	$product = get_post($product_id);
	$productTitle = get_the_title( $product_id );
?>

<div class="wrap wilson">	
	<h1><?php echo $productTitle ?></h1>
	<div class="button-bar">
		<button class="button"><a href="<?php echo esc_url(admin_url( 'admin.php?page=admin_product_edit&product_id='.$product_id )) ?>" class="page-title-action"><i class="fa fa-edit"></i>Edit product</a></button>
		<button class="button"><a href="<?php echo esc_url(admin_url( 'admin.php?page=new_product' )) ?>" class="page-title-action"><i class="fa fa-plus"></i>Add new</a></button>
	</div>	
	<div class='filters'>
		<label>&nbsp;</label>
	</div>	
	<div class="product-properties" >	
		<div class="content">
			<h5>
				<span>
					Product stock
				</span>
			</h5>
			<div class="card-body">
				<br>	
				<?php 	
					foreach ($functions->getStockLines($product_id) as $key => $stockline){
						$stockline_id = intval($stockline->ID);
						if ($key==0){
							echo '<div class="tab alert alert-default" id="tab_stock_'.$stockline_id .'" >';
						}
						else{
							echo '<div class="tab alert alert-default" style="display:none" id="tab_stock_'.$stockline_id .'">';
						}
						$stock = $functions->getProductStock($product_id, $stockline_id);
							echo "Stock: " . intval($stock["stock"]) . "<br/>" ;
							echo "Submitted: " . intval($stock["submitted"]) . "<br/>" ;
							echo "Shipped: " . intval($stock["shipped"]) . "<br/>" ;
							echo "Backorder: " . intval($stock["backorder"]) . "<br/>" ;
							echo "Init: " . intval($stock["init"]) . "<br/>" ;
							echo "<hr/>";
							echo "<b>Available: " . (intval($stock["stock"])-intval($stock["init"])) . "</b>";
					?>
			</div>
				<?php
					}
				?>					
		</div>
		<div class="button-bar">
			<?php 	
				foreach ($functions->getStockLines($product_id) as $key => $stockline){
					$stockline_id = intval($stockline->ID);
					if ($key==0){
						echo "<button data-stockline_id='".$stockline_id."' type='button' class='button mb-1 mt-1 mr-1 btn btn-primary tab-button' >".esc_html(get_post_meta( $stockline_id, 'description', true ))."</button>";
					}
					else{
						echo "<button data-stockline_id='".$stockline_id."' type='button' class='button mb-1 mt-1 mr-1 btn btn-default tab-button' >".esc_html(get_post_meta( $stockline_id, 'description', true ))."</button>";
					}
				}
			?>	
		</div>
	</div>			
	<div class="content">
		<h5>
			<span>
				Order history
			</span>
		</h5>
		<div class="card-body">
			<table class="table table-bordered table-striped mb-0 dataTable no-footer" id="datatable-default">
				<thead>
					<tr>
						<th>ID</th>
						<th>Status</th>
						<th>Date</th>
						<th>User</th>
						<th>Office</th>
						<th>Shipping</th>
						<th>Production</th>					
						<th>Actions</th>		
					</tr>
				</thead>
				<?php 	
					foreach ($orderHistory as $key => $value) {			
						$order_id = $value["ID"];
						$order = get_post($order_id);
						$actions = "<a href='" . admin_url( 'admin.php?page=order&order_id='.$order_id ) .  "' class='on-default edit-row'><button type='button' class='mb-1 mt-1 mr-1 btn btn-default'>View</button></i></a>"; 
						
						
						$output = "<tr>";
						$output .= "<td>";
						$output .= $order_id;
						$output .= "</td>";
						$output .= "<td>";
						$output .= esc_html($functions->getOrderStatusHTML($order_id));
						$output .= "</td>";
						$output .= "<td>";
						$output .= esc_html($order_functions->getOrderDate($order_id));
						$output .= "</td>"; 						
						$output .= "<td>";
						$output .= esc_html($user_functions->getOrderUser($order_id));
						$output .= "</td>";
						$output .= "<td>";
						$output .= esc_html($order_functions->getOrderOffice($order_id));
						$output .= "</td>";
						$output .= "<td>";
						$output .= esc_html($order_functions->getOrderShippingCostsV2($order_id));
						$output .= "</td>"; 
						$output .= "<td>";
						$output .= esc_html($order_functions->getOrderProductionCosts($order_id));
						$output .= "</td>"; 					
						$output .= "<td>".$actions."</td>";
						$output .= "</tr>";
						echo $output;
					}
				?>
			</table>
		</div>		
	</div>
</div>