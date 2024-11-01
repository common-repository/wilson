<?php 
	$status = sanitize_text_field($_GET["status"] );
	$office_get = sanitize_text_field($_GET["office"] );
	$functions = new Merch_Stock_Functions();
	$admin_functions = new Merch_Stock_WP_Admin();
	$user_functions = new Merch_Stock_User_Functions();
	$order_functions = new Merch_Stock_Order_Functions();
	$current_role = $user_functions->getMerchStockRoleName();
	$offices = $user_functions->getUserOffices();
	$ajaxURL = esc_url( admin_url("admin-post.php") );
	$customers = json_decode($admin_functions->getCustomers(),true);
	$customer_id = 0;
	$office_id = 0;
	$status = "status";
	$customer_id = intval($_GET["customer_id"]);
	$office_id = intval($_GET["office_id"]);
	if ($status==NULL){
		$status="status";
	}
	$orders = json_decode($admin_functions->getOrders($customer_id, $status, $office_id));	
	$offices = json_decode($admin_functions->getOffices($customer_id));
	$baseUrl = esc_url(admin_url( 'admin.php?page=orders' ));
?>
<div class='wrap wilson'>
	<h1 class='wp-heading-inline'>Orders</h1>
	<div class="button-bar">
		<button id="select_all_orders" class="button"><i class="fa fa-check-square"></i>(De)select all</button>	
		<form action="<?php echo esc_url( admin_url('admin-post.php') ); ?>" method="POST">
			<input type="hidden" name="action" value="delete_wilson_orders">
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
					if ($customerID==$customer_id){
						echo "<option selected='selected' value=$customerID>".$customerName."</option>";
					}
					else{
						echo "<option value=$customerID>".$customerName."</option>";
					}
				}
			?>
		</select>	
		<?php 	
			if ($customer_id>0){
				echo "<select class='filter filter-office' data-filter_item='office_id'>";
			}
			else{
				echo "<select style='display:none' class='filter filter-office' data-filter_item='office_id'>";
			}
		?>	
			
			<option value=0>All offices</option>
			<?php 	
				foreach ($offices as $office) {
					$officeName = esc_html(get_the_title( $office->ID ));
					$officeID = $office->ID;
					if ($officeID==$office_id){
						echo "<option selected='selected' value=$officeID>$officeName</option>";
					}
					else{
						echo "<option value=$officeID>$officeName</option>";
					}
					
				}
			?>
		</select>
		<select class='filter' data-filter_item='status'>
			<option value=0>All statuses</option>
			<option value='submitted'>Submitted</option>
			<option value='shipped'>Shipped</option>			
		</select>
	</div>
	<table class='wp-list-table widefat fixed striped posts dataTable'>
		<thead>	
			<tr>
				<th class="th-select">	</th>
				<th class="th-id">ID</th>
				<th>Customer</th>
				<th>Office</th>
				<th>User</th>
				<th>Status</th>
				<th>Date</th>
				<th>Shipping</th>
				<th>Production</th>
				<th style="text-align: right;">Actions</th>
			</tr>
		</thead>			
		<tbody>	
			<?php 	
				foreach ($orders as $order){					
					$order_id = intval($order->ID);
					$office_id = intval(get_post_meta( $order_id, 'office_id', true ));					
					$user_id = intval(get_post_meta( $order_id, 'user_id', true ));
					$user = get_user_by( 'ID', $user_id );
					$customer_id = intval(get_post_meta( $office_id, 'customer_id', true ));
					$shipping_costs = json_decode($order_functions->getOrderShippingCostsV2($order_id, 0, 0, 0,true),true);
					$actions = 
					$output = '';
					$output .= '<tr class="iedit author-other level-0 alternate">';
					$output .= '<td class="">';
					$output .=  '<input data-order_id="'.$order_id.'" class="selected_orders" name="selected_orders['.$order_id.']" type="checkbox"></input>';
					$output .= '</td>';					
					$output .= '<td class="">';
					$output .= '<a href="'.esc_url(admin_url( 'admin.php?page=admin_order&order_id='.$order_id  )) .'">' . $order_id . "</a>";
					$output .= '</td>';
					$output .= '<td class="">';					
					$output .= '<a href="'.esc_url(admin_url( 'admin.php?page=admin_customer&customer_id='.$customer_id  )) .'">' . esc_html(get_the_title( $customer_id  )) . "</a>";
					$output .= '</td>';	
					$output .= '<td class="">';					
					$output .= '<a href="'.esc_url(admin_url( 'admin.php?page=admin_office&office_id='.$office_id  )) .'">' . esc_html(get_the_title( $office_id  )) . "</a>";
					$output .= '</td>';			
					$output .= '<td class="">';
					$output .= esc_html($user->user_firstname) . ' ' . esc_html($user->user_lastname);
					$output .= '</td>';
					$output .= '<td class="">';
					$output .= $functions->getStatusBubble(get_post_meta( $order_id, 'status', true ));
					$output .= '</td>';			
					$output .= '<td class="">';
					$output .= esc_html(get_post_meta( $order_id, 'status_changed', true ));
					$output .= '</td>';	
					$output .= '<td class="">';
					$output .= esc_html($shipping_costs[4]) . ' / ' . esc_html($shipping_costs[1]) . ' unit(s)';
					$output .= '</td>';		
					$output .= '<td class="">';
					$output .= $functions->formatMoney($order_functions->getOrderTotalPrice($order_id));
					$output .= '</td>';			
					$output .= '<td style="text-align: right;" class="actions">';
					$output .= '<form action="'.esc_url($ajaxURL).'" method="POST">';
					$output .=	'<input type="hidden" name="action" value="invoice_pdf">';
					$output .=	'<input type="hidden" name="order_id" value="'.$order_id.'">		';
					$output .=	'<button class="button mb-1 mt-1 mr-1 btn btn-primary" type="submit" name="generate_posts_pdf" value="generate">Generate PDF</button>';
					$output .= '</form>';
					$output .= "<a href='" . esc_url(admin_url( 'admin.php?page=admin_order&order_id='.$order_id )) .  "' class='on-default edit-row'><button type='button' class='button mb-1 mt-1 mr-1 btn btn-default'>View</button></i></a>";								
					$output .= '</td>';																																			
					$output .= '</tr>';
					echo $output;
				}
			?>
		</tbody>
		<tfoot>	
			<tr>
				<th class="th-select">	</th>
				<th>ID</th>
				<th>Customer</th>
				<th>Office</th>
				<th>User</th>
				<th>Status</th>
				<th>Date</th>
				<th>Shipping</th>
				<th>Production</th>
				<th style="text-align: right;">Actions</th>
			</tr>
		</tfoot>		
	</table>
</div>
