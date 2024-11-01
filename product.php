<?php 
	$functions = new Merch_Stock_Functions;
	$order_functions = new Merch_Stock_Order_Functions;
	$notification_functions = new Merch_Stock_Notifications;
	$user_functions = new Merch_Stock_User_Functions;
	$product_id = intval($_GET['product_id']);
	$product = get_post($product_id);
	$orderHistory = json_decode($functions->getOrderHistoryV2($product_id),true);	
	$notification_functions->updateNotifications($product_id, 'product_out_of_stock');		
?>
<header class="page-header col-lg-12">
	<h2>
		<?php 	
			echo esc_html(get_the_title($product_id));
		?>
	</h2>
	<div class="right-wrapper text-right">
			<ol class="breadcrumbs">
				<li>
					<a href="admin.php?page=Dashboard">
						<i class="fa fa-home"></i>
					</a>
				</li>
				<li><a href="admin.php?page=products"><span>Products</span></a></li>
				<li><span><?php echo esc_html(get_the_title( $product_id )); ?></span></li>
			</ol>	
	</div>
</header>
<section role="main" class="content-body">	
<div class="plugin-content col-lg-12 nopadding nomargin card">
	<header class="card-header">
		<div class="card-actions">
		</div>				
		<h2 class="card-title">Stock</h2>
	</header>	
	<div class="card-body">
			<br>	
			<?php 	
				foreach ($functions->getStockLines($product_id) as $key => $stockline){
					if ($key==0){
						echo ('<div class="tab alert alert-default" id="tab_stock_'.esc_html($stockline->ID) .'" >');
					}
					else{
						echo ('<div class="tab alert alert-default" style="display:none" id="tab_stock_'.esc_html($stockline->ID) .'">');
					}
					$stock = $functions->getProductStock($product_id, $stockline->ID);
						echo ("Stock: " . esc_html($stock["stock"]) . "<br/>") ;
						echo ("Submitted: " . esc_html($stock["submitted"]) . "<br/>") ;
						echo ("Shipped: " . esc_html($stock["shipped"]) . "<br/>") ;
						echo ("Backorder: " . esc_html($stock["backorder"]) . "<br/>") ;
						echo ("Init: " . esc_html($stock["init"]) . "<br/>") ;
						echo ("<hr/>");
						echo ("<b>Available: " . esc_html($stock["stock"]-$stock["init"]) . "</b>");
					?>
						</div>
					<?php
				}
			?>	
					
		</div>
			<div class="button-bar">
			<?php 	
				foreach ($functions->getStockLines($product_id) as $key => $stockline){
					if ($key==0){
						echo "<button data-stockline_id='".esc_html($stockline->ID)."' type='button' class='mb-1 mt-1 mr-1 btn btn-primary tab-button' >".esc_html(get_post_meta( $stockline->ID, 'description', true ))."</button>";
					}
					else{
						echo "<button data-stockline_id='".esc_html($stockline->ID)."' type='button' class='mb-1 mt-1 mr-1 btn btn-default tab-button' >".esc_html(get_post_meta( $stockline->ID, 'description', true ))."</button>";
					}
				}
			?>					
			</div>			
	</div>	
<div class="plugin-content col-lg-12 nopadding nomargin card">
	<header class="card-header">
		<div class="card-actions">
		</div>				
		<h2 class="card-title">Order history</h2>
	</header>	
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
					$actions = "<a href='" . admin_url( 'admin.php?page=order&order_id='.$order->ID ) .  "' class='on-default edit-row'><button type='button' class='mb-1 mt-1 mr-1 btn btn-default'>View</button></i></a>"; 
					$order_id = $value["ID"];
					$order = get_post($order_id);
					$output = "<tr>";
					$output .= "<td>";
					$output .= esc_html($order->ID);
					$output .= "</td>";
					$output .= "<td>";
					$output .= esc_html($functions->getOrderStatusHTML($order->ID));
					$output .= "</td>";
					$output .= "<td>";
					$output .= esc_html($order_functions->getOrderDate($order->ID));
					$output .= "</td>"; 						
					$output .= "<td>";
					$output .= esc_html($user_functions->getOrderUser($order->ID));
					$output .= "</td>";
					$output .= "<td>";
					$output .= esc_html($order_functions->getOrderOffice($order->ID));
					$output .= "</td>";
					$output .= "<td>";
					$output .= esc_html($order_functions->getOrderShippingCostsV2($order->ID));
					$output .= "</td>"; 
					$output .= "<td>";
					$output .= esc_html($order_functions->getOrderProductionCosts($order->ID));
					$output .= "</td>"; 					
					$output .= "<td>".esc_html($actions)."</td>";
					$output .= "</tr>";
					echo esc_html($output);
				}
			?>
		</table>
	</div>	
</div>
<div class="plugin-content col-lg-12 nopadding nomargin card">
	<header class="card-header">
		<div class="card-actions">
		</div>				
		<h2 class="card-title"><?php echo esc_html(get_the_title( $product_id )) ?></h2>
	</header>	
	<div class="card-body">
		<?php echo esc_html($product->post_content) ?>
	</div>
</div>
	
</div>
	<div id="order_information_popup" class="row nopadding nomargin card">
		<header class="card-header">
			<div class="card-actions">
			</div>				
			<h2 class="order-name"></h2>
			<div class="row"></div>
			<div id="close_order_information_popup">X</div>
		</header>		
		<div class="row">
			<div class="col-lg-2 card-body custom-card-body">
				Order Price: <div class="order-price"></div>
			</div>	
			<div class="col-lg-2 card-body custom-card-body">
				Order Weight: <div class="order-weight"></div>
			</div>
		</div>
		<div class="row">
			<div class="col-lg-12 orderlines">
				<table class="table table-responsive-md table-striped mb-0 orderlines-table">
					<thead>
						<tr>
							<th>#</th>
							<th>Product</th>
							<th>Amount</th>
							<th>Price</th>
							<th>Total</th>
						</tr>
					</thead>
					<tbody>
					</tbody>
				</table>
			</div>
		</div>
		<div class="col-lg-8 nopadding nomargin">				
			<div class="product-content"></div>
		</div>
	</div>	
</section>