<?php 
	$status = sanitize_text_field($_GET["status"] );
	$office_get = sanitize_text_field($_GET["office"] );
	$functions = new Merch_Stock_Functions();
	$admin_functions = new Merch_Stock_WP_Admin();
	$user_functions = new Merch_Stock_User_Functions();
	$order_functions = new Merch_Stock_Order_Functions();
	$backorders = json_decode($admin_functions->getBackorders());
	$current_role = $user_functions->getMerchStockRoleName();
	$offices = $user_functions->getUserOffices();
	$ajaxURL = esc_url( admin_url("admin-post.php") );
	$customers = json_decode($admin_functions->getCustomers(),true);
	$baseUrl = esc_url(admin_url( 'admin.php?page=backorders' ));
?>
<div class='wrap wilson'>
	<h1 class='wp-heading-inline'>Backorders</h1>
	<div class="button-bar">
		<button id="select_all_backorders" class="button"><i class="fa fa-check-square"></i>(De)select all</button>	
		<form action="<?php echo esc_url( admin_url('admin-post.php') ); ?>" method="POST">
			<input type="hidden" name="action" value="delete_wilson_backorders">
			<input type="hidden" class="ids" name="ids">
			<button class="button"><i class="fa fa-trash"></i>Delete selected</button>		
		</form>				
	</div>
	<div class='filters'>
		<label>Filter</label>
		<button type="button" class="button reset-filter" data-baseurl="<?php echo $baseUrl ?>"><i class="fa fa-redo"></i></button>
		<select class='filter' data-filter_item='customer_id'>
			<option value=0>All customers</option>
			<?php 
				foreach ($customers as $customer){
					$customerName = esc_html(get_the_title( $customer['ID'] ));
					$customerID = $customer['ID'];
					if ($customer_id==$customerID){
						echo "<option selected='selected' value=$customerID>".$customerName."</option>";	
					}
					else{
						echo "<option value=$customerID>".$customerName."</option>";
					}
				}
			?>
		</select>
	</div>	
	<table class='wp-list-table widefat fixed striped posts dataTable'>
		<thead>	
			<tr>
				<th class="th-select">Select</th>
				<th class="th-id">ID</th>
				<th >Customer</th>
				<th>User</th>
				<th>Status</th>
				<th>Product</th>
				<th>Amount</th>				
				<th>Actions</th>
			</tr>
		</thead>			
		<tbody>	
			<?php 	
				foreach ($backorders as $backorder){		
					$backorder_id = intval($backorder->ID);
					$stockline_id = intval(get_post_meta( $backorder_id, 'stockline_id', true ));
					$product_id = intval(get_post_meta( $stockline_id, 'product_id', true ));
					$productName = get_the_title( $product_id );
					$user_id = get_post_meta( $backorder->ID, 'user_id', true );
					$user = get_user_by( 'ID', $user_id );
					$customer_id = intval(get_user_meta( $user->ID, 'customer_id', true ));
					$shipping_costs = json_decode($order_functions->getOrderShippingCostsV2($order->ID, 0, 0, 0,true),true);
					$actions = 
					$output = '';
					$output .= '<tr class="iedit author-other level-0 alternate">';
					$output .= '<td class="">';
					$output .= '<input data-backorder_id="'.$backorder_id.'" class="selected_backorders" name="selected_backorders['.$backorder_id.']" type="checkbox"></input>';
					$output .= '</td>';							
					$output .= '<td class="">';
					// $output .= $backorder->ID;
					$output .= "<a href='" . admin_url( 'admin.php?page=admin_backorder&backorder_id='.$backorder_id ) .  "' class='on-default edit-row'>".$backorder_id."</a>";						
					$output .= '</td>';
					$output .= '<td class="">';
					$output .= '<a href="'.admin_url( 'admin.php?page=admin_customer&customer_id='.$customer_id  ) .'">' . get_the_title( $customer_id  ) . "</a>";
					$output .= '</td>';	
		
					$output .= '<td class="">';
					$output .= esc_html($user->user_firstname) . ' ' . esc_html($user->user_lastname);
					$output .= '</td>';
					$output .= '<td class="">';
					$output .= $functions->getStatusBubble(get_post_meta( $backorder->ID, 'status', true ));
					$output .= '</td>';			
					$output .= '<td class="">';
					$output .= $product_id . " " . esc_html(get_the_title( $product_id ));
					$output .= '</td>';	
					$output .= '<td class="">';
					$output .= intval(get_post_meta( $backorder->ID, 'amount', true ));
					$output .= '</td>';		
		
					$output .= '<td class="">';
					$output .= "<a href='" . admin_url( 'admin.php?page=admin_backorder&backorder_id='.$backorder_id ) .  "' class='on-default edit-row'><button type='button' class='button mb-1 mt-1 mr-1 btn btn-default'>View</button></i></a>";					

					$output .= '</td>';																																			
					$output .= '</tr>';
					echo $output;
				}
			?>
		</tbody>
		<tfoot>	
			<tr>
				<th class="th-select">Select</th>
				<th>ID</th>
				<th>Customer</th>
				<th>User</th>
				<th>Status</th>
				<th>Product</th>
				<th>Amount</th>				
				<th>Actions</th>
			</tr>
		</tfoot>		
	</table>
</div>
