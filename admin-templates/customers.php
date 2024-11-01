<?php 
	$status = sanitize_text_field($_GET["status"] );
	$office_get = sanitize_text_field($_GET["office"] );
	$functions = new Merch_Stock_Functions();
	$admin_functions = new Merch_Stock_WP_Admin();
	$user_functions = new Merch_Stock_User_Functions();
	$order_functions = new Merch_Stock_Order_Functions();	
	$current_role = $user_functions->getMerchStockRoleName();
	$ajaxURL = esc_url( admin_url("admin-post.php") );
	$customers = json_decode($admin_functions->getCustomers(),true);
?>
<div class='wrap wilson'>
	<h1 class='wp-heading-inline'>Customers</h1>
	<div class="button-bar">
		<button id="select_all_customers" class="button"><i class="fa fa-check-square"></i>(De)select all</button>	
		<form action="<?php echo esc_url( admin_url('admin-post.php') ); ?>" method="POST">
			<input type="hidden" name="action" value="delete_wilson_customers">
			<input type="hidden" class="ids" name="ids">
			<button class="button"><i class="fa fa-trash"></i>Delete selected</button>		
		</form>		
		<button class="button"><a href="<?php echo esc_url(admin_url( 'admin.php?page=new_customer' )) ?>" class="page-title-action"><i class="fa fa-plus"></i>Add New</a></button>
	</div>
	<div class='filters'>
		<label>&nbsp;</label>
	</div>
	<table class='wp-list-table widefat fixed striped posts dataTable'>
		<thead>	
			<tr>
				<th class="th-select">Select</th>
				<th class="th-id">ID</th>
				<th>Customer</th>
				<th>Image</th>
				<th class="table-right">Actions</th>
			</tr>
		</thead>			
		<tbody>	
			<?php 	
				foreach ($customers as $customer){		
					$customer_id = intval($customer['ID']);
					$actions = '';
					$actions .= "<a href='" . esc_url(admin_url( 'admin.php?page=admin_customer&customer_id='.$customer_id )) .  "' class='on-default edit-row'><button type='button' class='button mb-1 mt-1 mr-1 btn btn-default'>View</button></i></a>";	
					$actions .= "<a href='" . esc_url(admin_url( 'admin.php?page=admin_customer_edit&customer_id='.$customer_id )) .  "' class='on-default edit-row'><button type='button' class='button mb-1 mt-1 mr-1 btn btn-default'>Edit</button></i></a>";							
					$output = '';
					$output .= '<tr class="iedit author-other level-0 alternate">';
					$output .= '<td class="">';
					$output .= '<input data-customer_id="'.$customer_id.'" class="selected_customers" name="selected_customers['.$customer_id.']" type="checkbox"></input>';
					$output .= '</td>';						
					$output .= '<td class="">';
					$output .= '<a href="'.esc_url(admin_url( 'admin.php?page=admin_customer&customer_id='.$customer_id  )) .'">' . $customer_id . "</a>";
					$output .= '</td>';
					$output .= '<td class="">';
					$output .= '<a href="'.esc_url(admin_url( 'admin.php?page=admin_customer&customer_id='.$customer_id  )) .'">' . esc_html(get_the_title( $customer_id )) . "</a>";
					$output .= '</td>';				
						
					$output .= '<td class="">';
					$url = esc_url(get_the_post_thumbnail_url($customer_id));
					$output .= '<img class="list-thumbnail" src='.$url.'>';		
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
				<th>Select</th>
				<th>ID</th>
				<th>Customer</th>
				<th>Image</th>
				<th class="table-right">Actions</th>
			</tr>
		</tfoot>
	</table>
</div>
