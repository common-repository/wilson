<?php 	
	$admin_functions = new Merch_Stock_WP_Admin();
	$functions = new Merch_Stock_Functions();
	$order_functions = new Merch_Stock_Order_Functions();
	$office_id = intval($_GET['office_id']);
	$customer_id = intval(get_post_meta( $office_id, 'customer_id', true ));
	$orders = json_decode($functions->getOrders(-1, null, $office_id));
	$meta = get_post_custom( $office_id );
?>
<div class="wrap order wilson">	
	<h1 class='wp-heading-inline'>Office overview - <?php echo esc_html(get_the_title($office_id)) ?></h1>
		<div class="button-bar">
		<form action="<?php echo esc_url( admin_url('admin-post.php') ); ?>" method="POST">
			<input type="hidden" name="action" value="delete_wilson_products">
			<input type="hidden" class="ids" name="ids">
			<button class="button"><i class="fa fa-trash"></i>Delete</button>		
		</form>		
		<button class="button"><a href="<?php echo esc_url(admin_url( 'admin.php?page=admin_office_edit&office_id='.$office_id )) ?>" class="page-title-action"><i class="fa fa-edit"></i>Edit office</a></button>
		<button class="button"><a href="<?php echo esc_url(admin_url( 'admin.php?page=new_office' )) ?>" class="page-title-action"><i class="fa fa-plus"></i>Add New</a></button>		
		
	</div>
	<div class='filters'>
		<label>&nbsp;</label>
	</div>	
	<form action="<?php echo esc_url( admin_url('admin-post.php') ); ?>" method="POST">	
				<div class="content office">
					<h5>
						<span>
							Orders
						</span>
					</h5>
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
					$shipping_costs = json_decode($order_functions->getOrderShippingCostsV2($order->ID, 0, 0, 0,true),true);
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
					// $output .= get_the_title( $customer_id );
					$output .= '<a href="'.esc_url(admin_url( 'admin.php?page=admin_customer&customer_id='.$customer_id  )) .'">' . esc_html(get_the_title( $customer_id  )) . "</a>";
					$output .= '</td>';	
					$output .= '<td class="">';
					// $output .= get_the_title( $office_id );
					$output .= '<a href="'.esc_url(admin_url( 'admin.php?page=admin_office&office_id='.$office_id  )) .'">' . esc_html(get_the_title( $office_id  )) . "</a>";
					$output .= '</td>';			
					$output .= '<td class="">';
					$output .= esc_html($user->user_firstname) . ' ' . esc_html($user->user_lastname);
					$output .= '</td>';
					$output .= '<td class="">';
					$output .= $functions->getStatusBubble(get_post_meta( $order->ID, 'status', true ));
					$output .= '</td>';			
					$output .= '<td class="">';
					$output .= esc_html(get_post_meta( $order->ID, 'status_changed', true ));
					$output .= '</td>';	
					$output .= '<td class="">';
					$output .= $shipping_costs[4] . ' / ' . $shipping_costs[1] . ' unit(s)';
					$output .= '</td>';		
					$output .= '<td class="">';
					$output .= $functions->formatMoney($order_functions->getOrderTotalPrice($order->ID));
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
	<div class="content backorders">
		<h5>
			<span>
				Shipping
			</span>
		</h5>
		<div>	
			<label>Shipping costs per box</label>
			<label>€<?php echo esc_html($meta["shipping_box_price"][0]) ?></label>
		</div>
		<div>	
			<label>Shipping box weight</label>
			<label>€<?php echo esc_html($meta["shipping_box_weight"][0]) ?></label>
		</div>		
	</div>
	<div class="content backorders">
		<h5>
			<span>
				General
			</span>
		</h5>
		<div>	
			<label>ID</label>
			<label><?php echo $office_id ?></label>
		</div>
		<div>	
			<label>Customer</label>
			<label>
				<?php 	
					echo '<a href="'.esc_url(admin_url( 'admin.php?page=admin_customer&customer_id='.$customer_id  )) .'">' . esc_html(get_the_title( $customer_id  )) . "</a>";					
				?>
			</label>		
		</div>
	</div>
	<div class="content backorders">
		<h5>
			<span>
				Office address
			</span>
		</h5>
		<div>	
			<label>Addressline #1</label>
			<label><?php echo esc_html($meta["addressline1"][0]) ?></label>
		</div>
		<div>	
			<label>Addressline #2</label>
			<label><?php echo esc_html($meta["addressline2"][0]) ?></label>
		</div>
		<div>	
			<label>Addressline #3</label>
			<label><?php echo esc_html($meta["addressline3"][0]) ?></label>
		</div>
		<div>	
			<label>Postal code</label>
			<label><?php echo esc_html($meta["postal_code"][0]) ?></label>
		</div>
		<div>	
			<label>City</label>
			<label><?php echo esc_html($meta["city"][0]) ?></label>
		</div>
		<div>	
			<label>Region</label>
			<label><?php echo esc_html($meta["region"][0]) ?></label>
		</div>
		<div>	
			<label>County</label>
			<label><?php echo esc_html($meta["county"][0]) ?></label>
		</div>
		<div>	
			<label>Country</label>
			<label><?php echo esc_html($meta["country"][0]) ?></label>
		</div>														
	</div>