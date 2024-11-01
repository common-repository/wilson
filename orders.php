<?php 
	$status = sanitize_text_field($_GET["status"]);
	$office_get = sanitize_text_field($_GET["office"]);
	if (strlen($status)==0){
		$status="all"; 
	}
	if (strlen($office_get)==0){
		$office_get="all";
	}	 
	$functions = new Merch_Stock_Functions(); 
	$user_functions = new Merch_Stock_User_Functions();
	$order_functions = new Merch_Stock_Order_Functions();
	$orders = json_decode($functions->getOrders(-1, $status, $office_get));
	$current_role = $user_functions->getMerchStockRoleName();
	$offices = $user_functions->getUserOffices();
?>
<header class="page-header col-lg-12">
	<h2>Orders</h2>
	<div class="right-wrapper text-right">
			<ol class="breadcrumbs">
				<li>
					<a href="admin.php?page=dashboard">
						<i class="fa fa-home"></i>
					</a>
				</li>
				<li><span>Orders</span></li>
			</ol>			
	</div>
</header>	
<section role="main" class="content-body">	
<div class="plugin-content col-lg-12 nopadding nomargin card">
	<header class="card-header">
		<div class="card-actions"> 
		</div>				
		<h2 class="card-title">Orders</h2>
	</header>	
	<div class="col-lg-12 filter"> 
		<div class=" row">
			<div class="col-lg-2">
				<i class="fa fa-filter" style="font-size: 32px;"></i><span class='filter-results'>Filter your results:</span>
			</div>
			<div class="col-lg-5">
				<select data-filterkey="status" class="form-control filter-item">
					<option value="all">All statuses</option>
					<?php 			
						if ($status=='submitted'){
							echo '<option selected="selected" value="accepted">Submitted</option>';
						}
						else{
							echo '<option value="submitted">Submitted</option>';	
						}					
						if ($status=='shipped'){
							echo '<option selected="selected" value="shipped">Shipped</option>'; 
						} 
						else{
							echo '<option value="shipped">Shipped</option>';	
						}										
					?>
				</select>
			</div>
			<div class="col-lg-5">
			<select data-filterkey="office" class="form-control filter-item">
				
				<?php 			
					if ($office_get=='all' ){
						echo "<option selected='selected' value='all'>All offices</option>";
					}
					else{
						echo "<option value='all'>All offices</option>";
					}
					foreach ($offices as $office){
						$office_name = esc_html(get_the_title( $office->ID ));
						if ($office_get==$office->ID ){
							echo "<option selected='selected' value=$office->ID>$office_name</option>";	
						}
						else{
							echo "<option value=$office->ID>$office_name</option>";	
						}
						
					}
									
				?>
			</select>	
			</div>		
		</div>	
	</div>
	<div class="col-lg-12">
		<table class="table table-bordered table-striped mb-0 dataTable no-footer" id="datatable-default">
				<thead>
					<tr>
						<th>ID</th>		
						<?php 
							if ($current_role=="admin"){
								echo "<th>Customer</th>";
							}
						?>			
						<th>Office</th>
						<th>User</th>
						<th>Status</th>
						<th>Date</th>
						<th>Shipping</th>
						<th>Production</th>
						<th>Actions</th>
					</tr>
				</thead>
				<?php
					foreach ($orders as $key => $order) {	
						$order_id = intval($order->ID);
						$custom = get_post_custom($order_id);
						$actions = "";
						$actions .= "<a href='" . admin_url( 'admin.php?page=order&order_id='.$order_id ) .  "' class='on-default edit-row'><button type='button' class='mb-1 mt-1 mr-1 btn btn-default'>View</button></i></a>"; 
						$actions .= "<a href='" . admin_url( 'admin.php?page=invoice&order_id='.$order_id ) .  "' class='on-default edit-row'><button type='button' class='mb-1 mt-1 mr-1 btn btn-default'>Packing list</button></a>  ";							
						$output = "<tr>";
						$output .= "<td>";
						$output .= $order_id; 
 						$output .= "</td>";
 						if ($current_role=="admin"){
 							$output .= "<td>";
 							$output .= get_the_title( $order_functions->getCustomerFromOrder($order_id) );
 							$output .= "</td>";
 						}
 						$output .= "<td>";
 						$output .= esc_html($order_functions->getOrderOffice($order_id));
 						$output .= "</td>"; 	
						$output .= "<td>";
						$output .= esc_html($user_functions->getOrderUser($order_id));
 						$output .= "</td>"; 											
						$output .= "<td>";
						$output .= $functions->getOrderStatusHTML($order_id);
 						$output .= "</td>";
						$output .= "<td>";
						$output .= esc_html($order_functions->getOrderDate($order_id));
 						$output .= "</td>"; 						
 						$output .= "<td>";
 						$output .= esc_html($order_functions->getOrderShippingCostsV2($order_id));
 						$output .= "</td>"; 
 						$output .= "<td>";
 						$output .= esc_html($order_functions->getOrderProductionCosts($order_id));
 						$output .= "</td>";  							 						
						$output .= "<td class='actions'>";
						$output .= $actions;
 						$output .= "</td>"; 						 						 						
						$output .= "</tr>";
						echo $output;								
					}		
				?>		
			</table>
		</div>
	</div>
	<div id="order_information_popup" class="row nopadding nomargin card">
		<header class="card-header">
			<div class="card-actions">
			</div>				
			<h2 class="order-name"></h2>
			<div class="row">
			<div id="close_order_information_popup">X</div>
		</header>		
		
		<div class="row">
			<!-- Product Name: <div class="product-name"></div><br> -->
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
		