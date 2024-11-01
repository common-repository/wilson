<?php 
	$functions = new Merch_Stock_Functions();
	$user_functions = new Merch_Stock_User_Functions();
	$notification_functions = new Merch_Stock_Notifications();	
	$current_role = $user_functions->getMerchStockRoleName();	
	$status = sanitize_text_field($_GET["status"]);
	if (strlen($status)>0){
		$product_requests = json_decode($functions->getProductRequests($status), true);
	}
	else{
		$product_requests = json_decode($functions->getProductRequests(), true);
	}
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
				<li><span>Product requests</span></li>
			</ol>			
	</div>
</header>	
<section role="main" class="content-body">	
<div class="plugin-content col-lg-12 nopadding nomargin card">
	<header class="card-header">
		<div class="card-actions">
		</div>				
		<h2 class="card-title">Product Requests</h2>
	</header>
	<div class="col-lg-12 filter">
		<div class=" row">
			<div class="col-lg-2">
				<i class="fa fa-filter" style="font-size: 32px;"></i><span class='filter-results'>Filter your results:</span>
			</div>
			<div class="col-lg-10"> 
				<select data-filterkey="status" class="form-control filter-item">
					<option value="all">All statuses</option>
					<?php 			
					if ($status=='accepted'){
						echo '<option selected="selected" value="accepted">Accepted</option>';
					}
					else{
						echo '<option value="accepted">Accepted</option>';	
					}
					if ($status=='refused'){
						echo '<option selected="selected" value="refused">Refused</option>';
					}
					else{
						echo '<option value="refused">Refused</option>';	
					}
					if ($status=='pending'){
						echo '<option selected="selected" value="pending">Pending</option>';
					}
					else{
						echo '<option value="pending">Pending</option>';	
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
					<th>Status</th>		
					<th>Message</th>	
					<th>Actions</th>			
				</tr>
			</thead>
			<tbody>
			<?php 	
				foreach ($product_requests as $key => $product_request) {	
					$product_request_id = intval($product_request["ID"]);
					$actions = "";
					$actions .= "<a href='" . admin_url( 'admin.php?page=product_request&product_request_id='.$product_request_id ) .  "' class='on-default edit-row'><button type='button' class='mb-1 mt-1 mr-1 btn btn-default'>View</button></i></a>";				
					$output = "";
					$output .= "<tr>";
					$output .= "<td>";
					$output .= intval($product_request_id);
					$output .= "</td>";
					$output .= "<td>";
					$output .= $functions->getStatusBubble(get_post_meta( $product_request_id, 'status', true ));
					$output .= "</td>";
					$output .= "<td>";
					$output .= esc_html(get_post_meta( $product_request_id, 'message', true ));
					$output .= "</td>";										
					$output .= "<td class='actions'>";										
					$output .= $actions;
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
