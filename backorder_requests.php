<?php 
	$functions = new Merch_Stock_Functions();
	$user_functions = new Merch_Stock_User_Functions();
	$notification_functions = new Merch_Stock_Notifications();
	$backorder_requests = json_decode($functions->getBackorderRequests(), true);
	$current_role = $user_functions->getMerchStockRoleName();	
?>
<header class="page-header col-lg-12">
	<h2>Backorder Requests</h2>

	<div class="right-wrapper text-right">
			<ol class="breadcrumbs">
				<li>
					<a href="admin.php?page=Dashboard">
						<i class="fa fa-home"></i>
					</a>
				</li>
				<li><span>Backorder requests</span></li>
			</ol>			
	</div>
</header>	
<section role="main" class="content-body">	
<div class="plugin-content col-lg-12 nopadding nomargin card">
	<header class="card-header">
		<div class="card-actions">
		</div>				
		<h2 class="card-title">Backorder Requests</h2>
	</header>	
	<div class="col-lg-12">
		<table class="table table-bordered table-striped mb-0 dataTable no-footer" id="datatable-default">
				<thead>
					<tr>
						<th>ID</th>					
						<th>Status</th>		
						<th>Product</th>	
						<th>User</th>
						<th>Actions</th>					
					</tr>
				</thead>
				<tbody>
				<?php 	
					foreach ($backorder_requests as $key => $backorder_request) {		
						$backorder_request_custom = get_post_custom( $backorder_request["ID"] );		
						$product_id = $backorder_request_custom["product_id"][0];
						$stockline_id = $backorder_request_custom["stockline_id"][0];
						$product = get_post($product_id);
						$stockline = get_post($stockline_id);
						$status_name = $backorder_request_custom["status"][0];
						$status = $functions->getStatusBubble($backorder_request_custom["status"][0]);
						$actions = "";
						$output = "";
						$output .= "<tr>";
						$output .= "<td>";
						$output .= $backorder_request["ID"];
						$output .= "</td>";
						$output .= "<td>";
						$output .= $status;
						$output .= "</td>";	
						$output .= "<td>";
						$output .= get_the_title( $product->ID ) . " " . get_post_meta( $stockline->ID, 'description', true );
						$output .= "</td>";								
						$output .= "<td>";
						$output .= $user_functions->getBackorderRequestUser($backorder_request["ID"]);
						$output .= "</td>";			
						$output .= "<td>";							
						$output .= "<a href='".esc_attr(admin_url( 'admin.php?page=backorder_request&backorder_request_id='.$backorder_request["ID"] ))."'><button type='button' class='mb-1 mt-1 mr-1 btn btn-default'>View</button></a>";
						if ( ($status_name=="pending"||$status_name=="Pending") && $current_role=="headoffice"){
							$output .= $actions;
						}
						$output .= "</td>";				
						$output .= "</tr>";
						echo $output;
					}
				?>
				</tbody>
			</table>
		</div>
	</div>
</section>


		
