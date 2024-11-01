<?php 
	$office_get = sanitize_text_field($_GET["office"]);
	$functions = new Merch_Stock_Functions();
	$admin_functions = new Merch_Stock_WP_Admin();
	$user_functions = new Merch_Stock_User_Functions();
	$order_functions = new Merch_Stock_Order_Functions();
	$current_role = $user_functions->getMerchStockRoleName();
	$offices = $user_functions->getUserOffices();
	$ajaxURL = esc_url( admin_url("admin-post.php") );
	$customers = json_decode($admin_functions->getCustomers(),true);
	$customer_id = 0;
	$status = 'status';
	if (isset($_GET["customer_id"])){		
		$customer_id = intval($_GET["customer_id"]);
	}
	if (isset($_GET["status"])){
		$status = sanitize_text_field($_GET["status"]);	
	}
	$product_requests = json_decode($admin_functions->getProductRequests($customer_id, $status), TRUE);
	$baseUrl = esc_url(admin_url( 'admin.php?page=product_requests' ));
?>
<div class='wrap wilson'>
	<h1 class='wp-heading-inline'>Product requests</h1>
	<div class="button-bar">
		<button id="select_all_product_requests" class="button"><i class="fa fa-check-square"></i>(De)select all</button>	
		<form action="<?php echo esc_url( admin_url('admin-post.php') ); ?>" method="POST">
			<input type="hidden" name="action" value="delete_wilson_product_requests">
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
					$tmp_customer_id = intval($customer['ID']);
					$customerName = esc_html(get_the_title( $tmp_customer_id ));
					// $customerID = intval($tmp_customer_id);
					if ($customer_id==$tmp_customer_id){
						echo "<option selected='selected' value=$customerID>".$customerName."</option>";	
					}
					else{
						echo "<option value=$customerID>".$customerName."</option>";
					}
				}
			?>
		</select>
		<select class='filter' data-filter_item='status'>
			<option value='status'>All statuses</option>
			<?php 	
				if ($status=="pending"){
					echo "<option selected='selected' value='pending'>Pending</option>";
				}
				else{
					echo "<option value='pending'>Pending</option>";
				}			
				if ($status=="accepted"){
					echo "<option selected='selected' value='accepted'>Accepted</option>";
				}
				else{
					echo "<option value='accepted'>Accepted</option>";
				}
				if ($status=="refused"){
					echo "<option selected='selected' value='refused'>Refused</option>";
				}
				else{
					echo "<option value='refused'>Refused</option>";
				}
			?>
		</select>		
	</div>
	<table class='wp-list-table widefat fixed striped posts dataTable'>
		<thead>	
			<tr>
				<th class="th-select">Select</th>
				<th class="th-id">ID</th>
				<th>Customer</th>
				<th>User</th>	
				<th>Status</th>								
				<th>Request</th>			
				
				<th class="table-right">Actions</th>
			</tr>
		</thead>			
		<tbody>	
			<?php 	
				foreach ($product_requests as $request){	
					$request_id = intval($request['ID']);		
					$user_id = intval(get_post_meta( $request_id, 'user_id', true ));		
					$user = get_user_by( 'ID', $user_id );
					$customer_id = intval(get_user_meta( $user->ID, 'customer_id', true ));
					// $status = get_post_meta( $request["ID"], 'status', true );
					$request_message = get_post_meta( $request_id, 'message', true );
					$actions = '';
					$actions .= "<a href='" . esc_url(admin_url( 'admin.php?page=admin_product_request&product_request_id='.$request_id )) .  "' class='on-default edit-row'><button type='button' class='button mb-1 mt-1 mr-1 btn btn-default'>View</button></i></a>";	
					$output = '';
					$output .= '<tr class="iedit author-other level-0 alternate">';
					$output .= '<td class="">';
					$output .= '<input data-product_request_id="'.$request_id.'" class="selected_product_requests" name="selected_product_requests['.$request_id.']" type="checkbox"></input>';
					$output .= '</td>';	
					$output .= '<td class="">';
					$output .= '<a href="'.esc_url(admin_url( 'admin.php?page=admin_product_request&product_request_id='.$request_id  )) .'">' . $request_id . "</a>";
					$output .= '</td>';
					$output .= '<td class="">';
					$output .= '<a href="'.esc_url(admin_url( 'admin.php?page=admin_customer&customer_id='.$customer_id  )) .'">' . esc_html(get_the_title( $customer_id  )) . "</a>";
					$output .= '</td>';					
					$output .= '<td class="">';
					$output .= esc_html($user->user_firstname) . ' ' . esc_html($user->user_lastname);
					$output .= '</td>';	
					$output .= '<td class="">';
					$output .= $functions->getStatusBubble(get_post_meta( $request_id, 'status', true ));
					$output .= '</td>';											
					$output .= '<td class="">';
					$output .= esc_html($request_message);
					$output .= '</td>';						
					$output .= '<td class="table-right">';
					$output .= $actions;
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
				<th>Request</th>						
				<th class="table-right">Actions</th>
			</tr>
		</tfoot>		
	</table>
</div>
