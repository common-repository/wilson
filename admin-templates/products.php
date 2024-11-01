<?php 	
	$status = sanitize_text_field($_GET["status"] );
	$office_get = sanitize_text_field($_GET["office"]);
	$functions = new Merch_Stock_Functions();
	$admin_functions = new Merch_Stock_WP_Admin();
	$user_functions = new Merch_Stock_User_Functions();
	$order_functions = new Merch_Stock_Order_Functions();	
	$current_role = $user_functions->getMerchStockRoleName();
	$ajaxURL = esc_url( admin_url("admin-post.php") );
	$customer_id = intval($_GET["customer_id"]);
	if (isset($customer_id) && $customer_id>0){
		$products = json_decode($admin_functions->getProducts($customer_id),true);	
	}
	else{
		$products = json_decode($admin_functions->getProducts(),true);	
	}
		
	$customers = json_decode($admin_functions->getCustomers(),true);
	$baseUrl = esc_url(admin_url( 'admin.php?page=products' ));
?>
<div class='wrap wilson'>
	<h1 class='wp-heading-inline'>Products</h1>
	<div class="button-bar">
		<button id="select_all_products" class="button"><i class="fa fa-check-square"></i>(De)select all</button>	
		<form action="<?php echo esc_url( admin_url('admin-post.php') ); ?>" method="POST">
			<input type="hidden" name="action" value="delete_wilson_products">
			<input type="hidden" class="ids" name="ids">
			<button class="button"><i class="fa fa-trash"></i>Delete selected</button>		
		</form>		
		<button class="button"><a href="<?php echo esc_url(admin_url( 'admin.php?page=new_product' )) ?>" class="page-title-action"><i class="fa fa-plus"></i>Add New</a></button>
	</div>
	<div class='filters'>
		<label>Filter</label>
		<button type="button" class="button reset-filter" data-baseurl="<?php echo $baseUrl ?>"><i class="fa fa-redo"></i></button>
		<select class='filter' data-filter_item='customer_id'>
			<option value=0>All customers</option>
			<?php 
				foreach ($customers as $customer){
					$tmp_customer_id = intval($customer['ID']);
					$customerName = esc_html(get_the_title( $tmp_customer_id  ));
	
					if ($customer_id==$tmp_customer_id){
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
				<th class="th-select"></th>
				<th class="th-id">ID</th>
				<th>Product</th>
				<th>Image</th>
				<th>Customer</th>
				<th>Variations</th>
				<th style="text-align: right;">Actions</th>
			</tr>
		</thead>			
		<tbody>	
			<?php 	
				foreach ($products as $product){		
					$product_id = intval($product['ID']);
					$customer_id = intval(get_post_meta( $product_id, 'customer_id', true ));
					$actions = '';
					$actions .= "<a href='" . esc_url(admin_url( 'admin.php?page=admin_product&product_id='.$product_id )) .  "' class='button on-default edit-row'>View</a>";		
					$actions .= "<a href='" . esc_url(admin_url( 'admin.php?page=admin_product_edit&product_id='.$product_id )) .  "' class='button on-default edit-row'>Edit</a>";		
					$output = '';
					$output .= '<tr class="iedit author-other level-0 alternate">';
					$output .= '<td class="">';
					$output .= '<input data-product_id="'.$product_id.'" class="selected_products" name="selected_products['.$product_id.']" type="checkbox"></input>';
					$output .= '</td>';			
					$output .= '<td class="">';
					$output .=  '<a  href="'.esc_url(admin_url( 'admin.php?page=admin_product&product_id='.$product_id )) .'">' .   $product_id . '</a>';
					$output .= '</td>';
					$output .= '<td class="">';
					$output .=  '<a  href="'.esc_url(admin_url( 'admin.php?page=admin_product&product_id='.$product_id )) .'">' .  esc_html(get_the_title( $product_id )) . '</a>';
					$output .= '</td>';							
					$output .= '<td class="">';
					$url = esc_url(get_the_post_thumbnail_url($product_id));
					$output .= '<img class="list-thumbnail" src='.$url.'>';					
					$output .= '</td>';						
					$output .= '<td class="">';
					$output .= '<a href="'.esc_url(admin_url( 'admin.php?page=admin_customer&customer_id='.$customer_id  )) .'">' . esc_html(get_the_title( $customer_id  )) . "</a>";
					$output .= '</td>';	
					$output .= '<td class="">';
					$variations_output = "";
					$variations = $functions->getStockLines($product_id);
					foreach ($variations as $key => $value) {	
						$value_id = intval($value->ID);
						$custom_stockline = get_post_custom( $value_id  );
						$variations_output .= $custom_stockline['description'][0] . ",&nbsp;";
					}
					$output .=  esc_html(substr($variations_output, 0, strlen($variations_output)-7));
					$output .= '</td>';																						
					$output .= '<td style="text-align: right;">';
					$output .= $actions;
					$output .= '</td>';																																		
					$output .= '</tr>';
					echo $output;
				}
			?>
		</tbody>
		<tfoot>	
			<tr>
				<th>	</th>
				<th>ID</th>
				<th>Product</th>
				<th>Image</th>
				<th>Customer</th>
				<th>Variations</th>
				<th style="text-align: right;">Actions</th>
			</tr>
		</tfoot>		
	</table>
</div>
