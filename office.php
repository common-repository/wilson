<?php 
	$functions = new Merch_Stock_Functions();
	$user_functions = new Merch_Stock_User_Functions();
	$order_functions = new Merch_Stock_Order_Functions();
	$office_id = intval($_GET['office_id']);
	$office_orders = json_decode($functions->getOrders(-1, null, $office_id));
	$office = get_post($office_id);
	$shipping_box_price = get_post_meta( $office_id, 'shipping_box_price', true );
	$current_role = $user_functions->getMerchStockRole();
	// var_dump($office_orders);
?>
<header class="page-header col-lg-12">
	<h2>
		<?php 	
			echo esc_html(get_the_title($office_id));
		?>
	</h2>
	
	<div class="right-wrapper text-right">
		<ol class="breadcrumbs">
			<li>
				<a href="admin.php?page=Dashboard">
					<i class="fa fa-home"></i>
				</a>
			</li>
			<li><a href="admin.php?page=offices"><span>Offices</span></a></li>
			<li><span><?php echo esc_html(get_the_title($office_id)) ?></span></li>
		</ol>		
	</div>
</header>
<section role="main" class="content-body">	
<div class="plugin-content col-lg-12 nopadding nomargin card">
		<div class="card-actions">
		</div>	
		<header class="card-header">
			<h2 class="card-title">Orders</h2>
		</header>
		<div class="card-body">
			<table class="table table-bordered table-striped mb-0 dataTable no-footer" id="datatable-default">
				<thead>
					<tr>
						<th>ID</th>					
						<th>Status</th>
						<th>User</th>
						<th>Office</th>
						<th>Actions</th>
					</tr>
				</thead>			
			<?php 	
				foreach ($office_orders as $key => $order) {
					$order_id = intval($order->ID);
					$custom = get_post_custom($order_id);
					$actions = "";
					$actions .= "<a href='" . esc_url(admin_url( 'admin.php?page=order&order_id='.$order_id )) .  "' class='on-default edit-row'><button type='button' class='mb-1 mt-1 mr-1 btn btn-default'>View</button></i></a>"; 
					$actions .= "<a href='" . esc_url(admin_url( 'admin.php?page=invoice&order_id='.$order_id )) .  "' class='on-default edit-row'><button type='button' class='mb-1 mt-1 mr-1 btn btn-default'>Invoice</button></a>  ";	
					$output = "<tr>";
					$output .= "<td>";
					$output .= $order_id;
					$output .= "</td>";
					$output .= "<td>";
					$output .= $functions->getStatusBubble($order_functions->getOrderStatus($order_id));
					$output .= "</td>";
					$output .= "<td>";
					$output .= esc_html($order_functions->getOrderUser($order_id));
					$output .= "</td>";
					$output .= "<td>";
					$output .= esc_html($order_functions->getOrderOffice($order_id));
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
<?php
	if ($current_role=="ms_admin"){
		?>
			<form action="<?php echo esc_url( admin_url('admin-post.php') ); ?>" method="POST">
		<?php
	}
?>
<div class="plugin-content col-lg-12 nopadding nomargin card">
		<div class="card-actions">
		</div>	
	<header class="card-header">
		<h2 class="card-title">Shipping</h2>
	</header>
		<div class="card-body">
			<div class="form-group row">
				<label class="col-lg-3 control-label text-lg-right pt-2" for="Street">Shipping Costs</label>
				<div class="col-lg-6">
					<label class="col-lg-9 control-label text-lg-left pt-2"><?php echo esc_html($functions->formatMoney($shipping_box_price)) ?></label>					
				</div>
			</div>
		</div>		
	
</div>
<div class="plugin-content col-lg-12 nopadding nomargin card">	
	<?php 	
		if ($current_role=='ms_admin'){
			$template = plugin_dir_path( __FILE__ ) . 'address-edit.php';
		}
		else{
			$template = plugin_dir_path( __FILE__ ) . 'address.php';
		}		
		echo esc_html(load_template( $template, true ));					
	?>
</div>
<?php
	if ($current_role=="ms_admin"){
		echo esc_html(intval($office_id));
		?>
			<input type="hidden" name="office_id" value="<?php echo esc_html(intval($office_id)) ?>">
			<input type="hidden" name="action" value="update_office">	
	
			<button  type="submit" id="addToTable" class="btn btn-primary">Save office</i></button>
			</form>
		<?php
	}
?>
</section>