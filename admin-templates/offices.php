<?php 
	$functions = new Merch_Stock_Functions();
	$admin_functions = new Merch_Stock_WP_Admin();
	$user_functions = new Merch_Stock_User_Functions();
	$order_functions = new Merch_Stock_Order_Functions();
	$status = sanitize_text_field($_GET["status"] );
	$office_get = sanitize_text_field($_GET["office"] );
	$customer_id = intval($_GET["customer_id"]);
	if (isset($customer_id) && $customer_id>0){
		$offices = json_decode($admin_functions->getOffices($customer_id));	
	}
	else{
		$offices = json_decode($admin_functions->getOffices());
	}
	$current_role = $user_functions->getMerchStockRoleName();
	$ajaxURL = esc_url( admin_url("admin-post.php") );
	$customers = json_decode($admin_functions->getCustomers(),true);
	$baseUrl = esc_url(admin_url( 'admin.php?page=offices' ));
?>
<div class='wrap wilson'>
	<h1 class='wp-heading-inline'>Offices</h1>
	<div class="button-bar">
		<button id="select_all_offices" class="button"><i class="fa fa-check-square"></i>(De)select all</button>
		<form action="<?php echo esc_url( admin_url('admin-post.php') ); ?>" method="POST">
			<input type="hidden" name="action" value="delete_wilson_offices">
			<input type="hidden" class="ids" name="ids">
			<button class="button"><i class="fa fa-trash"></i>Delete selected</button>		
		</form>	
		<button class="button"><a href="<?php echo esc_url(admin_url( 'admin.php?page=new_office' )) ?>" class="page-title-action"><i class="fa fa-plus"></i>Add New</a></button>
	</div>
	<div class='filters'>
		<label>Filter</label>
		<button type="button" class="button reset-filter" data-baseurl="<?php echo esc_url($baseUrl) ?>"><i class="fa fa-redo"></i></button>
		<select class='filter' data-filter_item='customer_id'>
			<option value=0>Select Customer</option>
			<?php 
				foreach ($customers as $customer){
					$customerName = get_the_title( $customer['ID'] );
					$customer_id2 =  $customer['ID'];
					if ($customer_id==$customer_id2){
						echo "<option selected='selected' value=$customerID>".esc_html($customerName)."</option>";	
					}
					else{
						echo "<option value=$customerID>".esc_html($customerName)."</option>";
					}
					
				}
			?>
		</select>
	</div>
	<table class='wp-list-table widefat fixed striped posts dataTable'>
		<thead>	
			<tr>
				<th class="th-select"></th>
				<th class="th-id">ID</th>
				<th>Office</th>
				<th>Customer</th>
				<th class="table-right">Actions</th>
			</tr>
		</thead>
		<tbody>	
			<?php 	
				foreach ($offices as $office){		
					$office_id = intval($office->ID);
					$customer_id = intval(get_post_meta( $office_id, 'customer_id', true ));			
					$actions = '';
					$actions .= "<a href='" . admin_url( 'admin.php?page=admin_office&office_id='.$office_id ) .  "' class='on-default edit-row'><button type='button' class='button mb-1 mt-1 mr-1 btn btn-default'>View</button></i></a>";		
					$output = '';
					$output .= '<tr class="iedit author-other level-0 alternate">';
					$output .= '<td class="">';
					$output .= '<input data-office_id="'.$office_id.'" class="selected_offices" name="selected_offices['.$office_id.']" type="checkbox"></input>';
					$output .= '</td>';						
					$output .= '<td class="">';
					$output .= '<a href="'.admin_url( 'admin.php?page=admin_office&office_id='.$office_id ) .'">' . $office_id . "</a>";
					$output .= '</td>';
					$output .= '<td class="">';
					$output .= '<a href="'.admin_url( 'admin.php?page=admin_office&office_id='.$office_id  ) .'">' . esc_html(get_the_title( $office_id  )) . "</a>";
					$output .= '</td>';	
					$output .= '<td class="">';
					$output .= '<a href="'.admin_url( 'admin.php?page=admin_customer&customer_id='.$customer_id  ) .'">' . esc_html(get_the_title( $customer_id  )) . "</a>";
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
				<th></th>
				<th>ID</th>
				<th>Customer</th>
				<th>Office</th>
				<th style="text-align: right;">Actions</th>
			</tr>
		</tfoot>		
	</table>
</div>
